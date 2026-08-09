<?php
include("../common/access.php");
include("../common/autoload.php");

if($_GET['del_id']):
   $r=deleteData("app_feedback",array("feedback_id"=>$_GET['del_id']));
   if($r){
        $data['msg']=$configs['delete_msg'];
   }else{
        $data['msg']="Error deleting feedback.";
   }
endif;

if($_GET['id'] && $_GET['status']):

    if($_GET['status']=="Y"){
        $_GET['status']=1;
    }else{
        $_GET['status']=0;
    }

   $r=updateTable("app_feedback",array("is_resolved"=>$_GET['status']),array("feedback_id"=>$_GET['id']));
   $data['msg']=$configs['success_msg'];
   $_GET['status']="";
endif;

$query=[];

$data['test']=getQuery("course","*",array(),"course_id");
$test_name=[];

$student_total=[];

foreach($data['test'] as $t)
{
	$student_total[$t['course_id']]=getTotalRow("app_feedback",array_merge($query,array('course_name'=>$t['course_name'])));
   
 
    $test_name[$t['course_id']]=$t['course_name'];
    
}	
$data['test_name']=$test_name;
$data['student_total']=$student_total;

if($_POST['key'])
{
	$query=array_merge($query,array("[or]"=>"(platform like '%" .$_POST['key'] ."%' or email like '%" .$_POST['key'] ."%' or feedback_id=".(int)$_POST['key'].")"));
	
}

$data['total']=getTotalRow("app_feedback",$query);
$data['db_list']=getQuery("app_feedback","*",$query,"feedback_id desc",$configs['limit']);

$data['page_title']="App Feedback Management ";
$data['breadcrumb']=array("App User Feedback List[" .$data['total'] ."]"=>"");

echo template($data); 
?>	 
 