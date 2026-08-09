<?php
include("../common/access.php");
include("../common/autoload.php");

$data['pred_test_name'] = getFldValueById("predic_test","test_name",array("Predic_test_id"=>$_GET['predic_test_id']));

$data['page_title']=$data['pred_test_name']. " Set List";
$data['breadcrumb']=array("Prediction Test List"=>"prediction-test", $data['pred_test_name']." Set List"=>"");
$data['btn']="<a href='#' class='set-pred-set-btn btn btn-success' data-test-id=" .$_GET['predic_test_id'] . " data-toggle='modal' data-target='#setPredSetModal'>Add Prediction Test Set</a>";

if($_GET['del_id']) 
{
   deleteData("predic_question_set",array("set_id"=>$_GET['del_id']));
   deleteData("predic_questions",array("set_id"=>$_GET['del_id']));
   $data['msg']=$configs['delete_msg'];
}
$data['db_list']=getQuery("predic_question_set","*",array("predic_test_id"=>$_GET['predic_test_id']),"set_id");

echo template($data); 
?> 
