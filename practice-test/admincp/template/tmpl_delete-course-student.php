<?php if (!empty($message)): ?>
    <p style="font-weight: bold; color: #444;"><?= $message; ?></p>
<?php endif; ?>


<form method="post" action="">
    <select name='test_id' class='form-control'>
        <option value=''>All Course</option>
        <?
        foreach ($test as $t) {
            echo "<option value='" . $t['course_id'] . "'>" . $t['course_name'] . " [" . $student_total[$t['course_id']] . "]</option>";
        }
        ?>
    </select>
    <br>
    <button type="submit" name="delete_course_students" class="btn btn-danger">Delete Course Students</button>
</form>