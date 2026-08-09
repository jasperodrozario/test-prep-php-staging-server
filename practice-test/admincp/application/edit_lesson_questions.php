<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['lesson_id']) {
    $_GET['lesson_id'] = $_POST['lesson_id'];
}
if (!$_GET['cat_id']) {
    $_GET['cat_id'] = $_POST['cat_id'];
}
if (!$_GET['course_id']) {
    $_GET['course_id'] = $_POST['course_id'];
}
if($_POST){$_REQUEST=$_POST;}

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_GET['course_id']));
$data['lesson_name'] = getFldValueById("lesson", "lesson_name", array("lesson_id" => $_GET['lesson_id']));
$data['option_no']=5;
		    
$data['db_list'] = getQueryByJoin(array("question as q","lesson_questions as l"), "q.*, l.order_no, l.HW, l.pt,l.online, l.id",array("l.id"=>(int)$_GET['id'],"l.lesson_id"=>(int)$_GET['lesson_id'], "l.cat_id"=>(int)$_GET['cat_id'], "l.course_id"=>(int)$_GET['course_id']),array("q.qid"=>"l.qid"), "l.order_no","1");

if ($_POST['order_no']){
    $updatedData = array(
        'order_no' => (int)$_POST['order_no'],
        'online' => $_POST['online'],
        'HW' => (int)$_POST['homework'],
        'pt' => (int)$_POST['practice_test']
    );
    
    updateTable("lesson_questions", $updatedData, array("id"=>$_POST['id']));
    $data['msg'] = $configs['success_msg'];
    
    header("location: questions_list?lesson_id=".$_GET['lesson_id']."&cat_id=".$_GET['cat_id']."&course_id=".$_GET['course_id']);
}

$data['page_title']="Course: ".$data['course_name']." > Category: ".$data['cat_name']." > Lesson: ".$data['lesson_name'];
$data['breadcrumb']=array("Edit Lesson Question"=>"");

echo template($data);
?>

 
