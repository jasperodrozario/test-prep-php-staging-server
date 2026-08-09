<?php 
include("../common/access.php");
include("../common/autoload.php");
 

# Title & BreadCrumb===================
$data['page_title']="Page Content Management";

$data['breadcrumb']=array("Page Content List"=>"content_list");

$data['btn']="<input type='button' value='Add New' class='btn btn-success' onClick=\"javascript:window.location='page.editor?mid=62'\">";

#End========================== 
 
if($_REQUEST['del_id'])
{
   deleteData("contents",array("id"=>$_REQUEST['del_id']));  
   $data['msg']=$configs["delete_msg"]; 
}

#fetch data for list

$data['contents']=getQuery("contents","*",array("parent_id"=>0,"status !"=>"S"));

foreach($data['contents'] as $d)
{
	$data['contents_sub'][$d['id']]=getQuery("contents","*",array("parent_id"=>$d['id'],"menu"=>"N"));
}

$data['total']=getTotalRow("contents",array("parent_id"=>0,"status !"=>"S"));
 
echo template($data); 
?>
