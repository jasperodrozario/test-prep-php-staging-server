<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$query = [];

if (!$_GET['action']) {
    $_GET['action'] = "faq-list";
}

switch ($_GET['action']) {
    case "faq-list":

        if (isset($_POST['search_btn']) && $_POST['search']) {
            $query = array_merge($query, array("[or]" => "(question like '%" . $_POST['search'] . "%' or id like '%" . $_POST['search'] . "%' or answers like '%" . $_POST['search'] . "%')"));
        }

        $data['total'] = getTotalRow("faq", $query);

        if ($_GET['del_faq_id']) {
            deleteData("faq", array("id" => $_GET['del_faq_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['page_subtitle'] = "Total FAQ [" . $data['total'] . "]";

        $data['breadcrumb'] = array("FAQ List" => "asvab-faq-list?action=faq-list");

        $data['page_title'] = "FAQ List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-faq-list?action=add-faq' class='btn btn-success'>Add New FAQ</a>";

        $data['db_list'] = getQuery("faq", "*", $query, "ord");

        break;


    case "add-faq":

        $data['page_subtitle'] = "Add / Edit FAQ";

        $data['breadcrumb'] = array(
            "FAQ List" => "asvab-faq-list?action=faq-list",
            "Add / Edit FAQ Information" => ""
        );

        $data['btn'] = "<a href='asvab-faq-list?action=faq-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['faq_id']) {
            $_GET['faq_id'] = $_POST['faq_id'] ?? null;
        }

        if ($_GET['faq_id']) {
            $data['db_value'] = getDataByWhere("faq", "*", array("id" => $_GET['faq_id']));
        }

        if (isset($_POST['submit'])) {

            $is_online = isset($_POST['is_online']) ? $_POST['is_online'] : 'N';

            // List of all fields from form
            $fields = [
                'question',
                'answers',
                'category',
                'ord'
            ];

            // Prepare data array only with non-empty values
            $insert_data = [];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $insert_data[$field] = $_POST[$field];
                }
            }

            // Add is_online explicitly
            $insert_data['is_online'] = $is_online;

            if (!empty($_GET['faq_id'])) {
                // Update existing FAQ
                $flag = updateTable("faq", $insert_data, ["id" => $_GET['faq_id']]);
                $data['db_value'] = getDataByWhere("faq", "*", ["id" => $_GET['faq_id']]);
                if ($flag){
                    $data['msg'] = str_replace("{table}", "FAQ ", $configs['success_msg']);
                } else {
                    $data['msg'] = $configs['error_msg'];
                }
            } else {
                // Insert new FAQ
                if (!empty($insert_data)) {
                    $flag = insertTable("faq", $insert_data);
                }

                if ($flag) {
                    $data['msg'] = str_replace("{table}", "FAQ ", $configs['success_msg']);
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
