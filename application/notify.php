<?php
include("../configs/config.php");
include("../configs/license.php");
include("../configs/common.php");
include("../smtpmail.php");

$setting = getDataByWhere("ocs_setting","*",array("id"=>1));

$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
    $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
  $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
  if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    $value = urlencode(stripslashes($value));
  } else {
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
}
$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
// $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$res = curl_exec($ch);

curl_close($ch);


if (strcmp ($res, "VERIFIED") == 0) 
{
    // Assign posted variables to local variables
    //$receiver_email  = $_POST['receiver_email'];
    $payer_email      = $_POST['payer_email'];
    $payer_status     = $_POST['payer_status'];
    $payment_gross    = $_POST['payment_gross'];
    $payment_currency = $_POST['mc_currency'];
    $payment_fee      = $_POST['payment_fee'];
    $payment_date     = $_POST['payment_date'];
    $payment_type     = $_POST['payment_type'];
    $payment_status   = $_POST['payment_status'];
    $pending_reason   = $_POST['pending_reason'];
    $txn_id           = $_POST['txn_id'];
    $txn_type         = $_POST['txn_type'];
    $item_name        = $_POST['item_name'];
    $item_number      = $_POST['item_number'];
    $custom           = $_POST['custom'];
    
    $e=explode(",",$custom);

    $order_id=insertTable("ocs_transactions",array("name"=> $payment_status."|".$e[3] . "-u" . $e[0],"payer_email"=>$_POST['payer_email'],"amount"=>$payment_gross,"course"=>$item_name,"subscription"=>$e[2],"user_id"=>(int)$e[0],"test_id"=>(int)$e[1],"currency"=>$payment_currency,"date"=>date("Y-m-d"),"txn_id"=>$txn_id,"payment_mode"=>"paypal","payment_status"=>"success"));

    $array= getDataByWhere("ocs_student","*",["user_id"=>$e[0]]);
    $package = getQuery("package","*");
    
    foreach ($package as $p) {
        if((int)$array['subscription']==$p['package_id'])
        {
            $numericPart = (int)explode(" ", $p['duration'])[0];
            $days = $numericPart*30;
        }
    }
    
    $timezone = new DateTimeZone('America/New_York');
    $date = new DateTime('now', $timezone);
    $date->modify('+' . $days . ' days');
    $exp_date = $date->format('Y-m-d');
    $array['exp_date'] = $exp_date;
//    $array['exp_date'] = date("Y-m-d", strtotime("+".$days ." day"));
    
    $array["referral_id"] =$e[3];
    $referral_earning =($payment_gross*($setting['ref_commission']/100));
    $referral_earning1 ="(" .$payment_gross . "*(" .$setting['ref_commission'] . "/100))";

//    $_POST['user_id']=insertTable("login",array("email"=>$array['email'],"reg_at"=>"web-sub","paid"=>1,"exp_date"=> $array['exp_date'],"user_type"=>"S","status"=>"Y","password"=>$array['password'],"activation_status"=>"Y","last_login"=>date("Y-m-d"),"reg_date"=>date("Y-m-d")));
    $flag = true;
    $code = number(8);
    while($flag){
        $code_exist = getFldValueById("user", "referral_code", array("referral_code"=>$code));
        if((int)$code_exist){
            $code = number(8);
        }
        else{
            $flag=false;
        }
    }
    
    $timezone = new DateTimeZone('America/New_York');
    $date = new DateTime('now', $timezone);
    $formatted_date = $date->format('Y-m-d');
    $formatted_date_time = $date->format('Y-m-d H:i:s');
    
    if($array['referral_id']){
        $_POST['user_id']=insertTable("user",array("email"=>$array['email'],"password"=>md5($array['password']),"first_name"=>$e[5],"last_name"=>$e[6],"mobile"=>$array['mobile'],"user_type"=>"Student","reg_date"=>$formatted_date,"activation_status"=>"Y","paypal_id"=>$payer_email,"referral_code"=>$code, "referer_id"=>$array['referral_id'],"reg_at"=>"web", "fp"=>1, "pt"=>1));
    }else{
        $_POST['user_id']=insertTable("user",array("email"=>$array['email'],"password"=>md5($array['password']),"first_name"=>$e[5],"last_name"=>$e[6],"mobile"=>$array['mobile'],"user_type"=>"Student","reg_date"=>$formatted_date,"activation_status"=>"Y","paypal_id"=>$payer_email,"referral_code"=>$code,"reg_at"=>"web", "fp"=>1, "pt"=>1));
    }
    
    insertTable("login",array("user_id"=>$_POST['user_id'],"last_login"=>$formatted_date_time));
    $r=insertTable("subscription",array("course_id"=>$array['test_id'],"user_id"=>$_POST['user_id'],"subscription_date"=>$formatted_date,"expire_date"=>$array['exp_date'],"package_id"=>$array['subscription'], "amount"=>$payment_gross, "payment_mode"=>$payment_type, "txn_id"=>$txn_id, "currency"=>"USD"));
    
    if($array['referral_id'])
    {
        $referrer=getDataByWhere("user","*",["referral_code"=>$array['referral_id']]);
        $r=insertTable("reference",array("user_id"=>$_POST['user_id'],"commission"=>$referral_earning,"referer_id"=>$referrer['user_id'],"date"=>$formatted_date)); 
        
        $earning=getFldValueById("reference","sum(commission)",['referer_id'=>$referrer['user_id']]);
	
        $paid=getFldValueById("referrer_payout","sum(amount)",['user_id'=>$referrer['user_id']]);
        
        $due=$earning-$paid;

        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Referral Join"));
            
        $body=html_entity_decode($mail['template']);
        
        $subject=str_replace("{ref_name}",$array['name'] ,$mail['subject']);
                
        $mail_body=str_replace("{ref_name}",$array['name'],$body);
            
        $mail_body=str_replace("{name}",$referrer['first_name'],$mail_body);

        $mail_body=str_replace("{subscription_amount}",number_format($payment_gross,2),$mail_body);

        $mail_body=str_replace("{total_earning}",number_format($earning,2),$mail_body);
        
        $mail_body=str_replace("{paid}",number_format($paid,2),$mail_body);

        $mail_body=str_replace("{total_due}",number_format($due,2),$mail_body);
                    
        $mail_body=html_entity_decode($mail_body);
                
        smtpmailer($referrer['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  

    }

    $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Register"));
    $body=html_entity_decode($mail['template']);
    $subject=$mail['subject'];
    
    $sendpassword=$array['password'];
    
    $mail_body=str_replace("{name}",$e[5]." ".$e[6] ,$body);
    
    $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
        
    $mail_body=str_replace("{email}",$array['email'],$mail_body);
    $mail_body=str_replace("{password}",$sendpassword,$mail_body);
    $mail_body=html_entity_decode($mail_body);
    
    smtpmailer($array['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);
    
    
//    $email_body = '
//    <html>
//    <body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
//        <p>Hi '.$e[5].' '.$e[6].',</p>
//        <p>Thank you for your registration.</p>
//        <p><strong>Login URL:</strong> <a href="'.ucwords($configs['dotcom']).'" style="color: #0073e6; text-decoration: none;">'.ucwords($configs['dotcom']).'</a></p>
//        <p><strong>Your Login ID:</strong> '.$array['email'].'</p>
//        <p><strong>Your Password:</strong> '.$sendpassword.'</p>
//        <p>Thanks,</p>
//        <p>The Test Prep Team</p>
//
//        <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; text-align: center;">
//            <p style="color: #ff6600; font-weight: bold;">🌟 Unlock Your Potential with Test Prep! 🌟</p>
//            <p><strong>Exclusive Tutoring Services Just for You!</strong></p>
//            <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
//                <li>✅ <strong>One-on-One Sessions:</strong> Personalized coaching tailored to your needs.</li>
//                <li>✅ <strong>Comprehensive Study Materials:</strong> Access the best resources and guides.</li>
//                <li>✅ <strong>Expert Tutors:</strong> Learn from the best in the field.</li>
//            </ul>
//            <p style="color: #ff6600; font-weight: bold;">🎓 Achieve Your Goals with Us!</p>
//            <p><a href="https://staging-staging-test-prep.asvab-tutoring.com/get-tutor" style="display: inline-block; padding: 10px 20px; margin-top: 10px; font-size: 16px; color: #fff; background-color: #0073e6; text-decoration: none; border-radius: 5px;">Explore Our Tutoring Facilities Now!</a></p>
//        </div>
//
//        <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; text-align: center;">
//            <p style="color: #ff6600; font-weight: bold;">📚 Expand Your Knowledge with Our Additional Courses! 📚</p>
//            <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
//                <li>🔹 <strong>HESI A2 Preparation:</strong> Get the best scores with our expert guidance.</li>
//                <li>🔹 <strong>ATI TEAS Preparation:</strong> Comprehensive resources and tutoring to excel.</li>
//                <li>🔹 <strong>CNA Preparation:</strong> Customized programs to help you succeed.</li>
//            </ul>
//            <p><a href="https://staging-staging-test-prep.asvab-tutoring.com/" style="display: inline-block; padding: 10px 20px; margin-top: 10px; font-size: 16px; color: #fff; background-color: #0073e6; text-decoration: none; border-radius: 5px;">Discover All Our Courses Here!</a></p>
//        </div>
//
//        <p style="text-align: center;">If you have any questions or need further assistance, feel free to reach out to our team.</p>
//    </body>
//    </html>
//    ';
//    
//    $email_body=html_entity_decode(html_entity_decode(html_entity_decode($email_body)));
//    smtpmailer($array['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $email_body);
}
else if (strcmp ($res, "INVALID") == 0) 
{
   // $_POST['payer_email']
    mail("info@scriptgiant.com", "SORRY, WE ARE UNABLE TO PROCESS YOUR ORDER","SORRY, WE ARE UNABLE TO PROCESS YOUR ORDER.\n

    We regret to inform you that we have been unable to complete payment for this transaction.\n
    Please verify with your bank that there are funds available to pay for your order. We then suggest re-trying your order one further time.\n

    If you are still having problems please contact us by phone or email and we will be happy to provide a speedy resolution.\n

    We apologise for any inconvenience.\r\n" . Ucwords($configs['dotcom']),"From:" .$configs['admin_mail'] ."\r\nReply-to:" . $configs['admin_mail']);

     //redirect($configs['dotcom']."regret");
}

?>