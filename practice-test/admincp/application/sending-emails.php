<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../smtpmail.php");

$data['page_title'] = "Send Email";

$data['breadcrumb'] = array("Send Email" => "");

$data['btn'] = "<input type='button' value='Mail List' class='btn btn-success' onClick=\"javascript:window.location='mailsetup'\">";

$data['db_value']=getDataByWhere("mailsetup","*",array("id"=>$_REQUEST['id']));

$data["id"]=$_REQUEST['id'];

if (isset($_POST['send'])) {

    $mail = $data['db_value'];

    $subject = $mail['subject'];

    $body = html_entity_decode($mail['template']);
    
    $mail_body = html_entity_decode($body);    

    smtpmailer($_POST['receiver_email'], "info@asvab-tutoring.com", ucwords($configs['dotcom']), $subject, $mail_body);
}

echo template($data);
