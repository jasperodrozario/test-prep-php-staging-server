<?php
include("includes/access.php");
include("includes/autoload.php");

if($_GET['del_id']) 
{
   deleteData("questions",array("id"=>$_GET['del_id']));
   $data['msg']=$configs['delete_msg'];
} 


$data['page_title']="View Question List";

$data['breadcrum']=array("Question List"=>"questions_list.php"," Question  List"=>"");

if($_REQUEST['set_id'])
{
	$data['set_name']=getFldValueById("questions_set","set_name",array("id"=>$_REQUEST['set_id']));
	
} 
 
$data['btn']="<a  href='questions_list.php?set_id=".$_REQUEST['set_id'] . "' class='btn btn-success'> Question List</a>";

  


$query=array("s.set_id"=>$_REQUEST['set_id']);

 
$data['page_title']="Question List of <b>".$data['set_name'] ."</b>";

$limit=(($_REQUEST['limit']-1)*30) . ",30";      

$data['db_values']=getQueryByJoin(array("questions as q","questions_set_questions as s"),"q.*",$query,array("q.id"=>"s.question_id"),"q.id desc",$limit,1);
 
echo template($data); 
?>