<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Add Category";
$data['breadcrumb'] = array("Category List" => "category_list?course_id=" . $_GET['course_id'], "Add Category" => "");

if (isset($_POST['SBMT_REG'])) {

	if (!empty($_POST['cat_id'])) {

		$allowed_fields = [
			'category_name',
			'description',
			'course_id',
			'category_order',
			'online',
			'free',
			'shortcode',
			'num_of_ques_pt',
			'num_of_story_pt',
			'free_test',
			'num_free_test_ques',
			'free_pred_test',
			'num_free_pred_test_ques'
		];

		$filtered_data = array_filter(
			array_intersect_key($_POST, array_flip($allowed_fields)),
			fn($v) => $v !== '' && $v !== null
		);

		$r = updateTable(
			"category",
			$filtered_data,
			["cat_id" => $_POST['cat_id'], "course_id" => $_POST['course_id']]
		);

		$cat_id = $_POST['cat_id'];

		if ($r) {
			$data['msg'] = $configs['success_msg'];
		} else {
			$data['msg'] = $configs['error_msg'];
		}

		$data['checking_data'] = $filtered_data;
		$data['flag'] = "i'm here";
	} else {
		unset($_POST['cat_id']);

		$cat_id = insertTable("category", $_POST);

		if ($cat_id) {
			$data['msg'] = $configs['success_msg'];
		} else {
			$data['msg'] = $configs['error_msg'];
		}
	}

	if (!mysqli_errno($configs['con'])) {

		if ($_POST['backpage'] == "free") {
			header("location:category-list-free-test?course_id=" . $_POST['course_id']);
			exit;
		} 
		else if($_POST['backpage'] == "free-pred"){
			header("location:category-list-free-pred-test?course_id=" . $_POST['course_id']);
			exit;
		} 
		else {
			header("location:category_list?course_id=" . $_POST['course_id']);
		}
	}
}

$data['course'] = getQuery("course", "*", "", "course_name");

if ($_REQUEST['cat_id']) {
	$data['db_value'] = getDataByWhere("category", "*", array("cat_id" => $_REQUEST['cat_id'], "course_id" => $_GET['course_id']));
}

echo template($data);

?>