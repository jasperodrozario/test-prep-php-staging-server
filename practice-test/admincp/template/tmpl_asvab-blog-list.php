<?
echo "<br>";

// print_r($insert_data);

switch ($action) {
    case "blog-list":
?>
        <div class="row">

            <form method='post' action='<?= $configs['admin_vpath'] ?>asvab-blog-list?action=blog-list'>
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
                    <td align="center"><b>Writer Img</b></td>
                    <td align="center"><b>Written By</b></td>
                    <td align="center"><b>Blog Title</b></td>
                    <td align="center"><b>Created At</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_list as $d) {
                    if (!$d['writer_img']) {
                        $d['writer_img'] = "uploads/no-user.png";
                    }

                    $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    echo "
            <tr>
                <td align='center'>" . $d['blog_id'] . "</td>
                <td>
                    <img src='" . $configs['vpath'] . "image.php?src=" . $image_path . $d['writer_img'] . "&w=70'>
                </td>
                <td align='center'>" . $d['written_by'] . "</td>
                <td>" . ucwords($d['blog_title']) . "</td>
                <td>" . $d['created_at'] . "</td>
                <td align='center'> 
                    <a   href='asvab-blog-list?action=add-blog&blog_id=" . $d['blog_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                    <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-blog-list?action=blog-list&del_blog_id=" . $d['blog_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>

    <?
        break;

    case "add-blog":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-blog-list?action=add-blog' enctype="multipart/form-data">
            <input type="hidden" name="blog_id" value="<?php echo $_GET['blog_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="400px">Blog Title</td>
                    <td><input type="text" name="blog_title" class='form-control' value="<?php echo $db_value['blog_title']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left">Content</td>
                    <td>
                        <textarea name="blog_content" id="blog_content" class="form-control" rows="5"><?php echo $db_value['blog_content']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Written By</td>
                    <td><input type="text" name="written_by" class='form-control' value="<?php echo $db_value['written_by']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Writer Image</td>
                    <td><input class="form-control" name="writer_img" type="file" accept=".png, .jpg, .jpeg"></td>
                </tr>

                <tr>
                    <td align="left">Created At</td>
                    <td>
                        <input type="datetime-local" name="created_at" class='form-control'
                            value="<?php echo !empty($db_value['created_at']) ? date('Y-m-d\TH:i', strtotime($db_value['created_at'])) : ''; ?>">
                    </td>
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
}
?>
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
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    CKEDITOR.replace('blog_content', {
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