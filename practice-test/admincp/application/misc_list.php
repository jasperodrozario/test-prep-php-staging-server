<?php 
include("../common/access.php");
include("../common/autoload.php");

# Title & BreadCrumb===================
$data['page_title']="Story  Management";

$data['breadcrumb']=array("Story List"=>"");

$data['btn']="<input type='button' value='Add New Story' class='btn btn-success' onClick=\"javascript:window.location='misc.editor'\">";
#End========================== 
 
if($_REQUEST['del_id'])
{
   deleteData("story",array("story_id"=>$_REQUEST['del_id']));  
   $data['msg']=$configs["delete_msg"]; 
}

$query=[];
#fetch data for list
 
if($_POST['id'])
{
	$query=array_merge($query,array("story_id"=>$_POST['id']));
}

if($_POST['title'])
{
	$query=array_merge($query,array("story_title like "=>"'%".$_POST['title'] ."%'"));
}
	
$data['contents']=getQuery("story","*",$query,"story_id desc",$configs['limit']);

$data['total']=getTotalRow("story",$query);

echo template($data); 
?>
