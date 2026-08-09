<?php
ob_start();
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
error_reporting(E_ALL & ~E_NOTICE);
 
ini_set('display_errors',0);
date_default_timezone_set('Amaerica/New york');
$data=[];$configs=[];
switch($_SERVER['HTTP_HOST'])
{
	case "localhost":
	case "192.168.1.100":
		$host = "localhost";
		$dbuser = "root";
		$dbpass = "mysql";
		$dbname = "eduon";
		$configs['vpath']= "http://" .$_SERVER['HTTP_HOST'] ."/eduon/";
		$configs['apath'] = $_SERVER['DOCUMENT_ROOT']."/eduon/";
	break;
	case "192.168.31.100":
		$host   = "localhost";
		$dbuser = "root";
		$dbpass = "";
		$dbname = "ocs";
		$configs['vpath']= "http://" .$_SERVER['HTTP_HOST'] ."/ocs/";
		$configs['apath'] = $_SERVER['DOCUMENT_ROOT']."/ocs/";  
	break;
	default:
		/*$host="localhost"; //mysql host	
		$dbuser="bpgnkyqryw"; //mysql username	
		
		$dbpass="U3ZsPk4kqz"; // mysql password	
		$dbname="bpgnkyqryw"; //mysql database name*/

		$host="198.211.99.20"; //mysql host	
		$dbuser="ctbsrarevb"; //mysql username	
		
		$dbpass="sN2gCBzJ4X"; // mysql password	
		$dbname="ctbsrarevb"; //mysql database name
	

		
		$configs['vpath']="https://" . $_SERVER['HTTP_HOST'] . "/"; //virtual path i.e.: http://localhost/d/dstcms/ or http://www.abc.com/
		$configs['apath']=$_SERVER['DOCUMENT_ROOT']."/"; //server root path i.e.: /var/www/html/d/dstcms/ or /home/vectorsc/public_html/

	break;
	
}

#Connection======================== 
$configs['logo']="Asvab-Tuoring.com"; //site name
$configs['dotcom']="Asvab-Tuoring.com"; //site name

$configs['prev']="edu_";

//database connection
$configs['con'] = mysqli_connect($host, $dbuser, $dbpass, $dbname) 
    or die('I cannot connect to the database because: ' . mysqli_connect_error());

$configs['domain']=$configs['dotcom'];
$configs['support_mail']="support@".$configs['dotcom'];

$configs['country_id']=46;

$configs['template']="template1";

$configs['admin_dir']="admincp";

$configs['template_path']=$configs['vpath']."template/" .$configs['template'] ."/"; //site name
$configs['template_apath'] = $configs['apath'] . "template/" . $configs['template'] . "/";



$configs['admin_vpath']=$configs['vpath'].$configs['admin_dir'] ."/";


$configs['admin_template']="template1";

$configs['admin_template_path']=$configs['admin_vpath'] . "template/".$configs['admin_template']."/"; // table perfix
$configs['admin_template_include']=$configs['apath'].$configs['admin_dir']. "/template/".$configs['admin_template']."/"; // table perfix
$configs['admin_template_apath'] = $configs['apath'] . $configs['admin_dir'] . "/template/" . $configs['admin_template'] . "/";

$configs['url']=$configs['vpath'].basename($_SERVER["SCRIPT_FILENAME"], '.php') ."/";

$configs['vendor_id']=1; 

$configs['success_msg']="<p><img src='".$configs['vpath']."img/on.png'> {table} Information has been successfully saved.</p>";
$configs['approve_msg']="<p><img src='".$configs['vpath']."img/on.png'> {table} Approved.</p>";
$configs['error_msg']="<p><img src='".$configs['vpath']."img/off.png'> <font color=red>{table} Error!!.</font></p>";
$configs['delete_msg']="<p><img src='".$configs['vpath']."img/on.png'> {table} Deletion done.</p>";

$configs['exist_msg']="<p><img src='".$configs['vpath']."img/off.png'> <font color='red'>{table} One or more input data are exists!.Try other id!</font></p>";

$configs['host']= $_SERVER['HTTP_HOST'];

// Auto environment detection
if ($configs['host'] === 'staging-staging-test-prep.asvab-tutoring.com') {
    $ENV = 'sandbox';
} else {
    $ENV = 'production';
}

$configs['square_environment'] = ($ENV === 'production')
    ? "Production"
    : "Sandbox";

$configs['nextjs_base_url'] = ($ENV === 'production')
    ? "https://www.asvab-tutoring.com"
    : "https://unlawyerlike-burl-unstatistically.ngrok-free.dev";

$configs['square_application_id'] = ($ENV === 'production')
    ? "sq0idp-bM4yTUWUFV1dnN7Xltrmfw"
    : "sandbox-sq0idb-mSK_EsUWvcsNdMIw1OHNXQ";

$configs['square_sdk_url'] = ($ENV === 'production')
    ? 'https://web.squarecdn.com/v1/square.js'
    : 'https://sandbox.web.squarecdn.com/v1/square.js';

$configs['square_location_id'] = 'LNTZCWEZTTRMX';

$configs['internalApiSecret'] =  "KDII68SER55RF";

?>