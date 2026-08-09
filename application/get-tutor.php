<?php 
include("../configs/autoload.php");

$data['courses_list']=getQuery("course", "*", array("is_online"=>"Y"), "course_name");
$data['state_list']=getQuery("state","*",array("country_id"=>230),"name"); // USA
// $data['meta_title']="Get Asvab Tutor";

if($_GET['course_id']){

    $data['course_name'] = getFldValueById("course","course_name",array("course_id"=>(int)$_GET['course_id']));

    $data['tutoring_package_list']=array();

    $data['meta_title'] = "Get " . $data['course_name'] . " Tutor";

    $data['tutoring_detail_list']=getQuery("subscriptions_details", "*", array("course_id"=>(int)$_GET['course_id']), "ord");
    
    foreach($data['tutoring_detail_list'] as $t){
        $data['tutoring_package_list'][$t['det_id']]=getQuery("subscriptions_price", "*", array("course_id"=>(int)$_GET['course_id'], "subs_id"=>$t['det_id']), "ord");
    }
}

echo template($data);	
?>   
 