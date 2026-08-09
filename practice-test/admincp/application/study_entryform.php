<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add Lesson Study";
$data['breadcrumb']=array("Study List"=>"study-list", "Add Lesson Study"=>"");

 
if($_POST['SBMT_REG']):

   	if($_POST['study_id']):
   	    $r=updateTable("study",$_POST,array("study_id"=>$_POST['study_id']));
	    $study_id=$_POST['study_id'];

	else:
	    unset($_POST['study_id']);
	    $study_id=insertTable("study",$_POST);
	endif;
	
    if(!mysqli_errno($configs['con'])):
	 	$data['msg']=$configs['success_msg'];
        header("location:study-list");
	endif;

endif;

if($_REQUEST['study_id'])
{
	$data['db_value']=getDataByWhere("study","*",array("study_id"=>$_REQUEST['study_id']));	
} 

echo template($data);
?>

 