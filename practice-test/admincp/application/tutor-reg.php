<?
include("../common/access.php");
include("../common/autoload.php");
   
$data['page_title']="Add | Edit tutor";

if($_REQUEST['user_id'])
{
	$data['db_value']=getDataByJoin(array("tutor as v","login as l"),"l.*,v.*",array("l.user_id"=>$_REQUEST['user_id']),array("v.user_id"=>"l.user_id"));
	$data['page_title']="ADD | Edit tutor : <font color=orange>".ucwords($data['db_value']['fname']);
    $data['page_title'].=" <font color=gray>|</font> Member Since:".showdate($data['db_value']['reg_date']) ." <font color=gray></font> ";
     
}

$data['breadcrumb']=array("Tutors Management"=>"tutor-list","Add | Edit Tutor"=>"");
$data['btn']="<a href='javascript:history.back()' class='btn btn-success'>Back To List</a>";

if(!$_REQUEST['action'])
{
	 $_REQUEST['action']="add-tutor-login";
	 
	
}
$data['action']=$_REQUEST['action'];
echo template($data);  
?>


