<?php
include("../common/access.php");
include("../common/autoload.php");

$data['breadcrumb'] = array("Employees"=>"employee-list", "Attendance & work reports" => "");

$departments = array("Engineering", "Content", "Tutoring", "Support", "Marketing");

function buildQS($overrides = array())
{
	$params = array_merge($_GET, $overrides);
	foreach ($params as $k => $v) {
		if ($v === '' || $v === null) unset($params[$k]);
	}
	return http_build_query($params);
}

$monthParam = isset($_GET['month']) && preg_match('/^\d{4}-\d{2}$/', $_GET['month']) ? $_GET['month'] : date('Y-m');
list($year, $monthNum) = array_map('intval', explode('-', $monthParam));
if ($monthNum < 1 || $monthNum > 12) $monthParam = date('Y-m');
$monthStart = date('Y-m-01', strtotime($monthParam . '-01'));
$monthEnd   = date('Y-m-t', strtotime($monthParam . '-01'));

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$dept   = isset($_GET['dept']) && in_array($_GET['dept'], $departments, true) ? $_GET['dept'] : '';

$focusId = isset($_GET['employee_id']) ? (int) $_GET['employee_id'] : 0;

$empWhereParts = array();
if ($dept !== '') $empWhereParts[] = "department = '" . mysqli_real_escape_string($configs['con'], $dept) . "'";
if ($search !== '') {
	$esc = mysqli_real_escape_string($configs['con'], $search);
	$empWhereParts[] = "(full_name like '%$esc%' or employee_code like '%$esc%')";
}
if ($focusId) $empWhereParts[] = "id = " . $focusId;
$empWhereSql = count($empWhereParts) ? implode(" and ", $empWhereParts) : "1=1";

$employees = array();
foreach (getQuery("employee", "id, employee_code, full_name, department", $empWhereSql, "full_name asc") as $row) {
	$employees[(int) $row['id']] = array(
		'id'      => (int) $row['id'],
		'code'    => $row['employee_code'] ?: ('#' . $row['id']),
		'name'    => $row['full_name'],
		'dept'    => $row['department'],
		'present' => 0,
		'absent'  => 0,
		'late'    => 0,
		'leave'   => 0,
		'reports' => 0,
		'days'    => array(),
	);
}

if (count($employees)) {
	$idsSql = implode(",", array_map('intval', array_keys($employees)));
	$attRes = mysqli_query(
		$configs['con'],
		"select employee_id, attendance_date, check_in, check_out, status, work_report from "
			. $configs['prev'] . "employee_attendance where employee_id in ($idsSql) and attendance_date between '$monthStart' and '$monthEnd' order by attendance_date asc"
	);
	while ($a = mysqli_fetch_assoc($attRes)) {
		$eid = (int) $a['employee_id'];
		if (!isset($employees[$eid])) continue;

		$hasReport = trim((string) $a['work_report']) !== '';

		$employees[$eid]['days'][] = array(
			'd'      => date('M j', strtotime($a['attendance_date'])),
			'dow'    => strtoupper(date('D', strtotime($a['attendance_date']))),
			'status' => $a['status'],
			'in'     => $a['check_in'] ? date('g:i A', strtotime($a['check_in'])) : '—',
			'out'    => $a['check_out'] ? date('g:i A', strtotime($a['check_out'])) : '—',
			'report' => $a['work_report'] ?: '',
		);

		if (isset($employees[$eid][$a['status']])) $employees[$eid][$a['status']]++;
		if ($hasReport) $employees[$eid]['reports']++;
	}
}

foreach ($employees as $eid => $e) {
	$total = $e['present'] + $e['absent'] + $e['late'] + $e['leave'];
	$employees[$eid]['rate']     = $total ? round((($e['present'] + $e['late']) / $total) * 100) : 0;
	$employees[$eid]['expected'] = $e['present'] + $e['late'];
}

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="attendance-' . $monthParam . '.csv"');
	$out = fopen('php://output', 'w');
	fputcsv($out, array('Employee', 'Employee ID', 'Department', 'Present', 'Absent', 'Late', 'On leave', 'Attendance rate', 'Reports filed', 'Reports expected'));
	foreach ($employees as $e) {
		fputcsv($out, array($e['name'], $e['code'], $e['dept'], $e['present'], $e['absent'], $e['late'], $e['leave'], $e['rate'] . '%', $e['reports'], $e['expected']));
	}
	fclose($out);
	exit;
}

$teamPresent = $teamAbsent = $teamLate = $teamLeave = $teamReports = $teamExpected = 0;
$attendanceDatesSet = array();
foreach ($employees as $e) {
	$teamPresent  += $e['present'];
	$teamAbsent   += $e['absent'];
	$teamLate     += $e['late'];
	$teamLeave    += $e['leave'];
	$teamReports  += $e['reports'];
	$teamExpected += $e['expected'];
	foreach ($e['days'] as $d) $attendanceDatesSet[$d['d']] = true;
}
$teamTotalMarked = $teamPresent + $teamAbsent + $teamLate + $teamLeave;
$distinctDays     = count($attendanceDatesSet);

$data['stat_rate']            = $teamTotalMarked ? round((($teamPresent + $teamLate) / $teamTotalMarked) * 100, 1) : 0;
$data['stat_avg_present']     = $distinctDays ? round($teamPresent / $distinctDays) : 0;
$data['stat_late']            = $teamLate;
$data['stat_missing_reports'] = max(0, $teamExpected - $teamReports);

$data['employees']   = array_values($employees);
$data['departments'] = $departments;
$data['search']      = $search;
$data['dept']        = $dept;
$data['month']       = $monthParam;
$data['monthLabel']  = date('F Y', strtotime($monthStart));
$data['prevMonth']   = date('Y-m', strtotime($monthStart . ' -1 month'));
$data['nextMonth']   = date('Y-m', strtotime($monthStart . ' +1 month'));
$data['autoOpenCode'] = ($focusId && isset($employees[$focusId])) ? $employees[$focusId]['code'] : '';

echo template($data);
