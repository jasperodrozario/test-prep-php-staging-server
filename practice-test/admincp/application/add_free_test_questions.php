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
if ($_GET['chapter']) {
    $_POST['chapter'] = $_GET['chapter'];
}
if ($_GET['subject']) {
    $_POST['subject'] = $_GET['subject'];
}
if ($_GET['topic']) {
    $_POST['topic'] = $_GET['topic'];
}

if ($_POST) {
    $_REQUEST = $_POST;
}

$data['course'] = getQuery("course", "*");

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_GET['course_id']));
$data['subject_list'] = getQuery("question", "DISTINCT subject", array("subject!" => ""), "subject");
$data['option_no'] = 5;

$data['categories'] = getQuery("question", " DISTINCT cat_id", "", "cat_id");

$query = array();

# -------- Start Question ID List of already added questions ---------

$question_list = getQuery("free_test_questions", "qid", array("cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));

$question_id_list = array();
foreach ($question_list as $q) {
    $question_id_list[] = $q['qid'];
}
$not_in_clause = implode(',', $question_id_list);
if ($not_in_clause) {
    $query = array_merge($query, array("qid not in " => "($not_in_clause)"));
}


# -------- End Question ID List of already added questions ---------

if (!$_POST['search_id']) {
    $_POST['search_id'] = $_GET['search_id'];
}
if (!$_POST['search']) {
    $_POST['search'] = $_GET['search'];
}

if (!$_POST['cat_id_drop']) {
    $_POST['cat_id_drop'] = $_GET['cat_id_drop'];
}
if (!$_POST['question_tag']) {
    $_POST['question_tag'] = $_GET['question_tag'];
}

if (!empty($_POST['search_id'])) {

    $data['search_id'] = $_POST['search_id'];

    // Split by comma, trim spaces, keep only valid integers
    $ids = array_filter(
        array_map('intval', array_map('trim', explode(',', $_POST['search_id']))),
        function ($v) { return $v > 0; }
    );

    if (!empty($ids)) {
        $query['qid in '] = '(' . implode(',', $ids) . ')';
    } else {
        $query['qid in '] = '(0)'; // force no result if invalid input
    }

} elseif (!empty($_POST['search'])) {
    $data['search_phrage'] = $_POST['search'];
    $query = array_merge($query, array("question like " => "'%" . $_POST['search'] . "%'"));
}

if ($_POST['cat_id_drop']) {
    $data['category'] = $_POST['cat_id_drop'];
    $query = array_merge($query, array("cat_id" => $_POST['cat_id_drop']));
}
if ($_POST['question_tag']) {
    $questionTypes = $_POST['question_tag'];
    $questionTypesArray = explode(',', $questionTypes);
    foreach ($questionTypesArray as $qtype) {
        $query = array_merge($query, array("question_tag like " => "'%" . $qtype . "%'"));
    }
    $data['ques_tag'] = $_POST['question_tag'];
}

if ($_POST['subject']) {
    $data['subject'] = $_POST['subject'];
    $query = array_merge($query, array("subject" => $_POST['subject']));
}
if ($_POST['chapter']) {
    $data['chapter'] = $_POST['chapter'];
    $query = array_merge($query, array("chapter" => $_POST['chapter']));
}
if ($_POST['topic']) {
    $data['topic'] = $_POST['topic'];
    $query = array_merge($query, array("topic" => $_POST['topic']));
}

if (isset($_POST['ok'])) {
    $data['db_list'] = getQuery("question", "*", $query, "qid desc", $configs['limit']);
    $data['total'] = getTotalRow("question", $query);
} else {
    $data['db_list'] = getQuery("question", "*", $query, "", $configs['limit']);
    $data['total'] = getTotalRow("question", $query);
}


//  ************ Start Insert Questions ***********       

if (isset($_POST['save_button'])) {

    // Extract form data
    $targetCourse = $_GET['course_id'];
    $targetCategoryId = $_GET['cat_id'];
    $selectedQuestionIds = $_REQUEST['ids'];
    $count = count($selectedQuestionIds);
    $data['copy_msg'] = $count . " Questions has been copied successfully!";

    foreach ($selectedQuestionIds as $questionId) {
        $newQuestionData = array(
            'qid' => $questionId,
            'cat_id' => (int)$targetCategoryId,
            'course_id' => $targetCourse
        );
        $newQuestionId = insertTable("free_test_questions", $newQuestionData);
    }
    $data['msg'] = $configs['success_msg'];

    header("location:questions_list_free_test?cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id']);
}

//  ************ End Insert Questions ***********     


$data['page_title'] = $data['course_name'] . " > " . $data['cat_name'];

$data['breadcrumb'] = array(
    "Free Test Question List" => "questions_list_free_test?cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'],
    " Total Questions: " . $data['total'] => ""
);

echo template($data);
