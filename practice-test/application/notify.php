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


if (strcmp($res, "VERIFIED") == 0) {

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

    $e = explode(",", $custom);

    $name=getDataByWhere("user","*",array("user_id"=>$e[0]));

    $order_id = insertTable("ocs_transactions", array("name" => $payment_status . "|" . $e[3] . "-u" . $e[0], "payer_email" => $_POST['payer_email'], "amount" => $payment_gross, "course" => $item_name, "subscription" => $e[2], "user_id" => (int)$e[0], "test_id" => (int)$e[1], "currency" => $payment_currency, "date" => date("Y-m-d"), "txn_id" => $txn_id, "payment_mode" => "paypal", "payment_status" => $payment_status, "renew"=>"renew"));

    $array = getDataByWhere("ocs_student", "*", ["user_id" => $e[0]]);
    $package = getQuery("package", "*");
    $package_id = getDataByWhere("package", "*", ["duration" => $e[2]]);

    $numericPart = (int)explode(" ", $e[2])[0];
    $days = $numericPart * 30;

    $timezone = new DateTimeZone('America/New_York');
    $date = new DateTime('now', $timezone);
    $date->modify('+' . $days . ' days');
    $exp_date = $date->format('Y-m-d');

    // updateTable("user", array("mobile" => $days), array("email" => 'test_renew@gmail.com')); // test code

    $timezone = new DateTimeZone('America/New_York');
    $date = new DateTime('now', $timezone);
    $formatted_date = $date->format('Y-m-d');

    updateTable("user", array("paypal_id" => $payer_email,"activation_status" => "Y", "fp" => 1, "pt" => 1), array("user_id" => $e[0]));

    $r = updateTable("subscription", array("subscription_date" => $formatted_date, "expire_date" => $exp_date, "package_id" => (int)$e[3], "amount" => $payment_gross, "payment_mode" => $payment_type, "txn_id" => $txn_id, "currency" => "USD"), array("user_id"=>$e[0]));

    // updateTable("login",["paid"=>1,"status"=>"Y","exp_date"=>$exp_date],["user_id"=>$e['0']]);
  
    //smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);

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