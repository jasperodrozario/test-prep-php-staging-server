<?php 
include("../configs/autoload.php");
chklogin();
$data['test_names']=getQuery("course","*","","course_name");
$data['state_list']=getQuery("state","*",array("country_id"=>$configs['country_id']),"name");

$data['meta_title']="Edit User Profile";

$data['db_value']=getDataByJoin(array("user as u","subscription as s"),"u.*,s.*",array("u.user_id"=>$_SESSION['user_id']),array("u.user_id"=>"s.user_id"));

$is_square_subscriber = getTotalRow("square_subscriptions", array("user_id" => $_SESSION['user_id']));
$is_active_user = getTotalRow("user", array("user_id" => $_SESSION['user_id'], "activation_status" => "Y"));

if($is_square_subscriber && $is_active_user){
    
    $data['db_value']['display_info'] = true;

    $data['db_value']['next_billing_date'] = getFldValueById("square_subscriptions", "next_billing_date", array("user_id" => $_SESSION['user_id']));

} else{

    $data['display_info'] = false;

}

if($data['db_value']['state_id'])
{
	$data['city_list']=getQuery("city","*",array("state_id"=>$data['db_value']['state_id']),"name");	
}

if($_GET['deactivate_id']){
    updateTable("user",["activation_status"=>"D"],["user_id"=>$_GET['deactivate_id']]);
    header("location:".$configs['vpath']."logout");
}
echo template($data);	
?>   
 