<?php 
include("../configs/autoload.php");
include("../smtpmail.php");
chklogin();
$error="";
$success="";
$data['meta_title']="Change Password";
 
if(isset($_POST['SBMT']))
{
 
    $n=getTotalRow("user",array("password"=>md5($_POST['old_password']),"user_id"=>$_SESSION['user_id']));

    if($n)
    {

        if($_POST['new_password']==$_POST['conf_password']) 
        {
            $r1=updateTable("user",array("password"=>md5($_POST['new_password'])),array("user_id"=>$_SESSION['user_id']));
             
            $data['msg']="<img src='".$configs['vpath']."img/on.png'> Password has been successfully changed.</font>";
            
	        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Change Password"));

	        $body=html_entity_decode($mail['template']);
	        $subject=$mail['subject'];
	        
	        $mail_body=str_replace("{name}",$_SESSION['name'],$body);
	        $mail_body=str_replace("{dotcom}",ucwords($config['dotcom']),$mail_body);
	        $mail_body=str_replace("{email}",$_SESSION['email'],$mail_body);
	        $mail_body=str_replace("{password}",$_POST['new_password'],$mail_body);
	        $mail_body=html_entity_decode($mail_body);

        	smtpmailer($_SESSION['email'],$configs['support_mail'], ucwords($config['dotcom']),$subject, $mail_body); 
       
        }
        else
        {
          $data['msg']="<font color=red>Confirm password does not match with new password!</font>";
        }  
      
    } 
    else
    {

      $data['msg']="<font color=red>Wrong old password!!</font>";
    }  
}        
echo template($data);

?>   
 