<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add Tutoring Session Package";
$data['breadcrumb']=array("Tutoring Package List"=>"tutor-package-list","Add Package"=>"");

if($_POST['SBMT_REG']):
    $flag = 0;
   	if($_POST['price_id']):
   	    $flag=updateTable("subscriptions_price",$_POST,array("price_id"=>$_POST['price_id']));
		$package_id=$_POST['price_id'];

	else:
	    unset($_POST['price_id']);
	    $flag=insertTable("subscriptions_price",$_POST);
	endif;

	if($flag):
	 	$data['msg']=$configs['success_msg'];
        header("location: tutor-package-list");
    else:
        $data['msg']=$configs['error_msg'];
	endif;

endif;

if($_REQUEST['price_id'])
{
    $data['db_value']=getDataByWhere("subscriptions_price","*",array("price_id"=>$_REQUEST['price_id']));
} 

echo template($data);
?>

 