<?php
include("../common/access.php");
include("../common/autoload.php");

// ---------- Tester accounts (excluded from revenue) ----------
// Every logged-in admin may change the status (access.php has already required an admin login).
$canMarkTesting = !empty($_SESSION['admin_id']);

if (empty($_SESSION['testing_account_csrf'])) {
	$_SESSION['testing_account_csrf'] = bin2hex(random_bytes(16));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'set_testing') {
	$returnQS = (string) ($_POST['return_qs'] ?? '');
	parse_str($returnQS, $returnParams);
	unset($returnParams['testing_msg'], $returnParams['testing_uid']);

	$userId = (int) ($_POST['user_id'] ?? 0);
	$markAs = ($_POST['is_testing'] ?? '') === '1';
	$wasTesting = $nowTesting = null;

	if (!$canMarkTesting) {
		$returnParams['testing_msg'] = 'denied';
	} elseif (!hash_equals($_SESSION['testing_account_csrf'], (string) ($_POST['csrf'] ?? '')) || $userId <= 0 || !getTotalRow("user", array("user_id" => $userId))) {
		$returnParams['testing_msg'] = 'invalid';
	} else {
		$wasTesting = getTotalRow("testing_account", array("user_id" => $userId)) > 0;
		if ($markAs && !$wasTesting) {
			insertTable("testing_account", array("user_id" => $userId, "marked_by" => (int) $_SESSION['admin_id'], "marked_at" => date("Y-m-d H:i:s")));
		} elseif (!$markAs && $wasTesting) {
			deleteData("testing_account", array("user_id" => $userId));
		}
		// Read the status back so the page only shows what is actually saved
		$nowTesting = getTotalRow("testing_account", array("user_id" => $userId)) > 0;
		$returnParams['testing_msg'] = $nowTesting !== $markAs ? 'failed' : ($markAs ? 'marked' : 'unmarked');
	}
	$returnParams['testing_uid'] = $userId;

	// In-page request from the Mark/Unmark link: answer with JSON so the list doesn't reload
	if (($_POST['ajax'] ?? '') === '1') {
		$result = $returnParams['testing_msg'];
		$messages = array(
			'marked'   => "Student #$userId marked as a tester account. Its payments are now excluded from revenue.",
			'unmarked' => "Student #$userId is no longer a tester account. Its payments count towards revenue again.",
			'denied'   => "You don't have permission to change tester accounts.",
			'invalid'  => "The request expired or the account wasn't found. Please reload the page and try again.",
			'failed'   => "Couldn't save the tester status for student #$userId. Please try again.",
		);
		while (ob_get_level()) {
			ob_end_clean();
		}
		header('Content-Type: application/json');
		echo json_encode(array(
			'ok'         => in_array($result, array('marked', 'unmarked'), true),
			'is_testing' => $nowTesting,                                  // confirmed from the database (null if not checked)
			'changed'    => $nowTesting !== null && $wasTesting !== $nowTesting,
			'message'    => $messages[$result],
		));
		exit;
	}

	header("Location: student-list" . ($returnParams ? '?' . http_build_query($returnParams) : ''));
	exit;
}

$testingMessages = array(
	'marked'   => array('success', 'marked as a tester account. Its payments are now excluded from revenue.'),
	'unmarked' => array('success', 'is no longer a tester account. Its payments count towards revenue again.'),
	'denied'   => array('danger', "You don't have permission to change tester accounts."),
	'invalid'  => array('danger', "The request expired or the account wasn't found. Nothing was changed."),
);
if (isset($testingMessages[$_GET['testing_msg'] ?? ''])) {
	$tm = $testingMessages[$_GET['testing_msg']];
	$data['testing_flash'] = array($tm[0], in_array($_GET['testing_msg'], array('marked', 'unmarked'), true) ? "Student #" . (int) ($_GET['testing_uid'] ?? 0) . " " . $tm[1] : $tm[1]);
}
$data['canMarkTesting'] = $canMarkTesting;
$data['testing_csrf']   = $_SESSION['testing_account_csrf'];

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

	$testingCond = array("u.user_id in " => "(SELECT user_id FROM " . $configs['prev'] . "testing_account)");
	$data['total_testing'] = getTotalRowsByJoin(array("user as u", "subscription as s"), array_merge($query, $testingCond), array("u.user_id" => "s.user_id"));

	if ($_GET['status']) {
		if ($_GET['status'] == 'testing') {
			$query = array_merge($query, $testingCond);
		} else if ($_GET['status'] == 'T') {
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

$testingIds = array();
$rr = getQuery("testing_account", "user_id", "1=1");
while ($rr && $row = mysqli_fetch_assoc($rr)) {
	$testingIds[(int) $row['user_id']] = true;
}
$data['testingIds'] = $testingIds;

$data['breadcrumb'] = array($tag . "Student List" => "");
$data['page_title'] = $tag . "Student List [" . $data['total'] . "]";

echo template($data);
