<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Square Transations List";

$data['breadcrumb']=array("Web Analytics"=>"website-analytics");

$data['course_list'] = getQuery("asvab_payments","test_name", array(), "test_name", "", "", "test_name");

$query=[];

if($_GET['test_name'])
{
    $query=array_merge($query, array("s.test_name"=>$_GET['test_name']));
}

if($_POST['key'])
{
	$query=array_merge($query,array("[or]"=>"(u.mobile like '%" .$_POST['key'] ."%' or u.email like '%" .$_POST['key'] ."%' or u.user_id=".(int)$_POST['key'].")"));	
}

$data['db_list']=getQueryByJoin(array("user as u", "asvab_payments as s"),"u.*,s.*", $query, array("u.user_id"=>"s.user_id"), "s.id desc", $configs['limit']);
 
$data['total']=getTotalRowsByJoin(array("user as u", "asvab_payments as s"), $query, array("u.user_id"=>"s.user_id"));

$data['page_title']="Square Transations List (".$data['total'] .")";
  
echo template($data);
?>

 