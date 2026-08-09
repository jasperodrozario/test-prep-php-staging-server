<table width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
    <thead>
        <tr>
            <td><b>Course ID</b></td>
            <td><b>Course Name</b></td>
            <td><b>Free Test</b></td>
            <td><b>Course Group</b></td>
            <td><b>Available Online</b></td>
        </tr>
    </thead>
    <tbody>
        <?php
        $j = 0;

        foreach ($course_group_list as $c) {

            foreach ($course_list[$c['group_id']] as $d) {
                // Check if 'app' is in course_name
                if (stripos($d['course_name'], 'app') === false) {
                    $group_name = getFldValueById("course_group", "group_name", array("group_id" => $d['group_id']));
        ?>
                    <tr>
                        <td><?php echo $d['course_id'] ?>/<?php echo $d['ord'] ?></td>
                        <td>
                            <?php echo $d['course_name']; ?>
                        </td>
                        <td>
                            <?php echo "<a href='category-list-free-test?course_id=" . $d['course_id'] . "'><u>Practice Test</u></a>"; ?> <br>
                            <?php echo "<a href='category-list-free-pred-test?course_id=" . $d['course_id'] . "'><u>Prediction Test</u></a>"; ?>
                        </td>
                        <td><?php echo $group_name ?></td>
                        <td style="color: <?php echo ($d['is_online'] === 'Y') ? 'green' : 'red'; ?>; font-weight: bold;"><?php echo $d['is_online'] ?></td>
                    </tr>
        <?php
                }
            }
        }
        ?>
    </tbody>
</table>