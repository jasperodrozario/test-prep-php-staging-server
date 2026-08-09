<?php
include("../configs/autoload.php");




$data['review_list']=getQuery("ocs_review","*",array("status"=>"Y"),"id desc");

$review_details=[];
foreach ($data['review_list'] as $d) 
{
	$review_details[$d['user_id']]=getDataByWhere("ocs_user","*",array("user_id"=>$d['user_id']));
}
$data['review_details']=$review_details;


echo template($data);

?>
    