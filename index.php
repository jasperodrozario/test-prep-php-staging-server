<?
include("configs/autoload.php");

$data['courses']=getQuery("ocs_course","*",array("status"=>"Y"),"ord");

$data['feedbacks']=getQuery("ocs_feedback","*",array("status"=>"Y"));

echo template($data);
?>





 


 
