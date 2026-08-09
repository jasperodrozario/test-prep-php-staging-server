<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Category List";
$course_name = getFldValueById("course","course_name",array("course_id"=>$_GET['course_id']));
$data['breadcrumb']=array("Courses > " . $course_name=> $configs['vpath'] . "/admincp/course_list", " Categories"=>"");

$data['course'] = getDataByWhere("course","*",array("course_id"=>$_GET['course_id']));

$data['btn']="<a  href='category_entryform?course_id=" . $_GET['course_id'] . "'  class='btn btn-success'>Add Category</a>";

if($_GET['lesson_del_id']) 
{
   deleteData("lesson",array("lesson_id"=>$_GET['lesson_del_id']));
   $data['msg']=$configs['delete_msg'];
   header("location:category_list?course_id=".$_GET['course_id']);
}  
 
if($_GET['del_id']) 
{
   deleteData("category",array("cat_id"=>$_GET['del_id'], "course_id"=>$_GET['course_id']));
   deleteData("lesson",array("cat_id"=>$_GET['del_id'], "course_id"=>$_GET['course_id']));
   deleteData("lesson_questions",array("cat_id"=>$_GET['del_id'], "course_id"=>$_GET['course_id']));
   
   $data['msg']=$configs['delete_msg'];
}  

$data['db_list']=getQuery("category","*",array("course_id"=>$_GET['course_id']),"category_order");

$lessons=array();
$total_lessons=array();
foreach($data['db_list'] as $d)
{
	$query=array("cat_id"=>$d['cat_id'], "course_id"=>$_GET['course_id']);
	
	$total_lessons[$d['cat_id']]=getTotalRow("lesson",$query);
	
    if($total_lessons[$d['cat_id']])
    {
		$lessons[$d['cat_id']]=getQuery("lesson","*",$query,"lesson_order");
   
	} 
}

$data['total_lessons']=$total_lessons;
$data['lessons']=$lessons;

echo template($data); 
?> 
