<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['cat_id']) {
    $_GET['cat_id'] = $_POST['cat_id'];
}
if (!$_GET['course_id']) {
    $_GET['course_id'] = $_POST['course_id'];
}

$data['cat_id'] = $_GET['cat_id'];
$data['course_id'] = $_GET['course_id'];

if ($_POST) {
    $_REQUEST = $_POST;
}

$data['categories'] = getQueryByJoin(array("question as q", "pred_free_test_questions as l"), " DISTINCT q.cat_id", array("l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']), array("q.qid" => "l.qid"), "q.cat_id");

$data['subject_list'] = getQueryByJoin(array("question as q", "pred_free_test_questions as l"), " DISTINCT q.subject", array("l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']), array("q.qid" => "l.qid"), "q.subject");

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_GET['course_id']));
$data['option_no'] = 5;

if (!$data['cat_name'] &&  $_GET['cat_id']) {
    $data['page_error'] = "There is no category questions to view.";
} else {

    if ($_GET['del_id']) {
        $r = deleteData("pred_free_test_questions", array("test_id" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
    }

    if (isset($_POST['remove_hw'])) {
        $selectedQuestionIds = $_POST['ids']; // Assuming the checkboxes have name='ids[]'

        foreach ($selectedQuestionIds as $questionId) {
            $r = deleteData("pred_free_test_questions", array("test_id" => (int)$questionId));
        }

        if ($r) {
            $data['msg'] = $configs['delete_msg'];
        } else {
            $data['msg'] = $configs['error_msg'];
        }
    }

    // Ensure $_POST receives values if coming via GET
    $getFilterParams = ['cat_id_drop', 'question_tag', 'search_id', 'subject', 'chapter', 'search', 'topic'];
    foreach ($getFilterParams as $param) {
        if (!empty($_GET[$param])) {
            $_POST[$param] = $_GET[$param];
        }
    }

    $conditions = array("l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']);

    if (!empty($_POST['search_id'])) {

        $data['search_id'] = $_POST['search_id'];

        // Split by comma, trim spaces, keep only valid integers
        $ids = array_filter(
            array_map('intval', array_map('trim', explode(',', $_POST['search_id']))),
            function ($v) { return $v > 0; }
        );

        if (!empty($ids)) {
            $conditions['q.qid in '] = '(' . implode(',', $ids) . ')';
        } else {
            $conditions['q.qid in '] = '(0)'; // force no result if invalid input
        }

    } elseif (!empty($_POST['search'])) {
        $conditions["q.question like "] = "'%" . $_POST['search'] . "%'";
    }

    // Determine category type

    $data['db_list'] = getQueryByJoin(
        ["question as q", "pred_free_test_questions as l"],
        "q.*, l.order_no, l.test_id",
        $conditions,
        ["q.qid" => "l.qid"],
        "l.order_no"
    );

    $data['total'] = getTotalRowsByJoin(
        ["question as q", "pred_free_test_questions as l"],
        $conditions,
        ["q.qid" => "l.qid"]
    );
}

$data['page_title'] = $data['course_name'] . " > " . $data['cat_name'] . " > Total Questions: " . $data['total'];
$data['breadcrumb'] = array(
    "Free Pred Test Categories" => "category-list-free-pred-test?course_id=" . $data['course_id'],
    "Total Questions: " . $data['total'] => ""
);

echo template($data);
