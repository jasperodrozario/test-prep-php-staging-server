<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['page']) {
    $_GET['page'] = 'story-list';
}

if ($_GET['page']) {

    if ($_GET['del_id']) {
        $r = deleteData("story", array("story_id" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
    }

    if ($_POST['search']) {
        $data['db_list'] = getQuery("story", "*", array("story like " => "'%" . $_POST['search'] . "%'"));

        $data['total'] = getTotalRow("story", "*", array("story like " => "'%" . $_POST['search'] . "%'"));

    } else {
        $data['db_list'] = getQuery("story", "*", "", "story_id desc", $configs['limit']);
        
        $data['total'] = getTotalRow("story");
    }
}

$data['page_title'] = "Story List";
$data['breadcrumb'] = array("Story List" => "",
    " > Total Stories: " . $data['total'] => "");

echo template($data);
?>
