<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Prediction Category List";

$data['breadcrumb'] = array(
   "Prediction Test List" => "prediction-test",
   "Prediction Category List" => ""
);


$data['btn'] = "<a  href='pred-cat-entryform'  class='btn btn-success'>Add Prediction Category</a>";

if ($_GET['del_id']) {
   deleteData("lesson_questions", array("pred_id" => $_GET['del_id']));
   deleteData("pred_test_category", array("pred_id" => $_GET['del_id']));

   $data['msg'] = $configs['delete_msg'];
}

$data['pred_cat_list'] = getQuery("pred_test_category", "*", array(), "ord");

echo template($data);
