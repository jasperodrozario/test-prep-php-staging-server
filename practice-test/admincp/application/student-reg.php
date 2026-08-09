<?
include("../common/access.php");
include("../common/autoload.php");
   
$data['page_title']="ADD | Edit Student";

if($_GET['user_id'])
{
	$data['db_value']=getDataByJoin(array("user as u","subscription as s"),"u.*,s.*",array("u.user_id"=>$_GET['user_id']),array("u.user_id"=>"s.user_id"));

    /*if(!$data['db_value']['code'])
	{
        $code=substr($_GET['user_id'] .seo_url($data['db_value']['first_name']),0,8);

		updateTable("login",['code'=>$code],["user_id"=>$_GET['user_id']]);
		
		$data['db_value']['code']=$code;
	}*/
	$data['page_title']="ADD | Edit Student : <font color=orange>".ucwords($data['db_value']['first_name']);
    $data['page_title'].=" <font color=gray> | </font> Member Since : ".showdate($data['db_value']['reg_date']);
}

$data['breadcrumb']=array("Students Management"=>"student-list");
$data['btn']="<a href='javascript:history.back()' class='btn btn-success'>Back To List</a>";

if(!$_GET['info'])
{
	$_GET['info']="login";
}
$data['info']=$_GET['info'];
echo template($data);  
?>


