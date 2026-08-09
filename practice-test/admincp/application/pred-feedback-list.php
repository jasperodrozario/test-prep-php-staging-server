<?php
include("../common/access.php");
include("../common/autoload.php");

$data['btn']="<a href='pred-feedback-entryform' class='btn btn-success'>Add Feedback</a>";

if ($_GET['del_id']):
    $r = deleteData("prediction_test_feedback", array("feedback_id" => $_GET['del_id']));
    if ($r) {
        $data['msg'] = $configs['delete_msg'];
    } else {
        $data['msg'] = "Error deleting feedback.";
    }
endif;

if ($_GET['id'] && $_GET['status']):

    if ($_GET['status'] == "Y") {
        $_GET['status'] = 1;
    } else {
        $_GET['status'] = 0;
    }

    $r = updateTable("prediction_test_feedback", array("is_resolved" => $_GET['status']), array("feedback_id" => $_GET['id']));
    $data['msg'] = $configs['success_msg'];
    $_GET['status'] = "";
endif;

if ($_GET['id'] && $_GET['online_status']) {

    if ($_GET['online_status'] == "Y") {
        $_GET['online_status'] = 1;
    } else {
        $_GET['online_status'] = 0;
    }

    $r = updateTable("prediction_test_feedback", array("is_online" => $_GET['online_status']), array("feedback_id" => $_GET['id']));
    $data['msg'] = $configs['success_msg'];
    $_GET['status'] = "";
}

$query = [];

$data['test'] = getQuery("course", "*", array(), "course_id");
$test_name = [];

$student_total = [];

foreach ($data['test'] as $t) {
    $student_total[$t['course_id']] = getTotalRow("prediction_test_feedback", array_merge($query, array('course_id' => $t['course_id'])));

    $test_name[$t['course_id']] = $t['course_name'];
}

$data['test_name'] = $test_name;
$data['student_total'] = $student_total;


$search     = trim($_POST['key'] ?? '');
$course_id  = $_POST['course_id'] ?? '';
$from_date  = $_POST['from_date'] ?? '';
$to_date    = $_POST['to_date'] ?? '';

$conditions = [];

/* -----------------------
   1. SEARCH (email/user)
------------------------*/
if (!empty($search)) {

    $searched_user_id = getFldValueById(
        "user",
        "user_id",
        ["email" => $search]
    );
    
    $query['user_id'] = $searched_user_id;
}

/* -----------------------
   2. COURSE FILTER
------------------------*/
if (!empty($course_id)) {
    $query['course_id'] = (int)$course_id;
}

/* -----------------------
   3. DATE RANGE FILTER
------------------------*/
if (!empty($from_date) && !empty($to_date)) {
    $query["created_at >"] = $from_date;
    $query["created_at <"] = $to_date;
} elseif (!empty($from_date)) {
    $query["created_at >"] = $from_date;
} elseif (!empty($to_date)) {
    $query["created_at <"] = $to_date;
}

// if ($_POST['key']) {
//     $searched_user_id = getFldValueById("user", "user_id", array("email" => $_POST['key']));

//     $query = array_merge($query, array("[or]" => "(platform like '%" . $_POST['key'] . "%' or email like '%" . $_POST['key'] . "%' or user_id=" . (int)$searched_user_id . ")"));
// }

$data['total'] = getTotalRow("prediction_test_feedback", $query);
$data['db_list'] = getQuery("prediction_test_feedback", "*", $query, "feedback_id desc", $configs['limit']);

$data['page_title'] = "Web User Feedback Management ";
$data['breadcrumb'] = array("Web User Feedback List[" . $data['total'] . "]" => "pred-feedback-list");

echo template($data);
