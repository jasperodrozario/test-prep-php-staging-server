<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Free Test Category List";

$course_name = getFldValueById("course","course_name",array("course_id"=>$_GET['course_id']));

$data['breadcrumb']=array($course_name=> $configs['vpath'] . "/admincp/course-list-free-test", " Free Practice Test Categories"=>"");

$data['db_list']=getQuery("category","*",array("course_id"=>$_GET['course_id']),"category_order");

echo template($data); 
?> 
