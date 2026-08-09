<br>
<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
    <input type="hidden" name="course_id" value="<?php echo $_GET['course_id'] ?>">
    <table width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
                <td width=4%><b>ID/Ord</b></td>
                <td><b>Category Name</b></td>
                <td><b>Free Pred Test</b></td>
                <td><b>Online</b></td>
                <td><b>PT Q. Limit</b></td>
                <td width=8%><b>Total Questions</b></td>
                <td align="center" width=15%><b>Action</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $j = 0;
            foreach ($db_list as $d) {
            ?>
                <tr>
                    <td><?php echo $d['cat_id']; ?>/<?php echo $d['category_order']; ?></td>
                    <td><?php echo "<a  href='questions_list_free_pred_test?cat_id=" . $d['cat_id'] . "&course_id=" . $d['course_id'] . "'><u>" . $d['category_name'] . "(" . $d['shortcode'] . ")</u></a>"; ?></td>
                    <td style="color: <?php echo ($d['free_pred_test'] === '1') ? 'green' : 'red'; ?>; font-weight: bold;"><?php echo ($d['free_pred_test'] == 0) ? 'N' : 'Y'; ?></td>
                    <td style="color: <?php echo ($d['online'] === 'Y') ? 'green' : 'red'; ?>; font-weight: bold;"><?php echo $d['online'] ?></td>
                    <?
                    $total = getTotalRow("pred_free_test_questions", array("cat_id" => $d['cat_id'], "course_id" => $_GET['course_id']));
                    ?>
                    <td><?php echo $d['num_free_pred_test_ques']; ?></td>
                    <td><?php echo $total; ?></td>
                    <td align="center">
                        <?php
                        echo "<a  href='category_entryform?course_id=" . $d['course_id'] . "&cat_id=" . $d['cat_id'] . "&backpage=free-pred'><u>Edit</u></a>";
                        ?>

                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</form>

<style>
    .link {
        color: inherit;
        /* Set the default text color */
        text-decoration: none;
        /* Remove underline */
    }

    .link:hover {
        color: white;
        /* Change text color on hover */
    }
</style>