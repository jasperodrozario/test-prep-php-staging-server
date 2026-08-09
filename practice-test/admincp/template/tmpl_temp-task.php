 
 <h2> Total updated users: <?= $total_updated; ?> </h2>

 <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
        <thead>
            <tr>
                <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                <td width="5%" align="center"><b>Id</b></td>
                <td align="center"><b>Name</b></td>
                <td align="center"><b>Course</b></td>
                <td align="center"><b>Reg at</b></td>
                <td align="center"><b>Paid</b></td>
                <td width="10%" align="center"><b>Reg. Date</b></td>
                <td width="10%" align="center"><b>Exp. Date</b></td>
                <td width="10%" align="center"><b>Subscription Date</b></td>
                <td width="10%" align="center"><b>FPT</b></td>
                <td width="10%" align="center"><b>Pre. Test</b></td>
                <td width="20%" align="center"><b>Action</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($db_list as $d) {
                if ($d['activation_status'] == "Y") {
                    $paid = "Yes";
                } else {
                    $paid = "<font color=red>No</font>";
                }
            ?>
                <tr>
                    <td><input type='checkbox' name='user_ids[]' value='<?= $d['user_id']; ?>' class='checkBoxClass'></td>
                    <!--                    <td align="left"><input type='checkbox' name='user_ids[]' value='<?= $d['user_id']; ?>'></td>-->
                    <td align="left"><?= $d['user_id']; ?></td>
                    <td><?php echo ucwords($d['first_name'] . " " . $d['last_name']); ?><br>
                        <?php echo $d['email']; ?><br>
                        Mobile : <?php echo $d['mobile']; ?>
                    </td>
                    <td align="center"><?php echo $test_name[$d['course_id']]; ?></td>
                    <td align="center"><?php echo $d['reg_at']; ?></td>
                    <td align="center"><?php echo $paid; ?></td>
                    <td align="center"><?php echo showdate($d['reg_date']); ?></td>
                    <td align="center"><?php echo showdate($d['expire_date']); ?></td>
                    <td align="center"><?php echo showdate($d['subscription_date']); ?></td>
                    <td align="center">
                        <?php echo ($d['fp'] == 1)
                            ? "<i class='fa fa-check' style='color:green; font-size: 18px;'></i>"
                            : "<i class='fa fa-times' style='color:red; font-size: 18px;'></i>"; ?>
                    </td>
                    <td align="center">
                        <?php echo ($d['pt'] == 1)
                            ? "<i class='fa fa-check' style='color:green; font-size: 18px;'></i>"
                            : "<i class='fa fa-times' style='color:red; font-size: 18px;'></i>"; ?>
                    </td>
                    <td align="center"><a href="student-reg?user_id=<?php echo $d['user_id']; ?>">Edit</a> | <a href="<?= $configs['vpath'] ?>login/<?php echo $d['email']; ?>/<?php echo $d['password']; ?>" target='_new'>View User</a>
                        |&nbsp; <a href='javascript://' onClick="javascript:if(confirm('Are you deleting')){window.location='student-list?delete_id=<?= $d['user_id'] . "&amp;reg_at=" . $_GET['reg_at'] . "&limit=" . $_REQUEST['limit']; ?>'};">Delete</a>
                    </td>
                </tr>
            <?php
            }

            ?>
        </tbody>
    </table>