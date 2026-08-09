<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Online Free Test Course List";

$data['breadcrumb']=array("Online Free Test - Course List"=>"");
     
$data['course_group_list'] = getQuery("course_group","*","","ord");
foreach($data['course_group_list'] as $c){
    $data['course_list'][$c['group_id']]=getQuery("course","*",array("group_id"=>$c['group_id']),"ord");
}

echo template($data); 
?> 
