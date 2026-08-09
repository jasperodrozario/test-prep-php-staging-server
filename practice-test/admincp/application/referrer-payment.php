<?php
include("../common/access.php");
include("../common/autoload.php");

$user = getDataByWhere("user", "*", array("user_id" => (int)$_GET['user_id']));

$data['db_value'] = $user;

$data['page_title'] = "Add Referrer Payout | " . $user['first_name'] . " " . $user['last_name'];

$data['name'] = $user['first_name'] . " " . $user['last_name'];
 
//$val_total=getTotalRow("referral",['referrer_id'=>$_GET['user_id']]);

$val_earning = getFldValueById("referral_earnings", "sum(amount)", ['user_id' => $_GET['user_id']]);
$paid_amount = getFldValueById("referrer_payout", "sum(amount)", ['user_id' => $_GET['user_id']]);
$data['due_amount'] = $val_earning - $paid_amount;

unset($configs['includes']);

echo template($data);
?>