<?php
include("../configs/autoload.php");
$data['meta_title']="Become Affiliate";

if($_SERVER['HTTP_HOST']!="localhost")
{
    include("../smtpmail.php");
}    

$data['db_list']=getQuery("ocs_faq","*",["course_id"=>1000],"ord");
 
echo template($data);

?>
