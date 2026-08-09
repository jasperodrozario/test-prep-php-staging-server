<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if ($_GET['chapter']) {
    $_POST['chapter'] = $_GET['chapter'];
}
if ($_GET['subject']) {
    $_POST['subject'] = $_GET['subject'];
}
if ($_GET['topic']) {
    $_POST['topic'] = $_GET['topic'];
}
if (!$_GET['add_to']) {
    $_GET['add_to'] = $_POST['add_to'];
}
if (!$_GET['pred_id']) {
    $_GET['pred_id'] = $_POST['pred_id'];
}
if (!$_GET['course_id']) {
    $_GET['course_id'] = $_POST['course_id'];
}
if (!$_GET['cat_id']) {
    $_GET['cat_id'] = $_POST['cat_id'];
}
if (!$_GET['lesson_id']) {
    $_GET['lesson_id'] = $_POST['lesson_id'];
}

if ($_POST) {
    $_REQUEST = $_POST;
}

$data['pred_name'] = getFldValueById("pred_test_category", "cat_name", array("pred_id" => $_GET['pred_id']));
$data['pred_code'] = getFldValueById("pred_test_category", "cat_code", array("pred_id" => $_GET['pred_id']));
$data['pred_desciption'] = getFldValueById("pred_test_category", "description", array("pred_id" => $_GET['pred_id']));
$data['subject_list'] = getQuery("question", "DISTINCT subject", array("subject!" => ""), "subject");
$data['course'] = getQuery("course", "*");
$data['option_no'] = 5;

$data['categories'] = getQuery("question", " DISTINCT cat_id", "", "cat_id");

$conditions = array("l.pred_id" => (int)$_GET['pred_id']);

$data['total_hw'] = getTotalRowsByJoin(
        ["question as q", "lesson_questions as l"],
        $conditions,
        ["q.qid" => "l.qid"]
    );

$query = array();
$query_lesson = array();

if (!empty($_GET['course_id'])) {
    $data['course_id'] = $_GET['course_id'];
    $query_lesson['course_id'] = (int)$_GET['course_id'];
}

if (!empty($_GET['cat_id'])) {
    $data['cat_id'] = $_GET['cat_id'];
    $query_lesson['cat_id'] = (int)$_GET['cat_id'];
}
if (!empty($_GET['lesson_id'])) {
    $data['lesson_id'] = $_GET['lesson_id'];
    $query_lesson['lesson_id'] = (int)$_GET['lesson_id'];
}

if (!empty($query_lesson)) {
    $lessonQues = getQuery("lesson_questions", "qid", $query_lesson);
    $qid_array = [];
    if ($lessonQues && $lessonQues->num_rows > 0) {
        while ($row = $lessonQues->fetch_assoc()) {
            $qid_array[] = (int)$row['qid'];
        }
    }

    $qids = $qid_array;
    $data['total_ids'] = count($qid_array);
    $data['qids_test'] = $qids;

    if (!empty($qids)) {
        $query["qid in "] = "(" . implode(",", array_map('intval', $qids)) . ")";
    } else {
        $query["qid in "] = "(0)"; // ensures no result
    }
}

# -------- Start Question ID List of already added questions ---------

if ($_GET['add_to'] == "HW") {
    $question_list = getQuery("lesson_questions", "qid", array("cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));
} else if ($_GET['add_to'] == "pt") {
    $question_list = getQuery("lesson_questions", "qid", array("cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));
} else if ($_GET['add_to'] == "flashcards") {
    $question_list = getQuery("lesson_questions", "qid", array("cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));
} else if ($_GET['add_to'] == "pred_set") {
    $question_list = getQuery("predic_questions", "qid", array("pred_cat_id" => $_GET['pred_cat_id'], "set_id" => $_GET['set_id'], "predic_test_id" => $_GET['predic_test_id']));
} else if ($_GET['add_to'] == "pred_ques") {
    $question_list = getQuery("lesson_questions", "qid", array("pred_id" => $_GET['pred_id']));
}


if ($_GET['add_to']) {
    $question_id_list = array();
    foreach ($question_list as $q) {
        $question_id_list[] = $q['qid'];
    }
    $not_in_clause = implode(',', $question_id_list);
    if ($not_in_clause) {
        $query = array_merge($query, array("qid not in " => "($not_in_clause)"));
    }
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

if ($_POST['search_id']) {
    $data['search_id'] = $_POST['search_id'];

    $ids = array_filter(
        array_map('intval', array_map('trim', explode(',', $_POST['search_id']))),
        function ($v) {
            return $v > 0;
        }
    );

    if (!empty($ids)) {
        $query['qid in '] = '(' . implode(',', $ids) . ')';
    } else {
        $query['qid in '] = '(0)'; // force no result if invalid input
    }
} elseif ($_POST['search']) {
    $data['search_phrage'] = $_POST['search'];
    $query = array_merge($query, array("question like " => "'%" . $_POST['search'] . "%'"));
}

if ($_POST['cat_id_drop']) {
    $data['category'] = $_POST['cat_id_drop'];
    $query = array_merge($query, array("cat_id" => $_POST['cat_id_drop']));
}
if ($_POST['question_tag']) {
    //	$query=array_merge($query,array("question_tag like "=>"'%".$_POST['question_tag'] ."%'"));

    $questionTypes = $_POST['question_tag'];
    $questionTypesArray = explode(',', $questionTypes);
    foreach ($questionTypesArray as $qtype) {
        $query = array_merge($query, array("question_tag like " => "'%" . $qtype . "%'"));
    }
    $data['ques_type'] = $_POST['question_tag'];
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
    $data['db_list'] = getQuery("question", "*", $query, "qid desc");
    $data['total'] = getTotalRow("question", $query);
} else {
    //    $data['db_list'] = getQueryByJoin(array("question as q","lesson_questions as l"), "q.*",array("l.lesson_id"=>(int)$_GET['lesson_id'], "l.cat_id"=>(int)$_GET['cat_id'], "l.course_id"=>(int)$_GET['course_id'],  "l.HW"=>1),array("q.qid !"=>"l.qid"), "l.order_no","","", "q.qid");
    // $data['db_list'] = getQuery("question", "*", $query, "");
    // $data['total'] = getTotalRow("question", $query);
}

if ($_GET['del_id']) {
    $r = deleteData("question", array("qid" => $_GET['del_id']));
    deleteData("lesson_questions", array("qid" => $_GET['del_id']));
    $data['msg'] = $configs['delete_msg'];
}


//  ************ Start Insert Questions ***********            
if (isset($_POST['save_button'])) {

    // Extract form data 
    $targetPredCatId = $_GET['pred_id'];
    $selectedQuestionIds = $_REQUEST['ids'];
    $count = count($selectedQuestionIds);
    $data['copy_msg'] = $count . " Questions has been copied successfully!";

    foreach ($selectedQuestionIds as $questionId) {
        if ($_GET['pred_id']) {
            // Create a new question data array
            $newQuestionData = array(
                'qid' => $questionId,
                'pred_id' => $targetPredCatId,
            );
            $newQuestionId = insertTable("lesson_questions", $newQuestionData);
        }
    }
    $data['msg'] = $configs['success_msg'];

    if ($_GET['pred_id']) {
        header("location: prediction-question-list?pred_id=" . $_GET['pred_id']);
    }
}

//  ************ End Insert Questions ***********     
$data['page_title'] = "Add Prediction Questions";
$data['breadcrumb'] = array(
    $data['pred_code'] . " Prediction Questions" => "prediction-question-list?pred_id=" . $_GET['pred_id'],
    " Total Questions: " . $data['total'] => ""
);

echo template($data);
