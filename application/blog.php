<?
include("../configs/autoload.php");


//$data['blog_categories']=getQuery("services","*",array("status"=>"Y"),"name");
$data['meta_title']="Blog";

if($_GET['cat_id'])
{
	
	$data['blogs']=getQuery("ocs_blog","*",array("status"=>"Y","cat_id"=>$_GET['cat_id']),"id desc",$configs['limit']);
	$data['total']=getTotalRow("ocs_blog",array("status"=>"Y","cat_id"=>$_GET['cat_id']));
}
else
{
	$course_groups = getQuery("course_group","*",array());
    
    foreach($course_groups as $c){
        $total=getTotalRow("ocs_blog",array("status"=>"Y", "course_group"=>$c['group_id']));
        
        if($total){
            $data['blogs'][$c['group_name']] = getQuery("ocs_blog","*",array("status"=>"Y", "course_group"=>$c['group_id']),"id desc",$configs['limit']);
        }
    }
}

echo template($data);
?>
