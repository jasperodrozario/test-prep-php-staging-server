<?php 
include("../common/access.php");
include("../common/autoload.php");


if($_POST['submit']):
     
    $_POST['content']=htmlentities(htmlentities($_POST['content']));
 
   	if($_POST['study_id'])
   	{
		$r=updateTable("study",$_POST,array("study_id"=>$_POST['study_id']));
		$data['study_id']=$_POST['study_id'];
	}
 
	else 
	{
        if (isset($_POST['study_id'])) {
            unset($_POST['study_id']);
        }
		$r=insertTable("study",$_POST);
		$data['study_id']=$r;
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

$data['page_title']="Study Management";
$data['breadcrumb']=array("Study List"=>"study-list","Add | Edit Study"=>""); 

$data["btn"]="<input type='button' value='Back to List' class='btn btn-success' onclick=\"javascript:window.location.href='study-list'\">";

if($_REQUEST['study_id'])
{
	$data['db_value']=getDataByWhere("study","*",array("study_id"=>$_REQUEST['study_id']));
	$data['study_id']=$_REQUEST['study_id'];
}	

$data['study_connection_list'] = getQuery("lesson_study","*",array("study_id"=>$_REQUEST['study_id']));

echo template($data);
?>		

