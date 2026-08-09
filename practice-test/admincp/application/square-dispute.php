<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Square Dispute List";

$data['breadcrumb']=array("Square Dispute List"=>"");

$data['course_list'] = getQuery("course","*");

if ($_GET['del_id']) {
    deleteData("square_disputes", array("id" => $_GET['del_id']));
    $data['msg'] = $configs["delete_msg"];
}

$query=[];

if($_GET['course_id'])
{
    $query=array_merge($query, array("s.course_id"=>$_GET['course_id']));
}

if($_POST['key'])
{
	$query=array_merge($query,array("[or]"=>"(u.first_name like '%" .$_POST['key'] ."%' or u.email like '%" .$_POST['key'] ."%' or u.last_name like '%".$_POST['key']."%' or u.user_id=".(int)$_POST['key'].")"));	
}

$data['db_list']=getQueryByJoin(array("user as u","square_disputes as s"), "u.*,s.*", $query, array("u.user_id"=>"s.user_id"),"s.id desc",$configs['limit']);
 
$data['total']=getTotalRowsByJoin(array("user as u", "square_disputes as s"), $query, array("u.user_id"=>"s.user_id"));

$data['page_title']="Dispute List (".$data['total'] .")";
  
echo template($data);
?>

 