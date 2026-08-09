<?
// Live Apps: HESI A2, CNA, TEAS

header('Content-Type: application/json');
include("configs/config.php");
include("configs/common.php");
include("configs/license.php");
include("configs/array.php");
include("smtpmail.php");

$option_no = 4;

$setting = getDataByWhere("setting", "*", array("id" => 1));

$configs = array_merge($configs, $setting);

$response = array();

if ($_POST['action']) {
    switch ($_POST['action']) {
        case "pre-reg-info":

            $courses = getQuery("course", "*", array(), "ord");
            $course_list = [];
            foreach ($courses as $c) {
                $course_list[] = ["course_id" => $c['course_id'], "course_name" => $c['course_name']];
            }

            $response["course_list"] = $course_list;

            break;

        case "register":

            $n = getTotalRow("user", array("email" => $_POST['email']));
            if ($_POST['password'] != $_POST['confirm_password']) {
                $response["status"] = 201;
                $response["isSuccess"] = "false";
                $response["message"] = "Password & Confirm password doesn't match!";
            } else if ($n) {
                $response["status"] = 201;
                $response["isSuccess"] = "false";
                $response["message"] = "This email id already exits!";
            } else {
                //		     	$activation_code=time();
                $flag = true;
                $code = ref_code(8);
                while ($flag) {
                    $code_exist = getFldValueById("user", "referral_code", array("referral_code" => $code));
                    if ((int)$code_exist) {
                        $code = ref_code(8);
                    } else {
                        $flag = false;
                    }
                }

                $timezone = new DateTimeZone('America/New_York');
                $date = new DateTime('now', $timezone);
                $formatted_date = $date->format('Y-m-d');
                $formatted_date_time = $date->format('Y-m-d H:i:s');

                $course_name = getFldValueById("course", "course_name", array("course_id" => $_POST['course_id']));
                $user_id = insertTable("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "first_name" => $_POST['first_name'], "last_name" => $_POST['last_name'], "mobile" => $_POST['mobile'], "user_type" => "Student", "reg_date" => $formatted_date, "activation_status" => "N", "referral_code" => $code, "reg_at" => $course_name . " app"));

                if ($user_id) {
                    insertTable("login", array("user_id" => $user_id, "last_login" => $formatted_date_time));
                    insertTable("subscription", array("course_id" => $_POST['course_id'], "user_id" => $user_id));

                    $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Register"));

                    $body = html_entity_decode($mail['template']);
                    $subject = $mail['subject'];
                    $sendpassword = $_POST['password'];

                    $mail_body = str_replace("{name}", $_POST['first_name'] . " " . $_POST['last_name'], $body);
                    $mail_body = str_replace("{activation_code}", $activation_code, $mail_body);
                    $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
                    $mail_body = str_replace("{email}", $_POST['email'], $mail_body);
                    $mail_body = str_replace("{password}", $sendpassword, $mail_body);
                    $mail_body = html_entity_decode($mail_body);

                    smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);

                    $response['status'] = 200;

                    $response["isSuccess"] = true;
                    $authorizeToken = getToken();
                    $response["userDetails"] = array(
                        "userId" => $user_id,
                        "authorizationToken" => $authorizeToken,
                        "userName" => $_POST['email'],
                        "mobile" => $_POST['mobile'],
                        "name" => $_POST['first_name'] . " " . $_POST['last_name']
                    );

                    updateTable("login", array("auth_token" => $authorizeToken), array("user_id" => $user_id));

                    $response["message"] = "You are successfully registered with us. Please check your mail.";
                } else {
                    $response["message"] = "Some Error! Try Later.";
                }
            }

            break;

        case "login":

            $exits = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status!" => "D"));

            if ($exits) {
                $token = getToken();

                $d = getDataByJoin(array("user as u", "login as l"), "u.*,l.last_login", array("u.email" => $_POST['email'], "u.password" => md5($_POST['password'])), array("u.user_id" => "l.user_id"));

                $course_id = getFldValueById("subscription", "course_id", array("user_id" => $d["user_id"]));
                $expire_date = getFldValueById("subscription", "expire_date", array("user_id" => $d["user_id"]));

                updateTable("login", array("auth_token" => $token, "last_login" => date("Y-m-d H:i:s")), array("user_id" => $d["user_id"]));

                if ($course_id) {
                    $course_name = getFldValueById("course", "course_name", array("course_id" => $course_id));
                }

                if ($expire_date < date('Y-m-d')) {
                    updateTable("user", ["activation_status" => "N"], ["user_id" => $d["user_id"]]);
                }

                if ($d['activation_status'] == 'Y') {
                    $d['paid'] = true;
                } else {
                    $d['paid'] = false;
                }

                $response["userDetails"] = array(
                    "userId" => $d["user_id"],
                    "authorizationToken" => $token,
                    "userName" => $d['email'],
                    "name" => $d['first_name'] . " " . $d['last_name'],
                    "email" => $d['email'],
                    "mobile" => $d['mobile'],
                    "course_id" => $course_id,
                    "isUserSubscribed" => $d['paid']
                );
                $response["status"] = 200;
                $response["isSuccess"] = true;
                $response["message"] = "Login Success";
            } else {
                $response["status"] = 201;
                $response["isSuccess"] = "false";
                $response["message"] = "Login faild";
            }

            break;

        case "delete-account":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));

                $user_id = $user['user_id'];
                $name = $user['first_name'] . " " . $user['last_name'];
                $token = getToken();

                updateTable("user", ["activation_status" => "D"], ["user_id" => $user_id]);
                updateTable("login", ["auth_token" => $token], ["user_id" => $user_id]);

                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Delete Account"));
                $body = html_entity_decode($mail['template']);
                $subject = $mail['subject'];
                $mail_body = str_replace("{name}", $name, $body);
                $mail_body = html_entity_decode($mail_body);
                if ($_SERVER['HTTP_HOST'] != "localhost") {
                    smtpmailer($user['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
                }

                $response["status"] = 200;
                $response["isSuccess"] = true;
                $response["message"] = "Account Deleted Successfully";
            } else {
                $response["status"] = 201;
                $response["isSuccess"] = "false";
                $response["message"] = "Login faild";
            }

            break;

        case "forgot-password":

            $exist = getTotalRow("user", array("email" => $_POST['email']));

            if ($exist) {
                $d = getDataByWhere("user", "*", array("email" => $_POST['email']));

                $devider = date("Ymd");
                // $password_link = $configs['vpath'] . "create-password/" . ($d['user_id'] * $devider);
                $password_link = $configs['vpath'] . "create-password/21" . $d['user_id'] . "45";
                $password_link = str_replace('/practice-test', '', $password_link);

                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Forgot Password"));

                $body = html_entity_decode($mail['template']);
                $subject = $mail['subject'];

                $mail_body = str_replace("{name}", $name, $body);
                $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
                $mail_body = str_replace("{email}", $_POST['email'], $mail_body);
                $mail_body = str_replace("{password_link}", $password_link, $mail_body);
                $mail_body = html_entity_decode($mail_body);
                if ($_SERVER['HTTP_HOST'] != "localhost") {
                    smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);

                    $response['message'] = "Please check your email to reset password.";
                    $response['password_link'] = $password_link;
                }
            } else {
                $response['message'] = "Invalid Email ID.";
            }

            break;

        case "forgot-password-send-code":

            $exist = getTotalRow("user", array("email" => $_POST['email']));

            if ($exist) {
                $d = getDataByWhere("user", "*", array("email" => $_POST['email']));

                $response['user_id'] = $d['user_id'];

                $max_length = 6;
                $devider = date("Ymd");
                $password_code = ($d['user_id'] * $devider) % pow(10, $max_length);

                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Password Reset"));
                $body = html_entity_decode($mail['template']);
                $subject = $mail['subject'];
                $mail_body = str_replace("{name}", $name, $body);
                $mail_body = str_replace("{verification_code}", $password_code, $mail_body);
                $mail_body = html_entity_decode($mail_body);
                if ($_SERVER['HTTP_HOST'] != "localhost") {
                    smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);

                    $response['message'] = "Please check your email to reset password.";
                    $response['password_code'] = $password_code;
                }
            } else {
                $response['message'] = "Invalid Email ID.";
            }

            break;

        case "forgot-password-reset-pass":

            if ($_POST['new_password'] == $_POST['conf_password']) {

                $user_id = (int)$_POST['user_id'];
                $max_length = 6;
                $devider = date("Ymd");
                $password_code = ($user_id * $devider) % pow(10, $max_length);

                if ((int)$_POST['password_code'] == $password_code) {

                    $r1 = updateTable("user", array("password" => md5($_POST['new_password'])), array("user_id" => $user_id));

                    if ($r1) {

                        $user = getDataByWhere("user", "*", array("user_id" => $user_id));

                        $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Send Password"));

                        $body = html_entity_decode($mail['template']);
                        $subject = $mail['subject'];
                        $mail_body = str_replace("{name}", $user['first_name'] . " " . $user['last_name'], $body);
                        $mail_body = str_replace("{email}", $user['email'], $mail_body);
                        $mail_body = str_replace("{password}", $_POST['new_password'], $mail_body);
                        $mail_body = html_entity_decode($mail_body);

                        smtpmailer($user['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);

                        $response['message'] = "Password has been successfully changed.";
                    } else {

                        $response['message'] = "Error! Please try again later.";
                    }
                } else {
                    $response['message'] = "Please enter the correct varification code!";
                }
            } else {
                $response['message'] = "Confirm password does not match with new password!";
            }

            break;

        case "category":

            $list = array();

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                if ($test_id) {
                    $icon = "uploads/test.png";

                    $cat_list = getQuery("category", "*", array("course_id" => $test_id, "online" => "Y"), "category_order");
                    foreach ($cat_list as $c) {
                        $list[] = ["id" => $c['cat_id'], "name" => $c['category_name'], "icon" => $configs['vpath'] . $icon];
                    }

                    $predic_list = getQuery("predic_test", "*", array("course_id" => $test_id, "online" => "Y"), "test_order");
                    foreach ($predic_list as $p) {
                        $list[] = ["id" => $p['Predic_test_id'], "name" => $p['test_name'], "icon" => $configs['vpath'] . $icon];
                    }
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            $response['list'] = $list;

            break;

        case "lessons":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));

                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $user_id = $user['user_id'];
                $paid_member = $user['activation_status'];

                $lessons = getQuery("lesson", "*", array("cat_id" => $_POST['cat_id'], "online" => "Y"), "lesson_order");

                $list = array();
                foreach ($lessons as $c) {
                    if (!$c['icon']) {
                        $c['icon'] = $configs['vpath'] . "uploads/test.png";
                    }
                    if ($paid_member == 'Y' || $c['free'] == 'Y') {
                        $c['paid'] = 0;
                    } else {
                        $c['paid'] = 1;
                    }
                    $list[] = array("lesson_id" => $c['lesson_id'], "lesson_name" => $c['Lesson_name'], "icon" => $c['icon'], "paid" => (int)$c['paid'], "lesson_order" => (int)$c['lesson_order']);
                }
                $response['list'] = $list;
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "all-test":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $paid_member = $user['activation_status'];

                if ($test_id) {
                    $icon = "uploads/test.png";

                    $list = array();

                    $cat_list = getQuery("category", "*", array("course_id" => $test_id, "online" => "Y"), "category_order");

                    foreach ($cat_list as $c) {

                        if ($c['category_name'] == "Flashcards") {
                            $list[$c['category_name']] = array("is_free"=>$c['free'],"flashcard_id" => $c['cat_id'], "test_only" => 1, "icon" => $configs['vpath'] . "uploads/test.png", "action" => "knowledge-update");
                        } else {
                            $list[$c['category_name']] = [];
                            $lessons = getQuery("lesson", "*", array("cat_id" => $c['cat_id'], "online" => "Y"), "lesson_order");

                            foreach ($lessons as $l) {
                                if (!$c['icon']) {
                                    $l['icon'] = $configs['vpath'] . "uploads/test.png";
                                }
                                if ($paid_member == 'Y') {
                                    $l['paid'] = 0;
                                } else {
                                    $l['paid'] = 1;
                                }

                                $list[$c['category_name']][] = array("is_free"=>$l['free'],"ord" => $l['lesson_order'], "cat_id" => $l['cat_id'], "test_only" => 1, "lesson_id" => $l['lesson_id'], "name" => $l['Lesson_name'], "icon" => $l['icon'], "action" => "practice-test-questions");
                            }
                        }
                    }

                    $predic_list = getQuery("predic_test", "*", array("course_id" => $test_id, "online" => "Y"), "test_order");
                    foreach ($predic_list as $p) {

                        $list[$p['test_name']] = ["is_free"=>$p['free'],"id" => $p['Predic_test_id'], "name" => $p['test_name'], "icon" => $configs['vpath'] . $icon];
                    }

                    $response = $list;
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "lesson-view":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $paid_member = $user['activation_status'];

                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $lesson = getDataByWhere("lesson", "*", array("lesson_id" => $_POST['lesson_id']));
                $alert = 0;
                if ($lesson['free'] == 'N') {
                    if (!$paid_member == 'Y') {
                        $alert = 1;
                    }
                }
                if (!$alert) {
                    //                   ***************** Lesson Information *****************
                    //                    $response['lesson']=array("cat_id"=>$lesson['cat_id'],"lesson_id"=>$lesson['lesson_id'],"lesson_name"=>$lesson['Lesson_name'],"overview"=>$configs['vpath'] ."lesson-overview/".$_POST['lesson_id'],"free"=>$lesson['free']);
                    $response['lesson'] = array("cat_id" => $lesson['cat_id'], "lesson_id" => $lesson['lesson_id'], "lesson_name" => $lesson['Lesson_name'], "overview" => "");

                    $response['practice_test_total'] = getFldValueById("category", "num_of_ques_pt", array("cat_id" => $lesson['cat_id']));

                    $response['homework_total'] = getTotalRow("lesson_questions", array("cat_id" => $lesson['cat_id'], "lesson_id" => $lesson['lesson_id'], "course_id" => $test_id, "HW" => 1));

                    //                    ***************** Lesson Youtube Video *****************

                    $is_video_exist = getTotalRow("file", array("file_type" => "v", "lesson_id" => $_POST['lesson_id']));
                    if ($is_video_exist) {

                        $videos = getQuery("file", "*", array("file_type" => "v", "lesson_id" => $_POST['lesson_id']), 'pos');

                        foreach ($videos as $v) {

                            $file = $v['file'];

                            if (substr_count($file, "youtu.be")) {
                                $e = end(explode("/", $file));
                            } else {
                                $e1 = explode("?v=", $file);
                                $e = $e1[1];
                            }
                            $picture = "http://img.youtube.com/vi/" . $e . "/mqdefault.jpg";
                            $video[] = array("thumb" => $picture, "title" => $v['title'], "video" => $v['file']);
                        }
                        $response['videos'] = $video;
                    }
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = "false";
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["status"] = 201;
                $response["isSuccess"] = "false";
            }
            break;

        case "lesson-hw-questions":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $paid_member = $user['activation_status'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $lesson = getDataByWhere("lesson", "*", array("lesson_id" => $_POST['lesson_id']));
                $term = getFldValueById("report", "max(term)", array("test_id" => $test_id, "cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id']));
                $new_term = (int)$term + 1;

                $alert = 0;
                if ($lesson['free'] == 'N') {
                    if (!$paid_member == 'Y') {
                        $alert = 1;
                    }
                }

                if (!$alert) {
                    $has_order_no = getTotalRow("lesson_questions", array("lesson_id" => $_POST['lesson_id'], "cat_id" => $_POST['cat_id'], "HW" => 1, "course_id" => $test_id, "order_no>" => 0));

                    //                    $response['check_order_no'] = $has_order_no;

                    if ($has_order_no) {
                        $order_by = 'l.order_no';
                    } else {
                        $order_by = "q.qid";
                    }

                    $response['homework_total'] = getTotalRow("lesson_questions", array("cat_id" => $lesson['cat_id'], "lesson_id" => $lesson['lesson_id'], "course_id" => $test_id, "HW" => 1));

                    if (in_array($_POST['cat_id'], $data['story_question'])) {
                        $story_list = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.cat_id" => (int)$_POST['cat_id'], "l.lesson_id" => (int)$_POST['lesson_id'], "l.HW" => 1), array("q.qid" => "l.qid"), $order_by, '', '', 'q.story_id');

                        $homework_list = [];
                        $serial_no = 0;
                        $qlist = [];
                        foreach ($story_list as $a) {
                            //					    	$story=getFldValueById("story","story",array("story_id"=>$a['story_id']));

                            $homework_list = getQueryByJoin(array("question as q", "lesson_questions as l", "story as s"), "q.*,s.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "q.story_id" => $a['story_id']), array("q.qid" => "l.qid", "q.story_id" => "s.story_id"), $order_by, '', '', "q.qid");

                            foreach ($homework_list as $d) {
                                $serial_no++;
                                $qlist[] = array("story" => html_entity_decode(html_entity_decode(html_entity_decode($d['story']))), "serial_no" => $serial_no, "qid" => $d['qid'], "cat_id" => $d['cat_id'], "lesson_id" => $d['lesson_id'], "question" => html_entity_decode($d['question']), "question_explanation" => html_entity_decode(html_entity_decode($d['explanation'])), "correct_answer" => $data['alphabet'][$d['answer']], "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))));
                            }
                        }

                        $response['homework_questions'] = $qlist;
                    } else {
                        $homework_list = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.HW" => 1), array("q.qid" => "l.qid"), $order_by, '', '', "q.qid");

                        $qlist = [];
                        $serial_no = 0;
                        foreach ($homework_list as $d) {
                            $serial_no++;
                            $qlist[] = array("serial_no" => $serial_no, "qid" => $d['qid'], "cat_id" => $d['cat_id'], "lesson_id" => $d['lesson_id'], "question" => html_entity_decode(html_entity_decode($d['question'])), "question_explanation" => html_entity_decode(html_entity_decode($d['explanation'])), "correct_answer" => $data['alphabet'][$d['answer']], "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))));
                        }
                        $response['homework_questions'] = $qlist;
                    }

                    $response['cat_id'] = $_POST['cat_id'];
                    $response['lesson_id'] = $_POST['lesson_id'];
                    $response["status"] = 200;
                    $response["isSuccess"] = "true";
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = "false";
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["status"] = 201;
                $response["isSuccess"] = "false";
            }
            break;

        case "practice-test-questions":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $paid_member = $user['activation_status'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $lesson = getDataByWhere("lesson", "*", array("lesson_id" => $_POST['lesson_id']));

                $alert = 0;
                if ($lesson['free'] == 'N') {
                    if (!$paid_member == 'Y') {
                        $alert = 1;
                    }
                }

                if (!$alert) {
                    //                    if($_POST['test_type']=="freshtest")
                    //                    {
                    //                        $result_id=(int)getFldValueById("exam","result_id",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"is_result"=>0));
                    //                        
                    //                        deleteData("exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"is_result"=>0));
                    //
                    //                        deleteData("result",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"result_id"=>$result_id));
                    //                    }	

                    if ($_POST['cat_id'] && $_POST['lesson_id']) {
                        $response['cat_id'] = $_POST['cat_id'];
                        $response['lesson_id'] = $_POST['lesson_id'];

                        $has_pt_questions = getTotalRow("lesson_questions", array("cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "pt" => 1, "course_id" => $test_id));

                        if ($has_pt_questions) {
                            $pt_hw = 'pt';
                        } else {
                            $pt_hw = 'HW';
                        }

                        if (in_array($_POST['cat_id'], $data['story_question'])) {
                            $story_total = (int)getFldValueById("category", "num_of_story_pt", array("cat_id" => $_POST['cat_id'], "course_id" => $test_id));

                            if (!$story_total) {
                                $story_total = 4;
                            }
                            if ($story_total) {
                                $qno = 4;
                                $order = "rand()";

                                $response['total'] = getFldValueById("category", "num_of_ques_pt", array("cat_id" => $_POST['cat_id'], "course_id" => $test_id));

                                if (!$response['total']) {
                                    $response['total'] = 20;
                                }

                                $qset = 0;
                                $qset = getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                                if ($_POST['cat_id']) {
                                    //                                    if($_POST['test_type']=="continue" || $qset)
                                    if ($qset) {
                                        $response['story_list'] = getQueryByJoin(array("question as q", "exam as e", "lesson_questions as l"), "q.story_id", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.course_id" => $test_id, "e.user_id" => $user_id, "l.$pt_hw" => 1), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "q.story_id", $story_total, "", "q.story_id");
                                    } else {
                                        $response['story_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.course_id" => $test_id, "l.$pt_hw" => 1), array("q.qid" => "l.qid"), "q.story_id", $story_total, "", "q.story_id");
                                    }
                                }

                                $story = array();
                                $test_questions = [];
                                $total = 0;

                                $result_id = (int)getFldValueById("exam", "result_id", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                                $result_exist = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));

                                if (!$result_exist) {
                                    $result_id = insertTable("result", ["user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => $_POST['lesson_id'], "total_questions" => $response['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1]);
                                }

                                $serial_no = 0;
                                $qlist = [];

                                //                                $response['testing_story_list']=[];

                                foreach ($response['story_list'] as $a) {
                                    //                                    $response['testing_story_list'][] = $a['story_id'];
                                    $story[$a['story_id']] = getFldValueById("story", "story", array("story_id" => $a['story_id']));

                                    $has_order_no = getTotalRowsByJoin(array("question as q", "lesson_questions as l"), array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $test_id, "q.story_id" => $a['story_id'], "l.order_no>" => 0), array("q.qid" => "l.qid"));

                                    if ($has_order_no) {
                                        $order_by = 'l.order_no';
                                    } else {
                                        $order_by = "q.qid";
                                    }

                                    //                                    if($_POST['test_type']=="continue" || $qset)
                                    if ($qset) {
                                        $test_questions[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l", "exam as e"), "q.*,l.*,e.id as eid, e.user_ans", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.course_id" => $test_id, "e.user_id" => $user_id, "l.$pt_hw" => 1, "e.is_result" => 0, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid", "l.qid" => "e.qid"), $order_by, '', '', "q.qid");
                                    } else {
                                        $test_questions[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $test_id, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid"), $order_by, '', '', "l.qid");
                                    }

                                    $exist_prev_ques = (int)getTotalRowsByJoin(array("question as q", "exam as e"), array("e.cat_id" => $_POST['cat_id'], "e.user_id" => $user_id, "e.lesson_id" => (int)$_POST['lesson_id'], "q.story_id" => $a['story_id'], "is_result" => 0), array("q.qid" => "e.qid"));

                                    $ord = 0;
                                    foreach ($test_questions[$a['story_id']] as $d) {
                                        $serial_no++;

                                        //                            ********* insert new questions into running exam *******
                                        $eid = 0;
                                        //                                        if($_POST['test_type']!="continue" && !$exist_prev_ques)
                                        if (!$exist_prev_ques) {
                                            $ord++;
                                            $eid = insertTable("exam", ["user_id" => $user_id, "cat_id" => $_POST['cat_id'], "result_id" => $result_id, "lesson_id" => $_POST['lesson_id'], "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0]);
                                        }
                                        if ($eid) {
                                            $d['eid'] = $eid;
                                        }
                                        //                          ************** end ***********
                                        $qlist[] = array("story" => str_replace('"/uploads/', '"https://staging-officers.asvab-tutoring.com/uploads/', html_entity_decode(html_entity_decode(html_entity_decode($story[$a['story_id']])))), "serial_no" => $serial_no, "id" => $d['qid'], "eid" => $d['eid'], "cat_id" => $_POST['cat_id'], "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "answer" => $d['answer'], "my_answer" => $d['user_ans']);
                                    }
                                }
                                $response['practicetest'] = $qlist;
                            }
                        } else {
                            $response['total'] = getFldValueById("category", "num_of_ques_pt", array("cat_id" => $_POST['cat_id'], "course_id" => $test_id));
                            if (!$response['total']) {
                                $response['total'] = 15;
                            }
                            $qset = 0;
                            $qset = getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                            //                            if($_POST['test_type']=="continue" && $qset)
                            if ($qset) {
                                $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l", "exam as e"), "e.user_ans, e.id as eid, q.*, l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $test_id, "e.user_id" => $user_id, "e.is_result" => 0), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "e.id", $response['total'], "", "q.qid");
                            } else {
                                $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $test_id), array("q.qid" => "l.qid"), "l.order_no", $response['total'], "", "q.qid");
                            }
                        }
                    } else {
                        $qset = 0;
                        $qset = getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                        //                        if($_POST['test_type']=="continue" || $qset)
                        if ($qset) {
                            $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l", "exam as e"), "e.user_ans, e.id as eid,q.*, l.*", array("l.cat_id" => $_POST['cat_id'], "l.pt" => 1, "l.course_id" => $test_id, "e.user_id" => $user_id, "e.is_result" => 0), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "e.id", $response['total']);
                        } else {
                            $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.cat_id" => $_POST['cat_id'], "l.pt" => 1, "l.course_id" => $test_id), array("q.qid" => "l.qid"), "rand()", $response['total']);
                        }
                    }

                    if (!in_array($_POST['cat_id'], $data['story_question'])) {
                        $result_id = (int)getFldValueById("exam", "result_id", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                        $result_exist = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));

                        if (!$result_exist) {
                            $result_id = insertTable("result", ["user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => $_POST['lesson_id'], "total_questions" => $response['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1]);
                        }

                        $exist_prev_ques = getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                        $ord = 0;
                        $qlist = [];
                        $eid = 0;
                        foreach ($response['practicetest'] as $a) {
                            $ord++;
                            //                            if($_POST['test_type']!="continue" && !$exist_prev_ques)
                            if (!$exist_prev_ques) {
                                $eid = insertTable("exam", ["user_id" => $user_id, "cat_id" => $_POST['cat_id'], "result_id" => $result_id, "lesson_id" => $_POST['lesson_id'], "qid" => $a['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0]);
                            }
                            if ($eid) {
                                $a['eid'] = $eid;
                            }

                            //                           ************** end ***********
                            $qlist[] = array("serial_no" => $ord, "id" => $a['qid'], "eid" => $a['eid'], "cat_id" => $_POST['cat_id'], "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_e']))), "answer" => $a['answer'], "my_answer" => $a['user_ans']);
                        }
                        $response['practicetest'] = $qlist;
                    }
                    $response["total_answered_questions"] = getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "user_ans > " => 0, "is_result" => 0));

                    if ($response["total_answered_questions"]) {
                        $response['alert'] = "";
                        $response['alert'] = "You already answered " . $response["total_answered_questions"] . " question(s) from this lesson.";
                    }
                    if ($_GET['test_type'] == "continue") {
                        $data['alert'] = "";
                    }
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = "false";
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "answer-practice-test":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $correct = "0";

                if ($_POST['answer'] == $_POST['answer_no']) {
                    $correct = 1;
                }

                $exist_in_exam = getTotalRow("exam", array("id" => $_POST['eid']));

                if ($exist_in_exam) {
                    $id = updateTable("exam", ["user_ans" => $_POST['answer_no'], "iscorrect" => (int)$correct], array("id" => $_POST['eid']));
                }

                $total_answer = getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "is_result" => 0, "lesson_id" => (int)$_POST['lesson_id'], "user_ans>" => 0));

                $pending = $_POST['total'] - $total_answer;

                if ($id) {
                    $response['message'] = "You have answered this question successfully.";
                } else {
                    $response['message'] = "Error!";
                }

                $array['pending'] = "";

                if ($pending > 0) {
                    $response['pending_total'] = "Still pending " . $pending . " questions to answer!.";
                    // $array['pending']="";
                } else {
                    $response['message'] = "Congratulation ! You have successfully answered all questions. Check your score.";

                    $response['cat_id'] = $_POST['cat_id'];
                    $response['lesson_id'] = $_POST['lesson_id'];
                    $response['total'] = $_POST['total'];

                    //				    $total=$_POST['total'];
                    //                    
                    //                    $total_correct=getTotalRow("exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id, "is_result"=>0,"lesson_id"=>(int)$_POST['lesson_id'],"iscorrect"=>1));
                    //                    
                    //			        $total_wrong=getTotalRow("exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id, "is_result"=>0,"lesson_id"=>(int)$_POST['lesson_id'],"iscorrect"=>0));
                    //			     
                    //			        $total_answer_given=$total_correct+$total_wrong;
                    //			     
                    //			        $total_answer_not_given=$total-$total_answer_given;
                    //			        
                    //                    if($total_correct==0){
                    //                        $score = 0;
                    //                    }else{
                    //                        $score=round(20+(($total_correct/$total)*100)*0.55);
                    //                    }
                    //			        
                    //                    $result_id=getFldValueById("result", "result_id", array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'], "score"=>-1));
                    //                    
                    //                    $response['result_id']=$result_id;
                    //                    
                    //                    if($result_id){
                    //		    	
                    //                        updateTable("result",array("correct_answers"=>$total_correct, "score"=>$score, "result_date"=>date('Y-m-d H:i:s')),array("user_id"=>$user_id, "cat_id"=>$_POST['cat_id'], "lesson_id"=>(int)$_POST['lesson_id'],"result_id"=>$result_id));
                    //                        updateTable("exam",array("is_result"=>1),array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'], "result_id"=>$result_id));
                    //                    } 
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "practice-test-submission":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $total = $_POST['total'];

                $total_correct = (int)getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "is_result" => 0, "lesson_id" => (int)$_POST['lesson_id'], "iscorrect" => 1));

                $total_wrong = (int)getTotalRow("exam", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "is_result" => 0, "lesson_id" => (int)$_POST['lesson_id'], "iscorrect" => 0));

                $total_answer_given = $total_correct + $total_wrong;

                $total_answer_not_given = $total - $total_answer_given;

                if ($total_correct == 0) {
                    $score = 0;
                } else {
                    //                    $score=round(20+(($total_correct/$total)*100)*0.55);
                    $score = round(($total_correct / $total) * 100);
                }

                $result_id = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "score" => -1));

                $response['result_id'] = $result_id;

                if ($result_id) {

                    updateTable("result", array("correct_answers" => $total_correct, "score" => (int)$score, "result_date" => date('Y-m-d H:i:s')), array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));
                    updateTable("exam", array("is_result" => 1), array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));
                }


                $cat_name = getFldValueById("category", "category_name", array("cat_id" => $_POST['cat_id']));
                $lesson_name = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $_POST['lesson_id']));
                $result_data = getDataByWhere("result", "*", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], 'result_id' => $result_id));
                $response['report'] = array("result_id" => $result_id, "cat_id" => $_POST['cat_id'], "cat_name" => $cat_name, "lesson_id" => $_POST['lesson_id'], "lesson_name" => $lesson_name, "course_id" => $test_id, "date" => showdate($result_data['result_date']), "correct" => $result_data['correct_answers'], "total_questions" => $result_data['total_questions'], "wrong" => ($result_data['total_questions'] - $result_data['correct_answers']), "score" => (int)$result_data['score']);
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "practice-test-result":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $cat_name = getFldValueById("category", "category_name", array("cat_id" => $_POST['cat_id']));
                $lesson_name = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $_POST['lesson_id']));
                $result_data = getDataByWhere("result", "*", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], 'result_id' => $_POST['result_id']));
                $response['report'] = array("result_id" => $_POST['result_id'], "cat_id" => $_POST['cat_id'], "cat_name" => $cat_name, "lesson_id" => $_POST['lesson_id'], "lesson_name" => $lesson_name, "course_id" => $test_id, "date" => showdate($result_data['result_date']), "correct" => $result_data['correct_answers'], "total_questions" => $result_data['total_questions'], "wrong" => ($result_data['total_questions'] - $result_data['correct_answers']), "score" => (int)$result_data['score']);
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;


        case "review-test": //after submiting practice test, view the submitted questions on clicking "review test" button

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $cat_name = getFldValueById("category", "category_name", array("cat_id" => $_POST['cat_id']));
                $lesson_name = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $_POST['lesson_id']));
                $result_data = getDataByWhere("result", "*", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], 'result_id' => $_POST['result_id']));
                $response['report'] = array("result_id" => $_POST['result_id'], "cat_id" => $_POST['cat_id'], "cat_name" => $cat_name, "lesson_id" => $_POST['lesson_id'], "lesson_name" => $lesson_name, "course_id" => $test_id, "date" => showdate($result_data['result_date']), "correct" => $result_data['correct_answers'], "total_questions" => $result_data['total_questions'], "wrong" => ($result_data['total_questions'] - $result_data['correct_answers']), "score" => round($result_data['score']));

                $qlist = [];
                $story = [];
                $i = 0;
                $list = [];
                $flag = 0;
                $srno = 0;

                if (in_array($_POST['cat_id'], $data['story_question'])) {
                    $story_list = getQueryByJoin(array("question as q", "exam as e"), "q.story_id", array("e.lesson_id" => $_POST['lesson_id'], "e.cat_id" => $_POST['cat_id'], "e.user_id" => $user_id), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");

                    foreach ($story_list as $d) {
                        $story = getFldValueById("story", "story", array("story_id" => $d['story_id']));

                        $question_test[$d['story_id']] = getQueryByJoin(array("question as q", "exam as e"), "q.*,e.*", array("e.lesson_id" => $_POST['lesson_id'], "e.cat_id" => $_POST['cat_id'], "e.user_id" => $user_id, "e.result_id" => $_POST['result_id'], "q.story_id" => $d['story_id']), array("q.qid" => "e.qid"), "e.id", '', '', "q.qid");


                        foreach ($question_test[$d['story_id']] as $a) {
                            $i++;

                            if (!$a['user_ans']) {
                                $text = "No Answer";
                            } elseif ($a['user_ans'] == $a['answer']) {
                                $text = 'Correct';
                            } else {
                                $text = 'Wrong Answer';
                            }

                            $correct_answer = $data['alphabet'][$a['answer']] . " | Your answer is " .  $data['alphabet'][$a['user_ans']] . " | " . $text;
                            $explanation = html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['explanation'])));

                            $qlist[] = array("story" => str_replace('"/uploads/', '"https://staging-officers.asvab-tutoring.com/uploads/', html_entity_decode(html_entity_decode(html_entity_decode($story)))), "srno" => $i, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_e']))), "correct_answer" => $correct_answer, "myanswer" => $a['user_ans'], "explanation" => $a['explanation']);
                        }
                    }

                    $response['qlist'] = $qlist;
                } else {
                    $question_test = getQueryByJoin(array("question as q", "exam as e"), "q.*,e.*", array("e.lesson_id" => $_POST['lesson_id'], "e.cat_id" => $_POST['cat_id'], "e.result_id" => $_POST['result_id']), array("q.qid" => "e.qid"), 'e.id', '', '', 'q.qid');

                    $i = 0;
                    foreach ($question_test as $a) {
                        $i++;
                        $my_answer = $a['user_ans'];
                        if (!$my_answer) {
                            $text = "No Answer";
                        } elseif ($my_answer == $a['answer']) {
                            $text = 'Correct';
                        } else {
                            $text = 'Wrong Answer';
                        }

                        $correct_answer = $data['alphabet'][$a['answer']] . " | Your answer is " .  $data['alphabet'][$my_answer] . " | " . $text;
                        $explanation = html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['explanation'])));
                        $qlist[] = array("srno" => $i, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_e']))), "correct_answer" => $correct_answer, "myanswer" => $a['user_ans'], "explanation" => $explanation);
                    }

                    $response['qlist'] = $qlist;
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "knowledge-update":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $paid_member = $user['activation_status'];

                if ($paid_member == 'Y') {
                    $total = getTotalRowsByJoin(array("question as q", "lesson_questions as l"), array("l.cat_id" => $_POST['flashcard_id'], "l.course_id" => $test_id), array("q.qid" => "l.qid"));

                    if ($total) {
                        $questions = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*", array("l.cat_id" => $_POST['flashcard_id'], "l.course_id" => $test_id), array("q.qid" => "l.qid"), "rand()");

                        $serial_no = 0;
                        $question = array();

                        foreach ($questions as $a) {
                            $serial_no++;
                            $question[] = array("serial_no" => $serial_no, "id" => $a['qid'], "cat_id" => $a['cat_id'], "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['question']))), "question_explanation" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['explanation']))));
                        }

                        $response['questions'] = $question;
                        $response["status"] = 200;
                        $response["isSuccess"] = "true";
                    } else {
                        $response['message'] = "No questions to view.";
                    }
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = "false";
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["status"] = 201;
                $response["isSuccess"] = "false";
            }

            break;

        case "prediction-test-questions":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $paid_member = $user['activation_status'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $response['pred_test_name'] = getFldValueById("predic_test", "test_name", array("Predic_test_id" => $_POST['pred_test_id']));
                $response['pred_test_id'] = $_POST['pred_test_id'];

                $alert = 0;
                if ($lesson['free'] == 'N') {
                    if (!$paid_member == 'Y') {
                        $alert = 1;
                    }
                }

                if (!$alert) {
                    if ($_POST['test_type'] == "freshtest") {
                        $result_id = (int)getFldValueById("exam", "result_id", array("Predic_test_id" => $_POST['pred_test_id'], "user_id" => $user_id, "is_result" => 0));

                        deleteData("exam", array("Predic_test_id" => $_POST['pred_test_id'], "user_id" => $user_id, "is_result" => 0));
                        deleteData("result", array("Predic_test_id" => $_POST['pred_test_id'], "user_id" => $user_id, "result_id" => $result_id));
                    }

                    $question = [];
                    $prediction_cat = [];
                    $pred_categories = [];
                    $story = array();
                    $total = 0;
                    $i = 0;
                    $completed_total = 0;
                    $flag = 1;

                    $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_POST['pred_test_id']), "", "", "", "id");

                    foreach ($pred_cat_list as $p) {
                        $total += $p['number_of_questions'];
                        $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));

                        if (!$i && !$_POST['cat_id']) {
                            $_POST['cat_id'] = $cat['cat_id'];
                            $flag = 0;
                        }

                        $completed = (int)getTotalRow("exam", ['Predic_test_id' => $_POST['pred_test_id'], 'user_id' => $user_id, 'cat_id' => $cat['cat_id'], 'user_ans>' => 0, 'is_result' => 0]);

                        if ($completed > $p['number_of_questions']) {
                            $completed = $p['number_of_questions'];
                        }
                        $completed_total += $completed;

                        $prediction_cat[$cat["cat_id"]] = ["cat_name" => $cat['category_name'], "shortcode" => $cat["shortcode"], "cat_id" => $cat['cat_id'], "total" => $p['number_of_questions'], "completed" => $completed];

                        $pred_categories[] = ["cat_name" => $cat['category_name'], "shortcode" => $cat["shortcode"], "cat_id" => $cat['cat_id'], "total" => $p['number_of_questions'], "duration" => $p['duration'], "completed" => $completed];

                        $i++;
                    }
                    $response['cat_id'] = $_POST['cat_id'];
                    $response['category_name'] = getFldValueById("category", "category_name", ["cat_id" => $_POST['cat_id']]);
                    $response['completed_total'] = $completed_total;
                    $response['total'] = $total;
                    $response['prediction_category'] = $pred_categories;
                    $response['alert'] = "";
                    $cat_id = $_POST['cat_id'];
                    $limit = $prediction_cat[$cat_id]["total"];
                    $lessons_text = [];
                    $set_id = 0;

                    if ($completed_total) {
                        $response['alert'] = "You have answers (" . $completed_total . ") questions already. Do you want to continue or start a new test?";
                    }

                    if (in_array($cat_id, $data['story_question'])) {
                        if (in_array($cat_id, array(84, 63, 65))) {
                            $limit = (int)$limit / 5;
                        } else {
                            $limit = (int)$limit / 4;
                        }

                        $story_cat_limit = 5;
                        $tot = 0;
                        $story_list_array = [];
                        $story_list = [];

                        $exist_prev_question = getTotalRow("exam", array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        if ((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed'] > 0) || $exist_prev_question) {
                            $story_list_array = getQueryByJoin(array("question as q", "exam as e"), "q.story_id", array("e.cat_id" => (int)$cat_id, "e.Predic_test_id" => $_POST['pred_test_id'], "e.user_id" => $user_id, "e.is_result" => 0), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");
                            $response['testing cat id'] = (int)$cat_id;
                            $response['testing Predic_test_id'] = $_POST['pred_test_id'];
                            $response['testing user_id'] = $user_id;
                        } else {
                            $attempted_set_rows = getQuery("exam", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "is_result" => 1), "", "", "", "pred_set_id");
                            $attempted_set_list = array();
                            foreach ($attempted_set_rows as $r) {
                                $attempted_set_list[] = $r['pred_set_id'];
                            }
                            $set_rows = getQuery("predic_questions", "*", array("course_id" => $test_id, "predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id), "", "", "", "set_id");
                            $set_list = array();
                            foreach ($set_rows as $r) {
                                if (!in_array($r['set_id'], $attempted_set_list)) {
                                    $set_list[] = $r['set_id'];
                                }
                            }
                            if (empty($set_list)) {
                                $set_id = $attempted_set_list[array_rand($attempted_set_list)];
                            } else {
                                $set_id = $set_list[array_rand($set_list)];
                            }

                            if ($set_id) {
                                $story_list_array = getQueryByJoin(array("question as q", "predic_questions as p"), "q.story_id", array("p.cat_id" => (int)$cat_id, "p.set_id" => $set_id, "q.story_id >" => 0, "p.predic_test_id" => $_POST['pred_test_id'], "p.course_id" => $test_id), array("q.qid" => "p.qid"), "", "", "", "q.story_id");
                            } else {
                                $story_total = (int)$limit;
                                $story_list_array = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.cat_id" => (int)$cat_id, "l.course_id" => $test_id), array("q.qid" => "l.qid"), "rand()", "", "", "q.story_id");
                            }
                        }

                        $result_id = (int)getFldValueById("exam", "result_id", array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        $result_exist = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $cat_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "result_id" => $result_id));

                        if (!$set_id) {
                            $set_id = 0;
                        }

                        if (!$result_exist) {
                            $result_id = insertTable("result", ["user_id" => $user_id, "cat_id" => $cat_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "total_questions" => $prediction_cat[$cat_id]['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1, "pred_set_id" => $set_id]);
                        }

                        $serial_no = 0;
                        $total_story_ques = $prediction_cat[$cat_id]["total"];
                        foreach ($story_list_array as $a) {
                            if ($total_story_ques <= 0) {
                                break;
                            }

                            $story = getFldValueById("story", "story", array("story_id" => $a['story_id']));

                            if ($set_id) {
                                $question_list = getQueryByJoin(array("question as q", "predic_questions as p"), "q.*,p.*", array("p.cat_id" => (int)$cat_id, "p.predic_test_id" => $_POST['pred_test_id'], "p.course_id" => $test_id, "q.story_id" => $a['story_id']), array("q.qid" => "p.qid"), 'q.qid', '', '', "q.qid");
                            } else {
                                $question_list = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.cat_id" => (int)$cat_id, "l.course_id" => $test_id, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid"), "q.qid", $total_story_ques, '', "l.qid");

                                $n_question = getTotalRowsByJoin(array("question as q", "lesson_questions as l"), array("l.cat_id" => (int)$cat_id, "l.course_id" => $test_id, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid"));

                                $total_story_ques -= $n_question;
                            }

                            foreach ($question_list as $d) {
                                $serial_no++;
                                $eid = 0;
                                if (!$prediction_cat[$cat_id]['completed'] && !$exist_prev_question) {
                                    $eid = insertTable("exam", ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0, "pred_set_id" => $set_id]);
                                }

                                if ($eid) {
                                    $d['eid'] = $eid;
                                } else {
                                    //                                    $d['eid'] = $d['id'];
                                    $d['eid'] = getFldValueById("exam", "id", ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "is_result" => 0]);

                                    $d['user_ans'] = getFldValueById("exam", "user_ans", ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "is_result" => 0]);
                                }

                                $question[] = array("story" => str_replace('"/uploads/', '"https://staging-officers.asvab-tutoring.com/uploads/', html_entity_decode(html_entity_decode(html_entity_decode($story)))), "serial_no" => $serial_no, "id" => $d['qid'], "eid" => $d['eid'], "pred_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "answer" => $d['answer'], "my_answer" => $d['user_ans']);

                                //                                if($serial_no==$prediction_cat[$cat_id]["total"]){
                                //                                    break;
                                //                                }
                            }
                        }
                        $response['question_test'] = $question;
                    } else {
                        $exist_prev_question = getTotalRow("exam", array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        if ((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed'] > 0) || $exist_prev_question) {
                            $question_list = getQueryByJoin(array("question as q", "exam as e"), "q.*, e.*", array("e.cat_id" => $cat_id, "e.user_id" => $user_id, "e.Predic_test_id" => $_POST['pred_test_id'], "e.is_result" => 0), array("e.qid" => "q.qid"), "e.id", $limit);
                        } else {
                            $attempted_set_rows = getQuery("exam", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "is_result" => 1), "", "", "", "pred_set_id");
                            $attempted_set_list = array();
                            foreach ($attempted_set_rows as $r) {
                                $attempted_set_list[] = $r['pred_set_id'];
                            }
                            $set_rows = getQuery("predic_questions", "*", array("course_id" => $test_id, "predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id), "", "", "", "set_id");
                            $set_list = array();
                            foreach ($set_rows as $r) {
                                if (!in_array($r['set_id'], $attempted_set_list)) {
                                    $set_list[] = $r['set_id'];
                                }
                            }
                            if (empty($set_list)) {
                                $set_id = $attempted_set_list[array_rand($attempted_set_list)];
                            } else {
                                $set_id = $set_list[array_rand($set_list)];
                            }

                            if ($set_id) {
                                $question_list = getQueryByJoin(array("predic_questions as p", "question as q"), "q.*,p.*", array("p.set_id" => $set_id, "p.course_id" => $test_id, "p.predic_test_id" => $_POST['pred_test_id'], "p.cat_id" => $cat_id), array("p.qid" => "q.qid"), "rand()", $limit);
                            } else {
                                $question_list = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.cat_id" => (int)$cat_id, "l.course_id" => $test_id), array("q.qid" => "l.qid"), "rand()", $limit, "", "q.qid");
                            }
                        }
                    }

                    if (!in_array($cat_id, $data['story_question'])) {
                        if (!$set_id) {
                            $set_id = 0;
                        }

                        $result_id = (int)getFldValueById("exam", "result_id", array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        $result_exist = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $cat_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "result_id" => $result_id));

                        if (!$result_exist) {
                            $result_id = insertTable("result", ["user_id" => $user_id, "cat_id" => $cat_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "total_questions" => $prediction_cat[$cat_id]['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1, "pred_set_id" => $set_id]);
                        }

                        $exist_prev_ques = getTotalRow("exam", array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        $serial_no = 0;
                        foreach ($question_list as $d) {
                            $serial_no++;
                            $eid = 0;
                            if (!$prediction_cat[$cat_id]['completed'] && !$exist_prev_ques) {
                                $eid = insertTable("exam", ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0, "pred_set_id" => $set_id]);
                            }

                            if ($eid) {
                                $d['eid'] = $eid;
                            } else {
                                $d['eid'] = $d['id'];
                            }
                            $question[] = array("serial_no" => $serial_no, "id" => $d['qid'], "eid" => $d['eid'], "pred_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "answer" => $d['answer'], "my_answer" => $d['user_ans']);
                        }

                        $response['question_test'] = $question;
                    }
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = "false";
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;


        case "answer-prediction-test":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $correct = "0";

                if ($_POST['answer'] == $_POST['answer_no']) {
                    $correct = 1;
                }

                $exist_in_exam = getTotalRow("exam", array("id" => $_POST['eid']));

                if ($exist_in_exam) {
                    $id = updateTable("exam", ["user_ans" => $_POST['answer_no'], "iscorrect" => (int)$correct], array("id" => $_POST['eid']));
                }

                $total_answer = getTotalRow("exam", array("Predic_test_id" => $_POST['pred_test_id'], "user_id" => $user_id, "is_result" => 0, "user_ans>" => 0));

                $pending = $_POST['total'] - $total_answer;

                if ($id) {
                    $response['message'] = "You have answered this question successfully.";
                } else {
                    $response['message'] = "Error!";
                }

                $array['pending'] = "";

                if ($pending > 0) {
                    $response['pending_total'] = "Still pending " . $pending . " questions to answer!.";
                } else {
                    $response['message'] = "Congratulation ! You have successfully answered all questions. Check your score.";

                    $response['cat_id'] = $_POST['cat_id'];
                    $response['pred_test_id'] = $_POST['pred_test_id'];
                    $response['total'] = $_POST['total'];
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "prediction-test-submit":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $total_questions = 0;
                $total_correct_ans = 0;
                $total_cat_score = 0;
                $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_POST['pred_test_id']));
                $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_POST['pred_test_id']), "", "", "", "id");

                foreach ($pred_cat_list as $p) {
                    $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                    $cat_id = $p['cat_id'];
                    $cat_score = 0;
                    $num_correct_ans = (int)getTotalRow("exam", array("iscorrect" => 1, "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "is_result" => 0));

                    if ($num_correct_ans) {
                        if($_SESSION['test_id']==28){
                            if($p['number_of_questions']==11){
                                $cat_score = $data["11q_asvab_pred_score_array"][$num_correct_ans];
                            }
                            else if($p['number_of_questions']==16){
                                $cat_score = $data["16q_asvab_pred_score_array"][$num_correct_ans];
                            }
                        }
                        else{
                            $correct_ans_percentage=round(($num_correct_ans / $p['number_of_questions']) * 100);
			    	        $cat_score=round(20 + $correct_ans_percentage * 0.55);
                        }
                    }
                    $total_cat_score += $cat_score;
                    $num_wrong_ans = $p['number_of_questions'] - $num_correct_ans;
                    $total_questions += $p['number_of_questions'];
                    $total_correct_ans += $num_correct_ans;

                    $pred_set_id = (int)getFldValueById("result", "pred_set_id", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "score" => -1));

                    $result_id = getFldValueById("result", "result_id", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "score" => -1));

                    updateTable("result", ["score" => (int)$cat_score, "correct_answers" => $num_correct_ans], ['user_id' => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id]);
                }

                if($_SESSION['test_id']==28){
                    if($total_cat_score > 270){
                        $final_pred_score = 99; 
                    }
                    else if($total_cat_score < 91){
                        $final_pred_score = 0; 
                    }
                    else
                    {
                        $final_pred_score = $data["asvab_pred_score_array"][$total_cat_score];
                    }
                }
                else{
                    $final_pred_score = round($total_cat_score/$num_category);
                }

                $reslt_id =  insertTable("result", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score" => $final_pred_score, "result_date" => date("Y-m-d"), "pred_set_id" => $pred_set_id, "total_questions" => $total_questions, "correct_answers" => $total_correct_ans));

                updateTable("exam", ['is_result' => 1], ['user_id' => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "pred_set_id" => $pred_set_id]);

                if ($reslt_id) {
                    $response['result_id'] = $reslt_id;
                }
                $response['pred_test_id'] = $_POST['pred_test_id'];
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "prediction-test-result":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $response['prediction_test'] = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_POST['pred_test_id']));
                //                $response['date']=getFldValueById("result","result_date",array("result_id"=>$_POST['result_id']));
                //                $pred_set_id=getFldValueById("result","pred_set_id",array("result_id"=>$_POST['result_id']));

                $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_POST['pred_test_id']));
                $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id']), "result_id desc", 1);

                $response['report'] = [];

                foreach ($pred_report as $p) {
                    $response['report']['prediction_score'] = array("result_id" => $p['result_id'], "cat_id" => $p['cat_id'], "pred_test_id" => $p['Predic_test_id'], "course_id" => $test_id, "date" => showdate($p['result_date']), "correct" => $p['correct_answers'], "total_questions" => $p['total_questions'], "wrong" => ($p['total_questions'] - $p['correct_answers']), "score" => (int)$p['score']);
                }

                $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_POST['pred_test_id']), "", "", "", "id");
                //                $pred_test_report=[];
                $pred_cat_name = [];
                $total_array = [];
                //                $i=0;
                foreach ($pred_cat_list as $p) {
                    //                    if(!$i && !$_POST['lesson_id']){
                    //                        $_POST['lesson_id']=$p['cat_id']; 
                    //                    }
                    //                    $i++;
                    $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                    $cat_id = $p['cat_id'];
                    $cat_name = $cat['category_name'];
                    $total_array[$cat['cat_id']] = $p['number_of_questions'];
                    //                    $pred_cat_name[]=["cat_id"=>$cat_id,"cat_name"=>$cat_name];

                    $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id), "result_id desc", 1);

                    foreach ($pred_report as $p) {
                        $response['report'][$cat_name] = array("result_id" => $p['result_id'], "cat_id" => $p['cat_id'], "cat_name" => $cat_name, "pred_test_id" => $p['Predic_test_id'], "course_id" => $test_id, "date" => showdate($p['result_date']), "correct" => $p['correct_answers'], "total_questions" => $p['total_questions'], "wrong" => ($p['total_questions'] - $p['correct_answers']), "score" => (int)$p['score']);
                    }

                    //                    $pred_report=getDataByWhere("result","*",array("user_id"=>$user_id,"Predic_test_id"=>$_POST['pred_test_id'], "cat_id"=>$cat_id));

                    //                    $response['report'][$cat_name]=array("result_id"=>$pred_report['result_id'],"cat_id"=>$pred_report['cat_id'],"cat_name"=>$cat_name,"pred_test_id"=>$pred_report['Predic_test_id'],"course_id"=>$test_id,"date"=>showdate($pred_report['result_date']),"correct"=>$pred_report['correct_answers'],"total_questions"=>$pred_report['total_questions'],"wrong"=>($pred_report['total_questions']-$pred_report['correct_answers']),"score"=>(int)$pred_report['score']);

                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;


        case "progress-report":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $report_cat_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1, "lesson_id>" => 0), "cat_id", "", "", "cat_id");

                $lesson_reports = array();

                foreach ($report_cat_list as $rc) {
                    $cat_name = getFldValueById("category", "category_name", array("cat_id" => $rc['cat_id']));

                    $report_lesson_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1, "lesson_id>" => 0, "cat_id" => $rc['cat_id']), "lesson_id", "", "", "lesson_id");

                    foreach ($report_lesson_list as $rl) {
                        $lesson_name = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $rl['lesson_id']));
                        $reports = getQuery("result", "*", array("user_id" => $user_id, "cat_id" => $rc['cat_id'], "lesson_id" => $rl['lesson_id']), "result_id desc", "1");

                        foreach ($reports as $r) {
                            $lesson_reports[] = array(
                                "result_id" => $r['result_id'],
                                "cat_id" => $rc['cat_id'],
                                "cat_name" => $cat_name,
                                "lesson_id" => $rl['lesson_id'],
                                "lesson_name" => $lesson_name,
                                "total_questions" => $r['total_questions'],
                                "correct_answers" => $r['correct_answers'],
                                "wrong_answer" => $r['total_questions'] - $r['correct_answers'],
                                "total_answered" => $r['total_questions'],
                                "result_date" => $r['result_date'],
                                "score" => round($r['score'])
                            );
                        }
                    }
                }

                //                $response['checking'] = [];
                //                $response['checking'][]=$rl['result_id'];
                $response['lesson_result'] = $lesson_reports;

                $pred_test_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1), "Predic_test_id", "", "", "Predic_test_id");

                $prediction_reports = array();
                foreach ($pred_test_list as $pl) {
                    $pred_name = getFldValueById("predic_test", "test_name", array("Predic_test_id" => $pl['Predic_test_id']));
                    $preports = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $pl['Predic_test_id']), "result_id desc", "1");

                    $tmp_report = [];
                    foreach ($preports as $r) {
                        if ($r['cat_id'] == $r['Predic_test_id']) {
                            $tmp_report[$pred_name] = array(
                                "result_id" => $r['result_id'],
                                "pred_test_id" => $r['Predic_test_id'],
                                "pred_test_name" => $pred_name,
                                "total_questions" => $r['total_questions'],
                                "correct_answers" => $r['correct_answers'],
                                "wrong_answer" => $r['total_questions'] - $r['correct_answers'],
                                "total_answered" => $r['total_questions'],
                                "result_date" => $r['result_date'],
                                "score" => round($r['score'])
                            );
                            $prediction_reports[] = $tmp_report;
                            $tmp_report = [];
                        } else {
                            $cat_name = getFldValueById("category", "category_name", array("cat_id" => $r['cat_id']));

                            $tmp_report[$cat_name] = array(
                                "result_id" => $r['result_id'],
                                "cat_id" => $r['cat_id'],
                                "cat_name" => $cat_name,
                                "pred_test_id" => $r['Predic_test_id'],
                                "pred_test_name" => $pred_name,
                                "total_questions" => $r['total_questions'],
                                "correct_answers" => $r['correct_answers'],
                                "wrong_answer" => $r['total_questions'] - $r['correct_answers'],
                                "total_answered" => $r['total_questions'],
                                "result_date" => $r['result_date'],
                                "score" => round($r['score'])
                            );
                        }
                    }
                }
                $response['prediction-test'] = $prediction_reports;
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;


        case "edit-profile":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $response["userDetails"] = array(
                    "email" => $user['email'],
                    "first_name" => $user['first_name'],
                    "last_name" => $user['last_name'],
                    "mobile" => $user['mobile'],
                    "password" => $user['password'],
                    "authorizationToken" => $user['auth_token']
                );

                $error = "";
                if ($user['email']) {
                    if (isset($_POST['authorizationToken'])) {
                        unset($_POST['authorizationToken']);
                        unset($_POST['action']);
                    }

                    $flag = updateTable("user", $_POST, array("user_id" => $user_id));

                    if ($flag) {
                        $response['message'] = "Your profile has been successfully updated.";
                        $response['status'] = 200;
                        $response["isSuccess"] = true;
                    } else {
                        $response['message'] = "Your profile has not been updated. Please try again.";
                        $response["isSuccess"] = false;
                    }
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["isSuccess"] = false;
            }
            break;

        case "change-password":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                if ($_POST['new_password'] == $_POST['conf_password']) {
                    $r1 = updateTable("user", array("password" => md5($_POST['new_password'])), array("user_id" => $user_id));

                    $response['message'] = "Password has been successfully changed.";

                    $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Change Password"));

                    $body = html_entity_decode($mail['template']);
                    $subject = $mail['subject'];
                    $mail_body = str_replace("{name}", $user['first_name'] . " " . $user['last_name'], $body);
                    $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
                    $mail_body = str_replace("{email}", $user['email'], $mail_body);
                    $mail_body = str_replace("{password}", $_POST['new_password'], $mail_body);
                    $mail_body = html_entity_decode($mail_body);

                    smtpmailer($user['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
                } else {
                    $response['message'] = "Confirm password does not match with new password!";
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "banner":

            $banner_array = array();
            $banner = getQuery("slider", "*", array(), "ord", 4);
            foreach ($banner as $d) {
                $banner_array[] = array("banner" => $configs['vpath'] . $d['slider'], "link" => $d['link']);
            }
            $response['slider'] = $banner_array;

            break;

        case "view-contact_us":
            $name = [];
            $test = getQuery("test", "*", array("status" => "Y"), "ord");
            foreach ($test as $t) {
                $name[$t['id']] = $t['name'];
            }
            $response['test'] = $name;
            break;

        case "contact_us":
            $test_name = "";
            if ($_POST['authorizationToken']) {
                $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));
                if ($exist) {
                    $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                    $user_id = $user['user_id'];
                    $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                    $test_name = getFldValueById("course", "course_name", array("course_id" => $test_id));
                }
            }

            $_POST['date'] = date("Y-m-d");
            $_POST['status'] = "N";
            $_POST['user_id'] = (int)$user_id;
            $_POST['test_id'] = (int)$test_id;
            $_POST['message'] = nl2br($_POST['message']);
            if (!$test_name) {
                $test_name = "Sorry, authentication token is missing!";
            }

            $POST['reg_at'] = "App";

            unset($_POST['action']);
            $flag = insertTable("enquiry", $_POST);
            if ($flag) {
                $response['message'] = "We have received your information. We will contact you soon.";
            } else {
                $response['message'] = "Failed! Please try again later.";
            }

            $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Contact Us"));
            $body = html_entity_decode($mail['template']);
            $subject = $mail['subject'];
            $mail_body = str_replace("{name}", $_POST['first_name'] . " " . $_POST['last_name'], $body);
            $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
            $mail_body = str_replace("{email}", $_POST['email'], $mail_body);
            $mail_body = html_entity_decode($mail_body);
            smtpmailer($_POST['email'], $configs['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);

            $subject = "New Mail from " . $_POST['first_name'];
            $_POST['date'] = showdate(date("Y-m-d"));

            $response['message_info'] = array("Name" => $_POST['first_name'] . " " . $_POST['last_name'], "Email" => $_POST['email'], "Phone" => $_POST['mobile'], "State" => $_POST['subject'], "Message" => $_POST['message'], "Date" => $_POST['date'], "Source" => "App", "Course Name" => $test_name);

            $body = "<html><body><table width=100%  style='background:#fafafa; width:650px;margin:0;' cellpadding='4' cellspacing='1' border='1' class='table'>";
            foreach ($response['message_info'] as $p => $v) {
                $body .= "<tr><td>" . Ucwords($p) . "</td><td>" . $v . "</td></tr>";
            }
            $body .= "</table></html></body>";

            smtpmailer($configs['support_mail'], $_POST['email'], $_POST['first_name'], $subject, html_entity_decode($body));

            break;


        case "pay":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $_POST["user_id"] = $user_id;
                $_POST["name"] = $user['first_name'] . " " . $user['last_name'];
                $_POST["email"] = $user['email'];
                $_POST["pay_amount"] = $configs['subscription_fees'];

                $_POST['date'] = date("Y-m-d");
                $_POST['cancel_date'] = "0000-00-00";

                $_POST['cancel_subscription'] = 0;
                insertTable("creditcard", $_POST);

                $ch = curl_init('https://www.asvab-tutoring.com/online-study/mystrip/pay_confirm.php');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);

                $result = curl_exec($ch);
                curl_close($ch);
                $result = json_decode($result, TRUE);

                if ($result['status'] == "success") {
                    $response['message'] = "You have successfully paid the subscription fees. Enjoy all test.";
                } else {
                    $response['error'] = "Your payment got " . $result['status'] . ".";
                }
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "payment-success-ios":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));
            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $exp_date = date("Y-m-d", strtotime("+" . $configs['validity'] . " day"));

                updateTable("user", array('activation_status' => 'Y'), array("user_id" => $user_id));
                updateTable("subscription", array('subscription_date' => date("Y-m-d"), "expire_date" => $exp_date, "amount" => $_POST['amount'], "txn_id" => $_POST['transaction_id']), array("user_id" => $user_id));

                $_POST['course_id'] = (int)$test_id;

                insertTable("order", array('user_id' => $user_id, "amount" => $_POST['amount'], "date" => date("Y-m-d"), "exp_date" => $exp_date, "transaction_id" => $_POST['transaction_id']));

                $response['message'] = "You have successfully paid the subscription fees. Enjoy all test.";
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "payment-success-android":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));
            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $exp_date = date("Y-m-d", strtotime("+" . $configs['validity'] . " day"));

                updateTable("user", array('activation_status' => 'Y'), array("user_id" => $user_id));

                updateTable("subscription", array('subscription_date' => date("Y-m-d"), "expire_date" => $exp_date, "amount" => $_POST['amount'], "txn_id" => $_POST['orderid'], "purchaseToken" => $_POST['purchaseToken']), array("user_id" => $user_id));

                //	            insertTable("order",array('user_id'=>$user_id,"amount"=>$_POST['amount'],"date"=>date("Y-m-d"),"exp_date"=>$exp_date,"transaction_id"=>$_POST['orderid']));

                $response['message'] = "You have successfully paid the subscription fees. Enjoy all test.";
            } else {

                $response['error'] = "Token does not exist!";
            }
            break;

        case "payment-failure":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));
            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $response['message'] = "Your transaction has been failure. Please try again.";
            } else {

                $response['error'] = "Token does not exist!";
            }
            break;
        case "savecarddetail":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));
            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $_POST['date'] = date("Y-m-d");
                $_POST['cancel_date'] = "0000-00-00";
                $_POST['user_id'] = $user_id;
                $_POST['cancel_subscription'] = 0;
                insertTable("creditcard", $_POST);

                $response['message'] = "Thanks for subscription. Your card information saved successfully.Your card will be charged after 3 days. It will not charge if you cencel the subscription before 3 days from the date of subscription.";
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "cancel-subscription":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $response['isUserSubscribed'] = false;
                $flag = updateTable("user", array('activation_status' => 'N'), array("user_id" => $user_id));
                if ($flag) {
                    $response['email'] = $user['email'];
                    $response['first_name'] = $user['first_name'];
                    $response['last_name'] = $user['last_name'];
                    $response['test_id'] = $test_id;
                    $response['message'] = "Your subscription has been canceled successfully.";
                } else {
                    $response['message'] = "Your subscription hasn't been canceled. Please try again.";
                }
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "report-error":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {

                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $test_name = getFldValueById("course", "course_name", array("course_id" => $test_id));

                $error = "<b>Error Details</b><hr>";

                $error = $error . "Source : " . $test_name . " App<br>";

                if ($_POST['cat_id']) {
                    $exist_cat = getTotalRow("category", ['cat_id' => $_POST['cat_id']]);
                }
                if ($_POST['lesson_id']) {
                    $exist_lesson = getTotalRow("lesson", ['lesson_id' => $_POST['lesson_id']]);
                }
                if ($exist_cat) {
                    $cat_name = getFldValueById("category", "category_name", ['cat_id' => $_POST['cat_id']]);
                    $error = $error . "Category: " . $cat_name . "<br>";
                }
                if ($exist_lesson) {
                    $lesson_name = getFldValueById("lesson", "Lesson_name", ['lesson_id' => $_POST['lesson_id']]);
                    $error = $error . "Lesson: " . $lesson_name . "<br>";
                }
                if ($_POST['from_page']) {

                    $error = $error . "Page: " . $_POST['from_page'] . "<br>";
                }

                $error = $error . "Error: " . $_POST['message'];

                insertTable("report_error", ["user_id" => $user_id, "date" => date("Y-m-d"), "error" => $error, "status" => "Open"]);

                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Report Error"));

                $body = html_entity_decode($mail['template']);
                $subject = str_replace("{name}", $user['first_name'], $mail['subject']);

                $mail_body = str_replace("{grade}", $test_name, $body);

                $mail_body = str_replace("{lesson_name}", $lesson_name, $body);
                $mail_body = str_replace("{lesson_id}", $_POST['lesson_id'], $body);
                $mail_body = str_replace("{cat_name}", $cat_name, $body);
                $mail_body = str_replace("{cat_id}", $_POST['cat_id'], $body);

                $mail_body = str_replace("{date}", showdate("Y-m-d"), $mail_body);
                $mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);

                //                $mail_body=str_replace("{error_url}",$_POST['error_url'],$mail_body);
                $mail_body = str_replace("{message}", $error, $mail_body);

                $mail_body = "<html><body>" . html_entity_decode($mail_body) . "</body></html>";

                smtpmailer("info@asvab-tutoring.com", $user['email'], ucwords($configs['dotcom']), $subject, $mail_body);

                $response['success_msg'] = "Thanks for report this issue.Our team will do the needful.";
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "terms-and-conditions":
        case "about-us":
        case "privacy-policy":

            $response['url'] = "https://www.asvab-tutoring.com/online-study/information/" . $_POST['action'];

            break;
    }

    echo json_encode($response);
    die();
}
