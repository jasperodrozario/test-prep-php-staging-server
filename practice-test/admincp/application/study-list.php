<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$data['categories'] = getQuery("category", "*","", "category_name");

if (!$_GET['page']) {
    $_GET['page'] = 'study-list';
}

$data['btn']="<input type='button' value='Add New Lesson Study' class='btn btn-success' onClick=\"javascript:window.location='study_entryform'\">";

$data['option_no'] = 5;

if ($_GET['page']) {

    if ($_GET['del_id']) {
        $r = deleteData("study", array("study_id" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
    }
    
    $query=[];
    if($_POST['id'])
    {
        $query=array_merge($query,array("study_id"=>$_POST['id']));
    }
    if($_POST['title'])
    {
        $query=array_merge($query,array("study_title like "=>"'%".$_POST['title'] ."%'"));
    }
    
    $data['db_list'] = getQuery("study", "*", $query, "study_title");
    $data['total'] = getTotalRow("study",$query);
}

$data['page_title'] = "Lesson Study List";
$data['breadcrumb'] = array("Study List" => "",
    " > Total Studies: " . $data['total'] => "");

echo template($data);
?>
