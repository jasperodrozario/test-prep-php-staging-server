<?php 
include("../configs/autoload.php");
session_start();

$_SESSION = array();
session_destroy();
echo template($data);
?>
