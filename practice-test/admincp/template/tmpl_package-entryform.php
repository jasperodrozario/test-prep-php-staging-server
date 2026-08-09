<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
<input type="hidden" name="package_id" value="<?php echo $_GET['package_id']?>">
<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
<tr>
    <td width="20%">Course</td>
    <td>
        <select name='course_id' size=1 class='form-control' required>
        <option value='0'>Select Course</option>	
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
    <td width="20%">Duration</td>
    <td>
        <select name='duration' size=1 class='form-control' required>
        <option value='0'>Select Duration</option>	
        <?
        $subscriptionDurations = ['1 month', '3 months', '6 months', '12 months'];
        foreach($subscriptionDurations as $dd)
        {
            if($db_value['duration']==$dd):
                echo"<option value='". $dd . "' selected>" . $dd . "</option>";
            else:
                echo"<option value='". $dd . "'>" . $dd . "</option>";
            endif;
        }
        ?>
        </select>
    </td>
</tr>
<tr>
    <td align="left" width="20%">Price</td>
    <td>
        <input type="number" class="form-control" name="price" 
               value="<?php echo $db_value['price'] ?? ''; ?>" 
               required min="0" step="0.01" placeholder="Enter price">
    </td>
</tr>

<tr><td></td><td ><input type=submit  name='SBMT_REG' value='Update' class='btn btn-success'></td></tr>
</table>
</form>