<?php
include("../../common/access.php");
include("../../common/autoload.php");


if(!$_GET['action'])
{
	$_GET['action']="feedback-list";
}

$data['breadcrumb']=[];
$data['page_title']="Feedback Managment";
 
switch($_GET['action'])
{

	
	case "add-feedback":
		$data['page_subtitle']="Add Feedback";

		$data['breadcrumb']=array("Feedback Management"=>$configs['admin_vpath'] ."feedback/feedback-list","Add | Edit Feedback"=>"");
		 
		if($_GET['id'])
		{
			 
			$data['db_value']= getDataByWhere("ocs_feedback","*",array("id"=>$_GET['id']));
		
	    }
			 
        
        $data['courses']=getQuery("ocs_course","*",array(),"name");
	
	break;


	default:

		if($_GET['del_id'])
		{

			$r=deleteData("ocs_feedback",array("id"=>$_GET['del_id']));
		
			$data['msg']=$configs['delete_msg'];

			$data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."feedback/feedback-list';\",2000);</script>";
		}
	    
	    $data['total']=getTotalRow('ocs_feedback');
	    
	 	$data['page_subtitle']="Feedback List (".$data['total'].")";

		$data['breadcrumb']=array("Feedback"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."feedback/add-feedback' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add New </a>";
 
		
	    $data['db_list']= getQuery('ocs_feedback','*',"","id desc",$configs['limit']);

	    $course_name=[];

	    foreach($data['db_list'] as $d) 
	    {
			if($d['course_id'])
			{
				$course_name[$d['course_id']]=getFldValueById('ocs_course','name',array("course_id"=>$d['course_id']));
			}
			
	    }	
        $data['courses_name']=$course_name;

}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

