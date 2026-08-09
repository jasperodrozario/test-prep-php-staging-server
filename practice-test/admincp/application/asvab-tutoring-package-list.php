<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['action']) {
    $_GET['action'] = "package-list";
}

switch ($_GET['action']) {
    case "package-list":

        $data['total'] = getTotalRow("asvab_tutor_package", array());

        if ($_GET['del_package_id']) {
            deleteData("asvab_tutor_package_sessions", array("package_id" => $_GET['del_package_id']));
            deleteData("asvab_tutor_package", array("package_id" => $_GET['del_package_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['breadcrumb'] = array("Tutoring Package List" => "");

        $data['page_title'] = "Tutoring Package List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-tutoring-package-list?action=add-package' class='btn btn-success'>Add Tutoring Package</a>";

        $data['course_list'] = getQuery("asvab_course", "*", $query, "ord");

        foreach ($data['course_list'] as $course) {
            $data['db_list'][$course['course_id']] = getQuery("asvab_tutor_package", "*", array("course_id" => $course['course_id']), "ord");
        }

        break;

    case "add-package":

        $data['course_list'] = getQuery("asvab_course", "*", $query, "ord");

        $data['page_subtitle'] = "Add / Edit Tutoring Package";

        $data['breadcrumb'] = array(
            "Tutoring Package" => "asvab-tutoring-package-list?action=package-list",
            "Add / Edit Tutoring Package Information" => ""
        );

        $data['btn'] = "<a href='asvab-tutoring-package-list?action=package-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['package_id']) {
            $_GET['package_id'] = $_POST['package_id'] ?? null;
        }

        if ($_GET['package_id']) {
            $data['db_value'] = getDataByWhere("asvab_tutor_package", "*", array("package_id" => $_GET['package_id']));
        }

        if (isset($_POST['submit'])) {

            $_POST['description'] = $_POST['details'];

            // List of all fields from the form
            $fields = [
                'title',
                'course_id',
                'description',
                'code_name',
                'is_online',
                'is_group_tutoring',
                'num_of_sessions',
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
            $table_name = "asvab_tutor_package";

            if (isset($_GET['package_id']) && !empty($_GET['package_id'])) {
                // Update existing tutoring package
                updateTable($table_name, $insert_data, ["package_id" => $_GET['package_id']]);
                $data['db_value'] = getDataByWhere($table_name, "*", ["package_id" => $_GET['package_id']]);
                $data['msg'] = str_replace("{table}", "Tutoring Package ", $configs['success_msg']);
            } else {
                // Insert new tutoring package
                if (!empty($insert_data)) {
                    $flag = insertTable($table_name, $insert_data);
                }

                if (!empty($flag)) {
                    $data['msg'] = str_replace("{table}", "Tutoring Package ", $configs['success_msg']);
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

        $data['course_list'] = getQuery("course", "*", array(), "group_id, ord");

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

            $course_data = getDataByWhere("course", "*", array("course_id" => $_POST['course_id']));
            $_POST['course_name'] = $course_data['course_name'];

            // List of all fields from the form
            $fields = [
                'title',
                'package_id',
                'feature',
                'is_most_popular',
                'price',
                'ord',
                'course_id',
                'payment_mode',
                'production_plan_variation_id',
                'sandbox_plan_variation_id',
                'currency',
                'is_active',
                'course_name',
                'package_duration'
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
                $flag = updateTable($table_name, $insert_data, ["session_id" => $_GET['session_id']]);

                if($flag){
                    $data['msg'] = str_replace("{table}", "Tutoring Session ", $configs['success_msg']);
                }else{
                    $data['msg'] = $configs['error_msg'];
                }
                $data['db_value'] = getDataByWhere($table_name, "*", ["session_id" => $_GET['session_id']]);
                
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
