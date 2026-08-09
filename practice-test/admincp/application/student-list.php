<?php
include("../common/access.php");
include("../common/autoload.php");

//updateTable("login",["status"=>"Y"],["activation_status"=>"Y","exp_date > "=>"'".date("Y-m-d")."'","reg_at"=>"web-sub"]);

//updateTable("login",["status"=>"E"],["exp_date < "=>"'".date("Y-m-d")."'","reg_at"=>"web-sub"]);


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
	$data['msg'] = $configs['delete_msg'];
}
if (isset($_POST['deleteMultiUser'])) {
	for ($i = 0; $i < count($_POST['user_ids']); $i++) {
		if ($_POST['user_ids'][$i]) {
			deleteData("user", array("user_id" => $_POST['user_ids'][$i]));
			deleteData("login", array("user_id" => $_POST['user_ids'][$i]));
			deleteData("picture", array("user_id" => $_POST['user_ids'][$i]));
			deleteData("subscription", array("user_id" => $_POST['user_ids'][$i]));
			deleteData("exam", array("user_id" => $_POST['user_ids'][$i]));
			deleteData("result", array("user_id" => $id['user_id']));
		}
	}
}

$param = [];
$query = array("u.user_type" => "Student");

if ($_GET['reg_at']) {
	if ($_GET['reg_at'] != "all") {
		$query = array_merge($query, array("u.reg_at like '%" => $_GET['reg_at'] . "%'"));
		$param[] = "reg_at=" . $_GET['reg_at'];
	}
}

$data['test'] = getQuery("course", "*", array(), "course_name");
$test_name = [];

$student_total = [];

foreach ($data['test'] as $t) {
	$student_total[$t['course_id']] = getTotalRowsByJoin(array("user as u", "subscription as s"), array_merge($query, array("s.course_id" => $t['course_id'])), array("u.user_id" => "s.user_id"));

	$test_name[$t['course_id']] = getFldValueById("course", "course_name", array("course_id" => $t['course_id']));
}
$data['test_name'] = $test_name;
$data['student_total'] = $student_total;

if ($_GET['key']) {
	$query = array_merge($query, array("[or]" => "(u.user_id like '%" . $_GET['key'] . "%' or u.mobile like '%" . $_GET['key'] . "%' or u.email like '%" . $_GET['key'] . "%' or concat(u.first_name,' ',u.last_name) like '%" . $_GET['key'] . "%')"));
}

//if($_POST['source_type']){
//    $query=array_merge($query,array("u.reg_at like '%"=>$_POST['source_type']."%'"));
//}

//if($_GET['test_id'])
//{
//	$query=array_merge($query,array("s.course_id"=>$_GET['test_id']));
//	
//	$param[]="course_id=".$_GET['test_id'];
// 
//}

if ($_GET['test_id']) {
	$query = array_merge($query, array("s.course_id" => $_GET['test_id']));
	$param[] = "test_id=" . $_GET['test_id'];
}

if ($_GET['referral_member']) {
	$param[] = "referral_member=Y";
}
if ($_GET['status']) {
	$param[] = "status=" . $_GET['status'];
}

$data['param'] = implode("&", $param);

if ($_GET['referral_member']) {
	//	if($_GET['referrer_id'])
	//	{
	//		$query=array_mergr($query,["r.referrer_id"=>$_GET['referrer_id']]);
	//	}	
	$data['all_total'] = getTotalRow("user", array("referer_id>" => 0));

	$data['total_paid'] = getTotalRow("user", array("referer_id>" => 0, "activation_status" => "Y"));

	$data['total_free'] = getTotalRow("user", array("referer_id>" => 0, "activation_status" => "N"));

	$data['total_tutor'] = getTotalRow("user", array("referer_id>" => 0, "activation_status" => "Y", "paypal_id is " => null));

	if ($_GET['status']) {
		$data['db_list'] = getQueryByJoin(array("user as u", "subscription as s"), "u.*,s.*", array("u.activation_status" => $_GET['status'], "u.referer_id>" => 0), array("u.user_id" => "s.user_id"), "u.user_id desc", $configs['limit']);

		$data['total'] = getTotalRow("user", array("activation_status" => $_GET['status'], "referer_id>" => 0));
	} else {
		$data['db_list'] = getQueryByJoin(array("user as u", "subscription as s"), "u.*,s.*", array("u.referer_id>" => 0), array("u.user_id" => "s.user_id"), "u.user_id desc", $configs['limit']);

		$data['total'] = getTotalRow("user", array("referer_id>" => 0));
	}

	$tag = "Referral ";
} else {
	$data['all_total'] = getTotalRowsByJoin(array("user as u", "subscription as s"), $query, array("u.user_id" => "s.user_id"));

	$data['total_paid'] = getTotalRowsByJoin(array("user as u", "subscription as s"), array_merge($query, ['u.activation_status' => 'Y', 'u.tutor_sub' => 'N', 's.amount >' => 0]), array("u.user_id" => "s.user_id"));

	$data['total_free'] = getTotalRowsByJoin(array("user as u", "subscription as s"), array_merge($query, ['u.activation_status' => 'N', 'u.tutor_sub' => 'N']), array("u.user_id" => "s.user_id"));

	$data['total_tutor'] = getTotalRowsByJoin(array("user as u", "subscription as s"), array_merge($query, ['u.activation_status' => 'Y', 'u.tutor_sub' => 'Y']), array("u.user_id" => "s.user_id"));

	if ($_GET['status']) {
		if ($_GET['status'] == 'T') {
			$query = array_merge($query, ['u.activation_status' => 'Y', 'u.tutor_sub' => 'Y']);
		} else if ($_GET['status'] == 'Y') {
			$query = array_merge($query, ['u.activation_status' => 'Y']);
		} else if ($_GET['status'] == 'N') {
			$query = array_merge($query, ['u.activation_status' => 'N']);
		} else {
			//		  $query=array_merge($query,['u.activation_status'=>$_GET['status']]);
		}
	}

	$data['db_list'] = getQueryByJoin(array("user as u", "subscription as s"), "u.*,s.*", $query, array("u.user_id" => "s.user_id"), "u.user_id desc", $configs['limit']);

	$data['total'] = getTotalRowsByJoin(array("user as u", "subscription as s"), $query, array("u.user_id" => "s.user_id"));
}

$data['breadcrumb'] = array($tag . "Student List" => "");
$data['page_title'] = $tag . "Student List [" . $data['total'] . "]";

echo template($data);
