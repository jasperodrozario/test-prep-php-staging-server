<?php
include("../common/access.php");
include("../common/autoload.php");

if($_GET['del_id']):
   $r=deleteData("get_tutor_msg",array("msg_id"=>$_GET['del_id']));
   if($r){
       $data['msg']=$configs['delete_msg']; 
   }else{
       $data['msg']="Deletion Not Working!";
   }
    
endif;

if($_GET['id'] && $_GET['status']):
   $r=updateTable("get_tutor_msg",array("status"=>$_GET['status']),array("msg_id"=>$_GET['id']));
   $data['msg']=$configs['success_msg'];
   $_GET['status']="";
endif;

$query=[];

if($_POST['key'])
{
	$query=array_merge($query,array("[or]"=>"(phone like '%" .$_POST['key'] ."%' or email like '%" .$_POST['key'] ."%' or msg_id=".(int)$_POST['key'].")"));
}

$data['total']=getTotalRow("get_tutor_msg",$query);
$data['db_list']=getQuery("get_tutor_msg","*",$query,"msg_id desc",$configs['limit']);

$data['page_title']="Tutor Request List";
$data['breadcrumb']=array("Tutor Request List[" .$data['total'] ."]"=>"");

echo template($data); 
?>	 
 