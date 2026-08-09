<?php
include("../configs/config.php");
include("../configs/license.php");
include("../configs/common.php");
include("../configs/array.php");

$setting = getDataByWhere("ocs_setting", "*", array("id" => 1));

if ($_SERVER['HTTP_HOST'] != "localhost") {
    include('../smtpmail.php');
}

if ($_POST['action']) {
    $array = array();
    switch ($_POST['action']) {

        case "get_subscriptions":

            $course_id = $_POST['course_id'] ?? '';
            $subscriptionOptions = "";

            $subscriptionResult = getQuery(
                "asvab_tutor_package_sessions",
                "session_id, title, price, is_most_popular",
                ["course_id" => $course_id, "payment_mode" => "subscription"],
                "ord"
            );

            if ($subscriptionResult && $subscriptionResult instanceof mysqli_result) {
                while ($row = mysqli_fetch_assoc($subscriptionResult)) {
                    $session_id = (int)$row['session_id'];
                    $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
                    $price = number_format((float)$row['price'], 2);
                    $selected = ($row['is_most_popular'] == "1") ? ' selected' : '';

                    $subscriptionOptions .= '<option value="' . $session_id . '"' . $selected . '>' . $title . ' ($' . $price . ')</option>';
                }
            }

            $array['msg'] = $subscriptionOptions;

            // header('Content-Type: application/json');
            // echo json_encode(['msg' => $subscriptionOptions]);
            // exit;
            break;

        case "login":

            // $exits = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y", "reg_at!" => "ASVAB Tutoring (app) app"));

            $is_tutor = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y", "user_type" => "Tutor"));

            $is_referrer = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y", "user_type" => "Referrer"));

            $exits = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password'])));

            if ($exits) {
                $user_id = getFldValueById("user", "user_id", array("email" => $_POST['email'], "password" => md5($_POST['password'])));
                $subscription_date = getFldValueById("subscription", "subscription_date", array("user_id" => $user_id));
            }

            $active_user = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y"));

            if ($is_tutor || $subscription_date || $active_user) {

                $user_type = getFldValueById("user", "user_type", array("email" => $_POST['email'], "password" => md5($_POST['password'])));

                if ($user_type == "Tutor") {

                    $d = getDataByJoin(array("user as u", "login as l"), "u.*,l.last_login", array("u.email" => $_POST['email'], "u.password" => md5($_POST['password'])), array("u.user_id" => "l.user_id"));

                    $_SESSION['user_id']      = $d["user_id"];
                    $_SESSION['status']       = $d["activation_status"];
                    $_SESSION['email']        = $d['email'];
                    $_SESSION['first_name']   = $d['first_name'];
                    $_SESSION['last_name']    = $d['last_name'];
                    $_SESSION['mobile']       = $d['mobile'];
                    $_SESSION['name']         = $d['first_name'] . " " . $d['last_name'];
                    $_SESSION['user_type']      = $d['user_type'];
                    $_SESSION['reg_date']      = $d['reg_date'];
                    $_SESSION['last_login']      = $d['last_login'];
                    $_SESSION['checking']     = "I'm tutor";

                    if (empty($d['referral_code'])) {
                        $flag = true;
                        $ref_code = number(9);
                        while ($flag) {
                            $code_exist = (int)getTotalRow("user", array("referral_code" => $ref_code));
                            if ($code_exist) {
                                $ref_code = number(9);
                            } else {
                                $flag = false;
                            }
                        }

                        updateTable("user", array("referral_code" => $ref_code), array("user_id" => $d['user_id']));
                    }

                    #update last login
                    updateTable("login", array("last_login" => date("Y-m-d H:i:s")), array("user_id" => $d['user_id']));

                    $array['msg'] = "";

                    if ($_POST['referer']) {
                        $array['url'] = $configs['vpath'] . "practice-test/" . $_POST['referer'];
                    } else {
                        $array['url'] = $configs['vpath'] . "practice-test/report-student-list";
                    }
                } else {

                    $d = getDataByJoin(array("user as u", "login as l"), "u.*,l.last_login", array("u.email" => $_POST['email'], "u.password" => md5($_POST['password'])), array("u.user_id" => "l.user_id"));

                    $course_id = getFldValueById("subscription", "course_id", array("user_id" => $d["user_id"]));

                    // If course_id is missing, try to recover it via the session_id in the subscription table
                    if (!$course_id) {
                        $session_id = getFldValueById("subscription", "session_id", array("user_id" => $d["user_id"]));
                        if ($session_id) {
                            $recovered_course_id = getFldValueById("asvab_tutor_package_sessions", "course_id", array("session_id" => $session_id));
                            if ($recovered_course_id) {
                                // Backfill the missing course_id into the subscription table
                                updateTable("subscription", array("course_id" => $recovered_course_id), array("user_id" => $d["user_id"]));
                                $course_id = $recovered_course_id;
                                $_SESSION['test_id']   = $course_id;
                            }
                        }
                    }

                    $expire_date = getFldValueById("subscription", "expire_date", array("user_id" => $d["user_id"]));
                    $course_exist = getTotalRow("course", array("course_id" => $course_id, "is_online" => "Y"));

                    if ($course_exist) {
                        $_SESSION['user_id']      = $d["user_id"];
                        $_SESSION['status']       = $d["activation_status"];
                        $_SESSION['email']        = $d['email'];
                        $_SESSION['first_name']   = $d['first_name'];
                        $_SESSION['last_name']    = $d['last_name'];
                        $_SESSION['mobile']       = $d['mobile'];
                        $_SESSION['name']         = $d['first_name'] . " " . $d['last_name'];
                        $_SESSION['user_type']      = $d['user_type'];
                        $_SESSION['reg_date']      = $d['reg_date'];
                        $_SESSION['last_login']      = $d['last_login'];
                        $_SESSION['test_id']      = $course_id;
                        $_SESSION['old_user_id']  = $d["old_user_id"];
                        $_SESSION['has_multi_courses'] = $active_user;

                        if ($course_id) {
                            $_SESSION['test_name'] = getFldValueById("course", "course_name", array("course_id" => $course_id));
                        }

                        if ($expire_date < date('Y-m-d')) {
                            updateTable("user", ["activation_status" => "N"], ["user_id" => $d["user_id"]]);
                            $_SESSION['status'] = "N";
                        }

                        if (empty($d['referral_code'])) {
                            $flag = true;
                            $ref_code = number(9);
                            while ($flag) {
                                $code_exist = (int)getTotalRow("user", array("referral_code" => $ref_code));
                                if ($code_exist) {
                                    $ref_code = number(9);
                                } else {
                                    $flag = false;
                                }
                            }

                            updateTable("user", array("referral_code" => $ref_code), array("user_id" => $d['user_id']));
                        }

                        #update last login
                        updateTable("login", array("last_login" => date("Y-m-d H:i:s")), array("user_id" => $d['user_id']));

                        $array['msg'] = "";

                        if ($_POST['referer']) {
                            $array['url'] = $configs['vpath'] . "practice-test/" . $_POST['referer'];
                        } else {
                            $array['url'] = $configs['vpath'] . "practice-test/" . "exam/" . $_SESSION['test_id'] . "-" . seo_url($_SESSION['test_name']);
                        }
                    } else {
                        $array['msg'] = "<p><img src='" . $configs['vpath'] . "img/off.png'> <font color=red>Apologies for the inconvenience! The course is currently undergoing updates. Please try again later.</font></p>";
                    }
                }
            } else if ($is_referrer) {
                $user = getDataByWhere("user", "*", ['email' => $_POST['email'], "password" => md5($_POST['password'])]);

                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['name'] = $user['first_name'] . " " . $user['last_name'];
                $_SESSION['reg_date'] = $user['reg_date'];

                $array['url'] = $configs['vpath'] . "affiliate-dashboard";
            } else {
                $user_exist = (int)getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "reg_at like " => "'%web%'"));

                if ($user_exist) {
                    $user_id = getFldValueById("user", "user_id", array("email" => $_POST['email'], "password" => md5($_POST['password'])));
                    $expire_date = getFldValueById("subscription", "expire_date", array("user_id" => $user_id));

                    if ($expire_date) {
                        $array['msg'] = "<p>
                                            <img src='" . $configs['vpath'] . "img/off.png' alt='inactive'> 
                                            <span style='color:red;'>
                                                Your account needs to be activated. To renew it, please 
                                                <a href='https://staging-test-prep.asvab-tutoring.com/contact-us' style='color:#007bff; text-decoration:underline;'>
                                                    contact us
                                                </a>.
                                            </span>
                                        </p>";
                    } else {
                        $array['msg'] = "<p>
                                            <img src='" . $configs['vpath'] . "img/off.png' alt='inactive'> 
                                            <span style='color:red;'>
                                                Your account needs to be activated. If it is delayed for more than 24 hours, please 
                                                <a href='https://staging-test-prep.asvab-tutoring.com/contact-us' style='color:#007bff; text-decoration:underline;'>contact us</a>.
                                            </span>
                                        </p>";
                    }
                } else {
                    $array['msg'] = "<p><img src='" . $configs['vpath'] . "img/off.png'> <font color=red>Sorry! Invalid Email and Password.</font></p>";
                }
            }

            break;

        case "tutor-info":

            $exist = getTotalRow("user", array("email" => $_POST['email']));

            if ($exist) {
                $array['msg'] = "<div style='color: white; background-color: red; padding: 10px; border-radius: 5px;'><strong>The email address you provided already exists. Please use a different email address.</strong></div>";
            } else if (isset($_POST['mobile']) && strlen($_POST['mobile']) > 10) {
                $array['msg'] = "<div style='color: white; background-color: red; padding: 10px; border-radius: 5px;'><strong>The given Mobile Number have to be less than 13 digits.</strong></div>";
            } else {
                $user_data_array = array("email" => $_POST['email'], "password" => md5($_POST["password"]), "first_name" => $_POST['fname'], "last_name" => $_POST['lname'], "mobile" => $_POST['mobile'], "reg_date" => date("Y-m-d"), "activation_status" => "N", "user_type" => "Tutor", "reg_at" => "web", "about" => $_POST['about'], "country_id" => $_POST['country_id']);

                $tutor_id = insertTable("user", $user_data_array, array("photo", "attachment"));

                $login_data_array = array("last_login" => date("Y-m-d H:i:s"), "user_id" => $tutor_id);

                $_POST['user_id'] = insertTable("login", $login_data_array);

                if ($tutor_id) {
                    $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Register"));

                    $body = html_entity_decode($mail['template']);
                    $subject = $mail['subject'];
                    $sendpassword = $_POST['password'];

                    //  $activation_code="<a href='" . $configs['vpath'] . "activation/" . $activation_code . "' target='_new'>Activate</a>";

                    $mail_body = str_replace("{name}", $_POST['fname'] . " " . $_POST['lname'], $body);
                    // $mail_body=str_replace("{activation_code}",$activation_code,$mail_body);
                    $mail_body = str_replace("{dotcom}", ucwords($dotcom), $mail_body);
                    $mail_body = str_replace("{email}", $_POST['email'], $mail_body);
                    $mail_body = str_replace("{password}", $sendpassword, $mail_body);
                    $mail_body = html_entity_decode($mail_body);

                    smtpmailer($_POST['email'], $setting['support_mail'], ucwords($dotcom), $subject, $mail_body);

                    $array['msg'] = str_replace("{table}", "Tutor ", $configs['success_msg']);
                    $array['next'] = $configs['vpath'] . "tutor-reg-success";
                } else {
                    $array['msg'] = $configs['error_msg'];
                }
            }
            break;

        case "user_feedback":
            $r = insertTable("ocs_feedback", array("course_id" => $_POST['course_id'], "feedback" => $_POST['feedback'], "rating" => $_POST['rating'], "name" => $_POST['name'], "email" => $_POST['email'], "date" => date("Y-m-d"), "status" => "N"));

            if ($r) {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/on.png"> Your feedback has been submitted successfully.<br>';
                $array['isreset'] = true;
            } else {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/off.png"> Your feedback has not submitted successfully, kindly try the process again.<br>';
            }
            break;

        case "affiliate-login":

            $exist = getTotalRow("user", ['email' => $_POST['email'], "password" => md5($_POST['password'])]);

            if ($exist) {
                $user = getDataByWhere("user", "*", ['email' => $_POST['email'], "password" => md5($_POST['password'])]);

                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['name'] = $user['first_name'] . " " . $user['last_name'];
                $_SESSION['reg_date'] = $user['reg_date'];

                $array['url'] = $configs['vpath'] . "affiliate-dashboard";
            } else {
                $array['msg'] = '<font color=red>Invalid email or password.</font>';
            }

            break;

        case "affiliate-register":

            if ($_POST['type'] == "Referrer") {

                $error = "";

                $exist_email = getTotalRow("user", ["email" => $_POST['email']]);

                if ($exist_email) {
                    $error = "<p style='background:#fff; font-weight:600; color:red; padding:10px;'>
                                Already an ASVAB tutoring member. Please login with correct credentials.
                            </p>";
                }

                // if ($exist_email) {
                //     $error = "<p style='background: #fff; font-weight: 600; color: red; padding: 10px;'>This email address already exists. Please use a different email</p>";
                // }
                // if ($exist_mobile) {
                //     $error = "<p style='background: #fff; font-weight: 600; color: red; padding: 10px;'>This mobile number already exists. Please use a different number</p>";
                // }

                if (empty($_POST['terms'])) {
                    $error = "<p style='background:#fff; font-weight:600; color:red; padding:10px;'>Please accept the Terms and Conditions.</p>";
                }

                if (!$error) {
                    $_POST['reg_date'] = date("Y-m-d");
                    $_POST['user_type'] = "Referrer";
                    $_POST['activation_status'] = "Y";
                    $_POST['password'] = md5($_POST['password']);
                    $_POST['referrer'] = "Y";

                    $flag = true;
                    $_POST['code'] = number(8);
                    while ($flag) {
                        $code_exist = (int)getTotalRow("user", array("referral_code" => $_POST['code']));
                        if ($code_exist) {
                            $_POST['code'] = number(8);
                        } else {
                            $flag = false;
                        }
                    }

                    if ($_POST['code']) {
                        $user_id = insertTable("user", array("email" => $_POST['email'], "password" => $_POST['password'], "first_name" => $_POST['name'], "mobile" => $_POST['mobile'], "user_type" => "Referrer", "reg_date" => date("Y-m-d"), "activation_status" => "Y", "referral_code" => $_POST['code']));

                        $array['msg'] = "";

                        $array['list'] = "<h5 style='color:#fff'>Congratulation! <br>You are successfully registered as an affiliate member. Your Referral Code : " . $_POST['code'] . ". Please check email and get affiliate dashboard credentials. Thanks Asvab Team.</h5>";

                        $mail = getDataByWhere("ocs_mailsetup", "*", array("mail_type" => "Become an Affiliate"));

                        $body = html_entity_decode($mail['template']);
                        $subject = $mail['subject'];

                        $mail_body = str_replace("{name}", $_POST['name'], $body);
                        $mail_body = str_replace("{password}", $_POST['password'], $mail_body);
                        $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
                        $mail_body = str_replace("{email}", $_POST['email'], $mail_body);
                        $mail_body = html_entity_decode($mail_body);

                        if ($_SERVER['HTTP_HOST'] != "localhost") {
                            smtpmailer($_POST['email'], $configs['admin_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
                        }

                        // $array['url'] = $configs['vpath'] . "affiliate-login";

                        if ($user_id) {
                            $user = getDataByWhere("user", "*", array('user_id' => $user_id));

                            $_SESSION['user_type'] = $user['user_type'];
                            $_SESSION['user_id'] = $user['user_id'];
                            $_SESSION['email'] = $user['email'];
                            $_SESSION['name'] = $user['first_name'] . " " . $user['last_name'];
                            $_SESSION['reg_date'] = $user['reg_date'];

                            $array['url'] = $configs['vpath'] . "affiliate-dashboard";
                        }
                    }
                }

                $array['msg'] = $error;
            }

            break;

        case "get_subcription_option":
            $option = "<option value=''>Select Subscription</option>";

            // Ensure test_id is valid
            $test_id = isset($_POST['test_id']) ? (int)$_POST['test_id'] : 0;

            if ($test_id > 0) {
                $list = getQuery("package", "*", array("course_id" => $test_id));

                if (!empty($list)) {
                    foreach ($list as $l) {
                        $option .= "<option value='" . $l["package_id"] . "'>"
                            . $l['duration'] . " subscription ($" . $l['price'] . ")</option>";
                    }
                } else {
                    $option .= "<option value=''>No subscriptions available</option>";
                }
            }

            $array = ['msg' => $option];

            header('Content-Type: application/json');  // Set correct response type
            echo json_encode($array);
            flush();
            exit;  // Ensure script stops here
            break;

        case "get_course_option":
            $option = "<option value=''>Select Course</option>";

            // Ensure groupId is valid
            $groupId = isset($_POST['groupId']) ? (int)$_POST['groupId'] : 0;

            if ($groupId > 0) {
                $list = getQuery("course", "*", array("group_id" => $groupId), "ord");

                if (!empty($list)) {
                    foreach ($list as $l) {
                        $option .= "<option value='" . $l["course_id"] . "'>" . $l['course_name'] . "</option>";
                    }
                } else {
                    $option .= "<option value=''>No courses available</option>";
                }
            }

            $array = ['msg' => $option];

            header('Content-Type: application/json');  // Set correct response type
            echo json_encode($array);
            flush();
            exit;  // Ensure script stops here
            break;

        case "resendVarEmail":

            $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Email Verification"));

            $body = html_entity_decode($mail['template']);
            $subject = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail['subject']);

            $mail_body = str_replace("{name}", "", $body);
            $mail_body = str_replace("{var_code}", $_POST['new_code'], $mail_body);
            $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
            $mail_body = html_entity_decode($mail_body);

            if ($_SERVER['HTTP_HOST'] != "localhost") {
                smtpmailer($_POST['email'], $configs['admin_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
            }

            // $response = ['msg' => 'Verification code resent successfully. Please check your email. New Code: '.$_POST['new_code']];

            $response = ['msg' => 'Verification code resent successfully. Please check your email.'];

            echo json_encode($response);
            exit;

            break;

        case "checkUserRegInput":

            $error = "";
            $exist_email = getTotalRow("user", array("email" => $_POST['email']));
            //        $exist_mobile = getTotalRow("user",array("mobile"=>$_POST['mobile']));

            //        if($_POST['referral_id'])
            //        {
            //            $valid_referral=getTotalRow("user",array("referral_code"=>$_POST['referral_id']));
            //
            //            if(!$valid_referral)
            //            {
            //                $error="Invalid Referral Code!";
            //                $_POST['referral_id']="";
            //            }
            //        }

            if (empty($_POST['course'])) {
                $error = "Please, Select a Course First!";
            } else if (empty($_POST['fname'])) {
                $error = "Please enter your first name.";
            } elseif (empty($_POST['lname'])) {
                $error = "Please enter your last name.";
            } else if (empty($_POST['email'])) {
                $error = "Please provide an email address.";
            } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid Email address!";
            } else if ($exist_email) {
                $error = "The email exists. Use different email!";
            } else if (empty($_POST['password'])) {
                $error = "Please provide a password.";
            } else if ($_POST['password'] != $_POST['re_password']) {
                $error = "Password & Repeat password does not match!";
            } elseif (empty($_POST['mobile'])) {
                $error = "Please enter your mobile number.";
            }

            if ($error) {
                $flag = false;
            } else {
                $flag = true;

                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Email Verification"));

                $body = html_entity_decode($mail['template']);
                $subject = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail['subject']);

                $mail_body = str_replace("{name}", "", $body);
                $mail_body = str_replace("{var_code}", $_POST['var_code'], $mail_body);
                $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
                $mail_body = html_entity_decode($mail_body);

                if ($_SERVER['HTTP_HOST'] != "localhost") {
                    smtpmailer($_POST['email'], $configs['admin_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
                }
            }

            $response = ['msg' => $error, 'flag' => $flag];
            echo json_encode($response);
            exit; // Ensure that no other output interferes with the JSON response

            break;

        case "get_city_option":

            $option = "<option value='0'>Select City</option>";
            $list = getQuery("city", "*", array("state_id" => (int)$_POST['state_id']), "name");

            foreach ($list as $l) {
                $option .= "<option value='" . $l['id'] . "'>" . $l['name'] . "</option>";
            }
            $array['list'] = $option;

            $array['msg'] = "";
            echo json_encode($array);
            flush();
            break;

        case "edit-profile":
            $_POST['country_id'] = (int)$_POST['country_id'];
            $_POST['state_id'] = (int)$_POST['state_id'];
            $_POST['city_id'] = (int)$_POST['city_id'];
            $_POST['zip_code'] = (int)$_POST['zip_code'];
            $r = updateTable("user", $_POST, array("user_id" => $_SESSION['user_id']));
            if ($r) {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/on.png"> Your profile has been successfully updated.';
            } else {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/off.png"> The profile update was not successful, kindly try the process again.';
            }
            break;

        case "update_profile":

            $r = updateTable("user", $_POST, array("user_id" => $_SESSION['user_id']));
            if ($r) {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/on.png"> Your profile has been successfully updated.';
            } else {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/off.png"> The profile update was not successful, kindly try the process again.';
            }
            break;

        case "update_payment_info":

            $r = updateTable("user", $_POST, array("user_id" => $_SESSION['user_id']));
            if ($r) {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/on.png"> Your profile has been successfully updated.';
            } else {
                $array['msg'] = '<img src="' . $configs['vpath'] . 'img/off.png"> The profile update was not successful, kindly try the process again.';
            }
            break;

        // case "forgot-password":
        //     $exists = getTotalRow("user", array("email" => $_POST['email']));

        //     if ($exists) {
        //         $d = getDataByWhere("user", "*", array("email" => $_POST['email']));

        //         $devider = date("Ymd");
        //         $password_link = $configs['vpath'] . "create-password/" . ($d['user_id'] * $devider);

        //         //updateTable("login",array("password"=>md5($new_password)),array("user_id"=>$d['user_id']));
        //         $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Forgot Password"));
        //         $body = html_entity_decode($mail['template']);
        //         $subject = $mail['subject'];
        //         $mail_body = str_replace("{name}", $name, $body);
        //         $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
        //         $mail_body = str_replace("{email}", $_POST['email1'], $mail_body);
        //         $mail_body = str_replace("{password_link}", $password_link, $mail_body);
        //         $mail_body = html_entity_decode($mail_body);
        //         if ($_SERVER['HTTP_HOST'] != "localhost") {
        //             smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
        //         }
        //         $array['success'] = "Please check your email to reset password.";
        //     } else {
        //         $array['error'] = "<font color=red>Invalid Email Id!</font>";
        //     }
        //     break;

        case "create-password":

            if ($_POST['password'] != $_POST['conf_password']) {
                $array['error'] = "<font color=red><img src='" . $configs['vpath'] . "img/off.png'> Password and confirm password does not match!</font>";
                $array['success'] = '';
            } else {
                $devider = date("Ymd");
                $user_id = substr($_POST['user_id'], 2, -2);

                $exist = getTotalRow("user", array("user_id" => $user_id));
                if ($exist) {
                    updateTable("user", array("password" => md5($_POST['password'])), array("user_id" => $user_id));
                    $array['success'] = "<img src='" . $configs['vpath'] . "img/on.png'> Your new password has been updated successfully.<br>";
                } else {
                    $array['error'] = "<font color=red><img src='" . $configs['vpath'] . "img/off.png'> Invalid  Id.</font>";
                    $array['success'] = '';
                }
            }
            break;

        case "get-tutor":

            $_POST['created_at'] = date("Y-m-d H:i:s");
            $_POST['status'] = "N";

            $course_name = getFldValueById("course", "course_name", array("course_id" => (int)$_POST['course_id']));

            $e_id = insertTable("get_tutor_msg", $_POST);

            if ($e_id) {
                $array['msg'] = "<img src='" . $configs['vpath'] . "img/on.png'> You request for tutor has been submitted successfully. Our team will contact you soon with in 24 hrs.";
                $array['isreset'] = true;
            } else {
                $array['msg'] = "<img src='" . $configs['vpath'] . "img/off.png'> Oops! Message not sent. If the issue persists, contact us.";
                $array['isreset'] = false;
            }

            unset($_POST['action'], $_POST['status']);

            $mailbody = "<html><body><table width=100%  style='background:#fafafa; width:650px;margin:0;' cellpadding='4' cellspacing='1' border='1' class='table'>";
            $mailbody .= "<tr><td>Source</td><td>Test Prep Web</td></tr>";
            foreach ($_POST as $p => $v) {
                if ($p == "course_id") {
                    $p = "course name";
                    $v = $course_name;
                }
                $mailbody .= "<tr><td>" . Ucwords($p) . "</td><td>" . $v . "</td></tr>";
            }
            $mailbody .= "</table></body></html>";

            if ($_SERVER['HTTP_HOST'] != "localhost") {
                smtpmailer("info@asvab-tutoring.com", $_POST['email'], $_POST['first_name'], "One-On-One Tutor Request from " . $_POST['first_name'], $mailbody);
            }


            //           $body = '
            //<html>
            //<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
            //    <p>Hi {name},</p>
            //    <p>Thank you for your registration.</p>
            //    <p><strong>Login URL:</strong> <a href="https://staging-test-prep.asvab-tutoring.com" style="color: #0073e6; text-decoration: none;">https://staging-test-prep.asvab-tutoring.com</a></p>
            //    <p><strong>Your Login ID:</strong> {email}</p>
            //    <p><strong>Your Password:</strong> {password}</p>
            //    <p>Thanks,</p>
            //    <p>The ASVAB Tutoring Team</p>
            //
            //    <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; text-align: center;">
            //        <p style="color: #ff6600; font-weight: bold;">🌟 Unlock Your Potential with ASVAB Tutoring! 🌟</p>
            //        <p><strong>Exclusive Tutoring Services Just for You!</strong></p>
            //        <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
            //            <li>✅ One-on-One Sessions: Personalized coaching tailored to your needs.</li>
            //            <li>✅ Comprehensive Study Materials: Access the best resources and guides.</li>
            //            <li>✅ Expert Tutors: Learn from the best in the field.</li>
            //        </ul>
            //        <p style="color: #ff6600; font-weight: bold;">🎓 Achieve Your Goals with Us!</p>
            //        <p><a href="https://staging-test-prep.asvab-tutoring.com/facilities" style="display: inline-block; padding: 10px 20px; margin-top: 10px; font-size: 16px; color: #fff; background-color: #0073e6; text-decoration: none; border-radius: 5px;">Explore Our Tutoring Facilities Now!</a></p>
            //    </div>
            //
            //    <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; text-align: center;">
            //        <p style="color: #ff6600; font-weight: bold;">📚 Expand Your Knowledge with Our Additional Courses! 📚</p>
            //        <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
            //            <li>🔹 <strong>SAT Preparation:</strong> Get the best scores with our expert guidance.</li>
            //            <li>🔹 <strong>ACT Preparation:</strong> Comprehensive resources and tutoring to excel.</li>
            //            <li>🔹 <strong>GED Preparation:</strong> Tailored programs to help you succeed.</li>
            //            <li>🔹 <strong>TOEFL Preparation:</strong> Improve your English proficiency with our courses.</li>
            //        </ul>
            //        <p><a href="https://staging-test-prep.asvab-tutoring.com/courses" style="display: inline-block; padding: 10px 20px; margin-top: 10px; font-size: 16px; color: #fff; background-color: #0073e6; text-decoration: none; border-radius: 5px;">Discover All Our Courses Here!</a></p>
            //    </div>
            //
            //    <p>If you have any questions or need further assistance, feel free to reach out to our support team.</p>
            //</body>
            //</html>
            //';

            // Send confirmation email to user 
            $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "one on one tutor"));

            $body = $mail['template'];
            $body = str_replace("{name}", $_POST['first_name'] . " " . $_POST['last_name'], $body);
            $body = str_replace("{tutoring_type}", $_POST['tutoring_type'], $body);
            $body = str_replace("{course}", $course_name, $body);
            $body = html_entity_decode(html_entity_decode($body));

            $subject = $mail['subject'];

            smtpmailer($_POST['email'], "info@asvab-tutoring.com", $_POST['first_name'], $subject, $body);

            break;

        case "contact":

            $_POST['date'] = date("Y-m-d H:i:s");
            $_POST['status'] = "N";
            if ((int)$_POST['test_id']) {
                $course_name = getFldValueById("course", "course_name", array("course_id" => (int)$_POST['test_id']));
                $add_course = "<tr><td>Course Name</td><td>" . $course_name . "</td></tr>";
            } else {
                $add_course = "";
            }
            if ($_SESSION['user_id']) {
                $_POST['user_id'] = $_SESSION['user_id'];
            } else {
                $_POST['user_id'] = "";
            }
            $e_id = insertTable("enquiry", array("user_id" => (int)$_POST['user_id'], "first_name" => $_POST['first_name'], "last_name" => $_POST['last_name'], "mobile" => $_POST['mobile'], "subject" => $_POST['subject'], "message" => $_POST['message'], "date" => $_POST['date'], "status" => $_POST['status'], "email" => $_POST['email'], "reg_at" => "Test Prep Web", "test_id" => (int)$_POST['test_id']));

            if ($e_id) {
                $array['msg'] = "<p align=center><h5>You have successfully submitted enquiry. Our team will contact you soon with in 24 hrs.<br><br>Thank You<br>Asvab-Tutor.com Team</h5></p>";
            } else {
                $array['msg'] = "<p align=center><h5>Oops! Message not sent. If the issue persists, contact us. Thanks for your patience.<br><br>From<br>Asvab-Tutor.com Team</h5></p>";
            }

            $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Contact Us"));

            $body = html_entity_decode($mail['template']);
            $subject = $mail['subject'];

            $mail_body = str_replace("{name}", $_POST['first_name'] . " " . $_POST['last_name'], $body);
            $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
            $mail_body = html_entity_decode($mail_body);

            unset($_POST['action'], $_POST['status'], $_POST['test_id']);

            $mailbody = "<html><body><table width=100%  style='background:#fafafa; width:650px;margin:0;' cellpadding='4' cellspacing='1' border='1' class='table'>";
            $mailbody .= "<tr><td>Source</td><td>Test Prep Web</td></tr>";
            $mailbody .= $add_course;
            foreach ($_POST as $p => $v) {
                $mailbody .= "<tr><td>" . Ucwords($p) . "</td><td>" . $v . "</td></tr>";
            }
            $mailbody .= "</table></html></body>";

            if ($_SERVER['HTTP_HOST'] != "localhost") {
                smtpmailer($_POST['email'], $configs['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
                smtpmailer("info@asvab-tutoring.com", $_POST['email'], $_POST['first_name'], "New Contact from " . $_POST['first_name'], $mailbody);
            }
            break;

        case "review":

            $exit = getTotalRow("ocs_blog_review", array("email" => $_POST['email'], "blog_id" => $_POST['blog_id']));


            if (!$exit) {

                insertTable("ocs_blog_review", array("name" => $_POST['name'], "email" => $_POST['email'], "user_id" => (int)$_POST['user_id'], "blog_id" => $_POST['blog_id'], "rate" => $_POST['rate'], "review" => $_POST['review'], "status" => "N", "date" => date("Y-m-d")));


                /*	$array['msg']="<font color=green>Review added successfully!</font>";
		    	$array['list']="";*/

                $array['msg'] = "<font color=green>Review added successfully!</font>";

                //$array['msg']="<p align=center><h5>Review added successfully!</h5></p>";


            } else {

                $array['msg'] = "<font color=red>You have already submite review for this Blog before!</font>";
                $array['list'] = "";
            }


            break;

        case "get_subscriptions_testprep":


            $course_id = $_POST['course_id'] ?? '';
            $subscriptionOptions = "";

            $subscriptionResult = getQuery(
                "asvab_tutor_package_sessions",
                "session_id, title, price, is_most_popular",
                ["course_id" => $course_id, "payment_mode" => "subscription"],
                "ord"
            );

            if ($subscriptionResult && $subscriptionResult instanceof mysqli_result) {
                while ($row = mysqli_fetch_assoc($subscriptionResult)) {
                    $session_id = (int)$row['session_id'];
                    $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
                    $price = number_format((float)$row['price'], 2);
                    $selected = ($row['is_most_popular'] == "1") ? ' selected' : '';

                    $subscriptionOptions .= '<option value="' . $session_id . '"' . $selected . '>' . $title . ' ($' . $price . ')</option>';
                }
            }

            header('Content-Type: application/json');
            echo json_encode(['msg' => $subscriptionOptions]);
            exit;
            break;

        case "checkUserExist":



            $error = "";
            $flag = true;

            $email = isset($_POST['email']) ? trim($_POST['email']) : '';

            if ($email == '') {
                $error = "Email is required.";
                $flag = false;
            } else {
                $exist_email = getTotalRow("user", array("email" => $email));

                if ($exist_email > 0) {
                    $error = "The email already exists. Use a different email.";
                    $flag = false;
                }
            }

            $response = array(
                'msg' => $error,
                'flag' => $flag
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;

            break;
    }
    echo json_encode($array);
    flush();
}
