<?
include("../common/access.php");
include("../common/autoload.php");
$data['page_title']="Mail Template  Management";
$data['breadcrumb']=array("Mail Tempalte List"=>"mailsetup","Add | Edit Template"=>"");

$data['btn']="<input type='button' value='Back to List' class='btn btn-success' onClick=\"javascript:history.back()\">";

if($_POST['submit'])
{

  
    $_POST['template']=htmlentities($_POST['template']);
    if($_POST['id'])
    {
		$r=updateTable("mailsetup",$_POST,array("id"=>$_POST["id"]));
   		$data["id"]=$_POST["id"];
	}
   		
    else
    {
	unset($_POST['id']);
        $r=insertTable("mailsetup",$_POST);
        $data["id"]=$r;
	}
  
    if($r)
    {
	   
	
		$data['msg']=$configs["success_msg"];
	}
	else
	{
		$data['msg']=$configs["error_msg"];
	}
   
}
if($_REQUEST['id'])
{
	$data['db_value']=getDataByWhere("mailsetup","*",array("id"=>$_REQUEST['id']));
	$data["id"]=$_REQUEST['id'];
}
 
echo template($data);
?>	 


