<?
include("../common/access.php");
include("../common/autoload.php");
 
$data['page_title']="Lesson Entry";
$data['breadcrumb']=array("Category List"=>"category_list",  
    "Add Lesson"=>"");
 
$data['category']= getQuery("category","*",array("parent_id"=>0),"cat_name");


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
			if($_REQUEST['lesson_id']) 
			{ 
				 
				$data['db_value']=getDataByWhere("lesson","*",array("lesson_id"=>$_REQUEST['lesson_id']));
			    
			    $data['file_value']=getDataByWhere("file","*",array("id"=>$_REQUEST['id']));
			  
			    if($_REQUEST['page']=="studymat")
			    {
				   $data['file_list']=getQuery("file","*",array("file_type"=>"p","lesson_id"=>$_REQUEST['lesson_id']),'pos');
			    	
				}
				if($_REQUEST['page']=="video")
				{
					$data['video_list']=getQuery("file","*",array("file_type"=>"v","lesson_id"=>$_REQUEST['lesson_id']),'pos');
			    }
			    if($_REQUEST['page']=="homework-list")
			    {
					$data['db_list']=getQuery("questions","*",array("cat_id"=>$_GET['cat_id']),"id desc",$configs['limit']);

				}
			  
			}

		break;
    }
}
  	
 


if(!$_REQUEST['page'])
{
	$_REQUEST['page']="lesson";
}
echo template($data);
?>

 
