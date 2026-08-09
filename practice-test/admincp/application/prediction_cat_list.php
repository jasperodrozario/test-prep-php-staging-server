<?php
include("../common/access.php");
include("../common/autoload.php");

$data['pred_test_name'] = getFldValueById("predic_test","test_name",array("Predic_test_id"=>$_GET['predic_test_id']));

$data['page_title']=$data['pred_test_name']. " Category List";
$data['breadcrumb']=array($data['pred_test_name']." Set List"=>"prediction_set_list?predic_test_id=".$_GET['predic_test_id'], " Category List"=>"");

$data['btn']="<a href='#' class='set-pred-set-btn btn btn-success' data-test-id=" .$_GET['predic_test_id'] . " data-set-id=".$_GET['set_id'] . " data-toggle='modal' data-target='#setPredSetModal'>Add Pred Test Category</a>";

$course_id = getFldValueById("predic_test","course_id",array("Predic_test_id"=>$_GET['predic_test_id']));
$data['categories'] = getQuery("category", "*",array("course_id"=>$course_id),"category_name");

if($_GET['del_id']) 
{
   deleteData("prediction_test_category",array("id"=>$_GET['del_id']));
   deleteData("predic_questions",array("pred_cat_id"=>$_GET['del_id']));
    
   $data['msg']=$configs['delete_msg'];
}
$data['db_list']=getQuery("prediction_test_category","*",array("pred_test_id"=>$_GET['predic_test_id']),"id");

echo template($data); 
?> 
