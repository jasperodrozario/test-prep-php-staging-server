<?
include("../common/access.php");
include("../common/autoload.php");

$data['meta_title'] = "New Task";
$data['breadcrumb'] = array("Task Management" => "task_list?status=pending", "New Task" => "");

if ($_POST['SBMT_REG']) {
    $flag = 0;

    $_POST['on_staging'] = isset($_POST['on_staging']) ? 'Y' : 'N';
    $_POST['tested'] = isset($_POST['tested']) ? 'Y' : 'N';
    $_POST['routine_task'] = $_POST['routine_task'] ?? 'N';

    if ($_POST['routine_task'] == 'N') {
        $_POST['routine_type'] = '';
    } else {
        $_POST['status'] = 'In Progress';
    }

    if ($_POST['task_id']) {
        unset($task_data['id']);

        $flag = updateTable("employee_task", $_POST, array("id" => $_POST['task_id']));
    } else {
        unset($_POST['task_id']);
        
        $_POST['date_created'] = date('Y-m-d H:i:s');
        
        if ($_POST['routine_task'] != 'Y') {
            $_POST['status'] = 'Pending';
        }

        $flag = insertTable("employee_task", $_POST);
    }

    if ($flag) {
        $data['msg'] = $configs['success_msg'];
        header("location:task_list?status=pending");
    } else {
        $data['msg'] = $configs['error_msg'];
    }
}

if ($_REQUEST['task_id']) {
    $data['db_value'] = getDataByWhere("employee_task", "*", array("id" => $_REQUEST['task_id']));
}

echo template($data);
