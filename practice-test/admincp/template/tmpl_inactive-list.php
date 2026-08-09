<div class='clearfix'><br></div>

<form method='post' action="<?=basename($_SERVER['PHP_SELF'],".php");?>?reg_at=<?=$_REQUEST['reg_at'];?>">
<input type='hidden' name='reg_at'  value='<?=$_REQUEST['reg_at'];?>'>
<div class='row'>
    <div class='col-md-6'>
        <input type='text' class='form-control' name='key' placeholder='Search By First Name / Last Name /Email Id' autocomplete="off">
    </div>
    <div class='col-md-2'>
        <select name='source_type' class='form-control'>
            <option value=''>All Student</option>
            <option value='app'>App Student</option>
            <option value='web'>Web Student</option>
        </select>
    </div>
    
    <div class='col-md-2'>
        <select name='test_id' class='form-control'>
            <option value=''>All Course</option>
            <?
            foreach($test as $t)
            {
               if($t['course_id']==$_GET['test_id'])
               {
                  echo"<option value='".$t['course_id']."' selected>".$t['course_name'] ." [" . $student_total[$t['course_id']]."]</option>"; 	
               }
               else
               {
                   echo"<option value='".$t['course_id']."'>".$t['course_name'] . "</option>"; 	
               }	
            }	
            ?>	
        </select>	
    </div>
    
    <div class='col-md-2'>
        <input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'>
    </div>

</div>
</form>


<div class='clearfix'><br></div>

<form method="post" id="userViewForm" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
    <div style="width: 25%; float: right;">
        <button type="submit" class="btn btn-success" name="deleteMultiUser" style='width:60%; float: right;'>Delete Selected User</button>
    </div>
    
    <br><br><br>
    
    <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
        <thead>
            <tr >
                <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                <td width="5%" align="left"><b>Id</b></td>
                <td align="left"><b>Name</b></td>
                <td align="left"><b>Course</b></td>
                <td align="center"><b>Paid</b></td>
                <td width="10%" align="center"><b>Reg. Date</b>
                </td><td width="10%" align="center"><b>Exp. Date</b></td>
                <td width="10%" align="center"><b>Subscription Date</b></td>  
                <td width="20%" align="center"><b>Action</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($db_list as $d)
            {    
                 if($d['activation_status']=="Y")
                 {
                    $paid="Yes";
                 }
                 else
                 {
                    $paid="<font color=red>No</font>";
                 }	
                 ?>
                <tr>
                    <td><input type='checkbox' name='user_ids[]' value='<?=$d['user_id'];?>' class='checkBoxClass'></td>
<!--                    <td align="left"><input type='checkbox' name='user_ids[]' value='<?=$d['user_id'];?>'></td>-->
                    <td align="left"><?=$d['user_id'];?></td>
                    <td><?php echo ucwords($d['first_name'] . " " .$d['last_name']);?><br>
                     <?php echo $d['email'];?><br>
                     Mobile : <?php echo $d['mobile'];?>
                    </td>
                    <td align="center"><?php echo $test_name[$d['course_id']];?></td>
                    <td align="center"><?php echo $paid;?></td> 
                    <td align="center"><?php echo showdate($d['reg_date']);?></td>
                    <td align="center"><?php echo showdate($d['expire_date']);?></td>
                    <td align="center"><?php echo $d['subscription_date'];?></td>
                    <td align="center"><a  href="student-reg?user_id=<?php echo $d['user_id'];?>">Edit</a> | <a  href="<?=$configs['vpath']?>login/<?php echo $d['email'];?>/<?php echo $d['password'];?>" target='_new'>View User</a> 
                     |&nbsp; <a href='javascript://' onClick="javascript:if(confirm('Are you deleting')){window.location='student-list?delete_id=<?=$d['user_id']."&amp;reg_at=" . $_GET['reg_at'] . "&limit=" . $_REQUEST['limit'];?>'};">Delete</a>
                    </td></tr>
               <?php
            }

            ?> 
        </tbody>
    </table>  
</form>
<?if($total>20){echo"<ul class='pagination'>" . paging($total,"",$param) . "</ul>";}?>

<script>
    
 $("#ckbCheckAll").click(function () {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
    });
    
</script>