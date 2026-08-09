<!-- <div id='response'></div> -->

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        <script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
        <h5><b>Add Question</b></h5>
        <p><br></p>
        <div id="form-response"></div>

        <form method="post" id="form" enctype="multipart/form-data">
            <input type='hidden' name="action" value="add-question">
            <input type='hidden' name="qid" value="<?= $_GET['qid']; ?>">
            <!--            <input type='hidden' name="cat_id" value="<?= $_GET['cat_id']; ?>">-->
            <input type='hidden' name="limit" value="<?= $_GET['limit']; ?>">

            <?php
            if ($_GET['cat_id'] != 71) {
            ?>
                <!-- Additional code for category 71 goes here -->
            <?php
            }
            ?>

            <div class='row form-group' id='options'>
                <div class='col-md-3'>
                    <label class='form-control-label'>Category</label>
                </div>
                <div class='col-md-9'>
                    <select name='cat_id' id='categoryInput' class='form-control' size=1>
                        <option value=''>Select or Enter Category</option>
                        <?php
                        foreach ($categories as $c) {
                            if ($_GET['cat_id'] == $c['cat_id']) {
                                echo "<option value='" . $c['cat_id'] . "' selected>" . $c['cat_id'] . "</option>";
                            } else {
                                echo "<option value='" . $c['cat_id'] . "'>" . $c['cat_id'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class='row form-group'>
                <div class='col-md-3'>
                    <label class='form-control-label'>Story</label>
                </div>
                <div class='col-md-9'>
                    <select name='story_id' id="storyInput" class='form-control' size=1>
                        <option value=''>Select Story</option>
                        <?php
                        if ($_GET['story_id']) {
                            $db_value['story_id'] = $_GET['story_id'];
                        }
                        foreach ($story_list as $s) {
                            if ($db_value['story_id'] == $s['story_id']) {
                                echo "<option value='" . $s['story_id'] . "' selected>" . $s['story_title'] . "</option>";
                            } else {
                                echo "<option value='" . $s['story_id'] . "'>" . $s['story_title'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>Question</label></div>
                <div class='col-md-9'><textarea class='form-control' name='question' id='question' required><?= html_entity_decode($db_value['question']); ?></textarea></div>
            </div>

            <?php
            $condition = 1;
            if ($condition) {
                if ($total_answers) {
                    for ($i = 1; $i <= $option_no; $i++) {
                        $option_label = chr(96 + $i);
                        echo "<div class='row form-group'>
                                <div class='col-md-3'><label class='form-control-label'>Option " . $option_label . "</label></div>
                                <div class='col-md-9'><textarea id='answer" . $i . "' class='form-control' name='option_" . $option_label . "'>" . html_entity_decode($db_value['option_' . $option_label]) . "</textarea></div>
                            </div>";
                        if ($option_no == $i) break;
                    }
                } else {
                    for ($i = 1; $i <= $option_no; $i++) {
                        $option_label = chr(96 + $i);
                        echo "<div class='row form-group'>
                                <div class='col-md-3'><label class='form-control-label'>Option " . $option_label . "</label></div>
                                <div class='col-md-9'><textarea  id='answer" . $i . "' class='form-control' name='option_" . $option_label . "'></textarea></div>
                            </div>";
                    }
                }
            ?>
                <div class='row form-group'>
                    <div class='col-md-3'><label class='form-control-label'>Answer</label></div>
                    <div class='col-md-9'>
                        <select name='answer' class='form-control' required>
                            <option value=''>Select Answer</option>
                            <?php
                            for ($i = 1; $i <= $option_no; $i++) {
                                $option_label = chr(96 + $i);
                                if ($db_value['answer'] == $i) {
                                    echo "<option value='" . $i . "' selected>Option " . $option_label . "</option>";
                                } else {
                                    echo "<option value='" . $i . "'>Option " . $option_label . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            <?php
            }
            ?>

            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>Clarification [optional]</label></div>
                <div class='col-md-9'><textarea placeholder="Explanation" class='form-control' name='explanation' id='explanation'><?= html_entity_decode(html_entity_decode($db_value['explanation'])); ?></textarea></div>
            </div>

            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>Question Tags</label></div>
                <div class='col-md-9'><input type="text" name='question_tag' value='<?= $db_value['question_tag'] ?>' class='form-control' placeholder="Question Tags"></div>
            </div>

            <div class='row form-group'>
                <div class='col-md-3'>
                    <label class='form-control-label'>Subject</label>
                </div>
                <div class='col-md-9'>
                    <select name='subject' id="subjectInput" class='form-control' size=1 >
                        <option value=''>Select Subject</option>
                        <?php
                        if ($_GET['subject']) {
                            $db_value['subject'] = $_GET['subject'];
                        }
                        foreach ($subject_list as $s) {
                            if ($db_value['subject'] == $s['subject']) {
                                echo "<option value='" . $s['subject'] . "' selected>" . $s['subject'] . "</option>";
                            } else {
                                echo "<option value='" . $s['subject'] . "'>" . $s['subject'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class='row form-group'>
                <div class='col-md-3'>
                    <label class='form-control-label'>Chapter</label>
                </div>
                <div class='col-md-9'>
                    <select name='chapter' id="chapterInput" class='form-control' size=1 >
                        <option value=''>Select Chapter</option>
                        <?php
                        if ($_GET['chapter']) {
                            $db_value['chapter'] = $_GET['chapter'];
                        }
                        foreach ($chapter_list as $s) {
                            if ($db_value['chapter'] == $s['chapter']) {
                                echo "<option value='" . $s['chapter'] . "' selected>" . $s['chapter'] . "</option>";
                            } else {
                                echo "<option value='" . $s['chapter'] . "'>" . $s['chapter'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class='row form-group'>
                <div class='col-md-3'>
                    <label class='form-control-label'>Topic</label>
                </div>
                <div class='col-md-9'>
                    <select name='topic' id="topicInput" class='form-control' size=1 >
                        <option value=''>Select Topic</option>
                        <?php
                        if ($_GET['topic']) {
                            $db_value['topic'] = $_GET['topic'];
                        }
                        foreach ($topic_list as $s) {
                            if ($db_value['topic'] == $s['topic']) {
                                echo "<option value='" . $s['topic'] . "' selected>" . $s['topic'] . "</option>";
                            } else {
                                echo "<option value='" . $s['topic'] . "'>" . $s['topic'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>Source</label></div>
                <div class='col-md-9'><input type="text" name='source' value='<?= $db_value['source'] ?>' class='form-control'></div>
            </div>

            <div class='row form-group'>
                <div class='col-md-3'></div>
                <div class='col-md-9'><input type="submit" class="btn btn-success" name='submit' value='Save'></div>
            </div>

        </form>

    </div>
</div>

<div class='row form-group'>
    <div class='col-md-3'></div>
    <div class='col-md-9'><div id='response'></div></div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#topicInput').select2({
            placeholder: 'Select or Search Topic',
            allowClear: true,
            tags: true
        });

        // Listen for changes in Select2 and update the hidden field accordingly
        $('#topicInput').on('change', function() {
            var storyChoiceField = document.getElementById('topicChoice');
            if ($(this).val() === '') {
                storyChoiceField.value = 'input';
            } else {
                storyChoiceField.value = 'select';
            }
        });
    });

    $(document).ready(function() {
        $('#chapterInput').select2({
            placeholder: 'Select or Search Chapter',
            allowClear: true,
            tags: true
        });

        // Listen for changes in Select2 and update the hidden field accordingly
        $('#chapterInput').on('change', function() {
            var storyChoiceField = document.getElementById('chapterChoice');
            if ($(this).val() === '') {
                storyChoiceField.value = 'input';
            } else {
                storyChoiceField.value = 'select';
            }
        });
    });

    $(document).ready(function() {
        $('#subjectInput').select2({
            placeholder: 'Select or Search Subject',
            allowClear: true,
            tags: true
        });

        // Listen for changes in Select2 and update the hidden field accordingly
        $('#subjectInput').on('change', function() {
            var storyChoiceField = document.getElementById('subjectChoice');
            if ($(this).val() === '') {
                storyChoiceField.value = 'input';
            } else {
                storyChoiceField.value = 'select';
            }
        });
    });

    $(document).ready(function() {
        $('#storyInput').select2({
            placeholder: 'Select or Search Story',
            allowClear: true,
            tags: true
        });

        // Listen for changes in Select2 and update the hidden field accordingly
        $('#storyInput').on('change', function() {
            var storyChoiceField = document.getElementById('storyChoice');
            if ($(this).val() === '') {
                storyChoiceField.value = 'input';
            } else {
                storyChoiceField.value = 'select';
            }
        });
    });

    // Initialize Select2 on the dropdown
    $(document).ready(function() {
        $('#categoryInput').select2({
            placeholder: 'Select or Enter Category',
            allowClear: true,
            tags: true
        });

        // Listen for changes in Select2 and update the hidden field accordingly
        $('#categoryInput').on('change', function() {
            var categoryChoiceField = document.getElementById('categoryChoice');
            if ($(this).val() === '') {
                categoryChoiceField.value = 'input';
            } else {
                categoryChoiceField.value = 'select';
            }
        });
    });

    CKEDITOR.replace('question', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 120,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form',
        versionCheck: false
    });

    // Replace 'answer1', 'answer2', ..., 'answer5' with the respective IDs
    for (let i = 1; i <= 5; i++) {
        CKEDITOR.replace('answer' + i, {
            extraPlugins: 'mathjax',
            mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
            height: 80,
            filebrowserUploadUrl: 'application/ck_upload.php',
            filebrowserUploadMethod: 'form',
            versionCheck: false
        });
    }

    CKEDITOR.replace('explanation', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 80,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form',
        versionCheck: false
    });

    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
        document.getElementById('ie8-warning').className = 'tip alert';
    }
</script>