<?
echo "<br>";
switch ($action) {
    case "payment-method-list":
?>
        <div class="row">

            <form method='post' action='<?= $configs['admin_vpath'] ?>asvab-payment-method-list?action=payment-method-list'>
                <div class='col-md-4'>
                    <input type='text' name='search' class='form-control' placeholder='Enter Search Key'>
                </div>
                <div class='col-md-2'><input type='submit' name='search_btn' class='btn btn-success' value='Search' style='width: 100%;'></div>
            </form>

        </div>

        <hr>

        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID</b></td>
                    <td with=5% align="center"><b>Photo</b></td>
                    <td align="center"><b>Payment Method</b></td>
                    <td align="center"><b>Status</b></td>
                    <td align="center"><b>Order</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_list as $d) {
                    if (!$d['photo']) {
                        $d['photo'] = "uploads/no-user.png";
                    }

                    $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    echo "
            <tr>
                <td align='center'>" . $d['id'] . "</td>
                <td>
                    <img src='" . $configs['vpath'] . "image.php?src=" . $image_path . $d['photo'] . "&w=60'>
                </td>
                <td>" . ucwords($d['payment_method']) . "</td>
                <td>" . $d['status'] . "</td>
                <td>" . $d['ord'] . "</td>
                <td align='center'> 
                    <a   href='asvab-payment-method-list?action=add-payment-method&id=" . $d['id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                    <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-payment-method-list?action=payment-method-list&del_id=" . $d['id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>
        
        <?
        break;

    case "add-payment-method":
        ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-payment-method-list?action=add-payment-method' enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="400px">Payment Method Name</td>
                    <td><input type="text" name="payment_method" class='form-control' value="<?php echo $db_value['payment_method']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left">Status</td>
                    <td>
                        <label>
                            <input type="radio" name="status" value="Y" <?php echo ($db_value['status'] == 'Y') ? 'checked' : ''; ?>> Active
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="N" <?php echo ($db_value['status'] == 'N') ? 'checked' : ''; ?>> Inactive
                        </label>
                    </td>
                </tr>

                <tr>
                    <td align="left">Photo</td>
                    <td><input class="form-control" name="photo" type="file" accept=".png, .jpg, .jpeg"></td>
                </tr>

                <tr>
                    <td align="left">Order</td>
                    <td><input type="number" name="ord" class='form-control' value="<?php echo $db_value['ord']; ?>"></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='submit' value='Save' class='btn btn-success'>&nbsp;&nbsp;
                        <? if ($id) { ?>
                            <input type='submit' name='SBMT_DEL' class='btn btn-danger' value='Delete'>
                        <? } ?>
                    </td>
                </tr>
            </table>
        </form>
    <?
        break;
    case "grant":
    ?>
        <form id='form1' method='post' action='<?= $configs['admin_vpath'] ?>tutor-list?action=grant&admin_id=<?php echo $_GET['admin_id'] ?>'>
            <input type='hidden' name='admin_id' value='<?php echo $_GET['admin_id'] ?>'>
            <input type='hidden' name='action' value='grant-access'>
            <table border=0 cellpadding=4 cellspacing=1 width=100% align="center" class='table table-bordered table-striped'>
                <tr>
                    <td height=30 align="left" colspan=2><b>Check/uncheck all</b> <input type='checkbox' name="checkall" id="checkall" onClick="check_uncheck_checkbox(this.checked);"> <input type='submit' name='SBMT_REG' class='btn btn-success pull-right' value='Update'>&nbsp;&nbsp;<input type="Reset" class='btn btn-danger pull-right'>&nbsp;&nbsp;</td>
                </tr>

                <?
                foreach ($menus as $d) {
                    echo '<tr ><td colspan="2"><b>' . $d['name'] . '</b></td></tr>';
                    $sub = $submenus[$d['id']];
                    if (count($sub)) {
                        foreach ($sub as $s) {
                            $chk = "";
                            if (in_array($s['id'], $grants)) {
                                $chk = "checked";
                            }
                ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input class='customcheckbox' type='checkbox' name='progid[]' value="<?php echo $s['id'] ?>,<?php echo $s['parent_id'] ?>" <?php echo $chk ?>> <?php echo $s['name']; ?></td>
                            </tr>
                <?
                        }
                    }
                }

                ?>

                <tr>
                    <td colspan=2 height=25 align=center><input type='submit' name="SBMT_REG" class='btn btn-ar btn-primary' value='Submit'></td>
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
    CKEDITOR.replace('about', {
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

<script>
    function check_uncheck_checkbox(isChecked) {
        if (isChecked) {
            $('.customcheckbox').each(function() {
                this.checked = true;
            });
        } else {
            $('.customcheckbox').each(function() {
                this.checked = false;
            });
        }
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
    function copyLink() {
        // Get the link element
        var copyText = document.getElementById("linkToCopy");

        // Create a temporary input to hold the text to copy
        var tempInput = document.createElement("input");
        tempInput.style.position = "absolute";
        tempInput.style.left = "-9999px";
        tempInput.value = copyText.href;
        document.body.appendChild(tempInput);

        // Select the text field
        tempInput.select();
        tempInput.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text inside the text field
        document.execCommand("copy");

        // Remove the temporary input
        document.body.removeChild(tempInput);

        // Alert the copied text (Optional)
        alert("Copied the link: " + tempInput.value);
    }
</script>

<style>
    .link {
        font-size: 13px;
        /* Increase font size */
        font-weight: bold;
    }
</style>