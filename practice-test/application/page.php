<?php 
include("../configs/autoload.php");

$data['db_value']=getDataByWhere("contents","*",array("seo_url"=>$_GET['seo_url']));
echo template($data);
?>
