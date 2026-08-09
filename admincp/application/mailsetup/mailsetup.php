<?php
include("../../common/access.php");
include("../../common/autoload.php");


if($_GET['id'])
{
	$_GET['id']=decrypt($_GET['id']);
}

 
if($_GET['del_id'])
{
	$_GET['del_id']=decrypt($_GET['del_id']);
}

if(!$_GET['action'])
{
	$_GET['action']="mailsetup-list";
}

$data['breadcrumb']=[];
$data['page_title']="Mail Template Setting";
 
switch($_GET['action'])
{
	

	
	case "add-mailsetup":
		$data['page_subtitle']="Add Template";

		$data['breadcrumb']=array("Mail Template"=>$configs['admin_vpath'] ."mailsetup/mailsetup-list","Add | Edit Template"=>"");
		 
		if($_GET['id'])
		{
		   $data['db_value']= getDataByWhere('ocs_mailsetup','*',array('id'=>$_GET['id']));
		}
	
	break;


	default:
	    $data['total']=getTotalRow('ocs_mailsetup');
	    
	 	$data['page_subtitle']="Mail Template List (".$data['total'].")";

		$data['breadcrumb']=array("Mail Template"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."mailsetup/add-mailsetup' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add New Template</a>";

		if($_GET['del_id'])
		{

			$r=deleteData("ocs_mailsetup",array("id"=>$_GET['del_id']));
		
			
			
			$data['msg']=str_replace("{table}","ocs_mailsetup",$configs['delete_msg']);
			
			$data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."mailsetup/mailsetup-list?mid=140';\",2000);</script>";
		}


		
	    $data['db_list']= getQuery('ocs_mailsetup','*',array(),"id",$configs['limit']);

	    

}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

