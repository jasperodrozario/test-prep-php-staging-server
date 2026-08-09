<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Square Plan Variations List";

$data['breadcrumb']=array("Square Plan Variations List"=>"");

$data['course_list'] = getQuery("course","*");

$data["btn"]="<input name='button' type='button' class='btn btn-success' onclick=\"javascript:window.location.href='".$configs['admin_vpath']."transaction-edit-form?action=add-plan-variation'\" value='Add New' style='float:right' />";

$query = [];

if ($_GET['env_name']) {
	if ($_GET['env_name'] != "all") {
		$query = array_merge($query, array("environment like '%" => $_GET['env_name'] . "%'"));
		$param[] = "env_name=" . $_GET['env_name'];
	}
}

if ($_GET['del_id']) {
    deleteData("square_plan_variations", array("id" => $_GET['del_id']));
    $data['msg'] = $configs["delete_msg"];
}

if($_POST['key'])
{
	$query=array_merge($query,array("[or]"=>"(square_plan_id like '%" .$_POST['key'] ."%' or plan_variation_id like '%" .$_POST['key'] ."%' or environment like '%".$_POST['key']."%')"));	
}

$data['db_list']=getQuery("square_plan_variations", "*", $query,"id desc",$configs['limit']);
 
$data['total']=getTotalRow("square_plan_variations", $query);

$data['page_title']="Square Plan Variations List (".$data['total'] .")";
  
echo template($data);
?>

 