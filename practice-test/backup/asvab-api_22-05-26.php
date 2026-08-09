<?
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
include("configs/config.php");
include("configs/common.php");
include("configs/license.php");
include("configs/array.php");
include("smtpmail.php");

// // Handle preflight OPTIONS request
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(200);
//     exit();
// }

// header('Content-Type: application/json');
// include("configs/config.php");

register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            "ok" => false,
            "message" => "Fatal error",
            "error" => $e["message"],
            "file" => $e["file"],
            "line" => $e["line"],
        ]);
        exit;
    }
});

$option_no = 4;

$setting = getDataByWhere("setting", "*", array("id" => 1));

$configs = array_merge($configs, $setting);

$response = array();

if ($_POST['action']) {

    switch ($_POST['action']) {

        case "admin-login":
            // Check if username and password are provided
            if (!empty($_POST['username']) && !empty($_POST['password'])) {

                // Sanitize inputs
                $username = trim($_POST['username']);
                $password = md5(trim($_POST['password'])); // Consider using password_hash() and password_verify() in real applications

                // Check if user exists
                $is_exist = (int)getTotalRow("admin", [
                    "username" => $username,
                    "password" => $password,
                    "status"   => 'Y'
                ]);

                if ($is_exist) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Login successful."
                    ];
                } else {
                    $response = [
                        "status"     => 401,
                        "isSuccess"  => false,
                        "message"    => "Invalid username or password."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Username and password are required."
                ];
            }
            break;

        case "check-email":
            $email = strtolower(trim($_POST['email'] ?? ''));

            if (empty($email)) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Email is required."
                ];
                break;
            }

            $is_exist = (int)getTotalRow("user", ["email" => $email]);

            if ($is_exist) {
                $response = [
                    "status"    => 409,
                    "isSuccess" => false,
                    "code"      => "EMAIL_EXISTS",
                    "message"   => "This email already exists. Please use a different email."
                ];
            } else {
                $response = [
                    "status"    => 200,
                    "isSuccess" => true,
                ];
            }
            break;

        case "get-state-list":

            $state_list = getQuery("state", "*", array("country_id" => 230));

            foreach ($state_list as $state) {
                $response[] = [
                    "id"   => $state['id'],
                    "name" => trim($state['name'])
                ];
            }

            break;

        case "tutor-request":

            $flag = insertTable("asvab_tutor_request", array(
                "name"     => $_POST['name'],
                "email"    => $_POST['email'],
                "phone"    => $_POST['phone'],
                "state_id" => $_POST['state_id'],
                "message"  => $_POST['message'],
                "request_from" => "asvab-web"
            ));

            if ($flag) {

                // -------------------------
                // 1) Send STYLED email to the user
                // -------------------------
                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "asvab tutor"));
                if (!empty($mail) && isset($mail['template'], $mail['subject'])) {
                    $tutoring_type = "1-on-1 Tutoring";
                    $first_name = explode(" ", $_POST['name'])[0];
                    $timestamp = date("F j, Y \a\\t g:i A");
                    $body = $mail['template'];
                    $body = str_replace("{name}", $_POST['name'], $body);
                    $body = str_replace("{first_name}", $first_name, $body);
                    $body = str_replace("{tutoring_type}", $tutoring_type, $body);
                    $body = str_replace("{timestamp}", $timestamp, $body);
                    
                    // TRIPLE DECODE
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    
                    // CLEAN UP
                    $body = preg_replace('/<p>\s*<\/p>/i', '', $body);
                    
                    // ASSETS
                    $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                    $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";
                    
                    // LOAD STYLED EMAIL TEMPLATE
                    $inner_body = $body;
                    $footer_reason = "You received this because you requested a tutor on our website.";
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
                    smtpmailer($_POST['email'], "info@asvab-tutoring.com", $_POST['name'], $subject, $styled_body);
                }

                // -------------------------
                // 2) Send STYLED email to the asvab tutoring (Admin)
                // -------------------------
                $state_info = getDataByWhere("state", "*", array("id" => $_POST['state_id']));
                $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "get-tutor-request"));
                
                if (!empty($mail) && isset($mail['template'], $mail['subject'])) {
                    $body = $mail['template'];
                    $body = str_replace("{name}", $_POST['name'], $body);
                    $body = str_replace("{email}", $_POST['email'], $body);
                    $body = str_replace("{phone}", $_POST['phone'], $body);
                    $body = str_replace("{state}", $state_info['name'], $body);
                    $body = str_replace("{message}", nl2br($_POST['message']) . '<br><br>', $body);
                    
                    // TRIPLE DECODE
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    
                    // CLEAN UP
                    $body = preg_replace('/<p>\s*<\/p>/i', '', $body);
                    
                    // ASSETS
                    $logo_url = "https://staging-test-prep.asvab-tutoring.com/images/asvab_tutoring_logo.png";
                    $bg_pattern_url = "https://staging-test-prep.asvab-tutoring.com/images/review_bg-1.jpg";
                    
                    // LOAD STYLED EMAIL TEMPLATE
                    $inner_body = $body;
                    $footer_reason = "This is a new tutor request notification.";
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
                    smtpmailer("info@asvab-tutoring.com", "info@asvab-tutoring.com", "ASVAB Tutoring", $subject, $styled_body);
                }

                $response = [
                    "isSuccess" => true,
                    "message"   => "Tutor request has been sent successfully.",
                    "data"      => $_POST
                ];
            } else {
                $response = [
                    "isSuccess" => false,
                    "message"   => "Failed to send tutor request. Please try again later."
                ];
            }
            break;

        case "add-tutoring-package":

            // Basic validation
            if (!empty($_POST['course_id']) && !empty($_POST['description']) && !empty($_POST['title']) && !empty($_POST['code_name']) && !empty($_POST['num_of_sessions'])) {

                $flag = insertTable("asvab_tutor_package", array(
                    "course_id"       => (int)$_POST['course_id'],
                    "title"           => $_POST['title'],
                    "code_name"       => $_POST['code_name'],
                    "description"     => $_POST['description'],
                    "num_of_sessions" => (int)$_POST['num_of_sessions'],
                    "ord"           => (int)$_POST['order'],
                ));

                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Tutoring package added successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to add tutoring package. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Please fill up all the fields."
                ];
            }
            break;

        case "get-tutoring-packages":

            // Fetch all tutoring packages
            $result = getQuery("asvab_tutor_package", "*", array(), "course_id, ord");

            if ($result && mysqli_num_rows($result) > 0) {
                $packages = [];

                while ($row = mysqli_fetch_assoc($result)) {
                    $row['course_name'] = getDataByWhere("asvab_course", "name", array("course_id" => $row['course_id']))['name'];
                    $packages[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Tutor packages fetched successfully.",
                    "data"       => $packages
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No tutor packages found.",
                    "data"       => []
                ];
            }

            break;

        case "get-1-on-1-tutoring-packages":

            // Fetch all tutoring packages
            $result = getQuery("asvab_tutor_package", "*", array("is_group_tutoring" => 0), "course_id, ord");

            if ($result && mysqli_num_rows($result) > 0) {
                $packages = [];

                while ($row = mysqli_fetch_assoc($result)) {
                    $row['course_name'] = getDataByWhere("asvab_course", "name", array("course_id" => $row['course_id']))['name'];
                    $packages[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Tutor packages fetched successfully.",
                    "data"       => $packages
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No tutor packages found.",
                    "data"       => []
                ];
            }

            break;

        case "get-group-tutoring-packages":

            // Fetch all tutoring packages
            $result = getQuery("asvab_tutor_package", "*", array("is_group_tutoring" => 1), "course_id, ord");

            if ($result && mysqli_num_rows($result) > 0) {
                $packages = [];

                while ($row = mysqli_fetch_assoc($result)) {
                    $row['course_name'] = getDataByWhere("asvab_course", "name", array("course_id" => $row['course_id']))['name'];
                    $packages[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Tutor packages fetched successfully.",
                    "data"       => $packages
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No tutor packages found.",
                    "data"       => []
                ];
            }

            break;

        case "remove-tutoring-package":
            if (!empty($_POST['package_id'])) {
                $package_id = (int)$_POST['package_id'];

                $exist = getTotalRow("asvab_tutor_package", array("package_id" => $package_id));

                if ($exist) {
                    $flag = deleteData("asvab_tutor_package", array("package_id" => $package_id));

                    if ($flag) {
                        $response = [
                            "status"     => 200,
                            "isSuccess"  => true,
                            "message"    => "Tutoring package removed successfully."
                        ];
                    } else {
                        $response = [
                            "status"     => 500,
                            "isSuccess"  => false,
                            "message"    => "Failed to remove tutoring package. Please try again later."
                        ];
                    }
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Tutoring package not found."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Package ID is required."
                ];
            }
            break;

        case "add-tutoring-package-sessions":

            // Basic validation
            if (!empty($_POST['package_id']) && !empty($_POST['title']) && !empty($_POST['price'])) {

                $flag = insertTable("asvab_tutor_package_sessions", array(
                    "package_id"      => (int)$_POST['package_id'],
                    "title"   => $_POST['title'],
                    "price" => $_POST['price'],
                    "feature" => $_POST['feature'],
                    "ord"           => (int)$_POST['order'],
                    "is_most_popular" => (int)$_POST['is_most_popular']
                ));

                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Tutoring package session added successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to add tutoring package session. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Please fill up all the fields."
                ];
            }
            break;

        case "get-tutoring-package-sessions":

            $result = getQuery("asvab_tutor_package_sessions", "*", array(), "package_id, ord");

            if ($result && mysqli_num_rows($result) > 0) {
                $sessions_list = [];

                while ($row = mysqli_fetch_assoc($result)) {
                    $row['package_title'] = getDataByWhere("asvab_tutor_package", "title", array("package_id" => $row['package_id']))['title'];
                    $sessions_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Tutoring package sessions fetched successfully.",
                    "data"       => $sessions_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No tutoring package sessions found.",
                    "data"       => []
                ];
            }

            break;

        case "remove-tutoring-package-session":
            if (!empty($_POST['session_id'])) {
                $session_id = (int)$_POST['session_id'];

                $exist = getTotalRow("asvab_tutor_package_sessions", array("session_id" => $session_id));

                if ($exist) {
                    $flag = deleteData("asvab_tutor_package_sessions", array("session_id" => $session_id));

                    if ($flag) {
                        $response = [
                            "status"     => 200,
                            "isSuccess"  => true,
                            "message"    => "Tutoring package session removed successfully."
                        ];
                    } else {
                        $response = [
                            "status"     => 500,
                            "isSuccess"  => false,
                            "message"    => "Failed to remove tutoring package session. Please try again later."
                        ];
                    }
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Tutoring package session not found."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Session ID is required."
                ];
            }
            break;

        case "tutoring-package-details-page":

            if (!empty($_POST['course_id'])) {
                $course_id = (int)$_POST['course_id'];

                $course_data = getDataByWhere("asvab_course", "*", array("course_id" => $course_id));

                $intro_list = [
                    "SIFT" => "ASVAB Tutoring helps you to fulfill your dreams by ensuring that you cover all the important areas to become an Aviation Officer. It prepares you for the area of defense you strive for. During the course, you can hone your skills and learn different strategies to crack the SIFT Test.",

                    "AFOQT" => "The Air Force Officer Qualifying Test (AFOQT) is a standardized exam assessing verbal, math, and career-specific aptitudes. It is required for officer commissioning programs such as OTS and Air Force ROTC, qualifies candidates for Pilot, CSO, and ABM training, contributes to the PCSM score, and is mandatory for all scholarship and POC cadets.",

                    "OAR" => "Want to be a US Navy Officer? First, you must prepare for the OAR (Officer Aptitude Rating) Test. A student can take entire ASTB Test or only the OAR. OAR is for those individuals who only want to become an officer, rather than pursuing the career in aviation.  ASVAB Tutoring provides comprehensive and easy to grab study materials to prepare students with confidence.",

                    "ASVAB" => "ASVAB Tutoring helps you to fulfill your dreams by ensuring that you cover all the important areas to enlist yourself in the US DoD. It prepares you for the area of defense you strive for. During the course, you can hone your skills and learn different strategies to cracking the Military Entrance Test (MET).",

                    "ASTB" => "Those who want to be the part of U.S Navy, Coast Guard and Marine Corps can utilize the help of ASVAB Tutoring. We provide ASTB (Aviation Selection Test Battery) Test tutoring to optimize their chances of being placed in the programs of their choice."
                ];

                if ($course_data) {

                    $packages = getQuery("asvab_tutor_package", "*", array("course_id" => $course_id), "ord");

                    $package_list = [];
                    $package_ids = [];
                    foreach ($packages as $package) {
                        $package_ids[] = $package['package_id'];
                        $sessions = getQuery("asvab_tutor_package_sessions", "*", array("package_id" => $package['package_id']), "ord");
                        $package['sessions'] = [];
                        $flag_sub = false;
                        foreach ($sessions as $session) {
                            if ($session['is_group_tutoring'] == 2) {
                                $session['is_online_study'] = true;
                                $flag_sub = true;
                            } else {
                                $session['is_online_study'] = false;
                                $flag_sub = false;
                            }
                            $package['sessions'][] = $session;
                        }
                        if ($flag_sub) {
                            $package['is_online_study'] = true;
                        } else {
                            $package['is_online_study'] = false;
                        }
                        $package_list[] = $package;
                    }

                    $min_price = 0;
                    $max_price = 0;

                    if (!empty($package_ids)) {
                        $ids_str = implode(",", $package_ids);

                        $min_price = getFldValueById(
                            "asvab_tutor_package_sessions",
                            "MIN(price)",
                            "package_id IN ($ids_str) AND limited_time != '1'"
                        );

                        $max_price = getFldValueById(
                            "asvab_tutor_package_sessions",
                            "MAX(price)",
                            "package_id IN ($ids_str) AND limited_time != '1'"
                        );
                    }

                    $related_course_list = [];
                    $related_courses = getQuery("asvab_course", "*", array("course_id !" => (int)$course_id, "status" => "Y"), "ord");

                    foreach ($related_courses as $course) {
                        // get all package IDs for this course

                        $packages = getQuery("asvab_tutor_package", "package_id", array("course_id" => $course['course_id']));

                        $package_ids = [];

                        foreach ($packages as $pkg) {
                            $package_ids[] = $pkg['package_id'];
                        }

                        $min_price_r = 0;
                        $max_price_r = 0;

                        if (!empty($package_ids)) {
                            $ids_str = implode(",", $package_ids);

                            $min_price_r = getFldValueById(
                                "asvab_tutor_package_sessions",
                                "MIN(price)",
                                "package_id IN ($ids_str) AND limited_time != '1'"
                            );

                            $max_price_r = getFldValueById(
                                "asvab_tutor_package_sessions",
                                "MAX(price)",
                                "package_id IN ($ids_str) AND limited_time != '1'"
                            );
                        }

                        // $image_path = str_replace("practice-test/", "", $configs['vpath']);

                        // $img_url_1 = $configs['vpath'] . "image.php?src=" . $image_path . $course['thumb_pic'];

                        $img_url_1 = $configs['vpath'] . $course['thumb_pic'];

                        if ($is_group_tutoring == 0) {
                            $price_range_txt = "Starts at $" . number_format($min_price, 2) . " per session (2 hours)";
                        } else if ($is_group_tutoring == 1) {
                            $price_range_txt = "$" . number_format($min_price, 2) . " (3-5 Person Per Group)";
                        } else if ($is_group_tutoring == 2) {
                            $price_range_txt = "Starts at $" . number_format($min_price, 2);
                        }

                        $related_course_list[] = [
                            'course_id'   => $course['course_id'],
                            'course_code' => $course['course_id'],
                            'image'       => $img_url_1,
                            'name'        => $course['name'],
                            'price_range' => $price_range_txt
                        ];
                    }

                    // $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $course_data['thumb_pic'];

                    $img_url = $configs['vpath'] . $course_data['thumb_pic'];

                    if ($is_group_tutoring == 0) {
                        $price_range_txt = "Starts at $" . number_format($min_price, 2) . " per session (2 hours)";
                    } else if ($is_group_tutoring == 1) {
                        $price_range_txt = "$" . number_format($min_price, 2) . " (3-5 Person Per Group)";
                    } else if ($is_group_tutoring == 2) {
                        $price_range_txt = "Starts at $" . number_format($min_price, 2);
                    }

                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Tutoring package details fetched successfully.",
                        "course_name" => $course_data['name'],
                        "image_src" =>  $img_url,
                        "course_intro" => $intro_list[strtoupper($course_data['name'])] ?? "course intro part......",
                        "course_description" => html_entity_decode($course_data['details']),
                        "price_range" => $price_range_txt,

                        "data"       => $package_list,
                        "related_courses" => $related_course_list
                    ];
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Course not found."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Course ID is required."
                ];
            }

            break;

        case "specific-tutoring-package-details-page":

            if (!empty($_POST['course_id'])) {

                $mode = "";

                if ($_POST['submenu'] == "one") {
                    $is_group_tutoring = 0;
                } else if ($_POST['submenu'] == "group") {
                    $is_group_tutoring = 1;
                } else if ($_POST['submenu'] == "subscribe") {
                    $is_group_tutoring = 2;
                    $mode = "subscription";
                }

                $course_id = (int)$_POST['course_id'];

                $course_data = getDataByWhere("asvab_course", "*", array("course_id" => $course_id));

                $intro_list = [
                    "SIFT" => "ASVAB Tutoring helps you to fulfill your dreams by ensuring that you cover all the important areas to become an Aviation Officer. It prepares you for the area of defense you strive for. During the course, you can hone your skills and learn different strategies to crack the SIFT Test.",

                    "AFOQT" => "The Air Force Officer Qualifying Test (AFOQT) is a standardized exam assessing verbal, math, and career-specific aptitudes. It is required for officer commissioning programs such as OTS and Air Force ROTC, qualifies candidates for Pilot, CSO, and ABM training, contributes to the PCSM score, and is mandatory for all scholarship and POC cadets.",

                    "OAR" => "Want to be a US Navy Officer? First, you must prepare for the OAR (Officer Aptitude Rating) Test. A student can take entire ASTB Test or only the OAR. OAR is for those individuals who only want to become an officer, rather than pursuing the career in aviation.  ASVAB Tutoring provides comprehensive and easy to grab study materials to prepare students with confidence.",

                    "ASVAB" => "ASVAB Tutoring helps you to fulfill your dreams by ensuring that you cover all the important areas to enlist yourself in the US DoD. It prepares you for the area of defense you strive for. During the course, you can hone your skills and learn different strategies to cracking the Military Entrance Test (MET).",

                    "ASTB" => "Those who want to be the part of U.S Navy, Coast Guard and Marine Corps can utilize the help of ASVAB Tutoring. We provide ASTB (Aviation Selection Test Battery) Test tutoring to optimize their chances of being placed in the programs of their choice."
                ];

                if ($course_data) {

                    $packages = getQuery("asvab_tutor_package", "*", array("course_id" => $course_id, "is_group_tutoring" => $is_group_tutoring), "ord");

                    $package_list = [];
                    $package_ids = [];
                    foreach ($packages as $package) {
                        $package_ids[] = $package['package_id'];
                        $sessions = getQuery("asvab_tutor_package_sessions", "*", array("package_id" => $package['package_id']), "ord");
                        $package['sessions'] = [];
                        foreach ($sessions as $session) {
                            $months = $session['title'];
                            $session['duration'] = (int) $months;
                            $session['product'] = $course_data['name'];
                            $session['cadence'] = (int)$months . "M";
                            if ($mode != "") {
                                $session['mode'] = $mode;
                            }
                            $package['sessions'][] = $session;
                        }
                        $package_list[] = $package;
                    }

                    $min_price = 0;
                    $max_price = 0;

                    if (!empty($package_ids)) {
                        $ids_str = implode(",", $package_ids);

                        $min_price = getFldValueById(
                            "asvab_tutor_package_sessions",
                            "MIN(price)",
                            "package_id IN ($ids_str) AND limited_time != '1'"
                        );

                        $max_price = getFldValueById(
                            "asvab_tutor_package_sessions",
                            "MAX(price)",
                            "package_id IN ($ids_str) AND limited_time != '1'"
                        );
                    }

                    $related_course_list = [];
                    $related_courses = getQuery("asvab_course", "*", array("course_id !" => (int)$course_id, "status" => "Y"), "ord");

                    foreach ($related_courses as $course) {
                        // get all package IDs for this course

                        $packages = getQuery("asvab_tutor_package", "package_id", array("course_id" => $course['course_id'], "is_group_tutoring" => $is_group_tutoring));

                        $package_ids = [];

                        foreach ($packages as $pkg) {
                            $package_ids[] = $pkg['package_id'];
                        }

                        $min_price_r = 0;
                        $max_price_r = 0;

                        if (!empty($package_ids)) {
                            $ids_str = implode(",", $package_ids);                        

                            $min_price_r = getFldValueById(
                                "asvab_tutor_package_sessions",
                                "MIN(price)",
                                "package_id IN ($ids_str) AND limited_time != '1'"
                            );

                            $max_price_r = getFldValueById(
                                "asvab_tutor_package_sessions",
                                "MAX(price)",
                                "package_id IN ($ids_str) AND limited_time != '1'"
                            );
                        }

                        // $image_path = str_replace("practice-test/", "", $configs['vpath']);

                        // $img_url_1 = $configs['vpath'] . "image.php?src=" . $image_path . $course['thumb_pic'];

                        $img_url_1 = $configs['vpath'] . $course['thumb_pic'];

                        if ($is_group_tutoring == 0) {
                            $price_range_txt = "Starts at $" . number_format($min_price_r) . "/Session (2 hrs.)";
                        } else if ($is_group_tutoring == 1) {
                            if ($course['course_code'] == 'asvab') {
                                $price_range_txt = "$" . number_format($min_price) . " (5-10 Person/Group)";
                            } else {
                                $price_range_txt = "$" . number_format($min_price) . " (3-5 Person/Group)";
                            }
                        } else if ($is_group_tutoring == 2) {
                            $price_range_txt = "Starts at $" . number_format($min_price_r) . "/Month";
                        }

                        $related_course_list[] = [
                            'course_id'   => $course['course_id'],
                            'course_code' => $course['course_code'],
                            'full_form' => $course['full_form'],
                            'image'       => $img_url_1,
                            'name'        => $course['name'],
                            'price_range' => $price_range_txt
                        ];
                    }

                    // $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $course_data['thumb_pic'];

                    $img_url = $configs['vpath'] . $course_data['thumb_pic'];

                    if ($is_group_tutoring == 0) {
                        $price_range_txt = "Starts at $" . number_format($min_price, 2) . " per session (2 hours)";
                    } else if ($is_group_tutoring == 1) {
                        if ($course_data['course_code'] == 'asvab') {
                            $price_range_txt = "$" . number_format($min_price, 2) . " (5-10 Persons per Group)";
                        } else {
                            $price_range_txt = "$" . number_format($min_price, 2) . " (3-5 Persons per Group)";
                        }
                    } else if ($is_group_tutoring == 2) {
                        $price_range_txt = "Starts at $" . number_format($min_price, 2) . " per Month";
                    }

                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Tutoring package details fetched successfully.",
                        "course_name" => $course_data['name'],
                        "full_form" => $course_data['full_form'],
                        "image_src" =>  $img_url,
                        "course_intro" => $intro_list[strtoupper($course_data['name'])] ?? "course intro part......",
                        "course_description" => html_entity_decode($course_data['details']),
                        "price_range" => $price_range_txt,

                        "data"       => $package_list,
                        "related_courses" => $related_course_list
                    ];
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Course not found."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Course ID is required."
                ];
            }

            break;

        case "get-tutor-requests":

            $result = getQuery("asvab_tutor_request", "*", array(), "id DESC");

            $tutor_requests = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $tutor_requests[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Tutor requests fetched successfully.",
                    "data"       => $tutor_requests
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No tutor requests found.",
                    "data"       => []
                ];
            }

            break;

        case "get-payment-list":

            $result = getQuery("square_payments", "*", array(), "id DESC");
            $payment_list = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $payment_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Payment records fetched successfully.",
                    "data"       => $payment_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No payment records found.",
                    "data"       => []
                ];
            }

            break;

        case "insert-tutor":
            // Check required fields
            $name        = trim($_POST['name'] ?? '');
            $designation = trim($_POST['designation'] ?? '');
            $about       = trim($_POST['about'] ?? '');

            // Validate required fields
            if (empty($name) || empty($designation) || empty($about)) {
                $response = [
                    "status"  => "error",
                    "message" => "Name, Designation and About fields are required."
                ];
                exit;
            }

            $data = [
                "about"          => $_POST['about'],
                "education"      => $_POST['education'],
                "specialization" => $_POST['specialization'],
                "language"       => $_POST['language'],
                "tutor_order"    => $_POST['tutor_order'],
                "name"           => $_POST['name'],
                "designation"     => $_POST['designation'],
            ];

            $flag = insertTable("tutor", $data, array("photo"));

            if ($flag) {
                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Tutor information added successfully."
                ];
            } else {
                $response = [
                    "status"     => 500,
                    "isSuccess"  => false,
                    "message"    => "Failed to add tutor. Please try again later."
                ];
            }

            break;

        case "remove-tutor":
            if (!empty($_POST['tutor_id'])) {
                $tutor_id = (int)$_POST['tutor_id'];

                $exist = getTotalRow("tutor", array("id" => $tutor_id));

                if ($exist) {
                    $flag = deleteData("tutor", array("id" => $tutor_id));

                    if ($flag) {
                        $response = [
                            "status"     => 200,
                            "isSuccess"  => true,
                            "message"    => "Tutor removed successfully."
                        ];
                    } else {
                        $response = [
                            "status"     => 500,
                            "isSuccess"  => false,
                            "message"    => "Failed to remove tutor. Please try again later."
                        ];
                    }
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Tutor not found."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Tutor ID is required."
                ];
            }
            break;

        case "get-tutor-list":

            // Fetch tutor information from the database
            // Limit to 4 tutors as per the original code
            // You can adjust the limit as needed

            $result = getQuery("tutor", "*", array(), "tutor_order");

            $tutor_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $row['photo'];

                    $img_url = $configs['vpath'] . $row['photo'];

                    // $img_url = $image_path . $row['photo'];

                    $row['photo'] =  $img_url;
                    $row['about'] = html_entity_decode($row['about'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); // decode entities

                    $tutor_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Tutor information fetched successfully.",
                    "data"       => $tutor_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No tutor information found."
                ];
            }

            break;

        case "get-payment-method-list":

            $result = getQuery("asvab_payment_method", "*", array(), "ord");

            $payment_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $row['photo'];

                    $img_url = $configs['vpath'] . $row['photo'];

                    // $img_url = $image_path . $row['photo'];

                    $row['photo'] =  $img_url;

                    $payment_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Payment method information fetched successfully.",
                    "data"       => $payment_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No payment method information found."
                ];
            }

            break;

        case "add-faq":

            // Check for required fields
            if (
                !empty($_POST['question']) &&
                !empty($_POST['answers'])
            ) {
                // Prepare data
                $data = [
                    "question"    => isset($_POST['question']) ? trim($_POST['question']) : "N/A",
                    "course_id"   => isset($_POST['course_id']) ? (int)$_POST['course_id'] : (int)0,
                    "answers"     => isset($_POST['answers']) ? trim($_POST['answers']) : "N/A",
                    "related_id"  => isset($_POST['related_id']) ? (int)$_POST['related_id'] : 0,
                    "category"    => isset($_POST['category']) ? trim($_POST['category']) : "N/A",
                    "ord"         => isset($_POST['ord']) ? (int)$_POST['ord'] : 0,
                    "is_online"   => isset($_POST['is_online']) ? $_POST['is_online'] : "N"
                ];

                // Insert into the database
                $flag = insertTable("faq", $data);

                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "FAQ added successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to add FAQ. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }

            break;

        case "get-faq":

            $result = getQuery("faq", "*", array());

            $tutor_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $row['answers'] = html_entity_decode($row['answers'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $tutor_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "FAQs fetched successfully.",
                    "data"       => $tutor_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No FAQs found."
                ];
            }

            break;

        case "add-course":
            // Check for required fields
            if (
                !empty($_POST['course_name']) //&&
                // !empty($_POST['header']) &&
                // !empty($_POST['tagline']) &&
                // !empty($_POST['details']) &&
                //!empty($_POST['curriculum']) &&
                //!empty($_POST['ord']) &&
                //!empty($_POST['course_name_test_prep']) &&
                // isset($_POST['status']) // since it's an ENUM, just check if it's set
            ) {
                // Prepare data array
                $data = [
                    "name"        => trim($_POST['course_name']),
                    "full_form"   => trim($_POST['full_form']),
                    "header"      => trim($_POST['header']),
                    "tagline"     => trim($_POST['tagline']),
                    "details"     => $_POST['details'],
                    "curriculum"  => $_POST['curriculum'],
                    "video"       => $_POST['video'],
                    "video_title"     => $_POST['video_title'],
                    "ord"         => $_POST['ord'],
                    "status"      => $_POST['status'],
                    "course_name_test_prep" => $_POST['course_name_test_prep']
                ];

                if (!empty($_POST['course_id'])) {

                    $flag = updateTable("asvab_course", $data, array("course_id" => (int)$_POST['course_id']));
                    if ($flag) {
                        $response = [
                            "status"     => 200,
                            "isSuccess"  => true,
                            "message"    => "Course updated successfully."
                        ];
                    } else {
                        $response = [
                            "status"     => 500,
                            "isSuccess"  => false,
                            "message"    => "Failed to update course. Please try again later."
                        ];
                    }
                } else {

                    // if(!$_POST['thumb_pic']){
                    //     $data['name'] = 'thumb_pic missing';
                    // }

                    // Insert into the database
                    $flag = insertTable("asvab_course", $data, array("thumb_pic"));

                    if ($flag) {
                        $response = [
                            "status"     => 200,
                            "isSuccess"  => true,
                            "message"    => "Course added successfully."
                        ];
                    } else {
                        $response = [
                            "status"     => 500,
                            "isSuccess"  => false,
                            "message"    => "Failed to add course. Please try again later."
                        ];
                    }
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }
            break;

        case "get-all-courses":

            $result = getQuery("asvab_course", "*", array("status" => "Y"), "ord");

            $num_of_courses = mysqli_num_rows($result);

            $course_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    // $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $row['thumb_pic'];

                    $img_url = $configs['vpath'] . $row['thumb_pic'];

                    // $img_url = $image_path . $row['thumb_pic'];

                    $course_details = $row['details'];
                    $course_details = preg_replace('/\bwhat[^?]*\?/i', '', $course_details);

                    $course_list[] = [
                        'course_id' => $row['course_id'],
                        'course_code' => $row['course_code'],
                        'full_form' => $row['full_form'],
                        'name' => $row['name'],
                        'image_src' =>  $img_url,
                        'details' => $course_details
                    ];
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Course List fetched successfully.",
                    "num_of_courses" => $num_of_courses,
                    "data"       => $course_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No Course List found."
                ];
            }

            break;

        case "delete-course":

            if (!empty($_POST['course_id'])) {
                $course_id = $_POST['course_id'];

                $exist = getTotalRow("asvab_course", array("course_id" => $course_id));

                if ($exist) {
                    $flag = deleteData("asvab_course", array("course_id" => $course_id));

                    if ($flag) {
                        $response = [
                            "status"     => 200,
                            "isSuccess"  => true,
                            "message"    => "Course deleted successfully."
                        ];
                    } else {
                        $response = [
                            "status"     => 500,
                            "isSuccess"  => false,
                            "message"    => "Failed to delete course. Please try again later."
                        ];
                    }
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Course not found."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Course ID is required."
                ];
            }

            break;

        case "get-course-details":

            if (!empty($_POST['course_code'])) {
                $course_code = $_POST['course_code'];

                $exist = getTotalRow("asvab_course", array("course_id" => $_POST['course_code']));

                if ($exist) {
                    $course_data = getDataByWhere("asvab_course", "*", array("course_id" => $course_code));


                    if ($course_data['video']) {
                        $videos['video_title'] = $course_data['video_title'] ?? "";
                        $videos['video_link'] = $course_data['video'];

                        // $image_path = str_replace("practice-test/", "", $configs['vpath']);
                        // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $course_data['thumb_pic'];

                        $img_url1 = $configs['vpath'] . $course_data['thumb_pic'] ?? "";

                        $videos['thumb_pic'] =  $img_url1;
                    } else {
                        $videos = [];
                    }

                    $courses = getQuery("asvab_course", "*", array("status" => "Y"));

                    // Start Online Free Test

                    $course_name = $course_data['course_name_test_prep'];
                    // $course_id = (int)getFldValueById("course", "course_id", array("course_name" => $course_name));
                    $course_id = $course_code;
                    $cat_list = getQuery("category", "*", array("course_id" => $course_id, "free_test" => 1), "category_order");

                    $free_test_cat_list = array();
                    $free_test_ques_list = array();
                    foreach ($cat_list as $i) {
                        $free_test_cat_list[] = $i['category_name'];

                        $free_test_ques_list[$i['category_name']] = [];
                        $temp_ques_list = getQueryByJoin(
                            array("question as q", "free_test_questions as l"),
                            "q.*, l.*",
                            array("l.course_id" => $course_id, "l.cat_id" => $i['cat_id']),
                            array("q.qid" => "l.qid")
                        );
                        foreach ($temp_ques_list as $q) {
                            $free_test_ques_list[$i['category_name']][] = $q;
                        }
                    }

                    // End Online Free Test

                    $num_prac_ques = getTotalRow("lesson_questions", array("course_id" => $course_id));

                    $related_courses = getQuery("asvab_course", "*", array("course_id !" => $course_data['course_id'], "status" => "Y"));
                    $related_course_list = [];
                    foreach ($related_courses as $course) {
                        // $image_path = str_replace("practice-test/", "", $configs['vpath']);
                        // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $course['thumb_pic'];

                        $img_url = $configs['vpath'] . $course['thumb_pic'];

                        $related_course_list[] = [
                            'course_id' => $course['course_id'],
                            'course_code'   => $course['course_id'],
                            'full_form' => $course['full_form'],
                            "subscribe_button_url" => $configs['vpath'] . "register/" . $course['seo_url'],
                            'image' => $img_url,
                            'name' => $course['name'],
                            'details' => $course['details']
                        ];
                    }

                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Course details fetched successfully.",
                        "course_name" => $course_data['name'],
                        "full_form" => $course_data['full_form'],
                        "course_header" => $course_data['header'],
                        "course_tagline" => $course_data['tagline'],
                        "image_src" =>  $configs['vpath'] . $course_data['thumb_pic'],
                        "overview" => html_entity_decode($course_data["details"]),
                        "curriculum" => html_entity_decode($course_data["curriculum"]),
                        "video" => $videos,
                        "online_free_test_data" => [
                            "course_id" => $course_id,
                            "categories" => $free_test_cat_list,
                            "questions" => $free_test_ques_list
                        ],
                        "related_courses" => $related_course_list,
                    ];
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Course not found."
                    ];
                }
            }

            break;

        case "get-course-details-by-code":

            if (!empty($_POST['course_code'])) {
                $course_code = $_POST['course_code'];

                $exist = getTotalRow("asvab_course", array("course_code" => $course_code));

                if ($exist) {
                    $course_data = getDataByWhere("asvab_course", "*", array("course_code" => $course_code));

                    if ($course_data['video']) {
                        $videos['video_title'] = $course_data['video_title'] ?? "";
                        $videos['video_link'] = $course_data['video'];

                        // $image_path = str_replace("practice-test/", "", $configs['vpath']);
                        // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $course_data['thumb_pic'];

                        $img_url1 = $configs['vpath'] . $course_data['thumb_pic'] ?? "";

                        $videos['thumb_pic'] =  $img_url1;
                    } else {
                        $videos = [];
                    }

                    // $courses = getQuery("asvab_course", "*", array("status" => "Y"));

                    // Start Online Free Test

                    $course_name = $course_data['course_name_test_prep'];
                    // $course_id = (int)getFldValueById("course", "course_id", array("course_name" => $course_name));
                    $course_id = $course_data["course_id"];
                    $cat_list = getQuery("category", "*", array("course_id" => $course_id, "free_test" => 1), "category_order");

                    $free_test_cat_list = array();
                    $free_test_ques_list = array();
                    foreach ($cat_list as $i) {
                        $free_test_cat_list[] = $i['category_name'];

                        $free_test_ques_list[$i['category_name']] = [];
                        $temp_ques_list = getQueryByJoin(
                            array("question as q", "free_test_questions as l"),
                            "q.*, l.*",
                            array("l.course_id" => $course_id, "l.cat_id" => $i['cat_id']),
                            array("q.qid" => "l.qid")
                        );
                        foreach ($temp_ques_list as $q) {
                            $free_test_ques_list[$i['category_name']][] = $q;
                        }
                    }

                    // End Online Free Test

                    $num_prac_ques = getTotalRow("lesson_questions", array("course_id" => $course_id));

                    $related_courses = getQuery("asvab_course", "*", array("course_id !" => $course_data['course_id'], "status" => "Y"));
                    $related_course_list = [];
                    foreach ($related_courses as $course) {
                        // $image_path = str_replace("practice-test/", "", $configs['vpath']);
                        // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $course['thumb_pic'];

                        $img_url = $configs['vpath'] . $course['thumb_pic'];

                        $related_course_list[] = [
                            'course_id' => $course['course_id'],
                            'course_code'   => $course['course_code'],
                            'full_form' => $course['full_form'],
                            "subscribe_button_url" => $configs['vpath'] . "register/" . $course['seo_url'],
                            'image' => $img_url,
                            'name' => $course['name'],
                            'details' => $course['details']
                        ];
                    }
                    // Fetch sessions and lessons
                    $sessions_query = getQuery("asvab_sessions", "*", array("course_id" => $course_id), "order_index");
                    $sessions_list = [];
                    foreach ($sessions_query as $session) {
                        $lessons_query = getQuery("asvab_lessons", "lesson_name", array("session_id" => $session['id']), "order_index");
                        $lessons = [];
                        foreach ($lessons_query as $lesson) {
                            $lessons[] = $lesson['lesson_name'];
                        }
                        $sessions_list[] = [
                            'sessionTitle' => $session['session_title'],
                            'lessons' => $lessons
                        ];
                    }

                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Course details fetched successfully.",
                        "course_name" => $course_data['name'],
                        "full_form" => $course_data['full_form'],
                        "course_header" => $course_data['header'],
                        "course_tagline" => $course_data['tagline'],
                        "image_src" =>  $configs['vpath'] . $course_data['thumb_pic'],
                        "overview" => html_entity_decode($course_data["details"]),
                        "curriculum" => html_entity_decode($course_data["curriculum"]),
                        "sessions" => $sessions_list,
                        "video" => $videos,
                        "online_free_test_data" => [
                            "course_id" => $course_id,
                            "categories" => $free_test_cat_list,
                            "questions" => $free_test_ques_list
                        ],
                        "related_courses" => $related_course_list,
                    ];
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Course not found."
                    ];
                }
            }

            break;

        case "submit-user-feedback":

            if (!empty($_POST['course_id']) && !empty($_POST['feedback']) && !empty($_POST['rating']) && !empty($_POST['name']) && !empty($_POST['email'])) {

                $flag = insertTable("ocs_feedback", array("course_id" => $_POST['course_id'], "feedback" => $_POST['feedback'], "rating" => $_POST['rating'], "name" => $_POST['name'], "email" => $_POST['email'], "date" => date("Y-m-d"), "status" => "N"));

                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Feedback submitted successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to submit feedback. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }
            break;

        case "insert-about-us":

            // Check for required fields
            if (
                !empty($_POST['title']) &&
                !empty($_POST['description']) &&
                isset($_POST['is_online'])
            ) {
                // Prepare data for insertion
                $data = [
                    "title"       => trim($_POST['title']),
                    "description" => trim($_POST['description']),
                    "is_online"   => $_POST['is_online']  // Expected: 'Y' or 'N'
                ];

                // Insert into the database
                $flag = insertTable("asvab_about_us", $data);

                // Prepare response
                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "About Us information added successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to add About Us information. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }

            break;

        case "get-about-us":

            $result = getQuery("asvab_about_us", "*", ["is_online" => "Y"]);

            $about_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $about_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "About Us information fetched successfully.",
                    "data"       => $about_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No About Us information found."
                ];
            }

            break;

        case "insert-pricing-page-content":

            // Check for required fields
            if (
                !empty($_POST['title']) &&
                !empty($_POST['description'])
            ) {
                // Prepare data for insertion
                $data = [
                    "title"       => trim($_POST['title']),
                    "description" => trim($_POST['description']),
                    "is_online"   => $_POST['is_online'],  // Expected: 'Y' or 'N'
                    "closing_content" => trim($_POST['closing_content'])
                ];

                deleteData("asvab_pricing_page", array("pricing_id !" => 0));

                // Insert into the database
                $flag = insertTable("asvab_pricing_page", $data);

                // Prepare response
                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Pricing page information added successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to add Pricing page information. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }

            break;

        case "get-pricing-page-content":

            $result = getQuery("asvab_pricing_page", "*", array());

            $about_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $about_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Pricing page information fetched successfully.",
                    "data"       => $about_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No Pricing page information found."
                ];
            }

            break;

        case "specific-get-pricing-page-content":

            $result = getQuery("asvab_pricing_page", "*", array("tutoring_type" => $_POST['submenu']));

            $about_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $about_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Pricing page information fetched successfully.",
                    "data"       => $about_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No Pricing page information found."
                ];
            }

            break;

        // Insert How It Works item
        case "insert-how-it-works":

            // Sanitize input
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';

            // Validate input
            if ($title === '' || $description === '') {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Title and Description are required."
                ];
                break;
            }

            // Prepare data array for insertion
            $data = [
                "title"       => $title,
                "description" => $description
            ];

            deleteData("asvab_how_it_works", ""); // Clear existing entries

            // Call your custom insert function
            $insert_id = insertTable("asvab_how_it_works", $data);

            if ($insert_id) {
                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "How It Works item inserted successfully.",
                    "insert_id"  => $insert_id
                ];
            } else {
                $response = [
                    "status"     => 500,
                    "isSuccess"  => false,
                    "message"    => "Failed to insert item."
                ];
            }

            break;

        // Fetch How It Works items

        case "get-how-it-works":

            // Fetch all items from the asvab_how_it_works table
            $result = getQuery("asvab_how_it_works", "*");

            $how_it_works_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $how_it_works_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "How It Works items fetched successfully.",
                    "data"       => $how_it_works_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No How It Works items found."
                ];
                http_response_code(404);
            }
            break;

        case "get-welcome-text":

            $result = getQuery("asvab_welcome_text", "*");

            $how_it_works_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $how_it_works_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Welcome text fetched successfully.",
                    "data"       => $how_it_works_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No welcome text found."
                ];
                http_response_code(404);
            }
            break;

        case "get-terms-conditions":

            $result = getQuery("asvab_terms_condition", "*");

            $how_it_works_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $how_it_works_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Terms and Condition fetched successfully.",
                    "data"       => $how_it_works_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No terms and condition found."
                ];
                http_response_code(404);
            }
            break;

        case "get-privacy-policy":

            $result = getQuery("asvab_policy_privacy", "*");

            $how_it_works_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $how_it_works_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Privacy policy fetched successfully.",
                    "data"       => $how_it_works_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No Privacy policy found."
                ];
                http_response_code(404);
            }
            break;

        // Insert site settings

        case "insert-site-setting":

            // Check for required fields
            if (
                !empty($_POST['admin_email']) &&
                !empty($_POST['site_contact']) &&
                !empty($_POST['support_email']) &&
                !empty($_POST['payment_email']) &&
                !empty($_POST['twitter_url']) &&
                !empty($_POST['facebook_url']) &&
                !empty($_POST['linkedin_url']) &&
                !empty($_POST['contact_details']) &&
                isset($_POST['referral_discount']) &&
                isset($_POST['referral_commission']) &&
                isset($_POST['auto_approve']) &&
                !empty($_POST['subscription_type'])
            ) {
                // Prepare data array
                $data = [
                    "admin_mail"           => trim($_POST['admin_email']),
                    "admin_contact"        => trim($_POST['site_contact']),
                    "support_mail"         => trim($_POST['support_email']),
                    "paypal_mail"          => trim($_POST['payment_email']),
                    "favicon"              => $_POST['favicon'],
                    "twitter_url"          => $_POST['twitter_url'],
                    "facebook_url"         => $_POST['facebook_url'],
                    "linkedin_url"         => $_POST['linkedin_url'],
                    "contact_details"      => $_POST['contact_details'],
                    "ref_discount"         => $_POST['referral_discount'],
                    "ref_commission"       => $_POST['referral_commission'],
                    "auto_approve_member"  => $_POST['auto_approve'],
                    "subscription_type"    => $_POST['subscription_type']
                ];

                // Insert into the database
                $flag = insertTable("asvab_site_setting", $data, array("logo"));

                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Site settings saved successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to save site settings. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }

            break;

        // Fetch site settings 
        case "get-site-setting":

            // Fetch site settings from the database
            $result = getQuery("asvab_site_setting", "*");

            $site_settings = [];

            // Check if any settings were found

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $site_settings[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Site settings fetched successfully.",
                    "data"       => $site_settings
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No site settings found."
                ];
            }

            break;

        case "insert-email-draft":

            // Validate required POST fields
            if (
                !empty($_POST['subject']) &&
                !empty($_POST['body']) &&
                !empty($_POST['email_from'])
            ) {
                // Sanitize and prepare data
                $data = [
                    "email_from" => trim($_POST['email_from']),
                    "subject"    => trim($_POST['subject']),
                    "body"       => $_POST['body']

                ];

                // Insert into the database
                $flag = insertTable("asvab_email_draft", $data);

                // Respond
                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Email draft saved successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to save email draft. Please try again."
                    ];
                }
            } else {
                // If any required field is missing
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Subject, Body, and From fields are required."
                ];
            }

            break;

        case "get-email-drafts":

            // Fetch email drafts from the database
            $result = getQuery("asvab_email_draft", "*", array(), "id DESC");

            $email_drafts = [];

            // Check if any drafts were found
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $email_drafts[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Email drafts fetched successfully.",
                    "data"       => $email_drafts
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No email drafts found."
                ];
            }

            break;

        case "course-list-tutoring-package":

            $courses = getQuery("asvab_course", "*", array("status" => "Y"), "ord");

            $related_course_list = [];

            foreach ($courses as $course) {
                $package_ids = [];
                $packages = getQuery(
                    "asvab_tutor_package",
                    "*",
                    array(
                        "course_id" => (int)$course['course_id'],
                        "is_group_tutoring" => 0,
                        "is_online" => "Y"
                    ),
                    "ord"
                );

                foreach ($packages as $pkg) {
                    $package_ids[] = $pkg['package_id'];
                }

                // Skip course if no valid online tutoring package exists
                if (empty($package_ids)) {
                    continue;
                }

                $min_price = 0;
                $max_price = 0;

                $ids_str = implode(",", array_map('intval', $package_ids));

                $min_price = getFldValueById(
                    "asvab_tutor_package_sessions",
                    "MIN(price)",
                    "package_id IN ($ids_str) AND limited_time != '1'"
                );

                $max_price = getFldValueById(
                    "asvab_tutor_package_sessions",
                    "MAX(price)",
                    "package_id IN ($ids_str) AND limited_time != '1'"
                );

                $img_url = $configs['vpath'] . $course['thumb_pic'];

                if ($is_group_tutoring == 0) {
                    $price_range_txt = "Starts at $" . number_format((float)$min_price, 2) . " per session (2 hours)";
                } else if ($is_group_tutoring == 1) {
                    $price_range_txt = "$" . number_format((float)$min_price, 2) . " (3-5 Person Per Group)";
                } else if ($is_group_tutoring == 2) {
                    $price_range_txt = "Starts at $" . number_format((float)$min_price, 2);
                } else {
                    $price_range_txt = "Starts at $" . number_format((float)$min_price, 2);
                }

                $related_course_list[] = [
                    'course_id'   => $course['course_id'],
                    'course_code' => $course['course_id'],
                    'name'        => $course['name'],
                    'image_src'   => $img_url,
                    'price_range' => $price_range_txt,
                ];
            }

            $response = [
                "status"    => 200,
                "isSuccess" => true,
                "message"   => "Course List fetched successfully.",
                "data"      => $related_course_list
            ];

            break;

        case "specific-course-list-tutoring-package":

            $courses = getQuery("asvab_course", "*", array("status" => "Y"), "ord");

            if ($_POST['submenu'] == 'one') {
                $is_group_tutoring = 0;
            } else if ($_POST['submenu'] == 'group') {
                $is_group_tutoring = 1;
            } else if ($_POST['submenu'] == 'subscribe') {
                $is_group_tutoring = 2;
            }

            $related_course_list = [];

            foreach ($courses as $course) {
                $package_ids = [];

                $packages = getQuery("asvab_tutor_package", "*", array("course_id" => (int)$course['course_id'], "is_group_tutoring" => $is_group_tutoring), "ord");

                foreach ($packages as $pkg) {
                    $package_ids[] = $pkg['package_id'];
                }

                $min_price = 0;
                $max_price = 0;

                if (!empty($package_ids)) {
                    $ids_str = implode(",", $package_ids);

                    $min_price = getFldValueById(
                        "asvab_tutor_package_sessions",
                        "MIN(price)",
                        "package_id IN ($ids_str) AND limited_time != '1'"
                    );

                    $max_price = getFldValueById(
                        "asvab_tutor_package_sessions",
                        "MAX(price)",
                        "package_id IN ($ids_str) AND limited_time != '1'"
                    );
                }

                // $img_url = $configs['vpath'] . "image.php?src=" . str_replace("practice-test/", "", $configs['vpath']) . $course['thumb_pic'];

                $img_url = $configs['vpath'] . $course['thumb_pic'];

                if ($is_group_tutoring == 0) {
                    $price_range_txt = "Starts at $" . number_format($min_price) . "/Session (2 hrs.)";
                } else if ($is_group_tutoring == 1) {
                    if ($course['course_code'] == 'asvab') {
                        $price_range_txt = "$" . number_format($min_price) . " (5-10 Person/Group)";
                    } else {
                        $price_range_txt = "$" . number_format($min_price) . " (3-5 Person/Group)";
                    }
                } else if ($is_group_tutoring == 2) {
                    $price_range_txt = "Starts at $" . number_format($min_price) . "/Month";
                }

                $related_course_list[] = [
                    'course_id' => $course['course_id'],
                    'course_code'   => $course['course_code'],
                    'name' => $course['name'],
                    'full_form' => $course['full_form'],
                    'image_src' => $img_url,
                    'price_range' => ($min_price && $max_price)
                        ? $price_range_txt
                        : "N/A",
                ];
            }

            $response = [
                "status"     => 200,
                "isSuccess"  => true,
                "message"    => "Course List fetched successfully.",
                "data"       => $related_course_list
            ];

            break;

        case "add-our-app-info":

            // Check for required fields
            if (
                !empty($_POST['app_name']) &&
                !empty($_POST['google_play_url']) &&
                !empty($_POST['app_store_url'])
            ) {
                // Prepare data for insertion
                $data = [
                    "app_name"             => trim($_POST['app_name']),
                    "app_store_url"        => trim($_POST['app_store_url']),
                    "google_play_url"      => trim($_POST['google_play_url'])
                ];

                // Insert into the database
                $flag = insertTable("asvab_our_app", $data, array("logo_img"));

                // Prepare response
                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Our App information added successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to add Our App information. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }

            break;

        case "get-our-app-info":
            $result = getQuery("asvab_our_app", "*", array(), "ord");

            $app_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // $image_path = str_replace("practice-test/", "", $configs['vpath']);
                    // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $row['logo_img'];

                    $img_url = $configs['vpath'] . $row['logo_img'];

                    $row['logo_img'] = $img_url;

                    $app_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Our App information fetched successfully.",
                    "data"       => $app_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No Our App information found."
                ];
            }

            break;

        case "add-blog":

            // Check for required fields
            if (
                !empty($_POST['blog_title']) &&
                !empty($_POST['blog_content'])
            ) {
                // Prepare data for insertion
                $data = [
                    "blog_title"       => trim($_POST['blog_title']),
                    "blog_content"     => trim($_POST['blog_content']),
                    "written_by"        => $_POST['written_by'],
                    "created_at"   => !empty($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s')
                ];

                // Insert into the database
                $flag = insertTable("asvab_blog", $data, array("writer_img"));

                // Prepare response
                if ($flag) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Blog added successfully."
                    ];
                } else {
                    $response = [
                        "status"     => 500,
                        "isSuccess"  => false,
                        "message"    => "Failed to add blog. Please try again later."
                    ];
                }
            } else {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "All required fields must be provided."
                ];
            }

            break;

        case "get-blogs":

            $page_no = isset($_POST['page_no']) ? (int)$_POST['page_no'] : 1; // default to page 1 if not provided
            $limit   = 9;
            $offset  = ($page_no - 1) * $limit;

            $total_blogs  = getTotalRow("asvab_blog", array());

            // Fetch only 8 blogs per page
            $result = getQuery("asvab_blog", "*", array(), "created_at DESC LIMIT $limit OFFSET $offset");

            $total_pages  = ceil($total_blogs / $limit);

            $blog_list = [];

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // $image_path = str_replace("practice-test/", "", $configs['vpath']);
                    // $img_url = $configs['vpath'] . "image.php?src=" . $image_path . $row['writer_img'];

                    $img_url = $configs['vpath'] . $row['writer_img'];

                    $row['writer_img'] = $img_url;

                    $blog_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Blogs fetched successfully.",
                    "page_no"      => $page_no,
                    "total_pages"  => $total_pages,
                    "total_blogs"  => $total_blogs,
                    "limit_per_page" => $limit,
                    "data"       => $blog_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No blogs found."
                ];
            }

            break;

        case "get-blog-details":

            // Fetch only 8 blogs per page
            $result = getDataByWhere("asvab_blog", "*", array("blog_id" => (int)$_POST['blog_id']));

            if ($result) {
                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Blogs fetched successfully.",
                    "data"       => $result
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No blog found."
                ];
            }

            break;

        case "get-sitemap":

            $courses_list = getQuery("asvab_course", "*", array("status" => "Y"), "ord");
            $lines = file("city.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // removes \n automatically
            $sitemap = [];

            foreach ($courses_list as $d) {
                foreach ($lines as $city) {
                    $sitemap[] = [
                        "course_id"   => $d['course_id'],
                        "course_code" => $d['course_code'],
                        "name"        => $d['name'] . " Tutor in " . trim($city),
                        "city"        => trim($city),
                    ];
                }
            }

            $response = [
                "status"     => 200,
                "isSuccess"  => true,
                "message"    => "Sitemap fetched successfully.",
                "comment"    => "Redirect to the course details page with corresponding course_id.",
                "data"       => $sitemap
            ];
        break;
        
        case "get-service-course-list":

            $courses = array(
                "1-on-1 Tutoring" => "one",
                "Group Tutoring"  => "group",
                "Self Study" => "subscribe"
            );

            $services = [];

            foreach ($courses as $course_name => $service_type) {

                $service_id = getFldValueById(
                    "asvab_services_content",
                    "service_id",
                    array("is_online" => "Y", "service_type" => $service_type)
                );

                $services[] = [
                    "service_id"  => $service_id,
                    "course_name" => $course_name
                ];
            }

            if ($courses) {
                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Service Course List fetched successfully.",
                    "data"       => $services
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No Service Course found."
                ];
            }

            break;

        case "get-specific-service-detail":

            if (empty($_POST['service_id'])) {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Service ID is required."
                ];
            } else {
                $service_id = $_POST['service_id'];

                $course_data = getDataByWhere("asvab_services_content", "*", array("service_id" => $service_id, "is_online" => "Y"));

                $service_data = [
                    "service_id" => $course_data['service_id'],
                    "title" => $course_data['title'],
                    "is_online" => $course_data['is_online'],
                    "course_name" => $course_data['course_name'],
                    "course_id" => $course_data['course_id'],
                    "ord" => $course_data['ord'],
                    "description" => html_entity_decode($course_data['description'])
                ];


                if ($course_data) {
                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Service Course details fetched successfully.",
                        "data"       => $service_data
                    ];
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "Service Course not found."
                    ];
                }
            }

            break;

        case "user-registration":

            // ✅ Free user registration ONLY (no Square / no payment table insert)

            // -------------------------
            // 1) Validate required fields
            // -------------------------
            $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
            $last_name  = isset($_POST['last_name'])  ? trim($_POST['last_name'])  : '';
            $email      = isset($_POST['email'])      ? trim($_POST['email'])      : '';
            $mobile     = isset($_POST['mobile'])     ? trim($_POST['mobile'])     : '';
            $password   = isset($_POST['password'])   ? (string)$_POST['password'] : '';

            $incoming_course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
            $session_id         = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;

            if ($first_name === '' || $last_name === '' || $email === '' || $mobile === '' || $password === '') {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing required fields."
                ];
                break;
            }

            if ($incoming_course_id <= 0 || $session_id <= 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Course and package are required."
                ];
                break;
            }

            // Basic email validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Invalid email address."
                ];
                break;
            }

            // -------------------------
            // 2) Map UI course_id -> internal course_id
            // -------------------------
            $course_id = 0;
            if ($incoming_course_id == 49) {
                $course_id = 24;
            } else if ($incoming_course_id == 40) {
                $course_id = 27;
            } else if ($incoming_course_id == 50) {
                $course_id = 25;
            } else if ($incoming_course_id == 51) {
                $course_id = 26;
            } else if ($incoming_course_id == 52) {
                $course_id = 21;
            }

            if ($course_id == 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Invalid course selected."
                ];
                break;
            }

            // -------------------------
            // 3) Prevent duplicate email
            // -------------------------
            $existing = getDataByWhere("user", "user_id", ["email" => $email]);
            if (!empty($existing) && isset($existing['user_id'])) {
                $response = [
                    "status"    => 409,
                    "isSuccess" => false,
                    "message"   => "Email already exists. Please login."
                ];
                break;
            }

            // -------------------------
            // 4) Build subscription dates (Free access)
            // -------------------------
            $timezone = new DateTimeZone('America/New_York');
            $now      = new DateTime('now', $timezone);

            $formatted_date      = $now->format('Y-m-d');
            $formatted_date_time = $now->format('Y-m-d H:i:s');

            // Free access validity (same as your old logic): 4 months ≈ 120 days
            $month = 4;
            $days  = $month * 30;

            $expire = new DateTime('now', $timezone);
            $expire->modify('+' . $days . ' days');
            $expire_date = $expire->format('Y-m-d');

            // -------------------------
            // 5) Read package/session info safely
            // -------------------------
            // Keep your default package_id (as you used previously)
            $package_id = 62;

            // For free registration, we store amount = 0 (or you can keep session price if you want)
            // Recommended: 0 to avoid mixing with paid flows.
            $amount   = 0;
            $currency = "usd";

            // Optional: validate session exists
            $session_price = getFldValueById("asvab_tutor_package_sessions", "price", ["session_id" => $session_id]);
            if ($session_price === '' || $session_price === null) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Invalid package selected."
                ];
                break;
            }

            // Course name for email/payment display (no payment insert, but still useful)
            $test_name = getFldValueById("course", "course_name", ["course_id" => $course_id]);
            $user_name = $first_name . " " . $last_name;

            // -------------------------
            // 6) Insert user + login + subscription (transaction if available)
            // -------------------------
            // If you have a DB transaction helper, use it. Otherwise this still works.
            // Example (if mysqli): mysqli_begin_transaction($conn);

            $user_id = insertTable("user", [
                "email"             => $email,
                "password"          => md5($password), // legacy; best is password_hash(...) but keeping your system consistent
                "first_name"        => $first_name,
                "last_name"         => $last_name,
                "mobile"            => $mobile,
                "user_type"         => "Student",
                "reg_date"          => $formatted_date,
                "activation_status" => "N",
                "reg_at"            => "asvab-web",
                "fp"                => 0,
                "pt"                => 0
            ]);

            if (!$user_id) {
                $response = [
                    "status"    => 500,
                    "isSuccess" => false,
                    "message"   => "Failed to create user. Please try again later."
                ];
                break;
            }

            insertTable("login", [
                "user_id"     => $user_id,
                "last_login"  => $formatted_date_time
            ]);

            $sub_id = insertTable("subscription", [
                "course_id"          => $course_id,
                "user_id"            => $user_id,
                "package_id"         => $package_id,
                "amount"             => $amount,
                "payment_mode"       => "free_registration",
                "currency"           => $currency
            ]);

            if (!$sub_id) {
                // Optional: rollback user if your delete helper exists
                // deleteData("user", ["user_id" => $user_id]);
                $response = [
                    "status"    => 500,
                    "isSuccess" => false,
                    "message"   => "Failed to create subscription. Please try again later."
                ];
                break;
            }

            // -------------------------
            // 7) Send STYLED register email
            // -------------------------
            $mail = getDataByWhere("mailsetup", "*", ["mail_type" => "Register"]);
            if (!empty($mail) && isset($mail['template'], $mail['subject'])) {
                $timestamp = date("F j, Y \a\\t g:i A");
                $body = $mail['template'];
                
                // Replace placeholders
                $body = str_replace("{name}", $user_name, $body);
                $body = str_replace("{first_name}", $first_name, $body);
                $body = str_replace("{email}", $email, $body);
                $body = str_replace("{timestamp}", $timestamp, $body);
                
                // TRIPLE DECODE
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                // CLEAN UP
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
                // don't block registration if email fails
                @smtpmailer($email, $setting['support_mail'], ucwords($configs['dotcom']), $subject, $styled_body);
            }

            // -------------------------
            // 8) Success response
            // -------------------------
            $response = [
                "status"     => 200,
                "isSuccess"  => true,
                "message"    => "User registered successfully (free).",
                "user_id"    => $user_id,
                "course_id"  => $course_id,
                "session_id" => $session_id,
                "expire_date" => $expire_date
            ];

            break;

        case "user-registration-one-time-square-payment":
            // ---- Helper for consistent JSON responses
            $respond = function(int $status, bool $ok, string $message, array $extra = []) {
                return array_merge([
                    "status"    => $status,
                    "isSuccess" => $ok,
                    "message"   => $message,
                ], $extra);
            };
            // ---- 1) Read + normalize inputs
            $payment_status      = isset($_POST["payment_status"]) ? trim($_POST["payment_status"]) : "";
            $payment_id          = isset($_POST["payment_id"]) ? trim($_POST["payment_id"]) : "";
            $square_customer_id  = isset($_POST["square_customer_id"]) ? trim($_POST["square_customer_id"]) : ""; 
            $order_id            = isset($_POST["order_id"]) ? trim($_POST["order_id"]) : "";
            $amount              = isset($_POST["amount"]) ? trim($_POST["amount"]) : ""; 
            $currency            = isset($_POST["currency"]) ? trim($_POST["currency"]) : "USD";
            $created_at_raw      = isset($_POST["created_at"]) ? trim($_POST["created_at"]) : "";
            $payment_source_type = isset($_POST["payment_source_type"]) ? trim($_POST["payment_source_type"]) : "CARD";
            $course_id_in = isset($_POST["course_id"]) ? (int)$_POST["course_id"] : 0;
            $package_id = isset($_POST["package_id"]) ? (int)$_POST["package_id"] : 0;
            $session_id   = isset($_POST["session_id"]) ? (int)$_POST["session_id"] : 0;
            $first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
            $last_name  = isset($_POST["last_name"])  ? trim($_POST["last_name"])  : "";
            $email      = isset($_POST["email"])      ? trim($_POST["email"])      : "";
            $mobile     = isset($_POST["mobile"])     ? trim($_POST["mobile"])     : "";
            $password   = isset($_POST["password"])   ? (string)$_POST["password"] : "";
            
            // NEW: Read user_id from POST
            $posted_user_id = isset($_POST["user_id"]) ? (int)$_POST["user_id"] : 0;
            $referral_code = isset($_POST["ref_code"]) ? trim($_POST["ref_code"]) : (isset($_POST["referral_id"]) ? trim($_POST["referral_id"]) : "");
            $ref_type      = isset($_POST["ref_type"]) ? trim($_POST["ref_type"]) : "";
            // Basic Validation
            $allowed_payment_status = ["COMPLETED", "APPROVED"];
            if ($payment_status === "" || !in_array($payment_status, $allowed_payment_status, true)) {
                $response = $respond(400, false, "Payment not completed.", [
                    "payment_status" => $payment_status
                ]);
                break;
            }
        
            // Timezone and conversion
            $tz = new DateTimeZone("America/New_York");
            $now_dt = new DateTime("now", $tz);
            $now_mysql = $now_dt->format("Y-m-d H:i:s");
            $formatted_date = $now_dt->format("Y-m-d");

            // Expiration: 6 months (default)
            $expire_dt = clone $now_dt;
            $expire_dt->modify("+6 months");
            $expire_date = $expire_dt->format("Y-m-d");

            $amount_cents = (int) round(((float)$amount) * 100);
            
            // Format user name
            $user_name = $first_name . " " . $last_name;

            // Mapping Course ID (for both new and existing user paths)
            $mapping = [49 => 24, 40 => 27, 50 => 25, 51 => 26, 52 => 21];
            $course_id = isset($mapping[$course_id_in]) ? $mapping[$course_id_in] : $course_id_in;

            // ---- 2) CHECK IF USER EXISTS
            $existingUser = null;
            
            // First try by ID (if provided by Next.js BuyMoreSessions flow)
            if ($posted_user_id > 0) {
                $existingUser = getDataByWhere("user", "*", ["user_id" => $posted_user_id]);
            }
            
            // If not found by ID or no ID sent, check by Email
            if (empty($existingUser) && $email !== "") {
                $existingUser = getDataByWhere("user", "*", ["email" => $email]);
            }
            // Normalize existingUser (getDataByWhere can return a nested array)
            if (is_array($existingUser) && isset($existingUser[0]) && is_array($existingUser[0])) {
                $existingUser = $existingUser[0];
            }
            $is_existing = !empty($existingUser) && !empty($existingUser["user_id"]);
            $user_id = $is_existing ? (int)$existingUser["user_id"] : 0;
            if ($is_existing) {
                // ==========================================
                // PATH A: EXISTING USER (Buy More Sessions)
                // ==========================================
                
                // Update activation status if currently 'N'
                if ($existingUser["activation_status"] === "N") {
                    updateTable("user", ["activation_status" => "Y"], ["user_id" => $user_id]);
                }
                // Record Payment
                insertTable("square_payments", [
                    "payment_id"         => $payment_id,
                    "square_customer_id" => $square_customer_id,
                    "order_id"           => $order_id,
                    "amount"             => $amount_cents,
                    "currency"           => $currency,
                    "payment_status"     => $payment_status,
                    "created_at"         => $now_mysql,
                    "updated_at"         => $now_mysql,
                    "user_id"            => $user_id,
                    "user_created_at"    => $now_mysql
                ]);
                // Update Login
                insertTable("login", ["user_id" => $user_id, "last_login" => $now_mysql]);
                // Update Subscription
                $sub_data = [
                    "course_id"         => $course_id,
                    "user_id"           => $user_id,
                    "expire_date"       => $expire_date,
                    "package_id"        => $package_id,
                    "session_id"        => $session_id,
                    "amount"            => $amount,
                    "payment_mode"      => $payment_source_type,
                    "currency"          => $currency
                ];

                if ($square_customer_id !== "") $sub_data["customer_id"] = $square_customer_id;

                $existingSub = getDataByWhere("subscription", "id", ["user_id" => $user_id]);
                if (is_array($existingSub) && isset($existingSub[0]) && is_array($existingSub[0])) {
                    $existingSub = $existingSub[0];
                }

                if (is_array($existingSub) && !empty($existingSub["id"])) {
                    updateTable("subscription", $sub_data, ["id" => $existingSub["id"]]);
                    $sub_id = $existingSub["id"];
                } else {
                    $sub_id = insertTable("subscription", $sub_data);
                }
                $response = $respond(200, true, "Payment processed and sessions added.", [
                    "user_id" => $user_id,
                    "is_existing" => true
                ]);
            } else {
                // ==========================================
                // PATH B: NEW USER (Original Logic)
                // ==========================================
                
                if ($password === "" || $email === "") {
                    $response = $respond(400, false, "Email and password are required for new registration.");
                    break;
                }

                // Path B: New user creation
                // course_id is already resolved at the top of the case

                $user_id = insertTable("user", [
                    "email"             => $email,
                    "password"          => md5($password),
                    "first_name"        => $first_name,
                    "last_name"         => $last_name,
                    "mobile"            => $mobile,
                    "user_type"         => "Student",
                    "reg_date"          => $formatted_date,
                    "activation_status" => "Y",
                    "reg_at"            => "asvab-web",
                    "fp"                => ($course_id != 27 ? 1 : 0),
                    "pt"                => ($course_id != 27 ? 1 : 0),
                    "referral_code"     => $referral_code,
                    "ref_type"          => $ref_type
                ]);
                if (!$user_id) {
                    $response = $respond(500, false, "Failed to create user.");
                    break;
                }
                // Record Payment
                insertTable("square_payments", [
                    "payment_id"         => $payment_id,
                    "square_customer_id" => $square_customer_id,
                    "order_id"           => $order_id,
                    "amount"             => $amount_cents,
                    "currency"           => $currency,
                    "payment_status"     => $payment_status,
                    "created_at"         => $now_mysql,
                    "updated_at"         => $now_mysql,
                    "user_id"            => $user_id,
                    "user_created_at"    => $now_mysql
                ]);
                insertTable("login", ["user_id" => $user_id, "last_login" => $now_mysql]);
                // Validate required fields
                if ($course_id <= 0) {
                    $response = [
                        "status"=>400,
                        "isSuccess"=>false,
                        "message"=>"Missing or invalid course_id."
                    ];
                    break;
                }
                if ($package_id <= 0) {
                    $response = [
                        "status"=>400,
                        "isSuccess"=>false,
                        "message"=>"Missing or invalid package_id."
                    ];
                    break;
                }
                if ($session_id <= 0) {
                    $response = [
                        "status"=>400,
                        "isSuccess"=>false,
                        "message"=>"Missing or invalid session_id."
                    ];
                    break;
                }
                $sub_id = insertTable("subscription", [
                    "course_id"         => $course_id,
                    "user_id"           => $user_id,
                    "expire_date"       => $expire_date,
                    "package_id"        => $package_id,
                    "session_id"        => $session_id,
                    "amount"            => $amount,
                    "payment_mode"      => $payment_source_type,
                    "currency"          => $currency
                ]);
                // -------------------------
                // Send STYLED register email
                // -------------------------
                $mail = getDataByWhere("mailsetup", "*", ["mail_type" => "Register"]);
                if (!empty($mail) && !empty($mail["template"]) && !empty($mail["subject"])) {
                    $timestamp = date("F j, Y \a\\t g:i A");
                    $body = $mail['template'];
                    
                    // Replace placeholders
                    $body = str_replace("{name}", $user_name, $body);
                    $body = str_replace("{first_name}", $first_name, $body);
                    $body = str_replace("{email}", $email, $body);
                    $body = str_replace("{timestamp}", $timestamp, $body);
                    
                    // TRIPLE DECODE
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    
                    // CLEAN UP
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
                    @smtpmailer($email, "info@asvab-tutoring.com", "ASVAB Tutoring", $subject, $styled_body);
                }
                $response = $respond(200, true, "Registration and payment successful.", [
                    "user_id" => $user_id,
                    "is_existing" => false
                ]);
            }
        break;

        case "user-registration-subscription":

            // 1) Inputs
            $subscription_id    = isset($_POST['subscription_id']) ? trim($_POST['subscription_id']) : '';
            $square_customer_id = isset($_POST['square_customer_id']) ? trim($_POST['square_customer_id']) : '';
            $plan_variation_id  = isset($_POST['plan_variation_id']) ? trim($_POST['plan_variation_id']) : '';

            $posted_course_id   = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;

            // ✅ ADDED: session_id from checkout flow
            $posted_session_id  = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;

            // ✅ ADDED: package_id from checkout flow
            $posted_package_id  = isset($_POST['package_id']) ? (int)$_POST['package_id'] : 0;

            $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $mobile   = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';

            $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
            $last_name  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';

            // Optional: amount/currency from client (we will NOT trust it for access yet)
            $raw_amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
            $decimal_amount = number_format(($raw_amount / 100), 2, '.', '');
            $currency = isset($_POST['currency']) ? trim($_POST['currency']) : 'USD';

            $referral_code = isset($_POST["ref_code"]) ? trim($_POST["ref_code"]) : "";
            $ref_type      = isset($_POST["ref_type"]) ? trim($_POST["ref_type"]) : "";

            // 2) Validate required fields
            if ($subscription_id === '' || $plan_variation_id === '' || $email === '' || $password === '') {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing required fields."
                ];
                break;
            }

            // ✅ ADDED: require package_id
            if ($posted_package_id <= 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing package_id."
                ];
                break;
            }

            // ✅ ADDED: require session_id
            if ($posted_session_id <= 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing session_id."
                ];
                break;
            }

            // 3) Date setup
            $timezone = new DateTimeZone('America/New_York');
            $now = new DateTime('now', $timezone);
            $formatted_date = $now->format('Y-m-d');
            $formatted_date_time = $now->format('Y-m-d H:i:s');

            // 4) Prevent duplicate subscription insert
            $sub_exists = (int)getTotalRow("square_subscriptions", [
                "subscription_id" => $subscription_id
            ]);
            if ($sub_exists) {
                $existing = getDataByWhere("square_subscriptions", "*", [
                    "subscription_id" => $subscription_id
                ]);

                $response = [
                    "status"          => 200,
                    "isSuccess"       => true,
                    "message"         => "Subscription already recorded. Waiting for invoice payment.",
                    "user_id"         => (int)$existing["user_id"],
                    "subscription_id" => $subscription_id
                ];
                break;
            }

            // 5) Prevent duplicate email
            $email_exists = (int)getTotalRow("user", ["email" => $email]);
            if ($email_exists > 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Email already registered."
                ];
                break;
            }

            // 6) Map course IDs (your existing mapping)
            $course_id = $posted_course_id;
            if ($posted_course_id == 49) {
                $course_id = 24;
            } else if ($posted_course_id == 40) {
                $course_id = 27;
            } else if ($posted_course_id == 50) {
                $course_id = 25;
            } else if ($posted_course_id == 51) {
                $course_id = 26;
            } else if ($posted_course_id == 52) {
                $course_id = 21;
            }

            // ✅ UPDATED: use posted package_id (no hard-coded 62)
            $package_id = $posted_package_id;

            // 8) Create User (PENDING access)
            $user_id = insertTable("user", [
                "email"             => $email,
                "password"          => md5($password),
                "first_name"        => $first_name,
                "last_name"         => $last_name,
                "mobile"            => $mobile,
                "user_type"         => "Student",
                "reg_date"          => $formatted_date,
                "activation_status" => "N",   // wait for invoice.payment_made status=PAID
                "reg_at"            => "asvab-web",
                "fp"                => 1,
                "pt"                => 1,
                "referral_code"     => $referral_code,
                "ref_type"          => $ref_type
            ]);

            if (!$user_id) {
                $response = [
                    "status"    => 500,
                    "isSuccess" => false,
                    "message"   => "User creation failed."
                ];
                break;
            }

            // 9) Create login record
            insertTable("login", [
                "user_id"    => $user_id,
                "last_login" => $formatted_date_time
            ]);

            // 10) Create subscription row NOW (with placeholders)
            // amount/payment_mode/currency will be updated by webhook when invoice is PAID
            $sub_id = insertTable("subscription", [
                "course_id"         => $course_id,
                "user_id"           => $user_id,
                "subscription_date" => $formatted_date,
                "package_id"        => $package_id,
                "session_id"        => $posted_session_id, // ✅ ADDED (remove if column doesn't exist)
                "amount"            => "0.00",
                "payment_mode"      => "",
                "currency"          => "",
                "txn_id"            => $subscription_id
            ]);

            if (!$sub_id) {
                $response = [
                    "status"    => 500,
                    "isSuccess" => false,
                    "message"   => "Failed to create subscription record."
                ];
                break;
            }

            // 11) Save Square subscription metadata (PENDING)
            $okSub = insertTable("square_subscriptions", [
                "subscription_id"    => $subscription_id,
                "square_customer_id" => $square_customer_id,
                "plan_variation_id"  => $plan_variation_id,
                "amount"             => $decimal_amount,   // ok to store expected amount
                "currency"           => $currency,
                "status"             => "PENDING",
                "start_date"         => $formatted_date,
                "cancel_date"        => null,
                "user_id"            => $user_id,
                "created_at"         => $formatted_date_time,
                "updated_at"         => $formatted_date_time
            ]);

            if (!$okSub) {
                $response = [
                    "status"    => 500,
                    "isSuccess" => false,
                    "message"   => "Failed to save Square subscription metadata."
                ];
                break;
            }

            $response = [
                "status"          => 200,
                "isSuccess"       => true,
                "message"         => "User created. Access will activate after invoice payment.",
                "user_id"         => $user_id,
                "subscription_id" => $subscription_id
            ];

            break;

        case "web-user-registration-subscription":
            // 1) Inputs
            $square_subscription_id = isset($_POST['square_subscription_id']) ? trim($_POST['square_subscription_id']) : '';
            $square_customer_id     = isset($_POST['square_customer_id']) ? trim($_POST['square_customer_id']) : '';
            $plan_variation_id      = isset($_POST['plan_variation_id']) ? trim($_POST['plan_variation_id']) : '';
            $subscription_date      = isset($_POST['subscription_date']) ? trim($_POST['subscription_date']) : '';
            $status                 = isset($_POST['status']) ? trim($_POST['status']) : 'PENDING';
            $raw_amount             = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
            $decimal_amount         = number_format(($raw_amount / 100), 2, '.', '');
            $currency               = isset($_POST['currency']) ? trim($_POST['currency']) : 'USD';
            $session_id             = isset($_POST['session_id']) ? trim($_POST['session_id']) : '';
            $course_id              = isset($_POST['course_id']) ? trim($_POST['course_id']) : '';
            $package_id             = isset($_POST['package_id']) ? trim($_POST['package_id']) : '';
            $user_id                = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
            $auth_token             = isset($_POST['auth_token']) ? trim($_POST['auth_token']) : '';

            // 2) Validate required fields
            if ($square_subscription_id === '' || $plan_variation_id === '' || $square_customer_id === '') {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing required fields."
                ];
                break;
            }

            // require session_id
            if ($session_id <= 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing session_id."
                ];
                break;
            }

            // require course_id
            if ($course_id <= 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing course_id."
                ];
                break;
            }

            // require package_id
            if ($package_id <= 0) {
                $response = [
                    "status"    => 400,
                    "isSuccess" => false,
                    "message"   => "Missing package_id."
                ];
                break;
            }

            // ?) TODO: Check for auth token
            // if ($auth_token === '') {
            //     $response = [
            //         "status"    => 400,
            //         "isSuccess" => false,
            //         "message"   => "Missing auth_token."
            //     ];
            //     break;
            // }

            // // verify auth token
            // $token_exists = (int)getTotalRow("user", [
            //     "auth_token" => $auth_token
            // ]);
            // if ($token_exists) {
            //     $response = [
            //         "status"    => 400,
            //         "isSuccess" => false,
            //         "message"   => "Auth token already used."
            //     ];
            //     break;
            // }

            // 3) Prevent duplicate subscription insert
            $sub_exists = (int)getTotalRow("square_subscriptions", [
                "subscription_id" => $square_subscription_id
            ]);
            if ($sub_exists) {
                $existing = getDataByWhere("square_subscriptions", "*", [
                    "subscription_id" => $square_subscription_id
                ]);

                $response = [
                    "status"          => 200,
                    "isSuccess"       => true,
                    "message"         => "Subscription already recorded.",
                    "user_id"         => (int)$existing["user_id"],
                    "subscription_id" => $square_subscription_id
                ];
                break;
            }

            // 4) Map course IDs (your existing mapping)
            $course_id = $posted_course_id;
            if ($posted_course_id == 49) {
                $course_id = 24;
            } else if ($posted_course_id == 40) {
                $course_id = 27;
            } else if ($posted_course_id == 50) {
                $course_id = 25;
            } else if ($posted_course_id == 51) {
                $course_id = 26;
            } else if ($posted_course_id == 52) {
                $course_id = 21;
            }

            // Need to see if this is required
            // ?) Create login record
            // insertTable("login", [
            //     "user_id"    => $user_id,
            //     "last_login" => $formatted_date_time
            // ]);

            // 5) Update subscription row for web user
            // amount/payment_mode/currency will be updated by webhook when invoice is PAID
            $existing_subscription = getDataByWhere("subscription", "*", ["user_id" => $user_id]);

            // Single row check just in case the query returns multiple rows
            if (!empty($existing_subscription) && isset($existing_subscription[0]) && is_array($existing_subscription[0])) {
                $existing_subscription = $existing_subscription[0];
            }

            $sub_data = [
                "course_id"         => $course_id,
                "subscription_date" => $subscription_date,
                "package_id"        => $package_id,
                "session_id"        => $session_id,
                "amount"            => "0.00",
                "payment_mode"      => "",
                "currency"          => $currency,
                "txn_id"            => $square_subscription_id
            ];

            if (!empty($existing_subscription)) {
                // Update existing subscription row
                updateTable("subscription", $sub_data, ["user_id" => $user_id]);
                $sub_id = $existing_subscription['id']; // Assign existing id if needed
            } else {
                // Insert new subscription row
                $sub_data["user_id"] = $user_id;
                $sub_id = insertTable("subscription", $sub_data);
            }

            if (!$sub_id) {
                $response = [
                    "status"    => 500,
                    "isSuccess" => false,
                    "message"   => "Failed to create subscription record."
                ];
                break;
            }

            // 6) Save Square subscription metadata
            $okSub = insertTable("square_subscriptions", [
                "subscription_id"    => $square_subscription_id,
                "square_customer_id" => $square_customer_id,
                "plan_variation_id"  => $plan_variation_id,
                "amount"             => $decimal_amount,
                "currency"           => $currency,
                "status"             => $status,
                "start_date"         => $subscription_date,
                "cancel_date"        => null,
                "user_id"            => $user_id,
                "created_at"         => $subscription_date,
                "updated_at"         => $subscription_date
            ]);

            if (!$okSub) {
                $response = [
                    "status"    => 500,
                    "isSuccess" => false,
                    "message"   => "Failed to save Square subscription metadata."
                ];
                break;
            }

            $response = [
                "status"          => 200,
                "isSuccess"       => true,
                "message"         => "User created. Access will activate after invoice payment.",
                "user_id"         => $user_id,
                "subscription_id" => $subscription_id
            ];
            break;

        case "invoice-payment-made":

            $respond = function (int $status, bool $ok, string $msg, array $extra = []) {
                http_response_code($status);
                echo json_encode(array_merge([
                    "status"    => $status,
                    "isSuccess" => $ok,
                    "message"   => $msg
                ], $extra));
                exit;
            };

            // ---- Inputs
            $invoice_id         = trim($_POST['invoice_id'] ?? '');
            $subscription_id    = trim($_POST['subscription_id'] ?? '');
            $payment_id         = trim($_POST['payment_id'] ?? '');
            $square_customer_id = trim($_POST['square_customer_id'] ?? '');
            $amount             = trim($_POST['amount'] ?? '0');
            $currency           = trim($_POST['currency'] ?? 'USD');
            $paid_at_raw        = trim($_POST['paid_at'] ?? '');
            $event_created_at   = trim($_POST['event_created_at'] ?? '');

            // ✅ NEW: email from Next.js (most reliable)
            $email              = trim($_POST['email'] ?? '');

            // ✅ NEW: next_billing_date (YYYY-MM-DD) from webhook (computed in Next.js)
            $next_billing_date_raw = trim($_POST['next_billing_date'] ?? '');

            if ($invoice_id === '' || $subscription_id === '') {
                $respond(400, false, "Missing invoice_id or subscription_id");
            }

            $now = date("Y-m-d H:i:s");

            /* ============================================================
            Convert ISO 8601 -> MySQL DATETIME
            ============================================================ */
            $paid_at = $now;
            if ($paid_at_raw !== '') {
                $ts = strtotime($paid_at_raw);
                if ($ts !== false) $paid_at = date("Y-m-d H:i:s", $ts);
            }

            /* ============================================================
            Normalize next_billing_date -> MySQL DATE (YYYY-MM-DD)
            ============================================================ */
            $next_billing_date = "";
            if ($next_billing_date_raw !== "") {
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $next_billing_date_raw)) {
                    $next_billing_date = $next_billing_date_raw;
                } else {
                    $ts = strtotime($next_billing_date_raw);
                    if ($ts !== false) $next_billing_date = date("Y-m-d", $ts);
                }
            }

            /* ============================================================
            1) FIND USER_ID (MOST RELIABLE: by email in user table)
            ============================================================ */
            $user_id = 0;

            if ($email !== '') {
                $user_id = intval(getFldValueById("user", "user_id", ["email" => $email]));
            }

            // Fallback (only if email missing or not found)
            $sub = getDataByWhere("square_subscriptions", "*", [
                "subscription_id" => $subscription_id
            ]);

            if (empty($sub)) {
                $respond(404, false, "Subscription not found", [
                    "subscription_id" => $subscription_id
                ]);
            }

            if ($user_id <= 0) {
                $user_id = (int)($sub['user_id'] ?? 0);
            }

            if ($user_id <= 0) {
                $respond(404, false, "User not found (email not found and subscription user_id missing)", [
                    "email" => $email,
                    "subscription_id" => $subscription_id
                ]);
            }

            // Fallback customer_id from stored subscription row
            if ($square_customer_id === '') {
                $square_customer_id = trim((string)($sub['square_customer_id'] ?? ''));
            }

            // For expire_date extension we need duration (months)
            $plan_variation_id = trim((string)($sub['plan_variation_id'] ?? ''));

            // Map Square plan_variation_id => months
            $planToMonths = [
                // Study Subscription plan
                "2HEOFJALJWNOPIAV6534UHSF" => 1,
                "S37CLQST74S6TCF2GXECBXH2" => 3,
                "3QJROK4Z23H7LKZVBARXE6KS" => 6,
                "MSD3BX7G3ADUK2AEYBSG6N5N" => 12,
            ];

            $monthsToAdd = (int)($planToMonths[$plan_variation_id] ?? 1);

            /* ============================================================
            2) UPSERT INVOICE (idempotent)
            ============================================================ */
            $invoiceExists = getDataByWhere("square_invoices", "*", [
                "invoice_id" => $invoice_id
            ]);

            $alreadyPaid = false;
            if (!empty($invoiceExists)) {
                if (strtoupper(trim($invoiceExists['status'] ?? '')) === "PAID") {
                    $alreadyPaid = true;
                }
            }

            if (!empty($invoiceExists)) {
                updateTable("square_invoices", [
                    "subscription_id"    => $subscription_id,
                    "payment_id"         => $payment_id,
                    "square_customer_id" => $square_customer_id,
                    "user_id"            => $user_id,
                    "amount"             => $amount,
                    "status"             => "PAID",
                    "paid_at"            => $paid_at,
                    "updated_at"         => $now
                ], [
                    "invoice_id" => $invoice_id
                ]);
            } else {
                insertTable("square_invoices", [
                    "invoice_id"         => $invoice_id,
                    "subscription_id"    => $subscription_id,
                    "payment_id"         => $payment_id,
                    "square_customer_id" => $square_customer_id,
                    "user_id"            => $user_id,
                    "amount"             => $amount,
                    "status"             => "PAID",
                    "paid_at"            => $paid_at,
                    "created_at"         => $now,
                    "updated_at"         => $now
                ]);
            }

            /* ============================================================
            3) UPDATE square_subscriptions.status (+ next_billing_date)
            ============================================================ */
            $subUpdate = [
                "status"     => "ACTIVE",
                "amount"             => $amount,
                "currency"           => $currency,
                "updated_at" => $now

            ];

            if ($next_billing_date !== "") {
                $subUpdate["next_billing_date"] = $next_billing_date;
            }

            updateTable("square_subscriptions", $subUpdate, [
                "subscription_id" => $subscription_id
            ]);

            /* ============================================================
            ✅ NEW RULE: If dispute exists for this user and not WON/WIN,
            then DO NOT update activation_status to Y.
            ============================================================ */
            $latestDisputeState = trim((string)getFldValueById(
                "square_disputes",
                "state",
                'user_id="' . $user_id . '" ORDER BY id DESC'
            ));

            $hasDispute = ($latestDisputeState !== '' && $latestDisputeState !== null);
            $canActivate = true;

            if ($hasDispute) {
                $ds = strtoupper(trim($latestDisputeState));
                // accept both WON and WIN (user asked "WIN", Square usually uses "WON")
                if ($ds !== "WON" && $ds !== "WIN") {
                    $canActivate = false;
                }
            }

            if ($canActivate) {
                // Verify user exists (by user_id)
                $check_user = intval(getFldValueById("user", "user_id", ["user_id" => $user_id]));
                if ($check_user <= 0) {
                    $respond(404, false, "User row not found to activate", [
                        "user_id" => $user_id,
                        "email"   => $email
                    ]);
                }

                updateTable("user", [
                    "activation_status" => "Y"
                ], [
                    "user_id" => $user_id
                ]);
            }

            /* ============================================================
            5) UPDATE PORTAL SUBSCRIPTION (expire_date extend)
            - keep your existing idempotent behavior (only if not alreadyPaid)
            ============================================================ */
            $newExpire = null;
            $baseDateUsed = null;

            if (!$alreadyPaid) {

                $portalSub = getDataByWhere("subscription", "*", [
                    "user_id" => $user_id
                ]);

                if (!empty($portalSub)) {

                    $currentExpireRaw = trim((string)($portalSub['expire_date'] ?? ''));

                    $nowDateTs = strtotime(date("Y-m-d"));
                    $currentExpireTs = $currentExpireRaw !== '' ? strtotime($currentExpireRaw) : false;

                    $baseTs = ($currentExpireTs !== false && $currentExpireTs >= $nowDateTs)
                        ? $currentExpireTs
                        : $nowDateTs;

                    $baseDateUsed = date("Y-m-d", $baseTs);
                    $newExpire = date("Y-m-d", strtotime("+" . $monthsToAdd . " months", $baseTs));

                    updateTable("subscription", [
                        "amount"       => $amount,
                        "currency"     => $currency,
                        "payment_mode" => "square_subscription",
                        "customer_id"  => $square_customer_id,
                        "expire_date"  => $newExpire,
                        "updated_at"   => $now
                    ], [
                        "user_id" => $user_id
                    ]);
                }
            }

            $respond(
                200,
                true,
                $alreadyPaid
                    ? "Invoice already processed"
                    : "Invoice payment processed successfully",
                [
                    "email_used"         => ($email !== '' ? $email : null),
                    "user_id"            => $user_id,
                    "activation_updated" => ($canActivate ? "YES" : "NO"),
                    "dispute_state"      => ($hasDispute ? $latestDisputeState : null),

                    "months_added"       => $monthsToAdd,
                    "base_date_used"     => $baseDateUsed,
                    "new_expire_date"    => $newExpire,
                    "plan_variation_id"  => $plan_variation_id,

                    "next_billing_date"  => ($next_billing_date !== "" ? $next_billing_date : null),
                    "next_billing_raw"   => ($next_billing_date_raw !== "" ? $next_billing_date_raw : null),
                ]
            );

            break;

        case "payment-updated-webhook":

            $respond = function (int $status, bool $ok, string $msg, array $extra = []) {
                http_response_code($status);
                echo json_encode(array_merge([
                    "status"    => $status,
                    "isSuccess" => $ok,
                    "message"   => $msg,
                ], $extra));
                exit;
            };

            // ---- Inputs
            $event_id          = trim($_POST["event_id"] ?? "");
            $event_type        = trim($_POST["event_type"] ?? "");
            $event_created_at  = trim($_POST["event_created_at"] ?? "");

            $payment_id         = trim($_POST["payment_id"] ?? "");
            $square_customer_id = trim($_POST["square_customer_id"] ?? "");
            $order_id           = trim($_POST["order_id"] ?? "");
            $payment_status     = trim($_POST["payment_status"] ?? "");
            $amount_raw         = trim($_POST["amount"] ?? "");  // cents
            $currency           = trim($_POST["currency"] ?? "");
            $payment_created_at = trim($_POST["payment_created_at"] ?? "");
            $payment_updated_at = trim($_POST["payment_updated_at"] ?? "");

            // ✅ NEW: email from webhook (Next route sends this)
            $buyer_email_address = trim($_POST["buyer_email_address"] ?? "");
            if ($buyer_email_address === "") $buyer_email_address = trim($_POST["email"] ?? "");

            if ($payment_id === "")     $respond(400, false, "Missing payment_id");
            if ($payment_status === "") $respond(400, false, "Missing payment_status");

            error_log("[payment-updated-webhook] event_id={$event_id} type={$event_type} payment_id={$payment_id} status={$payment_status} email={$buyer_email_address}");

            // ---- Normalize amount to integer string (keep cents)
            $amount_int = null;
            if ($amount_raw !== "" && is_numeric($amount_raw)) {
                $amount_int = (string)intval($amount_raw);
            }

            // ---- Datetimes
            $created_dt = ($payment_created_at !== "" ? date("Y-m-d H:i:s", strtotime($payment_created_at)) : null);
            $updated_dt = ($payment_updated_at !== "" ? date("Y-m-d H:i:s", strtotime($payment_updated_at)) : date("Y-m-d H:i:s"));

            // ---- Status precedence (only upgrade, never downgrade)
            $rank = function (string $s): int {
                $s = strtoupper(trim($s));
                if ($s === "COMPLETED") return 4;
                if ($s === "APPROVED")  return 3;
                if ($s === "PENDING")   return 2;
                if ($s === "FAILED" || $s === "CANCELED") return 1;
                return 0;
            };

            // ✅ Correct call: fields="*", cond=["payment_id"=>...], print=0
            $existing = getDataByWhere("square_payments", "*", ["payment_id" => $payment_id], 0);

            // ✅ Helper: try to resolve user_id by email (ONLY if provided)
            $resolve_user_id_by_email = function (string $email) {
                $email = trim($email);
                if ($email === "") return 0;

                // IMPORTANT: adjust field name if your users table uses different email column
                // Common ones: email, user_email, email_address
                $u = getDataByWhere("user", "*", ["email" => $email], 0);
                $uid = intval($u["user_id"] ?? $u["id"] ?? 0);

                // Fallback attempts if your column name is not "email"
                if ($uid <= 0) {
                    $u = getDataByWhere("user", "*", ["user_email" => $email], 0);
                    $uid = intval($u["user_id"] ?? $u["id"] ?? 0);
                }
                return $uid > 0 ? $uid : 0;
            };

            // If no row, insert
            if (empty($existing) || trim((string)($existing["payment_id"] ?? "")) === "") {

                // ✅ NEW: decide user_id for insert (if possible)
                $user_id = 0;
                if ($buyer_email_address !== "") {
                    $user_id = $resolve_user_id_by_email($buyer_email_address);
                }

                $insert = [
                    "payment_id"     => $payment_id,
                    "payment_status" => strtoupper($payment_status),
                    "created_at"     => ($created_dt ?? $updated_dt),
                    "updated_at"     => $updated_dt,
                ];

                if ($square_customer_id !== "") $insert["square_customer_id"] = $square_customer_id;
                if ($order_id !== "")           $insert["order_id"] = $order_id;
                if ($amount_int !== null)       $insert["amount"] = $amount_int;
                if ($currency !== "")           $insert["currency"] = $currency;

                // ✅ NEW: store email + user_id if your table has these columns
                if ($buyer_email_address !== "") $insert["buyer_email_address"] = $buyer_email_address;
                if ($user_id > 0) $insert["user_id"] = (string)$user_id;

                insertTable("square_payments", $insert);

                $respond(200, true, "Inserted payment row", [
                    "payment_id"      => $payment_id,
                    "status_saved"    => strtoupper($payment_status),
                    "existing_found"  => false,
                    "user_id_saved"   => ($user_id > 0 ? $user_id : null),
                    "email_saved"     => ($buyer_email_address !== "" ? $buyer_email_address : null),
                ]);
            }

            // Update branch
            $existing_status = strtoupper(trim((string)($existing["payment_status"] ?? "")));
            $incoming_status = strtoupper(trim($payment_status));

            $final_status = ($rank($incoming_status) >= $rank($existing_status))
                ? $incoming_status
                : $existing_status;

            $update = [
                "payment_status" => $final_status,
                "updated_at"     => $updated_dt,
            ];

            if ($square_customer_id !== "") $update["square_customer_id"] = $square_customer_id;
            if ($order_id !== "")           $update["order_id"] = $order_id;
            if ($amount_int !== null)       $update["amount"] = $amount_int;
            if ($currency !== "")           $update["currency"] = $currency;

            // Set created_at only if empty in DB and we have it
            if ($created_dt !== null) {
                $existing_created = trim((string)($existing["created_at"] ?? ""));
                if ($existing_created === "" || $existing_created === "0000-00-00 00:00:00") {
                    $update["created_at"] = $created_dt;
                }
            }

            // ✅ NEW: if user_id missing in DB, try to fill it using buyer_email_address
            $existing_user_id = intval($existing["user_id"] ?? 0);
            if ($existing_user_id <= 0 && $buyer_email_address !== "") {
                $resolved_user_id = $resolve_user_id_by_email($buyer_email_address);
                if ($resolved_user_id > 0) {
                    $update["user_id"] = (string)$resolved_user_id;
                }
                // Optional: keep the email in the row as well (if column exists)
                $update["buyer_email_address"] = $buyer_email_address;
            } else {
                // Optional: still store email if provided and column exists
                if ($buyer_email_address !== "") $update["buyer_email_address"] = $buyer_email_address;
            }

            updateTable("square_payments", $update, ["payment_id" => $payment_id]);

            $respond(200, true, "Updated payment row", [
                "payment_id"       => $payment_id,
                "existing_found"   => true,
                "status_before"    => $existing_status,
                "status_incoming"  => $incoming_status,
                "status_saved"     => $final_status,
                "user_id_before"   => ($existing_user_id > 0 ? $existing_user_id : null),
                "user_id_after"    => (isset($update["user_id"]) ? intval($update["user_id"]) : ($existing_user_id > 0 ? $existing_user_id : null)),
                "email_used"       => ($buyer_email_address !== "" ? $buyer_email_address : null),
            ]);

            break;

        case "square-dispute":

            $respond = function (int $status, bool $ok, string $msg, array $extra = []) {
                http_response_code($status);
                echo json_encode(array_merge([
                    "status"    => $status,
                    "isSuccess" => $ok,
                    "message"   => $msg
                ], $extra));
                exit;
            };

            // ---- Inputs
            $dispute_id        = trim($_POST['dispute_id'] ?? '');
            $payment_id        = trim($_POST['payment_id'] ?? '');
            $amount            = trim($_POST['amount'] ?? '0.00');
            $currency          = trim($_POST['currency'] ?? '');
            $card_brand        = trim($_POST['card_brand'] ?? '');
            $reason            = trim($_POST['reason'] ?? '');
            $state             = trim($_POST['state'] ?? '');
            $due_at            = trim($_POST['due_at'] ?? '');
            $square_created_at = trim($_POST['square_created_at'] ?? '');
            $square_updated_at = trim($_POST['square_updated_at'] ?? '');

            if ($dispute_id === '') $respond(400, false, "Missing dispute_id");
            if ($payment_id === '') $respond(400, false, "Missing payment_id");

            // ---- Normalize dates
            $fmtDT = function (string $s) {
                $s = trim($s);
                if ($s === '') return '';
                $ts = strtotime($s);
                return $ts ? date("Y-m-d H:i:s", $ts) : $s;
            };

            $due_at_dt            = $fmtDT($due_at);
            $square_created_at_dt = $fmtDT($square_created_at);
            $square_updated_at_dt = $fmtDT($square_updated_at);

            // ============================================================
            // 1) Resolve user_id from square_payments
            // ============================================================
            $user_id = intval(getFldValueById("square_payments", "user_id", ["payment_id" => $payment_id]));
            if ($user_id <= 0) {
                $respond(404, false, "Payment not found or user_id missing", [
                    "payment_id" => $payment_id
                ]);
            }

            // ============================================================
            // 2) UPSERT dispute row (idempotent by dispute_id)
            // ============================================================
            $existing_id = intval(getFldValueById("square_disputes", "id", ["dispute_id" => $dispute_id]));

            $data = [
                "user_id"           => $user_id,
                "dispute_id"        => $dispute_id,
                "payment_id"        => $payment_id,
                "amount"            => $amount,
                "currency"          => $currency,
                "card_brand"        => $card_brand,
                "reason"            => $reason,
                "state"             => $state,
                "due_at"            => $due_at_dt,
                "square_created_at" => $square_created_at_dt,
                "square_updated_at" => $square_updated_at_dt,
                "updated_at"        => date("Y-m-d H:i:s"),
            ];

            if ($existing_id <= 0) {
                $data["created_at"] = date("Y-m-d H:i:s");
                insertTable("square_disputes", $data);
                $db_action = "inserted";
            } else {
                updateTable("square_disputes", $data, ["id" => $existing_id]);
                $db_action = "updated";
            }

            // ============================================================
            // 3) NEW RULE: deactivate until resolved; reactivate only if WON
            //    activation_status ENUM('Y','N','D')
            // ============================================================
            $stateU = strtoupper(trim($state));
            $new_activation = ($stateU === "WON") ? "Y" : "N";

            // Verify user exists
            $check_user = intval(getFldValueById("user", "user_id", ["user_id" => $user_id]));
            if ($check_user <= 0) {
                $respond(404, false, "User not found", ["user_id" => $user_id]);
            }

            updateTable("user", [
                "activation_status" => $new_activation
            ], [
                "user_id" => $user_id
            ]);

            $activation_after = getFldValueById("user", "activation_status", ["user_id" => $user_id]);

            $respond(200, true, "Dispute processed", [
                "dispute_id"               => $dispute_id,
                "payment_id"               => $payment_id,
                "user_id"                  => $user_id,
                "state"                    => $state,
                "db_action"                => $db_action,
                "activation_status_after"  => $activation_after
            ]);

            break;

        case "subscription-updated":

            // ------------------------------------------------------------
            // Robust responder
            // ------------------------------------------------------------
            $respond = function (int $status, bool $ok, string $msg, array $extra = []) {
                http_response_code($status);
                echo json_encode(array_merge([
                    "status"    => $status,
                    "isSuccess" => $ok,
                    "message"   => $msg
                ], $extra));
                exit;
            };

            // ------------------------------------------------------------
            // Helper: normalize getDataByWhere() output to 1 row
            // ------------------------------------------------------------
            $firstRow = function ($r) {
                if (!is_array($r)) return [];
                // If it's a list of rows, use the first row
                if (isset($r[0]) && is_array($r[0])) return $r[0];
                // Otherwise assume associative row
                return $r;
            };

            // ------------------------------------------------------------
            // Accept x-www-form-urlencoded OR JSON
            // ------------------------------------------------------------
            $raw = file_get_contents("php://input");
            $j = json_decode($raw, true);
            if (!is_array($j)) $j = [];

            $in = function (string $k, $default = '') use ($j) {
                if (isset($_POST[$k])) return $_POST[$k];
                if (isset($j[$k])) return $j[$k];
                return $default;
            };

            // ------------------------------------------------------------
            // Inputs (from Next.js / webhooks / internal)
            // ------------------------------------------------------------
            $subscription_id        = trim((string)$in('subscription_id', ''));
            $square_customer_id     = trim((string)$in('square_customer_id', ''));
            $plan_variation_id      = trim((string)$in('plan_variation_id', ''));
            $status                 = trim((string)$in('status', ''));
            $start_date             = trim((string)$in('start_date', ''));         // YYYY-MM-DD
            $next_billing_date      = trim((string)$in('next_billing_date', ''));  // YYYY-MM-DD
            $charged_through_date   = trim((string)$in('charged_through_date', '')); // YYYY-MM-DD

            // Optional cancellation fields
            $cancel_effective_date  = trim((string)$in('cancel_effective_date', '')); // YYYY-MM-DD
            $cancel_reason          = trim((string)$in('cancel_reason', ''));

            // Extra optional fields
            $user_id_posted         = trim((string)$in('user_id', ''));
            $course_id_posted       = trim((string)$in('course_id', ''));
            $amount_posted          = trim((string)$in('amount', ''));              // "15.00"
            $currency_posted        = trim((string)$in('currency', ''));            // "USD"
            $payment_mode_posted    = trim((string)$in('payment_mode', 'subscription'));

            // Debug flag
            $debug_sql              = (int)$in('debug_sql', 0);

            if ($subscription_id === '') {
                $respond(400, false, "Missing subscription_id");
            }

            $now          = date("Y-m-d H:i:s");
            $status_upper = strtoupper($status);

            // ------------------------------------------------------------
            // Determine whether row exists (by subscription_id)
            // ------------------------------------------------------------
            $rowRaw = getDataByWhere("square_subscriptions", "*", ["subscription_id" => $subscription_id]);
            $row    = $firstRow($rowRaw);
            $exists = (!empty($row["subscription_id"]));

            // ------------------------------------------------------------
            // If NEW row, square_customer_id is REQUIRED (your DB requires it)
            // ------------------------------------------------------------
            if (!$exists && $square_customer_id === '') {
                $respond(400, false, "Missing square_customer_id for new subscription insert.", [
                    "subscription_id" => $subscription_id
                ]);
            }

            // ------------------------------------------------------------
            // Resolve user_id (priority: posted -> existing row -> by square_customer_id)
            // ------------------------------------------------------------
            $user_id = "";
            if ($user_id_posted !== "") {
                $user_id = $user_id_posted;
            } elseif ($exists && !empty($row["user_id"])) {
                $user_id = (string)$row["user_id"];
            } elseif ($square_customer_id !== "") {
                $byCustomerRaw = getDataByWhere("square_subscriptions", "*", ["square_customer_id" => $square_customer_id]);
                $byCustomer = $firstRow($byCustomerRaw);
                if (!empty($byCustomer["user_id"])) {
                    $user_id = (string)$byCustomer["user_id"];
                }
            }

            // ------------------------------------------------------------
            // Build data (insertTable/updateTable will ignore unknown columns)
            // ------------------------------------------------------------
            $data = [];
            $data["subscription_id"] = $subscription_id;

            // Always include when present (and required for inserts)
            if ($square_customer_id !== "")  $data["square_customer_id"] = $square_customer_id;

            if ($plan_variation_id !== "")   $data["plan_variation_id"]  = $plan_variation_id;
            if ($status !== "")              $data["status"]             = $status;
            if ($start_date !== "")          $data["start_date"]         = $start_date;
            if ($charged_through_date !== "") $data["charged_through_date"] = $charged_through_date;

            if ($user_id !== "")             $data["user_id"]            = $user_id;

            if ($course_id_posted !== "")    $data["course_id"]          = $course_id_posted;
            if ($amount_posted !== "")       $data["amount"]             = $amount_posted;
            if ($currency_posted !== "")     $data["currency"]           = $currency_posted;
            if ($payment_mode_posted !== "") $data["payment_mode"]       = $payment_mode_posted;

            if ($cancel_effective_date !== "") $data["cancel_effective_date"] = $cancel_effective_date;
            if ($cancel_reason !== "")         $data["cancel_subs_reason"]    = $cancel_reason;

            // Status-driven fields
            if ($status_upper === "CANCELED") {
                $data["cancel_status"] = "CANCELED";
                $data["canceled_at"]   = $now;
                // do NOT set next_billing_date here (we'll force NULL below)
            } else {
                if ($next_billing_date !== "") $data["next_billing_date"] = $next_billing_date;

                // If you want "scheduled cancel", use a different value; keeping your original behavior:
                if ($cancel_effective_date !== "") $data["cancel_status"] = "CANCELED";
            }

            $data["updated_at"] = $now;
            if (!$exists) $data["created_at"] = $now;

            // ------------------------------------------------------------
            // Upsert with explicit DB error checks
            // ------------------------------------------------------------
            global $configs;

            if ($exists) {
                updateTable("square_subscriptions", $data, ["subscription_id" => $subscription_id]);

                $errno = mysqli_errno($configs['con']);
                if ($errno) {
                    $respond(500, false, "DB update failed", [
                        "db_errno" => $errno,
                        "db_error" => mysqli_error($configs['con']),
                        "subscription_id" => $subscription_id,
                        "exists" => true,
                        "data_attempted" => $data
                    ]);
                }
            } else {
                $newId = insertTable("square_subscriptions", $data, [], $debug_sql ? 1 : 0);

                $errno = mysqli_errno($configs['con']);
                if ($errno || !$newId) {
                    $respond(500, false, "DB insert failed", [
                        "db_errno" => $errno,
                        "db_error" => mysqli_error($configs['con']),
                        "subscription_id" => $subscription_id,
                        "exists" => false,
                        "insert_id" => (int)$newId,
                        "data_attempted" => $data
                    ]);
                }
            }

            // ------------------------------------------------------------
            // Force next_billing_date = NULL when fully canceled (SQL NULL)
            // ------------------------------------------------------------
            if ($status_upper === "CANCELED") {
                $sid = mysqli_real_escape_string($configs['con'], $subscription_id);
                $sql = "UPDATE " . $configs['prev'] . "square_subscriptions
                        SET next_billing_date = NULL
                        WHERE subscription_id = \"$sid\"";

                $rr = mysqli_query($configs['con'], $sql);
                if (!$rr) {
                    $respond(500, false, "Failed to set next_billing_date NULL", [
                        "db_error" => mysqli_error($configs['con']),
                        "subscription_id" => $subscription_id
                    ]);
                }
            }

            // ------------------------------------------------------------
            // Verify row exists now
            // ------------------------------------------------------------
            $verifyRaw = getDataByWhere("square_subscriptions", "*", ["subscription_id" => $subscription_id]);
            $verify    = $firstRow($verifyRaw);

            if (empty($verify["subscription_id"])) {
                $respond(500, false, "Upsert reported OK but row not found after write", [
                    "subscription_id" => $subscription_id
                ]);
            }

            // ------------------------------------------------------------
            // Return
            // ------------------------------------------------------------
            $respond(200, true, "subscription-updated processed", [
                "subscription_id"       => $subscription_id,
                "user_id"               => ($user_id !== "" ? $user_id : ""),
                "square_customer_id"    => ($square_customer_id !== "" ? $square_customer_id : null),
                "course_id"             => ($course_id_posted !== "" ? $course_id_posted : null),
                "amount"                => ($amount_posted !== "" ? $amount_posted : null),
                "currency"              => ($currency_posted !== "" ? $currency_posted : null),
                "status"                => $status,
                "start_date"            => ($start_date !== "" ? $start_date : null),
                "charged_through_date"  => ($charged_through_date !== "" ? $charged_through_date : null),
                "next_billing_date"     => ($status_upper === "CANCELED"
                    ? null
                    : ($next_billing_date !== "" ? $next_billing_date : null)),
                "cancel_status"         => (isset($data["cancel_status"]) ? $data["cancel_status"] : null),
                "cancel_effective_date" => ($cancel_effective_date !== "" ? $cancel_effective_date : null),
                "cancel_reason"         => ($cancel_reason !== "" ? $cancel_reason : null),
                "debug"                 => [
                    "exists_before" => $exists,
                    "wrote_user_id" => ($user_id !== ""),
                    "wrote_square_customer_id" => ($square_customer_id !== ""),
                ],
            ]);

            break;

        case "subscribe-again":

            $respond = function (int $status, bool $ok, string $msg, array $extra = []) {
                http_response_code($status);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(array_merge([
                    "ok"      => $ok,
                    "message" => $msg
                ], $extra));
                exit;
            };

            // This API file routes by $_POST['action'], so requests SHOULD be form-urlencoded or form-data.
            // Still, we keep JSON fallback so Next.js/PowerShell can call it if router is later upgraded.
            $raw = file_get_contents("php://input");
            $j = json_decode($raw, true);
            if (!is_array($j)) $j = [];

            // Prefer $_POST (because switch() is based on $_POST['action'])
            $auth_token = trim((string)($_POST["auth_token"] ?? ""));
            $user_id    = trim((string)($_POST["user_id"]    ?? ""));
            $return_url = trim((string)($_POST["return_url"] ?? ""));

            // JSON fallback (only if POST fields are empty)
            if ($auth_token === "") $auth_token = trim((string)($j["auth_token"] ?? ""));
            if ($user_id === "")    $user_id    = trim((string)($j["user_id"] ?? ""));
            if ($return_url === "") $return_url = trim((string)($j["return_url"] ?? ""));

            if ($auth_token === "" || $user_id === "") {
                $respond(400, false, "auth_token and user_id are required.");
            }

            $user_id_int = (int)$user_id;

            // 1) Validate auth_token belongs to user_id (login table)
            // IMPORTANT: keep $print = 0 in production
            $loginRow = getDataByWhere("login", "*", [
                "user_id"    => $user_id_int,
                "auth_token" => $auth_token
            ], 0);

            if (!is_array($loginRow) || empty($loginRow["login_id"])) {
                $respond(401, false, "Invalid token or user.");
            }

            // 2) Load user profile
            $u = getDataByWhere("user", "*", ["user_id" => $user_id_int], 0);
            if (!is_array($u) || empty($u["user_id"])) {
                $respond(401, false, "User not found.");
            }

            // 3) Load subscription (course_id + package_id)
            $sub = getDataByWhere("subscription", "*", ["user_id" => $user_id_int], 0);


            $course_id  = trim((string)($sub["course_id"] ?? ""));

            if ($course_id === "27") {
                $course_id = "40";
            }


            if (!is_array($sub) || empty($course_id)) {
                $respond(400, false, "Course not found for this user subscription.");
            }

            if ($u["activation_status"] === "N") {
                $package_row = getDataByWhere("asvab_tutor_package_sessions", "package_id", ["course_id" => $course_id, "payment_mode" => "subscription"], 0);
                $package_id = (int)($package_row["package_id"] ?? 0);
                $web_user = true;
            } else {
                if (empty($sub["package_id"])) {
                    $respond(400, false, "Package not found for this user subscription.");
                }
                $package_id = (int)($sub["package_id"] ?? 0);
                $web_user = false;
            }

            // 4) Course info
            $course_name = "";
            $c = getDataByWhere("course", "*", ["course_id" => $course_id], 0);
            if (is_array($c)) {
                $course_name = (string)($c["course_name"] ?? "");
            }

            // 5) Multi-row package sessions
            $rows = getAllRowsByWhereWithPrefix(
                "asvab_tutor_package_sessions",
                "*",
                ["package_id" => $package_id],
                "ord ASC",
                "",
                0
            );

            if (!is_array($rows)) $rows = [];


            $outPkgs = [];
            foreach ($rows as $r) {
                $price = (float)($r["price"] ?? 0);

                $outPkgs[] = [
                    "session_id"       => (int)($r["session_id"] ?? 0),
                    "package_id"       => (int)($r["package_id"] ?? 0),
                    "production_plan_variation_id" => trim((string)($r["production_plan_variation_id"] ?? "")),
                    "sandbox_plan_variation_id" => trim((string)($r["sandbox_plan_variation_id"] ?? "")),
                    "title"            => (string)($r["title"] ?? ""),
                    "price"            => (string)($r["price"] ?? "0.00"),
                    "amount_cents"     => (int)round($price * 100),
                    "feature"          => (string)($r["feature"] ?? ""),
                    "is_most_popular"  => (int)($r["is_most_popular"] ?? 0) === 1,
                    "ord"              => (int)($r["ord"] ?? 0),
                ];
            }


            if ($return_url === "") {
                $return_url = "https://staging-staging-test-prep.asvab-tutoring.com/practice-test/exam/" . $course_id . "-" . (string)$course_name;
            }

            $respond(200, true, "OK", [
                "user" => [
                    "user_id"    => (int)($u["user_id"] ?? 0),
                    "first_name" => (string)($u["first_name"] ?? ""),
                    "last_name"  => (string)($u["last_name"] ?? ""),
                    "email"      => (string)($u["email"] ?? ""),
                    "web_user"   => $web_user,
                ],
                "course" => [
                    "course_id"   => (string)$course_id,
                    "course_name" => (string)$course_name,
                ],
                "package" => [
                    "package_id" => (int)$package_id
                ],
                "packages"   => $outPkgs,
                "return_url" => (string)$return_url,
            ]);
        break;

        case "buy-more-sessions":
            $respond = function(int $status, bool $ok, string $msg, array $extra = []) {
                http_response_code($status);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(array_merge([
                    "ok"      => $ok,
                    "message" => $msg
                ], $extra));
                exit;
            };

            // Read JSON body (if caller sends application/json)
            $raw = file_get_contents("php://input");
            $j = json_decode($raw, true);
            if (!is_array($j)) $j = [];

            // Accept from POST OR JSON
            $user_id    = trim((string)($_POST["user_id"]    ?? ($j["user_id"]    ?? "")));
            $auth_token = trim((string)($_POST["auth_token"] ?? ($j["auth_token"] ?? "")));

            if ($user_id === "" || $auth_token === "") {
                $respond(400, false, "user_id and auth_token are required.");
            }

            $user_id_int = (int)$user_id;

            // Check in "user" table
            $u = getDataByWhere("user", "*", ["user_id" => $user_id_int], 0);
            if (!is_array($u) || empty($u["user_id"])) {
                $respond(404, false, "User not found.");
            }

            // Check in "login" table
            $loginRow = getDataByWhere("login", "*", [
                "user_id"    => $user_id_int,
                "auth_token" => $auth_token
            ], 0);

            if (!is_array($loginRow) || empty($loginRow["login_id"])) {
                $respond(401, false, "Invalid auth token or user ID.");
            }

            // Search in "subscription" table
            $sub = getDataByWhere("subscription", "*", ["user_id" => $user_id_int], 0);
            $session_id = "";
            if (is_array($sub) && !empty($sub["session_id"])) {
                $session_id = $sub["session_id"];
            }

            // Fetch all rows with payment_mode="one_time"
            $rows = getAllRowsByWhereWithPrefix(
                "asvab_tutor_package_sessions",
                "*",
                ["payment_mode" => "one_time"],
                "ord ASC",
                "",
                0
            );

            if (!is_array($rows)) $rows = [];

            // Group by course_name
            $excluded_package_ids = [15, 19, 24, 29, 31];
            $package_details = [];
            foreach ($rows as $r) {
                // Skip blacklisted package IDs
                if (in_array((int)($r["package_id"] ?? 0), $excluded_package_ids, true)) {
                    continue;
                }

                // Skip rows with null or empty course_name
                $course_name_key = trim((string)($r["course_name"] ?? ""));
                if ($course_name_key === "") {
                    continue;
                }

                if (!isset($package_details[$course_name_key])) {
                    $package_details[$course_name_key] = [];
                }

                if ($session_id === "" || is_null($session_id) || empty($session_id)) {
                    $is_prev_session = $r["course_id"]===$sub["course_id"] && $r["package_id"]===$sub["package_id"];
                }
                else {
                    $is_prev_session = (string)($r["session_id"] ?? "") === $session_id;
                }

                $package_details[$course_name_key][] = [
                    "package_id"      => (string)($r["package_id"] ?? ""),
                    "session_id"      => (string)($r["session_id"] ?? ""),
                    "course_id"       => (string)($r["course_id"] ?? ""),
                    "course_name"     => (string)($r["course_name"] ?? ""),
                    "title"           => (string)($r["title"] ?? ""),
                    "price"           => (string)($r["price"] ?? ""),
                    "feature"         => (string)($r["feature"] ?? ""),
                    "is_most_popular" => (string)($r["is_most_popular"] ?? "0"),
                    "prev_session"  => $is_prev_session,
                ];
            }

            // Response
            $respond(200, true, "OK", [
                "user" => [
                    "first_name" => (string)($u["first_name"] ?? ""),
                    "last_name"  => (string)($u["last_name"] ?? ""),
                    "email"      => (string)($u["email"] ?? ""),
                ],
                "package_details" => $package_details
            ]);
        break;

        case "auth-token-lookup":

            // Local responder (prevents: Function name must be a string)
            $respondFn = function (int $status, bool $ok, string $msg, array $extra = []) {
                http_response_code($status);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(array_merge([
                    "ok"      => $ok,
                    "message" => $msg
                ], $extra));
                exit;
            };

            // Read JSON body (if caller sends application/json)
            $raw = file_get_contents("php://input");
            $j = json_decode($raw, true);
            if (!is_array($j)) $j = [];

            // Accept auth_token from x-www-form-urlencoded / form-data OR JSON
            $auth_token = trim((string)($_POST["auth_token"] ?? ($j["auth_token"] ?? "")));
            if ($auth_token === "") {
                $respondFn(400, false, "auth_token is required.");
            }

            // getDataByWhere may return [0 => row] or a single associative row
            $loginRow = getDataByWhere("login", "*", ["auth_token" => $auth_token], 0);
            if (is_array($loginRow) && isset($loginRow[0]) && is_array($loginRow[0])) {
                $loginRow = $loginRow[0];
            }

            if (!is_array($loginRow) || empty($loginRow["user_id"])) {
                $respondFn(401, false, "Invalid auth token.");
            }

            $user_id = (int)$loginRow["user_id"];

            $u = getDataByWhere("user", "*", ["user_id" => $user_id], 0);
            if (is_array($u) && isset($u[0]) && is_array($u[0])) {
                $u = $u[0];
            }

            if (!is_array($u) || empty($u["user_id"])) {
                $respondFn(401, false, "User not found for this token.");
            }

            // Try square_subscriptions first, fallback subscription.customer_id
            $square_customer_id = "";

            $sq = getDataByWhere("square_subscriptions", "*", ["user_id" => $user_id], 0);
            if (is_array($sq) && isset($sq[0]) && is_array($sq[0])) {
                $sq = $sq[0];
            }

            if (is_array($sq) && !empty($sq["square_customer_id"])) {
                $square_customer_id = trim((string)$sq["square_customer_id"]);
            } else {
                $sub = getDataByWhere("subscription", "*", ["user_id" => $user_id], 0);
                if (is_array($sub) && isset($sub[0]) && is_array($sub[0])) {
                    $sub = $sub[0];
                }

                if (is_array($sub) && !empty($sub["customer_id"])) {
                    $square_customer_id = trim((string)$sub["customer_id"]);
                }
            }

            $respondFn(200, true, "OK", [
                "user" => [
                    "user_id"            => (int)($u["user_id"] ?? 0),
                    "first_name"         => (string)($u["first_name"] ?? ""),
                    "last_name"          => (string)($u["last_name"] ?? ""),
                    "email"              => (string)($u["email"] ?? ""),
                    "square_customer_id" => (string)$square_customer_id
                ]
            ]);

            break;

        case "user-registration-mobile-payment":

            $course_id = 0;

            if ($_POST['course_id'] == 49) {
                $course_id = 24;
            } else if ($_POST['course_id'] == 40) {
                $course_id = 27;
            } else if ($_POST['course_id'] == 50) {
                $course_id = 25;
            } else if ($_POST['course_id'] == 51) {
                $course_id = 26;
            } else if ($_POST['course_id'] == 52) {
                $course_id = 21;
            }

            $amount = getFldValueById("asvab_tutor_package_sessions", "price", array("session_id" => $_POST['session_id']));

            $test_name = getFldValueById("course", "course_name", array("course_id" => $course_id));
            $user_name = $_POST['first_name'] . " " . $_POST['last_name'];

            $package_id = 62;
            $package = getDataByWhere("package", "*", ["package_id" => $package_id]);

            $month = 4;
            $days = $month * 30;
            $timezone = new DateTimeZone('America/New_York');
            $date = new DateTime('now', $timezone);
            $date->modify('+' . $days . ' days');
            $expire_date = $date->format('Y-m-d');

            $timezone = new DateTimeZone('America/New_York');
            $date = new DateTime('now', $timezone);
            $formatted_date = $date->format('Y-m-d');
            $formatted_date_time = $date->format('Y-m-d H:i:s');

            $user_id = insertTable("user", array("email" => $_POST['email'], "password" => md5($_POST['password']), "first_name" => $_POST['first_name'], "last_name" => $_POST['last_name'], "mobile" => $_POST['mobile'], "user_type" => "Student", "reg_date" => $formatted_date, "activation_status" => "N", "reg_at" => "asvab-web", "fp" => 0, "pt" => 0));

            $payment_id = insertTable("asvab_payments_mobile", array("mobile" => $_POST['mobile'], "ref_code" => $_POST['ref_code'], "amount" => $_POST['amount'], "currency" => $_POST['currency'], "created_at" => $_POST['created_at'], "user_id" => $user_id, "test_name" => $test_name, "user_name" => $user_name, "user_created_at" => date("Y-m-d")));

            insertTable("login", array("user_id" => $user_id, "last_login" => $formatted_date_time));

            $sub_id = insertTable("subscription", array("course_id" => $course_id, "user_id" => $user_id, "subscription_date" => $formatted_date, "expire_date" => $expire_date, "package_id" => $package_id, "amount" => $_POST['amount'], "payment_mode" => $_POST['payment_source_type'], "currency" => $_POST['currency']));

            // -------------------------
            // Send STYLED register email
            // -------------------------
            $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Register"));
            if (!empty($mail) && isset($mail['template'], $mail['subject'])) {
                $timestamp = date("F j, Y \a\\t g:i A");
                $body = $mail['template'];
                
                // Replace placeholders
                $body = str_replace("{name}", $_POST['first_name'] . " " . $_POST['last_name'], $body);
                $body = str_replace("{first_name}", $_POST['first_name'], $body);
                $body = str_replace("{email}", $_POST['email'], $body);
                $body = str_replace("{timestamp}", $timestamp, $body);
                
                // TRIPLE DECODE
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                // CLEAN UP
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

            if ($sub_id) {
                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "User registered successfully.",
                    "user_id"    => $user_id
                ];
            } else {
                $response = [
                    "status"     => 500,
                    "isSuccess"  => false,
                    "message"    => "Failed to register user. Please try again later."
                ];
            }

            break;

        case "get-reccurent-payment-list":

            $result = getQuery("asvab_recurrent_payments", "*", array());
            $payment_list = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $payment_list[] = $row;
                }

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Payment records fetched successfully.",
                    "data"       => $payment_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No payment records found.",
                    "data"       => []
                ];
            }

            break;

        case "user-login":

            $email = trim($_POST['email']);
            $password = md5(trim($_POST['password']));
            $user = getDataByWhere("user", "*", array("email" => $email, "password" => $password));
            if ($user) {
                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "User logged in successfully.",
                    "url"        => "https://staging-test-prep.asvab-tutoring.com/practice-test/login/" . $email . "/" . $password,
                    "data"       => $user
                ];
            } else {
                $response = [
                    "status"     => 401,
                    "isSuccess"  => false,
                    "message"    => "Invalid email or password."
                ];
            }

            break;

        case "get-course-list":

            $courses = getQuery("asvab_course", "*", array("status" => "Y"), "ord");

            $course_list = [];
            if ($courses && mysqli_num_rows($courses) > 0) {
                while ($course = mysqli_fetch_assoc($courses)) {

                    $course_list[] = [
                        'course_id' => $course['course_id'],
                        'name' => $course['name']
                    ];
                }

                $service_list =  array(
                    "1-on-1 Tutoring" => 0,
                    "Group Tutoring"  => 1,
                    "Self Study" => 2
                );

                $response = [
                    "status"     => 200,
                    "isSuccess"  => true,
                    "message"    => "Course List fetched successfully.",
                    "data"       => $course_list,
                    "services"   => $service_list
                ];
            } else {
                $response = [
                    "status"     => 404,
                    "isSuccess"  => false,
                    "message"    => "No Course found."
                ];
            }

            break;

        case "get-package-session-list":

            if (empty($_POST['course_id'])) {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Course ID is required."
                ];
            } else {

                $package_id = getFldValueById("asvab_tutor_package", "package_id", array("course_id" => $_POST['course_id'], "is_group_tutoring" => $_POST['service_type']));

                $sessions = getQuery("asvab_tutor_package_sessions", "*", array("package_id" => $package_id), "ord");

                $session_list = [];
                if ($sessions && mysqli_num_rows($sessions) > 0) {
                    while ($session = mysqli_fetch_assoc($sessions)) {

                        $session_list[] = [
                            'session_id' => $session['session_id'],
                            'name' => $session['title'],
                            'price' => $session['price'],
                            'is_most_popular' => $session['is_most_popular']
                        ];
                    }

                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "message"    => "Session List fetched successfully.",
                        "data"       => $session_list
                    ];
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "No Session found for the given Course ID."
                    ];
                }
            }

            break;

        case "get-plan-variation-id":

            $session_id = isset($_POST['session_id']) ? (int) $_POST['session_id'] : 0;

            if (empty($session_id)) {
                $response = [
                    "status"     => 400,
                    "isSuccess"  => false,
                    "message"    => "Session ID is required."
                ];
            } else {

                $package = getQuery("asvab_tutor_package_sessions", "*", array("session_id" => $session_id));

                if (!empty($package)) {
                    $data = [];

                    foreach ($package as $c) {
                        $data[] = [
                            "session_id"                   => $c['session_id'],
                            "course_id"                    => $c['course_id'],
                            "course_name"                  => $c['course_name'],
                            "package_id"                   => $c['package_id'],
                            "package_duration"             => $c['package_duration'],
                            "payment_mode"                 => $c['payment_mode'],
                            "title"                        => $c['title'],
                            "price"                        => $c['price'],
                            "currency"                     => $c['currency'],
                            "is_most_popular"              => $c['is_most_popular'],
                            "production_plan_variation_id" => trim($c['production_plan_variation_id']),
                            "sandbox_plan_variation_id"    => trim($c['sandbox_plan_variation_id']),
                            "is_active"                    => $c['is_active'],
                            "updated_at"                   => $c['updated_at'],
                            "ord"                          => $c['ord'],
                            "asvab"                        => $c['asvab'],
                            "ocs"                          => $c['ocs'],
                            "test_prep"                    => $c['test_prep'],
                            "feature"                      => $c['feature']
                        ];
                    }

                    $response = [
                        "status"     => 200,
                        "isSuccess"  => true,
                        "data"       => $data
                    ];
                } else {
                    $response = [
                        "status"     => 404,
                        "isSuccess"  => false,
                        "message"    => "No Plan Variation ID found for the given Session ID."
                    ];
                }
            }

            break;
    }

    echo json_encode($response);

    die();
}