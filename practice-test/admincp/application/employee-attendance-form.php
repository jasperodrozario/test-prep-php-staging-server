<?php
include("../common/access.php");
include("../common/autoload.php");

define('ATTENDANCE_LATE_AFTER', '09:30:00');

$data['breadcrumb'] = array("My Attendance" => "");

$employee_id = isset($_SESSION['employee_id']) ? (int) $_SESSION['employee_id'] : 0;
$employee    = $employee_id ? getDataByWhere("employee", "*", array("id" => $employee_id)) : null;

if (!$employee) $employee_id = 0;

$today = date('Y-m-d');
$now   = date('H:i:s');

if ($employee_id && $_SERVER['REQUEST_METHOD'] === 'POST') {

	$todayRow = getDataByWhere("employee_attendance", "*", array("employee_id" => $employee_id, "attendance_date" => $today));

	if (isset($_POST['clock_action']) && $_POST['clock_action'] === 'check_in' && !$todayRow) {
		$status = ($now <= ATTENDANCE_LATE_AFTER) ? 'present' : 'late';
		insertTable("employee_attendance", array(
			"employee_id"     => $employee_id,
			"attendance_date" => $today,
			"check_in"        => $now,
			"status"          => $status,
		));
	} elseif (isset($_POST['clock_action']) && $_POST['clock_action'] === 'check_out' && $todayRow && !$todayRow['check_out']) {
		updateTable("employee_attendance", array("check_out" => $now), array("id" => (int) $todayRow['id']));
	} elseif (isset($_POST['save_report']) && $todayRow) {
		$report = mysqli_real_escape_string($configs['con'], trim($_POST['work_report']));
		updateTable("employee_attendance", array("work_report" => $report), array("id" => (int) $todayRow['id']));
	}

	header("location: employee-attendance-form");
	exit;
}

if ($employee_id) {
	$data['todayRow'] = getDataByWhere("employee_attendance", "*", array("employee_id" => $employee_id, "attendance_date" => $today));

	$recent = array();
	foreach (getQuery("employee_attendance", "*", array("employee_id" => $employee_id), "attendance_date desc", "0,10") as $r) {
		$recent[] = $r;
	}
	$data['recent']   = $recent;
	$data['employee'] = $employee;
}

$data['employee_id']       = $employee_id;
$data['today']             = $today;
$data['mustResetPassword'] = $employee && $employee['must_reset_password'] === 'Y';

echo template($data);
