<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Test Name List";

$data['breadcrumb']=array("Test List"=>"");

$data['btn']="<a  href='test-entryform'  class='btn btn-success'>Add New</a>";

if($_GET['del_id']) 
{
   deleteData("test",array("id"=>$_GET['del_id']));
   $data['msg']=$configs['delete_msg'];
}  
     

$data['db_list']=getQuery("test","*",array(),"ord");
 

echo template($data); 
?> 
