<br>
<div class="card mt-3">
    <div class="card-header bg-primary text-white">
        <b>&nbsp;&nbsp;Prediction Category Details</b>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered mb-0">
            <tr>
                <th>Pred Category</th>
                <td><?= htmlspecialchars($pred_name) ?> (<?= htmlspecialchars($pred_code) ?>)</td>
                <th>Total Added Questions</th>
                <td><?= htmlspecialchars($total_hw) ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td colspan="3"><?= htmlspecialchars($pred_desciption) ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        <form method="post" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
            <input type='hidden' name="pred_id" value="<?= $_GET['pred_id']; ?>">
            <input type='hidden' name="add_to" value="<?= $_GET['add_to']; ?>">

            <table class='table table-bordered table-striped'>
                <tr>
                    <td width=25%>
                        <input type='text' class='form-control' name='search' placeholder='Search By Question Phrase'>
                    </td>
                    <td width=25%>
                        <select name='cat_id_drop' class='form-control'>
                            <option value=''>Select Category</option>
                            <?php
                            foreach ($categories as $c) {
                                echo "<option value='" . $c['cat_id'] . "'>" . $c['cat_id'] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width=25%>
                        <input type='text' class='form-control' name='question_tag' placeholder='Enter Question Tags'>
                    </td>
                    <td width=25%>
                        <input type='text' class='form-control' name='search_id' placeholder='Search By Question ID'>
                    </td>
                </tr>

                <tr>
                    <td width=25%>
                        <select name='subject' id='subject' class='form-control' size=1>
                            <option value=''>Select Subject</option>
                            <?php
                            foreach ($subject_list as $c) {
                                echo "<option value='" . $c['subject'] . "'>" . $c['subject'] . "</option>";
                            }
                            ?>
                        </select>
                    </td>

                    <td width=25%>
                        <select name='chapter' id='chapter' class='form-control' size=1>
                            <option value=''>Select Chapter</option>
                        </select>
                    </td>

                    <td width=25%>
                        <select name='topic' id='topic' class='form-control' size=1>
                            <option value=''>Select Topic</option>
                        </select>
                    </td>

                    <td width=25%>
                        <input type='submit' value='Search' name='ok' class='btn btn-success' style='width:100%'>
                    </td>
                </tr>

                <tr>
                    <!-- Course Dropdown -->
                    <td width="25%">
                        <select name="course_id" id="course_id" class="form-control" size="1" data-selected="<?= $_GET['course_id'] ?? '' ?>">
                            <option value=''>Select Course</option>
                            <?php foreach ($course as $c): ?>
                                <option value='<?= $c['course_id'] ?>' <?= (($_GET['course_id'] ?? '') == $c['course_id']) ? 'selected' : '' ?>>
                                    <?= $c['course_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <!-- Category Dropdown (Initially empty, populated via AJAX) -->
                    <td width="25%">
                        <select name="cat_id" id="cat_id" class="form-control" size="1" data-selected="<?= $_GET['cat_id'] ?? '' ?>">
                            <option value=''>Select Category</option>
                        </select>
                    </td>

                    <!-- Lesson Dropdown (Initially empty, populated via AJAX) -->
                    <td width="25%">
                        <select name="lesson_id" id="lesson_id" class="form-control" size="1" data-selected="<?= $_GET['lesson_id'] ?? '' ?>">
                            <option value=''>Select Lesson</option>
                        </select>
                    </td>
                
                    <td width=25%>
                        <input type='button' value='Clear Filter' name='ok' class='btn btn-success' style='width:100%'>
                    </td>
                    
                </tr>

            </table>

        </form>

        <form method="post" id="myForm" enctype="multipart/form-data">
            <input type='hidden' name="add_to" value="<?= $_GET['add_to']; ?>">
            <input type='hidden' name="pred_id" value="<?= $_GET['pred_id']; ?>">

            <input style="float: right; width: 30%; margin-bottom: 10px;" type='submit' value='Add Questions in <?= $pred_code ?>' name='save_button' class='btn btn-success' onclick="return confirmSubmit();">
            <div id="selectedCountTop" style="margin: 10px 0; font-weight: bold; color: #16A085; display:none;">Selected Questions: <span id="countNumber">0</span>
            </div>

            <table class='table table-bordered'>
                <thead style="background-color:#16A085">
                    <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                    <th width=2%>Q.ID</th>
                    <th width=5%>Order</th>
                    <th width=10%>Category</th>
                    <th width=10%>Story ID</th>
                    <th width=5%>Subject</th>
                    <th>Chapter</th>
                    <th>Topic</th>
                    <th>Question Tags</th>
                    <th width=15%>Action</th>
                </thead>

                <tbody>
                    <?
                    if ($total) {
                        foreach ($db_list as $f) {

                            $story = "";
                            if ($f['story_id']) {
                                $title = getFldValueById("story", "story_title", array("story_id" => $f['story_id']));
                                $story = "<font color='#1c8348'>Story Id : " . $f['story_id'] . " | " . $title . "</font>";
                            }

                            echo "
                            <tr style='background-color:#CCD2D5'>
                                <td><input type='checkbox' name='ids[]' value='" . $f['qid'] . "' class='checkBoxClass'></td>
                                <td>" . $f['qid'] . "</td>
                                <td>" . (int)$f['order_no'] . "</td>
                                <td>" . $f['cat_id'] . "</td>
                                <td>" . $story . "</td>
                                <td>
                                    <a href='#' class='set-subject-btn' data-question-id=" . $f['qid'] . "><i class='fa fa-edit'></i></a>&nbsp;<span id='subject-field-" . $f['qid'] . "'>" . $f['subject'] . "</span>
                                </td>
                                <td>
                                    <a href='#' class='set-chapter-btn' data-question-id=" . $f['qid'] . "><i class='fa fa-edit'></i></a>&nbsp;<span id='chapter-field-" . $f['qid'] . "'>" . $f['chapter'] . "</span>
                                </td>
                                <td>
                                    <a href='#' class='set-topic-btn' data-question-id=" . $f['qid'] . "><i class='fa fa-edit'></i></a>&nbsp;<span id='topic-field-" . $f['qid'] . "'>" . $f['topic'] . "</span>
                                </td> 
                                <td>
                                    <a href='#' class='set-type-btn' data-question-id=" . $f['qid'] . " data-question-type='" . $f['question_tag'] . "'><i class='fa fa-edit'></i></a>&nbsp;<span id='type-field-" . $f['qid'] . "'>" . $f['question_tag'] . "</span>
                                </td>
                                <td>
                                    <i class='fa fa-edit'></i> 
                                    <a href='add_questions?cat_id=" . $f['cat_id'] . "&qid=" . $f['qid'] . "&limit=" . $_GET['limit'] . "'>Edit</a> &nbsp;

                                    <i class='fa fa-trash'></i> 							
                                    <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&del_id=" . $f['qid'] . "';}\" >Delete</a>
                                </td>
                            </tr>";

                            echo "
                            <tr style='background:#f5f5f5'>
                                <td colspan=10>" . html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question']))) . "</td>
                            </tr>";

                            for ($i = 1; $i <= $option_no; $i++) {
                                $ans = $alphabet[$i];
                                $ind = 'option_' . strtolower($ans);
                                if ($f[$ind]) {
                                    if ($f['answer'] == $i) {
                                        $ans = '<span class="badge badge-success">' . $alphabet[$i] . '</a>';
                                    }
                                    echo "
                                    <tr>
                                        <td width=5>" . $ans . "</td>
                                        <td class='text-align:left' colspan=9>" . html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind]))) . "</td>
                                    </tr>";
                                }
                            }

                            echo "
                            <tr style='background:#f5f5f5'>
                                <td class='text-align:left' colspan=10><b>Explanation: </b><br><br>" . html_entity_decode(html_entity_decode($f['explanation'])) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>No Questions to View.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>

        <!-- Question Tags Modal -->
        <div class="modal fade" id="setTypeModal" tabindex="-1" role="dialog" aria-labelledby="setTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setTypeModalLabel">Set Question Tags</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="typeInput">Enter Question Tags:</label>
                        <input type="text" id="typeInput" class="form-control" placeholder="Enter Question Tags">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveTypeBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Chapter Modal -->
        <div class="modal fade" id="setChapterModal" tabindex="-1" role="dialog" aria-labelledby="setChapterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setChapterModalLabel">Set Question Chapter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="chapterInput">Enter Question Chapter:</label>
                        <input type="text" id="chapterInput" class="form-control" placeholder="Enter Question Chapter">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveChapterBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Modal -->
        <div class="modal fade" id="setSubjectModal" tabindex="-1" role="dialog" aria-labelledby="setSubjectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setSubjectModalLabel">Set Question Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="subjectInput">Enter Question Subject:</label>
                        <input type="text" id="subjectInput" class="form-control" placeholder="Enter Question Subject">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveSubjectBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topic Modal -->
        <div class="modal fade" id="setTopicModal" tabindex="-1" role="dialog" aria-labelledby="setTopicModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setTopicModalLabel">Set Question Topic</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="topicInput">Enter Question Topic:</label>
                        <input type="text" id="topicInput" class="form-control" placeholder="Enter Question Topic">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveTopicBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <?
        // if ($total > 100) {
        //     echo "<ul class='pagination'>" . paging($total, 100, "course_id=" . $_GET['course_id'] . "&cat_id=" . $_GET['cat_id'] . "&lesson_id=" . $_GET['lesson_id'] . "&add_to=" . $_GET['add_to'] . "&cat_id_drop=" . $category . "&search_id=" . $search_id . "&search=" . $search_phrage . "&question_tag=" . $ques_type . "&subject=" . $subject . "&chapter=" . $chapter . "&topic=" . $topic."&pred_id=".$pred_id) . "</ul>";
        // }

        ?>
    </div>
</div>

<script>
    // Function to update selected question counters
    function updateSelectedCount() {
        var selectedCount = $('.checkBoxClass:checked').length;

        // Update counter text
        $('#selectedCountTop, #selectedCountBottom').find('#countNumber').text(selectedCount);

        // Show/hide counters based on selection
        if (selectedCount > 0) {
            $('#selectedCountTop, #selectedCountBottom').show();
        } else {
            $('#selectedCountTop, #selectedCountBottom').hide();
        }
    }

    // Rest of your existing code remains the same
    $("#ckbCheckAll").click(function() {
        $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    $("#ckbCheckAll1").click(function() {
        $(".checkBoxClass1").prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    $(document).on('change', '.checkBoxClass', function() {
        updateSelectedCount();
    });

    // Initialize count on page load
    updateSelectedCount();
</script>

<script>
    function confirmSubmit() {

        // Get the selected value of the "job" dropdown
        //    var jobValue = document.querySelector('select[name="job"]').value;

        // Define different confirmation messages based on the selected job value
        var confirmationMessage = "";

        //    if (jobValue === 'add') {
        confirmationMessage = "Are you sure you want to add the following questions?";
        //    }

        // Display the confirmation dialog with the appropriate message
        var confirmation = confirm(confirmationMessage);

        // If the user confirms, the form will be submitted
        return confirmation;
    }
</script>

<script>
    $(document).ready(function() {
        // Trigger the modal when the link is clicked
        $('.set-type-btn').click(function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the question ID
            var questionId = $(this).data('question-id');
            $('#setTypeModal').data('question-id', questionId); // Store the question ID in the modal

            var questionType = $(this).data('question-type');
            // Populate the input field with the current question type
            $('#typeInput').val(questionType);

            // Show the modal
            $('#setTypeModal').modal('show');
        });

        // Handle the save button click
        $('#saveTypeBtn').click(function() {
            // Get the stored question ID and input value
            var questionId = $('#setTypeModal').data('question-id');
            var typeValue = $('#typeInput').val();

            // AJAX request to update the database
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "action=setQuesType&questionId=" + questionId + "&question_tag=" + typeValue,
                success: function(response) {
                    //                alert(response); // Show success message

                    // Update the type field dynamically
                    $('#type-field-' + questionId).html(response.type); // Assuming `type` is sent in the response

                    // Update the `data-question-type` of the button to reflect the new value
                    $('.set-type-btn[data-question-id="' + questionId + '"]').data('question-type', response.type);

                    $('#setTypeModal').modal('hide'); // Hide the modal
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error); // Show error message
                }
            });
        });

        // Trigger the modal when the link is clicked
        $('.set-chapter-btn').click(function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the question ID
            var questionId = $(this).data('question-id');
            //        if (!questionId) {
            //            alert("Question ID is missing!");
            //            return;
            //        }

            $('#setChapterModal').data('question-id', questionId); // Store the question ID in the modal

            // Show the modal
            $('#setChapterModal').modal('show');
        });

        // Handle the save button click
        $('#saveChapterBtn').click(function() {
            // Get the stored question ID and input value
            var questionId = $('#setChapterModal').data('question-id');
            var chapterValue = $('#chapterInput').val();
            //        if (!questionId) {
            //            alert("Question ID is not set!");
            //            return;
            //        }

            // AJAX request to update the database
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "action=setQuesChapter&questionId=" + questionId + "&chapter=" + chapterValue,
                success: function(response) {
                    //                alert(response.msg); // Show success message

                    // Update the type field dynamically
                    $('#chapter-field-' + questionId).html(response.chapter); // Assuming `type` is sent in the response

                    // Update the `data-question-type` of the button to reflect the new value
                    //                $('.set-type-btn[data-question-id="' + questionId + '"]').data('question-type', response.type);

                    $('#setChapterModal').modal('hide'); // Hide the modal
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error); // Show error message
                }
            });
        });


        // Trigger the modal when the link is clicked
        $('.set-topic-btn').click(function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the question ID
            var questionId = $(this).data('question-id');

            $('#setTopicModal').data('question-id', questionId); // Store the question ID in the modal

            // Show the modal
            $('#setTopicModal').modal('show');
        });

        // Handle the save button click
        $('#saveTopicBtn').click(function() {
            // Get the stored question ID and input value
            var questionId = $('#setTopicModal').data('question-id');
            var topicValue = $('#topicInput').val();

            // AJAX request to update the database
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "action=setQuesTopic&questionId=" + questionId + "&topic=" + topicValue,
                success: function(response) {
                    //                alert(response.topic);
                    // Update the type field dynamically

                    $('#topic-field-' + questionId).html(response.topic);

                    $('#setTopicModal').modal('hide'); // Hide the modal
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error); // Show error message
                }
            });
        });

        // Trigger the modal when the link is clicked
        $('.set-subject-btn').click(function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the question ID
            var questionId = $(this).data('question-id');
            //        if (!questionId) {
            //            alert("Question ID is missing!");
            //            return;
            //        }

            $('#setSubjectModal').data('question-id', questionId); // Store the question ID in the modal

            // Show the modal
            $('#setSubjectModal').modal('show');
        });

        // Handle the save button click
        $('#saveSubjectBtn').click(function() {
            // Get the stored question ID and input value
            var questionId = $('#setSubjectModal').data('question-id');
            var subjectValue = $('#subjectInput').val();
            //        if (!questionId) {
            //            alert("Question ID is not set!");
            //            return;
            //        }

            // AJAX request to update the database
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "action=setQuesSubject&questionId=" + questionId + "&subject=" + subjectValue,
                success: function(response) {
                    //                alert(response.msg); // Show success message

                    // Update the type field dynamically
                    $('#subject-field-' + questionId).html(response.subject); // Assuming `type` is sent in the response

                    // Update the `data-question-type` of the button to reflect the new value
                    //                $('.set-type-btn[data-question-id="' + questionId + '"]').data('question-type', response.type);

                    $('#setSubjectModal').modal('hide'); // Hide the modal
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error); // Show error message
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Handle chapter selection change
        $('#chapter').change(function() {
            var selectedChapter = $(this).val();

            if (selectedChapter === "") {
                // Reset topic dropdown if no chapter is selected
                $('#topic').html("<option value=''>Select Topic</option>");
                return;
            }

            // AJAX request to fetch topics based on the selected chapter
            $.ajax({
                type: 'POST',
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax", // Adjust URL to your endpoint
                data: {
                    action: 'getTopicsByChapter',
                    chapter: selectedChapter,
                    cat_id: "<?= $cat_id; ?>",
                    lesson_id: "<?= $lesson_id; ?>",
                    course_id: "<?= $course_id; ?>"
                },
                dataType: 'json',
                success: function(response) {
                    if (response.options) {
                        // Populate the topic dropdown
                        $('#topic').html(response.options);
                    } else {
                        alert("No topics found for this chapter.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                }
            });
        });

        // Handle subject selection change
        $('#subject').change(function() {
            var selectedSubject = $(this).val();

            if (selectedSubject === "") {
                // Reset chapter dropdown if no subject is selected
                $('#chapter').html("<option value=''>Select Chapter</option>");
                return;
            }

            // AJAX request to fetch chapter based on the selected chapter
            $.ajax({
                type: 'POST',
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax", // Adjust URL to your endpoint
                data: {
                    action: 'getChapterBySubject',
                    subject: selectedSubject,
                    cat_id: "<?= $cat_id; ?>",
                    lesson_id: "<?= $lesson_id; ?>",
                    course_id: "<?= $course_id; ?>"
                },
                dataType: 'json',
                success: function(response) {
                    if (response.options) {
                        // Populate the chapter dropdown
                        $('#chapter').html(response.options);

                    } else {
                        alert("No chapter found for this subject.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                }
            });
        });
    });
</script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
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
                success: function (catResponse) {
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
                            success: function (lessonResponse) {
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


    $(document).ready(function() {

        // When Clear Filter is clicked
        $(document).on("click", "input[value='Clear Filter']", function(e) {

            e.preventDefault();
            // Reset all text inputs
            $("input[type='text']").val('');
            $("input[name='search_id']").val('');
            $("input[name='search']").val('');
            $("input[name='question_tag']").val('');
            $("input[name='chapter']").val('');
            $("input[name='topic']").val('');

            // Reset all dropdowns
            $("select").prop('selectedIndex', 0);

            // Reset hidden fields
            $("input[type='hidden']").val('');

            // Submit the form to reload questions with no filters
            //$(this).closest("form").submit();
        });

    });
</script>

<style>
    .info-block {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }

    .info-block div {
        margin-bottom: 5px;
    }
</style>