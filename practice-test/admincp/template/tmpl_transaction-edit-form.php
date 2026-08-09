<br>
<?
if($_GET['action']=='subscription-edit'){
?>
<form method="post" action='<?= $configs['admin_vpath'] ?>transaction-edit-form?action=subscription-edit&id=<?php echo $_GET['id'] ?>' enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td width="400px">Subscription ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['subscription_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Square Customer ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['square_customer_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Amount</td>
            <td>
                <input type="text" name="amount" class='form-control' value="<?php echo $db_value['amount']; ?>">
            </td>
        </tr>

        <tr>
            <td>Currency</td>
            <td>
                <input type="text" name="currency" class='form-control' value="<?php echo $db_value['currency']; ?>">
            </td>
        </tr>

        <tr>
            <td>Status</td>
            <td>
                <select name="status" class="form-control">
                    <option value="ACTIVE" <?= ($db_value['status'] == 'ACTIVE') ? 'selected' : '' ?>>ACTIVE</option>
                    <option value="CANCELED" <?= ($db_value['status'] == 'CANCELED') ? 'selected' : '' ?>>CANCELED</option>
                    <option value="PAUSED" <?= ($db_value['status'] == 'PAUSED') ? 'selected' : '' ?>>PAUSED</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Start Date</td>
            <td>
                <input type="date" name="start_date" class='form-control' value="<?php echo $db_value['start_date']; ?>">
            </td>
        </tr>

        <tr>
            <td>Next Billing Date</td>
            <td>
                <input type="date" name="next_billing_date" class='form-control' value="<?php echo $db_value['next_billing_date']; ?>">
            </td>
        </tr>

        <tr>
            <td>Cancel Status</td>
            <td>
                <select name="cancel_status" class="form-control">
                    <option value="">None</option>
                    <option value="CANCEL_PENDING" <?= ($db_value['cancel_status'] == 'CANCEL_PENDING') ? 'selected' : '' ?>>Cancel Pending</option>
                    <option value="CANCELLED" <?= ($db_value['cancel_status'] == 'CANCELLED') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Cancel Effective Date</td>
            <td>
                <input type="date" name="cancel_effective_date" class='form-control' value="<?php echo $db_value['cancel_effective_date']; ?>">
            </td>
        </tr>

        <tr>
            <td>Canceled At</td>
            <td>
                <input type="datetime-local" name="canceled_at" class='form-control'
                    value="<?php echo !empty($db_value['canceled_at']) ? date('Y-m-d\TH:i', strtotime($db_value['canceled_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type='submit' name='submit' value='Update' class='btn btn-success'>
            </td>
        </tr>

    </table>
</form>
<?
}
else if($_GET['action']=='payments-edit'){
?>
<form method="post" action='<?= $configs['admin_vpath'] ?>transaction-edit-form?action=payment-edit&id=<?php echo $_GET['id'] ?>'>

    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td width="400px">Payment ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['payment_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Square Customer ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['square_customer_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Order ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['order_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Amount</td>
            <td>
                <input type="text" name="amount" class='form-control' value="<?php echo $db_value['amount']; ?>">
            </td>
        </tr>

        <tr>
            <td>Currency</td>
            <td>
                <input type="text" name="currency" class='form-control' value="<?php echo $db_value['currency']; ?>">
            </td>
        </tr>

        <tr>
            <td>Payment Status</td>
            <td>
                <select name="payment_status" class="form-control">
                    <option value="PENDING" <?= ($db_value['payment_status'] == 'PENDING') ? 'selected' : '' ?>>PENDING</option>
                    <option value="COMPLETED" <?= ($db_value['payment_status'] == 'COMPLETED') ? 'selected' : '' ?>>COMPLETED</option>
                    <option value="FAILED" <?= ($db_value['payment_status'] == 'FAILED') ? 'selected' : '' ?>>FAILED</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Created At</td>
            <td>
                <input type="datetime-local" class='form-control'
                    value="<?php echo !empty($db_value['created_at']) ? date('Y-m-d\TH:i', strtotime($db_value['created_at'])) : ''; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Updated At</td>
            <td>
                <input type="datetime-local" name="updated_at" class='form-control'
                    value="<?php echo !empty($db_value['updated_at']) ? date('Y-m-d\TH:i', strtotime($db_value['updated_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td>User ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['user_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>User Created At</td>
            <td>
                <input type="datetime-local" class='form-control'
                    value="<?php echo !empty($db_value['user_created_at']) ? date('Y-m-d\TH:i', strtotime($db_value['user_created_at'])) : ''; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type='submit' name='submit' value='Update' class='btn btn-success'>
            </td>
        </tr>

    </table>
</form>
<?
}
else if($_GET['action']=='invoice-edit'){
?>
<form method="post" action='<?= $configs['admin_vpath'] ?>transaction-edit-form?action=invoice-edit&id=<?php echo $_GET['id'] ?>'>

    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td width="400px">Invoice ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['invoice_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Subscription ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['subscription_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Square Customer ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['square_customer_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>User ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['user_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Amount</td>
            <td>
                <input type="text" name="amount" class='form-control' value="<?php echo $db_value['amount']; ?>">
            </td>
        </tr>

        <tr>
            <td>Status</td>
            <td>
                <select name="status" class="form-control">
                    <option value="PENDING" <?= ($db_value['status'] == 'PENDING') ? 'selected' : '' ?>>PENDING</option>
                    <option value="PAID" <?= ($db_value['status'] == 'PAID') ? 'selected' : '' ?>>PAID</option>
                    <option value="FAILED" <?= ($db_value['status'] == 'FAILED') ? 'selected' : '' ?>>FAILED</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Paid At</td>
            <td>
                <input type="datetime-local" name="paid_at" class='form-control'
                    value="<?php echo !empty($db_value['paid_at']) ? date('Y-m-d\TH:i', strtotime($db_value['paid_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td>Failed At</td>
            <td>
                <input type="datetime-local" name="failed_at" class='form-control'
                    value="<?php echo !empty($db_value['failed_at']) ? date('Y-m-d\TH:i', strtotime($db_value['failed_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td>Next Payment Attempt</td>
            <td>
                <input type="datetime-local" name="next_payment_attempt_at" class='form-control'
                    value="<?php echo !empty($db_value['next_payment_attempt_at']) ? date('Y-m-d\TH:i', strtotime($db_value['next_payment_attempt_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td>Created At</td>
            <td>
                <input type="datetime-local" class='form-control'
                    value="<?php echo !empty($db_value['created_at']) ? date('Y-m-d\TH:i', strtotime($db_value['created_at'])) : ''; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Updated At</td>
            <td>
                <input type="datetime-local" name="updated_at" class='form-control'
                    value="<?php echo !empty($db_value['updated_at']) ? date('Y-m-d\TH:i', strtotime($db_value['updated_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type='submit' name='submit' value='Update' class='btn btn-success'>
            </td>
        </tr>

    </table>
</form>
<?
}
else if($_GET['action']=='dispute-edit'){
?>
<form method="post" action='<?= $configs['admin_vpath'] ?>transaction-edit-form?action=dispute-edit&id=<?php echo $_GET['id'] ?>'>

    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td width="400px">Dispute ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['dispute_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Payment ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['payment_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>User ID</td>
            <td>
                <input type="text" class='form-control' value="<?php echo $db_value['user_id']; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Amount</td>
            <td>
                <input type="text" name="amount" class='form-control' value="<?php echo $db_value['amount']; ?>">
            </td>
        </tr>

        <tr>
            <td>Currency</td>
            <td>
                <input type="text" name="currency" class='form-control' value="<?php echo $db_value['currency']; ?>">
            </td>
        </tr>

        <tr>
            <td>Card Brand</td>
            <td>
                <input type="text" name="card_brand" class='form-control' value="<?php echo $db_value['card_brand']; ?>">
            </td>
        </tr>

        <tr>
            <td>Reason</td>
            <td>
                <input type="text" name="reason" class='form-control' value="<?php echo $db_value['reason']; ?>">
            </td>
        </tr>

        <tr>
            <td>State</td>
            <td>
                <select name="state" class="form-control">
                    <option value="OPEN" <?= ($db_value['state'] == 'OPEN') ? 'selected' : '' ?>>OPEN</option>
                    <option value="WON" <?= ($db_value['state'] == 'WON') ? 'selected' : '' ?>>WON</option>
                    <option value="LOST" <?= ($db_value['state'] == 'LOST') ? 'selected' : '' ?>>LOST</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Due At</td>
            <td>
                <input type="datetime-local" name="due_at" class='form-control'
                    value="<?php echo !empty($db_value['due_at']) ? date('Y-m-d\TH:i', strtotime($db_value['due_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td>Square Created At</td>
            <td>
                <input type="datetime-local" class='form-control'
                    value="<?php echo !empty($db_value['square_created_at']) ? date('Y-m-d\TH:i', strtotime($db_value['square_created_at'])) : ''; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Square Updated At</td>
            <td>
                <input type="datetime-local" name="square_updated_at" class='form-control'
                    value="<?php echo !empty($db_value['square_updated_at']) ? date('Y-m-d\TH:i', strtotime($db_value['square_updated_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td>Created At</td>
            <td>
                <input type="datetime-local" class='form-control'
                    value="<?php echo !empty($db_value['created_at']) ? date('Y-m-d\TH:i', strtotime($db_value['created_at'])) : ''; ?>" readonly>
            </td>
        </tr>

        <tr>
            <td>Updated At</td>
            <td>
                <input type="datetime-local" name="updated_at" class='form-control'
                    value="<?php echo !empty($db_value['updated_at']) ? date('Y-m-d\TH:i', strtotime($db_value['updated_at'])) : ''; ?>">
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type='submit' name='submit' value='Update' class='btn btn-success'>
            </td>
        </tr>

    </table>
</form>
<?
}
else if($_GET['action']=='plan-variation-edit'){
?>
<form method="post" action='<?= $configs['admin_vpath'] ?>transaction-edit-form?action=plan-variation-edit&id=<?php echo $_GET['id'] ?>'>

    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td width="400px">Session ID</td>
            <td>
                <input type="text" name="session_id" class='form-control' value="<?php echo $db_value['session_id']; ?>">
            </td>
        </tr>

        <tr>
            <td>Env Name</td>
            <td>
                <input type="text" name="env_name" class='form-control' value="<?php echo $db_value['env_name']; ?>">
            </td>
        </tr>

        <tr>
            <td>Square Plan ID</td>
            <td>
                <input type="text" name="square_plan_id" class='form-control' value="<?php echo $db_value['square_plan_id']; ?>">
            </td>
        </tr>

        <tr>
            <td>Plan Variation ID</td>
            <td>
                <input type="text" name="plan_variation_id" class='form-control' value="<?php echo $db_value['plan_variation_id']; ?>">
            </td>
        </tr>

        <tr>
            <td>Environment</td>
            <td>
                <select name="environment" class="form-control">
                    <option value="sandbox" <?= ($db_value['environment'] == 'sandbox') ? 'selected' : '' ?>>Sandbox</option>
                    <option value="production" <?= ($db_value['environment'] == 'production') ? 'selected' : '' ?>>Production</option>
                </select>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type='submit' name='submit' value='Update' class='btn btn-success'>
            </td>
        </tr>

    </table>
</form>
<?
}
else if($_GET['action']=='add-plan-variation'){
?>

<form method="post" action='<?= $configs['admin_vpath'] ?>transaction-edit-form?action=add-plan-variation'>

    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td width="400px">Session ID</td>
            <td>
                <input type="number" name="session_id" class='form-control'>
            </td>
        </tr>

        <tr>
            <td>Environment Name</td>
            <td>
                <input type="text" name="env_name" class='form-control'>
            </td>
        </tr>

        <tr>
            <td>Square Plan ID</td>
            <td>
                <input type="text" name="square_plan_id" class='form-control'>
            </td>
        </tr>

        <tr>
            <td>Plan Variation ID</td>
            <td>
                <input type="text" name="plan_variation_id" class='form-control'>
            </td>
        </tr>

        <tr>
            <td>Environment</td>
            <td>
                <input type="text" name="environment" class='form-control'>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type='submit' name='submit' value='Submit' class='btn btn-success'>
            </td>
        </tr>

    </table>
</form>
<?
}