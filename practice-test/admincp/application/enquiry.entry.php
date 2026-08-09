<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Enquiry List";
$data['breadcrumb']=array("Enquiry List"=>"enquires.list", 
     "View Enquiry"=>"");

if($_POST['SBMT_REG']):
 
 	$r=updateTable("enquiry",$_POST,array("id"=>$_POST['id'])); 	
    $data['msg']=$configs['success_msg'];
   
endif;	

if($_REQUEST['id']):

	$data['db_value']=getDataByWhere("enquiry","*",array("id"=>$_REQUEST['id']));

endif;

echo template($data);
?>
 
