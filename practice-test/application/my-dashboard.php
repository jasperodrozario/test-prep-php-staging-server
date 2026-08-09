<?php 
include ('../configs/autoload.php');
chklogin();
 

//$total_tender_open=getTotalRow("tender",array("user_id"=>$_SESSION['user_id'],"status"=>"Y"));
//$total_tender_close=getTotalRow("tender",array("user_id"=>$_SESSION['user_id'],"status"=>"C"));
echo template($data);
?>   

 