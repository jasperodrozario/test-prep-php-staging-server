<?php
include("../common/access.php");
include("../common/autoload.php");

if($_GET['del_id']):
   $r=deleteData("enquiry",array("id"=>$_GET['del_id']));
   $data['msg']=$configs['delete_msg'];
endif;

if($_GET['id'] && $_GET['status']):
   $r=updateTable("enquiry",array("status"=>$_GET['status']),array("id"=>$_GET['id']));
   $data['msg']=$configs['success_msg'];
   $_GET['status']="";
endif;

$query=[];

if($_GET['reg_at'])
{
//	$query=array_merge($query,array("reg_at"=>$_GET['reg_at']));
}
if($_GET['test_id'])
{
//	$query=array_merge($query,array("test_id"=>$_GET['test_id']));
}

$data['test']=getQuery("course","*",array(),"course_id");
$test_name=[];

$student_total=[];

foreach($data['test'] as $t)
{
	$student_total[$t['course_id']]=getTotalRow("enquiry",array_merge($query,array('test_id'=>$t['course_id'])));
   
 
    $test_name[$t['course_id']]=$t['course_name'];
    
}	
$data['test_name']=$test_name;
$data['student_total']=$student_total;

if($_POST['key'])
{
	$query=array_merge($query,array("[or]"=>"(mobile like '%" .$_POST['key'] ."%' or email like '%" .$_POST['key'] ."%' or id=".(int)$_POST['key'].")"));
	
}


$data['total']=getTotalRow("enquiry",$query);
$data['db_list']=getQuery("enquiry","*",$query,"id desc",$configs['limit']);

$data['page_title']="Enquiry Management ";
$data['breadcrumb']=array("Enquiry List[" .$data['total'] ."]"=>"");

echo template($data); 
?>	 
 