<?php
include("../common/access.php");
include("../common/autoload.php");

// Initialize message variable
$data['msg'] = '';

$course_id = 26; // Specify the course ID to filter users

$data['db_list']=getQueryByJoin(array("user as u","subscription as s"),"u.*,s.*",array("u.activation_status"=>"Y", "reg_at"=>"web", "s.course_id"=>$course_id), array("u.user_id"=>"s.user_id"),"u.user_id desc");

$data['total']=getTotalRowsByJoin(array("user as u","subscription as s"),array("u.activation_status"=>"Y", "reg_at"=>"web", "s.course_id"=>$course_id),array("u.user_id"=>"s.user_id"));

// Page configuration
$data['page_title'] = "User activation management";
$data['breadcrumb'] = array(
    "User activation management (". $data['total'] .")"  => ""
);
$cnt=0;
foreach ($data['db_list'] as $d) {
    // Additional processing can be done here if needed
    // if(!empty($d['reg_date'])) {
    //     // Do something for activated users
    //     $expire_date = date('Y-m-d', strtotime($d['reg_date'] . ' +24 months'));

    //     updateTable("subscription", ["expire_date" => $expire_date], ["user_id" => $d['user_id']]);

    //     $cnt++;
    // }

    // updateTable("user", ["fp" => 1, "pt"=>1], ["user_id" => $d['user_id']]);
}

$data['total_updated'] = $cnt;

// Include template
echo template($data);
?>