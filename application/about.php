<?php

include("../configs/autoload.php");

$data['db_value']=getDataByWhere("contents","*",array("id"=>"5"));

$data['feedbacks']=getQuery("feedback","*",array("admin_chk"=>"Y"));

$data['meta_title']="About Asvab Tutoing";
    


echo template($data);
?>

 


