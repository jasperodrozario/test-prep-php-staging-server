<?
echo "<br>";
switch ($action) {
    case "menu-list":
?>
        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID/Ord</b></td>
                    <td align="center"><b>Name</b></td>
                    <td align="center"><b>URL</b></td>
                    <td align="center"><b>Status</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($parent_menu_list as $d) {
                    echo "
            <tr style='background-color: #1ABC9C;'>
                <td align='center'>" . $d['id'] . "/" . $d['ord'] . "</td>
                <td>" . ucwords($d['name']) . "</td>
                <td>" . $d['url'] . "</td>
                <td align='center'><span style='font-weight:bold; color:".($dd['status'] == 'Y' ? 'green' : 'red')."'>" . (($dd['status'] == 'Y') ? "Yes" : "No") . "</span></td>
                <td align='center'>-</td>
            </tr>";
                    foreach ($db_list[$d['id']] as $dd) {
                        echo "
                <tr>
                    <td align='center'>" . $dd['id'] . "/" . $dd['ord'] . "</td>
                    <td> " . $dd['name'] . "</u></a></td>
                    <td>" . $dd['url'] . "</td>
                    <td align='center'><span style='font-weight:bold; color:".($dd['status'] == 'Y' ? 'green' : 'red')."'>" . (($dd['status'] == 'Y') ? "Yes" : "No") . "</span></td>
                    <td align='center'> 
                        <a   href='edit-admin-menu?action=add-menu&id=" . $dd['id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                        <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='edit-admin-menu?action=menu-list&del_menu_id=" . $dd['id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                    </td>
                </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

    <?
        break;

    case "add-menu":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>edit-admin-menu?action=add-menu' enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="20%">Menu Name</td>
                    <td><input type="text" name="name" class='form-control' value="<?php echo $db_value['name']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left" width="20%">URL</td>
                    <td><input type="text" name="url" class='form-control' value="<?php echo $db_value['url']; ?>" required></td>
                </tr>

                <tr>
                    <td style="vertical-align: middle;">Parent Menu</td>
                    <td>
                        <select name='parent_id' id='parent_id' size=1 class='form-control' required>
                            <option value='0'>Select A Parent Menu</option>
                            <option value='0'>None</option>
                            <?
                            foreach ($parent_list as $dd) {
                                if ($db_value['parent_id'] == $dd['id']):
                                    echo "<option value=" . $dd['id'] . " selected>" . $dd['name'] . "</option>";
                                else:
                                    echo "<option value=" . $dd['id'] . ">" . $dd['name'] . "</option>";
                                endif;
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="left">Status</td>
                    <td>
                        <label>
                            <input type="radio" name="status" value="Y" <?php echo ($db_value['status'] == 'Y') ? 'checked' : ''; ?>> Yes
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="N" <?php echo ($db_value['status'] == 'N') ? 'checked' : ''; ?>> No
                        </label>
                    </td>

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
                    <td align="left">Feature</td>
                    <td>
                        <textarea name="feature" class="form-control" rows="5"><?php echo $db_value['feature']; ?></textarea>
                    </td>
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
                    <td align="left">Price</td>
                    <td><input type="number" name="price" class='form-control' value="<?php echo $db_value['price']; ?>"></td>
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
    CKEDITOR.replace('feature', {
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