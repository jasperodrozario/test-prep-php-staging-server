<?php
include("../../common/access.php");
include("../../common/autoload.php");


if($_GET['id'])
{
	$_GET['id']=decrypt($_GET['id']);
}

if($_GET['city_id'])
{
    $_REQUEST['city_id']=$_GET['city_id'];
	$_GET['city_id']=decrypt($_GET['city_id']);
	
	
}
 
if($_GET['del_id'])
{
	$_GET['del_id']=decrypt($_GET['del_id']);
}

if(!$_GET['action'])
{
	$_GET['action']="city-list";
}

$data['breadcrumb']=[];
$data['page_title']="City Management";
 
switch($_GET['action'])
{
	case "city-list":
	
		$data['page_subtitle']="City List";

		$data['breadcrumb']=array("City"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."city/add-city' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add City</a>";
		$data['total']=getTotalRow('city');
	    $data['db_list']= getQuery('city','*',"","id",$configs['limit']);

	    if($_GET['del_id'])
		{

			$r=deleteData("city",array("id"=>$_GET['del_id']));
		
		

			$data['msg']=str_replace("{table}","city",$configs['delete_msg']);
		}
	
	break;

	
	case "add-city":
		$data['page_subtitle']="Add City";

		$data['breadcrumb']=array("City"=>$configs['admin_vpath'] ."city/city-list","Add | Edit City"=>"");
		 
		if($_GET['id'])
		{
		   $data['db_value']= getDataByWhere('city','*',array('id'=>$_GET['id']));
		}
	
	break;
	
	case "area-list":

		if($_GET['city_id']){
			$city_name="of ".getFldNameById("city","name",array("id"=>$_GET['city_id']));
		}
	
		$data['page_subtitle']="Area List ".$city_name;

		$data['breadcrumb']=array("Area"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."city/add-area?city_id=".$_REQUEST['city_id']."' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Area</a>";
		

		if($_GET['city_id'])
		{
			
		   $data['db_list']= getQuery('area','*',array("city_id"=>$_GET['city_id']),"id desc",$configs['limit']);

		   $data['total']=getTotalRow('area',array("city_id"=>$_GET['city_id']));
		}
		else{
			 $data['db_list']= getQuery('area','*',"","id",$configs['limit']);

			 $data['total']=getTotalRow('area');
		}

	   

	    if($_GET['del_id'])
		{

			$r=deleteData("area",array("id"=>$_GET['del_id']));
		
			$data['msg']=str_replace("{table}","area",$configs['delete_msg']);
			
			$data['msg'].="<script>setTimeout(\"location.href = '".$configs['admin_vpath'] ."city/area-list?city_id=".$_REQUEST['city_id']."';\",2000);</script>";
			
		
		}
	
	break;

	case "add-area":
		$data['page_subtitle']="Add Area";

		$data['breadcrumb']=array("Area"=>$configs['admin_vpath'] ."city/area-list?city_id=".$_REQUEST['city_id'],"Add | Edit Area"=>"");
		 
		if($_GET['id'])
		{
		   $data['db_value']= getDataByWhere('area','*',array('id'=>$_GET['id']));
		}
	
	break;

}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

