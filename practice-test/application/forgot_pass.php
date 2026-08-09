<?
include("configs/license.php"); 
include("configs/common.php");
include("../smtpmail.php");  
if(isset($_POST))
{
 	$email1=;
 
	$exist=getTotalRow("login",array("email"=>$_POST['email1']));
	 
	if($exist):
	    
	    $d=getDataBywhere("login","*",array("email"=>$_POST['email1']));
	 
		$name=$d['fname'].$d['lname'];
		 
        $new_password=getPassword(8);

		updateTable("login",array("password"=>md5($new_password)),array("user_id"=>$d['user_id']));
       
        
         //$mail_body="Thanks for New Password.<br>Your Login Details:<br>Login :" . $_POST['email1']."<br>New Password:" . $new_password."<br>Thanks<br>".ucwords($dotcom);
        //$subject="Thanks for New Password";
        
       
        echo"<img src='".$configs['vpath']."img/on.png'> A new password has beeen sent your email id";
        
        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Forgot Password"));

        $body=html_entity_decode($mail['template']);
        $subject=$mail['subject'];
        
        $mail_body=str_replace("{name}",$name,$body);
        
        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
        $mail_body=str_replace("{email}",$_POST['email1'],$mail_body);
        $mail_body=str_replace("{password}",$new_password,$mail_body);
        $mail_body=html_entity_decode($mail_body);

        smtpmailer($_POST['email1'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body); 
          	
	else:
		echo"Invalid Email Id.";
	endif;
}
?>