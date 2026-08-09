<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Admin Menu Management";

$data['breadcrumb']=array("Admin Menu Management"=>"adminmenu.list"," Admin Menu List"=>"");

$data['btn']="<input type='button' OnClick=\"javascritp:window.location.href='adminmenu.entryform?parent_id=0';\" class='btn btn-success' value='Add New'>";

if($_REQUEST['del_id']):
    deleteData("adminmenu",array("parent_id"=>$_REQUEST["del_id"]));
    deleteData("adminmenu",array("id"=>$_REQUEST["del_id"]));
    $data['msg']=$configs['delete_msg'];	  
endif;
$data['db_value']=getQuery("adminmenu","*",array("parent_id"=>0),"ord");
echo template($data);
?>
