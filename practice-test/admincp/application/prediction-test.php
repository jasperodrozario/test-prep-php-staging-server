<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Prediction Test List";
$data['breadcrumb']=array("Prediction Test List"=>"");
$data['btn']="<a href='prediction-test-list' class='btn btn-success'>Manage Pred Ques Bank</a> <a href='prediction_entryform' class='btn btn-success'>Add Prediction Test</a>";

if($_GET['del_id']) 
{
   deleteData("predic_test",array("Predic_test_id"=>$_GET['del_id']));
   deleteData("prediction_test_category",array("pred_test_id"=>$_GET['del_id']));
   deleteData("predic_questions",array("predic_test_id"=>$_GET['del_id']));
   deleteData("predic_question_set",array("predic_test_id"=>$_GET['del_id']));
    
   $data['msg']=$configs['delete_msg'];
}

if($_GET['del_cat_id']) 
{
   deleteData("prediction_test_category",array("id"=>$_GET['del_cat_id']));
   deleteData("predic_questions",array("pred_test_id"=>$_GET['predic_test_id'], "cat_id"=>$_GET['cat_id']));
    
   $data['msg']=$configs['delete_msg'];
}
//$data['db_list']=getQuery("predic_test","*");
  

$data['db_list']=getQuery("course","*","","course_id");

$prediction=array();
$total_predic=array();
foreach($data['db_list'] as $d)
{
	$query=array("course_id"=>$d['course_id']);
	
	$total_predic[$d['course_id']]=getTotalRow("predic_test",$query);
	
    if($total_predic[$d['course_id']])
    {
		$prediction[$d['course_id']]=getQuery("predic_test","*",$query,"test_order");
        
        foreach($prediction[$d['course_id']] as $c)
        {
            $data['pred_test_cat'][$c['Predic_test_id']] = getQuery("prediction_test_category","*",array("pred_test_id"=>$c['Predic_test_id']),"order_no");
        }
	} 
}

$data['total_predic']=$total_predic;
$data['prediction_test']=$prediction;

echo template($data); 
?> 
