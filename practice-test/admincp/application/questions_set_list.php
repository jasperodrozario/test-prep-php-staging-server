<?php
include("../common/access.php");
include("../common/autoload.php");

if($_GET['del_id']) 
{
   deleteData("questions_set",array("id"=>$_GET['del_id']));
   $data['msg']=$configs['delete_msg'];
}   
 
$data['page_title']="Question Set Management";

$data['breadcrum']=array("Question Set Management"=>"questions_set_list","Question Set List"=>"");

$data['btn']="<a href='modal_questions_set_form' class='btn btn-success'  data-toggle='modal' data-target='#myModalEdit'>Add Question Set</a>";

$data['db_values']=getQuery("questions_set");


echo template($data); 
?>