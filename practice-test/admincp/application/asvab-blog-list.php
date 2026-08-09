<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$query = [];

if (!$_GET['action']) {
    $_GET['action'] = "blog-list";
}

switch ($_GET['action']) {
    case "blog-list":

        if (isset($_POST['search_btn']) && $_POST['search']) {
            $query = array_merge($query, array("[or]" => "(blog_title like '%" . $_POST['search'] . "%' or blog_id like '%" . $_POST['search'] . "%')"));
        }

        $data['total'] = getTotalRow("asvab_blog", $query);

        if ($_GET['del_blog_id']) {
            $flag = deleteData("asvab_blog", array("blog_id" => $_GET['del_blog_id']));

            if ($flag) {
                $data['msg'] = $configs["delete_msg"];
            }
        }

        $data['page_subtitle'] = "Total Blog [" . $data['total'] . "]";

        $data['breadcrumb'] = array("Blog List" => "");

        $data['page_title'] = "Blog List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-blog-list?action=add-blog' class='btn btn-success'>Add New Blog</a>";

        $data['db_list'] = getQuery("asvab_blog", "*", $query);

        break;


    case "add-blog":

        $data['page_subtitle'] = "Add / Edit Blog";

        $data['breadcrumb'] = array(
            "Blog List" => "asvab-blog-list?action=blog-list",
            "Add / Edit Blog Information" => ""
        );

        $data['btn'] = "<a href='asvab-blog-list?action=blog-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['blog_id']) {
            $_GET['blog_id'] = $_POST['blog_id'] ?? null;
        }

        if ($_GET['blog_id']) {
            $data['db_value'] = getDataByWhere("asvab_blog", "*", array("blog_id" => $_GET['blog_id']));
        }

        if (isset($_POST['submit'])) {

            // List of all fields from form
            $fields = [
                'blog_title',
                'blog_content',
                'written_by',
                'created_at'
            ];

            // Prepare data array only with non-empty values
            $insert_data = [];
            foreach ($fields as $field) {
                if (!empty($_POST[$field])) {
                    $insert_data[$field] = $_POST[$field];
                }
            }

            if ($_GET['blog_id']) {
                if($_POST['writer_img']){
                    updateTable("asvab_blog", $insert_data, array("blog_id" => $_GET['blog_id']), array("writer_img"));
                }
                else{
                    updateTable("asvab_blog", $insert_data, array("blog_id" => $_GET['blog_id']));
                }
                
                $data['db_value'] = getDataByWhere("asvab_blog", "*", array("blog_id" => $_GET['blog_id']));
                $data['msg'] = str_replace("{table}", "Blog ", $configs['success_msg']);
            } else {
                // Insert new course
                if (!empty($insert_data)) {
                    $flag = insertTable("asvab_blog", $insert_data, ["writer_img"]);
                    // $data['insert_data'] = $insert_data;
                }

                if (!empty($flag)) {
                    $data['msg'] = str_replace("{table}", "Blog ", $configs['success_msg']);
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
