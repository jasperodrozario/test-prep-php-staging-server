<?php
include("../../common/access.php");

include("../../common/autoload.php");

if(!$_GET['action'])
{
	$_GET['action']='enquiry-list';
}
 
$data['breadcrumb']=[];
$data['page_title']="Enquiry List";
 
switch($_GET['action'])
{
	case "enquiry-list":
	    
	     
	    $data['total']=getTotalRow("enquiry");

		$data['page_subtitle']="Enquiry List (".$data['total'].")";
		$data['breadcrumb']=array("enquiry"=>"");
		 	
		if($_POST['ids']) 
		{
			
			for($i=0;$i<count($_POST['ids']);$i++)
			{
			   	 deleteData("enquiry",array("id"=>$_POST['ids'][$i]));
			   	 
			   	 $data['msg']=str_replace("{table}","Enquiry",$configs['delete_msg']);
			}
			  
		}
	
		

	    if($_GET['del_id'])
		{
			$r=deleteData("enquiry",array("id"=>$_GET['del_id']));		
			$data['msg']=str_replace("{table}","Enquiry",$configs['delete_msg']);
			
			$data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."enquiry/enquiry-list';\",2000);</script>";
		}

		
	    $data['db_list']= getQuery('enquiry','*',array(),"id desc",$configs['limit']);

	break;

	case "add-enquiry":
	case "view-enquiry":
		$data['page_subtitle']="Add enquiry";

	 	 
		if($_GET['id'])
		{
		   $data['db_value']= getDataByWhere('enquiry','*',array('id'=>$_GET['id']));
		}
			
		unset($configs['includes']);
		$data['modal']=1;	
	break;      
     
}      
 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

