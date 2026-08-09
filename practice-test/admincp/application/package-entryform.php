<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add Course Package";
$data['breadcrumb']=array("Package List"=>"package-list","Add Package"=>"");

if($_POST['SBMT_REG']):
    $flag = 0;
   	if($_POST['package_id']):
   	    $flag=updateTable("package",$_POST,array("package_id"=>$_POST['package_id']));
		$package_id=$_POST['package_id'];

	else:
	    unset($_POST['package_id']);
	    $flag=insertTable("package",$_POST);
	endif;

	if($flag):
	 	$data['msg']=$configs['success_msg'];
        header("location:package-list");
    else:
        $data['msg']=$configs['error_msg'];
	endif;

endif;

if($_REQUEST['package_id'])
{
    $data['db_value']=getDataByWhere("package","*",array("package_id"=>$_REQUEST['package_id']));
} 

echo template($data);
?>

 