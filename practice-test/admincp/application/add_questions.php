<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));

if($data['cat_name']){
    $add_ques_in = $data['cat_name'];
}
else{
    $add_ques_in = "Question List";
}

$data['page_title'] = "Add Question in <u>" . $add_ques_in . "</u>";
$data['breadcrumb'] = array(
    "Question List" => "questions",
    "Add Question in <u>" . $add_ques_in . "</u>" => ""
);

$data['categories'] = getQuery("question", " DISTINCT cat_id","", "cat_id");

if (!$_GET['page']) {
    $_GET['page'] = 'add-question';
}

$data['option_no'] = 5;

if ($_GET['page']) {

    if ($_GET['qid']) {
        $data['qid'] = $_GET['qid'];
        $data['db_value'] = getDataByWhere("question","*",array("qid" => $_GET['qid']));

        $data['total_answers'] = getTotalRow("question", array("qid" => $_GET['qid']));
    } else {
        $data['db_value']['cat_id'] = $_GET['cat_id'];
    }

    $data['total'] = getTotalRow("question");

    $data['story_list'] = getQuery("story", "*","","story_title");
    
    $data['subject_list'] = getQuery("question", "*",array("subject!"=>" "),"subject","","","subject");
    
    $data['chapter_list'] = getQuery("question", "*",array("chapter!"=>" "),"chapter","","","chapter");
    
    $data['topic_list'] = getQuery("question", "*",array("topic!"=>" "),"topic","","","topic");
}

//$data['page_title'] = "Course " . $data['course_name'] . " > Category " . $data['cat_name'];


echo template($data);
?>
