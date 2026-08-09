<?php
include("../configs/autoload.php");

 $data['db_list']= getQuery('ocs_gallery','*',array("file_type"=>"p"),"id desc",$configs['limit']);
  

echo template($data);

?>
    