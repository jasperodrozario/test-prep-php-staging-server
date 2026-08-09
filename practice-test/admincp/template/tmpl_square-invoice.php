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
                <th>Invoice Info</th>
                <th style="text-align:right">Amount</th>
                <th>Status</th>
                <th>Payment Dates</th>
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
                        <small>Course:</small> <?php echo $course_name; ?>
                    </td>

                    <td>
                        <small>Invoice:</small> <?php echo $d['invoice_id']; ?><br>
                        <small>Subs:</small> <?php echo $d['subscription_id']; ?><br>
                        <small>Sqr Customer ID:</small> <?php echo $d['square_customer_id']; ?>
                    </td>

                    <td style="text-align:right">
                        <strong>$<?php echo number_format($d['amount'], 2); ?></strong>
                    </td>

                    <td>
                        <span class="badge badge-<?php
                                                    if ($d['status'] == 'PAID') echo 'success';
                                                    elseif ($d['status'] == 'FAILED') echo 'danger';
                                                    else echo 'warning';
                                                    ?>">
                            <?php echo ucfirst(strtolower($d['status'])); ?>
                        </span>
                    </td>

                    <td>
                        <?php if ($d['paid_at']) { ?>
                            <small>Paid:</small> <?php echo date("d M Y", strtotime($d['paid_at'])); ?><br>
                        <?php } ?>

                        <?php if ($d['failed_at']) { ?>
                            <small>Failed:</small> <?php echo date("d M Y", strtotime($d['failed_at'])); ?>
                        <?php } ?>
                    </td>

                    <!-- <td align="center">
                        <?php
                        echo "<a  href='transaction-edit-form?action=invoice-edit&id=" . $d['id'] . "'><u>Edit</u></a> | ";

                        echo "<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $d['invoice_id'] . "`?')){window.location.href='square-invoice?del_id=" . $d['id'] . "'}\"><u>Delete</u></a>";
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