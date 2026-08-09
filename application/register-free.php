<?php
include("../configs/autoload.php");
include("../smtpmail.php");
$error = null;

$data['course_group_list'] = getQuery("course", "*", "", "ord");
$data['courses_list'] = getQuery("course", "*", array("is_online" => "Y", "tutoring"=>1), "group_id, ord");
$data['package_list'] = getQuery("package", "*");

$data['meta_title']="Registion for 1-On-1 Tutor";

if ($_POST['register'] == "register") {
    $exist_email = getTotalRow("user", array("email" => $_POST['email']));

    //    $exist_mobile = getTotalRow("user",array("mobile"=>$_POST['mobile']));

    if ($_POST['referral_id']) {
        $valid_referral = getTotalRow("user", array("referral_code" => $_POST['referral_id']));

        if (!$valid_referral) {
            $error = "<h4 style='color:red'>Invalid Referral Code!</h4>";
            $_POST['referral_id'] = "";
        }
    }

    if ($exist_email) {
        $error = "<h4 style='color:red'>The email exists. Use different email!</h4>";
    }
    //    if($exist_mobile)
    //    {
    //        $error="<h4 style='color:red'>The phone number exists. Use different phone number!</h4>";
    //    }
    if ($_POST['password'] != $_POST['re_password']) {
        $error = "<h4 style='color:red'>Password & Repeat password does not match!</h4>";
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "<h4 style='color:red'>Invalid Email address!</h4>";
    }


    if ($valid_referral) {
        // $setting[$_POST['subscription']]=($setting[$_POST['subscription']]*((100-$setting['ref_discount'])/100));

        if ($_POST['referral_id']) {
            $_GET['referral_id'] = $_POST['referral_id'];
        }
    }

    if (!$error) {
        $_POST['reg_date'] = date("Y-m-d");

        $_POST['status'] = "Y";

        $_POST['user_id'] = insertTable("ocs_student", $_POST);

        $flag = true;
        if ($flag) {
            $flag = true;
            $code = number(8);
            while ($flag) {
                $code_exist = getFldValueById("user", "referral_code", array("referral_code" => $code));
                if ((int)$code_exist) {
                    $code = number(8);
                } else {
                    $flag = false;
                }
            }

            $timezone = new DateTimeZone('America/New_York');
            $date = new DateTime('now', $timezone);
            $formatted_date = $date->format('Y-m-d');
            $formatted_date_time = $date->format('Y-m-d H:i:s');

            if ($_POST['referral_id']) {
                $_POST['user_id'] = insertTable("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "first_name" => $_POST['first_name'], "last_name" => $_POST['last_name'], "mobile" => $_POST['mobile'], "user_type" => "Student", "reg_date" => $formatted_date, "activation_status" => "N", "referral_code" => $code, "reg_at" => "web", "referer_id" => $_POST['referral_id']));
            } else {
                $_POST['user_id'] = insertTable("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "first_name" => $_POST['first_name'], "last_name" => $_POST['last_name'], "mobile" => $_POST['mobile'], "user_type" => "Student", "reg_date" => $formatted_date, "activation_status" => "N", "referral_code" => $code, "reg_at" => "web"));
            }

            insertTable("login", array("user_id" => $_POST['user_id'], "last_login" => $formatted_date_time));
            insertTable("subscription", array("course_id" => $_POST['test_id'], "user_id" => $_POST['user_id'], "amount" => 0));

            //          *************  start sending email *************

            $setting = getDataByWhere("ocs_setting", "*", array("id" => 1));

            $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Register"));
            $body = html_entity_decode($mail['template']);
            $subject = $mail['subject'];
            $sendpassword = $_POST['password'];
            $mail_body = str_replace("{name}", $_POST['first_name'] . " " . $_POST['last_name'], $body);
            $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
            $mail_body = str_replace("{email}", $array['email'], $mail_body);
            $mail_body = str_replace("{password}", $sendpassword, $mail_body);
            $mail_body = html_entity_decode($mail_body);
            smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);

            //          *************  end sending email *************

            header("location:" . $configs['vpath'] . "thank-you?account=free");
        }
    } else {
        $data['error'] = $error;
    }
}

echo template($data);

?>