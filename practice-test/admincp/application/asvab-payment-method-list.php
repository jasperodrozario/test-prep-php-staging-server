<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$query = [];

if (!$_GET['action']) {
    $_GET['action'] = "payment-method-list";
}

switch ($_GET['action']) {
    case "payment-method-list":

        if (isset($_POST['search_btn']) && $_POST['search']) {
            $query = array_merge($query, array("[or]" => "(id like '%" . $_POST['search'] . "%' or payment_method like '%" . $_POST['search'] . "%')"));
        }

        $data['total'] = getTotalRow("asvab_payment_method", $query);

        if ($_GET['del_id']) {
            deleteData("asvab_payment_method", array("id" => $_GET['del_id']));
            $data['msg'] = $configs["delete_msg"];
        }

        $data['page_subtitle'] = "Total Payment Method [" . $data['total'] . "]";

        $data['breadcrumb'] = array("Payment Method List" => "");

        $data['page_title'] = "Payment Method List (" . $data['total'] . ")";

        $data["btn"] = "<a href='asvab-payment-method-list?action=add-payment-method' class='btn btn-success'>Add Payment Method</a>";

        $data['db_list'] = getQuery("asvab_payment_method", "*", $query, "ord");

        break;


    case "add-payment-method":

        $data['page_subtitle'] = "Add New Payment Method";

        $data['breadcrumb'] = array("Payment Method Management" => "asvab-payment-method-list?action=payment-method-list", "Add / Edit Payment Information" => "");
        $data['btn'] = "<a href='asvab-payment-method-list?action=payment-method-list' class='btn btn-success'>Back To List</a>";

        if (!$_GET['id']) {
            $_GET['id'] = $_POST['id'];
        }

        if ($_GET['id']) {
            $data['db_value'] = getDataByWhere("asvab_payment_method", "*", array("id" => $_GET['id']));
            //            $data['msg'].="<script>setTimeout(\"location.href = 'tutor-list?action=employee-list';\",2500);</script>";
        }

        if (isset($_POST['submit'])) {

            $status = isset($_POST['status']) ? $_POST['status'] : 'N';

            if ($_GET['id']) {
                updateTable("asvab_payment_method", array("payment_method" => $_POST["payment_method"], "status" => $status, "ord" => $_POST["ord"]), array("id" => $_GET['id']), array("photo"));

                $data['db_value'] = getDataByWhere("asvab_payment_method", "*", array("id" => $_GET['id']));
                $data['msg'] = str_replace("{table}", "Payment Method ", $configs['success_msg']);
            } else {

                // List of all fields
                $fields = ['payment_method', 'status', 'ord'];

                // Prepare data array only with non-empty values
                $insert_data = [];
                foreach ($fields as $field) {
                    if (!empty($_POST[$field])) {
                        $insert_data[$field] = $_POST[$field];
                    }
                }

                $insert_data['status'] = $status;

                // Only insert if there's at least one value
                if (!empty($insert_data)) {
                    $flag = insertTable("asvab_payment_method", $insert_data, ["photo"]);
                }

                if ($flag) {
                    $data['msg'] = str_replace("{table}", "Payment Method ", $configs['success_msg']);
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
