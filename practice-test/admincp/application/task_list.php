<?php
include("../common/access.php");
include("../common/autoload.php");

// Initialize message variable
$data['msg'] = '';

// Page configuration
$data['page_title'] = "Open Task Management";
$data['breadcrumb'] = array(
    "Dashboard" => "dashboard.php",
    "Open Task List" => ""
);

// $data['btn'] = '<a href="employee_task" class="btn btn-primary">Add New Task</a>';

// Handle task deletion
if(isset($_GET['del_id']) && is_numeric($_GET['del_id'])) {
    $del_id = (int)$_GET['del_id'];
    $r = deleteData("employee_task", array("id" => $del_id));
    if($r) {
        $data['msg'] = '<div class="alert alert-success">Task deleted successfully</div>';
    } else {
        $data['msg'] = '<div class="alert alert-danger">Error deleting task</div>';
    }
}

// Build base query
$query = array();

// Add search functionality
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];

    $query = array_merge($query, array("[or]" => "(id='".$search_term."' or assigned_by like '%" . $search_term . "%' or assigned_to like '%" . $search_term . "%')"));
}

// Build base query - only show untested tasks
if($_GET['search_status'] && $_GET['search_status']!='all') {
    $query = array_merge($query, array("status" => htmlspecialchars($_GET['search_status'])));
}
else if($_GET['status'] == "pending"){
    $query = array("[or]" => "status!='Completed' or tested!='Y'");
    // $query = array("tested"=>'N');
}
else if($_GET['status'] == "completed"){
    $query = array("status"=>'Completed', "tested"=>'Y');
}

// echo "<br><br><br><br><br><br><br>";

// Get task data
$data['total'] = getTotalRow("employee_task", $query);
$data['db_list'] = getQuery("employee_task", "*", $query, "id DESC", $configs['limit']);

// Include template
echo template($data);
?>