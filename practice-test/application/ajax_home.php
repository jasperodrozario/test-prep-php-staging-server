<?
include("../configs/config.php");
include_once("../configs/license.php");
include_once("../configs/common.php");
if ($_SERVER['HTTP_HOST'] != "localhost") {
	include("../smtpmail.php");
}
$array = array();


if ($_POST['action']) {
	switch ($_POST['action']) {
		case "login":

            // $exits = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y", "reg_at!" => "ASVAB Tutoring (app) app"));

            $is_tutor = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y", "user_type" => "Tutor"));

            $is_referrer = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y", "user_type" => "Referrer"));

            $exits = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']),"reg_at like "=>"'%web%'"));

            if($exits){
                $user_id = getFldValueById("user", "user_id", array("email" => $_POST['email'], "password" => md5($_POST['password'])));
                $subscription_date = getFldValueById("subscription", "subscription_date", array("user_id" => $user_id));
            }

            $active_user = getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "activation_status" => "Y", "reg_at like "=>"'%web%'"));

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

                    if(empty($d['referral_code'])){
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

                    $d = getDataByJoin(array("user as u", "login as l"), "u.*,l.last_login", array("u.email" => $_POST['email'], "u.password" => md5($_POST['password']), "u.reg_at like "=>"'%web%'"), array("u.user_id" => "l.user_id"));

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

                        if ($course_id) {
                            $_SESSION['test_name'] = getFldValueById("course", "course_name", array("course_id" => $course_id));
                        }

                        if ($expire_date < date('Y-m-d')) {
                            updateTable("user", ["activation_status" => "N"], ["user_id" => $d["user_id"]]);
                            $_SESSION['status'] = "N";
                        }

                        if(empty($d['referral_code'])){
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
            } 
            else if($is_referrer){
                $user = getDataByWhere("user", "*", ['email' => $_POST['email'], "password" => md5($_POST['password'])]);

                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['name'] = $user['first_name'] . " " . $user['last_name'];
                $_SESSION['reg_date'] = $user['reg_date'];

                $array['url'] = $configs['vpath'] . "affiliate-dashboard";
            }
            else {
                $user_exist = (int)getTotalRow("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "reg_at like "=>"'%web%'"));

                if ($user_exist) {
                    $user_id = getFldValueById("user", "user_id", array("email" => $_POST['email'], "password" => md5($_POST['password'])));
                    $expire_date = getFldValueById("subscription", "expire_date", array("user_id" => $user_id));

                    if($expire_date){
                        $array['msg'] = "<p>
                                            <img src='" . $configs['vpath'] . "img/off.png' alt='inactive'> 
                                            <span style='color:red;'>
                                                Your account needs to be activated. To renew it, please 
                                                <a href='https://staging-test-prep.asvab-tutoring.com/contact-us' style='color:#007bff; text-decoration:underline;'>
                                                    contact us
                                                </a>.
                                            </span>
                                        </p>";
                    } else{
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

		case "test":

			$test_id = (int)$_SESSION['test_id'];

			if (in_array($test_id, [27, 40, 28, 38, 39])) {

				$exam_table = "asvab_exam";
				$exam_record_table = "asvab_exam_records";
			} elseif (in_array($test_id, [21, 24, 25, 26, 42])) {

				$exam_table = "ocs_exam";
				$exam_record_table = "ocs_exam_records";
			} elseif (in_array($test_id, [20, 33, 34])) {

				$exam_table = "health_care_exam";
				$exam_record_table = "health_care_exam_records";
			} else {
				$exam_table = "exam";
				$exam_record_table = "exam_records";
			}

			$correct = "0";
			$answer = getDataByWhere("question", "*", array("qid" => $_POST['question_id']));

			if ($answer['answer'] == $_POST['answer_no']) {
				$correct = 1;
			}

			$id = updateTable($exam_table, array("user_ans" => (int)$_POST['answer_no'], "iscorrect" => (int)$correct), array("qid" => $_POST['question_id'], "cat_id" => $_POST['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

			$total_answer = getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_POST['lesson_id'], "user_ans >" => 0, "is_result" => 0));

			$pending = $_POST['total'] - $total_answer;

			//$array['list']=html_entity_decode($answer['explanation']);

			$array['list'] = "";

			if ($id) {
				$array['msg'] = "<span class='badge-success'>Answer Saved</span>";
			} else {
				$array['msg'] = "<span class='badge'>Error!</span>";
			}

			$array['pending'] = "";

			if ($pending > 0) {
				if ($pending == 1 ) {
					$array['total'] = '<div class="btn btn-success practice-test-submit disabled">Submit</div>';
				} else {
					$array['total'] = '<div class="btn btn-success practice-test-submit disabled">Submit</div>';
				}
				
			} else {
				$category_name = getFldValueById("category", "category_name", array("cat_id" => $_POST['cat_id']));

				$result_id = getFldValueById($exam_table, "result_id", array("qid" => $_POST['question_id'], "cat_id" => $_POST['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

				$array['total'] = "<div class=\"btn btn-success practice-test-submit text-center\" onClick=\"javascript:window.location='" . $configs['vpath'] . "exam/result/" . $_POST['cat_id'] . "-" . seo_url($category_name) . "/" . $_POST['lesson_id'] . "/" . $result_id . "';\">Submit</div>";
			}

			break;
		case "freetest":

			$correct = "0";
			//$array['msg']="<div class='mtq_stamp mtq_wrong_stamp'>Wrong</div>";

			$answer = getDataByWhere("question_answer", "*", array("question_id" => $_POST['question_id'], "free" => 1));

			if ($answer['answer_no'] == $_POST['answer_no']) {
				$correct = 1;
			}
			//deleteData("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$_SESSION['user_id'],"set_no"=>$_POST['set_no'],"lesson_id"=>(int)$_POST['lesson_id'],"unique_id"=>$_SESSION['unique_id'],"date"=>date("Y-m-d")));

			deleteData("free_exam_score", array("question_id" => $_POST['question_id'], "cat_id" => $_POST['cat_id'], "user_id" => $_POST['user_id']));

			$id = insertTable("free_exam_score", array("question_id" => $_POST['question_id'], "cat_id" => $_POST['cat_id'], "answer_no" => $_POST['answer_no'], "user_id" => $_POST['user_id'], "date" => date("Y-m-d"), "correct" => (int)$correct));

			$total_answer = getTotalRow("free_exam_score", array("cat_id" => $_POST['cat_id'], "user_id" => $_POST['user_id']));

			$pending = 10 - $total_answer;

			//$array['list']=html_entity_decode($answer['explanation']);

			$array['list'] = "";
			if ($id) {
				$array['msg'] = "<span class='badge-success'>Answer Saved</span>";
			} else {
				$array['msg'] = "<span class='badge'>Error!</span>";
			}

			$array['pending'] = "";

			if ($pending > 0) {
				$array['total'] = "<font color=red>Still pending " . $pending . " questions to answer!</font>";
			} else {
				$array['total'] = "<div class=\"btn btn-success free-test-submit text-center\" onClick=\"javascript:window.location='https://www.asvab-tutoring.com/online-courses/freetest/score-" . $_POST['cat_id'] . "';\">View Score</div>";
			}

			break;

		case "add-bookmark":

			insertTable("homework_bookmark", array("user_id" => $_SESSION['user_id'], "ord" => (int)$_POST['ord'], "question_id" => (int)$_POST['question_id'], "story_id" => (int)$_POST['story_id'], "lesson_id" => (int)$_POST['lesson_id'], "date" => date("Y-m-d")));

			break;

		case "remove-bookmark":
			if ($_POST['story_id'] == 0) {
				deleteData("homework_bookmark", array("user_id" => $_SESSION['user_id'], "ord" => (int)$_POST['ord'], "question_id" => (int)$_POST['question_id'], "lesson_id" => (int)$_POST['lesson_id']));
			}

			break;

		case "freetest_reg":
			$_POST['date'] = date("Y-m-d");
			$_POST['status'] = "N";
			$_POST['subject'] = "freetest";
			$id = insertTable("enquiry", $_POST);
			$array['url'] = "https://www.asvab-tutoring.com/online-courses/freetest/myscore-" . $_POST['cat_id'];
			break;

		case "prediction-test":

			$test_id = (int)$_SESSION['test_id'];

			if (in_array($test_id, [27, 40, 28, 38, 39])) {

				$exam_table = "asvab_exam";
				$exam_record_table = "asvab_exam_records";
			} elseif (in_array($test_id, [21, 24, 25, 26, 42])) {

				$exam_table = "ocs_exam";
				$exam_record_table = "ocs_exam_records";
			} elseif (in_array($test_id, [20, 33, 34])) {

				$exam_table = "health_care_exam";
				$exam_record_table = "health_care_exam_records";
			} else {
				$exam_table = "exam";
				$exam_record_table = "exam_records";
			}

			$correct = "0";

			$answer = getDataByWhere("question", "*", array("qid" => $_POST['question_id']));
			if ($answer['answer'] == $_POST['answer_no']) {
				$correct = 1;
			}

			$id = updateTable($exam_table, array("exam_date" => date("Y-m-d"), "user_ans" => $_POST['answer_no'], "iscorrect" => $correct), array("qid" => $_POST['question_id'], "Predic_test_id" => $_POST['cat_id'], "cat_id" => (int)$_POST['lesson_id'], "user_id" => $_SESSION['user_id'], "is_result" => 0));

			$total_answer = getTotalRow($exam_table, array("user_ans>" => 0, "Predic_test_id" => $_POST['cat_id'], "user_id" => $_SESSION['user_id'], "is_result" => 0));
			$array['total_answer'] = $total_answer;

			$pending = $_POST['total'] - $total_answer;

			$array['list'] = "";
			if ($id) {
				$array['msg'] = "<span class='badge-success'>Answer Saved</span>";
			} else {
				$array['msg'] = "<span class='badge'>Error!</span>";
			}


			$array['pending'] = "";

			$array['ctotal'] = getTotalRow($exam_table, array("user_ans>" => 0, "Predic_test_id" => $_POST['cat_id'], "cat_id" => (int)$_POST['lesson_id'], "user_id" => $_SESSION['user_id'], "is_result" => 0));

			if ($pending == 0) {
				$cat_name = getFldValueById("predic_test", "test_name", array("Predic_test_id" => $_POST['cat_id']));

				$array['total'] = "<div class=\"btn btn-success prediction-test-submit text-center\" onClick=\"javascript:window.location='" . $configs['vpath'] . "exam/prediction-result/" . $_POST['cat_id'] . "-" . seo_url($cat_name) . "';\">Submit</div>";
			}

			break;

		case "report":
			$list = '<div id="mtq_shaded_item_msg-1" class="mtq_shaded_item_msg">Shaded items are complete.</div>
			<table id="mtq_question_list_container-1" class="mtq_question_list_container">
			<tr>';
			if (!$_POST['set_no']) {
				$_POST['set_no'] = 1;
			}
			$total = $_POST['total'];
			$questions = getQuery("questions", "id", array("set_no" => $_POST['set_no'], "cat_id" => $_POST['cat_id']));
			$answers = array();
			$i = 0;
			foreach ($questions as $q) {
				$i++;
				$yes = getTotalRow("running_exam", array("user_id" => $_SESSION['user_id'], "question_id" => $q['id'], "set_no" => $_POST['set_no']));
				if ($yes) {
					$answers[] = $i;
				}
			}

			for ($i = 1; $i <= $total; $i++) {
				$background = "";
				if (in_array($i, $answers)) {
					$background = "style='background-color:green';";
				}
				$list .= "<td  " . $background . " class='mtq_list_item' onclick='selectIt(" . $i . ");'>" . $i . "</td>";
				if (!($i % 5)) {
					$list .= "</tr><tr>";
				}
			}

			$cat_name = getFldValueById("category", "cat_name", array("cat_id" => $_POST['cat_id']));

			$list .= "</table><div class='clearfix'><br></div>";

			if ($_POST['lesson_id']) {
				$name = getFldValueById("lesson", "name", array("id" => $_POST['lesson_id']));
				$list .= "<div class=\"btn btn-info text-center\" onClick=\"javascript:window.location='" . $configs['vpath'] . "lesson/" . $_POST['cat_id'] . "-" . $_POST['lesson_id'] . "-" . seo_url($name) . "/practice-test';\">Return</div>";
			} else {
				$list .= "<div class=\"btn btn-info text-center\" onClick=\"javascript:window.location='" . $configs['vpath'] . "exam/" . $_POST['cat_id'] . "-" . seo_url($cat_name) . "/set-" . $_POST['set_no'] . "';\">Return</div>";
			}

			$list .= "<div class=\"btn btn-success report-submit text-center\" onClick=\"javascript:window.location='" . $configs['vpath'] . "exam/" . $_POST['cat_id'] . "-" . seo_url($cat_name) . "/set-" . $_POST['set_no'] . "/" . $_SESSION['unique_id'] . "/result';\">Submit</div>
			
			</div>";

			$array['list'] = $list;

			break;
		case "get-exam":

			if (!$_POST['set_no']) {
				$_POST['set_no'] = 1;
			}
			$list = '<div id="big-image">';
			$i = 0;
			$total = getTotalRowsByJoin(array("questions as q", "question_answer as qa"), array("q.set_no" => $_POST['set_no'], "q.cat_id" => $_POST['cat_id'], "q.homework" => 0), array("qa.question_id" => "q.id"));
			$question_test = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array("q.set_no" => $_POST['set_no'], "q.cat_id" => $_POST['cat_id'], "q.homework" => 0), array("qa.question_id" => "q.id"));
			$answers = array();

			foreach ($question_test as $a) {
				if ($_POST['cat_id'] == 18) {
					$answers[$a['question_id']] = getFldValueById("answers", "answer", array("question_id" => $a['question_id'], "answer_no" => $a['answer_no']));
				} else {
					$answers[$a['question_id']] = getQuery("answers", "*", array("question_id" => $a['question_id']));
				}
			}

			foreach ($question_test as $f) {
				$i++;
				$list .= '<item>
				<table class="table table-borderless">
				<tr><td ><span class="badge">' . $i . '</span></td><td colspan=2>' . html_entity_decode($f['question']) . "</td></tr>";
				$j = 0;
				foreach ($answers[$f['question_id']] as $a) {
					$j++;
					$list .= "<tr><td width=5>" . $alphabet[$j] . "</td><td width=5><input type='radio' name='answer' id='" . $i . "' onClick=\"getAnswer('" . $f['question_id'] . "','" . $j . "');\" ></td><td class='text-align:left'>" . html_entity_decode($a['answer']) . "</td></tr>";
				}
				$list .= "</table>";
				$list .= "</item>";
			}
			$list .= '</div>
         	<div class="small-images" style="display: none">';
			for ($i = 1; $i <= $total; $i++) {
				$class = '';
				if ($i == 1) {
					$class = 'class="selected"';
				}

				$list .= "<item " . $class . ">" . $i . "</item>";
			}
			$list .= "</div>
			   
			   <div class='row'>
			   	<div class='col-md-2'>
			   		<div id=\"prev\"   class='prev browse left mtq_css_back_button mtq_listrow_button pull-left'>&#8592;</div>
			 		
			   	</div>
			   	<div class='col-md-8 text-center'>
			   		<center>There are " . $total . " questions to complete.</center>
			   		<div class=\"btn btn-info\" onClick=\"javascript:getReport();\">List </div>
					</div>
			   	<div class='col-md-2'>
			   		<div id=\"next\"   class='next browse right mtq_css_next_button mtq_listrow_button pull-right'>&#8594;</div>
					</div>
			   </div> 
			   <div id='list'></div>";

			$array['list'] = $list;

			break;
		case "edit-profile":
			$_POST['country_id'] = (int)$_POST['country_id'];
			$_POST['state_id'] = (int)$_POST['state_id'];
			$_POST['city_id'] = (int)$_POST['city_id'];
			$_POST['zip_code'] = (int)$_POST['zip_code'];

			updateTable("user", $_POST, array("user_id" => $_SESSION['user_id']));
			$array['msg'] = $configs['success_msg'];
			break;

		case "contact":

			$_POST['date'] = date("Y-m-d");
			$_POST['status'] = "N";
			$_POST['user_id'] = (int)$_POST['user_id'];
			$_POST['test_id'] = (int)$_POST['test_id'];

			insertTable("enquiry", $_POST);

			$array['msg'] = "<h4>Answer Saved</h4>";

			$mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Contact Us"));

			$body = html_entity_decode($mail['template']);
			$subject = $mail['subject'];

			$mail_body = str_replace("{name}", $_POST['fname'] . " " . $_POST['lname'], $body);
			$mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
			$mail_body = str_replace("{email}", $_POST['email'], $mail_body);
			$mail_body = html_entity_decode($mail_body);
			$mailbody = "<table width=100%>";
			foreach ($_POST as $p => $v) {
				$mailbody .= "<tr><td>" . Ucwords($p) . "</td><td>" . $v . "</td></tr>";
			}
			$mailbody .= "<tr><td>Source</td><td>Officers Web</td></tr>";
			$mailbody = "</table>";

			smtpmailer($_POST['email'], $configs['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
			smtpmailer($configs['admin_mail'], $_POST['email'], $_POST['name'], "New Contact from " . $_POST['first_name'], $mailbody);

			break;
		case "report-error":

			$test_id = (int)$_SESSION['test_id'];

			if (in_array($test_id, [27, 40, 28, 38, 39])) {

				$exam_table = "asvab_exam";
				$exam_record_table = "asvab_exam_records";
			} elseif (in_array($test_id, [21, 24, 25, 26, 42])) {

				$exam_table = "ocs_exam";
				$exam_record_table = "ocs_exam_records";
			} elseif (in_array($test_id, [20, 33, 34])) {

				$exam_table = "health_care_exam";
				$exam_record_table = "health_care_exam_records";
			} else {
				$exam_table = "exam";
				$exam_record_table = "exam_records";
			}

			$error = "<b>Error Details</b><hr>";
			$error = $error . "From Page: " . $_POST['url'] . "<br>";
			if ($_POST['cat_id'] && $_POST['in'] != "prediction-test") {
				$exist_cat = getTotalRow("category", ['cat_id' => $_POST['cat_id']]);
			}
			if ($_POST['lesson_id'] && $_POST['in'] != "prediction-test") {
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
			if ($_POST['in']) {
				if ($_POST['in'] == "practice-test") {
					$qid_list = getQuery($exam_table, "*", ["lesson_id" => $_POST['lesson_id'], "user_id" => $_SESSION['user_id'], "cat_id" => $_POST['cat_id'], "is_result" => 0]);
					$i = 1;
					foreach ($qid_list as $q) {
						if ($i == (int)$_POST['question_order']) {
							$question_id = $q['qid'];
							break;
						}
						$i++;
					}
				} else if ($_POST['in'] == "homework") {
					$qid_list = getQuery("lesson_questions", "*", ["lesson_id" => $_POST['lesson_id'], "cat_id" => $_POST['cat_id'], "HW" => 1]);
					$i = 1;
					foreach ($qid_list as $q) {
						if ($i == (int)$_POST['hw_order']) {
							$question_id = $q['qid'];
							break;
						}
						$i++;
					}
					$_POST['question_order'] = $_POST['hw_order'];
				} else if ($_POST['in'] == "prediction-test") {
					$qid_list = getQuery($exam_table, "*", ["cat_id" => $_POST['lesson_id'], "user_id" => $_SESSION['user_id'], "Predic_test_id" => $_POST['cat_id'], "is_result" => 0]);
					$i = 1;
					foreach ($qid_list as $q) {
						if ($i == (int)$_POST['question_order']) {
							$question_id = $q['qid'];
							break;
						}
						$i++;
					}

					$pred_name = getFldValueById("predic_test", "test_name", ['Predic_test_id' => $_POST['cat_id']]);
					$error = $error . "From Page: " . $pred_name . "(" . $_POST['in'] . ")<br>";

					$cat_name = getFldValueById("category", "category_name", ['cat_id' => $_POST['lesson_id']]);
					$error = $error . "Category: " . $cat_name . "<br>";
				}
				$error = $error . "Question Order: " . $_POST['question_order'] . "<br>Question ID: " . $question_id . "<br>";
			}

			$error = $error . "Error: " . $_POST['message'];

			//			$error="<b>Error Details</b><hr>Category: " . $cat_name . "<br>Lesson: " . $lesson_name . "<br>Question Order:".$_POST['question_order']."<br>Question ID:".$question_id."<br>Error:".$_POST['message'];

			insertTable("report_error", ["user_id" => $_SESSION["user_id"], "date" => date("Y-m-d"), "error" => $error, "status" => "Open"]);

			$mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Report Error"));

			$body = html_entity_decode($mail['template']);
			$subject = str_replace("{name}", $_POST['name'], $mail['subject']);

			$mail_body = str_replace("{grade}", $_SESSION["test_name"], $body);

			$mail_body = str_replace("{lesson_name}", $_POST['lesson_name'], $body);
			$mail_body = str_replace("{lesson_id}", $_POST['lesson_id'], $body);
			$mail_body = str_replace("{cat_name}", $cat_name, $body);
			$mail_body = str_replace("{cat_id}", $_POST['cat_id'], $body);

			$mail_body = str_replace("{date}", showdate("Y-m-d"), $mail_body);
			$mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);


			$mail_body = str_replace("{error_url}", $_POST['error_url'], $mail_body);
			$mail_body = str_replace("{message}", $error, $mail_body);

			$mail_body = "<html><body>" . html_entity_decode($mail_body) . "<br>Question id: " . $question_id . " <br>Source : ASVAB Test Prep WEB</body></html>";
			if ($_SERVER['HTTP_HOST'] != "localhost") {
				smtpmailer("info@asvab-tutoring.com", $_POST['email'], ucwords($configs['dotcom']), $subject, $mail_body);
			}
			$array['success'] = "<h3>Thanks for report this issue.Our team will do the needful.</h3>";
			break;

		case "forgot-password":

			$exists = getTotalRow("login", array("email" => $_POST['email']));

			if ($exists) {
				$d = getDataByWhere("login", "*", array("email" => $_POST['email']));

				$devider = date("Ymd");
				$password_link = $configs['vpath'] . "create-password/" . ($d['user_id'] * $devider);

				//updateTable("login",array("password"=>md5($new_password)),array("user_id"=>$d['user_id']));


				$mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Forgot Password"));

				$body = html_entity_decode($mail['template']);
				$subject = $mail['subject'];

				$mail_body = str_replace("{name}", $name, $body);

				$mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
				$mail_body = str_replace("{email}", $_POST['email1'], $mail_body);
				$mail_body = str_replace("{password_link}", $password_link, $mail_body);
				$mail_body = html_entity_decode($mail_body);
				if ($_SERVER['HTTP_HOST'] != "localhost") {
					smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $mail_body);
				}
				$array['success'] = "Please check your email to reset password.<br><br>";
			} else {
				$array['error'] = "<font color=red>Invalid Email Id!</font>";
			}

			break;
		case "create-password":

			if ($_POST['password'] != $_POST['conf_password']) {
				$array['error'] = "<font color=red><img src='" . $configs['vpath'] . "img/off.png'> Password and confirm password does not match!</font>";
				$array['success'] = '';
			} else {
				$devider = date("Ymd");
				$user_id = ($_POST['user_id'] / $devider);

				$exist = getTotalRow("user", array("user_id" => $user_id));
				if ($exist) {

					updateTable("user", array("password" => md5($_POST['password'])), array("user_id" => $user_id));
					$array['success'] = "<img src='" . $configs['vpath'] . "img/on.png'> Your new password has been updated.<br>";
				} else {
					$array['error'] = "<font color=red><img src='" . $configs['vpath'] . "img/off.png'> Invalid  Id.</font>";
					$array['success'] = '';
				}
			}
			break;
		case "contact":

			if ($_SESSION["captcha_code"] != $_POST['captchatext']) {
				$array['msg'] = "<font color=#FF0000>Error in Security code.</font>";
			} else {
				$_POST['date'] = date("Y-m-d");
				$_POST['status'] = "Y";
				insertTable("enquiry", $_POST);
				$array['msg'] = "";
				$array['list'] = "<h4>Answer Saved</h4>";

				$mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Contact Us"));

				$body = html_entity_decode($mail['template']);
				$subject = $mail['subject'];

				$mail_body = str_replace("{name}", $_POST['fname'] . " " . $_POST['lname'], $body);
				$mail_body = str_replace("{dotcom}", ucwords($configs['dotcom']), $mail_body);
				$mail_body = str_replace("{email}", $_POST['email'], $mail_body);
				$mail_body = html_entity_decode($mail_body);
				$mailbody = "<table width=100%>";
				foreach ($_POST as $p => $v) {
					$mailbody .= "<tr><td>" . Ucwords($p) . "</td><td>" . $v . "</td></tr>";
				}
				$mailbody .= "<tr><td>Source</td><td>Officers Web</td></tr>";
				$mailbody = "</table>";

				if ($_SERVER['HTTP_HOST'] != "localhost") {
					// smtpmailer($_POST['email'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  
					smtpmailer($configs['admin_mail'], $_POST['email'], $_POST['name'], "New Contact from " . $_POST['first_name'], $mailbody);
				}
			}
			break;

			case "view-homework":
				$userId   = (int)($_SESSION['user_id'] ?? 0);
				$courseId = (int)($_SESSION['test_id'] ?? 0);

				$ord      = (int)($_POST['ord'] ?? 0);          // non-story: question order; story pages: story ord
				$qid      = (int)($_POST['question_id'] ?? 0);
				$lessonId = (int)($_POST['lesson_id'] ?? 0);
				$storyId  = (int)($_POST['story_id'] ?? 0);

				if ($userId && $courseId && $lessonId && $qid) {
					// Check if a view already exists for this user+question+lesson+course
					$exists = (int) getTotalRow("homework_view", array(
						"user_id"     => $userId,
						"question_id" => $qid,
						"lesson_id"   => $lessonId,
						"course_id"   => $courseId
					));

					// Insert only if NOT exists
					if ($exists === 0) {
						insertTable("homework_view", array(
							"user_id"     => $userId,
							"ord"         => $ord,
							"question_id" => $qid,
							"story_id"    => $storyId,
							"lesson_id"   => $lessonId,
							"date"        => date("Y-m-d"),
							"course_id"   => $courseId
							
						));
					}
				}

				// Total viewed for this lesson & course (per user)
				$total = (int) getTotalRow("homework_view", array(
					"user_id"   => $userId,
					"lesson_id" => $lessonId,
					"course_id" => $courseId
				));

				$array = array("ok" => true, "total" => $total);
			break;
	}
	//print_r($array);
	echo json_encode($array);
	flush();
}


if ($_POST['state_id'] && $_POST['get_city_option']) {
	$citys = getQuery("city", "*", array("state_id" => $_POST['state_id']), "name");
	echo "<option value=''>Select City</option</option>";
	foreach ($citys as $c) {
		echo "<option value='" . $c['id'] . "'>" . $c['name'] . "</option</option>";
	}
}
