<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");


$data['page_subtitle'] = "Add / Edit - How It Works";

$data['breadcrumb'] = array(
    "Add / Edit How It Works Information" => ""
);

$result = getQuery("asvab_how_it_works", "*", array());

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

    deleteData("asvab_how_it_works", ""); // Clear existing entries

    // Call your custom insert function
    $insert_id = insertTable("asvab_how_it_works", $insert_data);

    if ($insert_id) {
        $data['msg'] = $configs['success_msg'];
        $data['db_value'] = getDataByWhere("asvab_how_it_works", "*", array("id" => $insert_id));
    } else {
        $data['msg'] = $configs['error_msg'];
        $data['db_value'] = $_POST;
    }
}

echo template($data);
