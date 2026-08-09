<?php
include("../common/access.php");
include("../common/autoload.php");

if($_GET['del_id']):
   $r=deleteData("report_error",array("id"=>$_GET['del_id']));
   $data['msg']=$configs['delete_msg'];
endif;

if($_GET['id'] && $_GET['status']):
   $r=updateTable("report_error",array("status"=>$_GET['status']),array("id"=>$_GET['id']));
   $data['msg']=$configs['success_msg'];
   $_GET['status']="";
endif;

$query=[];
if($_GET['status'] || $_POST['status']) 
{
	if($_GET['status']){$_POST['status']=$_GET['status'];}
	$query=array_merge($query,['status'=>$_POST['status']]);
	$data['param']='status='.	$_POST['status'];
}
 

$data['total']=getTotalRow("report_error",$query);
$data['db_list']=getQuery("report_error","*",$query,"date desc",$configs['limit']);

$data['page_title']="Error Report Management ";
$data['breadcrumb']=array("Error Report List[" .$data['total'] ."]"=>"");

echo template($data); 
?>	 
 