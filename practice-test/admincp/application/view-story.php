<?php 
include("../common/access.php");
include("../common/autoload.php");


if($_POST['submit']):
     
    $_POST['story']=htmlentities(htmlentities($_POST['story']));
 
   	if($_POST['story_id'])
   	{
		$r=updateTable("story",$_POST,array("story_id"=>$_POST['story_id']));
		$data['story_id']=$_POST['story_id'];
	}
 
	else 
	{
        if (isset($_POST['story_id'])) {
            unset($_POST['story_id']);
        }
		$r=insertTable("story",$_POST);
		$data['story_id']=$r;
	}
	

	    
	 
	if($r)
	{ 
	
		$data['msg']=$configs['success_msg'];
	}
    else
    {
		$data['msg']=$configs['error_msg'];
	}
	
		if(!mysqli_errno($configs['con'])):
 	  	$data['msg']=$configs['success_msg'];
  else:
    	$data['msg']=$configs['error_msg'];
		$data['db_value']=$_POST;
	endif;
       
 
endif;

$data['page_title']="Story Management";
$data['breadcrumb']=array("Story List"=>"misc_list","Add | Edit Story"=>""); 

$data["btn"]="<input type='button' value='Back to List' class='btn btn-success' onclick=\"javascript:window.location.href='misc_list'\">";

$data['option_no'] = 5;
if($_REQUEST['story_id'])
{
	$data['db_value']=getDataByWhere("story","*",array("story_id"=>$_REQUEST['story_id']));
	$data['story_id']=$_REQUEST['story_id']; 
    $data['db_value_ques'] = getQuery("question", "*", array("story_id"=>$_REQUEST['story_id']), "qid desc", $configs['limit']);
}	

$data['story_connection_list'] = getQueryByJoin(array("question as q","lesson_questions as l"), "l.lesson_id, l.cat_id, l.course_id",array("q.story_id"=>(int)$_REQUEST['story_id']),array("q.qid"=>"l.qid"), "l.order_no", "", "", "q.story_id");

echo template($data);
?>		

