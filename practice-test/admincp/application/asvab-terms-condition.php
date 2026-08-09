<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");


$data['page_subtitle'] = "Add / Edit - Terms & Conditions";

$data['breadcrumb'] = array(
    "Add / Edit Terms & Condition" => ""
);

$result = getQuery("asvab_terms_condition", "*", array());

foreach ($result as $row) {
    $data['db_value'] = $row;
}


if (isset($_POST['submit'])) {

    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    $insert_data = [
        "title"       => $title,
        "description" => $description
    ];

    deleteData("asvab_terms_condition", ""); // Clear existing entries

    // Call your custom insert function
    $insert_id = insertTable("asvab_terms_condition", $insert_data);

    if ($insert_id) {
        $data['msg'] = $configs['success_msg'];
        $data['db_value'] = getDataByWhere("asvab_terms_condition", "*", array("id" => $insert_id));
    } else {
        $data['msg'] = $configs['error_msg'];
        $data['db_value'] = $_POST;
    }
}

echo template($data);
