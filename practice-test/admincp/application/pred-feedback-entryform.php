<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add Feedback";
$data['breadcrumb']=array("Web User Feedback List"=>"pred-feedback-list","Add Feedback"=>"");

if($_POST['SBMT_REG']){

	$is_online = isset($_POST['is_online']) ? 1 : 0;
	$created_at = $_POST['created_at'] ?? date('Y-m-d H:i:s');

	$tmp_data = array(
		"user_name" => $_POST['user_name'],
		"course_id" => $_POST['course_id'],
		"rating" => $_POST['rating'],
		"feedback" => $_POST['feedback'],
		"is_online" => $is_online,
		"created_at" => $created_at
	);
	
   	if($_POST['feedback_id']){
   	    $r=updateTable("prediction_test_feedback",$tmp_data,array("feedback_id"=>$_POST['feedback_id']));
		$success_flag = $r;
	
	} else{
		unset($_POST['feedback_id']);
	    $success_flag = insertTable("prediction_test_feedback",$tmp_data);
	}

	if($success_flag){
		$data['feedback_msg']=$configs['success_msg'];
	} else{
		$data['feedback_msg']=$configs['error_msg'];
	}
}

$data['db_value'] = getDataByWhere(
	"prediction_test_feedback",
	"*",
	["feedback_id" => $_GET['feedback_id']]
);

echo template($data);
?>

 