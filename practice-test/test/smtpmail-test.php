<?php
include("phpmailer/PHPMailerAutoload.php");  

    function smtpmailer($to, $from, $from_name = 'Example.com', $subject, $body, $is_gmail = true)
    {
        global $error;
        $mail = new PHPMailer();
        $mail->IsSMTP();
        //$mail->SMTPAuth = false; 
        //$mail->SMTPSecure = "none";                 // sets the prefix to the servier
        ////$mail->Host = 'localhost';
       // $mail->Port = 25;
       // $mail->SMTPDebug  = 1;  
        
        
        $mail->SMTPAuth = false;
        $mail->SMTPAutoTLS = false; 
        $mail->Port = 25; 
        $mail->Host = 'localhost';
        $mail->SMTPDebug  = 0;  
        
        
       // $mail->SMTPAuth = true; 
       // $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
       // $mail->Host = 'smtpout.secureserver.net';
      //  $mail->Port = 465;
      //  $mail->SMTPDebug  = 1;  
        
          
        $mail->Username = 'info@asvab-tutoring.com';  
        $mail->Password = '@Dd3372008';
        
        //$mail->Username = 'info@smart-tutors.net';  
        //$mail->Password = '@Dd3372008';
        
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
    
    
    function smtpmailer1($to, $from, $from_name = 'Example.com', $subject, $body)
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
       if(smtpmailer("info@scriptgiant.com", "info@asvab-tutoring.com", "Test2 Mail", "test2 mail from Tender", "this is test email from Tender"))
	   {
         echo "okk"; 
	   }
       else
       {
         echo"failed";		   
       }   
	}
 ?>
