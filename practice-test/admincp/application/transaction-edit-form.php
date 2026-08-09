<?
include("../common/access.php");
include("../common/autoload.php");

if ($_GET['action'] == 'subscription-edit') {
    $data['page_title'] = "Edit Subscription";
    $data['breadcrumb'] = array("Square Subscription List" => "square-subscription", "Edit Subscription" => "");
    $data['db_value'] = getDataByWhere("square_subscriptions", "*", array("id" => $_GET['id']));
} else if ($_GET['action'] == 'payments-edit') {
    $data['page_title'] = "Edit Payment";
    $data['breadcrumb'] = array("Square Payments List" => "square-payments", "Edit Payment" => "");
    $data['db_value'] = getDataByWhere("square_payments", "*", array("id" => $_GET['id']));
} else if ($_GET['action'] == 'invoice-edit') {
    $data['page_title'] = "Edit Invoice";
    $data['breadcrumb'] = array("Square Invoice List" => "square-invoice", "Edit Invoice" => "");
    $data['db_value'] = getDataByWhere("square_invoices", "*", array("id" => $_GET['id']));
} else if ($_GET['action'] == 'dispute-edit') {
    $data['page_title'] = "Edit Dispute";
    $data['breadcrumb'] = array("Square Dispute List" => "square-dispute", "Edit Dispute" => "");
    $data['db_value'] = getDataByWhere("square_disputes", "*", array("id" => $_GET['id']));
} else if ($_GET['action'] == 'plan-variation-edit') {
    $data['page_title'] = "Edit Plan Variation";
    $data['breadcrumb'] = array("Square Plan Variations List" => "square-plan-variations", "Edit Plan Variation" => "");
    $data['db_value'] = getDataByWhere("square_plan_variations", "*", array("id" => $_GET['id']));
} else if ($_GET['action'] == 'add-plan-variation') {
    $data['page_title'] = "Add Plan Variation";
    $data['breadcrumb'] = array("Square Plan Variations List" => "square-plan-variations", "Add Plan Variation" => "");
}

$query = [];

if (isset($_POST['submit'])) {
    if ($_GET['action'] == 'subscription-edit') {
        $query = array(
            "amount" => $_POST['amount'],
            "currency" => $_POST['currency'],
            "status" => $_POST['status'],
            "start_date" => $_POST['start_date'],
            "next_billing_date" => $_POST['next_billing_date'],
            "cancel_status" => $_POST['cancel_status'],
            "cancel_effective_date" => $_POST['cancel_effective_date'],
            "canceled_at" => $_POST['canceled_at']
        );

        updateTable("square_subscriptions", $query, array("id" => $_GET['id']));

        $data['msg'] = $configs["update_msg"];
        header("location:" . $configs['avpath'] . "square-subscription");
    } else if ($_GET['action'] == 'payments-edit') {
        $query = array(
            "amount" => $_POST['amount'],
            "currency" => $_POST['currency'],
            "payment_status" => $_POST['payment_status'],
            "updated_at" => $_POST['updated_at']
        );

        updateTable("edu_square_payments", $query, array("id" => $_GET['id']));

        $data['msg'] = $configs["update_msg"];
        header("location:" . $configs['avpath'] . "square-payments");
    } else if ($_GET['action'] == 'invoice-edit') {
        $query = array(
            "amount" => $_POST['amount'],
            "status" => $_POST['status'],
            "paid_at" => $_POST['paid_at'],
            "failed_at" => $_POST['failed_at'],
            "next_payment_attempt_at" => $_POST['next_payment_attempt_at'],
            "updated_at" => $_POST['updated_at']
        );

        updateTable("square_invoices", $query, array("id" => $_GET['id']));

        $data['msg'] = $configs["update_msg"];
        header("location:" . $configs['avpath'] . "square-invoices");
    } else if ($_GET['action'] == 'dispute-edit') {
        $query = array(
            "amount" => $_POST['amount'],
            "currency" => $_POST['currency'],
            "card_brand" => $_POST['card_brand'],
            "reason" => $_POST['reason'],
            "state" => $_POST['state'],
            "due_at" => $_POST['due_at'],
            "square_updated_at" => $_POST['square_updated_at'],
            "updated_at" => $_POST['updated_at']
        );

        updateTable("square_disputes", $query, array("id" => $_GET['id']));

        $data['msg'] = $configs["update_msg"];
        header("location:" . $configs['avpath'] . "square-dispute");
    } else if ($_GET['action'] == 'plan-variation-edit') {
        $query = array(
            "session_id" => $_POST['session_id'],
            "env_name" => $_POST['env_name'],
            "square_plan_id" => $_POST['square_plan_id'],
            "plan_variation_id" => $_POST['plan_variation_id'],
            "environment" => $_POST['environment']
        );

        updateTable("square_plan_variations", $query, array("id" => $_GET['id']));

        $data['msg'] = $configs["update_msg"];
        header("location:" . $configs['avpath'] . "square-plan-variations");
    } else if ($_GET['action'] == 'add-plan-variation') {

        if(isset($_POST['submit'])){
            
            unset($_POST['submit']);

            $flag = insertTable("square_plan_variations", $_POST);

            if ($flag) {
                $data['msg'] = $configs["success_msg"];
            }
        }
        // header("location:" . $configs['avpath'] . "square-plan-variations");
    }
}

echo template($data);
