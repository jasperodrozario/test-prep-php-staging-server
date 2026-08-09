<?
include("configs/config.php"); 
include("configs/common.php"); 

switch($_GET['field'])
{
	case "code":
	case "email":

		$exist = getTotalRow("login",array($_GET['field']=>$_GET['value']));
	break;
	case "mobile":
		$exist = getTotalRow("student",array($_GET['field']=>$_GET['value']));
		
	break;	
}
if($exist)  
{
	echo "Y";
}	  
?>