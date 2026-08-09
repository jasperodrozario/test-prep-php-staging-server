<?php
include("../../common/access.php");
include("../../common/autoload.php");

if($_GET['cat_id'])
{
	$_GET['cat_id']=decrypt($_GET['cat_id']);
}

if($_GET['id'])
{
	$_GET['id']=decrypt($_GET['id']);
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

	    

	    $data['db_list']= getQuery('category','*',array("parent_id"=>0),"cat_name asc",$configs['limit']);



	    if($_GET['del_id'])
		{

			$r=deleteData("category",array("cat_id"=>$_GET['del_id']));
		
			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 
		}


		 if($_GET['del_course'])
		{

			$r=deleteData("course",array("course_id"=>$_GET['del_course']));
		
			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 
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



	case "course-list":
		if($_GET['cat_id']){
			$cat_name="of ".getFldNameById("category","cat_name",array("cat_id"=>$_GET['cat_id']));
		}
	
		$data['page_subtitle']="Course List ".$cat_name;

		$data['breadcrumb']=array("Course"=>"");

		if($_GET['cat_id']){
			$data['btn']="<a href='" . $configs['admin_vpath']."course/add-course/".$_GET['cat_id']."' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Course</a>";
		}
		else{
			$data['btn']="<a href='" . $configs['admin_vpath']."course/add-course' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Course</a>";
		}
		
			   

	    //$data['db_list']=getQuery("course","*",array("status"=>"Y"),"course_id desc");

	   

	     if($_GET['cat_id'])
		 {
		 	 $data['db_list']= getQuery('course','*',array("cat_id"=>$_GET['cat_id']),"course_id desc",$configs['limit']);

		 	 $data['total']=getTotalRow('course',array("cat_id"=>$_GET['cat_id']));
		 }
		 else{
		 	 $data['db_list']= getQuery('course','*',"","course_id desc",$configs['limit']);

		 	 $data['total']=getTotalRow('course');
		 }





	    if($_GET['del_id'])
		{

			$r=deleteData("course",array("course_id"=>$_GET['del_id']));
		
			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 
		}


		 


		
	
	break;

	case "add-course":
		$data['page_subtitle']="Add | Edit Course";

		$data['breadcrumb']=array("Course List"=>$configs['admin_vpath'] ."course/course-list","Add | Edit Course"=>"");

		$data['category']= getQuery("category","*",array("parent_id"=>0),"cat_name");
		 
		if($_GET['course_id'])
		{
		   $data['db_value']= getDataByWhere('course','*',array('course_id'=>$_GET['course_id']));
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

	case "review-list":



		$name=getFldNameById("course","name",array("course_id"=>$_GET['course_id']));
	
		$data['page_subtitle']="Review List of ".$name;

		$data['breadcrumb']=array("Review"=>"");
		

		

		


		$data['total']=getTotalRow('course_review',array("course_id"=>$_GET['course_id']));


	    $data['db_list']= getQuery('course_review','*',array("course_id"=>$_GET['course_id']),"id");

	    if($_GET['del_id'])
		{

			$r=deleteData("course_review",array("id"=>$_GET['del_id']));
		
			$data['msg']=$configs['delete_msg'];
		}
	
	break;


case "view-review":
		$data['page_subtitle']="View Review";

		$data['breadcrumb']=array("Review list"=>$configs['admin_vpath'] ."course/review-list?course_id=".$_GET['course_id'],"View Review"=>"");

		$user_type=getFldNameById("login","user_type",array("user_id"=>$_GET['user_id']));

		if($user_type=="student"){
			$data['db_name']= getDataByWhere('student','*',array('user_id'=>$_GET['user_id']));
		}
		else{
			$data['db_name']= getDataByWhere('tutor','*',array('user_id'=>$_GET['user_id']));
		}
		 
		if($_GET['id'])
		{
		   $data['db_value']= getDataByWhere('course_review','*',array('id'=>$_GET['id']));
		}
	
	break;


	

	




}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

