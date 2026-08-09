<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
<input type="hidden" name="price_id" value="<?php echo $_GET['price_id']?>">
<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
<tr>
    <td width="20%">Course</td>
    <td>
        <select name='course_id' size=1 class='form-control' required>
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
    <td width="20%">Select Sub Title</td>
    <td>
        <select name='subs_id' size=1 class='form-control' required>
        <option>Select Subscription Title</option>	
        <?
        $subs = getQuery("subscriptions_details","*","","course_id");
        foreach($subs as $dd)
        {
            $course_name=getDataByWhere("course","course_name",array("course_id"=>$dd['course_id']));
            if($db_value['subs_id']==$dd['det_id']):
                echo"<option value=". $dd['det_id'] . " selected>" . $course_name['course_name']." - ".$dd['title'] . "</option>";
            else:
                echo"<option value=". $dd['det_id'] . ">" . $course_name['course_name'] . " - " . $dd['title'] . "</option>";
            endif;
        }
        ?>
        </select>
    </td>
</tr>     
<tr>
    <td width="20%">Title</td>
    <td>
        <select name='title' size=1 class='form-control' required>
        <option value='0'>Select Title</option>	
        <?
        $subscriptionDurations = ['1 Session', '3 Sessions', '4 Sessions', '6 Sessions', '10 Sessions', '12 Sessions'];
        foreach($subscriptionDurations as $dd)
        {
            if($db_value['title']==$dd):
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

<tr>
    <td align="left" width="20%">Discount Text</td>
    <td>
        <input type="text" class="form-control" name="discount" value="<?php echo $db_value['discount'] ?? ''; ?>" placeholder="Enter discount text">
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