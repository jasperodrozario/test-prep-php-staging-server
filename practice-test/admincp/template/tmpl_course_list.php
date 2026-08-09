<table width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
    <thead>
        <tr>
            <td><b>Course ID</b></td>
            <td><b>Course Name</b></td>
            <td align="center"><b>Tutoring</b></td>
            <td align="center"><b>Subscribe</b></td>
            <td align="center"><b>App</b></td>
            <td><b>Course Group</b></td>
            <td><b>Online</b></td>
            <td align="center" with=25%><b>Action</b></td>
        </tr>
    </thead>
    <tbody>
        <?php
        $j = 0;
        foreach ($course_group_list as $c) {
            foreach ($course_list[$c['group_id']] as $d) {
                $group_name = getFldValueById("course_group", "group_name", array("group_id" => $d['group_id']));
        ?>
                <tr>
                    <td><?php echo $d['course_id'] ?>/<? echo $d['ord'] ?></td>
                    <td><?php echo "<a  href='category_list?course_id=" . $d['course_id'] . "'><u>" . $d['course_name'] . "</u></a>"; ?></td>
                    <td align="center" style="color: <?php echo (!empty($d['tutoring'])) ? 'green' : 'red'; ?>; font-weight: bold;">
                        <?php echo (!empty($d['tutoring'])) ? 'Y' : 'N'; ?>
                    </td>
                    <td align="center" style="color: <?php echo (!empty($d['subscribe'])) ? 'green' : 'red'; ?>; font-weight: bold;">
                        <?php echo (!empty($d['subscribe'])) ? 'Y' : 'N'; ?>
                    </td>
                    <td align="center" style="color: <?php echo (!empty($d['app'])) ? 'green' : 'red'; ?>; font-weight: bold;">
                        <?php echo (!empty($d['app'])) ? 'Y' : 'N'; ?>
                    </td>  
                    <td><?php echo $group_name ?></td>
                    <td style="color: <?php echo ($d['is_online'] === 'Y') ? 'green' : 'red'; ?>; font-weight: bold;"><?php echo $d['is_online'] ?></td>
                    
                    <td align="center">
                        <?php
                        echo "<a  href='course_entryform?course_id=" . $d['course_id'] . "'><u>Edit</u></a> | ";

                        echo "<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $d['course_name'] . "`?')){window.location.href='course_list?del_id=" . $d['course_id'] . "'}\"><u>Delete</u></a>";
                        ?>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>