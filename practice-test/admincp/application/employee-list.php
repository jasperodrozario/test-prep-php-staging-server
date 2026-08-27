<?php
include("../common/access.php");
include("../common/autoload.php");

$data['breadcrumb'] = array("Employees" => "");

$departments = array("Engineering", "Content", "Tutoring", "Support", "Marketing");

function buildQS($overrides = array())
{
	$params = array_merge($_GET, $overrides);
	foreach ($params as $k => $v) {
		if ($v === '' || $v === null) unset($params[$k]);
	}
	return http_build_query($params);
}

if (isset($_GET['del_id']) && (int) $_GET['del_id'] > 0) {
	$r = deleteData("employee", array("id" => (int) $_GET['del_id']));
	$data['msg'] = $r ? $configs['delete_msg'] : $configs['error_msg'];
} elseif (!empty($_GET['updated'])) {
	$data['msg'] = $configs['success_msg'];
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$status = isset($_GET['status']) && in_array($_GET['status'], array('active', 'inactive'), true) ? $_GET['status'] : 'all';
$dept   = isset($_GET['dept']) && in_array($_GET['dept'], $departments, true) ? $_GET['dept'] : '';
$sort   = isset($_GET['sort']) && in_array($_GET['sort'], array('name', 'recent', 'department'), true) ? $_GET['sort'] : 'name';

$perPage = 10;
$page    = isset($_GET['limit']) ? max(1, (int) $_GET['limit']) : 1;
$offset  = ($page - 1) * $perPage;

$today = date('Y-m-d');

$leaveIds = array();
foreach (getQuery("employee_attendance", "DISTINCT employee_id", array("attendance_date" => $today, "status" => "leave")) as $row) {
	$leaveIds[] = (int) $row['employee_id'];
}

$whereParts = array();
if ($status !== 'all') {
	$whereParts[] = "status = '" . $status . "'";
}
if ($dept !== '') {
	$whereParts[] = "department = '" . mysqli_real_escape_string($configs['con'], $dept) . "'";
}
if ($search !== '') {
	$esc = mysqli_real_escape_string($configs['con'], $search);
	$whereParts[] = "(full_name like '%$esc%' or employee_code like '%$esc%' or email like '%$esc%')";
}
$whereSql = count($whereParts) ? implode(" and ", $whereParts) : "1=1";

$orderBy = "full_name asc";
if ($sort === 'recent')     $orderBy = "created_at desc";
if ($sort === 'department') $orderBy = "department asc, full_name asc";

$countResult = mysqli_query($configs['con'], "select count(*) as c from " . $configs['prev'] . "employee where " . $whereSql);
$total = $countResult ? (int) mysqli_fetch_assoc($countResult)['c'] : 0;

$employees = array();
foreach (getQuery("employee", "*", $whereSql, $orderBy, "$offset,$perPage") as $row) {
	$employees[] = $row;
}

$data['employees']   = $employees;
$data['leaveIds']    = $leaveIds;
$data['departments'] = $departments;

$data['stat_total']    = getTotalRow("employee");
$data['stat_active']   = getTotalRow("employee", array("status" => "active"));
$data['stat_on_leave'] = getTotalRow("employee_attendance", array("attendance_date" => $today, "status" => "leave"));
$data['stat_new']      = getTotalRow("employee", array("date_of_joining >=" => "'" . date('Y-m-01') . "'"));

$data['search']   = $search;
$data['status']   = $status;
$data['dept']     = $dept;
$data['sort']     = $sort;
$data['page']     = $page;
$data['perPage']  = $perPage;
$data['total']    = $total;

echo template($data);
