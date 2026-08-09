<?php
include("../../common/access.php");
include("../../common/autoload.php");

$data['module']=end(explode("/",dirname($_SERVER['REQUEST_URI'])));

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
	$_GET['action']="blog-list";
}

$data['breadcrumb']=[];
$data['page_title']="Blog Management";
 
switch($_GET['action'])
{
	case "blog-list":
	
		

		$data['breadcrumb']=array("Blog"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."blog/add-blog' class='btn btn-success pull-right'><i class='fa fa-plus'></i> Add Blog</a>";


		$data['total']=getTotalRow('ocs_blog');
	    $data['db_list']= getQuery('ocs_blog','*',"","id desc",$configs['limit']);
		
  		if($_GET['del_id'])
		{
			$r=deleteData("ocs_blog",array("id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 
		}	

		$data['page_subtitle']="Blog List (".$data['total'].")";	

	
	break;

	case "comment-list":

		$blog_name=getFldValueById("ocs_blog","name",array("id"=>$_GET['blog_id']));
	
		$data['page_subtitle']="Comment List of ".$blog_name."";

		$data['breadcrumb']=array("Blog"=>"");
		$data['btn']="<a href='" . $configs['admin_vpath']."blog/blog-list' class='btn btn-success pull-right'> Back </a>";


		$data['total']=getTotalRow('ocs_blog_review',array("blog_id"=>$_GET['blog_id']));
	    $data['db_list']= getQuery('ocs_blog_review','*',array("blog_id"=>$_GET['blog_id']),"id desc",$configs['limit']);
		
  		if($_GET['del_id'])
		{
			$r=deleteData("ocs_blog_review",array("id"=>$_GET['del_id']));			

			$data['msg']="<div clsss=''>" .str_replace("{table}",Ucwords(str_replace("_"," ",$_POST['table'])),$configs['delete_msg'])."</div>"; 
		}	

		if($_POST['ids']) 
		{
			
			for($i=0;$i<count($_POST['ids']);$i++)
			{
			   	 //deleteData("enquiry",array("id"=>$_POST['ids'][$i]));

			   	 $r=updateTable("ocs_blog_review",array("status"=>"Y"),array("id"=>$_POST['ids'][$i]),array("pic"));
			   	 
			   	 $data['msg']=str_replace("{table}","Comment",$configs['approve_msg']);
			}
			  
		}	

	
	break;

	
	case "add-blog":
		$data['page_subtitle']="Add Blog";

		$data['breadcrumb']=array("Blog"=>$configs['admin_vpath'] ."blog/blog-list","Add | Edit Blog"=>"");	

		//$data['categories']=getQuery("services","*",array("status"=>"Y"),"name");	

		if($_GET['id'])
		{
		$data['db_value']=getDataByWhere("ocs_blog","*",array("id"=>$_GET['id']));	

		
		}		 
		
	
	break;



}

 
$data['action']=$_GET['action'];
echo template($data); 
?>
 

