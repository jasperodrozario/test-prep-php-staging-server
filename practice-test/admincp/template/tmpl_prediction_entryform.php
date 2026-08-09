<form method="post" id='pred_form' action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
    <input type="hidden" name="Predic_test_id" value="<?php echo $_GET['Predic_test_id'] ?>">
    <table width="100%"  cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td style="vertical-align: middle;" width=20%>Prediction Test Name</td>
            <td><input type="text" class='form-control' name='test_name' value="<?php echo $db_value['test_name'] ?>" required></td>
        </tr>

        <tr>
            <td style="vertical-align: middle;" width="15%">Course Name</td>
            <td>
                <select name='course_id' id='course_id' size=1 class='form-control' required>
                    <option value='0'>Select Course</option>
                    <?
                    foreach ($course as $dd) {
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
            <td style="vertical-align: middle;" width=20%>Shortcode</td>
            <td><input type="text" class='form-control' name='shortcode' value="<?php echo $db_value['shortcode'] ?>"></td>
        </tr>

        <tr>
            <td style="vertical-align: middle;" >Payment Type</td>
            <td ><input type="radio" name="free" value="Y" 
                <? 
                if ($db_value['free'] == "Y") {
                echo "checked";
                 } 
                 ?>> Free 
                 <input type="radio" name="free" value="N" 
                 <? 
                 if ($db_value['free'] == "N") {
                 echo "checked";
                } 
                 ?>> Paid
            </td>
        </tr>

        <tr>
            <td style="vertical-align: middle;" >Status</td>
            <td style="vertical-align: middle;" ><input type="radio" name="online" value="Y" 
            <? 
                if ($db_value['online'] == "Y") {
                echo "checked";
                    } 
            ?>> Online 
            <input type="radio" name="online" value="N" 
            <? 
                if ($db_value['online'] == "N") {  
                    echo "checked";
            } 
            ?>> Offline
            </td>
        </tr>

        <tr>
            <td style="vertical-align: middle;" >Display Order</td>
            <td><input type="number" class='form-control' name='test_order' value="<?php echo $db_value['test_order'] ?>"></td>
        </tr>

        <tr>
            <td></td>
            <td><input type=submit name='SBMT_REG' value='Save' class='btn btn-success'></td>
        </tr>
    </table>
</form>
