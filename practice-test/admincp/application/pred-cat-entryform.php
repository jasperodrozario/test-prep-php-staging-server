<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Add Pred Category";
$data['breadcrumb'] = array(
	"Prediction Test List" => "prediction-test",
	"Pred Category List" => "prediction-test-list",
	"Add Pred Category" => ""
);

if ($_POST['SBMT_REG']):

	if ($_POST['pred_id']):
		$r = updateTable("pred_test_category", $_POST, array("pred_id" => $_POST['pred_id']));

		$pred_id = $_POST['pred_id'];
	else:
		unset($_POST['pred_id']);
		$pred_id = insertTable("pred_test_category", $_POST);


	endif;
	if (!mysqli_errno($configs['con'])):
		$data['msg'] = $configs['success_msg'];
		header("location:prediction-test-list");

	endif;

endif;


if ($_REQUEST['pred_id']) {
	$data['db_value'] = getDataByWhere("pred_test_category", "*", array("pred_id" => $_REQUEST['pred_id']));
}



echo template($data);
