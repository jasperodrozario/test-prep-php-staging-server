<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['action']) {
    $_GET['action'] = "pricing-list";
}

$data['action'] = $_GET['action'];

switch ($_GET['action']) {
    case "pricing-list":

        $data['page_subtitle'] = "Pricing Content List";

        $data['breadcrumb'] = array(
            "Pricing Content List" => ""
        );

        if(isset($_GET['del_pricing_id'])) {
            deleteData("asvab_pricing_page", array("pricing_id" => $_GET['del_pricing_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $result = getQuery("asvab_pricing_page", "*", array());
        $pricing_list = array();
        foreach ($result as $row) {
            $pricing_list[] = $row;
        }
        $data['db_value'] = $pricing_list;

        $data["btn"] = "<a href='asvab-pricing-content?action=add-pricing-content' class='btn btn-success'>Add Pricing Content</a>";

        break;

    case "add-pricing-content":

        $data['page_subtitle'] = "Add / Edit Pricing Content";

        $data['breadcrumb'] = array(
            "Pricing Content List" => "asvab-pricing-content?action=pricing-list",
            "Add / Edit Pricing Content" => ""
        );

        if (!$_GET['pricing_id']) {
            $_GET['pricing_id'] = $_POST['pricing_id'];
        }

        $data['db_value'] = getDataByWhere("asvab_pricing_page", "*", array("pricing_id" => $_GET['pricing_id']));

        if (isset($_POST['submit'])) {

            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $closing_content = isset($_POST['closing_content']) ? trim($_POST['closing_content']) : '';

            $insert_data = [
                "title"       => $title,
                "description" => $description,
                "closing_content" => $closing_content,
                "tutoring_type" => $_POST['tutoring_type']
            ];

            if($_GET['pricing_id']) {

                $update = updateTable("asvab_pricing_page", $insert_data, array("pricing_id" => $_GET['pricing_id']));

                if ($update) {
                    $data['msg'] = $configs['success_msg'];
                    $data['db_value'] = getDataByWhere("asvab_pricing_page", "*", array("pricing_id" => $_GET['pricing_id']));
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['db_value'] = $_POST;
                }
            }
            else{
                // Call your custom insert function
                $insert_id = insertTable("asvab_pricing_page", $insert_data);

                if ($insert_id) {
                    $data['msg'] = $configs['success_msg'];
                    $data['db_value'] = getDataByWhere("asvab_pricing_page", "*", array("pricing_id" => $insert_id));
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['db_value'] = $_POST;
                }
            }
        }

        $data["btn"] = "<a href='asvab-pricing-content?action=pricing-list' class='btn btn-success'>Pricing Content List</a>";

        break;
}
echo template($data);
