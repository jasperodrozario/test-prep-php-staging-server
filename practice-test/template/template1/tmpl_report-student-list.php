<div class="main-body-div">

    <!-- <ul  class="nav nav-tabs">      
        <li <? if ($_GET['status'] == "all" || !$_GET['status']) {
                echo 'class="active"';
            } ?>><a href="<?= $configs['vpath'] ?>report-student-list?reg_at=<?= $_GET['reg_at']; ?>&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>"  class='tab' id='All'>All (<?= $all_total; ?>)</a></li>

        <li <? if ($_GET['status'] == "Y") {
                echo 'class="active"';
            } ?>><a  href="<?= $configs['vpath'] ?>report-student-list?reg_at=<?= $_GET['reg_at']; ?>&status=Y&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>"   class='tab' id='active'>Paid Member (<?= $total_active; ?>)</a></li>  

        <li <? if ($_GET['status'] == "N") {
                echo 'class="active"';
            } ?>><a  href="<?= $configs['vpath'] ?>report-student-list?reg_at=<?= $_GET['reg_at']; ?>&status=N&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>"  class='tab'>Free Member (<?= $total_inactive; ?>)</a></li> 

        <li <? if ($_GET['status'] == "T") {
                echo 'class="active"';
            } ?>><a  href="<?= $configs['vpath'] ?>report-student-list?reg_at=<?= $_GET['reg_at']; ?>&status=T&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>"  class='tab'>1-On-1 Tutor (<?= $total_free_active; ?>)</a></li> 
    </ul> -->

    <div class='clearfix'><br></div>

    <form method='get' action="<?= $configs['vpath'] ?>report-student-list">

        <div class='row'>
            <div class='col-md-3'>
                <input type='text' class='form-control' name='key' placeholder='Search By First Name / Last Name / Email ID' autocomplete="off" value="<?= isset($_GET['key']) ? htmlspecialchars($_GET['key']) : ''; ?>">

            </div>

            <div class='col-md-2'>
                <select name='reg_at' class='form-control'>
                    <option value='all' <?= isset($_GET['reg_at']) && $_GET['reg_at'] === 'all' ? 'selected' : ''; ?>>All Platforms</option>
                    <option value='app' <?= isset($_GET['reg_at']) && $_GET['reg_at'] === 'app' ? 'selected' : ''; ?>>App</option>
                    <option value='subscribe' <?= isset($_GET['reg_at']) && $_GET['reg_at'] === 'subscribe' ? 'selected' : ''; ?>>Subscription</option>
                    <option value='tutoring' <?= isset($_GET['reg_at']) && $_GET['reg_at'] === 'tutoring' ? 'selected' : ''; ?>>Tutoring</option>
                </select>
            </div>

            <div class='col-md-2'>
                <select name='test_id' class='form-control'>
                    <option value=''>All Course</option>
                    <?
                    foreach ($test as $t) {
                        if ($t['course_id'] == $_GET['test_id']) {
                            echo "<option value='" . $t['course_id'] . "' selected>" . $t['course_name'] . " [" . $student_total[$t['course_id']] . "]</option>";
                        } else {
                            echo "<option value='" . $t['course_id'] . "'>" . $t['course_name'] . " [" . $student_total[$t['course_id']] . "]</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class='col-md-3'>
                <div class='row'>
                    <div class="col-md-3 form-check">
                        <input class="form-check-input" type="radio" name="payment_status" id="paid" value="paid"
                            <?= isset($_GET['payment_status']) && $_GET['payment_status'] === 'paid' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="paid">Paid</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input class="form-check-input" type="radio" name="payment_status" id="unpaid" value="unpaid"
                            <?= isset($_GET['payment_status']) && $_GET['payment_status'] === 'unpaid' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="unpaid">Unpaid</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input class="form-check-input" type="radio" name="payment_status" id="all" value="all"
                            <?= isset($_GET['payment_status']) && $_GET['payment_status'] === 'all' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="all">All</label>
                    </div>
                </div>
            </div>

            <div class='col-md-2'>
                <input type='submit' style='width:100%' class='btn btn-warning pull-right' value='Search'>
            </div>
        </div>
    </form>

    <div class='clearfix'><br></div>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
        <thead style="background-color: #3ABEF9">
            <tr>
                <td width="5%" align="center"><b>ID</b></td>
                <td align="center"><b>Name</b></td>
                <td align="center"><b>Course</b></td>
                <td align="center"><b>Reg At</b></td>
                <td align="center"><b>Paid</b></td>
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
                    <td align="left"><?= $d['user_id']; ?></td>
                    <td><?php echo ucwords($d['first_name'] . " " . $d['last_name']); ?><br>
                        <?php echo $d['email']; ?><br>
                        Mobile : <?php echo $d['mobile']; ?>
                    </td>
                    <td align="center"><?php echo $test_name[$d['course_id']]; ?></td>
                    <td align="center"><?php echo $d['reg_at']; ?></td>
                    <td align="center"><?php echo $paid; ?></td>
                    <td align="center">
                        <?php echo ($d['fp'] == 1)
                            ? "<i class='fa fa-check' style='color:green; font-size: 18px;'></i>"
                            : "<i class='fa fa-times' style='color:red; font-size: 18px;'></i>";
                        ?>
                    </td>
                    <td align="center">
                        <?php echo ($d['pt'] == 1)
                            ? "<i class='fa fa-check' style='color:green; font-size: 18px;'></i>"
                            : "<i class='fa fa-times' style='color:red; font-size: 18px;'></i>";
                        ?>
                    </td>

                    <!-- <td align="center"><a class="report-link" href="<?= $configs['vpath'] ?>tutor-view-report/<?= $d['user_id']; ?>/progress-report" target='_new'>View Progress Report</a></td> -->

                    <td align="center"><a href="<?= $configs['vpath'] ?>login/<?php echo $d['email']; ?>/<?php echo $d['password']; ?>" target='_new'>View Progress Report</a></td>

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <? if ($total > 20) {
        echo "<ul class='pagination'>" . paging_page($configs['vpath'] . "report-student-list", $total, "", $param) . "</ul>";
    } ?>

</div>

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
    .main-body-div {
        margin: 50px;
        margin-top: 90px;
        padding: 15px;
    }

    .report-link:hover {
        color: inherit;
        text-decoration: underline;
        /* Optionally, you can underline the text on hover */
    }
</style>

<style>
    a:hover {
        color: blue !important;
    }
</style>