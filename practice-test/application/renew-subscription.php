<?php


header("Location: https://staging-test-prep.asvab-tutoring.com/subscription-profile");
exit;

include("../configs/autoload.php");

chklogin();

// ----------------------------------------------------
// ✅ Generate auth_token and store in login table (edu_login)
// NOTE: Always omit "edu_" in table name => use "login"
// ----------------------------------------------------

$uid = (int)($_SESSION['user_id'] ?? 0);
$data['user_id'] = $uid;
$data['test_names'] = getQuery("course", "*", "", "course_name");
$data['meta_title'] = "User Subscription";


echo template($data);
?>