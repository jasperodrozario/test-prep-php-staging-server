<?
include("../configs/autoload.php");


$data['recent_post']=getQuery("ocs_blog","*",array("status"=>"Y","id !"=>$_GET['id']),"id desc",5);


$data['db_value']=getDataByWhere("ocs_blog","*",array("id"=>$_GET['id']));


$data['total_blog_review']=getTotalRow("ocs_blog_review",array("status"=>"Y","blog_id"=>$_GET['id']));

$data['db_blog_review']=getQuery("ocs_blog_review","*",array("status"=>"Y","blog_id"=>$_GET['id']),"id desc");

//$data['cat_name']=getFldNameById("ocs_services","name",array("id"=>$data['db_value']['cat_id']));

 //$data['blog_categories']=getQuery("ocs_services","*",array("status"=>"Y"),"name");


echo template($data);
?>
