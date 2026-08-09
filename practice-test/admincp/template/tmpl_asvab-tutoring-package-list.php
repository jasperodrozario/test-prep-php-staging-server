<?
echo "<br>";
switch ($action) {
    case "package-list":
?>
        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID/Ord</b></td>
                    <td align="center"><b>Tutoring Package Name</b></td>
                    <td align="center"><b>Number of Sessions</b></td>
                    <td align="center"><b>Online</b></td>
                    <td align="center"><b>Tutoring Type</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                $types = [
                    1 => "Group Tutoring",
                    0 => "1-On-1 Tutoring",
                    2 => "Subscribe"
                ];

                foreach ($course_list as $d) {
                    echo "
            <tr style='background-color: #1ABC9C;'>
                <td align='center'>" . $d['course_id'] . "/" . $d['ord'] . "</td>
                <td>" . ucwords($d['name']) . "</td>
                <td align='center'>-</td>
                <td align='center'>-</td>
                <td align='center'>-</td>
                <td align='center'>-</td>
            </tr>";
                    foreach ($db_list[$d['course_id']] as $dd) {
                        echo "
                <tr>
                    <td align='center'>" . $dd['package_id'] . "/" . $dd['ord'] . "</td>
                    <td> <a  href='asvab-tutoring-package-list?action=session-package-list&package_id=" . $dd["package_id"] . "'><u>" . $dd['title'] . "</u></a></td>
                    <td align='center'>" . $dd['num_of_sessions'] . "</td>
                    <td align='center'><span style='font-weight:bold;'>" . (($dd['is_online'] == 'Y') ? "Yes" : "No") . "</span></td>
                    <td align='center'><span style='font-weight:bold;'>" . $types[$dd['is_group_tutoring']] . "</span></td>
                    <td align='center'> 
                        <a   href='asvab-tutoring-package-list?action=add-package&package_id=" . $dd['package_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                        <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-tutoring-package-list?action=package-list&del_package_id=" . $dd['package_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                    </td>
                </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

    <?
        break;

    case "add-package":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-tutoring-package-list?action=add-package' enctype="multipart/form-data">
            <input type="hidden" name="package_id" value="<?php echo $_GET['package_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="20%">Tutoring Package Name</td>
                    <td><input type="text" name="title" class='form-control' value="<?php echo $db_value['title']; ?>" required></td>
                </tr>

                <tr>
                    <td style="vertical-align: middle;">Course Name</td>
                    <td>
                        <select name='course_id' id='course_id' size=1 class='form-control' required>
                            <option value='0'>Select a Course</option>
                            <?
                            foreach ($course_list as $dd) {
                                if ($db_value['course_id'] == $dd['course_id']):
                                    echo "<option value=" . $dd['course_id'] . " selected>" . $dd['name'] . "</option>";
                                else:
                                    echo "<option value=" . $dd['course_id'] . ">" . $dd['name'] . "</option>";
                                endif;
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="left">Description</td>
                    <td>
                        <textarea name="details" id="details" class="form-control" rows="5"><?php echo $db_value['description']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Code Name</td>
                    <td><input type="text" name="code_name" class='form-control' value="<?php echo $db_value['code_name']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Is Online</td>
                    <td>
                        <label>
                            <input type="radio" name="is_online" value="Y" <?php echo ($db_value['is_online'] == 'Y') ? 'checked' : ''; ?>> Yes
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="is_online" value="N" <?php echo ($db_value['is_online'] == 'N') ? 'checked' : ''; ?>> No
                        </label>
                    </td>

                </tr>

                <tr>
                    <td align="left">Tutoring Type</td>
                    <td>
                        <label>
                            <input type="radio" name="is_group_tutoring" value="1" <?php echo (isset($db_value['is_group_tutoring']) && $db_value['is_group_tutoring'] == 1) ? 'checked' : ''; ?>> Group Tutoring
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="is_group_tutoring" value="0" <?php echo (isset($db_value['is_group_tutoring']) && $db_value['is_group_tutoring'] == 0) ? 'checked' : ''; ?>> 1-On-1 Tutoring
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="is_group_tutoring" value="2" <?php echo (isset($db_value['is_group_tutoring']) && $db_value['is_group_tutoring'] == 2) ? 'checked' : ''; ?>> Subscribe
                        </label>
                    </td>
                </tr>


                <tr>
                    <td align="left">Number of Sessions</td>
                    <td><input type="number" name="num_of_sessions" class='form-control' value="<?php echo $db_value['num_of_sessions']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Order</td>
                    <td><input type="number" name="ord" class='form-control' value="<?php echo $db_value['ord']; ?>"></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='submit' value='Save' class='btn btn-success'>
                    </td>
                </tr>
            </table>
        </form>
    <?
        break;

    case "session-package-list":
    ?>
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3" style="color:#007bff;"><b><?php echo $tutoring_package['title']; ?></b></h4>
                <div class="row">
                    <div class="col-md-4">
                        <p><b>Course:</b> <?php echo $course['name']; ?></p>
                    </div>
                    <div class="col-md-4">
                        <p><b>Online:</b>
                            <span class="badge <?php echo ($tutoring_package['is_online'] == 'Y') ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo ($tutoring_package['is_online'] == 'Y') ? 'Yes' : 'No'; ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p><b>Number of Sessions:</b> <?php echo $tutoring_package['num_of_sessions']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID</b></td>
                    <td align="center"><b>Tutoring Session Name</b></td>
                    <td align="center"><b>Price</b></td>
                    <td align="center"><b>Feature</b></td>
                    <td align="center"><b>Is Active</b></td>
                    <td align="center"><b>Most Popular</b></td>
                    <td align="center"><b>Order</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_list as $dd) {
                    echo "
                <tr>
                    <td align='center'>" . $dd['session_id'] . "</td>
                    <td>" . $dd['title'] . "</td>
                    <td align='center'>" . $dd['price'] . "</td>
                    <td>" . html_entity_decode($dd['feature']) . "</td>
                    <td align='center'><span style='color:" . (($dd['is_active'] == 1) ? "green" : "red") . "; font-weight:bold;'>" . (($dd['is_active'] == 1) ? "Yes" : "No") . "</span></td>
                    <td align='center'><span style='color:" . (($dd['is_most_popular'] == 1) ? "green" : "red") . "; font-weight:bold;'>" . (($dd['is_most_popular'] == 1) ? "Yes" : "No") . "</span></td>
                    <td align='center'>" . $dd['ord'] . "</td>
                    <td align='center'> 
                        <a   href='asvab-tutoring-package-list?action=add-package-session&package_id=" . $_GET['package_id'] . "&session_id=" . $dd['session_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                        <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-tutoring-package-list?action=session-package-list&package_id=" . $_GET['package_id'] . "&del_session_id=" . $dd['session_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                    </td>
                </tr>";
                }

                ?>
            </tbody>
        </table>

    <?
        break;

    case "add-package-session":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-tutoring-package-list?action=add-package-session&package_id=<?= $_GET['package_id'] ?>' enctype="multipart/form-data">
            <input type="hidden" name="package_id" value="<?php echo $_GET['package_id'] ?>">
            <input type="hidden" name="session_id" value="<?php echo $_GET['session_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="20%">Session Name</td>
                    <td><input type="text" name="title" class='form-control' value="<?php echo $db_value['title']; ?>" required></td>
                </tr>

                <tr>
                    <td style="vertical-align: middle;">Package Name</td>
                    <td>
                        <select name='package_id' id='package_id' size=1 class='form-control' required>
                            <option value='0'>Select a Package</option>
                            <?
                            foreach ($package_list as $dd) {
                                if ($_GET['package_id'] == $dd['package_id']):
                                    echo "<option value=" . $dd['package_id'] . " selected>" . $dd['title'] . "</option>";
                                else:
                                    echo "<option value=" . $dd['package_id'] . ">" . $dd['title'] . "</option>";
                                endif;
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td style="vertical-align: middle;">Course in Test-Prep</td>
                    <td>
                        <select name='course_id' id='course_id' size=1 class='form-control' required>
                            <option value='0'>Select a Course</option>
                            <?
                            foreach ($course_list as $dd) {
                                if ($db_value['course_id'] == $dd['course_id']):
                                    echo "<option value=" . $dd['course_id'] . " selected>" . $dd['course_name'] . "</option>";
                                else:
                                    echo "<option value=" . $dd['course_id'] . ">" . $dd['course_name'] . "</option>";
                                endif;
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td style="vertical-align: middle;">Package Duration</td>
                    <td>
                        <select name='package_duration' id='package_duration' size=1 class='form-control' required>
                            <option value='0'>Select a Package Duration</option>

                            <option value='1M' <?= ($db_value['package_duration'] == '1M') ? 'selected' : '' ?>>
                                1 Month
                            </option>
                            <option value='3M' <?= ($db_value['package_duration'] == '3M') ? 'selected' : '' ?>>
                                3 Months
                            </option>
                            <option value='6M' <?= ($db_value['package_duration'] == '6M') ? 'selected' : '' ?>>
                                6 Months
                            </option>
                            <option value='12M' <?= ($db_value['package_duration'] == '12M') ? 'selected' : '' ?>>
                                12 Months
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td style="vertical-align: middle;">Payment Mode</td>
                    <td>
                        <select name='payment_mode' id='payment_mode' size=1 class='form-control' required>
                            <option value='0'>Select a Payment Mode</option>

                            <option value='one_time' <?= ($db_value['payment_mode'] == 'one_time') ? 'selected' : '' ?>>
                                One Time
                            </option>

                            <option value='subscription' <?= ($db_value['payment_mode'] == 'subscription') ? 'selected' : '' ?>>
                                Subscription
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="left">Feature</td>
                    <td>
                        <textarea name="feature" class="form-control" rows="5"><?php echo $db_value['feature']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Production Plan Variation ID</td>
                    <td><input type="text" name="production_plan_variation_id" class='form-control' value="<?php echo $db_value['production_plan_variation_id']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Sandbox Plan Variation ID</td>
                    <td><input type="text" name="sandbox_plan_variation_id" class='form-control' value="<?php echo $db_value['sandbox_plan_variation_id']; ?>"></td>
                </tr>
                
                <tr>
                    <td align="left">Most Popular</td>
                    <td>
                        <label>
                            <input type="radio" name="is_most_popular" value=1 <?php echo ($db_value['is_most_popular'] == 1) ? 'checked' : ''; ?>> Yes
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="is_most_popular" value=0 <?php echo ($db_value['is_most_popular'] == 0) ? 'checked' : ''; ?>> No
                        </label>
                    </td>
                </tr>

                <tr>
                    <td align="left">Is Active</td>
                    <td>
                        <label>
                            <input type="radio" name="is_active" value=1 <?php echo ($db_value['is_active'] == 1) ? 'checked' : ''; ?>> Yes
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="is_active" value=0 <?php echo ($db_value['is_active'] == 0) ? 'checked' : ''; ?>> No
                        </label>
                    </td>
                </tr>

                <tr>
                    <td align="left">Price</td>
                    <td><input type="number" name="price" class='form-control' step="0.01" value="<?php echo $db_value['price']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Currency</td>
                    <td><input type="text" name="currency" class='form-control' value="<?php echo $db_value['currency']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Order</td>
                    <td><input type="number" name="ord" class='form-control' value="<?php echo $db_value['ord']; ?>"></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='submit' value='Save' class='btn btn-success'>
                    </td>
                </tr>
            </table>
        </form>
<?
        break;
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    CKEDITOR.replace('details', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 120,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form',
        versionCheck: false
    });

    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
        document.getElementById('ie8-warning').className = 'tip alert';
    }
</script>

<style>
    .link {
        font-size: 13px;
        /* Increase font size */
        font-weight: bold;
    }
</style>