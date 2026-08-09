<?php
include("includes/access.php");
include("includes/autoload.php");

if($_SESSION['admin_id'])
{
	header("location:dashboard");
}

$data['page_title']="Dashboard";

echo template($data);
?>

			 
