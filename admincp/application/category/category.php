<?php
include("../../common/access.php");
include("../../common/autoload.php");



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

		
		
		$data['page_subtitle']="Add Category";

		$data['breadcrumb']=array("Category"=>$configs['admin_vpath'] ."category/category-list","Add | Edit Category"=>"");		
		

		if($_GET['cat_id'])
		{
		$data['db_value']=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));	

		
		}	

		 
		
	
	break;

	default:

		$data['total']=getTotalRow('category');

		$data['page_subtitle']="Category List (".$data['total'].")";
	 	

		$data['breadcrumb']=array("Category"=>"");		


		if($_GET['del_id'])
		{
			$r=deleteData("category",array("cat_id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 

			 $data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."category/category-list?mid=140';\",2000);</script>";

		}	
			

		$data['btn']="<a href='" . $configs['admin_vpath']."category/add-category' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Category</a>";
			
	    $data['db_list']= getQuery('category','*',"","cat_id asc",$configs['limit']);


}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

