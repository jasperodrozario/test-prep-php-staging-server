<?
session_start();
if(!isset($_SESSION['admin_id']))
{
	
	header("location:login");
	exit();
}

/*
else
{
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
}
*/
?>