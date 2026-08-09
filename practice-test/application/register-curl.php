<?php 
include("../configs/configs.php");
include("../configs/common.php");
include("../configs/license.php");
//include("smtpmail.php");  

include("../smtpmail.php");
  
$exist=getTotalRow("login",array("email"=>$_POST['email']));
    
$exist2=getTotalRow("student",array("mobile"=>$_POST['mobile']));
        
if($exist)
{
        $_POST['user_id']=getFldValueById("login","user_id",array("email"=>$_POST['email']));
        $error="<h4 style='color:red'>Email Id is Exists!</h4>";
}	
    
if(!$_POST['user_id'])	
{	
        $_POST['user_id']=insertTable("login",array("email"=>$_POST['email'],"reg_at"=>"web-sub","status"=>"Y","password"=>$_POST['password'],"activation_status"=>"Y","reg_date"=>date("Y-m-d")));
}      
if($_POST['user_id'] && !$error)
{
        	
	       
	              
	        $r=insertTable("student",array("test_id"=>$_POST['test_id'],"user_id"=>$_POST['user_id'],"first_name"=>$_POST['name'],"mobile"=>$_POST['mobile']));

	        $data['msg']='<h4>You have successfully registerted in '.$configs['dotcom'].'. Your account will be activate by  webmaster with in 24 hours.</h4>';

	        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Register"));
	        
	       
	        $body=html_entity_decode($mail['template']);
	        $subject=$mail['subject'];
	        $sendpassword=$_POST['password'];
	       
	        $mail_body=str_replace("{name}",$_POST['name'] ,$body);
	      
	        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
	        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
	        $mail_body=str_replace("{password}",$sendpassword,$mail_body);
	        $mail_body=html_entity_decode($mail_body);
	        
	        smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  
      
}
 
     
?>   
 