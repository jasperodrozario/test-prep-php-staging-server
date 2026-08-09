<?php
include("../configs/autoload.php");

 //$data['db_list']= getQuery('gallery','*',array("file_type"=>"p"),"id desc",$configs['limit']);
 
 $data['meta_title']="Frequently Asked Questions";

echo template($data);

?>
    