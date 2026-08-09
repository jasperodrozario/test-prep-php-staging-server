<?php
include("../../common/access.php");
include("../../common/autoload.php");


 

if(!$_GET['action'])
{
	$_GET['action']="faq-list";
}

$data['breadcrumb']=[];
$data['page_title']="Faq Management";
 
switch($_GET['action'])
{
	case "add-faq":
		$data['page_subtitle']="Add Faq";

		$data['breadcrumb']=array("Faq"=>$configs['admin_vpath'] ."faq/faq-list","Add | Edit Faq"=>"");	

		$data['courses']=getQuery("ocs_course","*",array("status"=>"Y"),"name");	

		if($_GET['id'])
		{
			$data['db_value']=getDataByWhere("ocs_faq","*",array("id"=>$_GET['id']));	
		}
	break;
	case "faq-category-list":
	    $data['total']=getTotalRow('faq_category',array("parent_id"=>0));
	
		$data['page_subtitle']="Faq Category List (".$data['total'].")";

		$data['breadcrumb']=array("Faq Category"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."faq/add-faq-category' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Faq Category</a>";

		if($_GET['del_id'])
		{
			$r=deleteData("faq_category",array("cat_id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 

			$data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."faq/faq-category-list?mid=140';\",2000);</script>";	 
 		} 
	    $data['db_list']= getQuery('faq_category','*',array("parent_id"=>0),"",$configs['limit']);
 
	break; 
	case "add-faq-category":
		$data['page_subtitle']="Add Faq Category";

		$data['breadcrumb']=array("Faq Category"=>$configs['admin_vpath'] ."faq/faq-category-list","Add | Edit Faq Category"=>""); 

		if($_GET['id'])
		{
			$data['db_value']=getDataByWhere("faq_category","*",array("cat_id"=>$_GET['id']));	
		}
	break;
	default:
	    
	    $query=['status'=>'Y'];
	    
	    if($_GET['course_id'])
	    {
	    	$query=array_merge($query,array("course_id"=>$_GET['course_id']));
	    }	
	    $data['total']=getTotalRow('ocs_faq',$query);
	    
	 	$data['page_subtitle']="Faq List (".$data['total'].")";

		$data['breadcrumb']=array("Faq"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."faq/add-faq' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Faq</a>";

		if($_GET['del_id'])
		{
			$r=deleteData("faq",array("id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 

			$data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."faq/faq-list?mid=140';\",2000);</script>";

		}
	    $data['db_list']= getQuery('ocs_faq','*',$query,"id desc",$configs['limit']);
}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

