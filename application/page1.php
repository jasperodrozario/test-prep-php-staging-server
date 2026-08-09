<?php
include("../configs/autoload.php");

$data['db_value']=getDataByWhere("contents","*",array("seo_url"=>$_GET['seo_url']));


$data['meta_title']=$data['db_value']['meta_title'];
$data['meta_keys']=$data['db_value']['meta_keys'];
$data['meta_desc']=$data['db_value']['meta_desc'];

if(!$data['db_value']['meta_title'])
{
    $data['meta_title']=$configs['meta_title'];
}
if(!$data['db_value']['meta_keys'])
{
    $data['meta_keys']=$configs['meta_keys'];
}
if(!$data['db_value']['meta_desc'])
{
    $data['meta_desc']=$configs['meta_desc'];
}
 


if($_GET['seo_url']=="logout")
{
	session_unset();
	

	session_destroy();
} 
  

echo template($data);

?>
    