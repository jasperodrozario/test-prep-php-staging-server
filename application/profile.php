<?php
include("../configs/autoload.php");

chklogin();

$data['db_value']=getDataByWhere("user","*",array("user_id"=>$_SESSION['user_id']));

$data['countries']=getQuery("country","*",array("status"=>'Y'),"name");


$data['states']=getQuery("state","*",array("status"=>'Y',"country_id"=>$configs['country_id']));

echo template($data);

?>