<?php
include("../common/access.php");
include("../common/autoload.php");


$course_id = getFldValueById("predic_test", "course_id", array("Predic_test_id" => $_GET['test_id']));
$test_name = getFldValueById("predic_test", "test_name", array("Predic_test_id" => $_GET['test_id']));
$data['categories'] = getQuery("category", "*", array("course_id" => $course_id), "category_name");

$data['page_title'] = "Add Category In " . $test_name;
$data['breadcrumb'] = array(
    "Prediction Test List" => "prediction-test",
    "Add Category - ".$test_name => ""
);

$data['msg'] = "";

if (!$_GET['test_id']) {
    $_GET['test_id'] = $_POST['test_id'];
}

$data['test_id'] = $_POST['test_id'];

if (!$_GET['id']) {
    $_GET['id'] = $_POST['id'];
}

if (!$_GET['cat_id']) {
    $_GET['cat_id'] = $_POST['cat_id'];
}


//  "order"=>$_POST['order'],


if ($_GET['id']) {
    $data['cat_data'] = getDataByWhere("prediction_test_category", "*", array("id" => (int)$_GET['id']));
}

if (isset($_POST['add_btn'])) {

    if ($_GET['id']) {
        $r = updateTable("prediction_test_category", array(
            "cat_id" => $_POST['cat_id_drop'],
            "number_of_questions" => $_POST['num_ques'],
            "order_no" => $_POST['order'],
            "pred_qb_id" => $_POST['pred_qb_id'],
            "duration" => $_POST['duration']

        ), array("id" => (int)$_GET["id"]), 1);
    } else {
        $r = insertTable("prediction_test_category", array(
            "cat_id" => $_POST['cat_id_drop'],
            "number_of_questions" => $_POST['num_ques'],
            "pred_test_id" => $_GET['test_id'],
            "order_no" => (int)$_POST['order'],
            "pred_qb_id" => $_POST['pred_qb_id'],
            "duration" => $_POST['duration']
        ));
    }

    if ($r) {
        $data['msg'] = "<p><img src='" . $configs['vpath'] . "img/on.png'> Prediction Test Category has been saved successfully.</p>";
    } else {
        $data['msg'] = $configs['error_msg'];
    }

    //header("location:" . $configs['vpath'] ."admincp/prediction-test");
}

/*
//delete category
if($_GET['del_cat_id']) 
{
   deleteData("prediction_test_category",array("id"=>$_GET['del_cat_id']));
   
    
   $data['msg']=$configs['delete_msg'];
}

*/
$data['pred_test_cat'] = getQuery("prediction_test_category", "*", array("pred_test_id" => $_GET['test_id']), "order_no");
$data['ques_sources'] = getQuery("pred_test_category", "*", array(), "ord");


echo template($data);
