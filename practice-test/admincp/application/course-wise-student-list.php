<?php
include("../common/access.php");
include("../common/autoload.php");

if (isset($_GET['download_csv'])) {

	$stud_list_cond = array(
		"u.user_type" => "Student"
	);

	if (!empty($_GET['from_date'])) {
		$stud_list_cond["u.reg_date >="] = "'" . $_GET['from_date'] . " 00:00:00'";
	}

	if (!empty($_GET['to_date'])) {
		$stud_list_cond["u.reg_date <="] = "'" . $_GET['to_date'] . " 23:59:59'";
	}

	$students = getQueryByJoin(
		array("user as u", "subscription as s"),
		"u.*, s.course_id",
		$stud_list_cond,
		array("u.user_id" => "s.user_id")
	);


	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="student_list.csv"');

	$output = fopen('php://output', 'w');

	// Header row
	fputcsv($output, [
		'ID',
		'Name',
		'Email',
		'Mobile',
		'Course',
		'Reg Date'
	]);

	foreach ($students as $d) {

		$course_name = getFldValueById("course", "course_name", array("course_id" => $d['course_id']));

		fputcsv($output, [
			$d['user_id'],
			$d['first_name'] . ' ' . $d['last_name'],
			$d['email'],
			$d['mobile'],
			$course_name,
			$d['reg_date']
		]);
	}

	fclose($output);
	exit;
}

if ($_GET['delete_id']) {
    deleteData("login", array("user_id" => $_GET['delete_id']));
    deleteData("user", array("user_id" => $_GET['delete_id']));
    deleteData("picture", array("user_id" => $_GET['delete_id']));
    deleteData("subscription", array("user_id" => $_GET['delete_id']));
    deleteData("exam", array("user_id" => $_GET['delete_id']));
    deleteData("report", array("user_id" => $_GET['delete_id']));
    $data['msg'] = $configs['delete_msg'];
}

$param = [];
$query = array("u.user_type" => "Student");

if ($_GET['platform']) {
    $query = array_merge($query, array("u.reg_at like '%" => $_GET['platform'] . "%'"));
    $param[] = "platform=" . $_GET['platform'];
}

if ($_GET['key']) {
    $query = array_merge($query, array("[or]" => "(u.mobile like '%" . $_GET['key'] . "%' or u.email like '%" . $_GET['key'] . "%' or concat(u.first_name,' ',u.last_name) like '%" . $_GET['key'] . "%')"));
}

if ($_GET['course']) {
    if ($_GET['course'] == "ASVAB") {
        $query = array_merge($query, array("[or]" => "(s.course_id=27 or s.course_id=40 or s.course_id = 28)"));
    } else if ($_GET['course'] == "HESI") {
        $query = array_merge($query, array("s.course_id" => 20));
    } else if ($_GET['course'] == "TEAS") {
        $query = array_merge($query, array("s.course_id" => 33));
    } else if ($_GET['course'] == "CNA") {
        $query = array_merge($query, array("s.course_id" => 34));
    } else if ($_GET['course'] == "Prep") {
        $query = array_merge($query, array("s.course_id" => 38));
    } else if ($_GET['course'] == "GT") {
        $query = array_merge($query, array("s.course_id" => 39));
    } else if ($_GET['course'] == "AFOQT") {
        $query = array_merge($query, array("s.course_id" => 24));
    } else if ($_GET['course'] == "SIFT") {
        $query = array_merge($query, array("s.course_id" => 25));
    } else if ($_GET['course'] == "ASTB") {
        $query = array_merge($query, array("s.course_id" => 26));
    } else if ($_GET['course'] == "OAR") {
        $query = array_merge($query, array("s.course_id" => 21));
    } else if ($_GET['course'] == "SHSAT") {
        $query = array_merge($query, array("s.course_id" => 30));
    } else if ($_GET['course'] == "GED") {
        $query = array_merge($query, array("s.course_id" => 37));
    }

    $param[] = "course=" . $_GET['course'];
}

$data['total'] = getTotalRowsByJoin(array("user as u", "subscription as s"), $query, array("u.user_id" => "s.user_id"));

if ($_GET['status']) {
    if ($_GET['status'] == 'Y') {
        $query = array_merge($query, ['u.activation_status' => 'Y']);
    } else if ($_GET['status'] == 'N') {
        $query = array_merge($query, ['u.activation_status' => 'N']);
    }
    $param[] = "status=" . $_GET['status'];
}

$data['param'] = implode("&", $param);

$data['all_total'] = getTotalRowsByJoin(array("user as u", "subscription as s"), $query, array("u.user_id" => "s.user_id"));

$data['total_active_student'] = getTotalRowsByJoin(array("user as u", "subscription as s"), array_merge($query, ['u.activation_status' => 'Y']), array("u.user_id" => "s.user_id"));

$data['total_inactive_student'] = getTotalRowsByJoin(array("user as u", "subscription as s"), array_merge($query, ['u.activation_status' => 'N']), array("u.user_id" => "s.user_id"));

$data['db_list'] = getQueryByJoin(array("user as u", "subscription as s"), "u.*,s.*", $query, array("u.user_id" => "s.user_id"), "u.user_id desc", $configs['limit']);

$data['breadcrumb'] = array($tag . $_GET['course'] . " Student List - " . $_GET['platform'] => "");
$data['page_title'] = $tag . "Student List [" . $data['total'] . "]";

echo template($data);
