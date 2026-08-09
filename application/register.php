<?php
include("../configs/autoload.php");
include("../smtpmail.php");

$vpath = isset($configs['vpath']) ? $configs['vpath'] : '/';
$data['vpath'] = $vpath;

$squareAppId = isset($configs['square_application_id']) ? $configs['square_application_id'] : '';
$data['squareAppId'] = $squareAppId;

$squareLocationId = isset($configs['square_location_id']) ? $configs['square_location_id'] : '';
$data['squareLocationId'] = $squareLocationId;

$squareEnvironment = isset($configs['square_environment']) ? $configs['square_environment'] : 'Sandbox';
$data['squareEnvironment'] = $squareEnvironment;

$squareSdkUrl = isset($configs['square_sdk_url']) ? $configs['square_sdk_url'] : '';
$data['squareSdkUrl'] = $squareSdkUrl;

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('subscribe_h')) {
    function subscribe_h($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('subscribe_post')) {
    function subscribe_post($key, $default = '')
    {
        return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
    }
}

if (!function_exists('subscribe_find_session')) {
    function subscribe_find_session($sessions, $course_id, $session_id)
    {
        if (!is_array($sessions)) {
            return null;
        }

        foreach ($sessions as $session) {
            if (
                isset($session['course_id'], $session['session_id'], $session['payment_mode']) &&
                (string)$session['course_id'] === (string)$course_id &&
                (string)$session['session_id'] === (string)$session_id &&
                (string)$session['payment_mode'] === 'subscription'
            ) {
                return $session;
            }
        }

        return null;
    }
}

if (!function_exists('subscribe_call_next_api')) {
    function subscribe_call_next_api($url, $payload)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // use it for sandbox.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // use it for production.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // use it for production.

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($curlError) {
            return array(
                'success' => false,
                'message' => 'cURL Error: ' . $curlError,
                'raw' => $response,
                'http_code' => $httpCode
            );
        }

        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            return array(
                'success' => false,
                'message' => 'Invalid response from Next.js API.',
                'raw' => $response,
                'http_code' => $httpCode
            );
        }

        if ($httpCode < 200 || $httpCode >= 300 || empty($decoded['success'])) {
            return array(
                'success' => false,
                'message' => isset($decoded['message']) ? $decoded['message'] : 'Subscription request failed.',
                'raw' => $decoded,
                'http_code' => $httpCode
            );
        }

        return array(
            'success' => true,
            'data' => $decoded,
            'http_code' => $httpCode
        );
    }
}

/*
|--------------------------------------------------------------------------
| Load subscription sessions
|--------------------------------------------------------------------------
*/
$subscriptionSessions = array();

$subscriptionSessionsResult = getQuery(
    "asvab_tutor_package_sessions",
    "*",
    array("payment_mode" => "subscription", "test_prep" => 1),
    "course_name, course_id, ord"
);

if ($subscriptionSessionsResult) {
    while ($row = mysqli_fetch_assoc($subscriptionSessionsResult)) {
        $subscriptionSessions[] = $row;
    }
}

// if ($subscriptionSessionsResult) {
//     while ($row = mysqli_fetch_assoc($subscriptionSessionsResult)) {
//         $subscriptionSessions[] = $row;
//     }
    
//     // Debug the entire array at once
//     echo "<pre>";
//     print_r($subscriptionSessions);
//     echo "</pre>";
//     exit; // Stop execution here to inspect the data
// }


$courses = array();
$seen_ids = array();

if (!function_exists('get_course_priority')) {
    function get_course_priority($course_id)
    {
        $mapping = array(
            27 => 1, 40 => 1, // ASVAB
            28 => 2,          // ASVAB Tutoring
            38 => 3,          // ASVAB Prep
            39 => 4,          // ASVAB GT
            24 => 5,          // AFOQT
            25 => 6,          // SIFT
            26 => 7,          // ASTB
            21 => 8,          // OAR
            20 => 9,          // HESI A2
            33 => 10,         // ATI TEAS
            34 => 11,         // CNA
            51 => 12,         // State Test Preparation - Grade 3
            50 => 13,         // State Test Preparation - Grade 4
            49 => 14,         // State Test Preparation - Grade 5
            48 => 15,         // State Test Preparation - Grade 6
            47 => 16,         // State Test Preparation - Grade 7
            46 => 17,         // State Test Preparation - Grade 8
            30 => 18,         // SHSAT
            37 => 19          // GED
        );
        return isset($mapping[$course_id]) ? $mapping[$course_id] : 99;
    }
}

foreach ($subscriptionSessions as $c) {
    $loop_course_id = isset($c['course_id']) ? $c['course_id'] : '';

    if ($loop_course_id !== '' && !in_array($loop_course_id, $seen_ids)) {
        $courses[] = array(
            'courseId'    => $loop_course_id,
            'course_name' => isset($c['course_name']) ? $c['course_name'] : '',
            'ord'         => get_course_priority($loop_course_id)
        );
        $seen_ids[] = $loop_course_id;
    }

}

usort($courses, function($a, $b) {
    return $a['ord'] - $b['ord'];
});

$data['testprep_course'] = $courses;

/*
|--------------------------------------------------------------------------
| Defaults for template
|--------------------------------------------------------------------------
*/
$error = array();
$success_message = '';
$api_response = array();



