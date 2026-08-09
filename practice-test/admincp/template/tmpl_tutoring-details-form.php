<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
<input type="hidden" name="det_id" value="<?php echo $_GET['det_id']?>">
<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
<tr>
    <td width="20%">Course</td>
    <td>
        <select name='course_id' size=1 class='form-control' required onchange="fetchSubTitles()">
        <option>Select Course</option>	
        <?
        $courses = getQuery("course","*","","course_name");
        foreach($courses as $dd)
        {
            if($db_value['course_id']==$dd['course_id'] or $_GET['course_id']==$dd['course_id']):
                echo"<option value=". $dd['course_id'] . " selected>" . $dd['course_name'] . "</option>";
            else:
                echo"<option value=". $dd['course_id'] . ">" . $dd['course_name'] . "</option>";
            endif;
        }
        ?>
        </select>
    </td>
</tr> 

<tr>
    <td align="left" width="20%">Title</td>
    <td>
        <input type="text" class="form-control" name="title" value="<?php echo $db_value['title'] ?? ''; ?>" placeholder="Enter title">
    </td>
</tr>

<tr>
    <td align="left" width="20%" height="200px">Description</td>
    <td>
    <textarea style="height: 200px;" class="form-control" name="description" id="description" required>
        <?= htmlspecialchars(isset($_GET['det_id']) ? html_entity_decode($db_value['description']) : '
                <div class="pricing-details-tutor" style="padding-left: 23%;">
                    <h4>The subscription includes:</h4>
                    <ul style="list-style-type: none;">
                        <li><span style="color: green; font-size: 1.2em;">&#10004;</span>detail item</li>
                        <li><span style="color: green; font-size: 1.2em;">&#10004;</span>add more</li>
                    </ul>
                </div>'); ?>
    </textarea>
   
    </td>
</tr>

<tr>
    <td align="left" width="20%">Order</td>
    <td>
        <input type="number" class="form-control" name="ord" value="<?php echo $db_value['ord'] ?? ''; ?>" placeholder="Enter order">
    </td>
</tr>

<!-- <tr>
    <td align="left" width="20%">Group Tutoring</td>
    <td>
        <input type="checkbox" name="is_group_tutoring" value="1" 
            <?php echo (!empty($db_value['is_group_tutoring']) && $db_value['is_group_tutoring'] == 1) ? 'checked' : ''; ?>>
        Yes
    </td>
</tr> -->

<tr><td></td><td ><input type=submit  name='SBMT_REG' value='Update' class='btn btn-success'></td></tr>
</table>
</form>

<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.24.0/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    CKEDITOR.replace('description', {
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