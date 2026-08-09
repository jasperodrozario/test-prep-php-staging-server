<?php
include("../../common/access.php");
include("../../common/autoload.php");


/*if($_GET['cat_id'])
{
	$_GET['cat_id']=decrypt($_GET['cat_id']);
}



 
if($_GET['del_id'])
{
	$_GET['del_id']=decrypt($_GET['del_id']);
}
*/
if(!$_GET['action'])
{
	$_GET['action']="category-list";
}

$data['breadcrumb']=[];
$data['page_title']="Category Management";



$data['cat_name']=getFldNameById("category","cat_name",array("cat_id"=>$_GET['parent_id']));


 
switch($_GET['action'])
{
	

	
	case "add-category":

		if($_GET['parent_id'])
		{
		  $data['page_subtitle']="Add Sub Category of ".$data['cat_name']."";
		}
		else
		{
			$data['page_subtitle']="Add Category";
		}

		

		$data['breadcrumb']=array("Category"=>$configs['admin_vpath'] ."category/category-list","Add | Edit Category"=>"");		

		if($_GET['cat_id'])
		{
		$data['db_value']=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));	

		
		}	

		 
		
	
	break;

	default:

		if($_GET['parent_id'])
		{
		  $data['page_subtitle']="Category List of ".$data['cat_name']."";
		}
		else
		{
			$data['page_subtitle']="Category List";
		}

	 	

		$data['breadcrumb']=array("Category"=>"");

		


		 if($_GET['del_id'])
		{
			$r=deleteData("category",array("cat_id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 

			 $data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."category/category-list?mid=140';\",2000);</script>";

		}
		

		if($_GET['parent_id'])
		{
			$data['btn']="<a href='" . $configs['admin_vpath']."category/add-category?parent_id=".$_GET['parent_id']."' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Category</a>";
			$data['total']=getTotalRow('category',array("parent_id"=>$_GET['parent_id']));
	    	$data['db_list']= getQuery('category','*',array("parent_id"=>$_GET['parent_id']),"cat_id desc",$configs['limit']);		
		}
		else{

			

			$data['btn']="<a href='" . $configs['admin_vpath']."category/add-category' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Category</a>";
			$data['total']=getTotalRow('category',array("parent_id"=>0));
	    	$data['db_list']= getQuery('category','*',array("parent_id"=>0),"cat_id asc",$configs['limit']);

	    	



	    	
		}

		


		

	   		



}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

