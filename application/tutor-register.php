<?php 
include("../configs/autoload.php");

$countries=getQuery("country");
$array=array();
foreach($countries as $c)
{
    $array[$c['id']]=$c['name'];
}
$data['countries']=$array;
$data['meta_title']="Tutor Register";

echo template($data);  
?>