<?php
include("../configs/autoload.php");

$data['db_value']=getDataByWhere("lesson","*",array("lesson_id"=>$_GET['lesson_id']));

unset($configs['includes']);

echo template($data);
?>