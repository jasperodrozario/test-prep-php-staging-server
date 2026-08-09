<?
include("../common/access.php");
include("../common/autoload.php");
include("../../smtpmail.php");

$data['page_title']="Send Email";
$data['breadcrumb']=array("Enquiery List"=>"enquires.list","Send Email"=>"");

 
if($_POST['SBMT_EMAIL']){
    
    smtpmailer($_POST['email'],"info@asvab-tutoring.com",$_POST['name'], $_POST['subject'], $_POST['body']);
    
//    $data['msg']=$configs['success_msg'];
//    header("location:enquires.list");
}

echo template($data);
?>

 