<?php 
include("../configs/autoload.php");

$configs['prev']="eduapp_";

$data['db_value']=getDataByWhere("contents","*",array("seo_url"=>$_GET['seo_url']));
//echo getDataByWhere("contents","*",array("seo_url"=>$_GET['seo_url']),1);
unset($configs['includes']);
echo template($data);
?>
