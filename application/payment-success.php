<?
include("../configs/autoload.php");
chklogin();

$data['db_value']=getDataByWhere("ocs_contents","*",array("id"=>70));

// $data['meta_title']="Payment Success";

echo template($data);
?>

