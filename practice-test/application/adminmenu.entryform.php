<?
include("../common/access.php");
include("../common/autoload.php");
$data['page_title']="Admin Menu Management";
$data['breadcrumb']=array("Admin Menu Management"=>"adminmenu.list",
     "Add/Modify adminmenu"=>"");  

 
$data['btn']="<a href='adminmenu.list' class='btn btn-success'>Back To List</a>";
 
$msg="";
if($_POST['submit']):
    unset($_POST['submit'],$_POST['tid'],$_POST['delete']);
    $_POST['ord']=(int)$_POST['ord'];
    $_POST['parent_id']=(int)$_POST['parent_id'];
    
    if(!$_POST['id']):
        $r=insertTable("adminmenu",$_POST,"",1);
        $data["id"]=$r;
       
    else:
        $r=updateTable("adminmenu",$_POST,array("id"=>$_POST['id']));
        $data["id"]=$_POST['id'];
    endif;
    if($r):
        $data['msg']=$configs['success_msg'];
        
        header("location:adminmenu.list?tid=" . $_REQUEST['tid']);
    else:
        $data['msg']=$configs['error_msg'];
    endif;   
elseif($_POST['DELT']):
    deleteData("adminmenu",array("parent_id"=>$_POST['id']));
    deleteData("adminmenu",array("id"=>$_POST['id']));
    
    $data['msg']=$configs['delete_msg'];
   
endif;
if($_REQUEST['id']):
    $data['db_value']=getDataByWhere("adminmenu","*",array("id"=>$_REQUEST['id']));
    $data['id']=$_REQUEST['id'];
endif;
 
 
echo template($data);
?>

