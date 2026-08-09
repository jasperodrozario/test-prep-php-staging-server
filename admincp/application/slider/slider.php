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
	$_GET['action']="slider-list";
}

$data['breadcrumb']=[];
$data['page_title']="Slider Management";
 
switch($_GET['action'])
{
	

	
	case "add-slider":
		$data['page_subtitle']="Add Slider";

		$data['breadcrumb']=array("Slider"=>$configs['admin_vpath'] ."slider/slider-list","Add | Edit Slider"=>"");		

		/*if($_GET['id'])
		{
		$data['db_value']=getDataByWhere("banner","*",array("id"=>$_GET['id']));	

		
		}	*/	


		switch($_GET['lang_id'])
		{
			case"1":
		
				$data['db_value']= getDataByWhere("slider","*",array("id"=>$_GET['id']));

			break;
			default:
			
				$json=getFldValueById("lang_data","json_value",array("table_name"=>"slider","fld_id"=>$_GET['id'],"lang_id"=>$_GET['lang_id']));
				
				
				$json=preg_replace('/[[:cntrl:]]/', '', $json);
		
				$data['db_value']=json_decode($json, true);
				
				
		}

		
	
	break;

	default:
	    $data['total']=getTotalRow('slider');
	    
	 	$data['page_subtitle']="Slider List (".$data['total'].")";

		$data['breadcrumb']=array("Slider"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."slider/add-slider' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Slider</a>";

		
	    $data['db_list']= getQuery('slider','*',"","id desc",$configs['limit']);

	    if($_GET['del_id'])
		{
			$r=deleteData("slider",array("id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 

			 $data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."slider/slider-list?mid=140';\",2000);</script>";
			 
		}		




}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

