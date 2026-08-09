<?php
include("common/autoload.php");


$data['page_title']="Dashboard";
 
$data['breadcrumb']=array("Dashboard"=>""); 
if($_SESSION['admin_id'])
{
	header("location:account/dashboard");
}
else
{
	header("location:account/login");
}   
$data['view_chart']=0;     
echo template($data);
?>

			 
