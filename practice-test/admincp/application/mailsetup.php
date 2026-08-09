<?php 
include("../common/access.php");
include("../common/autoload.php");
$data['page_title']="Mail template  Management";
$data['breadcrumb']= array("Mail Template List"=>"");
 
$data['btn']="<input type='button' value='Add New' class='btn btn-success' onClick=\"javascript:window.location='mailsetup_entry'\">";
 
if($_GET['del_id'])
{
	deleteData("mailsetup",array("id"=>$_GET['del_id']));
	$data['msg']=$configs['delete_msg'];
}	
$data['mailsetup']=getQuery("mailsetup");
 
echo template($data);
?> 