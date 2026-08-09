<br>
<?
if ($page_error) {
    echo $page_error;
} else {
?>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <b>&nbsp;&nbsp;Prediction Category Details</b>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered mb-0">
                <tr>
                    <th>Pred Category</th>
                    <td><?= htmlspecialchars($pred_name) ?> (<?= htmlspecialchars($pred_code) ?>)</td>
                    <th>Total Questions</th>
                    <td><?= htmlspecialchars($total) ?></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td colspan="3"><?= htmlspecialchars($pred_desciption) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div id='response'></div>

    <ul class="nav nav-tabs">
    </ul>
    <div class="tab-content" style='overflow-x: auto;'>
        <div class="tab-pane fade in active">

            <form method="post" id="hw_form" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
                <input type='hidden' name="pred_id" value="<?= htmlspecialchars($_GET['pred_id'] ?? '') ?>">

                <div class="row">

                    <div class="col-md-5"></div>

                    <!-- Remove Questions -->
                    <div class="col-md-2">
                        <input type="submit" value="Remove" name="remove_hw" class="btn btn-primary w-100">
                    </div>

                    <!-- Add Questions Link -->
                    <div class="col-md-2">
                        <a class="btn btn-primary w-100" href="add-pred-ques?pred_id=<?= $_GET['pred_id']; ?>&add_to=pred_ques">
                            Add
                        </a>
                    </div>
                </div>

                <br>

                <div id="selectedCountTop" style="margin: 10px 0; font-weight: bold; color: #16A085; display:none;">Selected Questions: <span id="countNumber">0</span>
                </div>

                <table class='table table-bordered' width=100%>
                    <thead style="background-color: #16A085">
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
                                if ($f['pred_id']) {
                                    $story = "";
                                    if ($f['story_id']) {
                                        $title = getFldValueById("story", "story_title", array("story_id" => $f['story_id']));
                                        //                                $story="<font color='#1c8348'>Story Id : " .$f['story_id'] ." | ".$title ."</font>";
                                        $story = "<a class='story-link' href='" . $configs['vpath'] . $configs['admin_dir'] . "/view-story?story_id=" . $f['story_id'] . "' target='_blank'><font>Story ID: " . $f['story_id'] . " | " . $title . "</font></a>";
                                    }

                                    echo "
                            <tr style='background-color:#CCD2D5'>
                                <td><input type='checkbox' name='ids[]' value='" . $f['id'] . "' class='checkBoxClass'></td>
                                <td>" . $f['qid'] . "</td>
                                <td>
                                    <a href='#' class='set-order-btn' data-question-id=" . $f['id'] . "><i class='fa fa-edit'></i></a>&nbsp;<span id='order-field-" . $f['qid'] . "'>" . $f['order_no'] . "</span>
                                </td>
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
                                    <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&del_id=" . $f['id'] . "';}\" >Delete</a>
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
                            }
                        } else {
                            echo "<tr><td colspan='10' style='text-align: center;'>No Questions to View.</td></tr>";
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

            <!-- Order Modal -->
            <div class="modal fade" id="setOrderModal" tabindex="-1" role="dialog" aria-labelledby="setOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="setOrderModalLabel">Set Question Order</h5>
                        </div>
                        <div class="modal-body">
                            <label for="orderInput">Enter Order:</label>
                            <input type="number" id="orderInput" class="form-control" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="saveOrderBtn">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?
}
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>

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

    $(document).on('click', '.set-order-btn', function(e) {
        e.preventDefault();
        const $td = $(this).closest('td');
        const $span = $td.find('span[id^="order-field-"]');

        $('#setOrderModal')
            .data('question-id', $(this).data('question-id'))
            .data('target-span', $span);

        // optional: preload current value into the input
        const current = $span.text().trim();
        $('#orderInput').val(current === '—' ? '' : current);

        $('#setOrderModal').modal('show');
    });


    $(document).ready(function() {
        /*
                // Trigger the modal when the link is clicked
                $('.set-order-btn').click(function(e) {
                    e.preventDefault(); // Prevent default link behavior

                    // Get the question ID
                    var questionId = $(this).data('question-id');
                    $('#setOrderModal').data('question-id', questionId); // Store the question ID in the modal

                    var questionType = $(this).data('question-type');
                    // Populate the input field with the current question type
                    $('#orderInput').val(questionType);

                    // Show the modal
                    $('#setOrderModal').modal('show');
                });
        */


        // Handle the save button click
        $(function() {
            function saveOrder() {
                var questionId = $('#setOrderModal').data('question-id');
                var order = $('#orderInput').val();
                //var order = (orderVal.trim() === '') ? null : orderVal;

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                    data: {
                        action: 'setOrder',
                        questionId: questionId,
                        order: order
                    },
                    success: function(res) {
                        // Close the modal
                        $('#setOrderModal').modal('hide');

                        // Get the target span we saved earlier
                        const $span = $('#setOrderModal').data('target-span');

                        // Decide what to display when null/empty
                        const display = (res && (res.order === null || res.order === '')) ? '' : res.order;

                        if ($span && $span.length) {
                            $span.text(display);

                            // Optional: quick highlight to show it updated
                            $span.addClass('order-updated');
                            setTimeout(() => $span.removeClass('order-updated'), 800);
                        }

                        // No page reload needed
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                    }
                });
            }

            // Click
            $(document).on('click', '#saveOrderBtn', function(e) {
                e.preventDefault();
                saveOrder();
            });

            // Enter on the input
            $(document).on('keydown', '#orderInput', function(e) {
                if (e.key === 'Enter' || e.which === 13) {
                    e.preventDefault();
                    saveOrder();
                }
            });
            $('#setOrderModal').on('shown.bs.modal', function() {
                $('#orderInput').focus();
            });
        });

    });
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

<style>
    .story-link {
        color: #1c8348;
        text-decoration: none;
    }

    .story-link:hover {
        color: #e2bb1d;
        text-decoration: underline;
    }
</style>