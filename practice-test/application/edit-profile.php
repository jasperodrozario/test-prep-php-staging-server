<?php 
include("../configs/autoload.php");
chklogin();
$data['test_names']=getQuery("course","*","","course_name");
$data['state_list']=getQuery("state","*",array("country_id"=>$configs['country_id']),"name");

$data['db_value']=getDataByJoin(array("user as u","subscription as s"),"u.*,s.*",array("u.user_id"=>$_SESSION['user_id']),array("u.user_id"=>"s.user_id"));

if($data['db_value']['state_id'])
{
	$data['city_list']=getQuery("city","*",array("state_id"=>$data['db_value']['state_id']),"name");	
}
echo template($data);	
?>   
 