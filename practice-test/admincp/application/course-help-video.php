<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$query = [];

if (!$_GET['action']) {
    $_GET['action'] = "course-help-video-list";
}

$data['course_list'] = getQuery("course", "*", [], "course_name");

switch ($_GET['action']) {

    case "course-help-video-list":

        $data['total'] = getTotalRow("course_help_videos", $query);

        if ($_GET['del_cv_id']) {
            deleteData("course_help_videos", array("c_id" => $_GET['del_cv_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['page_subtitle'] = "Total Course Study Videos [" . $data['total'] . "]";

        $data['breadcrumb'] = array("Course Study Videos" => "course-help-video?action=course-help-video-list");

        $data['page_title'] = "Course Study Videos (" . $data['total'] . ")";

        $data["btn"] = "<a href='course-help-video?action=add-course-help-video' class='btn btn-success'>Add New Course Study Video</a>";

        $data['db_list'] = getQuery("course_help_videos", "*", $query);

        break;


    case "add-course-help-video":

        $data['page_subtitle'] = "Add / Edit Course Study Video";

        $data['breadcrumb'] = array(
            "Course Study Videos" => "course-help-video?action=course-help-video-list",
            "Add / Edit Course Study Video" => ""
        );

        $data['btn'] = "<a href='course-help-video?action=course-help-video-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['cv_id']) {
            $_GET['cv_id'] = $_POST['cv_id'] ?? null;
        }

        if ($_GET['cv_id']) {
            $data['db_value'] = getDataByWhere("course_help_videos", "*", array("c_id" => $_GET['cv_id']));
        }

        if (isset($_POST['submit'])) {

            $is_online = isset($_POST['is_online']) ? $_POST['is_online'] : 'N';

            // List of all fields from form
            $fields = [
                "course_id",
                "mobile_youtube_link",
                "desktop_youtube_link"
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

            if (!empty($_GET['cv_id'])) {
                // Update existing Course Study Video
                $flag = updateTable("course_help_videos", $insert_data, ["c_id" => $_GET['cv_id']], array("thumbnail"));
                $data['db_value'] = getDataByWhere("course_help_videos", "*", ["c_id" => $_GET['cv_id']]);

                if ($flag){
                    $data['msg'] = str_replace("{table}", "Course Study Video ", $configs['success_msg']);
                } else {
                    $data['msg'] = $configs['error_msg'];
                }
            } else {
                // Insert new Course Study Video
                if (!empty($insert_data)) {
                    $flag = insertTable("course_help_videos", $insert_data, array("thumbnail"));
                }

                if ($flag) {
                    $data['msg'] = str_replace("{table}", "Course Study Video ", $configs['success_msg']);
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
