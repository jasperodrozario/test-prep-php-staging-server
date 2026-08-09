<?
include("../common/access.php");
include("../common/autoload.php");


if($_GET['user_id'])
{
	$data['db_value']=getDataByWhere("user","*",array("user_id"=>$_GET['user_id']));

}
unset($configs['includes']);

echo  template($data); 
?>