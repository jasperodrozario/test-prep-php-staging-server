<?php
include("../../common/access.php");
include("../../common/autoload.php");

 
if(!$_GET['action'])
{
	$_GET['action']="transaction-list";
}

$data['breadcrumb']=[];
$data['page_title']="Transaction Management";
 
switch($_GET['action'])
{
	

	
	case "view":
		$data['page_subtitle']="View Transaction";

		$data['breadcrumb']=array("Transaction List"=>$configs['admin_vpath'] ."transaction/transaction-list","View Transaction"=>"");	


		if($_GET['id'])
		{
		   $data['db_value']=getDataByWhere("ocs_transactions","*",array("id"=>$_GET['id']));	

		
		}		 
		
	
	break;


	default:
	    
	    $data['total']=getTotalRow("ocs_transactions",array("amount >"=>0));

	 	$data['page_subtitle']="Transaction List (".$data['total'].")";
	 	
	 	

		$data['breadcrumb']=array("Transaction"=>"");
	 

		if($_GET['del_id'])
		{
			$r=deleteData("ocs_transactions",array("id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 

			$data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."transaction/transaction-list';\",2000);</script>";

		}		
 
		$data['total']= getTotalRow('ocs_transactions');
		
	    $data['db_list']= getQuery("ocs_transactions","*",array(),"id desc",$configs['limit']);
	    //echo getQuery("transactions","*",array(),"id desc",$configs['limit'],1);
		 


}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

