<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>

<br>

<div id='response'>
    <!--
    <?php
 echo '<pre>';
if($query_lesson_test){
    echo 'query_lesson_test:';
    print_r($query_lesson_test);
   // echo ' lesson_id:';
   // print_r($lesson_id_test);
} else{
    echo "no id";
}

//echo $_SERVER['REQUEST_URI'];
echo '</pre>';
    ?>
    -->
</div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        <div class="btn-toolbar" role="toolbar" aria-label="Question Actions">
            <div class="btn-group mr-2" role="group" aria-label="First group">
                <a class="btn btn-primary" href='<?= $configs['vpath'] ?>admincp/add_questions'>Add New Questions</a>
            </div>
            <div class="btn-group" role="group" aria-label="Second group">
                <a class="btn btn-primary" href='<?= $configs['vpath'] ?>admincp/add_lesson_questions'>Add Questions In Lesson</a>
            </div>
            <div class="btn-group mr-2" role="group" aria-label="First group">
                <a class="btn btn-primary" href='<?= $configs['vpath'] ?>admincp/add_questions_csv'>Add New Questions From CSV File</a>
            </div>
        </div>


        <form method="get" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">


            <br>

            <table class='table table-bordered table-striped'>
                <tr>
                    <td width=25%>
                        <input type='text' class='form-control' name='search' placeholder='Search By Question Phrase' value='<?= htmlspecialchars($_GET["search"] ?? "") ?>'>
                    </td>
                    <td width=25%>
                        <?php $selected_cat = $_GET['cat_id_drop'] ?? ''; ?>
                        <select name="cat_id_drop" class="form-control" >
                            <option value="" <?= ($selected_cat == '') ? 'selected' : '' ?>>Select Category</option>

                            <?php foreach ($categories as $c): ?>
                                <?php if (empty($c['cat_id'])) continue; ?>
                                <option value="<?= $c['cat_id'] ?>" <?= ($selected_cat == $c['cat_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['cat_id']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </td>


                    <td width=25%>
                        <input type='text' class='form-control' name='question_tag' placeholder='Enter Question Tags' value='<?= htmlspecialchars($_GET["question_tag"] ?? "") ?>'>
                    </td>
                    <td width=25%>
                        <input type='text' class='form-control' name='search_id' placeholder='Search By Question ID' value='<?= htmlspecialchars($_GET["search_id"] ?? "") ?>'>
                    </td>
                </tr>

                <tr>
                    <!-- Subject -->
                    <td width="25%">
                        <select name="subject" id="subject" class="form-control" size="1" data-selected="<?= $_GET['subject'] ?? '' ?>">
                            <option value="">Select Subject</option>
                            <?php foreach ($subject_list as $c): ?>
                                <option value="<?= $c['subject'] ?>" <?= (($_GET['subject'] ?? '') == $c['subject']) ? 'selected' : '' ?>>
                                    <?= $c['subject'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <!-- Chapter (loaded via JS) -->
                    <td width="25%">
                        <select name="chapter" id="chapter" class="form-control" size="1" data-selected="<?= $_GET['chapter'] ?? '' ?>">
                            <option value="">Select Chapter</option>
                        </select>
                    </td>

                    <!-- Topic (loaded via JS) -->
                    <td width="25%">
                        <select name="topic" id="topic" class="form-control" size="1" data-selected="<?= $_GET['topic'] ?? '' ?>">
                            <option value="">Select Topic</option>
                        </select>
                    </td>
                    <td width=25%>
                        <input type='submit' value='Load' name='ok' class='btn btn-success' style='width:100%'>
                    </td>

                </tr>

                <!-- Questions by lessons -->
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

        <form method="post" id="qTypeForm" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">

            <input type="hidden" name="cat_id_drop" value="<?php echo htmlspecialchars($_POST['cat_id_drop'] ?? $_GET['cat_id_drop'] ?? ''); ?>">

            <input type="hidden" name="question_tag" value="<?php echo htmlspecialchars($_POST['question_tag'] ?? $_GET['question_tag'] ?? ''); ?>">
            <input type="hidden" name="search_id" value="<?php echo $_POST['search_id'] ?? $_GET['search_id'] ?? ''; ?>">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_POST['search'] ?? $_GET['search'] ?? ''); ?>">
            <input type="hidden" name="subject" value="<?php echo htmlspecialchars($_POST['subject'] ?? $_GET['subject'] ?? ''); ?>">
            <input type="hidden" name="chapter" value="<?php echo htmlspecialchars($_POST['chapter'] ?? $_GET['chapter'] ?? ''); ?>">
            <input type="hidden" name="topic" value="<?php echo htmlspecialchars($_POST['topic'] ?? $_GET['topic'] ?? ''); ?>">
            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($_POST['course_id'] ?? $_GET['course_id'] ?? ''); ?>">
            <input type="hidden" name="cat_id_drop" value="<?php echo htmlspecialchars($_POST['cat_id_drop'] ?? $_GET['cat_id_drop'] ?? ''); ?>">
            <input type="hidden" name="lesson_id" value="<?php echo htmlspecialchars($_POST['lesson_id'] ?? $_GET['lesson_id'] ?? ''); ?>">

            <table class='table table-bordered'>
                <tbody>
                    <tr>
                        <td width=60%>
                            <!-- New input field for question type -->
                            <input type="text" class="form-control mr-2" name="selectedQuestionData" placeholder="Enter Question Tags or Category or Subject or Chapter or Topic">
                        </td>
                        <td width=20%>
                            <select name='select_option' class='form-control' size=1>
                                <option value=''>Select Option</option>
                                <option value='qtype'>Question Tags</option>
                                <option value='qcategory'>Question Category</option>
                                <option value='qsubject'>Subject</option>
                                <option value='qchapter'>Chapter</option>
                                <option value='qtopic'>Topic</option>
                            </select>
                        </td>
                        <td>
                            <!-- New button to update question type -->
                            <button type="submit" class="btn btn-success" name="updateQuestionData" style='width:100%'>Update Question Data</button>
                        </td>
                    </tr>
                </tbody>
            </table>


            <?php
            if ($total > 100) {
                echo "<ul class='pagination'>" . paging($total, 100, 
                    "cat_id_drop=" . urlencode($category) . 
                    "&search_id=" . urlencode($search_id) . 
                    "&search=" . urlencode($search_phrage) . 
                    "&question_tag=" . urlencode($ques_type) . 
                    "&subject=" . urlencode($subject) . 
                    "&chapter=" . urlencode($chapter) . 
                    "&topic=" . urlencode($topic) . 
                    "&course_id=" . urlencode($course_id) . 
                    "&cat_id=" . urlencode($cat_id) . 
                    "&lesson_id=" . urlencode($lesson_id)) . 
                "</ul>";
            }
            ?>

            <div style="width: 20%; float: right;">
                <button type="submit" class="btn btn-success" name="deleteMultiQuestions" style='width:100%'>Delete Questions</button>
                <div style="margin:0; float: right;">
                    <label>
                        <input type="checkbox" id="orderByQuestion"
                            <?= isset($_GET['order_by_question']) ? 'checked' : '' ?>>
                        Order by Question
                    </label>
                </div>
            </div>

            <!-- Button with checkbox next to it -->
            <div style="width: 25%; float: right; margin-right:5px;">
                <button type="submit" class="btn btn-success" name="download_ques" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span>Download</span>

                    <!-- Visually embed checkbox -->
                    <label style="display: flex; align-items: center; margin: 0; font-weight: normal; font-size: 14px; gap: 4px; color: white;">
                        <input type="checkbox" name="with_id" value="1" style="margin: 0;">
                        <span style="color: white;">With ID</span>

                        <input type="checkbox" name="with_img" value="1" style="margin: 0;">
                        <span style="color: white;">Image</span>
                    </label>
                </button>
            </div>

            <div id="selectedCountTop" style="margin: 10px 0; font-weight: bold; color: #16A085; display:none;">Selected Questions: <span id="countNumber">0</span>
            </div>

            <table class='table table-bordered'>
                <thead style="background-color:#16A085">
                    <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                    <th width=2%>Q.ID</th>
                   <!-- <th width=5%>Order</th> -->
                    <th>Category</th>
                    <th>Story ID</th>
                    <th>Subject</th>
                    <th>Chapter</th>
                    <th>Topic</th>
                    <th>Question Tags</th>
                    <th width=10%>Action</th>
                </thead>

                <tbody>
                    <?
                    if ($total) {
                        foreach ($db_list as $f) {
                            $story = "";
                            if ($f['story_id']) {
                                $title = getFldValueById("story", "story_title", array("story_id" => $f['story_id']));
                                //		                $story="<font color='#1c8348'>Story Id : " .$f['story_id'] ." | ".$title ."</font>";
                                $story = "<a class='story-link' href='" . $configs['vpath'] . $configs['admin_dir'] . "/view-story?story_id=" . $f['story_id'] . "' target='_blank'><font>Story ID: " . $f['story_id'] . " | " . $title . "</font></a>";
                            }

                            echo "
					<tr style='background-color:#CCD2D5'>
						<td><input type='checkbox' name='ids[]' value='" . $f['qid'] . "' class='checkBoxClass'></td>
						<td>" . $f['qid'] . "</td>
						<!--  <td>" . (int)$f['order_no'] . "</td> -->
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
							<br>
							<i class='fa fa-trash'></i> 							
							<a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?cat_id=" . $f['cat_id'] . "&del_id=" . $f['qid'] . "&limit=" . $_GET['limit'] . "';}\" >Delete</a>
						</td>
					</tr>";

                            echo "
                    <tr style='background:#f5f5f5'>
                        <td colspan=10>
                            <a href='#' class='edit-question-btn' data-question-id='" . $f['qid'] . "'><i class='fa fa-edit'></i></a>
                            <div id='question-content-" . $f['qid'] . "'>" . html_entity_decode(html_entity_decode($f['question'])) . "</div>
                            
                        </td>
                    </tr>";

                            for ($i = 1; $i <= $option_no; $i++) {
                                $ans = $alphabet[$i];
                                $ind = 'option_' . strtolower($ans);
                                if ($f[$ind] || $f['cat_id'] == 'Simple Drawings-OCS' || $f['cat_id'] == 'Hidden Figures-OCS') {
                                    if ($f['answer'] == $i) {
                                        $ans = '<span class="badge badge-success">' . $alphabet[$i] . '</a>';
                                    }
                                    echo "
                           <tr>
								<td width=5>" . $ans . "</td>
								<td class='text-align:left' colspan=9>
									<a href='#' class='edit-option-btn' data-question-id='" . $f['qid'] . "' data-option-id='" . $i . "'><i class='fa fa-edit'></i></a>
									<div id='option-content-" . $f['qid'] . "-" . $i . "'>" .
                                        html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) .
                                        "</div>
								</td>
							</tr>";
                                }
                            }

                            echo "
                    <tr style='background:#f5f5f5'>
                        <td class='text-align:left' colspan=10>
                            <b>Explanation: </b>
                            <a href='#' class='edit-explanation-btn' data-question-id='" . $f['qid'] . "'><i class='fa fa-edit'></i></a>
                            <div id='explanation-content-" . $f['qid'] . "'><br>" . html_entity_decode(html_entity_decode($f['explanation'])) . "</div>

                            
                        </td>
                    </tr>";
                        }
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

        <?php
        if ($total > 100) {
            echo "<ul class='pagination'>" . paging($total, 100, "cat_id_drop=" . $category . "&search_id=" . $search_id . "&search=" . $search_phrage . "&question_tag=" . $ques_type . "&subject=" . $subject . "&chapter=" . $chapter . "&topic=" . $topic) . "</ul>";
        }
        ?>
    </div>
</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- MathJax (SVG or HTML config) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML" async></script>

<!-- CKEditor full build with all plugins -->
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>

<!-- Select2 (if you're using it) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>
    //Sort the question alphabetically.
    document.addEventListener("DOMContentLoaded", function() {
        const cb = document.getElementById('orderByQuestion');
        if (cb) {
            cb.addEventListener('change', function() {
                let url = new URL(window.location.href);
                if (this.checked) {
                    url.searchParams.set('order_by_question', '1');
                } else {
                    url.searchParams.delete('order_by_question');
                }
                window.location.href = url.toString();
            });
        }
    });
</script>


<script>
    //Sort the question alphabetically.

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
                    chapter: selectedChapter
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
                    subject: selectedSubject
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

$(document).ready(function () {
    const selectedSubject = $('#subject').data('selected');
    const selectedChapter = $('#chapter').data('selected');
    const selectedTopic = $('#topic').data('selected');

    if (selectedSubject) {
        $.ajax({
            url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
            type: "POST",
            data: {
                action: "getChapterBySubject",
                subject: selectedSubject
            },
            dataType: 'json',
            success: function (chapterResponse) {
                if (chapterResponse.options) {
                    $('#chapter').html(chapterResponse.options);

                    if (selectedChapter) {
                        $('#chapter').val(selectedChapter);

                        $.ajax({
                            url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                            type: "POST",
                            data: {
                                action: "getTopicsByChapter",
                                chapter: selectedChapter
                            },
                            dataType: 'json',
                            success: function (topicResponse) {
                                if (topicResponse.options) {
                                    $('#topic').html(topicResponse.options);

                                    if (selectedTopic) {
                                        $('#topic').val(selectedTopic);
                                    }
                                }
                            }
                        });
                    }
                }
            }
        });
    }
});

</script>
<style>
    .inline-editor-actions {
        margin-top: 10px;
    }

    .inline-editor-actions button {
        margin-right: 5px;
    }
</style>

<script>
    //  Inline editor
    function restoreOriginalContent(containerId, content) {
        $('#' + containerId).html(content);
        if (typeof MathJax !== 'undefined') {
            MathJax.Hub.Queue(["Typeset", MathJax.Hub, document.getElementById(containerId)]);
        }
    }

    // Utility to init editors
    function initEditor(containerId, editorId, content, height, saveClass, cancelClass, saveData) {
        var container = $('#' + containerId);
        var originalContent = container.html();
        container.data('original', originalContent);

        container.html('<textarea id="' + editorId + '"></textarea>');
        CKEDITOR.replace(editorId, {
            extraPlugins: 'mathjax',
            mathJaxLib: "https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML",
            filebrowserUploadUrl: 'application/ck_upload.php',
            filebrowserUploadMethod: 'form',
            height: height,
            versionCheck: false
        });
        CKEDITOR.instances[editorId].setData(content);

        container.append('<div class="inline-editor-actions">\
        <button type="button" class="btn btn-success ' + saveClass + '" data-meta=\'' + JSON.stringify(saveData) + '\'>Save</button>\
        <button type="button" class="btn btn-secondary ' + cancelClass + '">Cancel</button>\
    </div>');
    }

    // === Question Editor ===
    $(document).on('click', '.edit-question-btn', function(e) {
        e.preventDefault();
        var questionId = $(this).data('question-id');
        $.ajax({
            url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax-save-data-inline-editor",
            type: 'POST',
            data: {
                action: 'getQuestionByQuestionId',
                qid: questionId
            },
            dataType: 'json',
            success: function(response) {
                // if (response.status === 'success') {
                initEditor(
                    'question-content-' + questionId,
                    'questionEditInput-' + questionId,
                    response.question,
                    200,
                    'save-question',
                    'cancel-question', {
                        action: 'updateQuestionContent',
                        questionId: questionId
                    }
                );
                // }
            }
        });
    });

    $(document).on('click', '.cancel-question', function() {
        var container = $(this).closest('[id^="question-content-"]');
        restoreOriginalContent(container.attr('id'), container.data('original'));
    });

    // === Option Editor ===
    $(document).on('click', '.edit-option-btn', function(e) {
        e.preventDefault();
        var questionId = $(this).data('question-id');
        var optionId = $(this).data('option-id');
        $.ajax({
            url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax-save-data-inline-editor",
            type: 'POST',
            data: {
                action: 'getOptionByOptionId',
                qid: questionId,
                oid: optionId
            },
            dataType: 'json',
            success: function(response) {
                // if (response.status === 'success') {
                initEditor(
                    'option-content-' + questionId + '-' + optionId,
                    'optionEditInput-' + questionId + '-' + optionId,
                    response.option,
                    200,
                    'save-option',
                    'cancel-option', {
                        action: 'updateOptionContent',
                        questionId: questionId,
                        optionId: optionId
                    }
                );
                // }
            }
        });
    });

    $(document).on('click', '.cancel-option', function() {
        var container = $(this).closest('[id^="option-content-"]');
        restoreOriginalContent(container.attr('id'), container.data('original'));
    });

    // === Explanation Editor ===
    $(document).on('click', '.edit-explanation-btn', function(e) {
        e.preventDefault();
        var questionId = $(this).data('question-id');
        $.ajax({
            url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax-save-data-inline-editor",
            type: 'POST',
            data: {
                action: 'getExplanationByQuestionId',
                qid: questionId
            },
            dataType: 'json',
            success: function(response) {
                //if (response.status === 'success') {
                initEditor(
                    'explanation-content-' + questionId,
                    'explanationEditInput-' + questionId,
                    response.explanation,
                    120,
                    'save-explanation',
                    'cancel-explanation', {
                        action: 'updateExplanationContent',
                        questionId: questionId
                    }
                );
                //}
            }
        });
    });

    $(document).on('click', '.cancel-explanation', function() {
        var container = $(this).closest('[id^="explanation-content-"]');
        restoreOriginalContent(container.attr('id'), container.data('original'));
    });

    // === Save Handlers (delegated) ===
    $(document).on('click', '.save-question, .save-option, .save-explanation', function(e) {
        e.preventDefault();

        var meta = JSON.parse($(this).attr('data-meta'));
        var editorId, containerId, newContent;

        if ($(this).hasClass('save-question')) {
            editorId = 'questionEditInput-' + meta.questionId;
            containerId = 'question-content-' + meta.questionId;
            newContent = CKEDITOR.instances[editorId].getData();

            $.post("<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax-save-data-inline-editor", {
                    action: meta.action,
                    questionId: meta.questionId,
                    content: newContent
                },
                function() {
                    restoreOriginalContent(containerId, newContent);
                    CKEDITOR.instances[editorId].destroy(true);
                },
                'json'
            );
        }

        if ($(this).hasClass('save-option')) {
            editorId = 'optionEditInput-' + meta.questionId + '-' + meta.optionId;
            containerId = 'option-content-' + meta.questionId + '-' + meta.optionId;
            newContent = CKEDITOR.instances[editorId].getData();

            $.post("<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax-save-data-inline-editor", {
                    action: meta.action,
                    questionId: meta.questionId,
                    optionId: meta.optionId,
                    content: newContent
                },
                function() {
                    restoreOriginalContent(containerId, newContent);
                    CKEDITOR.instances[editorId].destroy(true);
                },
                'json'
            );
        }

        if ($(this).hasClass('save-explanation')) {
            editorId = 'explanationEditInput-' + meta.questionId;
            containerId = 'explanation-content-' + meta.questionId;
            newContent = CKEDITOR.instances[editorId].getData();

            $.post("<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax-save-data-inline-editor", {
                    action: meta.action,
                    questionId: meta.questionId,
                    content: newContent
                },
                function() {
                    restoreOriginalContent(containerId, newContent);
                    CKEDITOR.instances[editorId].destroy(true);
                },
                'json'
            );
        }
    });
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>


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
    .story-link {
        color: #1c8348;
        text-decoration: none;
    }

    .story-link:hover {
        color: #e2bb1d;
        text-decoration: underline;
    }

    .edit-question-btn,
    .edit-option-btn,
    .edit-explanation-btn {
        color: #16A085;
        margin-right: 5px;
    }

    .edit-question-btn:hover,
    .edit-option-btn:hover,
    .edit-explanation-btn:hover {
        color: #e2bb1d;
        text-decoration: none;
    }

    .edit-option-btn {
        float: left;
        margin-right: 10px;
    }
</style>