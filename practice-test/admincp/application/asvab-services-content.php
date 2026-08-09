<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['action']) {
    $_GET['action'] = "service-list";
}

$data['action'] = $_GET['action'];

switch ($_GET['action']) {
    case "service-list":

        $data['page_subtitle'] = "Service Content List";

        $data['breadcrumb'] = array(
            "Service Content List" => ""
        );

        if (isset($_GET['del_service_id'])) {
            deleteData("asvab_services_content", array("service_id" => $_GET['del_service_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $result = getQuery("asvab_services_content", "*", array());
        $service_list = array();
        foreach ($result as $row) {
            $service_list[] = $row;
        }
        $data['db_value'] = $service_list;

        $data["btn"] = "<a href='asvab-services-content?action=add-service-content' class='btn btn-success'>Add Services Content</a>";

        break;

    case "add-service-content":

        $data['course_list'] = getQuery("asvab_course", "*", array("status" => "Y"), "ord");

        $data['page_subtitle'] = "Add / Edit Service Content";

        $data['breadcrumb'] = array(
            "Service Content List" => "asvab-services-content?action=service-list",
            "Add / Edit Service Content" => ""
        );

        if (!$_GET['service_id']) {
            $_GET['service_id'] = $_POST['service_id'];
        }

        $data['db_value'] = getDataByWhere("asvab_services_content", "*", array("service_id" => $_GET['service_id']));

        if (isset($_POST['submit'])) {

            $title        = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description  = isset($_POST['description']) ? trim($_POST['description']) : '';
            $is_online    = isset($_POST['is_online']) ? $_POST['is_online'] : 'N';
            $ord          = isset($_POST['ord']) ? intval($_POST['ord']) : 0;
            $course_name  = $_POST['course_name'];
            $service_type = $_POST['service_type'];

            $insert_data = [
                "title"        => $title,
                "description"  => $description,
                "is_online"    => $is_online,
                "course_name"  => $course_name,
                "service_type" => $service_type,
                "ord"          => $ord
            ];


            if ($_GET['service_id']) {

                $update = updateTable("asvab_services_content", $insert_data, array("service_id" => $_GET['service_id']));

                if ($update) {
                    $data['msg'] = $configs['success_msg'];
                    $data['db_value'] = getDataByWhere("asvab_services_content", "*", array("service_id" => $_GET['service_id']));
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['db_value'] = $_POST;
                }
            } else {
                // Call your custom insert function
                $insert_id = insertTable("asvab_services_content", $insert_data);

                if ($insert_id) {
                    $data['msg'] = $configs['success_msg'];
                    $data['db_value'] = getDataByWhere("asvab_services_content", "*", array("service_id" => $insert_id));
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['db_value'] = $_POST;
                }
            }
        }

        $data["btn"] = "<a href='asvab-services-content?action=service-list' class='btn btn-success'>Service Content List</a>";

        break;
}
echo template($data);
