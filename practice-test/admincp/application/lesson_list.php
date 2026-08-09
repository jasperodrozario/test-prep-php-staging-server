<?php
require_once("includes/access.php");
require_once("includes/autoload.php");

if(isset($_GET['delete_course_id'])):
	deleteData("course","course_id=". $_GET['delete_course_id']);
endif;
$total=getTotalRow("course"); 

$data['page_title']="Lesson Management (" . $total ." Courses)";
$data['breadcrum']=array("Lesson List"=>"");
$btn="<a   class='btn btn-success' href='lesson_entryform.php?cat_id=". $_REQUEST['cat_id'] ."'\" style='float:right;margin-right:36px' />Add New</a>";

$list_array=array("course_id", "name","cost",array("category","cat_name","cat_id"));

$action="<a href='lessson_entryform.php?course_id={id}'>Edit</a> |  <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='" . $_SERVER['PHP_SELF'] . "?delete_course_id={id}';}\">Delete</a>";
#print grid list
echo getList("course",$list_array,$action,"","course_id desc");

echo template($data);
?>