/*
|--------------------------------------------------------------------------
| POST validation + Next.js API call
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register']) && $_POST['register'] == 'register') {

    $course_id = subscribe_post('course_id');
    $subscription_package = subscribe_post('subscription_package');
    $first_name = subscribe_post('first_name');
    $last_name = subscribe_post('last_name');
    $email = subscribe_post('email');
    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
    $re_password = isset($_POST['re_password']) ? (string)$_POST['re_password'] : '';
    $mobile = subscribe_post('mobile');
    $referral_id = $_COOKIE['referral_id'] ?? '';
    $ref_type = $_COOKIE['ref_type'] ?? '';
    $square_source_id = subscribe_post('square_source_id');
    $postal_code = subscribe_post('postal_code');
    $country_code = subscribe_post('country_code');

    if ($course_id == '') {
        $error['course_id'] = "Please select a course.";
    }

    if ($subscription_package == '') {
        $error['subscription_package'] = "Please select a subscription.";
    }

    if ($first_name == '') {
        $error['first_name'] = "First name is required.";
    }

    if ($last_name == '') {
        $error['last_name'] = "Last name is required.";
    }

    if ($email == '') {
        $error['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Please enter a valid email address.";
    } else {
        $exist_email = getTotalRow("user", array("email" => $email));
        if ($exist_email) {
            $error['email'] = "This email already exists.";
        }
    }

    if ($password == '') {
        $error['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $error['password'] = "Password must be at least 6 characters.";
    }

    if ($re_password == '') {
        $error['re_password'] = "Please repeat your password.";
    } elseif ($password != $re_password) {
        $error['re_password'] = "Passwords do not match.";
    }

    $mobile = preg_replace('/[^\d+]/', '', $mobile);

    // Ensure only one + and only at the beginning
    if (substr_count($mobile, '+') > 1 || (strpos($mobile, '+') > 0)) {
        $mobile = str_replace('+', '', $mobile);
    }

    // Final validation
    if ($mobile === '') {
        $error['mobile'] = "Mobile number is required.";
    } elseif (!preg_match('/^\+?[1-9]\d{9,14}$/', $mobile)) {
        $error['mobile'] = "Please enter a valid mobile number (10-15 digits, optional country code).";
    }

    if (!isset($_POST['termsCheckbox'])) {
        $error['termsCheckbox'] = "Please accept the terms and conditions.";
    }

    if ($square_source_id == '') {
        $error['payment'] = "Payment method not verified.";
    }

    $selectedSession = null;

    if ($course_id != '' && $subscription_package != '') {
        $selectedSession = subscribe_find_session($subscriptionSessions, $course_id, $subscription_package);

        if (!$selectedSession) {
            $error['subscription_package'] = "Invalid subscription selected for this course.";
        }
    }

    if (empty($error) && $selectedSession) {
        $isProduction = (strtolower(trim($squareEnvironment)) === 'production');

        $planVariationId = $isProduction
            ? (isset($selectedSession['production_plan_variation_id']) ? trim((string)$selectedSession['production_plan_variation_id']) : '')
            : (isset($selectedSession['sandbox_plan_variation_id']) ? trim((string)$selectedSession['sandbox_plan_variation_id']) : '');

        if ($planVariationId == '') {
            $error['subscription_package'] = "Plan variation ID is missing for the selected subscription.";
        } else {
            $nextBaseUrl = isset($configs['nextjs_base_url']) ? rtrim($configs['nextjs_base_url'], '/') : '';
            $nextApiUrl = $nextBaseUrl . '/api/subscribe-ocs-prep';

            if ($nextBaseUrl == '') {
                $error['server'] = "Next.js API base URL is missing.";
            } else {
                $payload = array(
                    'sourceId' => $square_source_id,
                    'product' => isset($selectedSession['course_name']) ? (string)$selectedSession['course_name'] : '',
                    'cadence' => isset($selectedSession['package_duration']) ? (string)$selectedSession['package_duration'] : '',
                    'userEmail' => $email,
                    'userName' => trim($first_name . ' ' . $last_name),
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'password' => $password,
                    'mobile' => $mobile,
                    'amount' => isset($selectedSession['price']) ? (string)$selectedSession['price'] : '0',
                    'course_id' => isset($selectedSession['course_id']) ? (string)$selectedSession['course_id'] : '0',
                    'session_id' => isset($selectedSession['session_id']) ? (string)$selectedSession['session_id'] : '0',
                    'package_id' => isset($selectedSession['package_id']) ? (string)$selectedSession['package_id'] : '0',
                    'planVariationId' => $planVariationId,
                    'postal_code' => $postal_code,
                    'country_code' => $country_code != '' ? $country_code : 'US',
                    'referral_id' => $referral_id,
                    'ref_type' => $ref_type
                );

                $apiResult = subscribe_call_next_api($nextApiUrl, $payload);

                if (!empty($apiResult['success'])) {

                    // $_SESSION['subscription_success'] = true;

                    if($referral_id != '' && $ref_type != '') {
                        $ref_user_id = getFldValueById("user", "user_id", array("referral_code" => $referral_id));
                        updateTable("user", ["referer_id" => $ref_user_id, "ref_type" => $ref_type], ["email" => $email]);
                    }
                    
                    header("Location: https://staging-staging-test-prep.asvab-tutoring.com/thank-you");
                    exit;
                } else {
                    $error['api'] = isset($apiResult['message'])
                        ? $apiResult['message']
                        : 'Failed to create subscription.';
                    $api_response = $apiResult;
                }
            }
        }
    }
}


$data['error'] = $error;
$data['success_message'] = $success_message;
$data['api_response'] = $api_response;

echo template($data);
?>