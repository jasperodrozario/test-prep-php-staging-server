<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$query = [];

if (!$_GET['action']) {
    $_GET['action'] = "app-list";
}

switch ($_GET['action']) {
    case "app-list":

        $data['total'] = getTotalRow("asvab_our_app", array());

        if ($_GET['del_app_id']) {
            deleteData("asvab_our_app", array("app_id" => $_GET['del_app_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['page_subtitle'] = "Total App [" . $data['total'] . "]";

        $data['breadcrumb'] = array("App List" => "");

        $data['page_title'] = "App List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-mobile-apps?action=add-app' class='btn btn-success'>Add New App</a>";

        $data['db_list'] = getQuery("asvab_our_app", "*", array(), "ord");

        break;


    case "add-app":

        $data['page_subtitle'] = "Add / Edit App";

        $data['breadcrumb'] = array(
            "App List" => "asvab-mobile-apps?action=app-list",
            "Add / Edit Mobile App" => ""
        );

        $data['btn'] = "<a href='asvab-mobile-apps?action=app-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['app_id']) {
            $_GET['app_id'] = $_POST['app_id'] ?? null;
        }

        if ($_GET['app_id']) {
            $data['db_value'] = getDataByWhere("asvab_our_app", "*", array("app_id" => $_GET['app_id']));
        }

        if (isset($_POST['submit'])) {

            // List of all fields from form
            $fields = [
                'app_name',
                'app_description',
                'app_store_url',
                'google_play_url',
                'ord'
            ];

            // Prepare data array with only submitted values
            $insert_data = [];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $insert_data[$field] = trim($_POST[$field]);
                }
            }

            // Determine table name
            $table_name = "asvab_our_app";

            if (!empty($_GET['app_id'])) {
                // Update existing app
                updateTable($table_name, $insert_data, array("app_id" => $_GET['app_id']), array("logo_img"));
                $data['db_value'] = getDataByWhere($table_name, "*", ["app_id" => $_GET['app_id']]);
                $data['msg'] = str_replace("{table}", "Mobile App ", $configs['success_msg']);
            } else {
                // Insert new app
                if (!empty($insert_data)) {
                    $flag = insertTable($table_name, $insert_data, array("logo_img"));
                }

                if (!empty($flag)) {
                    $data['msg'] = str_replace("{table}", "Mobile App ", $configs['success_msg']);
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['db_value'] = $_POST;
                }
            }
        }

        break;
}

$data['action'] = $_GET['action'];

echo template($data);
