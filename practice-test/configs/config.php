<?php

date_default_timezone_set('America/New_York');
ob_start();
if (!isset($_SESSION)) {
	session_start();
}
error_reporting(E_ALL);
ini_set('memory_limit', '-1');
ini_set('display_errors', 0);

$data = array();
switch ($_SERVER['HTTP_HOST']) {
	case "localhost":
	case "192.168.1.173":
		$host = "localhost";
		$dbuser = "root";
		$dbpass = "mysql";
		$dbname = "officers";
		$configs['vpath'] = "http://" . $_SERVER['HTTP_HOST'] . "/officers/";
		$configs['apath'] = $_SERVER['DOCUMENT_ROOT'] . "/officers/";
		break;
	case "192.168.1.14":
		$host = "localhost";
		$dbuser = "root";
		$dbpass = "";
		$dbname = "asvabtest";
		$configs['vpath'] = "http://192.168.1.14/asvabtest/";
		$configs['apath'] = $_SERVER['DOCUMENT_ROOT'] . "/asvabtest/";
		break;
	default:
		$host = "198.211.99.20"; //mysql host	
		$dbuser = "ctbsrarevb"; //mysql username	

		$dbpass = "sN2gCBzJ4X"; // mysql password	
		$dbname = "ctbsrarevb"; //mysql database name

		$configs['vpath'] = "https://" . $_SERVER['HTTP_HOST'] . "/practice-test/"; //virtual path i.e.: http://localhost/d/dstcms/ or http://www.abc.com/
		$configs['apath'] = $_SERVER['DOCUMENT_ROOT'] . "/practice-test/"; //server root path i.e.: /var/www/html/d/dstcms/ or /home/vectorsc/public_html/
		break;
}


#Connection======================== 

$configs['dotcom'] = "staging-test-prep.asvab-tutoring.com"; //site name
//database connection
$configs['con'] = mysqli_connect($host, $dbuser, $dbpass, $dbname);
if (!$configs['con']) {
    die('I cannot connect to the database because: ' . mysqli_connect_error());
}


$configs['prev'] = "edu_"; // table perfix

$configs['support_mail'] = "support@" . $configs['dotcom'];

$configs['country_id'] = 230;

#Path ========================

$configs['template'] = "template1"; //site name

$configs['admin_dir'] = "admincp";


$configs['template_path'] = $configs['vpath'] . "template/" . $configs['template'] . "/"; //site name
// Absolute filesystem path to template folder (for filemtime, etc.)
$configs['template_abspath'] = $configs['apath'] . "template/" . $configs['template'] . "/";

$configs['admin_path'] = $configs['vpath'] . $configs['admin_dir'] . "/";

$configs['admin_template'] = "template";

$configs['tpath'] = $configs['admin_path'] . $configs['admin_template'] . "/"; // table perfix

#Alert Message ========================
$configs['success_msg'] = "<p><img src='" . $configs['vpath'] . "img/on.png'> Update Successfully Done.</p>";
$configs['error_msg'] = "<p><img src='" . $configs['vpath'] . "img/off.png'> <font color=red>Error!!.</font></p>";
$configs['delete_msg'] = "<p><img src='" . $configs['vpath'] . "img/on.png'> Deletion done.</p>";
$configs['suspend_msg'] = "<p><img src='" . $configs['vpath'] . "img/on.png'> Account has been suspended.</p>";
$configs['exist_msg'] = "<p><img src='" . $configs['vpath'] . "img/off.png'> <font color='red'>One or more input data are exists!.Try other id!</font></p>";

#icon ========================
$configs['edit_icon'] = "<i class='fa fa-pencil-square-o'>";
$configs['delete_icon'] = "<i class='fa fa-times'></i>";
$configs['folder_icon'] = "<i class='fa fa-folder-o' ></i>";



$configs['url'] = $configs['vpath'] . basename($_SERVER["SCRIPT_FILENAME"], '.php') . "/";

#limit for pagination ========================

if (!$_GET['limit']) {
	$_GET['limit'] = 1;
}
$configs['limit'] = (($_GET['limit'] - 1) * 100) . ",100";
$configs['homelimit'] = 0;

if ($_GET['home_limit']) {
	$configs['homelimit'] = 1;
}

if (!$_GET['home_limit']) {
	$_GET['home_limit'] = 1;
}

if ($_GET['home_limit']) {
	$configs['home_limit'] = ($_GET['home_limit'] - 1) . ",1";
}

$configs['facebook']="https://www.facebook.com/asvabtutoring";
$configs['youtube']="https://www.youtube.com/@asvabtutoring5963";
$configs['x_url']		="https://x.com/asvabtutoring18";
$configs['instagram']="https://www.instagram.com/asvabtutoring/";
$configs['tiktok']="https://www.tiktok.com/@asvab_tutoring";
?>