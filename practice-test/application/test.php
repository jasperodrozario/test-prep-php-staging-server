<?php
include("../configs/autoload.php");
chklogin(str_replace("/officers/","",$_SERVER['REQUEST_URI']));

switch($_GET['id'])
{
	case "1":
	
	  header("location:".$configs['vpath']."lesson/93-174-lesson-1");
	break;
	default:
	  header("location:".$configs['vpath']."lesson/93-174-lesson-1");
	
	
}
 
echo template($data);
?>