<br>
<form method='post' action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">
    <div class='row'>
        <div class='col-md-8'>
            <input type='text' class='form-control' name='key' placeholder='Search By User ID/ Email ID/ Phone/ Name' autocomplete="off">
        </div>
        <div class='col-md-2'>
            <input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'>
        </div>
        <!-- <div class='col-md-2'>
            <select name='course' class='form-control' onchange='window.location=this.value'>
                <option value='<?= $configs['admin_path']; ?>transactions_list'>All Transections</option>
                <?php foreach ($course_list as $t) { ?>
                    <?php if ($t['course_id'] == $_GET['course_id']) { ?>
                        <option value='<?= $configs['admin_path']; ?>transactions_list?course_id=<?= $t['course_id']; ?>' selected>
                            <?= $t['course_name']; ?>
                        </option>
                    <?php } else { ?>
                        <option value='<?= $configs['admin_path']; ?>transactions_list?course_id=<?= $t['course_id']; ?>'>
                            <?= $t['course_name']; ?> 
                        </option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div> -->
    </div>
</form>

<br>

<div class="table-responsive" id="scrollable-table">
    <table class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
                <th width="4%">ID</th>
                <th width="6%">User ID</th>
                <th>User</th>
                <th>Payment Info</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
                <!-- <th>Action</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($db_list as $d) { ?>
                <?php
                $course_id = getFldValueById("subscription", "course_id", array("user_id" => $d['user_id']));
                $course_name = getFldValueById("course","course_name",array("course_id"=>$course_id));
                ?>
                <tr>

                    <td><?php echo $d['id']; ?></td>

                    <td><?php echo $d['user_id']; ?></td>

                    <td>
                        <strong><?php echo $d['first_name'] . " " . $d['last_name']; ?></strong><br>
                        <small><?php echo $d['email']; ?></small><br>
                        <small>Course: <?php echo $course_name; ?></small>
                    </td>

                    <td>
                        <small>Payment ID:</small> <?php echo $d['payment_id']; ?><br>
                        <small>Order ID:</small> <?php echo $d['order_id']; ?><br>
                        <small>Sqr Customer ID:</small> <?php echo $d['square_customer_id']; ?>
                    </td>

                    <td style="text-align:right">
                        <strong>$<?php echo number_format(($d['amount']/100), 2); ?></strong><br>
                        <small><?php echo $d['currency']; ?></small>
                    </td>

                    <td>
                        <span class="badge badge-<?php echo ($d['payment_status'] == 'COMPLETED') ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst(strtolower($d['payment_status'])); ?>
                        </span>
                    </td>

                    <td>
                        <?php echo date("d M Y", strtotime($d['created_at'])); ?><br>
                        <small><?php echo date("h:i A", strtotime($d['created_at'])); ?></small>
                    </td>

                    <!-- <td align="center">
                        <?php
                        echo "<a  href='transaction-edit-form?action=payments-edit&id=" . $d['id'] . "'><u>Edit</u></a> | ";

                        echo "<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $d['payment_id'] . "`?')){window.location.href='square-payments?del_id=" . $d['id'] . "'}\"><u>Delete</u></a>";
                        ?>
                    </td> -->

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
if ($total > 100) {
    echo "<ul class='pagination pull-right'>" . paging($total, $param) . "</ul>";
}
?>

<style>
    /* CSS for making the table scrollable horizontally */
    #scrollable-table {
        overflow-x: auto;
        white-space: nowrap;
    }

    .badge-success {
        background: #28a745;
        color: #fff;
        padding: 5px 8px;
        border-radius: 4px;
    }

    .badge-danger {
        background: #dc3545;
        color: #fff;
        padding: 5px 8px;
        border-radius: 4px;
    }

    .badge-warning {
        background: #ffc107;
        color: #000;
        padding: 5px 8px;
        border-radius: 4px;
    }
</style>