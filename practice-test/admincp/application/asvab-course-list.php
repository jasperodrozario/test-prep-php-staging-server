<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$query = [];

if (!$_GET['action']) {
    $_GET['action'] = "course-list";
}

switch ($_GET['action']) {
    case "course-list":

        if (isset($_POST['search_btn']) && $_POST['search']) {
            $query = array_merge($query, array("[or]" => "(name like '%" . $_POST['search'] . "%' or course_id like '%" . $_POST['search'] . "%')"));
        }

        $data['total'] = getTotalRow("asvab_course", $query);

        if ($_GET['del_course_id']) {
            deleteData("asvab_course", array("course_id" => $_GET['del_course_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['page_subtitle'] = "Total Course [" . $data['total'] . "]";

        $data['breadcrumb'] = array("Course List" => "");

        $data['page_title'] = "Course List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-course-list?action=add-course' class='btn btn-success'>Add New Course</a>";

        $data['db_list'] = getQuery("asvab_course", "*", $query, "ord", $configs['limit']);

        break;


    case "add-course":

        $data['page_subtitle'] = "Add / Edit Course";

        $data['breadcrumb'] = array(
            "Course List" => "asvab-course-list?action=course-list",
            "Add / Edit Course Information" => ""
        );

        $data['btn'] = "<a href='asvab-course-list?action=course-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['course_id']) {
            $_GET['course_id'] = $_POST['course_id'] ?? null;
        }

        if ($_GET['course_id']) {
            $data['db_value'] = getDataByWhere("asvab_course", "*", array("course_id" => $_GET['course_id']));
            
            // Fetch sessions and lessons for this course
            $sessions = getAllRowsByWhereWithPrefix("asvab_sessions", "*", array("course_id" => $_GET['course_id']), "order_index");
            $sessions_list = [];
            foreach ($sessions as $session) {
                $lessons = getAllRowsByWhereWithPrefix("asvab_lessons", "*", array("session_id" => $session['id']), "order_index");
                $session['lessons'] = $lessons;
                $sessions_list[] = $session;
            }
            $data['sessions_list'] = $sessions_list;
        }

        if (isset($_POST['submit'])) {

            $status = isset($_POST['status']) ? $_POST['status'] : 'N';

            // List of all fields from form
            $fields = [
                'name',
                'header',
                'tagline',
                'details',
                'curriculum',
                'status',
                'video_title',
                'video',
                'course_name_test_prep',
                'ord'
            ];

            // Prepare data array only with non-empty values
            $insert_data = [];
            foreach ($fields as $field) {
                if (!empty($_POST[$field])) {
                    $insert_data[$field] = $_POST[$field];
                }
            }

            // Add status explicitly
            $insert_data['status'] = $status;

            if ($_GET['course_id']) {
                // Update existing course
                updateTable("asvab_course", $insert_data, array("course_id" => $_GET['course_id']), array("thumb_pic"));
                $data['db_value'] = getDataByWhere("asvab_course", "*", array("course_id" => $_GET['course_id']));
                $data['msg'] = str_replace("{table}", "Course ", $configs['success_msg']);
            } else {
                // Insert new course
                $flag = 0;
                if (!empty($insert_data)) {
                    $flag = insertTable("asvab_course", $insert_data, ["thumb_pic"]);
                }

                if (!empty($flag)) {
                    $_GET['course_id'] = (int)$flag;
                    $data['db_value'] = getDataByWhere("asvab_course", "*", array("course_id" => $_GET['course_id']));
                    $data['msg'] = str_replace("{table}", "Course ", $configs['success_msg']);
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['db_value'] = $_POST;
                }
            }
        }

        // Load existing variants for display on edit
        if (!empty($_GET['course_id'])) {
            $variant_rows = getAllRowsByWhereWithPrefix(
                "asvab_course_variants",
                "*",
                ["parent_asvab_course_id" => (int)$_GET['course_id']],
                "ord"
            );
            $existing_variants = [];
            foreach ($variant_rows as $row) {
                $main_id = (int)($row['main_course_id'] ?? 0);
                $course_name = $main_id > 0
                    ? (string)getFldValueById("course", "course_name", ["course_id" => $main_id])
                    : '';
                $existing_variants[] = [
                    'variant_id' => (int)($row['variant_id'] ?? 0),
                    'main_course_id' => $main_id,
                    'label' => $row['label'] ?? '',
                    'ord' => (int)($row['ord'] ?? 0),
                    'is_active' => (int)($row['is_active'] ?? 0),
                    'course_name' => $course_name !== '' ? $course_name : ('Course ' . $main_id),
                ];
            }
            $data['existing_variants'] = $existing_variants;
        }

        break;

    case "add-variant":
        $course_id = (int)($_GET['course_id'] ?? $_POST['course_id'] ?? 0);
        if ($course_id <= 0) {
            header("Location: asvab-course-list?action=course-list");
            exit;
        }

        $parent_course = getDataByWhere("asvab_course", "*", ["course_id" => $course_id]);
        if (empty($parent_course)) {
            header("Location: asvab-course-list?action=course-list");
            exit;
        }

        $data['page_subtitle'] = "Add Course Variant";
        $data['breadcrumb'] = array(
            "Course List" => "asvab-course-list?action=course-list",
            "Add / Edit Course Information" => "asvab-course-list?action=add-course&course_id=" . $course_id,
            "Add Course Variant" => ""
        );
        $data['btn'] = "<a href='asvab-course-list?action=add-course&course_id=" . $course_id . "' class='btn btn-success'>Back To Course</a>";
        $data['parent_course'] = $parent_course;
        $data['course_id'] = $course_id;

        if (isset($_POST['submit'])) {
            $label = trim($_POST['label'] ?? '');
            $course_name = trim($_POST['course_name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ord = $_POST['ord'] ?? '';
            $group_id = (int)($_POST['group_id'] ?? 0);

            $errors = [];
            if ($label === '') {
                $errors[] = "Label is required.";
            }
            if ($course_name === '') {
                $errors[] = "Name is required.";
            }
            if (!isset($_POST['ord']) || trim((string)$_POST['ord']) === '') {
                $errors[] = "Display Order is required.";
            }
            if ($group_id <= 0) {
                $errors[] = "Course Group is required.";
            }

            if (!empty($errors)) {
                $data['msg'] = $configs['error_msg'] . " " . implode(" ", $errors);
                $data['db_value'] = $_POST;
            } else {
                $existing_label = getFldValueById(
                    "asvab_course_variants",
                    "variant_id",
                    [
                        "parent_asvab_course_id" => $course_id,
                        "label" => $label,
                    ]
                );
                if (!empty($existing_label)) {
                    $data['msg'] = $configs['error_msg'] . " A variant with this label already exists for this course.";
                    $data['db_value'] = $_POST;
                } else {
                    $is_online = isset($_POST['is_online']) ? 'Y' : 'N';
                    $app = isset($_POST['app']) ? 1 : 0;
                    $tutoring = isset($_POST['tutoring']) ? 1 : 0;
                    $subscribe = isset($_POST['subscribe']) ? 1 : 0;

                    $course_data = [
                        'course_name' => $course_name,
                        'description' => $description,
                        'ord' => (int)$ord,
                        'group_id' => $group_id,
                        'is_online' => $is_online,
                        'app' => $app,
                        'tutoring' => $tutoring,
                        'subscribe' => $subscribe,
                    ];

                    $main_course_id = insertTable("course", $course_data);
                    if (empty($main_course_id)) {
                        $data['msg'] = $configs['error_msg'] . " Failed to create portal course.";
                        $data['db_value'] = $_POST;
                    } else {
                        $bridge_id = insertTable("asvab_course_variants", [
                            'parent_asvab_course_id' => $course_id,
                            'main_course_id' => (int)$main_course_id,
                            'label' => $label,
                            'ord' => (int)$ord,
                            'is_active' => 1,
                        ]);

                        if (empty($bridge_id)) {
                            $data['msg'] = $configs['error_msg'] . " Created portal course but failed to link variant (duplicate label or DB error).";
                            $data['db_value'] = $_POST;
                        } else {
                            header("Location: asvab-course-list?action=add-course&course_id=" . $course_id);
                            exit;
                        }
                    }
                }
            }
        }

        break;

    case "add-session":
        $data['page_subtitle'] = "Add / Edit Session";
        $course_id = $_GET['course_id'] ?? $_POST['course_id'];
        $data['breadcrumb'] = array(
            "Course List" => "asvab-course-list?action=course-list",
            " Add / Edit Course Information" => "asvab-course-list?action=add-course&course_id=" . $course_id,
            "Add / Edit Session" => ""
        );
        $data['btn'] = "<a href='asvab-course-list?action=add-course&course_id=" . $course_id . "' class='btn btn-success'>Back To Course</a>";

        if ($_GET['id']) {
            $data['db_value'] = getDataByWhere("asvab_sessions", "*", array("id" => $_GET['id']));
        }

        if (isset($_POST['submit'])) {
            if (empty($_POST['session_title']) || empty($_POST['order_index'])) {
                $data['msg'] = $configs['error_msg'] . " All fields are required.";
                $data['db_value'] = $_POST;
            } else {
                $insert_data = [
                    'course_id' => $_POST['course_id'],
                    'session_title' => $_POST['session_title'],
                    'order_index' => $_POST['order_index']
                ];

                if ($_GET['id']) {
                    updateTable("asvab_sessions", $insert_data, array("id" => $_GET['id']));
                } else {
                    insertTable("asvab_sessions", $insert_data);
                }
                header("Location: asvab-course-list?action=add-course&course_id=" . $_POST['course_id']);
                exit;
            }
        }

        if (isset($_REQUEST['SBMT_DEL']) && $_GET['id']) {
            // Delete associated lessons first
            deleteData("asvab_lessons", array("session_id" => $_GET['id']));
            // Delete the session
            deleteData("asvab_sessions", array("id" => $_GET['id']));
            header("Location: asvab-course-list?action=add-course&course_id=" . $course_id);
            exit;
        }

        break;

    case "add-lesson":
        $data['page_subtitle'] = "Add / Edit Lesson";

        $session_id = $_GET['session_id'] ?? $_POST['session_id'];
        $course_id = $_GET['course_id'] ?? "";
        
        if ($session_id && !$course_id) {
            $session_info = getDataByWhere("asvab_sessions", "course_id", array("id" => $session_id));
            $course_id = $session_info['course_id'];
        }

        $data['breadcrumb'] = array(
            "Course List" => "asvab-course-list?action=course-list",
            "Add / Edit Course Information" => "asvab-course-list?action=add-course&course_id=" . $course_id,
            "Add / Edit Lesson" => ""
        );
        $data['btn'] = "<a href='asvab-course-list?action=add-course&course_id=" . $course_id . "' class='btn btn-success'>Back To Course</a>";

        if ($_GET['id']) {
            $data['db_value'] = getDataByWhere("asvab_lessons", "*", array("id" => $_GET['id']));
        }

        if (isset($_POST['submit'])) {
            if (empty($_POST['lesson_name']) || empty($_POST['order_index'])) {
                $data['msg'] = $configs['error_msg'] . " All fields are required.";
                $data['db_value'] = $_POST;
            } else {
                $insert_data = [
                    'session_id' => $_POST['session_id'],
                    'lesson_name' => $_POST['lesson_name'],
                    'order_index' => $_POST['order_index']
                ];

                if ($_GET['id']) {
                    updateTable("asvab_lessons", $insert_data, array("id" => $_GET['id']));
                } else {
                    insertTable("asvab_lessons", $insert_data);
                }
                header("Location: asvab-course-list?action=add-course&course_id=" . $course_id);
                exit;
            }
        }

        if (isset($_REQUEST['SBMT_DEL']) && $_GET['id']) {
            deleteData("asvab_lessons", array("id" => $_GET['id']));
            header("Location: asvab-course-list?action=add-course&course_id=" . $course_id);
            exit;
        }

        break;
}

$data['action'] = $_GET['action'];

echo template($data);
