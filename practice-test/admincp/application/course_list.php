<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Course List";

$data['breadcrumb']=array("Course List"=>"");

$data['btn']="<a href='course-group' class='btn btn-success'>Course Group</a> <a href='course_entryform' class='btn btn-success'>Add Course</a>";

if($_GET['del_id']) 
{
   deleteData("course",array("course_id"=>$_GET['del_id']));
   deleteData("category",array("course_id"=>$_GET['del_id']));
   deleteData("lesson",array("course_id"=>$_GET['del_id']));
   deleteData("lesson_questions",array("course_id"=>$_GET['del_id']));
   deleteData("predic_test",array("course_id"=>$_GET['del_id']));
   deleteData("predic_questions",array("course_id"=>$_GET['del_id']));
   deleteData("predic_question_set",array("course_id"=>$_GET['del_id']));
    
   $data['msg']=$configs['delete_msg'];
}  
     
$data['course_group_list'] = getQuery("course_group","*","","ord");
foreach($data['course_group_list'] as $c){
    $data['course_list'][$c['group_id']]=getQuery("course","*",array("group_id"=>$c['group_id']),"ord");
}

echo template($data); 
?> 
