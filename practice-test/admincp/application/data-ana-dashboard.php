<?
include("../common/access.php");

include("../common/autoload.php");
include(__DIR__ . "/../../configs/revenue.php");

$data['page_title']="Data Analysis Dashboard";

$data['breadcrumb']=array("Data Analysis Dashboard"=>"");

// All-time revenue in dollars, using the shared rules (Square in cents + app stores, no double counting, testers excluded)
$data['total_revenue']=revenueTotal('all');
$data['total_user']=getTotalRow("user", array());
$data['total_subscription'] = getTotalRow("subscription", array("amount > " => 0, "subscription_date > " => date('Y-m-d', strtotime('-30 days'))));
$data['total_registration'] = getTotalRow("subscription", array("subscription_date > " => date('Y-m-d', strtotime('-30 days'))));


echo template($data);
?>