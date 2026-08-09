<br>

<form method="post" action='<?= $configs['admin_vpath'] ?>asvab-welcom-text' enctype="multipart/form-data">
    
    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td align="left" width="300px">Title</td>
            <td><input type="text" name="title" class='form-control' value="<?php echo $db_value['title']; ?>" required></td>
        </tr>

        <tr>
            <td align="left">Welcome Text</td>
            <td>
                <textarea name="description" id="description" class="form-control" rows="5"><?php echo $db_value['description']; ?></textarea>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    CKEDITOR.replace('description', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 300,
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