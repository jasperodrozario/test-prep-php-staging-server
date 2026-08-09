<?php
include("phpmailer/PHPMailerAutoload.php");  

    function smtpmailer($to, $from, $from_name = 'Example.com', $subject, $body, $is_gmail = true)
    {
        global $error;
        $mail = new PHPMailer();
        $mail->IsSMTP();
         
        $mail->SMTPAuth = true;
//        $mail->SMTPAutoTLS = false; 
        $mail->Port = 587; 
        $mail->Host = 'smtp.elasticemail.com';
        $mail->SMTPDebug  = 0;  

        
       // $mail->SMTPAuth = true; 
         $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
       // $mail->Host = 'smtpout.secureserver.net';
      //  $mail->Port = 465;
      //  $mail->SMTPDebug  = 1;  
        
          
        $mail->Username = "somdtutoring@gmail.com";  
        $mail->Password = '9DCFB39728B98EB58C9E7FE1281D0A63E0E8';
        
        //$mail->Username = "info@scriptgiant.com";  
        //$mail->Password = 'DE4766D1910E90359EEC1D99F25C6C1CA194';
        
        
        $mail->IsHTML(true);
        $mail->From="info@asvab-tutoring.com";        $mail->FromName="ASVAB Tutoring";
        $mail->Sender="somdtutoring@gmail.com"; // indicates ReturnPath header
        $mail->AddReplyTo($from, $from_name); // indicates ReplyTo headers
    //     $mail->AddCC('somdtutoring@gmail.com', 'CC: to site.com');
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
    
        
    function smtpmailer2($to, $from, $from_name = 'Example.com', $subject, $body)
    {
   
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
		// Create email headers
		$headers .= 'From: '.$from."\r\n".
	    'Reply-To: '.$from."\r\n" .
	    'X-Mailer: PHP/' . phpversion();
        
        if(mail($to, $subject, $body, $headers))
        {
            $error = 'Message sent!';
            return true;
        }
        else
        {
        	$error = 'Mail error: '.$mail->ErrorInfo;
            return false;        
        }
    }
    
    
    
    if($_GET['test'])
	{		
       if(smtpmailer("info@asvab-tutoring.com", "info@asvab-tutoring.com", "Test mail from smd2", "test2 mail from asvab", "this is test email from asvab"))
	   {
         echo "okk"; 
	   }
       else
       {
         echo"failed";		   
       }   
	}
 ?>
