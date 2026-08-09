<?php
include("../configs/autoload.php");
chklogin(str_replace("/practice-test/", "", $_SERVER['REQUEST_URI']));

$report = [];
$lesson_list = [];
$report_total = [];

if (!$_GET['cat_id']) {
	$_GET['cat_id'] = "2000";
}

if ($_GET['cat_id'] == 2000) {
} else {

}

echo template($data);
