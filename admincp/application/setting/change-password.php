<?php

include("../../common/access.php");

include("../../common/autoload.php");


$data['page_title']="Change Password";


$data['page_subtitle']="Change Password";


$data['breadcrumb']=array("Site Setting"=>"setting","Change Password"=>"");  




 

if(isset($_POST['change_password']) || isset($_POST['old_password']) || isset($_POST['password']))
{

  	$d=getDataByWhere("ocs_admin","*",array("admin_id"=>$_SESSION['admin_id'],"password"=>md5($_POST['old_password'])));
 
    
    if($d)
	{
	   
        $r=updateTable("ocs_admin",array("password"=>md5($_POST['new_password'])),array("admin_id"=>$_SESSION['admin_id']));
		
		$admin_mail= $setting['admin_mail'];
		$email=$_POST['email'];
        $subject="Admin Password : " . $dotcom;
        $message="Admin Password for " . $dotcom . ": " . $_POST['new_password'];
		mail($_SESSION['email'],$subject,$message,"From: ".$admin_mail);
 
        $data['msg']=$configs['success_msg'];
        
        ?>
        <script>
		window.setTimeout(function() {
		    window.location = '<?=$configs["vpath"] ?>admincp/account/login';
		  }, 3000);
		</script>
        <?
 
	 }
	 else
	 { 
	     $data['msg']=$configs['error_msg'];
	 }
}
echo template($data);
?>










 