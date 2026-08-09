<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Transations List";

$data['breadcrumb']=array("Transaction List"=>"");

$data['course_list'] = getQuery("course","*");

$query=[];

if($_GET['course_id'])
{
    $query=array_merge($query, array("s.course_id"=>$_GET['course_id']));
}

if($_POST['key'])
{
	$query=array_merge($query,array("[or]"=>"(u.mobile like '%" .$_POST['key'] ."%' or u.email like '%" .$_POST['key'] ."%' or u.paypal_id like '%".$_POST['key']."%' or u.user_id=".(int)$_POST['key'].")"));	
}

$data['db_list']=getQueryByJoin(array("user as u","subscription as s"),"u.*,s.*",array_merge($query,array('s.amount!'=>0)),array("u.user_id"=>"s.user_id"),"s.id desc",$configs['limit'],"","s.txn_id");
 
$data['total']=getTotalRowsByJoin(array("user as u","subscription as s"),array_merge($query,array('s.amount!'=>0)),array("u.user_id"=>"s.user_id"),"","s.txn_id");

$data['page_title']="Transations List (".$data['total'] .")";
  
echo template($data);
?>

 