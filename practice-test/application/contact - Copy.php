<?php 
include '../configs/autoload.php';
include("smtpmail.php");

if(isset($_POST['Submit']))
{
    $_POST['date']=date("Y-m-d");

    $r=insertTable("enquiry",$_POST); 
    if($r)
    {

        $data['msg']="<img src='".$configs['vpath']."img/on.png'> We have received your information. We will contact you soon.";
        
        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Contact Us"));

        $body=html_entity_decode($mail['template']);
        $subject=$mail['subject'];

        $mail_body=str_replace("{name}",$_POST['fname'] ." ". $_POST['lname'],$body);
        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
        $mail_body=str_replace("{password}",$password,$mail_body);
        $mail_body=html_entity_decode($mail_body);
                     
        smtpmailer($_POST['email'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  
        smtpmailer($configs['admin_mail'],$_POST['email'], $_POST['name'],$subject, $mail_body);
                        
    } 
    else
    {
        $data['msg']="<img src='".$configs['vpath']."img/off.png'> Error! Please try again.";
    }
          
}
          
echo template($data);
?>   
