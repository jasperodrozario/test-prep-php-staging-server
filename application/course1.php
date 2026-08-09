<?php
include("../configs/autoload.php");

$exist=getTotalRow("course",array("seo_url"=>$_GET['seo_url']));

if(!$exist)
{
	header("location:".$configs['vpath']);
	exist;
}

$data['db_value']=getDataByWhere("course","*",array("seo_url"=>$_GET['seo_url']));

$data['similar_courses']=getQuery("course","*",array("course_id !"=>$data['db_value']['course_id']));

$exist=getTotalRow("faq",array("course_id"=>$data['db_value']['course_id']));

if($exist)
{
	$data['total_faq']=$exist;
	$data['faqs']=getQuery("faq","*",array("course_id"=>$data['db_value']['course_id']),"ord");
 
}
 
$data['feedback']=getQuery("feedback","*",array("course_id"=>$data['db_value']['course_id'],"status"=>"Y"));


if($_GET['location'])
{
	$meta_title=str_replace("{location}"," in ".ucwords(str_replace("-"," ",$_GET['location'])),$data['db_value']['meta_title']);
	$meta_keys=str_replace("{location}"," in ".ucwords(str_replace("-"," ",$_GET['location'])),$data['db_value']['meta_keys']);
	$meta_desc=str_replace("{location}"," in ".ucwords(str_replace("-"," ",$_GET['location'])),$data['db_value']['meta_desc']);
	$location=$_GET['location'] ."/";
}
else
{
	$meta_title=str_replace("{location}","",$data['db_value']['meta_title']);
	$meta_keys=str_replace("{location}","",$data['db_value']['meta_keys']);
	$meta_desc=str_replace("{location}","",$data['db_value']['meta_desc']);
	$location="";
}

$data['meta_title']=str_replace("{course}",ucwords(str_replace("-"," ",$_GET['seo_url'])),$meta_title);
$data['meta_keys']=str_replace("{course}",ucwords(str_replace("-"," ",$_GET['seo_url'])),$meta_keys);
$data['meta_desc']=str_replace("{course}",ucwords(str_replace("-"," ",$_GET['seo_url'])),$meta_desc);



echo template($data);
?>







