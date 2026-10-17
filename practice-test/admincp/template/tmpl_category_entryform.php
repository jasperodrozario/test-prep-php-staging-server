<form method="post" id="cat_form" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
    <input type="hidden" name="cat_id" value="<?php echo $_GET['cat_id'] ?>">
    <input type="hidden" name="backpage" value="<?php echo $_GET['backpage'] ?>">
    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td align="left" width=20%>Name</td>
            <td><input type="text" class='form-control' name='category_name' value="<?php echo htmlspecialchars((string)$db_value['category_name']) ?>" required></td>
        </tr>

        <tr>
            <td width="20%">Course Name</td>
            <td>
                <select name='course_id' size=1 class='form-control' required>
                    <option value=''>Select Course</option>
                    <?
                    foreach ($course as $dd) {
                        if ($_GET['course_id'] == $dd['course_id']):
                            echo "<option value=" . $dd['course_id'] . " selected>" . $dd['course_name'] . "</option>";
                        else:
                            echo "<option value=" . $dd['course_id'] . ">" . $dd['course_name'] . "</option>";
                        endif;
                    }
                    ?>
                </select>
            </td>
        </tr>

        <!--<tr><td align="left" width=20%>Course ID</td><td><input type="text" class='form-control' name='course_id'   value="<?php echo $_GET['course_id'] ?>" required></td></tr>-->

        <tr>
            <td>Description </td>
            <td><textarea class='form-control description' id='description' name='description'><?= html_entity_decode($db_value['description']); ?></textarea></td>
        </tr>

        <tr>
            <td>Online </td>
            <td><input type="radio" value='Y' name="online" <? if ($db_value['online'] == "Y") {
                                                                echo "checked";
                                                            } ?>> Active <input type='radio' name='online' value='N' <? if ($db_value['online'] == "N") {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } ?>> Hidden</td>
        </tr>

        <tr>
            <td>Paid </td>
            <td><input type="radio" value='Y' name="free" <? if ($db_value['free'] == "Y") {
                                                                echo "checked";
                                                            } ?>> Yes <input type='radio' name='free' value='N' <? if ($db_value['free'] == "N") {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?>> No</td>
        </tr>

        <tr>
            <td>Online Free Test</td>
            <td><input type="radio" value='1' name="free_test" <? if ($db_value['free_test'] == 1) {
                                                                    echo "checked";
                                                                } ?>> Yes <input type='radio' name='free_test' value='0' <? if ($db_value['free_test'] == 0) {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        } ?>> No</td>
        </tr>

        <tr>
            <td align="left">Number of Free Test Questions</td>
            <td><input type="number" class='form-control' name='num_free_test_ques' value="<?php echo $db_value['num_free_test_ques'] ?>"></td>
        </tr>

        <tr>
            <td>Online Free Pred Test</td>
            <td><input type="radio" value='1' name="free_pred_test" <? if ($db_value['free_pred_test'] == 1) {
                                                                    echo "checked";
                                                                } ?>> Yes <input type='radio' name='free_pred_test' value='0' <? if ($db_value['free_pred_test'] == 0) {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                            } ?>> No</td>
        </tr>

        <tr>
            <td align="left">Number of Free Pred Test Questions</td>
            <td><input type="number" class='form-control' name='num_free_pred_test_ques' value="<?php echo $db_value['num_free_pred_test_ques'] ?>"></td>
        </tr>

        <tr>
            <td align="left">Short Code</td>
            <td><input type="text" class='form-control' name='shortcode' value="<?php echo htmlspecialchars((string)$db_value['shortcode']) ?>"></td>
        </tr>

        <tr>
            <td align="left">Display Order</td>
            <td><input type="number" class='form-control' name='category_order' value="<?php echo $db_value['category_order'] ?>"></td>
        </tr>

        <tr>
            <td align="left">Number of Questions(PT)</td>
            <td><input type="number" class='form-control' name='num_of_ques_pt' value="<?php echo (int)$db_value['num_of_ques_pt'] ?>"></td>
        </tr>

        <tr>
            <td align="left">Number of Stories(PT)</td>
            <td><input type="number" class='form-control' name='num_of_story_pt' value="<?php echo (int)$db_value['num_of_story_pt'] ?>"></td>
        </tr>

        <tr>
            <td></td>
            <td><input type=submit name='SBMT_REG' value='Update' class='btn btn-success'></td>
        </tr>
    </table>
</form>

<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>

<script>
    CKEDITOR.replace('description', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 120,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form'
    });

    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
        document.getElementById('ie8-warning').className = 'tip alert';
    }
</script>