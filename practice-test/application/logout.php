<?
include("../configs/config.php"); 
session_start();

$_SESSION = array();
session_destroy();

if(empty($_SESSION['user_id']))
{
 
  header("location: https://staging-test-prep.asvab-tutoring.com/");

}

echo template($data);
?>