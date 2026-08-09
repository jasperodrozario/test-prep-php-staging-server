<?php 
include("../../common/access.php");
include("../../common/autoload.php");

$data['page_title']="Page Content Management";


if(!$_GET['action'])
{
	$_GET['action']="page-list";
}
switch($_GET['action'])
{
	
	case "page-list":
	    
	    $data['total']=getTotalRow("contents",array("parent_id"=>0));
	
		# Title & BreadCrumb===================
		$data['page_subtitle']="Page List (".$data['total'].")";

		$data['breadcrumb']=array("Page Content List"=>"");

		$data['btn']="<input type='button' value='Add New' class='btn btn-success' onClick=\"javascript:window.location='page-add'\">";

		#End========================== 
		 
		if($_GET['del_id'])
		{
		   deleteData("contents",array("id"=>$_GET['del_id']));  


		   

		   $data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 


		    $data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."content/page-list';\",2000);</script>";

		  
		}

		#fetch data for list
		$data['contents']=getQuery("contents","*",array("parent_id"=>0));
		
		
	break;
	
	case "page-add":
 
		$data['page_subtitle']="Add | Edit Page";
		$data['breadcrumb']=array("Page Content List"=>"page-list","Add | Edit Page"=>""); 

		$data["btn"]="<input type='button' value='Back to List' class='btn btn-success' onclick=\"javascript:window.location.href='page-list'\">";
		
		if($_GET['id'])
		{
		   
			$data['db_value']= getDataByWhere("contents","*",array("id"=>$_GET['id']));
		
	 	}	

	   
	break;
	
}

 
$data['id']=$_GET['id']; 	
if(!$data['db_value']['status']){$data['db_value']['status']="Y";}
if(!$data['db_value']['menu']){$data['db_value']['menu']="N";}

$data['action']=$_GET['action'];
 
echo template($data); 
?>