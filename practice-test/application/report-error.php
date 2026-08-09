<?
include("../configs/autoload.php");

chklogin(str_replace("/practice-test/","",$_SERVER['REQUEST_URI']));

if($_SESSION['status']=="N")
{
	header("location:" . $configs['vpath'] ."renew-subscription");
	exit();
} 

$data['cat_name']=getFldValueById("category","category_name",array("cat_id"=>$_GET['cat_id']));
$data['lesson_name']=getFldValueById("lesson","Lesson_name",array("lesson_id"=>$_GET['lesson_id']));
$data['pred_cat']=getFldValueById("predic_test","test_name",array("Predic_test_id"=>$_GET['cat_id']));
echo template($data);
?>