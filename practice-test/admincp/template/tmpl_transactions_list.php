<form method='post' action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">
    <div class='row'>
        <div class='col-md-8'>
            <input type='text' class='form-control' name='key' placeholder='Search By User ID / Email Id / Phone' autocomplete="off">
        </div>
        <div class='col-md-2'>
            <input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'>
        </div>
        <div class='col-md-2'>
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
        </div>
    </div>
</form>

<br>		 

<div class="table-responsive" id="scrollable-table">
    <table class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
                <th width="3%">T.ID</th>
                <th width="6%">User Id</th>
                <th width="7%">User Email</th>
                <th>Trans. Date</th>
                <th align="center">Name</th>
                <th style='text-align:right'>Amount</th>
                <th>Course</th>
                <th>Duration</th>
                <th align="center" width="5%">Payer Email</th>
                <th align="center">Trans Id</th>
                <th align="center">Payment Type</th>
                <!-- Add more th elements for additional columns -->
            </tr>
        </thead>
        <tbody>
            <?php foreach($db_list as $d) { ?>
                <?php
                    $course_name = getFldValueById("course","course_name",array("course_id"=>$d['course_id']));
                    $duration = getFldValueById("package","duration",array("package_id"=>$d['package_id']));
                ?>
                <tr>
                    <td><?php echo $d['id']; ?></td>
                    <td><?php echo $d['user_id']; ?></td>
                    <td><?php echo $d['email']; ?></td>
                    <td class="vertical-middle"><?php echo showdate($d['subscription_date']); ?></td>
                    <td class="vertical-middle">
                        <a href='mem_regform.php?user_id=<?php echo $d['user_id']; ?>'>
                            <?php echo $d['first_name']." ". $d['last_name']; ?>
                        </a>
                    </td>
                    <td class="vertical-middle" style='text-align:right'>
                        $<?php echo $d['amount']." ".$d['currency']; ?>
                    </td>
                    <td class="vertical-middle"><?php echo $course_name; ?></td>
                    <td class="vertical-middle"><?php echo $duration; ?></td>
                    <td class="vertical-middle"><?php echo $d['paypal_id']; ?></td>
                    <td class="vertical-middle"><?php echo $d['txn_id']; ?></td>
                    <td align="center" class="vertical-middle"><?php echo $d['payment_mode']; ?></td>
                    <!-- Add more td elements for additional columns -->
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
if($total > 100) {
    echo "<ul class='pagination pull-right'>".paging($total, $param) ."</ul>";
}
?>

<style>
/* CSS for making the table scrollable horizontally */
#scrollable-table {
    overflow-x: auto;
    white-space: nowrap;
}
</style>