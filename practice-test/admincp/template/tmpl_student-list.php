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

    <? if (!$_GET['referral_member']) { ?>
    <li <? if ($_GET['status'] == "testing") {
            echo 'class="active"';
        } ?>><a href="<?= $configs['admin_vpath'] ?>student-list?reg_at=<?= $_GET['reg_at']; ?>&status=testing&test_id=<?= $_GET['test_id']; ?>&key=<?= $_GET['key']; ?>" class='tab'><i class="fa fa-flask"></i> Tester Accounts (<span id="testerTabCount"><?= (int) $total_testing; ?></span>)</a></li>
    <? } ?>
</ul>

<div class='clearfix'><br></div>

<? if (!empty($testing_flash)) { ?>
    <div class="alert alert-<?= $testing_flash[0] ?>" style="margin-bottom: 15px;"><?= htmlspecialchars($testing_flash[1]) ?></div>
<? } ?>

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
                    $isTesting = isset($testingIds[(int) $d['user_id']]);
                ?>
                    <tr<?= $isTesting ? ' class="testing-account-row"' : '' ?>>
                        <td><input type='checkbox' name='user_ids[]' value='<?= $d['user_id']; ?>' class='checkBoxClass'></td>
                        <!--                    <td align="left"><input type='checkbox' name='user_ids[]' value='<?= $d['user_id']; ?>'></td>-->
                        <td align="left"><?= $d['user_id']; ?></td>
                        <td class="student-name-cell"><?php echo ucwords($d['first_name'] . " " . $d['last_name']); ?>
                            <?php if ($isTesting) { ?><span class="testing-badge" title="Payments from this account are excluded from revenue"><i class="fa fa-flask"></i> TESTER</span><?php } ?><br>
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
                            <?php if ($canMarkTesting) { ?>
                                <br>
                                <?php if ($isTesting) { ?>
                                    <a href="javascript://" class="testing-toggle unmark" onclick="setTestingAccount(<?= (int) $d['user_id'] ?>, 0, this)"><i class="fa fa-undo"></i> Unmark Tester</a>
                                <?php } else { ?>
                                    <a href="javascript://" class="testing-toggle" onclick="setTestingAccount(<?= (int) $d['user_id'] ?>, 1, this)"><i class="fa fa-flask"></i> Mark Tester</a>
                                <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php
                }

                ?>
            </tbody>
        </table>
    </div>
</form>

<?php if ($canMarkTesting) {
    $testingReturn = $_GET;
    unset($testingReturn['testing_msg'], $testingReturn['testing_uid'], $testingReturn['home_limit']);
?>
    <form method="post" action="student-list" id="testingAccountForm" style="display:none;">
        <input type="hidden" name="action" value="set_testing">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($testing_csrf) ?>">
        <input type="hidden" name="user_id" value="">
        <input type="hidden" name="is_testing" value="">
        <input type="hidden" name="return_qs" value="<?= htmlspecialchars(http_build_query($testingReturn)) ?>">
    </form>
    <script>
        function setTestingAccount(userId, isTesting, link) {
            // Marking happens straight away; unmarking asks first
            if (!isTesting && !confirm('Unmark student #' + userId + ' as a tester account?\n\nIts payments will be counted in revenue reports again.')) return;
            var form = document.getElementById('testingAccountForm');
            form.elements.user_id.value = userId;
            form.elements.is_testing.value = isTesting ? '1' : '0';

            // Save in the background; the page never reloads, so scroll position, filters and paging stay as they are
            var body = new FormData(form);
            body.append('ajax', '1');
            var originalHtml = link.innerHTML;
            link.style.pointerEvents = 'none';
            link.style.opacity = '0.5';
            link.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving…';

            // getAttribute: form.action would return the hidden <input name="action"> instead of the URL
            fetch(form.getAttribute('action'), { method: 'POST', body: body, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) {
                    if (r.redirected && /\/login(\?|$)/.test(r.url)) {
                        throw new Error('Your admin session has expired. Please log in again in another tab and retry. Nothing was changed.');
                    }
                    if (!r.ok) {
                        throw new Error('The server returned an error (HTTP ' + r.status + '). Nothing was changed. Please try again.');
                    }
                    return r.json().catch(function () {
                        throw new Error('The server sent an unexpected response. Nothing was changed. Please reload the page and try again.');
                    });
                })
                .then(function (res) {
                    link.style.pointerEvents = '';
                    link.style.opacity = '';
                    link.innerHTML = originalHtml;
                    // Only reflect what the server confirmed is saved in the database
                    if (res.is_testing === true || res.is_testing === false) {
                        updateTesterRow(userId, res.is_testing, link, res.changed);
                    }
                    showTesterToast(res.ok ? 'success' : 'error', res.message);
                })
                .catch(function (err) {
                    link.style.pointerEvents = '';
                    link.style.opacity = '';
                    link.innerHTML = originalHtml;
                    // Our own errors carry a message; a network failure is a TypeError from fetch
                    showTesterToast('error', err && err.message && err.message.indexOf('Nothing was changed') !== -1
                        ? err.message
                        : 'Couldn\'t reach the server. Nothing was changed. Please check your connection and try again.');
                });
        }

        function showTesterToast(type, message) {
            var box = document.getElementById('testerToastBox');
            if (!box) {
                box = document.createElement('div');
                box.id = 'testerToastBox';
                document.body.appendChild(box);
            }
            var toast = document.createElement('div');
            toast.className = 'tester-toast ' + (type === 'success' ? 'success' : 'error');
            toast.setAttribute('role', type === 'success' ? 'status' : 'alert');
            toast.innerHTML = '<i class="fa ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + '"></i> ';
            toast.appendChild(document.createTextNode(message));
            box.appendChild(toast);
            setTimeout(function () {
                toast.classList.add('hide');
                setTimeout(function () { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 300);
            }, type === 'success' ? 3500 : 6000);
        }

        function updateTesterRow(userId, isTesting, link, changed) {
            var row = link.closest('tr');
            row.classList.toggle('testing-account-row', isTesting);

            var nameCell = row.querySelector('.student-name-cell');
            var badge = nameCell.querySelector('.testing-badge');
            if (isTesting && !badge) {
                badge = document.createElement('span');
                badge.className = 'testing-badge';
                badge.title = 'Payments from this account are excluded from revenue';
                badge.innerHTML = '<i class="fa fa-flask"></i> TESTER';
                nameCell.insertBefore(badge, nameCell.querySelector('br'));
            } else if (!isTesting && badge) {
                badge.parentNode.removeChild(badge);
            }

            link.className = isTesting ? 'testing-toggle unmark' : 'testing-toggle';
            link.innerHTML = isTesting ? '<i class="fa fa-undo"></i> Unmark Tester' : '<i class="fa fa-flask"></i> Mark Tester';
            link.onclick = function () { setTestingAccount(userId, isTesting ? 0 : 1, link); };

            var count = document.getElementById('testerTabCount');
            if (count && changed) {
                count.textContent = Math.max(0, (parseInt(count.textContent, 10) || 0) + (isTesting ? 1 : -1));
            }
        }
    </script>
<?php } ?>

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

    .testing-badge {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        color: #8a5a00;
        background-color: #ffe08a;
        vertical-align: middle;
    }

    .testing-toggle {
        display: inline-block;
        margin-top: 4px;
        color: #000;
        font-weight: 600;
    }

    #testerToastBox {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        max-width: 360px;
    }

    .tester-toast {
        margin-bottom: 10px;
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 13px;
        line-height: 1.5;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        opacity: 1;
        transition: opacity 0.3s;
    }

    .tester-toast.success {
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }

    .tester-toast.error {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .tester-toast.hide {
        opacity: 0;
    }
</style>