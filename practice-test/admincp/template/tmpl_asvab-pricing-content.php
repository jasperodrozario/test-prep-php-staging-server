<?
echo "<br>";
switch ($action) {
    case "add-pricing-content":
?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-pricing-content?action=add-pricing-content' enctype="multipart/form-data">

            <input type="hidden" name="pricing_id" value="<?php echo $_GET['pricing_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="300px">Title</td>
                    <td><input type="text" name="title" class='form-control' value="<?php echo $db_value['title']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left">Description</td>
                    <td>
                        <textarea name="description" id="description" class="form-control" rows="5"><?php echo $db_value['description']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Closing Content</td>
                    <td>
                        <textarea name="closing_content" id="closing_content" class="form-control" rows="5"><?php echo $db_value['closing_content']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Tutoring Type</td>
                    <td>
                        <select name="tutoring_type" id="tutoring_type" class="form-control" required>
                            <option value="">-- Select Tutoring Type --</option>
                            <option value="one" <?php echo ($db_value['tutoring_type'] == 'one') ? 'selected' : ''; ?>>1-on-1 Tutoring</option>
                            <option value="group" <?php echo ($db_value['tutoring_type'] == 'group') ? 'selected' : ''; ?>>Group Tutoring</option>
                            <option value="subscribe" <?php echo ($db_value['tutoring_type'] == 'subscribe') ? 'selected' : ''; ?>>Subscribe</option>
                        </select>
                    </td>
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

    case "pricing-list":
    ?>
        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID</b></td>
                    <td align="center"><b>Title</b></td>
                    <td align="center"><b>Online</b></td>
                    <td align="center"><b>Tutoring Type</b></td>
                    <td align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_value as $d) {
                    $types = [
                        'one' => '1-on-1 Tutoring',
                        'group' => 'Group Tutoring',
                        'subscribe' => 'Subscribe'
                    ];

                    echo "
            <tr>
                <td align='center'>" . $d['pricing_id'] . "</td>
                <td>" . $d['title'] . "</td>
                <td>" . $d['is_online'] . "</td>
                <td>" . $types[$d['tutoring_type']] . "</td>
                <td align='center'> 
                    <a   href='asvab-pricing-content?action=add-pricing-content&pricing_id=" . $d['pricing_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                    <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-pricing-content?action=pricing-list&del_pricing_id=" . $d['pricing_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>
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
    CKEDITOR.replace('description', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 200,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form',
        versionCheck: false
    });

    CKEDITOR.replace('closing_content', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 150,
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