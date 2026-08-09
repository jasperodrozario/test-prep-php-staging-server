<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$query = [];

if (!$_GET['action']) {
    $_GET['action'] = "employee-list";
}

switch ($_GET['action']) {
    case "employee-list":

        if (isset($_POST['search_btn']) && $_POST['search']) {
            $query = array_merge($query, array("[or]" => "(id like '%" . $_POST['search'] . "%' or name like '%" . $_POST['search'] . "%')"));
        }

        $data['total'] = getTotalRow("tutor", $query);

        if ($_GET['del_tutor_id']) {
            deleteData("tutor", array("id" => $_GET['del_tutor_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['page_subtitle'] = "Total Tutor [" . $data['total'] . "]";

        $data['breadcrumb'] = array("Tutor List" => "");

        $data['page_title'] = "Tutor List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-tutor-list?action=add-employee' class='btn btn-success'>Add New Tutor</a>";

        $data['db_list'] = getQuery("tutor", "*", $query, "tutor_order", $configs['limit']);

        break;


    case "add-employee":

        $data['page_subtitle'] = "Add New Tutor";

        $data['breadcrumb'] = array("Tutor Management" => "asvab-tutor-list?action=employee-list", "Add / Edit Tutor Information" => "");
        $data['btn'] = "<a href='asvab-tutor-list?action=employee-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['id']) {
            $_GET['id'] = $_POST['id'];
        }

        if ($_GET['id']) {
            $data['db_value'] = getDataByWhere("tutor", "*", array("id" => $_GET['id']));
            //            $data['msg'].="<script>setTimeout(\"location.href = 'tutor-list?action=employee-list';\",2500);</script>";
        }

        if (isset($_POST['submit'])) {
            if ($_GET['id']) {

                updateTable("tutor", array("name" => $_POST["name"], "designation" => $_POST["designation"], "about" => $_POST["about"], "education" => $_POST["education"], "specialization" => $_POST["specialization"], "language" => $_POST["language"], "tutor_order" => $_POST["tutor_order"]), array("id" => $_GET['id']), array("photo"));

                $data['db_value'] = getDataByWhere("tutor", "*", array("id" => $_GET['id']));
                $data['msg'] = str_replace("{table}", "Tutor ", $configs['success_msg']);
            } else {

                // List of all fields
                $fields = ['about', 'education', 'specialization', 'language', 'tutor_order', 'name', 'designation'];

                // Prepare data array only with non-empty values
                $insert_data = [];
                foreach ($fields as $field) {
                    if (!empty($_POST[$field])) {
                        $insert_data[$field] = $_POST[$field];
                    }
                }

                // Only insert if there's at least one value
                if (!empty($insert_data)) {
                    $flag = insertTable("tutor", $insert_data, ["photo"]);
                }

                if ($flag) {
                    $data['msg'] = str_replace("{table}", "Tutor ", $configs['success_msg']);
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
