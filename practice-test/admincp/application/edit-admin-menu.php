<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['action']) {
    $_GET['action'] = "menu-list";
}

switch ($_GET['action']) {
    case "menu-list":

        if ($_GET['del_menu_id']) {
            deleteData("adminmenu", array("id" => $_GET['del_menu_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['breadcrumb'] = array("Admin Menu List" => "");

        $data['page_title'] = "Admin Menu List";

        $data["btn"] = "<a href='edit-admin-menu?action=add-menu' class='btn btn-success'>Add Menu Item</a>";

        $data['parent_menu_list'] = getQuery("adminmenu", "*", array("parent_id"=>0), "ord");

        foreach ($data['parent_menu_list'] as $menu) {
            $data['db_list'][$menu['id']] = getQuery("adminmenu", "*", array("parent_id" => $menu['id']), "ord");
        }

        break;

    case "add-menu":

        $data['parent_list'] = getQuery("adminmenu", "*", array("parent_id"=>0), "ord");

        $data['page_subtitle'] = "Add / Edit Admin Menu Item";

        $data['breadcrumb'] = array(
            "Admin Side Menu List" => "edit-admin-menu?action=menu-list",
            "Add / Edit Menu Item" => ""
        );

        $data['btn'] = "<a href='edit-admin-menu?action=menu-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['id']) {
            $_GET['id'] = $_POST['id'] ?? null;
        }

        if ($_GET['id']) {
            $data['db_value'] = getDataByWhere("adminmenu", "*", array("id" => $_GET['id']));
        }

        if (isset($_POST['submit'])) {

            // List of all fields from the form
            $fields = [
                'name',
                'url',
                'parent_id',
                'status',
                'ord'
            ];

            // Prepare data array with only non-empty values
            $insert_data = [];
            foreach ($fields as $field) {
                if (isset($_POST[$field]) && $_POST[$field] !== '') {
                    $insert_data[$field] = $_POST[$field];
                }
            }

            // Determine table name
            $table_name = "adminmenu";

            if (isset($_GET['id']) && !empty($_GET['id'])) {
                // Update existing tutoring package
                $flag = updateTable($table_name, $insert_data, ["id" => $_GET['id']]);
                $data['db_value'] = getDataByWhere($table_name, "*", ["id" => $_GET['id']]);

                if (!empty($flag)) {
                    $data['msg'] = str_replace("{table}", "Tutoring Package ", $configs['success_msg']);
                }else{
                    $data['msg'] = $configs['error_msg'];
                }

            } else {
                // Insert new tutoring package
                if (!empty($insert_data)) {
                    $flag = insertTable($table_name, $insert_data);
                }

                if (!empty($flag)) {
                    $data['msg'] = str_replace("{table}", "Admin menu ", $configs['success_msg']);
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['db_value'] = $_POST;
                }
            }
        }

        break;

    case "session-package-list":

        $data['total'] = getTotalRow("asvab_tutor_package_sessions", array("package_id" => $_GET['package_id']));

        if ($_GET['del_session_id']) {
            deleteData("asvab_tutor_package_sessions", array("session_id" => $_GET['del_session_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['breadcrumb'] = array(
            "Tutoring Package List" => "asvab-tutoring-package-list?action=package-list",
            "Tutoring Package Sessions" => ""
        );

        $data['page_title'] = "Tutoring Package Session List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-tutoring-package-list?action=add-package-session&package_id=" . $_GET['package_id'] . "' class='btn btn-success'>Add Tutoring Session</a>";

        $data['db_list'] = getQuery("asvab_tutor_package_sessions", "*", array("package_id" => $_GET['package_id']), "ord");

        $data['tutoring_package'] = getDataByWhere("asvab_tutor_package", "*", array("package_id" => $_GET["package_id"]), "ord");

        $data['course'] = getDataByWhere("asvab_course", "*", array("course_id" => $data['tutoring_package']['course_id']));

        break;

    case "add-package-session":

        $data['package_list'] = getQuery("asvab_tutor_package", "*", array(), "ord");

        $data['course_list'] = getQuery("asvab_course", "*", $query, "ord");

        $data['tutoring_package'] = getDataByWhere("asvab_tutor_package", "*", array("package_id" => $_GET["package_id"]));

        $data['page_subtitle'] = "Add / Edit Tutoring Package Session";

        $data['breadcrumb'] = array(
            "Tutoring Package Session" => "asvab-tutoring-package-list?action=package-list",
            "Add / Edit Tutoring Package Session" => ""
        );

        $data['btn'] = "<a href='asvab-tutoring-package-list?action=session-package-list&package_id=" . $_GET['package_id'] . "' class='btn btn-success'>Back To List</a>";

        if (!$_GET['session_id']) {
            $_GET['session_id'] = $_POST['session_id'] ?? null;
        }

        if ($_GET['session_id']) {
            $data['db_value'] = getDataByWhere("asvab_tutor_package_sessions", "*", array("session_id" => $_GET['session_id']));
        }

        if (isset($_POST['submit'])) {

            // List of all fields from the form
            $fields = [
                'title',
                'package_id',
                'feature',
                'is_most_popular',
                'price',
                'ord'
            ];

            // Prepare data array with only submitted values
            $insert_data = [];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $insert_data[$field] = $_POST[$field];
                }
            }

            // Determine table name
            $table_name = "asvab_tutor_package_sessions";

            if (isset($_GET['session_id']) && !empty($_GET['session_id'])) {
                // Update existing session
                updateTable($table_name, $insert_data, ["session_id" => $_GET['session_id']]);
                $data['db_value'] = getDataByWhere($table_name, "*", ["session_id" => $_GET['session_id']]);
                $data['msg'] = str_replace("{table}", "Tutoring Session ", $configs['success_msg']);
            } else {
                // Insert new session
                if (!empty($insert_data)) {
                    $flag = insertTable($table_name, $insert_data);
                }

                if (!empty($flag)) {
                    $data['msg'] = str_replace("{table}", "Tutoring Session ", $configs['success_msg']);
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
