<?php
include("../../common/access.php");
include("../../common/autoload.php");


if($_GET['cat_id'])
{
	$_GET['cat_id']=decrypt($_GET['cat_id']);
}


if($_GET['course_id'])
{
	$_GET['course_id']=decrypt($_GET['course_id']);
}


if($_GET['chapter_id'])
{
	$_GET['chapter_id']=decrypt($_GET['chapter_id']);
}


 
if($_GET['del_id'])
{
	$_GET['del_id']=decrypt($_GET['del_id']);
}


if($_GET['del_course'])
{
	$_GET['del_course']=decrypt($_GET['del_course']);
}

if(!$_GET['action'])
{
	$_GET['action']="category-list";
}

$data['breadcrumb']=[];
$data['page_title']="Course Management";
 
switch($_GET['action'])
{
	case "category-list":
	
		$data['page_subtitle']="Course Category List";

		$data['breadcrumb']=array("Category"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."course/add-category' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Course Category</a>";
		$data['total']=getTotalRow('category');

		$total_questions=array();
		$lessons=array();
		$total_lessons=array();
		$total_questions_hw=array();
		$total_questions_pt=array();
		$total_questions_ft=array();
		$data['spc']=array(70,19,18);

	   

	    $data['db_list']=getQuery("category","*",array("parent_id"=>0),"ord");



	    foreach($data['db_list'] as $d)
		{
			$total_lessons[$d['cat_id']]=getTotalRow("course",array("cat_id"=>$d['cat_id']));
			
		    if($total_lessons[$d['cat_id']])
		    {
				$lessons[$d['cat_id']]=getQuery("course","*",array("cat_id"=>$d['cat_id']),"name");
				
				foreach($lessons[$d['cat_id']] as $l)
				{
					if(!in_array($d['cat_id'],$data['spc']))
					{
						$total_questions_hw[$l['course_id']]=getTotalRow("questions",array("cat_id"=>$d['cat_id'],"course_id"=>$l['course_id'],"qtype"=>2));
			    	    $total_questions_pt[$l['course_id']]=getTotalRow("questions",array("cat_id"=>$d['cat_id'],"course_id"=>$l['course_id'],"qtype"=>1));
			
					}
					else
					{
						
						$total_questions_ft[$l['course_id']]=getTotalRow("questions",array("cat_id"=>$d['cat_id'],"course_id"=>$l['course_id']));
			 
			
					}
				
				}
		   
			} 
			$total_questions[$d['cat_id']]=getTotalRow("questions",array("cat_id"=>$d['cat_id']));

			
		}



		$data['total_questions_hw']=$total_questions_hw;
		$data['total_questions_pt']=$total_questions_pt;
		$data['total_questions_ft']=$total_questions_ft;

		$data['total_lessons']=$total_lessons;
		$data['lessons']=$lessons;
		$data['total_questions']=$total_questions;


	    



	    if($_GET['del_id'])
		{

			$r=deleteData("category",array("cat_id"=>$_GET['del_id']));
		
			$data['msg']=$configs['delete_msg'];
		}


		 if($_GET['del_course'])
		{

			$r=deleteData("course",array("course_id"=>$_GET['del_course']));
		
			$data['msg']=$configs['delete_msg'];
		}


		
	
	break;

	
	case "add-category":
		$data['page_subtitle']="Add Course Category";

		$data['breadcrumb']=array("Course Category"=>$configs['admin_vpath'] ."course/category-list","Add | Edit Course Category"=>"");

		$data['segments']= getQuery('segment','*',"","cat_name asc");
		 
		if($_GET['cat_id'])
		{
		   


		    $data['db_value']=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));
		}
	
	break;




	case "lesson-list":

		$cat_name=getFldNameById("segment","cat_name",array("cat_id"=>$_GET['cat_id']));
	
		$data['page_subtitle']="Subject List of ".$cat_name;

		$data['breadcrumb']=array("Subject"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."segment/add-subject/" . $_GET['cat_id']."' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Subject</a>";
		$data['total']=getTotalRow('subject',array("cat_id"=>$_GET['cat_id']));


	    $data['db_list']= getQuery('subject','*',array("status"=>"Y","cat_id"=>$_GET['cat_id']),"id");

	    if($_GET['del_id'])
		{

			$r=deleteData("subject",array("id"=>$_GET['del_id']));
		
			$data['msg']=$configs['delete_msg'];
		}
	
	break;

	case "add-lesson":
		$data['page_subtitle']="Add | Edit Course";

		$data['breadcrumb']=array("Course Category"=>$configs['admin_vpath'] ."course/category-list","Add | Edit Course"=>"");

		$data['category']= getQuery("category","*",array("parent_id"=>0),"cat_name");
		 
		if($_GET['course_id'])
		{
		   $data['db_value']= getDataByWhere('course','*',array('course_id'=>$_GET['course_id']));
		}
	
	break;


	case "add-chapter":
		$data['page_subtitle']="Add | Edit Chapter";

		$data['breadcrumb']=array("Chapter list"=>$configs['admin_vpath'] ."course/chapter-list?course_id=".$_GET['course_id'],"Add | Edit Course"=>"");

		
		 
		if($_GET['chapter_id'])
		{
		   $data['db_value']= getDataByWhere('chapter','*',array('chapter_id'=>$_GET['chapter_id']));
		}
	
	break;



	case "chapter-list":



		$name=getFldNameById("course","name",array("course_id"=>$_GET['course_id']));
	
		$data['page_subtitle']="Chapter List of ".$name;

		$data['breadcrumb']=array("Chapter"=>"");
		

		$data['btn']="<a href='".$configs['admin_vpath'] ."course/add-chapter?course_id=" . $_GET['course_id'] . "&cat_id=" . $_GET['cat_id']."' class='btn btn-success pull-right'>Add Chapter</a>";

		


		$data['total']=getTotalRow('chapter',array("course_id"=>$_GET['course_id']));


	    $data['db_list']= getQuery('chapter','*',array("course_id"=>$_GET['course_id']),"chapter_id");

	    if($_GET['del_id'])
		{

			$r=deleteData("chapter",array("chapter_id"=>$_GET['del_id']));
		
			$data['msg']=$configs['delete_msg'];
		}
	
	break;


	case "add-question":
		$data['page_subtitle']="Add | Edit Question";

		$data['breadcrumb']=array("Chapter list"=>$configs['admin_vpath'] ."course/chapter-list?course_id=".$_GET['course_id'],"Add | Edit Course"=>"");

		$data['chapter']= getQuery("chapter","*",array("course_id"=>$_GET['course_id']),"name");
		 
		if($_GET['id'])
		{
		   $data['db_value']= getDataByWhere('questions','*',array('id'=>$_GET['id']));
		}
	
	break;




	case "question-list":



		$name=getFldNameById("course","name",array("course_id"=>$_GET['course_id']));
	
		$data['page_subtitle']="Chapter List of ".$name;

		$data['breadcrumb']=array("Chapter"=>"");
		

		$data['btn']="<a href='".$configs['admin_vpath'] ."course/add-question?course_id=" . $_GET['course_id'] . "&chapter_id=" . $_GET['chapter_id']."&cat_id=" . $_GET['cat_id']."' class='btn btn-success pull-right'>Add Question</a>";

		


		$data['total']=getTotalRow('questions',array("course_id"=>$_GET['course_id'],"chapter_id"=>$_GET['chapter_id']));


	    $data['db_list']= getQuery('questions','*',array("course_id"=>$_GET['course_id'],"chapter_id"=>$_GET['chapter_id']),"id");

	    if($_GET['del_id'])
		{

			$r=deleteData("questions",array("id"=>$_GET['del_id']));
		
			$data['msg']=$configs['delete_msg'];
		}
	
	break;





	

	




}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

