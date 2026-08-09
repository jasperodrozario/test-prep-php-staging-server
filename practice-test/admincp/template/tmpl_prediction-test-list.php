<br>
<table width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
    <thead>
        <tr>
            <td><b>Pred ID</b></td>
            <td><b>Category Name</b></td>
            <td align="center"><b>Code Name(User)</b></td>
            <td align="center"><b>Order</b></td>
            <td align="center"><b>Total Questions</b></td>
            <td align="center" with=25%><b>Action</b></td>
        </tr>
    </thead>
    <tbody>
        <?php
        $j = 0;
            foreach ($pred_cat_list as $d) {
                $total = getTotalRow("lesson_questions", ["pred_id" => $d['pred_id']]);
        ?>
                <tr>
                    <td><?php echo $d['pred_id'] ?></td>
                    <td><?php echo "<a  href='prediction-question-list?pred_id=" . $d['pred_id'] . "'><u>" . $d['cat_name'] . "</u></a>"; ?></td>
                    <td><?php echo $d['cat_code'] ?></td>
                    <td><?php echo $d['ord'] ?></td>
                    <td><?php echo $total ?></td>
                    <td align="center">
                        <?php
                        echo "<a  href='pred-cat-entryform?pred_id=" . $d['pred_id'] . "'><u>Edit</u></a> | ";

                        echo "<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $d['cat_name'] . "`?')){window.location.href='prediction-test-list?del_id=" . $d['pred_id'] . "'}\"><u>Delete</u></a>";
                        ?>
                    </td>
                </tr>
        <?php
            }
        ?>
    </tbody>
</table>