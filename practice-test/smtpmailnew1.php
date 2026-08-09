<?php
ini_set('display_errors',1);
include("phpmailer/vendor/autoload.php");  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//include("phpmailer/PHPMailerAutoload.php");  

    function smtpmailer($to, $from, $from_name = 'Example.com', $subject, $body, $is_gmail = true)
    {
        global $error;
        $mail = new PHPMailer;
        $mail->IsSMTP();
   
        
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";    
        $mail->Port = 465; 
        $mail->Host = 'smtp.elasticemail.com';
        $mail->SMTPDebug  = 1;  

        $mail->Username = 'info@scriptgiant.com';  
        $mail->Password = 'EDC4D78340D50AB6E140CD58073A5E362C65';
        
      
        
        $mail->IsHTML(true);
        $mail->From=$from;
        $mail->FromName=$from_name;
        $mail->Sender=$from; // indicates ReturnPath header
        $mail->AddReplyTo($from, $from_name); // indicates ReplyTo headers
       // $mail->AddCC('info@scriptgiant.com', 'CC: to site.com');
        //$mail->addStringAttachment(file_get_contents('http://www.listopapi.com//images/logo.png'), 'logo.png');
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        if(!$mail->Send())
        {
            $error = 'Mail error: '.$mail->ErrorInfo;
            return false;
        }
        else
        {
            $error = 'Message sent!';
            return true;
        }
    }
    
 
    
    
    
    if($_GET['test'])
	{		
       if(smtpmailer("info@scriptgiant.com", "info@asvab-tutoring.com", "Test2 Mail", "test2 mail from asvab", "this is test email from asvab"))
	   {
         echo "smtp-ok"; 
	   }
       else
       {
         echo"failed";		   
       }   
	}
 ?>
