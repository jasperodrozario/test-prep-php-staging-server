<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Course Group List";

$data['breadcrumb']=array("Course List"=>"course_list", "Course Group List"=>"");

$data['btn']="<a href='#' class='set-pred-set-btn btn btn-success' data-test-id=" .$_GET['predic_test_id'] . " data-set-id=".$_GET['set_id'] . " data-toggle='modal' data-target='#setPredSetModal'>Create Course Group</a>";

if($_GET['del_id']) 
{
   deleteData("course_group",array("group_id"=>$_GET['del_id']));
    
   $data['msg']=$configs['delete_msg'];
}  
     
$data['course_group_list'] = getQuery("course_group","*","","ord");

echo template($data); 
?> 
