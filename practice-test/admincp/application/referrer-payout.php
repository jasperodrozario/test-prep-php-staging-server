<?php 
include("../common/access.php");
include("../common/autoload.php");

$admin_password = "!@Dd3372008";

if ($_POST['password'] == $admin_password) {
    deleteData("referrer_payout", ["id" => $_POST['id']]);
    header("Location: referrer-payout?user_id=" . $_POST['user_id']);
}


$user = getDataByWhere("user","*",array("user_id"=>(int)$_GET['user_id']));

$data['page_title']="Referrer Earning | User Id :" .$_GET['user_id'] . " | Name : " .$user['first_name']." ".$user['last_name'];
 
$data['breadcrumb']=array("Referrer List"=>"referrer-list","Referrer Payout"=>"");

$data['db_list']=getQuery("referrer_payout","*",["user_id"=>$_GET['user_id']],"id desc");

$data['total_paid']=getFldValueById("referrer_payout","sum(amount)",['user_id'=>$_GET['user_id']]);

$data['user_data'] = getDataByWhere("user","*",array("user_id"=>$_GET['user_id']));

echo template($data);
?>