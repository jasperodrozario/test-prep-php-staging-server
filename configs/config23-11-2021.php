<?php
ob_start();
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
error_reporting(E_ALL & ~E_NOTICE);
 
ini_set('display_errors',1);
date_default_timezone_set('Australia/Sydney');
$data=[];$configs=[];
switch($_SERVER['HTTP_HOST'])
{
	case "localhost":
	case "192.168.1.100":
		$host = "localhost";
		$dbuser = "root";
		$dbpass = "mysql";
		$dbname = "papiadams";
		$configs['vpath']= "http://" .$_SERVER['HTTP_HOST'] ."/papiadams/";
		$configs['apath'] = $_SERVER['DOCUMENT_ROOT']."/papiadams/";
	break;
	case "192.168.1.14":
		$host = "localhost";
		$dbuser = "root";
		$dbpass = "";
		$dbname = "papiadams";
		$vpath = "http://192.168.1.14/papiadams/";
		$apath=$_SERVER['DOCUMENT_ROOT'] ."/papiadams/";

		$calendar_new_path = "/calendar_new/";
		$captcha_path = "/captcha/";
		$fckapath = $apath."fckeditor/";
    	$fckbasepath =$vpath. "fckeditor/";
		

	break;
	default:
		$host="localhost"; //mysql host	
		$dbuser="dpernswysk"; //mysql username	
		
		$dbpass="249yq92SFY"; // mysql password	
		$dbname="dpernswysk"; //mysql database name
		
		$configs['vpath']="https://" . $_SERVER['HTTP_HOST'] . "/"; //virtual path i.e.: http://localhost/d/dstcms/ or http://www.abc.com/
		$configs['apath']=$_SERVER['DOCUMENT_ROOT']."/"; //server root path i.e.: /var/www/html/d/dstcms/ or /home/vectorsc/public_html/

	break;
	
}

#Connection======================== 
$configs['logo']="Papiadams"; //site name
$configs['dotcom']="papiadams.cl"; //site name

$configs['prev']="food_";

//database connection
$configs['con']=mysqli_connect ($host, $dbuser, $dbpass,$dbname) or die ('I cannot connect to the database because: ' . mysqli_error());

$configs['domain']=$configs['dotcom'];
$configs['support_mail']="support@".$configs['dotcom'];

$configs['country_id']=46;

$configs['template']="template1";

$configs['admin_dir']="admincp";

$configs['template_path']=$configs['vpath']."template/" .$configs['template'] ."/"; //site name
$configs['admin_vpath']=$configs['vpath'].$configs['admin_dir'] ."/";

$configs['admin_template']="template1";

$configs['admin_template_path']=$configs['admin_vpath'] . "template/".$configs['admin_template']."/"; // table perfix
$configs['admin_template_include']=$configs['apath'].$configs['admin_dir']. "/template/".$configs['admin_template']."/"; // table perfix
$configs['url']=$configs['vpath'].basename($_SERVER["SCRIPT_FILENAME"], '.php') ."/";

$configs['vendor_id']=1; 
?>