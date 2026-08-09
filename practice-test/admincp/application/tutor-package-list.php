<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Tutoring Package List";
$data['breadcrumb']=array("Tutoring Package List"=>"");
$data['btn']="<a  href='tutor-details-form'  class='btn btn-success'>Add New Package</a>";

if($_GET['del_id']) 
{
   deleteData("subscriptions_price", array("price_id"=>$_GET['del_id']));
    
   $data['msg']=$configs['delete_msg'];
}  

$data['db_list']=getQuery("course","*","","course_id");

$package_list=array();
$total_package=array();
foreach($data['db_list'] as $d)
{
	$query=array("course_id"=>$d['course_id']);
	
	$total_package[$d['course_id']]=getTotalRow("subscriptions_price",$query);
	
    if($total_package[$d['course_id']])
    {
		$package_list[$d['course_id']]=getQuery("subscriptions_price","*",$query,"subs_id, price_id");
	} 
}

$data['total_package']=$total_package;
$data['package_list']=$package_list;

echo template($data); 
?> 
