<div class="card">
    <?php
    /*
    echo '<pre>';
    if($qid){
 
       print_r($qid);
       // print_r($pt_check ."-". $HW_check);        
       // echo "checking_flag: " . $checking_flag;
    } else{
        echo "no hw_pt value";
    }
    //echo $_SERVER['REQUEST_URI'];
    echo '</pre>';
    */
    ?>

    <form method="post" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' class="form-inline add-to-lesson-form" style="margin-top: 10px;">

        <input type="hidden" name="qid" value="<?= (int)$qid; ?>">

        <div class="form-group">
            <label for="course_id">Course</label>
            <select name="course_id" id="course_id" class="form-control" size="1" data-selected="<?= $_GET['course_id'] ?? '' ?>">
                <option value=''>Select Course</option>
                <?php foreach ($course as $c): ?>
                    <option value='<?= $c['course_id'] ?>'>
                        <?= $c['course_name'] ?> (<?= $c['course_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <div class="form-group">
            <label for="cat_id">Category</label>
            <select name="cat_id" id="cat_id" class="form-control" size="1" data-selected="<?= $_GET['cat_id'] ?? '' ?>">
                <option value=''>Select Category</option>
            </select>
        </div>

        <div class="form-group">
            <label for="lesson_id">Lesson</label>
            <select name="lesson_id" id="lesson_id" class="form-control" size="1" data-selected="<?= $_GET['lesson_id'] ?? '' ?>">
                <option value=''>Select Lesson</option>
            </select>
        </div>

        <div class="form-group">
            <label for="hw_pt">HW / PT</label>
            <select name="hw_pt" id="hw_pt" class="form-control input-sm" style="width: 70px;">
                <option value="HW">HW</option>
                <option value="PT">PT</option>
            </select>
        </div>
        <div class="form-group">
            <label for="order_no">Ord</label>
            <input name="order_no" id="order_no" class="form-control input-sm" style="width: 50px;">
            </select>
        </div>

        <button type="submit"
            name="add_to_lesson"
            class="btn btn-primary btn-sm">
            Add to Lesson
        </button>

    </form>




    <div class="card-header">
        <h3 class="card-title">The Lesson Having the Question</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($lessons)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Q.ID</th>
                            <th>Course</th>
                            <th>Category</th>
                            <th>Lesson</th>
                            <th>HW/PT</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lessons as $lesson):
                            if ($lesson['pred_id'] == null || $lesson['pred_id'] == "") { ?>
                                <tr>
                                    <td><?php echo $lesson['qid']; ?></td>
                                    <td><?php echo $lesson['course_name'] . " (" . $lesson['course_id'] . ")"; ?></td>
                                    <td><?php echo $lesson['category_name'] . " (" . $lesson['cat_id'] . ")"; ?></td>
                                    <td>
                                        <a href="<?php echo $configs['vpath'] . "admincp/questions_list?lesson_id=" . $lesson['lesson_id'] . "&cat_id=" . $lesson['cat_id'] . "&course_id=" . $lesson['course_id']; ?>"
                                            style="color: blue; text-decoration: underline;">
                                            <?php echo $lesson['lesson_name'] . " (" . $lesson['lesson_id'] . ")"; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        echo ($lesson['HW'] == 1) ? "HW" : "PT";
                                        ?>
                                        <?php
                                        echo !empty($lesson['order_no']) ? "(" . $lesson['order_no'] . ")" : "(-)";
                                        ?>
                                    </td>
                                    <td>
                                        <i class='fa fa-trash'></i>
                                        <?
                                            echo "<a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?qid=" . $_GET['qid'] . "&limit=" . $_GET['limit'] . "&del_id=" . $lesson['id'] . "';}\" >Delete</a>";
                                        ?>
                                    </td>
                                </tr>

                        <?php }
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No lessons found for this Question ID.
            </div>
        <?php endif; ?>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger mt-3">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<style>
    .add-to-lesson-form {
        padding: 8px 10px;
        border: 1px solid #ddd;
        background: #fff;
        margin-bottom: 15px;
    }

    .add-to-lesson-form .form-group {
        margin-right: 12px;
    }

    .add-to-lesson-form label {
        margin-right: 4px;
        font-weight: normal;
    }

    .add-to-lesson-form .form-control {
        width: 140px;

    }

    .add-to-lesson-form #hw_pt {
        width: 100px;

    }
</style>

<script>
    $(document).ready(function() {
        const courseId = $('#course_id').data('selected');
        const selectedCategory = $('#cat_id').data('selected');
        const selectedLesson = $('#lesson_id').data('selected');

        if (courseId) {
            $.ajax({
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                type: "POST",
                data: {
                    action: "getCategories",
                    course_id: courseId
                },
                success: function(catResponse) {
                    $('#cat_id').html(catResponse);

                    if (selectedCategory) {
                        $('#cat_id').val(selectedCategory);

                        // Now load lessons only after category is selected
                        $.ajax({
                            url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                            type: "POST",
                            data: {
                                action: "getLessons",
                                cat_id: selectedCategory
                            },
                            success: function(lessonResponse) {
                                $('#lesson_id').html(lessonResponse);

                                if (selectedLesson) {
                                    $('#lesson_id').val(selectedLesson);
                                }
                            }
                        });
                    }
                }
            });
        }
    });
</script>