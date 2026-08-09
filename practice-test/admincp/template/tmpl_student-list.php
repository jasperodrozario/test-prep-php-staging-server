<br>
<ul class="nav nav-tabs">
    <li <? if ($_GET['status'] == "all" || !$_GET['status']) {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>student-list?reg_at=<?= $_GET['reg_at'] . '&referral_member=' . $_GET['referral_member']; ?>&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>" class='tab' id='All'>All (<?= $all_total; ?>)</a></li>

    <li <? if ($_GET['status'] == "Y") {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>student-list?reg_at=<?= $_GET['reg_at'] . '&referral_member=' . $_GET['referral_member']; ?>&status=Y&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>" class='tab' id='active'>Paid Member (<?= $total_paid; ?>)</a></li>

    <li <? if ($_GET['status'] == "N") {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>student-list?reg_at=<?= $_GET['reg_at'] . '&referral_member=' . $_GET['referral_member']; ?>&status=N&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>" class='tab'>Free Member (<?= $total_free; ?>)</a></li>

    <li <? if ($_GET['status'] == "T") {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>student-list?reg_at=<?= $_GET['reg_at'] . '&referral_member=' . $_GET['referral_member']; ?>&status=T&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>" class='tab'>1-On-1 Tutor (<?= $total_tutor; ?>)</a></li>
</ul>

<div class='clearfix'><br></div>

<form method='get' action="<?= $configs['admin_vpath'] ?>student-list">

    <div class='row'>
        <div class='col-md-4'>
            <input type='text' class='form-control' name='key' placeholder='Find a user by ID, name, or email...' autocomplete="off">
        </div>

        <div class='col-md-2'>
            <input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'>
        </div>

        <div class='col-md-3'>
            <select name='test_id' class='form-control'>
                <option value=''>All Course</option>
                <?
                foreach ($test as $t) {
                    // Build flag label
                    $flags = [];
                    if ($t['subscribe']) $flags[] = 'Subscribe';
                    if ($t['app'])       $flags[] = 'App';
                    if ($t['tutoring'])  $flags[] = 'Tutoring';

                    $flagLabel = count($flags) ? ' - (' . implode(', ', $flags) . ')' : '';

                    // Course label: Course Name [Count] - (Flags)
                    $label = $t['course_name'] . " [" . $student_total[$t['course_id']] . "]" . $flagLabel;

                    // Selected course
                    $selected = ($t['course_id'] == $_GET['test_id']) ? "selected" : "";

                    echo "<option value='" . $t['course_id'] . "' $selected>$label</option>";
                }
                ?>
            </select>
        </div>
        <div class='col-md-3'>
            <div class='row'>
                <div class="col-md-3 form-check">
                    <input class="form-check-input" type="radio" name="reg_at" id="regApp" value="app"
                        <?= isset($_GET['reg_at']) && $_GET['reg_at'] === 'app' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="regApp">App</label>
                </div>
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="radio" name="reg_at" id="regWeb" value="web"
                        <?= isset($_GET['reg_at']) && $_GET['reg_at'] === 'web' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="regWeb">Web</label>
                </div>
                <div class="col-md-3 form-check">
                    <input class="form-check-input" type="radio" name="reg_at" id="regAll" value=""
                        <?= !isset($_GET['reg_at']) || $_GET['reg_at'] === '' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="regAll">All</label>
                </div>
            </div>
        </div>
    </div>
</form>

<br>

<form method='get' action="<?= $configs['admin_vpath'] ?>student-list">
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

<!-- <div style="width: 35%; float: right;">
    <a href="<?= $configs['admin_vpath'] ?>delete-course-student" class="btn btn-info" style="width: 60%; float: right;">Delete Students by Course</a>
</div> -->

<form method="post" id="userViewForm" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
    <div style="float: right;">
        <button type="submit" class="btn btn-success" name="deleteMultiUser">Delete Selected User</button>
    </div>

    <br><br><br>
    <div style="overflow-x:auto;">
        <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
            <thead>
                <tr>
                    <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                    <td width="5%" align="center"><b>Id</b></td>
                    <td align="center"><b>Name</b></td>
                    <td align="center"><b>Course</b></td>
                    <td align="center"><b>Reg at</b></td>
                    <td align="center"><b>Paid</b></td>
                    <td width="12%" align="center"><b>Reg. Date</b></td>
                    <td width="10%" align="center"><b>Exp. Date</b></td>
                    <td width="10%" align="center"><b>Subscription Date</b></td>
                    <td width="10%" align="center"><b>FPT</b></td>
                    <td width="10%" align="center"><b>Pre. Test</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($db_list as $d) {
                    if ($d['activation_status'] == "Y") {
                        $paid = "Yes";
                    } else {
                        $paid = "<font color=red>No</font>";
                    }
                ?>
                    <tr>
                        <td><input type='checkbox' name='user_ids[]' value='<?= $d['user_id']; ?>' class='checkBoxClass'></td>
                        <!--                    <td align="left"><input type='checkbox' name='user_ids[]' value='<?= $d['user_id']; ?>'></td>-->
                        <td align="left"><?= $d['user_id']; ?></td>
                        <td><?php echo ucwords($d['first_name'] . " " . $d['last_name']); ?><br>
                            <?php echo $d['email']; ?><br>
                            Mobile : <?php echo $d['mobile']; ?>
                        </td>
                        <td align="center"><?php echo $test_name[$d['course_id']]; ?></td>
                        <td align="center"><?php echo $d['reg_at']; ?></td>
                        <td align="center"><?php echo $paid; ?></td>
                        <td align="center"><?php echo showdate($d['reg_date']); ?></td>
                        <td align="center">
                            <?php if (!empty($d['expire_date'])): ?>

                                <?php
                                $subscriptionDate = strtotime($d['expire_date']);
                                $isExpired = $subscriptionDate < time();
                                ?>

                                <span>
                                    <?php echo showdate($d['expire_date']); ?>
                                </span>

                                <?php if ($isExpired): ?>
                                    <span style="
                                        padding: 4px 10px;
                                        border-radius: 20px;
                                        font-size: 13px;
                                        font-weight: bold;
                                        color: #dc3545;
                                        background-color: #f8d7da;
                                        margin-right: 6px;
                                    ">
                                        Expired
                                    </span>
                                <?php endif; ?>

                            <?php endif; ?>
                        </td>
                        <td align="center"><?php echo showdate($d['subscription_date']); ?></td>
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
                        <td align="center">
                            <a href="student-reg?user_id=<?php echo $d['user_id']; ?>">Edit</a> | 
                            <a href="<?= $configs['vpath'] ?>login-for-admin/<?php echo $d['email']; ?>/<?php echo $d['password']; ?>" target='_new'>View User</a>
                            |&nbsp; <a href='javascript://' onClick="javascript:if(confirm('Are you deleting')){window.location='student-list?delete_id=<?= $d['user_id'] . "&amp;reg_at=" . $_GET['reg_at'] . "&limit=" . $_REQUEST['limit']; ?>'};">Delete</a>
                        </td>
                    </tr>
                <?php
                }

                ?>
            </tbody>
        </table>
    </div>
</form>
<? if ($total > 100) {
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