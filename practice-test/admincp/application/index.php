<?php
include("includes/access.php");
include("includes/autoload.php");

$data['page_title']="Dashboard";

header("Location: website-analytics");

echo template($data);
?>

			 
