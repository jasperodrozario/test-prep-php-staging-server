<?php 
include("../common/access.php");
include("../common/autoload.php");


if($_POST['submit']):
   if(!$_POST['status']){$_POST['status']="Y";}
	 
    $_POST['contents']=htmlentities(htmlentities($_POST['contents']));
    if(!$_POST['seo_url'] && $_POST['seo_url']!='#')
    {
	 	$_POST['seo_url']=seo_url(strtolower($_POST['title']));
	} 
	$_POST['seo_url']=strtolower($_POST['seo_url']); 
	
   	if($_POST['id'])
   	{
		$r=updateTable("contents",$_POST,array("id"=>$_POST['id']),array("img"));
		$data['id']=$_POST['id'];
	}
 
	else 
	{     
		unset($_POST['id']);
                $r=insertTable("contents",$_POST,array("img"));
		$data['id']=$r;
	}
	    
	 
	if($r)
	{ 
	
		
		$data['msg']=$configs['success_msg'];
		//$data['msg'].="<script>setTimeout(\"location.href = '".$configs['vpath'] ."admin/content_list.php';\",2500);</script>";
		
	}
    else
    {
		$data['msg']=$configs['error_msg'];
	}	
       
    
endif;

$data['page_title']="Page Content Management";
$data['breadcrumb']=array("Page Content List"=>"content_list","Add | Edit " .Ucwords($_GET['type'])=>""); 

$data["btn"]="<input type='button' value='Back to List' class='btn btn-success' onclick=\"javascript:window.location.href='content_list'\">";

if($_REQUEST['id'])
{
	 
	$data['db_value']=getDataByWhere("contents","*",array("id"=>$_REQUEST['id']));
	$data['id']=$_REQUEST['id']; 
	
}	
$data['parent_list']=getQuery("contents","*",array("parent_id"=>0),"title");
		
if(!$data['db_value']['status']){$data['db_value']['status']="Y";}
if(!$data['db_value']['menu']){$data['db_value']['menu']="N";}

echo template($data);
?>		

