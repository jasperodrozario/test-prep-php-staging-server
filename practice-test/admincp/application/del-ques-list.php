<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));

$data['categories'] = getQuery("question_deleted", " DISTINCT cat_id","", "cat_id");
$data['course'] = getQuery("course", "*");
$data['chapter_list'] = getQuery("question_deleted", "DISTINCT chapter", array("chapter!"=>""),"chapter");

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
       
        $q_data = getDataByWhere("question_deleted", "*", array("qid" => $_GET['qid']));
        
        if ($q_data) {
            // Insert data into the "question_deleted" table
                // Prepare data for insertion, only adding columns with data
            $insert_data = array();

            if (!empty($q_data["qid"])) {
                $insert_data["qid"] = $q_data["qid"];
            }
            if (!empty($q_data["question"])) {
                $insert_data["question"] = $q_data["question"];
            }
            if (!empty($q_data["option_a"])) {
                $insert_data["option_a"] = $q_data["option_a"];
            }
            if (!empty($q_data["option_b"])) {
                $insert_data["option_b"] = $q_data["option_b"];
            }
            if (!empty($q_data["option_c"])) {
                $insert_data["option_c"] = $q_data["option_c"];
            }
            if (!empty($q_data["option_d"])) {
                $insert_data["option_d"] = $q_data["option_d"];
            }
            if (!empty($q_data["option_e"])) {
                $insert_data["option_e"] = $q_data["option_e"];
            }
            if (!empty($q_data["answer"])) {
                $insert_data["answer"] = $q_data["answer"];
            }
            if (!empty($q_data["explanation"])) {
                $insert_data["explanation"] = $q_data["explanation"];
            }
            if (!empty($q_data["question_tag"])) {
                $insert_data["question_tag"] = $q_data["question_tag"];
            }
            if (!empty($q_data["cat_id"])) {
                $insert_data["cat_id"] = $q_data["cat_id"];
            }
            if (!empty($q_data["source"])) {
                $insert_data["source"] = $q_data["source"];
            }
            if (!empty($q_data["story_id"])) {
                $insert_data["story_id"] = $q_data["story_id"];
            }
            if (!empty($q_data["old_story_id"])) {
                $insert_data["old_story_id"] = $q_data["old_story_id"];
            }
            if (!empty($q_data["old_ord"])) {
                $insert_data["old_ord"] = $q_data["old_ord"];
            }
            if (!empty($q_data["chapter"])) {
                $insert_data["chapter"] = $q_data["chapter"];
            }
            if (!empty($q_data["topic"])) {
                $insert_data["topic"] = $q_data["topic"];
            }

            // Insert only if there's valid data to insert
            if (!empty($insert_data)) {
                insertTable_raw_data("question", $insert_data);
            }
            
            $r = deleteData("question_deleted", array("qid" => $_GET['qid']));
            $data['msg'] = "Question has been restored successfully.";
        }
    }

    if ($_GET['del_id']) {
        $r = deleteData("question_deleted", array("qid" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
    }
    
    if (isset($_POST['deleteMultiQuestions'])) {
        $selectedQuestionIds = $_POST['ids'];
        
        foreach ($selectedQuestionIds as $questionId) {
            $r = deleteData("question_deleted", array("qid" => (int)$questionId));
        } 
        
        if($r){
            $data['msg'] = $configs['success_msg'];
        }
        else{
            $data['msg'] = $configs['error_msg'];
        }
    }
    
    $query=array();
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
    
    $query_lesson=array();
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
    

    if (isset($_POST['updateQuestionData'])) {
        // Handle updating question types here
        $selectedQuestionData = $_POST['selectedQuestionData'];
        $selectedQuestionIds = $_POST['ids']; // Assuming the checkboxes have name='ids[]'
        
        if($_POST['select_option']=="qtype")
        {
            foreach ($selectedQuestionIds as $questionId) {
                $existing_ques_type = getFldValueById("question_deleted", "question_tag", array("qid" => (int)$questionId));
                $updated_ques_type = $existing_ques_type . ", " . $selectedQuestionData;
                $r = updateTable("question_deleted",array("question_tag"=>$updated_ques_type),array("qid"=> (int)$questionId));
            } 
        }
        else if($_POST['select_option']=="qcategory")
        {
            foreach ($selectedQuestionIds as $questionId) {
                $r = updateTable("question_deleted",array("cat_id"=>$selectedQuestionData),array("qid"=> (int)$questionId));
            } 
        }
        else if($_POST['select_option']=="qchapter")
        {
            foreach ($selectedQuestionIds as $questionId) {
                $r = updateTable("question_deleted",array("chapter"=>$selectedQuestionData),array("qid"=> (int)$questionId));
            } 
        }
        else if($_POST['select_option']=="qtopic")
        {
            foreach ($selectedQuestionIds as $questionId) {
                $r = updateTable("question_deleted",array("topic"=>$selectedQuestionData),array("qid"=> (int)$questionId));
            } 
        }
        
        if($r){
            $data['msg'] = $configs['success_msg'];
        }
        else{
            $data['msg'] = $configs['error_msg'];
        }
    }
    
    if(isset($_POST['ok']))
    {
        $data['db_list'] = getQuery("question_deleted", "*", $query, "qid desc", $configs['limit']);
        $data['total'] = getTotalRow("question_deleted", $query); 
    }
    else if(isset($_POST['ok2']))
    {
        $data['db_list'] = getQueryByJoin(array("question_deleted as q","lesson_questions as l"), "q.*, l.order_no, l.HW, l.pt, l.id",$query_lesson,array("q.qid"=>"l.qid"), "l.order_no", $configs['limit']);
        
        $data['total'] = getTotalRowsByJoin(array("question_deleted as q","lesson_questions as l"), $query_lesson, array("q.qid"=>"l.qid"));
        
        $data['lesson_flag'] = true;
    }
    else
    {
        $data['db_list'] = getQuery("question_deleted", "*", $query, "qid desc",$configs['limit']);
        $data['total'] = getTotalRow("question_deleted", $query);
    }
}

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_POST['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_POST['course_id']));
$data['lesson_name'] = getFldValueById("lesson", "lesson_name", array("lesson_id" => $_POST['lesson_id']));

$data['page_title'] = "Course " . $data['course_name'] . " > Category " . $data['cat_name'] . " > Lesson " . $data['lesson_name'];
$data['breadcrumb'] = array("Deleted Question List" => "", " > Total Questions: ". $data['total'] => "");

echo template($data);
?>
