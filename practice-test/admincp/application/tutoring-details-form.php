<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add Tutoring Details";
$data['breadcrumb']=array("Tutoring Details List"=>"tutoring-details-list","Add Package"=>"");

if($_POST['SBMT_REG']):
    $flag = 0;
   	if($_POST['det_id']):
   	    $flag=updateTable("subscriptions_details",$_POST,array("det_id"=>$_POST['det_id']));
		$package_id=$_POST['price_id'];

	else:
	    unset($_POST['det_id']);
	    $flag=insertTable("subscriptions_details",$_POST);
	endif;

	if($flag):
	 	$data['msg']=$configs['success_msg'];
        header("location: tutoring-details-list");
    else:
        $data['msg']=$configs['error_msg'];
	endif;

endif;

if($_REQUEST['det_id'])
{
    $data['db_value']=getDataByWhere("subscriptions_details","*",array("det_id"=>$_REQUEST['det_id']));
} 

echo template($data);
?>

 