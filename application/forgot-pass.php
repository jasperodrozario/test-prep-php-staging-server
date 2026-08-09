 <?php 

include("../configs/autoload.php");
include("../smtpmail.php");

$data['meta_title']="Forgot Password";

if (isset($_POST['forgot_password_btn'])) {

    $email = trim($_POST['email']);

    if (empty($email)) {
        $_SESSION['error'] = "Email is required.";
    }

    $exists = getTotalRow("user", array("email" => $email));

    if ($exists) {

        $user = getDataByWhere("user", "*", array("email" => $email));

        $divider = date("Ymd");

        $password_link = $configs['vpath'] . "create-password/21" . $user['user_id'] . "45";

        // -------------------------
        // Send STYLED forgot password email
        // -------------------------
        $mail = getDataByWhere("mailsetup", "*", array("mail_type" => "Forgot Password"));

        if (!empty($mail) && isset($mail['template'], $mail['subject'])) {

            $timestamp = date("F j, Y \a\\t g:i A");
            $body = $mail['template'];

            // Replace placeholders
            $body = str_replace("{name}", $user['first_name']." ".$user['last_name'], $body);
            $body = str_replace("{first_name}", $user['first_name'], $body);
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

            smtpmailer($_POST['email'], $setting['support_mail'], ucwords($configs['dotcom']), "Reset Your Password",  $body);

            $data['message'] = "Please check your email to reset password.";
            $data['password_link'] = $password_link;

            // if ($_SERVER['HTTP_HOST'] != "localhost") {
            //     smtpmailer($_POST['email'], "info@asvab-tutoring.com", ucwords($configs['dotcom']), $subject, $styled_body);
            //     $data['message'] = "Please check your email to reset password.";
            //     $data['password_link'] = $password_link;
            // }
        }

        $_SESSION['success'] = "Please check your email to reset your password.";

    } else {
        $_SESSION['error'] = "Invalid Email Address.";
    }
}

echo template($data);

?>
