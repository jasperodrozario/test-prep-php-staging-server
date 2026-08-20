<?

// Live Apps: ASVAB Tutoring, ASVAB Prep, ASVAB GT 

file_put_contents(__DIR__ . '/app-api-debug.log', date('Y-m-d H:i:s') . " | IP=" . ($_SERVER['REMOTE_ADDR'] ?? '') . " | METHOD=" . ($_SERVER['REQUEST_METHOD'] ?? '') . " | URI=" . ($_SERVER['REQUEST_URI'] ?? '') . " | POST=" . json_encode($_POST) . " | RAW=" . file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

header('Content-Type: application/json');
include("configs/config.php");
include("configs/common.php");
include("configs/license.php");
include("configs/array.php");
include("smtpmail.php");

function getExamTables($test_id)
{
    // Ensure $test_id is an integer
    $test_id = (int)$test_id;

    // Determine the appropriate tables based on test ID
    if (in_array($test_id, [27, 40, 28, 38, 39])) {
        return [
            'exam_table' => 'asvab_exam',
            'exam_record_table' => 'asvab_exam_records',
        ];
    } elseif (in_array($test_id, [21, 24, 25, 26])) {
        return [
            'exam_table' => 'ocs_exam',
            'exam_record_table' => 'ocs_exam_records',
        ];
    } elseif (in_array($test_id, [20, 33, 34])) {
        return [
            'exam_table' => 'health_care_exam',
            'exam_record_table' => 'health_care_exam_records',
        ];
    } else {
        return [
            'exam_table' => 'exam',
            'exam_record_table' => 'exam_records',
        ];
    }
}

// Utility function to calculate composite score
function calculate_composite_score($categories, $cat_scores, $score_array)
{
    $correct = 0;
    $total = 0;
    foreach ($categories as $cat) {
        $correct += $cat_scores[$cat]['correct'];
        $total += $cat_scores[$cat]['total_question'];
    }
    $percentage = ($total > 0) ? round(($correct / $total) * 100) : 0;
    return $score_array[$percentage];
}

function cleanArray($data)
{
    return array_filter(
        $data,
        function ($value, $key) {
            return !is_numeric($key) && $value !== null;
        },
        ARRAY_FILTER_USE_BOTH
    );
}

/** 1 = may take a prediction test; 0 = subscribe required. */
function getCanTakePredTestFlag($user_id)
{
    $has_submitted = getTotalRow("result", array(
        "user_id" => (int)$user_id,
        "Predic_test_id >" => 0,
        "score >" => -1
    ));

    if (!$has_submitted) {
        return 1;
    }

    $activation_status = getFldValueById("user", "activation_status", array("user_id" => (int)$user_id));

    if ($activation_status == 'N') {
        return 0;
    }

    return 1;
}

function predictionTestSubmissionLockedResponse(&$response)
{
    $response['error'] = 'Subscription required to take another prediction test.';
    $response['can_take_pred_test'] = 0;
}

$option_no = 4;

$setting = getDataByWhere("setting", "*", array("id" => 1));

$configs = array_merge($configs, $setting);

$response = array();

if ($_POST['action']) {
    switch ($_POST['action']) {

        case "pre-reg-info":

            $courses = getQuery("course", "*", array("app" => 1), "ord");
            $course_list = [];
            foreach ($courses as $c) {
                if (in_array($c['course_id'], [49, 48, 47, 46])) {
                    $course_list[] = ["course_id" => $c['course_id'], "course_name" => $c['course_name']];
                }
            }
            $response["course_list"] = $course_list;

            break;

        case "get-sub-course-options":
            // Unauthenticated: apps POST their asvab_course course_id to list active sub-courses/variants.
            $list = array();
            $parent_course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;

            if ($parent_course_id <= 0) {
                $response['error'] = "Invalid course_id";
                $response['list'] = $list;
                break;
            }

            $parent_exists = getTotalRow("asvab_course", array("course_id" => $parent_course_id));

            if ($parent_exists) {
                $variants = getQuery(
                    "asvab_course_variants",
                    "*",
                    array("parent_asvab_course_id" => $parent_course_id, "is_active" => 1),
                    "ord"
                );
                if ($variants) {
                    foreach ($variants as $v) {
                        $list[] = array(
                            "main_course_id" => (int)$v['main_course_id'],
                            "label" => $v['label']
                        );
                    }
                }
            } else {
                $response['error'] = "Course not found";
            }

            $response['list'] = $list;
            break;

        case "social-register":

            $provider = $_POST['provider']; // 'google' or 'apple'
            $idToken = $_POST['id_token'];
            $socialId = ""; // This will store the permanent unique ID from Google/Apple
            $userData = [];

            insertTable("testing", array("test_area" => $_POST['id_token']));

            // Step 1: Decode token & extract socialId + user info
            if ($provider == "google") {
                $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $idToken;
                $responseJson = file_get_contents($url);
                $providerData = json_decode($responseJson, true);

                if (!isset($providerData['sub'])) {
                    $response = [
                        "status" => 201,
                        "isSuccess" => false,
                        "message" => "Invalid Google token."
                    ];
                    echo json_encode($response);
                    exit;
                }

                $n = getTotalRow("user", array("email" => $providerData['email']));
                $n_token = getTotalRow("login", array("social_token" => $providerData['sub']));

                if ($n && !$n_token) {
                    $response["status"] = 201;
                    $response["isSuccess"] = false;
                    $response["message"] = "This email id already exits!";

                    echo json_encode($response);
                    exit;
                }

                $socialId = $providerData['sub']; // ✅ stable Google ID
                $userData = [
                    "email" => $providerData['email'] ?? '',
                    "first_name" => $providerData['given_name'] ?? '',
                    "last_name" => $providerData['family_name'] ?? ''
                ];
            } elseif ($provider == "apple") {
                $jwtParts = explode(".", $idToken);
                $payload = json_decode(base64_decode(strtr($jwtParts[1], '-_', '+/')), true);

                if (!isset($payload['sub'])) {
                    $response = [
                        "status" => 201,
                        "isSuccess" => false,
                        "message" => "Invalid Apple token."
                    ];
                    echo json_encode($response);
                    exit;
                }

                $socialId = $payload['sub']; // ✅ stable Apple ID
                $userData = [
                    "email" => $payload['email'] ?? '',
                    "first_name" => $_POST['first_name'] ?? '',
                    "last_name" => $_POST['last_name'] ?? ''
                ];
            }

            // Step 2: Check if user exists
            $does_exists = (int)getTotalRow("login", ["social_token" => $socialId, "provider" => $provider]);

            if ($does_exists) {
                // Existing user → Login
                $token = getToken();

                $d = getDataByJoin(
                    ["user as u", "login as l"],
                    "u.*,l.last_login",
                    ["l.social_token" => $socialId],
                    ["u.user_id" => "l.user_id"]
                );

                $course_id = getFldValueById("subscription", "course_id", ["user_id" => $d["user_id"]]);
                $expire_date = getFldValueById("subscription", "expire_date", ["user_id" => $d["user_id"]]);

                updateTable("login", ["auth_token" => $token, "last_login" => date("Y-m-d H:i:s")], ["user_id" => $d["user_id"]]);

                if ($course_id) {
                    $course_name = getFldValueById("course", "course_name", ["course_id" => $course_id]);
                }

                if ($expire_date < date('Y-m-d')) {
                    updateTable("user", ["activation_status" => "N"], ["user_id" => $d["user_id"]]);
                }

                $d['paid'] = ($d['activation_status'] == 'Y');

                $response["userDetails"] = [
                    "userId" => $d["user_id"],
                    "authorizationToken" => $token,
                    "userName" => $d['email'],
                    "name" => $d['first_name'] . " " . $d['last_name'],
                    "email" => $d['email'],
                    "mobile" => $d['mobile'],
                    "course_id" => $course_id,
                    "isUserSubscribed" => $d['paid']
                ];
                $response["status"] = 200;
                $response["isSuccess"] = true;
                $response["message"] = "Login Success";
            } else {
                // New user → Register
                $timezone = new DateTimeZone('America/New_York');
                $date = new DateTime('now', $timezone);
                $formatted_date = $date->format('Y-m-d');
                $formatted_date_time = $date->format('Y-m-d H:i:s');

                $course_name = getFldValueById("course", "course_name", ["course_id" => $_POST['course_id']]);

                $user_id = insertTable("user", [
                    "email" => $userData['email'],
                    "first_name" => $userData['first_name'],
                    "last_name" => $userData['last_name'],
                    "user_type" => "Student",
                    "reg_date" => $formatted_date,
                    "activation_status" => "N",
                    "reg_at" => $course_name . " app"
                ]);

                if ($user_id) {
                    $authorizeToken = getToken();

                    insertTable("login", [
                        "user_id" => $user_id,
                        "last_login" => $formatted_date_time,
                        "social_token" => $socialId,
                        "provider" => $provider,
                        "auth_token" => $authorizeToken
                    ]);

                    insertTable("subscription", [
                        "course_id" => $_POST['course_id'],
                        "user_id" => $user_id
                    ]);

                    // -------------------------
                    // Send STYLED registration email
                    // -------------------------
                    $mail = getDataByWhere("mailsetup", "*", ["mail_type" => "Register"]);

                    if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

                        $timestamp = date("F j, Y \a\\t g:i A");
                        $body = $mail['template'];

                        // Replace placeholders
                        $body = str_replace("{name}", $userData['first_name'] . " " . $userData['last_name'], $body);
                        $body = str_replace("{first_name}", $userData['first_name'], $body);
                        $body = str_replace("{activation_code}", $activation_code, $body);
                        $body = str_replace("{email}", $userData['email'], $body);
                        $body = str_replace("{timestamp}", $timestamp, $body);
                        $body = str_replace("{login_url}", "https://staging-test-prep.asvab-tutoring.com/login", $body);

                        // TRIPLE DECODE
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                        // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                        $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                        // ASSETS
                        $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                        $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";

                        // LOAD STYLED EMAIL TEMPLATE
                        $inner_body = $body;
                        $footer_reason = "You received this because you registered for an account.";
                        ob_start();
                        include __DIR__ . '/email-layouts/standard-wrapper.php';
                        $styled_body = ob_get_clean();

                        // Apply inline styles
                        $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                        $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                        $styled_body = str_replace('<a', '<a style="color: #004a99; text-decoration: underline;"', $styled_body);
                        $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                        $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);
                        $styled_body = str_replace('<br/>', '<br style="display: block;">', $styled_body);

                        $subject = $mail['subject'];
                        smtpmailer($userData['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $styled_body);
                    }

                    updateTable("login", ["auth_token" => $authorizeToken], ["user_id" => $user_id]);

                    $response = [
                        "status" => 200,
                        "isSuccess" => true,
                        "message" => "You are successfully registered with us. Please check your email.",
                        "userDetails" => [
                            "userId" => (string)$user_id,
                            "authorizationToken" => $authorizeToken,
                            "email" => $userData['email'],
                            "userName" => $userData['email'],
                            "name" => $userData['first_name'] . " " . $userData['last_name'],
                            "mobile" => "",
                            "course_id" => $_POST['course_id'],
                            "isUserSubscribed" => false
                        ]
                    ];
                } else {
                    $response = [
                        "status" => 500,
                        "isSuccess" => false,
                        "message" => "Some Error! Try Later."
                    ];
                }
            }
            break;

        case "check-missing-user-info-for-social":

            $user_id = $_POST['user_id'];
            $userData = [];

            $user = getDataByWhere("user", "*", ["user_id" => $user_id]);

            if ($user) {

                $index_pos = strpos($user['email'], '@privaterelay.appleid.com');

                if ($index_pos) {
                    $isMaskedEmail = true;
                } else {
                    $isMaskedEmail = false;
                }

                if (empty($user['first_name']) || empty($user['last_name']) || empty($user['email']) || empty($user['mobile'])) {
                    $userData = [
                        "email" => $user['email'] ?? '',
                        "first_name" => $user['first_name'] ?? '',
                        "last_name" => $user['last_name'] ?? '',
                        "mobile" => $user['mobile'] ?? ''
                    ];
                    $response = [
                        "status" => 200,
                        "isSuccess" => true,
                        "has_complete_info" => false,
                        "message" => "User info incomplete.",
                        "isMaskedEmail" => $isMaskedEmail,
                        "canEditEmail" => $isMaskedEmail,
                        "userData" => $userData
                    ];
                } else {
                    $response = [
                        "status" => 200,
                        "isSuccess" => true,
                        "has_complete_info" => true,
                        "message" => "User info complete.",
                        "userData" => $userData
                    ];
                }
            } else {
                $response = [
                    "status" => 404,
                    "isSuccess" => false,
                    "message" => "User not found."
                ];
            }

            break;

        case "register":

            $n = getTotalRow("user", array("email" => $_POST['email'], "activation_status!" => "D"));
            if ($_POST['password'] != $_POST['confirm_password']) {
                $response["status"] = 201;
                $response["isSuccess"] = false;
                $response["message"] = "Password & Confirm password doesn't match!";
            } else if ($n) {
                $response["status"] = 201;
                $response["isSuccess"] = false;
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

                    // -------------------------
                    // Send STYLED registration email
                    // -------------------------
                    $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Register"));

                    if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

                        $timestamp = date("F j, Y \a\\t g:i A");
                        $body = $mail['template'];

                        // Replace placeholders
                        $body = str_replace("{name}", $_POST['first_name'] . " " . $_POST['last_name'], $body);
                        $body = str_replace("{first_name}", $_POST['first_name'], $body);
                        $body = str_replace("{activation_code}", $activation_code, $body);
                        $body = str_replace("{email}", $_POST['email'], $body);
                        $body = str_replace("{timestamp}", $timestamp, $body);
                        $body = str_replace("{login_url}", "https://staging-test-prep.asvab-tutoring.com/login", $body);

                        // TRIPLE DECODE
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                        // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                        $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                        // ASSETS
                        $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                        $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";

                        // LOAD STYLED EMAIL TEMPLATE
                        $inner_body = $body;
                        $footer_reason = "You received this because you registered for an account.";
                        ob_start();
                        include __DIR__ . '/email-layouts/standard-wrapper.php';
                        $styled_body = ob_get_clean();

                        // Apply inline styles
                        $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                        $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                        $styled_body = str_replace('<a', '<a style="color: #004a99; text-decoration: underline;"', $styled_body);
                        $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                        $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);
                        $styled_body = str_replace('<br/>', '<br style="display: block;">', $styled_body);

                        $subject = $mail['subject'];
                        smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $styled_body);
                    }

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

            $exists = getTotalRowsByJoin(array("user as u", "subscription as s"), array("u.email" => $_POST['email'], "u.password" => md5($_POST['password']), "u.activation_status!" => "D", "u.reg_at like " => "'%app%'", "[or]" => "s.course_id in(49, 48, 47, 46)"), array("u.user_id" => "s.user_id"));

            if ($exists) {
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
                    "first_name" => $d['first_name'],
                    "last_name" => $d['last_name'],
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
                $response["isSuccess"] = false;
                $response["message"] = "Login failed!";
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

                // $password_link = str_replace("/practice-test", "", $password_link);

                // -------------------------
                // Send STYLED forgot password email
                // -------------------------
                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Forgot Password"));

                if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

                    $timestamp = date("F j, Y \a\\t g:i A");
                    $body = $mail['template'];

                    // Replace placeholders
                    $body = str_replace("{name}", $name, $body);
                    $body = str_replace("{first_name}", $first_name, $body);
                    $body = str_replace("{email}", $_POST['email'], $body);
                    $body = str_replace("{timestamp}", $timestamp, $body);
                    $body = str_replace("{password_link}", $password_link, $body);

                    // TRIPLE DECODE
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                    $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                    // ASSETS
                    $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                    $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";

                    // LOAD STYLED EMAIL TEMPLATE
                    $inner_body = $body;
                    $footer_reason = "You received this because you requested a password reset link.";
                    ob_start();
                    include __DIR__ . '/email-layouts/standard-wrapper.php';
                    $styled_body = ob_get_clean();

                    // Apply inline styles
                    $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                    $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                    $styled_body = str_replace('<a', '<a style="color: #004a99; text-decoration: underline;"', $styled_body);
                    $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                    $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);
                    $styled_body = str_replace('<br/>', '<br style="display: block;">', $styled_body);

                    $subject = $mail['subject'];
                    if ($_SERVER['HTTP_HOST'] != "localhost") {
                        smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $styled_body);
                        $response['message'] = "Please check your email to reset password.";
                        $response['password_link'] = $password_link;
                    }
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
                $user_name = $d['first_name'] . " " . $d['last_name'];
                $first_name = $d['first_name'];

                $max_length = 6;
                $devider = date("Ymd");
                $password_code = ($d['user_id'] * $devider) % pow(10, $max_length);

                // Pad with leading zeros to ensure 6 digits
                $password_code = str_pad($password_code, $max_length, '0', STR_PAD_LEFT);

                // -------------------------
                // Send STYLED password reset email
                // -------------------------
                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Password Reset"));

                if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

                    $body = $mail['template'];

                    // Replace placeholders
                    $body = str_replace("{first_name}", $first_name, $body);
                    $body = str_replace("{email_address}", $_POST['email'], $body);
                    $body = str_replace("{verification_code}", $password_code, $body);

                    // TRIPLE DECODE (SAME AS TUTOR-REQUEST)
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                    $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                    // ASSETS
                    $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                    $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";

                    // LOAD STYLED EMAIL TEMPLATE
                    $inner_body = $body;
                    $footer_reason = "You received this because you requested a password reset code.";
                    ob_start();
                    include __DIR__ . '/email-layouts/standard-wrapper.php';
                    $styled_body = ob_get_clean();

                    // Apply inline styles
                    $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                    $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                    $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                    $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);

                    $subject = str_replace("{email_address}", $_POST['email'], $mail['subject']);

                    if ($_SERVER['HTTP_HOST'] != "localhost") {
                        smtpmailer($_POST['email'], "info@asvab-tutoring.com", "ASVAB Tutoring", $subject, $styled_body);
                    }
                }

                $response['message'] = "Please check your email to reset password.";
                $response['password_code'] = $password_code;
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

                        // -------------------------
                        // Send STYLED password reset success email
                        // -------------------------
                        $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Change Password"));

                        if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

                            $timestamp = date("F j, Y \a\\t g:i A");
                            $reset_link = "https://staging-test-prep.asvab-tutoring.com/forgot-password";
                            $user_name = $user['first_name'] . " " . $user['last_name'];
                            $body = $mail['template'];

                            // Replace placeholders
                            $body = str_replace("{name}", $user_name, $body);
                            $body = str_replace("{first_name}", $user['first_name'], $body);
                            $body = str_replace("{email}", $user['email'], $body);
                            $body = str_replace("{timestamp}", $timestamp, $body);
                            $body = str_replace("{reset_link}", $reset_link, $body);

                            // TRIPLE DECODE
                            $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                            // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                            $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                            // ASSETS
                            $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                            $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";

                            // LOAD STYLED EMAIL TEMPLATE
                            $inner_body = $body;
                            $footer_reason = "You received this because your password was successfully reset.";
                            ob_start();
                            include __DIR__ . '/email-layouts/standard-wrapper.php';
                            $styled_body = ob_get_clean();

                            // Apply inline styles
                            $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                            $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                            $styled_body = str_replace('<a', '<a style="color: #004a99; text-decoration: underline;"', $styled_body);
                            $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                            $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);
                            $styled_body = str_replace('<br/>', '<br style="display: block;">', $styled_body);

                            $subject = $mail['subject'];
                            // Replace email placeholder in subject if present
                            $subject = str_replace("{email}", $email, $subject);

                            smtpmailer($user['email'], $setting['support_mail'], ucwords($configs['dotcom']), $subject, $styled_body);
                        }

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
                    $can_take_pred_test = getCanTakePredTestFlag($user_id);

                    $cat_list = getQuery("category", "*", array("course_id" => $test_id, "online" => "Y", "[or]"=>"shortcode in ('Math', 'FPT Math')"), "category_order");

                    if ($test_id == 24) {
                        $idx = 0;
                        foreach ($cat_list as $c) {
                            $list[(string)$idx++] = ["id" => $c['cat_id'], "is_pred_test" => 0, "name" => $c['category_name'], "icon" => $configs['vpath'] . $icon];
                        }

                        $predic_list = getQuery("predic_test", "*", array("course_id" => $test_id, "online" => "Y"), "test_order");

                        foreach ($predic_list as $p) {
                            $list[] = [
                                "id" => $p['Predic_test_id'],
                                "is_pred_test" => 1,
                                "name" => $p['test_name'],
                                "icon" => $configs['vpath'] . $icon,
                                "can_take_pred_test" => $can_take_pred_test
                            ];
                        }
                    } else {
                        foreach ($cat_list as $c) {
                            $list[] = ["id" => $c['cat_id'], "is_pred_test" => 0, "name" => $c['category_name'], "icon" => $configs['vpath'] . $icon];
                        }

                        $predic_list = getQuery("predic_test", "*", array("course_id" => $test_id, "online" => "Y"), "test_order");

                        foreach ($predic_list as $p) {
                            $list[] = [
                                "id" => $p['Predic_test_id'],
                                "is_pred_test" => 1,
                                "name" => $p['test_name'],
                                "icon" => $configs['vpath'] . $icon,
                                "can_take_pred_test" => $can_take_pred_test
                            ];
                        }
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

                    $cat_list = getQuery("category", "*", array("course_id" => $test_id, "online" => "Y", "[or]"=>"shortcode in ('Math', 'FPT Math')"), "category_order");

                    foreach ($cat_list as $c) {

                        if ($c['category_name'] == "Flashcards") {
                            $list[$c['category_name']] = array("is_free" => $c['free'], "flashcard_id" => $c['cat_id'], "test_only" => 1, "icon" => $configs['vpath'] . "uploads/test.png", "action" => "knowledge-update");
                        } else {
                            $list[$c['category_name']] = [];
                            $lessons = getQuery("lesson", "*", array("cat_id" => $c['cat_id'], "online" => "Y"), "lesson_order");

                            foreach ($lessons as $l) {
                                if (!$c['icon']) {
                                    $l['icon'] = $configs['vpath'] . "uploads/test.png";
                                }
                                if ($paid_member == 'Y') {
                                    $display = 1;
                                } else if ($l['free'] == 'Y') {
                                    $display = 1;
                                } else {
                                    $display = 0;
                                }

                                if ($paid_member == 'Y') {
                                    $l['paid'] = 1;
                                } else {
                                    $l['paid'] = 0;
                                }

                                $list[$c['category_name']][] = array("is_free" => $l['free'], "paid" => $l['paid'], "display_lesson" => $display, "ord" => $l['lesson_order'], "cat_id" => $l['cat_id'], "test_only" => 1, "lesson_id" => $l['lesson_id'], "name" => $l['Lesson_name'], "icon" => $l['icon'], "action" => "practice-test-questions");
                            }
                        }
                    }

                    $predic_list = getQuery("predic_test", "*", array("course_id" => $test_id, "online" => "Y"), "test_order");
                    foreach ($predic_list as $p) {

                        if ($paid_member == 'Y') {
                            $p['paid'] = 1;
                        } else {
                            $p['paid'] = 0;
                        }

                        if ($paid_member == 'Y') {
                            $display = 1;
                        } else if ($p['free'] == 'Y') {
                            $display = 1;
                        } else {
                            $display = 0;
                        }

                        if ($test_id == 24) {

                            $list['AFOQT Prediction Test'][] = ["is_free" => $p['free'], "paid" => $p['paid'], "display_lesson" => $display, "id" => $p['Predic_test_id'], "name" => $p['test_name'], "icon" => $configs['vpath'] . $icon];
                        } else {

                            $list[$p['test_name']] = ["is_free" => $p['free'], "paid" => $p['paid'], "display_lesson" => $display, "id" => $p['Predic_test_id'], "name" => $p['test_name'], "icon" => $configs['vpath'] . $icon];
                        }
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

                    $response['practice_test_total'] = getFldValueById("category", "num_of_ques_pt", array("cat_id" => $lesson['cat_id'], "course_id" => $test_id));

                    if (!$response['practice_test_total']) {
                        if (in_array($lesson['cat_id'], $data['story_question'])) {
                            $response['practice_test_total'] = 20;
                        } else {
                            $response['practice_test_total'] = 15;
                        }
                    }
                    
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
                        $response['video_exist'] = true;
                        $response['videos'] = $video;
                    } else {
                        $response['video_exist'] = false;
                        $response['videos'] = [];
                        $response['message'] = "No video found for this lesson.";
                    }
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = false;
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["status"] = 201;
                $response["isSuccess"] = false;
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

                                if ($test_id == 24 || $test_id == 25) {
                                    $story_content = html_entity_decode(
                                        html_entity_decode(
                                            html_entity_decode($d['story'])
                                        )
                                    );

                                    $story_content = str_replace(
                                        [
                                            '"/uploads/',
                                            '"/practice-test/uploads/'
                                        ],
                                        [
                                            '"https://staging-test-prep.asvab-tutoring.com/practice-test/uploads/',
                                            '"https://staging-test-prep.asvab-tutoring.com/practice-test/uploads/'
                                        ],
                                        $story_content
                                    );

                                    $qlist[] = array("story" => $story_content, "serial_no" => $serial_no, "qid" => $d['qid'], "cat_id" => $d['cat_id'], "lesson_id" => $d['lesson_id'], "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "question_explanation" => html_entity_decode(html_entity_decode($d['explanation'])), "correct_answer" => $data['alphabet'][$d['answer']], "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))));

                                } else {
                                    $qlist[] = array("story" => html_entity_decode(html_entity_decode(html_entity_decode($d['story']))), "serial_no" => $serial_no, "qid" => $d['qid'], "cat_id" => $d['cat_id'], "lesson_id" => $d['lesson_id'], "question" => html_entity_decode($d['question']), "question_explanation" => html_entity_decode(html_entity_decode($d['explanation'])), "correct_answer" => $data['alphabet'][$d['answer']], "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))));
                                }
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
                    $response["isSuccess"] = true;
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = false;
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["status"] = 201;
                $response["isSuccess"] = false;
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

                    $tables = getExamTables($test_id);

                    $exam_table = $tables['exam_table'];
                    $exam_record_table = $tables['exam_record_table'];

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
                                $qset = getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                                if ($_POST['cat_id']) {
                                    //                                    if($_POST['test_type']=="continue" || $qset)
                                    if ($qset) {
                                        $response['story_list'] = getQueryByJoin(array("question as q", "$exam_table as e", "lesson_questions as l"), "q.story_id", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.course_id" => $test_id, "e.user_id" => $user_id, "l.$pt_hw" => 1), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "q.story_id", $story_total, "", "q.story_id");
                                    } else {
                                        $response['story_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.course_id" => $test_id, "l.$pt_hw" => 1), array("q.qid" => "l.qid"), "q.story_id", $story_total, "", "q.story_id");
                                    }
                                }

                                $story = array();
                                $test_questions = [];
                                $total = 0;

                                $result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

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
                                        $test_questions[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_table as e"), "q.*,l.*,e.id as eid, e.user_ans", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.course_id" => $test_id, "e.user_id" => $user_id, "l.$pt_hw" => 1, "e.is_result" => 0, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid", "l.qid" => "e.qid"), $order_by, '', '', "q.qid");
                                    } else {
                                        $test_questions[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $test_id, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid"), $order_by, '', '', "l.qid");
                                    }

                                    $exist_prev_ques = (int)getTotalRowsByJoin(array("question as q", "$exam_table as e"), array("e.cat_id" => $_POST['cat_id'], "e.user_id" => $user_id, "e.lesson_id" => (int)$_POST['lesson_id'], "q.story_id" => $a['story_id'], "is_result" => 0), array("q.qid" => "e.qid"));

                                    $ord = 0;
                                    foreach ($test_questions[$a['story_id']] as $d) {
                                        $serial_no++;

                                        // ********* insert new questions into running exam *******
                                        $eid = 0;
                                        //                                        if($_POST['test_type']!="continue" && !$exist_prev_ques)
                                        if (!$exist_prev_ques) {
                                            $ord++;
                                            $eid = insertTable($exam_table, ["user_id" => $user_id, "cat_id" => $_POST['cat_id'], "result_id" => $result_id, "lesson_id" => $_POST['lesson_id'], "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0]);
                                        }
                                        if ($eid) {
                                            $d['eid'] = $eid;
                                        }
                                        // ************** end ***********

                                        if ($test_id == 24) {
                                            $story_content = html_entity_decode(
                                                html_entity_decode(
                                                    html_entity_decode($story[$a['story_id']])
                                                )
                                            );

                                            $story_content = str_replace(
                                                [
                                                    '"/uploads/',
                                                    '"/practice-test/uploads/'
                                                ],
                                                [
                                                    '"https://staging-test-prep.asvab-tutoring.com/practice-test/uploads/',
                                                    '"https://staging-test-prep.asvab-tutoring.com/practice-test/uploads/'
                                                ],
                                                $story_content
                                            );

                                            $qlist[] = array("story" => $story_content, "serial_no" => $serial_no, "id" => $d['qid'], "eid" => $d['eid'], "cat_id" => $_POST['cat_id'], "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "answer" => $d['answer'], "my_answer" => $d['user_ans']);
                                        } else {
                                            $qlist[] = array("story" => str_replace('"/uploads/', '"https://officers.asvab-tutoring.com/uploads/', html_entity_decode(html_entity_decode(html_entity_decode($story[$a['story_id']])))), "serial_no" => $serial_no, "id" => $d['qid'], "eid" => $d['eid'], "cat_id" => $_POST['cat_id'], "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "answer" => $d['answer'], "my_answer" => $d['user_ans']);
                                        }
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
                            $qset = getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                            //                            if($_POST['test_type']=="continue" && $qset)
                            if ($qset) {
                                $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_table as e"), "e.user_ans, e.id as eid, q.*, l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $test_id, "e.user_id" => $user_id, "e.is_result" => 0), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "e.id", $response['total'], "", "q.qid");
                            } else {
                                $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $test_id), array("q.qid" => "l.qid"), "l.order_no", $response['total'], "", "q.qid");
                            }
                        }
                    } else {
                        $qset = 0;
                        $qset = getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                        //                        if($_POST['test_type']=="continue" || $qset)
                        if ($qset) {
                            $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_table as e"), "e.user_ans, e.id as eid,q.*, l.*", array("l.cat_id" => $_POST['cat_id'], "l.pt" => 1, "l.course_id" => $test_id, "e.user_id" => $user_id, "e.is_result" => 0), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "e.id", $response['total']);
                        } else {
                            $response['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.cat_id" => $_POST['cat_id'], "l.pt" => 1, "l.course_id" => $test_id), array("q.qid" => "l.qid"), "rand()", $response['total']);
                        }
                    }

                    if (!in_array($_POST['cat_id'], $data['story_question'])) {
                        $result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                        $result_exist = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));

                        if (!$result_exist) {
                            $result_id = insertTable("result", ["user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => $_POST['lesson_id'], "total_questions" => $response['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1]);
                        }

                        $exist_prev_ques = getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "is_result" => 0));

                        $ord = 0;
                        $qlist = [];
                        $eid = 0;
                        foreach ($response['practicetest'] as $a) {
                            $ord++;
                            //                            if($_POST['test_type']!="continue" && !$exist_prev_ques)
                            if (!$exist_prev_ques) {
                                $eid = insertTable($exam_table, ["user_id" => $user_id, "cat_id" => $_POST['cat_id'], "result_id" => $result_id, "lesson_id" => $_POST['lesson_id'], "qid" => $a['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0]);
                            }
                            if ($eid) {
                                $a['eid'] = $eid;
                            }

                            //                           ************** end ***********
                            $qlist[] = array("serial_no" => $ord, "id" => $a['qid'], "eid" => $a['eid'], "cat_id" => $_POST['cat_id'], "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_e']))), "answer" => $a['answer'], "my_answer" => $a['user_ans']);
                        }
                        $response['practicetest'] = $qlist;
                    }
                    $response["total_answered_questions"] = getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "lesson_id" => (int)$_POST['lesson_id'], "user_ans > " => 0, "is_result" => 0));

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
                    $response["isSuccess"] = false;
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

                $tables = getExamTables($test_id);

                $exam_table = $tables['exam_table'];
                $exam_record_table = $tables['exam_record_table'];

                $correct = "0";

                if ($_POST['answer'] == $_POST['answer_no']) {
                    $correct = 1;
                }

                $exist_in_exam = getTotalRow($exam_table, array("id" => $_POST['eid']));

                if ($exist_in_exam) {
                    $id = updateTable($exam_table, ["user_ans" => $_POST['answer_no'], "iscorrect" => (int)$correct], array("id" => $_POST['eid']));
                }

                $total_answer = getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "is_result" => 0, "lesson_id" => (int)$_POST['lesson_id'], "user_ans>" => 0));

                $pending = $_POST['total'] - $total_answer;

                if ($id) {
                    $response['message'] = "You have answered this question successfully.";
                } else {
                    $response['message'] = "Error!";
                }

                $array['pending'] = "";

                if ($pending > 0) {
                    $response['pending_total'] = "Still pending " . $pending . " questions to answer!";
                    // $array['pending']="";
                } else {
                    $response['message'] = "Congratulation ! You have successfully answered all questions. Check your score.";

                    $response['cat_id'] = $_POST['cat_id'];
                    $response['lesson_id'] = $_POST['lesson_id'];
                    $response['total'] = $_POST['total'];
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

                $tables = getExamTables($test_id);

                $exam_table = $tables['exam_table'];
                $exam_record_table = $tables['exam_record_table'];

                $total = $_POST['total'];

                $total_correct = (int)getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "is_result" => 0, "lesson_id" => (int)$_POST['lesson_id'], "iscorrect" => 1));

                $total_wrong = (int)getTotalRow($exam_table, array("cat_id" => $_POST['cat_id'], "user_id" => $user_id, "is_result" => 0, "lesson_id" => (int)$_POST['lesson_id'], "iscorrect" => 0));

                $total_answer_given = $total_correct + $total_wrong;

                $total_answer_not_given = $total - $total_answer_given;

                if ($total_correct == 0) {
                    $score = 0;
                } else {
                    $score = round(($total_correct / $total) * 100);
                }

                $result_id = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "score" => -1));

                $response['result_id'] = $result_id;

                if ($result_id) {

                    updateTable("result", array("correct_answers" => $total_correct, "score" => (int)$score, "result_date" => date('Y-m-d H:i:s')), array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));

                    $exam_list = getQuery($exam_table, "*", array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));

                    foreach ($exam_list as $r) {

                        insertTable($exam_record_table, ["user_id" => $user_id, "cat_id" => $r['cat_id'], "result_id" => $r['result_id'], "lesson_id" => $r['lesson_id'], "qid" => $r['qid'], "exam_date" => $r['exam_date'], "is_result" => 1, "user_ans" => $r['user_ans'], "iscorrect" => $r['iscorrect']]);
                    }

                    deleteData($exam_table, array("user_id" => $user_id, "cat_id" => $_POST['cat_id'], "lesson_id" => (int)$_POST['lesson_id'], "result_id" => $result_id));
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

                // $tables = getExamTables($test_id);
                // $exam_table = $tables['exam_table'];
                // $exam_record_table = $tables['exam_record_table'];

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

                if ($user['old_user_id']) {
                    $response['old_user_id'] = $user['old_user_id'];
                    $response['is_old_user'] = true;
                } else {
                    $response['is_old_user'] = false;
                }

                $tables = getExamTables($test_id);

                $exam_table = $tables['exam_table'];
                $exam_record_table = $tables['exam_record_table'];

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
                    $story_list = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.lesson_id" => $_POST['lesson_id'], "e.cat_id" => $_POST['cat_id'], "e.user_id" => $user_id, "e.result_id" => $_POST['result_id']), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");

                    foreach ($story_list as $d) {
                        $story = getFldValueById("story", "story", array("story_id" => $d['story_id']));

                        $question_test[$d['story_id']] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*,e.*", array("e.lesson_id" => $_POST['lesson_id'], "e.cat_id" => $_POST['cat_id'], "e.user_id" => $user_id, "e.result_id" => $_POST['result_id'], "q.story_id" => $d['story_id']), array("q.qid" => "e.qid"), "e.id", '', '', "q.qid");


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

                            $qlist[] = array("story" => str_replace('"/uploads/', '"https://officers.asvab-tutoring.com/uploads/', html_entity_decode(html_entity_decode(html_entity_decode($story)))), "srno" => $i, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $a['option_e']))), "correct_answer" => $correct_answer, "myanswer" => $a['user_ans'], "explanation" => $a['explanation']);
                        }
                    }

                    $response['qlist'] = $qlist;
                } else {
                    $question_test = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*,e.*", array("e.lesson_id" => $_POST['lesson_id'], "e.cat_id" => $_POST['cat_id'], "e.result_id" => $_POST['result_id']), array("q.qid" => "e.qid"), 'e.id', '', '', 'q.qid');

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
                        $response["isSuccess"] = true;
                    } else {
                        $response['message'] = "No questions to view.";
                    }
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = false;
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["status"] = 201;
                $response["isSuccess"] = false;
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

                $tables = getExamTables($test_id);

                $exam_table = $tables['exam_table'];
                $exam_record_table = $tables['exam_record_table'];

                $alert = 0;
                if ($lesson['free'] == 'N') {
                    if (!$paid_member == 'Y') {
                        $alert = 1;
                    }
                }

                if (!$alert) {
                    if ($_POST['test_type'] == "freshtest") {
                        $result_id = (int)getFldValueById($exam_table, "result_id", array("Predic_test_id" => $_POST['pred_test_id'], "user_id" => $user_id, "is_result" => 0));

                        deleteData($exam_table, array("Predic_test_id" => $_POST['pred_test_id'], "user_id" => $user_id, "is_result" => 0));
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

                        $completed = (int)getTotalRow($exam_table, ['Predic_test_id' => $_POST['pred_test_id'], 'user_id' => $user_id, 'cat_id' => $cat['cat_id'], 'user_ans>' => 0, 'is_result' => 0]);

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

                        $exist_prev_question = getTotalRow($exam_table, array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        if ((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed'] > 0) || $exist_prev_question) {
                            $story_list_array = getQueryByJoin(array("question as q", "$exam_table as e"), "q.story_id", array("e.cat_id" => (int)$cat_id, "e.Predic_test_id" => $_POST['pred_test_id'], "e.user_id" => $user_id, "e.is_result" => 0), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");
                            $response['testing cat id'] = (int)$cat_id;
                            $response['testing Predic_test_id'] = $_POST['pred_test_id'];
                            $response['testing user_id'] = $user_id;
                        } else {
                            $attempted_set_rows = getQuery($exam_table, "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "is_result" => 1), "", "", "", "pred_set_id");
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

                        $result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

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
                                    $eid = insertTable($exam_table, ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0, "pred_set_id" => $set_id]);
                                }

                                if ($eid) {
                                    $d['eid'] = $eid;
                                } else {

                                    $d['eid'] = getFldValueById($exam_table, "id", ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "is_result" => 0]);

                                    $d['user_ans'] = getFldValueById($exam_table, "user_ans", ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "is_result" => 0]);
                                }

                                $question[] = array("story" => str_replace('"/uploads/', '"https://officers.asvab-tutoring.com/uploads/', html_entity_decode(html_entity_decode(html_entity_decode($story)))), "serial_no" => $serial_no, "id" => $d['qid'], "eid" => $d['eid'], "pred_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "answer" => $d['answer'], "my_answer" => $d['user_ans']);
                            }
                        }
                        $response['question_test'] = $question;
                    } else {
                        $exist_prev_question = getTotalRow($exam_table, array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        if ((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed'] > 0) || $exist_prev_question) {
                            $question_list = getQueryByJoin(array("question as q", "$exam_table as e"), "q.*, e.*", array("e.cat_id" => $cat_id, "e.user_id" => $user_id, "e.Predic_test_id" => $_POST['pred_test_id'], "e.is_result" => 0), array("e.qid" => "q.qid"), "e.id", $limit);
                        } else {
                            $attempted_set_rows = getQuery($exam_record_table, "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "is_result" => 1), "", "", "", "pred_set_id");
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

                        $result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        $result_exist = getFldValueById("result", "result_id", array("user_id" => $user_id, "cat_id" => $cat_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "result_id" => $result_id));

                        if (!$result_exist) {
                            $result_id = insertTable("result", ["user_id" => $user_id, "cat_id" => $cat_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "total_questions" => $prediction_cat[$cat_id]['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1, "pred_set_id" => $set_id]);
                        }

                        $exist_prev_ques = getTotalRow($exam_table, array("cat_id" => $cat_id, "user_id" => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "is_result" => 0));

                        $serial_no = 0;
                        foreach ($question_list as $d) {
                            $serial_no++;
                            $eid = 0;
                            if (!$prediction_cat[$cat_id]['completed'] && !$exist_prev_ques) {
                                $eid = insertTable($exam_table, ["user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0, "pred_set_id" => $set_id]);
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
                    $response["isSuccess"] = false;
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

                $tables = getExamTables($test_id);

                $exam_table = $tables['exam_table'];
                $exam_record_table = $tables['exam_record_table'];

                $correct = "0";

                if ($_POST['answer'] == $_POST['answer_no']) {
                    $correct = 1;
                }

                $exist_in_exam = getTotalRow($exam_table, array("id" => $_POST['eid']));

                if ($exist_in_exam) {
                    $id = updateTable($exam_table, ["user_ans" => $_POST['answer_no'], "iscorrect" => (int)$correct], array("id" => $_POST['eid']));
                }

                $total_answer = getTotalRow($exam_table, array("Predic_test_id" => $_POST['pred_test_id'], "user_id" => $user_id, "is_result" => 0, "user_ans>" => 0));

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

                if (getCanTakePredTestFlag($user_id) !== 1) {
                    predictionTestSubmissionLockedResponse($response);
                    break;
                }

                $tables = getExamTables($test_id);

                $exam_table = $tables['exam_table'];
                $exam_record_table = $tables['exam_record_table'];

                $total_questions = 0;
                $total_correct_ans = 0;
                $total_cat_score = 0;
                $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_POST['pred_test_id']));
                $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_POST['pred_test_id']), "", "", "", "id");

                if ($test_id == 28 || $test_id == 38 || $test_id == 39 || $_SESSION['test_id'] == 24) { // [ASVAB Tutoring, ASVAB Prep 2025, ASVAB GT, AFOQT] 
                    $cat_scores = array();
                }

                foreach ($pred_cat_list as $p) {
                    $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                    $cat_id = $p['cat_id'];
                    $cat_score = 0;
                    $num_correct_ans = (int)getTotalRow($exam_table, array("iscorrect" => 1, "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "is_result" => 0));

                    if ($num_correct_ans) {

                        if ($test_id == 24) { // AFOQT Score Calculation

                            $category_score_per = round(($num_correct_ans / $p['number_of_questions']) * 100);
                            $cat_score = $data["oc_afoqt_pred_score_array"][$category_score_per];

                            $cat_scores[$cat['shortcode']]['correct'] = $num_correct_ans;
                            $cat_scores[$cat['shortcode']]['total_question'] = $p['number_of_questions'];
                        } else if (in_array($test_id, [21, 25, 26])) { // SIFT, ASTB and OAR Score Calculation

                            $category_score_per = round(($num_correct_ans / $p['number_of_questions']) * 100);

                            if ($category_score_per < 25) {
                                $cat_score = 20;
                            } elseif ($category_score_per > 100) {
                                $cat_score = 80;
                            } else {
                                $cat_score = $data["oc_pred_score_array"][$category_score_per];
                            }
                        } else if ($test_id == 28) { // ASVAB Tutoring

                            $correct_ans_percentage = round(($num_correct_ans / $p['number_of_questions']) * 100);
                            $cat_score = round(20 + $correct_ans_percentage * 0.55);
                            $cat_scores[$cat['shortcode']] = $cat_score;
                        } else if ($test_id == 38) { // ASVAB Prep 2025

                            $correct_ans_percentage = round(($num_correct_ans / $p['number_of_questions']) * 100);
                            $cat_score = round(20 + $correct_ans_percentage * 0.55);
                            $cat_scores[$cat['shortcode']] = $cat_score;
                        } else if ($test_id == 39) { // ASVAB GT

                            $correct_ans_percentage = round(($num_correct_ans / $p['number_of_questions']) * 100);
                            $cat_score = round(20 + $correct_ans_percentage * 0.55);
                            $cat_scores[$cat['shortcode']] = $cat_score;
                        } else {

                            $correct_ans_percentage = round(($num_correct_ans / $p['number_of_questions']) * 100);
                            $cat_score = round(20 + $correct_ans_percentage * 0.55);
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


                if ($test_id == 24) {

                    //Full AFOQT Score Calculation
                    if ($test_id == 24 && $_POST['pred_test_id'] == 40) {
                        $full_afoqt_scores = array();

                        // Composite: Pilot (MK + TR + IC + AI)
                        $pilot_categories = ['MK', 'TR', 'IC', 'AI'];
                        $full_afoqt_scores['Pilot'] = calculate_composite_score($pilot_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                        // Composite: CSO (WK + MK + TR + BC)
                        $cso_categories = ['WK', 'MK', 'TR', 'BC'];
                        $full_afoqt_scores['CSO'] = calculate_composite_score($cso_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                        // Composite: ABM (VA + MK + TR + IC + BC + AI)
                        $abm_categories = ['VA', 'MK', 'TR', 'IC', 'BC', 'AI'];
                        $full_afoqt_scores['ABM'] = calculate_composite_score($abm_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                        // Composite: Academic Aptitude (VA + AR + WK + MK + RC)
                        $academic_categories = ['VA', 'AR', 'WK', 'MK', 'RC'];
                        $full_afoqt_scores['Academic Aptitude'] = calculate_composite_score($academic_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                        // Composite: Verbal (VA + WK + RC)
                        $verbal_categories = ['VA', 'WK', 'RC'];
                        $full_afoqt_scores['Verbal'] = calculate_composite_score($verbal_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                        // Composite: Quantitative (AR + MK)
                        $quant_categories = ['AR', 'MK'];
                        $full_afoqt_scores['Quantitative'] = calculate_composite_score($quant_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                        // Full AFOQT score
                        $pre_score = round(($total_correct_ans / $total_questions) * 100);
                        $full_afoqt_scores['AFOQT'] = $data["oc_afoqt_pred_score_array"][$pre_score];
                        $final_pred_score = $full_afoqt_scores['AFOQT'];
                    } else {
                        $pre_score = round(($total_correct_ans / $total_questions) * 100);
                        $final_pred_score = $data["oc_afoqt_pred_score_array"][$pre_score];
                    }
                } else if (in_array($test_id, [21, 25, 26])) {

                    $pre_score = round(($total_correct_ans / $total_questions) * 100);
                    $final_pred_score = $data["oc_pred_score_array"][$pre_score];
                } else if ($test_id == 28) { // ASVAB tutoring

                    // VE Score
                    $ve_score = round(($cat_scores['PC'] + $cat_scores['WK']) / 2);

                    // AFQT Score
                    $raw_score = $ve_score + $cat_scores['AR'] + $cat_scores['MK'];
                    if ($raw_score > 215) {
                        $raw_score = 215;
                    }
                    $afqt_score = $data['full_asvab_pred_score_array'][$raw_score];
                    $final_pred_score = $afqt_score;
                    $final_ve_score = $ve_score;
                } else if ($test_id == 38 || $test_id == 39) { // ASVAB Prep 2025 & ASVAB GT

                    $full_asvab_scores = array();

                    // VE Score
                    $ve_score = round(($cat_scores['PC'] + $cat_scores['WK']) / 2);
                    $full_asvab_scores['VE'] = $ve_score;

                    // AFQT Score
                    $raw_score = $ve_score + $cat_scores['AR'] + $cat_scores['MK'];
                    if ($raw_score > 215) {
                        $raw_score = 215;
                    }
                    $afqt_score = $data['full_asvab_pred_score_array'][$raw_score];
                    $full_asvab_scores['AFQT'] = $afqt_score;

                    // Army GT Score
                    $army_gt_score = round($ve_score + $cat_scores['AR'] + rand(0, 4));
                    if ($army_gt_score > 140) {
                        $army_gt_score = 140;
                    }
                    $full_asvab_scores['Army GT'] = $army_gt_score;

                    // Navy GT Score
                    $navy_gt_score = round($ve_score + $cat_scores['AR']);
                    if ($navy_gt_score > 140) {
                        $navy_gt_score = 140;
                    }
                    $full_asvab_scores['Navy GT'] = $navy_gt_score;

                    // Marine GT Score
                    $marine_gt_score = round($ve_score + $cat_scores['AR'] + ($cat_scores['MC'] - $cat_scores['AR']));
                    if ($marine_gt_score > 140) {
                        $marine_gt_score = 140;
                    }
                    $full_asvab_scores['Marine GT'] = $marine_gt_score;
                    $final_pred_score = $full_asvab_scores;
                } else {

                    $final_pred_score = round($total_cat_score / $num_category);
                }

                $response['final_pred_score'] = $final_pred_score;

                if ($test_id == 38) { // ASVAB Prep 2025
                    $total_pred_score = 0;

                    $reslt_id =  insertTable("result", array("pred_name" => "ASVAB Prep", "score" => $full_asvab_scores['AFQT'], "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "total_questions" => $total_questions, "correct_answers" => $total_correct_ans, "result_date" => date("Y-m-d")));

                    foreach ($full_asvab_scores as $key => $value) {
                        $total_pred_score += $value;

                        insertTable("result", array("pred_name" => $key, "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score" => $value, "parent_id" => $reslt_id, "result_date" => date("Y-m-d")));
                    }
                } else if ($test_id == 39) { // ASVAB GT
                    $total_pred_score = 0;

                    $reslt_id =  insertTable("result", array("pred_name" => "ASVAB GT", "score" => $full_asvab_scores['AFQT'], "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "total_questions" => $total_questions, "correct_answers" => $total_correct_ans, "result_date" => date("Y-m-d")));

                    foreach ($full_asvab_scores as $key => $value) {
                        $total_pred_score += $value;

                        insertTable("result", array("pred_name" => $key, "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score" => $value, "parent_id" => $reslt_id, "result_date" => date("Y-m-d")));
                    }
                } else if ($test_id == 24 && $_POST['pred_test_id'] == 40) { // Full AFOQT Score Result
                    $total_pred_score = 0;

                    $reslt_id =  insertTable("result", array("pred_name" => "AFOQT Prediction test", "score" => $full_afoqt_scores['AFOQT'], "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "total_questions" => $total_questions, "correct_answers" => $total_correct_ans, "result_date" => date("Y-m-d")));

                    foreach ($full_afoqt_scores as $key => $value) {

                        $total_pred_score += $value;

                        insertTable("result", array("pred_name" => $key, "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score" => $value, "parent_id" => $reslt_id, "result_date" => date("Y-m-d")));
                    }
                } else {
                    $reslt_id =  insertTable("result", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score" => $final_pred_score, "result_date" => date("Y-m-d"), "pred_set_id" => $pred_set_id, "total_questions" => $total_questions, "correct_answers" => (int)$total_correct_ans));

                    if ($test_id == 28) { // ASVAB Tutoring
                        insertTable("result", array("pred_name" => "VE", "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score" => $final_ve_score, "parent_id" => $reslt_id, "result_date" => date("Y-m-d")));
                    }
                }

                $result_ids = getQuery($exam_table, "DISTINCT(result_id)", array("is_result" => 0, 'user_id' => $user_id, "Predic_test_id" => (int)$_POST['pred_test_id'], "pred_set_id" => (int)$pred_set_id));

                foreach ($result_ids as $id) {
                    updateTable("result", array('parent_id' => $reslt_id), array('user_id' => $user_id, "result_id" => $id['result_id']));
                }

                // updateTable("exam", ['is_result' => 1], ['user_id' => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "pred_set_id" => $pred_set_id]);

                $exam_record_list = getQuery($exam_table, "*", array('is_result' => 0, 'user_id' => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "pred_set_id" => (int)$pred_set_id));

                foreach ($exam_record_list as $r) {
                    insertTable($exam_record_table, array("user_id" => $user_id, "Predic_test_id" => $r['Predic_test_id'], "cat_id" => $r['cat_id'], "result_id" => $r['result_id'], "qid" => $r['qid'], "user_ans" => $r['user_ans'], "iscorrect" => $r['iscorrect'], "exam_date" => $r['exam_date'], "is_result" => 1, "pred_set_id" => $r['pred_set_id']));
                }

                deleteData($exam_table, array('is_result' => 0, 'user_id' => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "pred_set_id" => (int)$pred_set_id));

                if ($reslt_id) {
                    $response['result_id'] = $reslt_id;
                }
                $response['pred_test_id'] = $_POST['pred_test_id'];
                $response['can_take_pred_test'] = getCanTakePredTestFlag($user_id);
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "prediction-test-result":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {

                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $user_name = $user['first_name'] . ' ' . $user['last_name'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $is_paid_user = $user['activation_status'] == 'Y' ? 1 : 0;

                if ($user['old_user_id']) {
                    $response['old_user_id'] = $user['old_user_id'];
                    $response['is_old_user'] = true;
                } else {
                    $response['is_old_user'] = false;
                }

                $pred_test_data = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_POST['pred_test_id']));

                $response['prediction_test'] = array("Predic_test_id" => $pred_test_data['Predic_test_id'], "course_id" => $pred_test_data['course_id'], "test_name" => $pred_test_data['test_name'], "test_order" => $pred_test_data['test_order'], "shortcode" => $pred_test_data['shortcode'], "online" => $pred_test_data['online'], "free" => $pred_test_data['free'], "display_pred_test_review" => $is_paid_user);

                $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_POST['pred_test_id']));

                if ($test_id == 38 || $test_id == 39) {

                    if ($test_id == 38) {
                        $pred_test_name = "ASVAB Prep";
                    } else {
                        $pred_test_name = "ASVAB GT";
                    }
                    $pred_report = getQuery("result", "*", array("pred_name" => $pred_test_name, "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score!" => -1), "result_id");
                } else if ($test_id == 24 && $_POST['pred_test_id'] == 40) { // AFOQT and Full AFOQT Report

                    $pred_test_name = "AFOQT Prediction test";

                    $pred_report = getQuery("result", "*", array("pred_name" => $pred_test_name, "user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score!" => -1), "result_id");
                } else if ($test_id == 28) {

                    $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "total_questions >" => 0, "score!" => -1), "result_id desc", 1);
                } else {

                    $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "score!" => -1), "result_id desc", 1);
                }

                $response['report'] = [];

                foreach ($pred_report as $p) {

                    $response['report']['prediction_score'] = array("result_id" => $p['result_id'], "cat_id" => $p['cat_id'], "pred_test_id" => $p['Predic_test_id'], "course_id" => $test_id, "date" => showdate($p['result_date']), "correct" => $p['correct_answers'], "total_questions" => $p['total_questions'], "wrong" => ($p['total_questions'] - $p['correct_answers']), "score" => (int)$p['score']);

                    if ($test_id == 38 || $test_id == 39) {
                        unset(
                            $response['report']['prediction_score']['correct'],
                            $response['report']['prediction_score']['total_questions'],
                            $response['report']['prediction_score']['wrong']
                        );
                    }
                }

                $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_POST['pred_test_id']), "", "", "", "id");
                $pred_cat_name = [];
                $total_array = [];

                foreach ($pred_cat_list as $p) {

                    $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                    $cat_id = $p['cat_id'];
                    $cat_name = $cat['category_name'];
                    $total_array[$cat['cat_id']] = $p['number_of_questions'];

                    $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "score!" => -1), "result_id desc", 1);

                    foreach ($pred_report as $r) {

                        $parent_id = $r['parent_id'];

                        $response['report'][$cat_name] = array("result_id" => $r['result_id'], "cat_id" => $r['cat_id'], "cat_name" => $cat_name, "shortcode" => $cat['shortcode'], "pred_test_id" => $r['Predic_test_id'], "course_id" => $test_id, "date" => showdate($r['result_date']), "correct" => $r['correct_answers'], "total_questions" => $r['total_questions'], "wrong" => ($r['total_questions'] - $r['correct_answers']), "score" => (int)$r['score']);
                    }
                }

                if ($test_id == 38 || $test_id == 39) {

                    $response['report']['VE'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "VE"
                        ])
                    );

                    $response['report']['marine_gt'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "Marine GT"
                        ])
                    );

                    $response['report']['navy_gt'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "Navy GT"
                        ])
                    );

                    $response['report']['army_gt'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "Army GT"
                        ])
                    );

                    $response['report']['applicant_name'] = $user_name;
                    $response['report']['session_date'] = $response['report']['army_gt']['result_date'];
                } else if ($test_id == 24 && $_POST['pred_test_id'] == 40) {

                    $response['report']['PIL'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "Pilot"
                        ])
                    );

                    $response['report']['CSO'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "CSO"
                        ])
                    );

                    $response['report']['ABM'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "ABM"
                        ])
                    );

                    $response['report']['AA'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "Academic Aptitude"
                        ])
                    );

                    $response['report']['V'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "Verbal"
                        ])
                    );

                    $response['report']['Q'] = cleanArray(
                        getDataByWhere("result", "*", [
                            "parent_id" => $parent_id,
                            "pred_name" => "Quantitative"
                        ])
                    );
                } else if ($test_id == 28) {

                    $ve_report_score = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $_POST['pred_test_id'], "pred_name" => "VE", "score!" => -1), "result_id desc", 1);

                    foreach ($ve_report_score as $p) {

                        $response['report'][$p['pred_name']] = array("result_id" => $p['result_id'], "cat_id" => $p['cat_id'], "cat_name" => $p['pred_name'], "shortcode" => $p['pred_name'], "pred_test_id" => $p['Predic_test_id'], "course_id" => $test_id, "date" => showdate($p['result_date']), "score" => (int)$p['score']);

                        $result_date = showdate($p['result_date']);
                    }

                    $response['report']['applicant_name'] = $user_name;
                    $response['report']['session_date'] = $result_date;
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
                $user_name = $user['first_name'] . ' ' . $user['last_name'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $is_paid_user = $user['activation_status'] == 'Y' ? 1 : 0;

                $response['display_pred_test_review'] = $is_paid_user;

                if ($user['old_user_id']) {
                    $response['old_user_id'] = $user['old_user_id'];
                    $response['is_old_user'] = true;
                } else {
                    $response['is_old_user'] = false;
                }

                $report_cat_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1, "lesson_id>" => 0), "cat_id", "", "", "cat_id");

                $lesson_reports = array();

                foreach ($report_cat_list as $rc) {
                    $cat_name = getFldValueById("category", "category_name", array("cat_id" => $rc['cat_id']));

                    $report_lesson_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1, "lesson_id>" => 0, "cat_id" => $rc['cat_id']), "lesson_id", "", "", "lesson_id");

                    foreach ($report_lesson_list as $rl) {
                        $lesson_name = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $rl['lesson_id']));
                        $reports = getQuery("result", "*", array("user_id" => $user_id, "cat_id" => $rc['cat_id'], "lesson_id" => $rl['lesson_id'], "score!" => -1), "result_id desc", "1");

                        foreach ($reports as $r) {
                            $lesson_reports[] = array(
                                "result_id" => $r['result_id'],
                                "cat_id" => $rc['cat_id'],
                                "cat_name" => $cat_name,
                                "lesson_id" => $rl['lesson_id'],
                                "lesson_name" => $lesson_name,
                                "total_questions" => $r['total_questions'],
                                "correct_answers" => (int)$r['correct_answers'],
                                "wrong_answer" => $r['total_questions'] - (int)$r['correct_answers'],
                                "total_answered" => $r['total_questions'],
                                "result_date" => $r['result_date'],
                                "score" => round($r['score'])
                            );
                        }
                    }
                }

                $response['lesson_result'] = $lesson_reports;

                if ($test_id == 38 || $test_id == 39) {

                    $pred_parent_id_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1), "parent_id desc", "1", "", "parent_id");

                    $all_pred_test = array();

                    foreach ($pred_parent_id_list as $ppid) {

                        if (empty($ppid['parent_id'])) {
                            continue;
                        }

                        $pred_test_info = array();

                        $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $ppid['Predic_test_id']), "", "", "", "id");
                        $pred_cat_name = [];
                        $total_array = [];

                        foreach ($pred_cat_list as $p) {

                            $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                            $cat_id = $p['cat_id'];
                            $cat_name = $cat['category_name'];
                            $total_array[$cat['cat_id']] = $p['number_of_questions'];

                            $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $ppid['Predic_test_id'], "cat_id" => $cat_id, "score !" => -1), "result_id desc", 1);

                            foreach ($pred_report as $r) {

                                $pred_test_info['standard_score'][$cat_name] = array("result_id" => $r['result_id'], "cat_id" => $r['cat_id'], "cat_name" => $cat_name, "shortcode" => $cat['shortcode'], "pred_test_id" => $r['Predic_test_id'], "course_id" => $test_id, "date" => showdate($r['result_date']), "correct" => $r['correct_answers'], "total_questions" => $r['total_questions'], "wrong" => ($r['total_questions'] - $r['correct_answers']), "score" => (int)$r['score']);
                            }
                        }

                        $pred_test_info['standard_score']['VE'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "parent_id" => $ppid['parent_id'],
                                "pred_name" => "VE"
                            ])
                        );

                        $pred_test_info['composite_scores']['marine_gt'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "parent_id" => $ppid['parent_id'],
                                "pred_name" => "Marine GT"
                            ])
                        );

                        $pred_test_info['composite_scores']['navy_gt'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "parent_id" => $ppid['parent_id'],
                                "pred_name" => "Navy GT"
                            ])
                        );

                        $pred_test_info['composite_scores']['army_gt'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "parent_id" => $ppid['parent_id'],
                                "pred_name" => "Army GT"
                            ])
                        );

                        $pred_test_info['composite_scores']['afqt_percentile_score'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "parent_id" => $ppid['parent_id'],
                                "pred_name" => "VE"
                            ])
                        );

                        $pred_test_info['applicant_name'] = $user_name;
                        $pred_test_info['session_date'] = $pred_test_info['composite_scores']['army_gt']['result_date'];

                        $all_pred_test[] = $pred_test_info;
                    }

                    $response['prediction-test'] = $all_pred_test;
                } else if ($test_id == 28) {

                    $pred_parent_id_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1), "parent_id desc", "1", "", "parent_id");

                    $all_pred_test = array();

                    foreach ($pred_parent_id_list as $ppid) {

                        if (empty($ppid['parent_id'])) {
                            continue;
                        }

                        $pred_test_info = array();

                        $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $ppid['Predic_test_id']), "", "", "", "id");
                        $pred_cat_name = [];
                        $total_array = [];

                        foreach ($pred_cat_list as $p) {

                            $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                            $cat_id = $p['cat_id'];
                            $cat_name = $cat['category_name'];
                            $total_array[$cat['cat_id']] = $p['number_of_questions'];

                            $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $ppid['Predic_test_id'], "cat_id" => $cat_id, "score !" => -1), "result_id desc", 1);

                            foreach ($pred_report as $r) {

                                $pred_test_info['standard_score'][$cat_name] = array("result_id" => $r['result_id'], "cat_id" => $r['cat_id'], "cat_name" => $cat_name, "shortcode" => $cat['shortcode'], "pred_test_id" => $r['Predic_test_id'], "course_id" => $test_id, "date" => showdate($r['result_date']), "correct" => $r['correct_answers'], "total_questions" => $r['total_questions'], "wrong" => ($r['total_questions'] - $r['correct_answers']), "score" => (int)$r['score']);
                            }
                        }

                        $pred_test_info['standard_score']['VE'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "parent_id" => $ppid['parent_id'],
                                "pred_name" => "VE"
                            ])
                        );

                        $pred_test_info['composite_scores']['afqt_percentile_score'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "result_id" => $ppid['parent_id']
                            ])
                        );

                        $pred_test_info['composite_scores']['VE'] = cleanArray(
                            getDataByWhere("result", "*", [
                                "parent_id" => $ppid['parent_id'],
                                "pred_name" => "VE"
                            ])
                        );

                        $pred_test_info['applicant_name'] = $user_name;
                        $pred_test_info['session_date'] = $pred_test_info['composite_scores']['VE']['result_date'];

                        $all_pred_test[] = $pred_test_info;
                    }

                    $response['prediction-test'] = $all_pred_test;
                } else {

                    $pred_test_list = getQuery("result", "*", array("user_id" => $user_id, "score!" => -1), "Predic_test_id", "", "", "Predic_test_id");

                    $prediction_reports = array();
                    foreach ($pred_test_list as $pl) {

                        if ($test_id == 24 && $pl['Predic_test_id'] == 40) { // Full AFOQT Report

                            $all_afoqt_prediction_score['AFOQT Prediction test'] = array();
                            $pred_test_name = "AFOQT Prediction test";

                            $pred_report = getQuery("result", "*", array("pred_name" => $pred_test_name, "user_id" => $user_id, "Predic_test_id" => $pl['Predic_test_id'], "cat_id" => $pl['Predic_test_id'], "score!" => -1), "result_id desc", "1");

                            foreach ($pred_report as $p) {

                                $afoqt_pred_score = array("result_id" => $p['result_id'], "cat_id" => $p['cat_id'], "pred_test_id" => $p['Predic_test_id'], "course_id" => $test_id, "date" => showdate($p['result_date']), "correct" => $p['correct_answers'], "total_questions" => $p['total_questions'], "wrong" => ($p['total_questions'] - $p['correct_answers']), "score" => (int)$p['score']);
                            }
                            $all_afoqt_prediction_score['AFOQT Prediction test']['prediction_score'] = $afoqt_pred_score;

                            $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $pl['Predic_test_id']), "", "", "", "id");
                            $pred_cat_name = [];
                            $total_array = [];

                            foreach ($pred_cat_list as $p) {

                                $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                                $cat_id = $p['cat_id'];
                                $cat_name = $cat['category_name'];
                                $total_array[$cat['cat_id']] = $p['number_of_questions'];

                                $pred_report = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $pl['Predic_test_id'], "cat_id" => $cat_id, "score!" => -1), "result_id desc", 1);

                                foreach ($pred_report as $r) {

                                    $parent_id = $r['parent_id'];

                                    $all_afoqt_prediction_score['AFOQT Prediction test'][$cat_name] = array("result_id" => $r['result_id'], "cat_id" => $r['cat_id'], "cat_name" => $cat_name, "shortcode" => $cat['shortcode'], "pred_test_id" => $r['Predic_test_id'], "course_id" => $test_id, "date" => showdate($r['result_date']), "correct" => $r['correct_answers'], "total_questions" => $r['total_questions'], "wrong" => ($r['total_questions'] - $r['correct_answers']), "score" => (int)$r['score']);
                                }
                            }

                            $all_afoqt_prediction_score['AFOQT Prediction test']['PIL'] = cleanArray(
                                getDataByWhere("result", "*", [
                                    "parent_id" => $parent_id,
                                    "pred_name" => "Pilot"
                                ])
                            );

                            $all_afoqt_prediction_score['AFOQT Prediction test']['CSO'] = cleanArray(
                                getDataByWhere("result", "*", [
                                    "parent_id" => $parent_id,
                                    "pred_name" => "CSO"
                                ])
                            );

                            $all_afoqt_prediction_score['AFOQT Prediction test']['ABM'] = cleanArray(
                                getDataByWhere("result", "*", [
                                    "parent_id" => $parent_id,
                                    "pred_name" => "ABM"
                                ])
                            );

                            $all_afoqt_prediction_score['AFOQT Prediction test']['AA'] = cleanArray(
                                getDataByWhere("result", "*", [
                                    "parent_id" => $parent_id,
                                    "pred_name" => "Academic Aptitude"
                                ])
                            );

                            $all_afoqt_prediction_score['AFOQT Prediction test']['V'] = cleanArray(
                                getDataByWhere("result", "*", [
                                    "parent_id" => $parent_id,
                                    "pred_name" => "Verbal"
                                ])
                            );

                            $all_afoqt_prediction_score['AFOQT Prediction test']['Q'] = cleanArray(
                                getDataByWhere("result", "*", [
                                    "parent_id" => $parent_id,
                                    "pred_name" => "Quantitative"
                                ])
                            );

                            $prediction_reports[] = $all_afoqt_prediction_score;
                        } else {
                            $pred_name = getFldValueById("predic_test", "test_name", array("Predic_test_id" => $pl['Predic_test_id']));

                            if ($test_id == 28) {
                                $preports = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $pl['Predic_test_id'], "score!" => -1, "total_questions >" => 0), "result_id desc", "1");
                            } else {
                                $preports = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $pl['Predic_test_id'], "score!" => -1), "result_id desc", "1");
                            }

                            $tmp_report = [];
                            foreach ($preports as $r) {
                                if ($r['cat_id'] == $r['Predic_test_id']) {
                                    $tmp_report[$pred_name] = array(
                                        "result_id" => $r['result_id'],
                                        "pred_test_id" => $r['Predic_test_id'],
                                        "pred_test_name" => $pred_name,
                                        "total_questions" => $r['total_questions'],
                                        "correct_answers" => (int)$r['correct_answers'],
                                        "wrong_answer" => $r['total_questions'] - (int)$r['correct_answers'],
                                        "total_answered" => (int)$r['total_questions'],
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
                                        "correct_answers" => (int)$r['correct_answers'],
                                        "wrong_answer" => $r['total_questions'] - (int)$r['correct_answers'],
                                        "total_answered" => $r['total_questions'],
                                        "result_date" => $r['result_date'],
                                        "score" => round($r['score'])
                                    );
                                }
                            }
                        }
                    }

                    $response['prediction-test'] = $prediction_reports;
                }
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
                    "authorizationToken" => $user['auth_token'],
                    "comment" => "user's old information."
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

                    // -------------------------
                    // Send STYLED change password email (NO PASSWORD INCLUDED)
                    // -------------------------
                    $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Change Password"));

                    if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

                        $timestamp = date("F j, Y \a\\t g:i A");
                        $reset_link = "https://staging-test-prep.asvab-tutoring.com/forgot-password";
                        $user_name = $user['first_name'] . " " . $user['last_name'];
                        $first_name = $user['first_name'];
                        $email = $user['email'];

                        $body = $mail['template'];

                        // Replace placeholders (NO PASSWORD REPLACEMENT)
                        $body = str_replace("{name}", $user_name, $body);
                        $body = str_replace("{first_name}", $first_name, $body);
                        $body = str_replace("{email}", $email, $body);
                        $body = str_replace("{timestamp}", $timestamp, $body);
                        $body = str_replace("{reset_link}", $reset_link, $body);

                        // TRIPLE DECODE (SAME AS TUTOR-REQUEST)
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                        // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                        $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                        // ASSETS
                        $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                        $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";

                        // LOAD STYLED EMAIL TEMPLATE
                        $inner_body = $body;
                        $footer_reason = "You received this because your account password was recently changed.";
                        ob_start();
                        include __DIR__ . '/email-layouts/standard-wrapper.php';
                        $styled_body = ob_get_clean();

                        // Apply inline styles (SAME AS TUTOR-REQUEST)
                        $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                        $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                        $styled_body = str_replace('<a', '<a style="color: #004a99; text-decoration: underline;"', $styled_body);
                        $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                        $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);

                        $subject = $mail['subject'];
                        // Replace email placeholder in subject if present
                        $subject = str_replace("{email}", $email, $subject);

                        smtpmailer($email, "info@asvab-tutoring.com", "ASVAB Tutoring", $subject, $styled_body);
                    }
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

        case "get-contact-us-user-data":
            if ($_POST['authorizationToken']) {
                $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));
                if ($exist) {
                    $user = getDataByJoin(array("user as u", "login as l"), "u.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                    $response['first_name'] = $user['first_name'];
                    $response['last_name'] = $user['last_name'];
                    $response['email'] = $user['email'];
                    $response['mobile'] = $user['mobile'];

                } else {
                    $response['error'] = "Token does not exist!";
                }
            } else {
                $response['error'] = "Authorization token is required!";
            }
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

            if (!empty($mail) && isset($mail['template'], $mail['subject'])) {
                $first_name = $_POST['first_name'];
                $full_name = $_POST['first_name'] . " " . $_POST['last_name'];
                $email = $_POST['email'];
                $raw_message = strip_tags($_POST['message']); // Get clean text for preview
                $message_preview = (strlen($raw_message) > 150) ? substr($raw_message, 0, 147) . "..." : $raw_message;

                $body = $mail['template'];

                // Replace placeholders (Standardizing with asvab-api.php pattern)
                $body = str_replace("{first_name}", $first_name, $body);
                $body = str_replace("{name}", $full_name, $body);
                $body = str_replace("{email}", $email, $body);
                $body = str_replace("{message_preview}", $message_preview, $body);

                // TRIPLE DECODE (Pattern from asvab-api.php)
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                // LOAD STYLED EMAIL TEMPLATE
                $inner_body = $body;
                $footer_reason = "You received this because you submitted an inquiry on our app.";
                ob_start();
                include __DIR__ . '/email-layouts/standard-wrapper.php';
                $styled_body = ob_get_clean();

                // Apply inline styles (consistent with other registration emails)
                $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                $styled_body = str_replace('<a', '<a style="color: #004a99; text-decoration: underline;"', $styled_body);
                $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);
                $styled_body = str_replace('<br/>', '<br style="display: block;">', $styled_body);

                $subject = $mail['subject'];
                smtpmailer($_POST['email'], $configs['support_mail'], ucwords($configs['dotcom']), $subject, $styled_body);
            }

            // Send email to admin

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

                // -------------------------
                // Send STYLED error report email
                // -------------------------
                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Report Error"));

                if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

                    $body = $mail['template'];

                    // Replace placeholders
                    $body = str_replace("{grade}", $test_name, $body);
                    $body = str_replace("{lesson_name}", $lesson_name, $body);
                    $body = str_replace("{lesson_id}", $_POST['lesson_id'], $body);
                    $body = str_replace("{cat_name}", $cat_name, $body);
                    $body = str_replace("{cat_id}", $_POST['cat_id'], $body);
                    $body = str_replace("{date}", showdate("Y-m-d"), $body);
                    $body = str_replace("{message}", $error, $body);

                    // TRIPLE DECODE
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    // CLEAN UP - ONLY REMOVE TRULY EMPTY PARAGRAPHS
                    $body = preg_replace('/<p>\s*<\/p>/i', '', $body);

                    // ASSETS
                    $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                    $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";

                    // LOAD STYLED EMAIL TEMPLATE
                    $inner_body = $body;
                    $footer_reason = "This is an automated error report notification.";
                    ob_start();
                    include __DIR__ . '/email-layouts/standard-wrapper.php';
                    $styled_body = ob_get_clean();

                    // Apply inline styles
                    $styled_body = str_replace('<p>', '<p style="margin: 0 0 12px 0; line-height: 1.6; color: #333333;">', $styled_body);
                    $styled_body = str_replace('<strong>', '<strong style="color: #004a99; font-weight: 600;">', $styled_body);
                    $styled_body = str_replace('<a', '<a style="color: #004a99; text-decoration: underline;"', $styled_body);
                    $styled_body = str_replace('<br>', '<br style="display: block;">', $styled_body);
                    $styled_body = str_replace('<br />', '<br style="display: block;">', $styled_body);
                    $styled_body = str_replace('<br/>', '<br style="display: block;">', $styled_body);

                    $subject = str_replace("{name}", $user['first_name'], $mail['subject']);
                    smtpmailer("info@asvab-tutoring.com", $user['email'], ucwords($configs['dotcom']), $subject, $styled_body);
                }

                $response['success_msg'] = "Thanks for report this issue.Our team will do the needful.";
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "terms-and-conditions":

            $response['url'] = "https://asvab-tutoring.com/terms-of-service";

            break;

        case "about-us":

            $response['url'] = "https://asvab-tutoring.com/about-us";

            break;

        case "privacy-policy":

            $response['url'] = "https://asvab-tutoring.com/privacy-policy";

            break;

        case "submit-feedback":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));

                $user_id = $user['user_id'];
                $email = $user['email'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $course_name = getFldValueById("course", "course_name", array("course_id" => $test_id));

                $feedback_text = trim($_POST['feedback_text'] ?? '');

                if ($feedback_text == '') {
                    $response['error'] = "Feedback text is required.";
                    break;
                }

                $insert_data = [
                    "feedback_text" => $feedback_text,
                    "email" => $email,
                    "user_id" => $user_id,
                    "course_name" => $course_name,
                    "platform" => $_POST['platform'],
                    "submitted_at" => date("Y-m-d H:i:s")
                ];

                $insert_id = insertTable("app_feedback", $insert_data);

                if ($insert_id) {
                    $response["message"] = "Feedback submitted successfully!";
                    $response["isSuccess"] = true;
                } else {
                    $response["error"] = "Failed to submit feedback.";
                    $response["isSuccess"] = false;
                }
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "save-question":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];

                $is_saved = (int)getTotalRow("app_saved_questions", array("user_id" => $user_id, "question_id" => $_POST['question_id'], "lesson_id" => $_POST['lesson_id'], "cat_id" => $_POST['cat_id']));

                if ($is_saved) {
                    $response["message"] = "Already Saved for more practice!";
                    $response["isSuccess"] = false;
                } else {

                    $insert_data = [
                        "user_id" => $user_id,
                        "question_id" => $_POST['question_id'],
                        "lesson_id" => $_POST['lesson_id'],
                        "cat_id" => $_POST['cat_id'],
                        "ord" => $_POST['ques_serial_no'],
                        "date" => date("Y-m-d")
                    ];

                    $insert_id = insertTable("app_saved_questions", $insert_data);

                    if ($insert_id) {
                        $response["message"] = "Question saved successfully!";
                        $response["isSuccess"] = true;
                    } else {
                        $response["error"] = "Failed to save the question.";
                        $response["isSuccess"] = false;
                    }
                }
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "remove-question":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));

                $user_id = $user['user_id'];

                $flag = deleteData("app_saved_questions", array('user_id' => $user_id, 'question_id' => $_POST['question_id'], "lesson_id" => $_POST['lesson_id'], "cat_id" => $_POST['cat_id']));

                if ($flag) {
                    $response["message"] = "Question removed successfully!";
                    $response["isSuccess"] = true;
                } else {
                    $response["error"] = "Failed to remove the question.";
                    $response["isSuccess"] = false;
                }
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "get_saved_ques_category":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                if ($test_id) {
                    $icon = "uploads/test.png";

                    $cat_list = getRowsByJoin(array("category as c", "app_saved_questions as a"), "c.*", array("c.course_id" => $test_id, "c.online" => "Y", "a.user_id" => $user_id), array("c.cat_id" => "a.cat_id"), "c.category_order", "", "", "c.cat_id");

                    $list = array();

                    foreach ($cat_list as $c) {
                        $list[] = ["id" => $c['cat_id'], "name" => $c['category_name'], "icon" => $configs['vpath'] . $icon];
                    }

                    $response['category_list'] = $list;
                }
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "get_saved_ques_lessons":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $paid_member = $user['activation_status'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $lessons = getRowsByJoin(array("lesson as l", "app_saved_questions as a"), "l.*", array("l.cat_id" => $_POST['cat_id'], "l.online" => "Y", "a.user_id" => $user_id), array("l.lesson_id" => "a.lesson_id"), "l.lesson_order", "", "", "l.lesson_id");

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
                    $list[] = array("cat_id" => $c['cat_id'], "lesson_id" => $c['lesson_id'], "lesson_name" => $c['Lesson_name'], "icon" => $c['icon'], "paid" => (int)$c['paid'], "lesson_order" => (int)$c['lesson_order']);
                }
                $response['list'] = $list;
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "lesson-saved-questions":

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
                    $has_order_no = getTotalRow("lesson_questions", array("lesson_id" => $_POST['lesson_id'], "cat_id" => $_POST['cat_id'], "HW" => 1, "course_id" => $test_id, "order_no>" => 0));

                    //                    $response['check_order_no'] = $has_order_no;

                    if ($has_order_no) {
                        $order_by = 'l.order_no';
                    } else {
                        $order_by = "q.qid";
                    }

                    // function getTotalRowsByJoin($table,$cond=array(),$relation=array(),$print=0,$group_by="")

                    $response['homework_total'] = (int)getTotalRow("app_saved_questions", array("cat_id" => $lesson['cat_id'], "lesson_id" => $lesson['lesson_id'], "user_id" => $user_id));

                    if (in_array($_POST['cat_id'], $data['story_question'])) {

                        $story_list = getQueryByJoin(array("question as q", "lesson_questions as l", "app_saved_questions as a"), "q.story_id", array("l.cat_id" => (int)$_POST['cat_id'], "l.lesson_id" => (int)$_POST['lesson_id'], "l.HW" => 1, "a.user_id" => $user_id), array("q.qid" => "l.qid", "l.qid" => "a.question_id"), $order_by, '', '', 'q.story_id');

                        $homework_list = [];
                        $serial_no = 0;
                        $qlist = [];
                        foreach ($story_list as $a) {

                            $homework_list = getQueryByJoin(array("question as q", "lesson_questions as l", "story as s"), "q.*,s.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "q.story_id" => $a['story_id']), array("q.qid" => "l.qid", "q.story_id" => "s.story_id"), $order_by, '', '', "q.qid");

                            foreach ($homework_list as $d) {
                                $serial_no++;
                                $qlist[] = array("story" => html_entity_decode(html_entity_decode(html_entity_decode($d['story']))), "serial_no" => $serial_no, "qid" => $d['qid'], "cat_id" => $d['cat_id'], "lesson_id" => $d['lesson_id'], "question" => html_entity_decode($d['question']), "question_explanation" => html_entity_decode(html_entity_decode($d['explanation'])), "correct_answer" => $data['alphabet'][$d['answer']], "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))));
                            }
                        }

                        $response['homework_questions'] = $qlist;
                    } else {
                        $homework_list = getQueryByJoin(array("question as q", "lesson_questions as l", "app_saved_questions as a"), "q.*,l.*", array("l.lesson_id" => $_POST['lesson_id'], "l.cat_id" => $_POST['cat_id'], "l.HW" => 1, "a.user_id" => $user_id), array("q.qid" => "l.qid", "l.qid" => "a.question_id"), $order_by, '', '', "q.qid");

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
                    $response["isSuccess"] = true;
                } else {
                    $response['message'] = "Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $" . $configs['subscription_fees'] . " USD for " . $configs['validity'] . " days.";
                    $response["status"] = 201;
                    $response["isSuccess"] = false;
                    $response["data"] = [];
                }
            } else {
                $response['error'] = "Token does not exist!";
                $response["status"] = 201;
                $response["isSuccess"] = false;
            }
            break;


        case "review-prediction-test": //after submiting prediction test, view the submitted questions on clicking "review test" button

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(array("user as u", "login as l"), "u.*,l.*", array("l.auth_token" => $_POST['authorizationToken']), array("u.user_id" => "l.user_id"));
                $user_id = $user['user_id'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));

                $tables = getExamTables($test_id);

                $exam_table = $tables['exam_table'];
                $exam_record_table = $tables['exam_record_table'];

                $category = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_POST['pred_test_id']));
                $date = getFldValueById("result", "result_date", array("result_id" => $_POST['result_id']));
                $pred_set_id = (int)getFldValueById("result", "pred_set_id", array("result_id" => $_POST['result_id']));
                $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_POST['pred_test_id']));
                $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_POST['pred_test_id']), "", "", "", "id");

                $get_parent_id = getFldValueById("result", "parent_id", array("result_id" => $_POST['result_id']));

                $pred_test_report = [];
                $pred_cat_name = [];
                $total_array = [];
                $i = 0;

                // $response['testing'] = [];

                foreach ($pred_cat_list as $p) {
                    if (!$i && !$_POST['cat_id']) {
                        $_POST['cat_id'] = $p['cat_id'];
                    }
                    $i++;
                    $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                    $cat_id = $p['cat_id'];
                    $cat_name = $cat['category_name'];
                    $total_array[$cat['cat_id']] = $p['number_of_questions'];
                    $pred_cat_name[] = ["cat_id" => $cat_id, "cat_name" => $cat_name];

                    $tmp_score = getQuery("result", "*", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id, "score!" => -1, "parent_id" => $get_parent_id), "result_id desc", 1);
                    foreach ($tmp_score as $t) {
                        $pred_test_report[$cat_name] = array("cat_name" => $cat_name, "result_id" => $t['result_id'], "cat_id" => $t['cat_id'], "pred_test_id" => $t['Predic_test_id'], "total_questions" => $t['total_questions'], "correct_answers" => $t['correct_answers'], "wrong_answers" => ($t['total_questions'] - $t['correct_answers']), "score" => (int)$t['score'], "result_date" => $t['result_date'], "parent_id" => $t['parent_id']);
                    }
                }

                $response['pred_test_cat_wise_scores'] = $pred_test_report;

                $total = getFldValueById("result", "total_questions", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "result_id" => $_POST['result_id'], "pred_set_id" => $pred_set_id));
                $total_correct = getFldValueById("result", "correct_answers", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "result_id" => $_POST['result_id'], "pred_set_id" => $pred_set_id));
                $response['prediction_score'] = (int)getFldValueById("result", "score", array("user_id" => $user_id, "Predic_test_id" => $_POST['pred_test_id'], "result_id" => $_POST['result_id'], "pred_set_id" => $pred_set_id));

                $total_wrong = $total - $total_correct;

                $my_answers = array();
                $answers = array();
                $story = array();
                $questions = [];
                $story_list = [];
                $qlist = array();

                $cat_id = $_POST['cat_id'];
                $response['cat_name'] = getFldValueByid("category", "category_name", array("cat_id" => $cat_id));
                $response['cat_id'] = $cat_id;
                $option_no = 5;
                $i = 0;

                if (in_array($cat_id, $data['story_question'])) {
                    $cat_result_id = getFldValueByid("result", "result_id", array("parent_id" => $_POST['result_id'], "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id));

                    $story_list[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.cat_id" => (int)$cat_id, "e.Predic_test_id" => $_POST['pred_test_id'], "e.user_id" => $user_id, "e.pred_set_id" => $pred_set_id, "e.result_id" => $cat_result_id), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");

                    foreach ($story_list[$cat_id] as $a) {
                        $story[$a['story_id']] = getFldValueById("story", "story", array("story_id" => $a['story_id']));

                        $data['question_test'][$a['story_id']] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*,e.*", array("e.cat_id" => (int)$cat_id, "e.user_id" => $user_id, "e.Predic_test_id" => $_POST['pred_test_id'], "e.result_id" => $cat_result_id, "q.story_id" => $a['story_id']), array("q.qid" => "e.qid"), 'e.id', '', '', "q.qid");

                        foreach ($data['question_test'][$a['story_id']] as $d) {

                            $i++;

                            if (!$d['user_ans']) {
                                $text = "No Answer";
                            } elseif ($d['user_ans'] == $d['answer']) {
                                $text = 'Correct';
                            } else {
                                $text = 'Wrong Answer';
                            }

                            $correct_answer = $data['alphabet'][$d['answer']] . " | Your answer is " .  $data['alphabet'][$d['user_ans']] . " | " . $text;
                            $explanation = html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['explanation'])));

                            $qlist[] = array("story" => html_entity_decode(html_entity_decode(html_entity_decode($story[$a['story_id']]))), "srno" => $i, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "correct_answer" => $correct_answer, "myanswer" => $d['user_ans'], "explanation" => $explanation);
                        }
                    }
                } else {
                    $cat_result_id = getFldValueByid("result", "result_id", array("parent_id" => $get_parent_id, "Predic_test_id" => $_POST['pred_test_id'], "cat_id" => $cat_id));

                    $questions[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*, e.*", array("e.cat_id" => $cat_id, "e.user_id" => $user_id, "e.Predic_test_id" => $_POST['pred_test_id'], "e.pred_set_id" => $pred_set_id, "e.result_id" => $cat_result_id), array("e.qid" => "q.qid"), "e.id", $total_array[$cat_id]);

                    $i = 0;

                    foreach ($questions[$cat_id] as $d) {

                        $i++;

                        $my_answer = $d['user_ans'];

                        if (!$my_answer) {
                            $text = "No Answer";
                        } elseif ($my_answer == $d['answer']) {
                            $text = 'Correct';
                        } else {
                            $text = 'Wrong Answer';
                        }

                        $correct_answer = $data['alphabet'][$d['answer']] . " | Your answer is " .  $data['alphabet'][$my_answer] . " | " . $text;

                        $explanation = html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['explanation'])));

                        $qlist[] = array("srno" => $i, "question" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['question']))), "option_a" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_a']))), "option_b" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_b']))), "option_c" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_c']))), "option_d" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_d']))), "option_e" => html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $d['option_e']))), "correct_answer" => $correct_answer, "myanswer" => $d['user_ans'], "explanation" => $explanation);
                    }
                }

                $response['questions'] = $qlist;
            } else {
                $response['error'] = "Token does not exist!";
            }
            break;

        case "tutoring-pricing-web-url":

            if ($_POST['course_id'] == 28 || $_POST['course_id'] == 38 || $_POST['course_id'] == 39) {
                $web_url = "https://www.asvab-tutoring.com/package-details/1-on-1-tutoring/asvab";
            } else if ($_POST['course_id'] == 24) {
                $web_url = "https://www.asvab-tutoring.com/package-details/1-on-1-tutoring/afoqt";
            } else if ($_POST['course_id'] == 21) {
                $web_url = "https://www.asvab-tutoring.com/package-details/1-on-1-tutoring/oar";
            } else if ($_POST['course_id'] == 25) {
                $web_url = "https://www.asvab-tutoring.com/package-details/1-on-1-tutoring/sift";
            } else if ($_POST['course_id'] == 26) {
                $web_url = "https://www.asvab-tutoring.com/package-details/1-on-1-tutoring/astb";
            } else {
                $web_url = "https://staging-test-prep.asvab-tutoring.com/get-tutor?course_id=" . $_POST['course_id'] . "&isapp=1";
            }

            $response['web_url'] = $web_url;

            break;

        case "delete-account-for-developers":

            $exist = getTotalRow("user", array("email" => $_POST['email']));

            if ($exist) {
                $user_list = getQuery("user", "*", array("email" => $_POST['email']));

                foreach ($user_list as $user) {
                    $user_id = $user['user_id'];

                    deleteData("user", array('user_id' => $user_id));
                    deleteData("login", array('user_id' => $user_id));
                    deleteData("subscription", array('user_id' => $user_id));
                }

                $response["status"] = 200;
                $response["isSuccess"] = true;
                $response["message"] = $exist . " account(s) deleted successfully";
            } else {
                $response["status"] = 201;
                $response["isSuccess"] = false;
                $response["message"] = "Email does not exist!";
            }

            break;

        case "submit-app-review":

            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist) {
                $user = getDataByJoin(
                    array("user as u", "login as l"),
                    "u.*,l.*",
                    array("l.auth_token" => $_POST['authorizationToken']),
                    array("u.user_id" => "l.user_id")
                );

                $user_id = $user['user_id'];
                $email = $user['email'];
                $test_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $course_name = getFldValueById("course", "course_name", array("course_id" => $test_id));

                $final_actual_score = isset($_POST['final_actual_score']) ? (int)$_POST['final_actual_score'] : 0;
                $initial_practice_score = trim($_POST['initial_practice_score'] ?? '');
                $helpfulness_rating = trim($_POST['helpfulness_rating'] ?? '');
                $experience_text = trim($_POST['experience_text'] ?? '');
                $features_used = trim($_POST['features_used'] ?? '');
                $share_permission = (isset($_POST['share_permission']) && $_POST['share_permission'] == '1') ? 1 : 0;
                $platform = trim($_POST['platform'] ?? '');

                $allowed_ratings = array('very_helpful', 'somewhat_helpful', 'not_helpful');
                $allowed_features = array(
                    'full_length_mock_exams',
                    'subtest_practice_drills',
                    'sentence_word_flashcards',
                    'performance_analytics'
                );

                if ($final_actual_score < 1 || $final_actual_score > 99) {
                    $response['error'] = "Final actual score must be between 1 and 99.";
                    $response['isSuccess'] = false;
                    break;
                }

                if ($initial_practice_score !== '') {
                    $initial_practice_score = (int)$initial_practice_score;
                    if ($initial_practice_score < 1 || $initial_practice_score > 99) {
                        $response['error'] = "Initial practice score must be between 1 and 99.";
                        $response['isSuccess'] = false;
                        break;
                    }
                } else {
                    $initial_practice_score = null;
                }

                if (!in_array($helpfulness_rating, $allowed_ratings, true)) {
                    $response['error'] = "Invalid helpfulness rating.";
                    $response['isSuccess'] = false;
                    break;
                }

                if ($experience_text == '') {
                    $response['error'] = "Experience text is required.";
                    $response['isSuccess'] = false;
                    break;
                }

                if ($features_used !== '') {
                    $feature_list = array_filter(array_map('trim', explode(',', $features_used)));
                    $invalid_features = array_diff($feature_list, $allowed_features);

                    if (!empty($invalid_features)) {
                        $response['error'] = "Invalid feature selection.";
                        $response['isSuccess'] = false;
                        break;
                    }

                    $features_used = implode(',', $feature_list);
                } else {
                    $features_used = null;
                }

                $insert_data = array(
                    "user_id" => $user_id,
                    "email" => $email,
                    "course_name" => $course_name,
                    "final_actual_score" => $final_actual_score,
                    "initial_practice_score" => $initial_practice_score,
                    "features_used" => $features_used,
                    "helpfulness_rating" => $helpfulness_rating,
                    "experience_text" => $experience_text,
                    "share_permission" => $share_permission,
                    "platform" => $platform,
                    "submitted_at" => date("Y-m-d H:i:s")
                );

                $insert_id = insertTable("app_review", $insert_data);

                if ($insert_id) {
                    $response["message"] = "Review submitted successfully!";
                    $response["isSuccess"] = true;
                } else {
                    $response["error"] = "Failed to submit review.";
                    $response["isSuccess"] = false;
                }
            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

        case "1-on-1-tutor-request":
            
            $exist = getTotalRow("login", array("auth_token" => $_POST['authorizationToken']));

            if ($exist > 0) {
                // Token exists, proceed with the request
                $user = getDataByJoin(
                    array("user as u", "login as l"),
                    "u.*,l.*",
                    array("l.auth_token" => $_POST['authorizationToken']),
                    array("u.user_id" => "l.user_id")
                );

                $user_id = $user['user_id'];
                $course_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
                $course_name = getFldValueById("course", "course_name", array("course_id" => $course_id));

                $response['1-on-1-tutor-request-url'] = "https://www.asvab-tutoring.com/package-details/1-on-1-tutoring/" . strtolower(str_replace(' ', '-', $course_name));

            } else {
                $response['error'] = "Token does not exist!";
            }

            break;

    }

    echo json_encode($response);
    die();
}
