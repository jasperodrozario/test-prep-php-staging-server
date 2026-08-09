<?
echo "<br>";

switch ($action) {
    case "faq-list":
?>
        <div class="row">

            <form method='post' action='<?= $configs['admin_vpath'] ?>asvab-faq-list?action=faq-list'>
                <div class='col-md-8'>
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
                    <td align="center"><b>Question</b></td>
                    <td align="center"><b>Answers</b></td>
                    <td align="center"><b>Online</b></td>
                    <td align="center"><b>Order</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_list as $d) {

                    echo "
            <tr>
                <td align='center'>" . $d['id'] . "</td>
                <td>" . $d['question'] . "</td>
                <td>" . html_entity_decode($d['answers']) . "</td>
                <td align='center'><span style='color:" . (($d['is_online'] == 'Y') ? "green" : "red") . "; font-weight:bold;'>" . (($d['is_online'] == 'Y') ? "Yes" : "No") . "</span></td>
                <td>" . $d['ord'] . "</td>
                <td align='center'> 
                    <a   href='asvab-faq-list?action=add-faq&faq_id=" . $d['id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                    <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-faq-list?action=faq-list&del_faq_id=" . $d['id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>

    <?
        break;

    case "add-faq":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-faq-list?action=add-faq&faq_id=<?= $_GET['faq_id'] ?>' enctype="multipart/form-data">
            <input type="hidden" name="faq_id" value="<?php echo $_GET['faq_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="400px">Question</td>
                    <td><input type="text" name="question" class='form-control' value="<?php echo $db_value['question']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left">Answers</td>
                    <td>
                        <textarea name="answers" id="answers" class="form-control" rows="4"><?php echo $db_value['answers']; ?></textarea>
                    </td>

                </tr>

                <tr>
                    <td align="left">Category</td>
                    <td><input type="text" name="category" class='form-control' value="<?php echo $db_value['category']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Status</td>
                    <td>
                        <label>
                            <input type="radio" name="is_online" value="Y" <?php echo ($db_value['is_online'] == 'Y') ? 'checked' : ''; ?>> Active
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="is_online" value="N" <?php echo ($db_value['is_online'] == 'N') ? 'checked' : ''; ?>> Inactive
                        </label>
                    </td>

                </tr>

                <tr>
                    <td align="left">FAQ Order</td>
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
    CKEDITOR.replace('answers', {
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