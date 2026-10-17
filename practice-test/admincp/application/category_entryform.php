<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Add Category";
$data['breadcrumb'] = array("Category List" => "category_list?course_id=" . $_GET['course_id'], "Add Category" => "");

if (isset($_POST['SBMT_REG'])) {

	$text_fields = ['category_name', 'description', 'shortcode', 'online', 'free'];
	$int_fields = [
		'course_id',
		'category_order',
		'num_of_ques_pt',
		'num_of_story_pt',
		'free_test',
		'num_free_test_ques',
		'free_pred_test',
		'num_free_pred_test_ques'
	];

	$filtered_data = [];
	foreach ($text_fields as $f) {
		if (isset($_POST[$f])) {
			$filtered_data[$f] = mysqli_real_escape_string($configs['con'], trim($_POST[$f]));
		}
	}
	// blank number fields are skipped (DB is in strict mode, '' is not a valid int)
	foreach ($int_fields as $f) {
		if (isset($_POST[$f]) && $_POST[$f] !== '') {
			$filtered_data[$f] = (int)$_POST[$f];
		}
	}

	if (empty($filtered_data['category_name']) || empty($filtered_data['course_id'])) {
		$data['msg'] = "<p><img src='" . $configs['vpath'] . "img/off.png'> <font color=red>Category name and course are required.</font></p>";
	} else {
		if (!empty($_POST['cat_id'])) {
			$cat_id = (int)$_POST['cat_id'];
			updateTable("category", $filtered_data, ["cat_id" => $cat_id]);
		} else {
			$cat_id = insertTable("category", $filtered_data);
		}

		if (!mysqli_errno($configs['con'])) {
			if ($_POST['backpage'] == "free") {
				header("location:category-list-free-test?course_id=" . $filtered_data['course_id']);
			} else if ($_POST['backpage'] == "free-pred") {
				header("location:category-list-free-pred-test?course_id=" . $filtered_data['course_id']);
			} else {
				header("location:category_list?course_id=" . $filtered_data['course_id']);
			}
			exit;
		}

		$data['msg'] = $configs['error_msg'];
	}
}

$data['course'] = getQuery("course", "*", "", "course_name");

if ($_REQUEST['cat_id']) {
	$data['db_value'] = getDataByWhere("category", "*", array("cat_id" => (int)$_REQUEST['cat_id']));
}

echo template($data);

?>