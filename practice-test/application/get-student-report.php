<?php
include("../configs/autoload.php");
chklogin(str_replace("/practice-test/","",$_SERVER['REQUEST_URI']));

if (isset($_POST['email'])) {
    $studentEmail = $_POST['email'];
    $student_id = getFldValueById("user","user_id",array("email"=>$_POST['email'], "reg_at like "=>"'".$_POST['platform']."%'"));
    
    if($student_id){
        header("location:tutor-view-report/".$student_id."/progress-report");
    }
    else{
        $data['response'] = "Student Doesn't Exist";
    }
}

echo template($data);
?>