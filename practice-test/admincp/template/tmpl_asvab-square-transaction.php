<br>
<form method='post' action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">
    <div class='row'>
        <div class='col-md-8'>
            <input type='text' class='form-control' name='key' placeholder='Search By User ID / Email Id' autocomplete="off">
        </div>
        <div class='col-md-2'>
            <input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'>
        </div>
        <div class='col-md-2'>
            <select name='course' class='form-control' onchange='window.location=this.value'>
                <option value='<?= $configs['admin_path']; ?>asvab-square-transaction'>All Transections</option>
                <?php foreach ($course_list as $t) { ?>
                    <?php if ($t['test_name'] == $_GET['test_name']) { ?>
                        <option value='<?= $configs['admin_path']; ?>asvab-square-transaction?test_name=<?= $t['test_name']; ?>' selected>
                            <?= $t['test_name']; ?>
                        </option>
                    <?php } else { ?>
                        <option value='<?= $configs['admin_path']; ?>asvab-square-transaction?test_name=<?= $t['test_name']; ?>'>
                            <?= $t['test_name']; ?> 
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
                <th align="center">Payment ID</th>
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
                    <td class="vertical-middle"><?php echo showdate($d['created_at']); ?></td>
                    <td class="vertical-middle">
                        <a href='mem_regform.php?user_id=<?php echo $d['user_id']; ?>'>
                            <?php echo $d['first_name']." ". $d['last_name']; ?>
                        </a>
                    </td>
                    <td class="vertical-middle" style='text-align:right'>
                        $<?php echo $d['amount']." ".$d['currency']; ?>
                    </td>
                    <td class="vertical-middle"><?php echo $d['test_name']; ?></td>
                    <td class="vertical-middle"><?php echo $d['payment_id']; ?></td>
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