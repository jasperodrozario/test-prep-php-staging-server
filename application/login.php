<?php
include("../configs/autoload.php");

$data['meta_title']="Log In";

//$exists = getTotalRow("user", array("email" => $_POST['email'], "password" => $_POST['password']));
//
//if ($exists) {
//    $user_data = getDataByJoin(array("user as u", "login as l"), "u.*, l.last_login", array("u.email" => $_POST['email'], "u.password" => $_POST['password']), array("u.user_id" => "l.user_id"));
//
//    $course_id = getFldValueById("subscription", "course_id", array("user_id" => $user_data["user_id"]));
//    $expire_date = getFldValueById("subscription", "expire_date", array("user_id" => $user_data["user_id"]));
//
//    $_SESSION['user_id'] = $user_data["user_id"];
//    $_SESSION['status'] = $user_data["activation_status"];
//    $_SESSION['email'] = $user_data['email'];
//    $_SESSION['name'] = $user_data['first_name'] . " " . $user_data['last_name'];
//    $_SESSION['user_type'] = $user_data['user_type'];
//    $_SESSION['reg_date'] = $user_data['reg_date'];
//    $_SESSION['last_login'] = $user_data['last_login'];
//    $_SESSION['test_id'] = $course_id;
//    $_SESSION['checking'] = $course_id;
//
//    if ($course_id) {
//        $_SESSION['test_name'] = getFldValueById("course", "course_name", array("course_id" => $course_id));
//    }
//
//    if ($expire_date < date('Y-m-d')) {
//        updateTable("user", ["activation_status" => "N"], ["user_id" => $user_data["user_id"]]);
//        $_SESSION['status'] = "N";
//    }
//
//    // Update last login
//    updateTable("login", ["last_login" => date("Y-m-d H:i:s")], ["user_id" => $user_data['user_id']]);
//
//    $data['msg'] = "";
//
//    if ($_POST['referer']) {
//        header("location:" . $configs['vpath'] . $_POST['referer']);
//    } else {
//        header("location:" . $configs['vpath'] . "practice-test/my-dashboard");
//    }
//} else if(isset($_POST['login_btn'])){
//    $data['msg'] = "<p><img src='" . $configs['vpath'] . "img/off.png'> <font color=red>Error! Invalid Email and Password.</font></p>";
//}

echo template($data);
?>
