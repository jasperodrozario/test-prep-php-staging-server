<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['predic_test_id']) {
    $_GET['predic_test_id'] = $_POST['predic_test_id'];
}
if (!$_GET['pred_cat_id']) {
    $_GET['pred_cat_id'] = $_POST['pred_cat_id'];
}
if (!$_GET['set_id']) {
    $_GET['set_id'] = $_POST['set_id'];
}
if($_POST){$_REQUEST=$_POST;}

$data['test_name'] = getFldValueById("predic_test", "test_name", array("predic_test_id" => $_GET['predic_test_id']));
$cat_id = getFldValueById("prediction_test_category", "cat_id", array("id" => $_GET['pred_cat_id']));
$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $cat_id));
$data['set_name'] = getFldValueById("predic_question_set", "set_name", array("set_id" => $_GET['set_id']));

$data['option_no']=5;

if ($_GET['del_id']) {
    $r = deleteData("predic_questions", array("id" => $_GET['del_id']));
    $data['msg'] = $configs['delete_msg'];
//    header("location: questions_list?lesson_id=".$_GET['lesson_id']."&cat_id=".$_GET['cat_id']."&course_id=".$_GET['course_id']);
}	

if (isset($_POST['remove'])) {
    $selectedQuestionIds = $_POST['ids']; // Assuming the checkboxes have name='ids[]'

    foreach ($selectedQuestionIds as $questionId) {
        $r = deleteData("predic_questions", array("id" => (int)$questionId));
    }
        
    if($r){
        $data['msg'] = $configs['delete_msg'];
    }
    else{
        $data['msg'] = $configs['error_msg'];
    }
}

if($_POST['search'])
{
    $data['db_list'] = getQueryByJoin(array("question as q","predic_questions as p"), "q.*, p.id",array("p.pred_cat_id"=>(int)$_GET['pred_cat_id'], "p.set_id"=>(int)$_GET['set_id'], "p.predic_test_id"=>(int)$_GET['predic_test_id'], "q.question like "=>"'%".$_POST['search'] ."%'"),array("q.qid"=>"p.qid"), "p.id");
  
    $data['total'] = getTotalRowsByJoin(array("question as q","predic_questions as p"), array("p.pred_cat_id"=>(int)$_GET['pred_cat_id'], "p.set_id"=>(int)$_GET['set_id'], "p.predic_test_id"=>(int)$_GET['predic_test_id'], "q.question like "=>"'%".$_POST['search'] ."%'"), array("q.qid"=>"p.qid"));
}
else
{
    $data['db_list'] = getQueryByJoin(array("question as q","predic_questions as p"), "q.*, p.id",array("p.pred_cat_id"=>(int)$_GET['pred_cat_id'], "p.set_id"=>(int)$_GET['set_id'], "p.predic_test_id"=>(int)$_GET['predic_test_id']),array("q.qid"=>"p.qid"), "p.id");
  
    $data['total'] = getTotalRow("predic_questions", array("pred_cat_id"=>(int)$_GET['pred_cat_id'], "set_id"=>(int)$_GET['set_id'], "predic_test_id"=>(int)$_GET['predic_test_id']));
}

$data['page_title']="Prediction Test: ".$data['test_name']." > Set: ".$data['set_name']." > Category: ".$data['cat_name'];
$data['breadcrumb']=array("Prediction Question List"=>"",  
" > Total Questions: ".$data['total']=>"");

echo template($data);
?>

 
