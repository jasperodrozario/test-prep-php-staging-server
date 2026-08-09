<?php
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Developer's Untilities";

$data['breadcrumb'] = array("Developer's Untilities" => "");

$tasks = [
    [
        'id' => 1,
        'link' => 'temp-task',
        'title' => "Update User's Data",
        'description' => ["It updates user's data in multiple tables using different conditions."]
    ],
    [
        'id' => 2,
        'link' => 'delete-course-student',
        'title' => "Delete All Users From A Specific Course",
        'description' => ["Just need to select a specific course to delete all users of that course."]
    ],
    [
        'id' => 3,
        'link' => 'transfer-users',
        'title' => "Transfer Users From One Database to Another",
        'description' => [
            "Check the code file for more details.",
            "Add extra conditions in code file if needed."
        ]
    ],
    [
        'id' => 4,
        'link' => 'edit-admin-menu',
        'title' => "Edit Admin Menu",
        'description' => [
            "This section allows you to edit and manage the admin menu items.",
            "You can update menu titles, URLs, ordering, and visibility.",
            "Check the code file for additional customization options."
        ]
    ]

];

$data['tasks'] = $tasks;

echo template($data);
