<?
include("../common/access.php");
include("../common/autoload.php");
 
$data['page_title']="Lesson Entry";
$data['breadcrumb']=array("Category List"=>"category_list?platfom=".$_REQUEST['platform']."&course_id=".$_REQUEST['course_id'], "Add | Edit Lesson"=>"");
 
if(!$data['menu_open_id'])
{
	$data['menu_open_id']=getFldValueById("adminmenu","parent_id",array("url"=>"category_list"));
}

$data['category']= getQuery("category","*",array("course_id"=>$_REQUEST['course_id']),"category_name");
$data['course']= getQuery("course","*","","course_name");
$data['all_studies']= getQuery("study","*","","study_title");

if($_REQUEST['page'])
{
	switch($_REQUEST['page'])
	{
		case "homework-list":
		    if($_REQUEST['del_id'])
		    {
				$r=deleteData("questions",array("id"=>$_REQUEST['del_id']));
			    $r=deleteData("answers",array("quesion_id"=>$_REQUEST['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
		 
	        $data['db_list']= getQuery("questions","*",array("cat_id"=>$_REQUEST['cat_id'],"lesson_id"=>$_REQUEST['lesson_id']),'','',1);
	 
	    break;
	    case "homework":
	        if($_REQUEST['id'])
	        {
				$data['id']=$_REQUEST['id'];
		    	$data['db_value']= getDataByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.id"=>$_REQUEST['id']),array("qa.question_id"=>"q.id"));

		    	$data['answers']=getQuery("answers","*",array("question_id"=>$_REQUEST['id']),"id");
		        $data['total_answers']=getTotalRow("answers",array("question_id"=>$_REQUEST['id']));
		    }
		 
		break;
       	
		case "lesson":
		     $data['db_value']=getDataByWhere("lesson","*",array("lesson_id"=>$_REQUEST['lesson_id'],"cat_id"=>$_REQUEST['cat_id'],"course_id"=>$_REQUEST['course_id']));
            
            $data['added_study']=getQuery("lesson_study","*",array("lesson_id"=>$_REQUEST['lesson_id'],"cat_id"=>$_REQUEST['cat_id'],"course_id"=>$_REQUEST['course_id']));
            
		break;
            
       	case "video":	
       	     if($_REQUEST['del_id'])
		     {
			 	deleteData("file",array("id"=>$_REQUEST['del_id']));
			 } 
			 if($_REQUEST['id'])
		     {
			 	deleteData("file",array("id"=>$_REQUEST['del_id']));
			 }    
			 $data['db_value']=getDataByWhere("file","*",array("id"=>$_REQUEST['id']));
	
			 $data['video_list']=getQuery("file","*",array("file_type"=>"v","lesson_id"=>$_REQUEST['lesson_id']),'pos');
			 
		break;
    }
}
  	
$data['platform']=$_REQUEST['platform'];


if(!$_REQUEST['page'])
{
	$_REQUEST['page']="lesson";
}
echo template($data);
?>

 
