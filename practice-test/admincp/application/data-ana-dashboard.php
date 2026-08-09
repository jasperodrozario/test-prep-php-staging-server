<?
include("../common/access.php");

include("../common/autoload.php");

$data['page_title']="Data Analysis Dashboard";
 
$data['breadcrumb']=array("Data Analysis Dashboard"=>""); 

$data['total_revenue']=getFldValueById("subscription","sum(amount)",array("amount > "=>0));
$data['total_user']=getTotalRow("user", array());
$data['total_subscription'] = getTotalRow("subscription", array("amount > " => 0, "subscription_date > " => date('Y-m-d', strtotime('-30 days'))));
$data['total_registration'] = getTotalRow("subscription", array("subscription_date > " => date('Y-m-d', strtotime('-30 days'))));


echo template($data);
?>