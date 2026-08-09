<h5 style="margin-top: 0; color: #333;">
    Course Name: <strong><?= $course['course_name'] ?></strong>
</h5>

<div class="course-info">
    <div>
        <span>Status:</span>
        <span class="value <?= ($course['is_online'] === 'Y') ? 'status-green' : 'status-red' ?>">
            <?= ($course['is_online'] === 'Y') ? 'Online' : 'Offline' ?>
        </span>
    </div>
    <div>
        <span>Subscribe:</span>
        <span class="value <?= ($course['subscribe'] == 1) ? 'status-green' : 'status-red' ?>">
            <?= ($course['subscribe'] == 1) ? 'Yes' : 'No' ?>
        </span>
    </div>
    <div>
        <span>App:</span>
        <span class="value <?= ($course['app'] == 1) ? 'status-green' : 'status-red' ?>">
            <?= ($course['app'] == 1) ? 'Yes' : 'No' ?>
        </span>
    </div>
    <div>
        <span>Tutoring:</span>
        <span class="value <?= ($course['tutoring'] == 1) ? 'status-green' : 'status-red' ?>">
            <?= ($course['tutoring'] == 1) ? 'Yes' : 'No' ?>
        </span>
    </div>
</div>


<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
    <input type="hidden" name="course_id" value="<?php echo $_GET['course_id'] ?>">
    <table width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
                <td width=4%><b>ID/Ord</b></td>
                <td><b>Lessons Name</b></td>
                <td><b>Lessons Title</b></td>
                <td><b>Short Code</b></td>
                <td><b>Free</b></td>
                <td><b>Online</b></td>
                <td width=8%><b>Total PT Q.</b></td>
                <td width=8%><b>Total HW Q.</b></td>
                <td align="center" width=15%><b>Action</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $j = 0;
            foreach ($db_list as $d) {
            ?>
                <tr style="background-color: #1ABC9C;">
                    <td><?php echo $d['cat_id']; ?>/<?php echo $d['category_order']; ?></td>
                    <td><?php echo $d['category_name']; ?> (<?php echo $d['shortcode']; ?>)</td>
                    <td>-</td>
                    <td>-</td>
                    <td><?php echo ($d['free_test'] == 1) ? 'Y' : 'N'; ?></td>
                    <td><b><?php echo $d['online']; ?></b></td>
                    <?
                    if ($d['category_name'] == "Flashcards") {
                        $total = getTotalRow("lesson_questions", array("cat_id" => $d['cat_id'], "course_id" => $_GET['course_id']));
                    ?>
                        <td colspan="2"><?php echo $total; ?></td>
                        <?
                    } else {
                        if ($d['num_of_ques_pt'] && !$d['num_of_story_pt']) {
                        ?>
                            <td><?php echo $d['num_of_ques_pt'] ?></td>
                            <td>-</td>
                        <?
                        } else if ($d['num_of_story_pt']) {
                        ?>
                            <td>Story: <?php echo $d['num_of_story_pt'] ?></td>
                            <td>-</td>
                        <?
                        } else {
                        ?>
                            <td>-</td>
                            <td>-</td>
                    <?
                        }
                    }
                    ?>
                    <td align="center">
                        <?php
                        echo "<a  href='category_entryform?course_id=" . $_GET['course_id'] . "&cat_id=" . $d['cat_id'] . "' class='link'><u>Edit</u></a> | ";

                        echo "<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $d['category_name'] . "`?')){window.location.href='category_list?course_id=" . $_GET['course_id'] . "&del_id=" . $d['cat_id'] . "'}\" class='link'><u>Delete</u></a> | ";

                        if ($d['category_name'] == "Flashcards") {
                            echo "<a  href='questions_list?course_id=" . $_GET['course_id'] . "&cat_id=" . $d['cat_id'] . "' class='link'><u>Add Questions</u></a>";
                        } else {
                            echo "<a  href='lesson_entryform?page=lesson&course_id=" . $_GET['course_id'] . "&cat_id=" . $d['cat_id'] . "' class='link'><u>Add Lesson</u></a>";
                        }
                        ?>

                    </td>
                </tr>
                <?php
                if ($total_lessons[$d['cat_id']]) {
                    foreach ($lessons[$d['cat_id']] as $dd) {
                        $hw_total = getTotalRow("lesson_questions", array("lesson_id" => $dd['lesson_id'], "cat_id" => $d['cat_id'], "course_id" => $_GET['course_id'], "HW" => 1));
                        $pt_total = getTotalRow("lesson_questions", array("lesson_id" => $dd['lesson_id'], "cat_id" => $d['cat_id'], "course_id" => $_GET['course_id'], "pt" => 1));
                ?>

                        <tr>
                            <td><?php echo $dd['lesson_id'] ?>/<?php echo $dd['lesson_order'] ?></td>
                            <td><?php echo "<a  href='questions_list?lesson_id=" . $dd['lesson_id'] . "&cat_id=" . $dd['cat_id'] . "&course_id=" . $dd['course_id'] . "'><u>" . $dd['Lesson_name'] . "</u></a>"; ?></td>
                            <td><?php echo $dd['Lesson_title'] ?></td>
                            <td><?php echo $dd['shortcode'] ?></td>
                            <td><?php echo $dd['free'] ?></td>
                            <td><?php echo $dd['online'] ?></td>
                            <td><?php echo $pt_total ?></td>
                            <td><?php echo $hw_total ?></td>
                            <td align="center">
                                <?php
                                echo "<a  href='lesson_entryform?page=lesson&lesson_id=" . $dd['lesson_id'] . "&cat_id=" . $dd['cat_id'] . "&course_id=" . $dd['course_id'] . "'><u>Edit</u></a> | ";

                                echo "<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $dd['Lesson_name'] . "`?')){window.location.href='category_list?lesson_del_id=" . $dd['lesson_id'] . "&course_id=" . $dd['course_id'] . "'}\"><u>Delete</u></a> ";
                                ?>

                            </td>
                        </tr>
            <?php
                    }
                }
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

<style>
    .course-info {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }

    .course-info div {
        min-width: 150px;
    }

    .course-info span {
        font-weight: bold;
    }

    .course-info .value {
        margin-left: 5px;
    }

    .status-green {
        color: green;
    }

    .status-red {
        color: red;
    }
</style>