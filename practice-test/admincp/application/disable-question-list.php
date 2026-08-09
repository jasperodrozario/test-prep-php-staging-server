<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));

$data['categories'] = getQuery("question", " DISTINCT cat_id", array("is_disable" => 1), "cat_id");
$data['course'] = getQuery("course", "*");
$data['chapter_list'] = getQuery("question", "DISTINCT chapter", array("chapter!"=>"", "is_disable" => 1),"chapter");

if (!$_GET['page']) {
    $_GET['page'] = 'question-list';
}

if ($_GET['cat_id_drop']) {
    $_POST['cat_id_drop'] = $_GET['cat_id_drop'];
}
if ($_GET['question_tag']) {
    $_POST['question_tag'] = $_GET['question_tag'];
}
if ($_GET['search_id']) {
    $_POST['search_id'] = $_GET['search_id'];
}
if ($_GET['search']) {
    $_POST['search'] = $_GET['search'];
}
if ($_GET['chapter']) {
    $_POST['chapter'] = $_GET['chapter'];
}

$data['option_no'] = 5;

if ($_GET['page']) {
    
    if (isset($_GET['action']) && $_GET['action'] === 'restore') {
       
        $r = updateTable("question", array("is_disable" => 0), array("qid" => $_GET['qid']));
        
        if($r){
            $data['msg'] = "Question has been enabled successfully.";
        }
        else{
            $data['msg'] = "Failed to enable the question. Please try again.";
        }
    }

    if ($_GET['del_id']) {
        $r = deleteData("question", array("qid" => $_GET['del_id']));
        if($r){
            $data['msg'] = $configs['delete_msg'];
        }
        else{
            $data['msg'] = $configs['error_msg'];
        }
    }

    if (isset($_POST['enableMultiQuestions'])) {
        $selectedQuestionIds = $_POST['ids'];
        
        foreach ($selectedQuestionIds as $questionId) {
            $r = updateTable("question", array("is_disable" => 0), array("qid" => (int)$questionId));
        } 
        
        if($r){
            $data['msg'] = "Selected questions have been enabled successfully.";
        }
        else{
            $data['msg'] = "Failed to enable selected questions. Please try again.";
        }
    }
    
    if (isset($_POST['deleteMultiQuestions'])) {
        $selectedQuestionIds = $_POST['ids'];
        
        foreach ($selectedQuestionIds as $questionId) {
            $r = deleteData("question", array("qid" => (int)$questionId));
        } 
        
        if($r){
            $data['msg'] = $configs['success_msg'];
        }
        else{
            $data['msg'] = $configs['error_msg'];
        }
    }
    
    $query=array("is_disable"=>1);

    if($_POST['search_id']){
        $data['search_id'] = $_POST['search_id'];
        $query=array_merge($query,array("qid"=>(int)$_POST['search_id']));
    }
    elseif($_POST['search']){
        $data['search_phrage'] = $_POST['search'];
        $query=array_merge($query,array("question like "=>"'%".$_POST['search'] ."%'"));
    }

    if($_POST['cat_id_drop'])
    {
        $data['category'] = $_POST['cat_id_drop'];
        $query=array_merge($query,array("cat_id"=>$_POST['cat_id_drop'])); 
    }
    if($_POST['question_tag'])
    {
        $questionTypes = $_POST['question_tag'];
        $questionTypesArray = explode(',', $questionTypes);
        foreach ($questionTypesArray as $qtype) {
            $query=array_merge($query,array("question_tag like "=>"'%".$qtype."%'"));
        }
        $data['ques_type'] = $_POST['question_tag'];
        //        $query=array_merge($query,array("question_tag like "=>"'%".$_POST['question_tag'] ."%'"));
    }
    
    if($_POST['chapter'])
    {
        $data['chapter'] = $_POST['chapter'];
        $query=array_merge($query,array("chapter"=>$_POST['chapter'])); 
    }
    if($_POST['topic'])
    {
        $data['topic'] = $_POST['topic'];
        $query=array_merge($query,array("topic"=>$_POST['topic'])); 
    }
    
    $query_lesson=array("q.is_disable"=>1);

    if($_POST['course_id'])
    {
        $query_lesson=array_merge($query_lesson,array("l.course_id"=>(int)$_POST['course_id'])); 
    }
    if($_POST['cat_id'])
    {
        $query_lesson=array_merge($query_lesson,array("l.cat_id"=>(int)$_POST['cat_id'])); 
    }
    if($_POST['lesson_id'])
    {
        $query_lesson=array_merge($query_lesson,array("l.lesson_id"=>(int)$_POST['lesson_id'])); 
    }
    if($_POST['hw_pt']=="hw")
    {
        $query_lesson=array_merge($query_lesson,array("l.HW"=>1)); 
    }
    if($_POST['hw_pt']=="pt")
    {
        $query_lesson=array_merge($query_lesson,array("l.pt"=>1)); 
    }
    
    if(isset($_POST['ok']))
    {
        $data['db_list'] = getQuery("question", "*", $query, "qid desc", $configs['limit']);
        $data['total'] = getTotalRow("question", $query); 
    }
    else if(isset($_POST['ok2']))
    {
        $data['db_list'] = getQueryByJoin(array("question as q","lesson_questions as l"), "q.*, l.order_no, l.HW, l.pt, l.id",$query_lesson,array("q.qid"=>"l.qid"), "l.order_no", $configs['limit']);
        
        $data['total'] = getTotalRowsByJoin(array("question as q","lesson_questions as l"), $query_lesson, array("q.qid"=>"l.qid"));
        
        $data['lesson_flag'] = true;
    }
    else
    {
        $data['db_list'] = getQuery("question", "*", $query, "qid desc",$configs['limit']);
        $data['total'] = getTotalRow("question", $query);
    }
}

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_POST['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_POST['course_id']));
$data['lesson_name'] = getFldValueById("lesson", "lesson_name", array("lesson_id" => $_POST['lesson_id']));

$data['page_title'] = "Course " . $data['course_name'] . " > Category " . $data['cat_name'] . " > Lesson " . $data['lesson_name'];
$data['breadcrumb'] = array("Enabled Question List" => "", " > Total Questions: ". $data['total'] => "");

echo template($data);
?>
