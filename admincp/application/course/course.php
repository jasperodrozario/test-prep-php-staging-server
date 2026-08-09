<?php
include("../../common/access.php");
include("../../common/autoload.php");

$data['module']=basename(dirname(__FILE__));



if(!$_GET['action'])
{
	$_GET['action']="course-list";
}

$data['breadcrumb']=[];
$data['page_title']="Course Management";
 
switch($_GET['action'])
{

	case "course-list":
		 
		$data['btn']="<a href='" . $configs['admin_vpath']."course/add-course' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Course</a>";
	 
		$data['db_list']= getQuery('ocs_course','*',"","ord asc",$configs['limit']);

		$data['total']=getTotalRow('ocs_course');
		 
		 
		$data['page_subtitle']="Course List (".$data['total'].") ".$cat_name;

		$data['breadcrumb']=array("Course"=>"");
 		
	    if($_GET['del_id'])
		{

			$r=deleteData("ocs_course",array("course_id"=>$_GET['del_id']));
		 
			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 
		}

	break;

	case "add-course":
	
		$data['page_subtitle']="Add | Edit Course";

		$data['breadcrumb']=array("Course List"=>$configs['admin_vpath'] ."course/course-list","Add | Edit Course"=>"");
 	 
		if($_GET['course_id'])
		{
		   $data['db_value']= getDataByWhere('ocs_course','*',array('course_id'=>$_GET['course_id']));
		 
		}
	
	break; 
}
  
$data['action']=$_GET['action'];
echo template($data); 
?>