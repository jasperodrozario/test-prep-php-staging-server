<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Tutoring Details List";
$data['breadcrumb']=array("Tutoring Details List"=>"");
$data['btn']="<a  href='tutoring-details-form'  class='btn btn-success'>Add New Details</a>";

if($_GET['del_id']) 
{
   deleteData("subscriptions_details", array("det_id"=>$_GET['del_id']));
    
   $data['msg']=$configs['delete_msg'];
}  

$data['db_list']=getQuery("course","*","","course_id");

$package_list=array();
$total_package=array();
foreach($data['db_list'] as $d)
{
	$query=array("course_id"=>$d['course_id']);
	
	$total_package[$d['course_id']]=getTotalRow("subscriptions_details",$query);
	
    if($total_package[$d['course_id']])
    {
		$package_list[$d['course_id']]=getQuery("subscriptions_details","*",$query,"ord");
	} 
}

$data['total_package']=$total_package;
$data['package_list']=$package_list;

echo template($data); 
?> 
