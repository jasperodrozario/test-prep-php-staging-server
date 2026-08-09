<br>

<ul class="nav nav-tabs">
    <li <? if ($_GET['status'] == "all" || !$_GET['status']) {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>course-wise-student-list?course=<?= $_GET['course'] . '&platform=' . $_GET['platform']; ?>&status=all&key=<?= $_GET['key']; ?>" class='tab' id='All'>All (<?= $total; ?>)</a></li>

    <li <? if ($_GET['status'] == "Y") {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>course-wise-student-list?course=<?= $_GET['course'] . '&platform=' . $_GET['platform']; ?>&status=Y&key=<?= $_GET['key']; ?>" class='tab' id='active'>Active (<?= $total_active_student; ?>)</a></li>

    <li <? if ($_GET['status'] == "N") {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>course-wise-student-list?course=<?= $_GET['course'] . '&platform=' . $_GET['platform']; ?>&status=N&key=<?= $_GET['key']; ?>" class='tab'>Inactive (<?= $total_inactive_student; ?>)</a></li>
</ul>

<div class='clearfix'><br></div>

<form method='get' action="<?= $configs['admin_vpath'] ?>course-wise-student-list">

    <div class='row'>
        <div class='col-md-8'>
            <input type='text' class='form-control' name='key' placeholder='Search By First Name / Last Name /Email Id' autocomplete="off">
        </div>

        <div class='col-md-3'>
            <input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'>
        </div>
    </div>
</form>

<br>

<form method='get' action="<?= $configs['admin_vpath'] ?>course-wise-student-list">
    <!-- keep existing filters -->
    <input type="hidden" name="test_id" value="<?= $_GET['test_id'] ?? '' ?>">
    <input type="hidden" name="status" value="<?= $_GET['status'] ?? '' ?>">
    <input type="hidden" name="key" value="<?= $_GET['key'] ?? '' ?>">
    <input type="hidden" name="reg_at" value="<?= $_GET['reg_at'] ?? '' ?>">

    <div class="row">

        <div class="col-md-4">
            <label><b>From Date</b></label>
            <input type="date" name="from_date" class="form-control"
                value="<?= $_GET['from_date'] ?? '' ?>">
        </div>

        <div class="col-md-4">
            <label><b>To Date</b></label>
            <input type="date" name="to_date" class="form-control"
                value="<?= $_GET['to_date'] ?? '' ?>">
        </div>

        <div class="col-md-3" style="display:flex; align-items:end;">
            <button type="submit" name="download_csv" value="1"
                class="btn btn-success" style="margin-top: 30px;">
                ⬇ Download Student List (CSV)
            </button>
        </div>
    </div>

    <small style="color:#888;">
        * If no date is selected, all students will be downloaded.
    </small>
</form>


<div class='clearfix'><br></div>

<form method="post" id="userViewForm" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">

    <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
        <thead>
            <tr>
                <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                <td width="5%" align="center"><b>Id</b></td>
                <td align="center"><b>Name</b></td>
                <td width="10%" align="center"><b>Final Test</b></td>
                <td width="10%" align="center"><b>Prediction Test</b></td>
                <td width="10%" align="center"><b>Reg. Date</b></td>
                <td align="center"><b>Activation Status</b></td>
                <td width="20%" align="center"><b>Action</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($db_list as $d) {
                if ($d['activation_status'] == "Y") {
                    $paid = "<font color=green><b>Yes</b></font>";
                } else {
                    $paid = "<font color=red><b>No</b></font>";
                }
            ?>
                <tr>
                    <td><input type='checkbox' name='user_ids[]' value='<?= $d['user_id']; ?>' class='checkBoxClass'></td>
                    <td align="left"><?= $d['user_id']; ?></td>
                    <td><?php echo ucwords($d['first_name'] . " " . $d['last_name']); ?><br>
                        <?php echo $d['email']; ?><br>
                        Mobile : <?php echo $d['mobile']; ?>
                    </td>
                    <td align="center">
                        <?php echo ($d['fp'] == 1)
                            ? "<i class='fa fa-check' style='color:green; font-size: 18px;'></i>"
                            : "<i class='fa fa-times' style='color:red; font-size: 18px;'></i>"; ?>
                    </td>
                    <td align="center">
                        <?php echo ($d['pt'] == 1)
                            ? "<i class='fa fa-check' style='color:green; font-size: 18px;'></i>"
                            : "<i class='fa fa-times' style='color:red; font-size: 18px;'></i>"; ?>
                    </td>
                    <td align="center"><?php echo showdate($d['reg_date']); ?></td>
                    <td align="center"><?php echo $paid; ?></td>
                    <td align="center"><a href="student-reg?user_id=<?php echo $d['user_id']; ?>">Edit</a> | <a href="<?= $configs['vpath'] ?>login-for-admin/<?php echo $d['email']; ?>/<?php echo $d['password']; ?>" target='_new'>View User</a>
                        |&nbsp; <a href='javascript://' onClick="javascript:if(confirm('Are you deleting')){window.location='course-wise-student-list?delete_id=<?= $d['user_id'] . "&course=".$_GET['course']."&platform=" . $_GET['platform'] . "&limit=" . $_REQUEST['limit']; ?>'};">Delete</a>
                    </td>
                </tr>
            <?php
            }

            ?>
        </tbody>
    </table>
</form>
<? if ($total > 20) {
    echo "<ul class='pagination'>" . paging($total, "", $param) . "</ul>";
} ?>

<script>
    $("#ckbCheckAll").click(function() {
        $(".checkBoxClass").prop('checked', $(this).prop('checked'));
    });
</script>
<script>
    $(document).ready(function() {
        // Auto-submit when dropdown changes
        $('select[name="test_id"]').change(function() {
            $(this).closest('form').submit();
        });

        // Auto-submit when radio button changes
        $('input[name="reg_at"]').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
<style>
    .inline-radio-group .form-check-inline {
        margin-right: 15px;
        /* Adjust spacing as needed */
    }
</style>