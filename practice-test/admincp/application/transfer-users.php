<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Developer's Untilities";

$data['breadcrumb'] = array("Developer's Untilities" => "dev-utilities", "Transfer Users" => "");

$db_info = [
    'host' => '198.211.99.20',
    'user' => 'ctbsrarevb',
    'pass' => 'sN2gCBzJ4X',
    'name' => 'ctbsrarevb'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transfer_user'])) {
    $user_id = (int)$_POST['user_id'];

    $source_user = getDataByWhere("user", "*", array("user_id" => $user_id));
    $source_subsciption = getDataByWhere("subscription", "*", array("user_id" => $user_id));
    $source_login = getDataByWhere("login", "*", array("user_id" => $user_id));

    if ($source_user) {

        $insert_user = array("email" => $source_user['email'], "password"=>$source_user['password'], "first_name"=>$source_user['first_name'], "last_name"=>$source_user['last_name'], "mobile"=>$source_user['mobile'], "user_type"=>$source_user['user_type'], "reg_date"=>$source_user['reg_date'], "activation_status"=>$source_user['activation_status'], "reg_at"=>$source_user['reg_at']);

        $new_user_id = insert_table_anotherDB($db_info, "user", $insert_user);

        // Insert login data

        $insert_login = array("user_id" => $new_user_id, "last_login" => $source_login['last_login'], "auth_token"=>$source_login['auth_token']);
        
        insert_table_anotherDB($db_info, "login", $insert_login);
        
        $insert_subscription = array("user_id" => $new_user_id, "course_id" => $source_subsciption['course_id'], "subscription_date"=>$source_subsciption['subscription_date'], "expire_date"=>$source_subsciption['expire_date'], "amount"=>$source_subsciption['amount']);

        insert_table_anotherDB($db_info, "subscription", $insert_subscription);

        if ($new_user_id) {
            $data['message'] = "User with ID $user_id has been successfully transferred. New User ID: $new_user_id";
        } else {
            $data['message'] = "Failed to transfer user with ID $user_id.";
        }
    } else {
        $data['message'] = "User with ID $user_id not found in the source database.";
    }
}

echo template($data);
