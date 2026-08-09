<?
if ($page_error) {
    echo $page_error;
} else {
?>
    <div id='response'></div>

    <ul class="nav nav-tabs">

    </ul>
    <div class="tab-content" style='overflow-x: auto;'>
        <div class="tab-pane fade in active">

            <form method="get" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
                <input type='hidden' name="cat_id" value="<?= htmlspecialchars($_GET['cat_id'] ?? '') ?>">
                <input type='hidden' name="course_id" value="<?= htmlspecialchars($_GET['course_id'] ?? '') ?>">

                <table class='table table-bordered table-striped'>
                    <tr>
                        <td width=25%>
                            <input type='text' class='form-control' name='search' placeholder='Search By Question Phrase' value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </td>
                        <td width=25%>
                            <select name='cat_id_drop' class='form-control'>
                                <option value=''>Select Category</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value='<?= $c['cat_id'] ?>' <?= (($_GET['cat_id_drop'] ?? '') == $c['cat_id']) ? 'selected' : '' ?>>
                                        <?= $c['cat_id'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width=25%>
                            <input type='text' class='form-control' name='question_tag' placeholder='Enter Question Tags' value="<?= htmlspecialchars($_GET['question_tag'] ?? '') ?>">
                        </td>
                        <td width=25%>
                            <input type='text' class='form-control' name='search_id' placeholder='Search By Question ID' value="<?= htmlspecialchars($_GET['search_id'] ?? '') ?>">
                        </td>
                    </tr>

                    <tr>
                        <td width=25%>
                            <select name='subject' id='subject' class='form-control' size=1>
                                <option value=''>Select Subject</option>
                                <?php foreach ($subject_list as $c): ?>
                                    <option value='<?= $c['subject'] ?>' <?= (($_GET['subject'] ?? '') == $c['subject']) ? 'selected' : '' ?>>
                                        <?= $c['subject'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>

                        <td width=25%>
                            <select name='chapter' id='chapter' class='form-control' size=1>
                                <option value=''>Select Chapter</option>
                                <!-- You may populate dynamically via JS based on subject -->
                            </select>
                        </td>

                        <td width=25%>
                            <select name='topic' id='topic' class='form-control' size=1>
                                <option value=''>Select Topic</option>
                                <!-- You may populate dynamically via JS based on chapter -->
                            </select>
                        </td>

                        <td width=25%>
                            <input type='submit' value='Search' name='ok' class='btn btn-success' style='width:100%'>
                        </td>
                    </tr>
                </table>
            </form>


            <form method="post" id="hw_form" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
                <input type='hidden' name="cat_id" value="<?= $_GET['cat_id']; ?>">
                <input type='hidden' name="course_id" value="<?= $_GET['course_id']; ?>">

                <div class="row">
                    <div class="col-md-6">
                        <h3>Questions (<?= $total ?>)</h3>
                    </div>
                    <div class="col-md-3 text-right">
                        <input type='submit' value='Remove Questions' name='remove_hw' class='btn btn-primary'>
                    </div>
                    <div class="col-md-3 text-left">
                        <a class="btn btn-primary" onclick="window.open('add_free_test_questions?cat_id=<?= $_GET['cat_id']; ?>&course_id=<?= $_GET['course_id']; ?>', '_blank')">Add Questions</a>
                    </div>
                </div>

                <table class='table table-bordered' width=100%>
                    <thead style="background-color: #16A085">
                        <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                        <th width=5%>Q.ID</th>
                        <th width=5%>Order</th>
                        <th width=15%>Story ID</th>
                        <th width=15%>Action</th>
                    </thead>

                    <tbody>
                        <?
                        if ($total) {
                            foreach ($db_list as $f) {
                           
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
                                <td>" . $story . "</td>
                                <td>
                                    <i class='fa fa-edit'></i> 
                                    <a href='add_questions?cat_id=" . $f['cat_id'] . "&qid=" . $f['qid'] . "&limit=" . $_GET['limit'] . "'>Edit</a> &nbsp;

                                    <i class='fa fa-trash'></i> 							
                                    <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&del_id=" . $f['id'] . "';}\" >Delete</a> &nbsp;
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
                            <button type="button" class="btn btn-primary" id="saveOrderBtn">Save Order</button>
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
    $("#ckbCheckAll").click(function() {
        $(".checkBoxClass").prop('checked', $(this).prop('checked'));
    });

    $("#ckbCheckAll1").click(function() {
        $(".checkBoxClass1").prop('checked', $(this).prop('checked'));
    });

    $(document).ready(function() {

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

        // Handle the save button click
        $('#saveOrderBtn').click(function() {
            // Get the stored question ID and input value
            var questionId = $('#setOrderModal').data('question-id');
            var order = $('#orderInput').val();

            // AJAX request to update the database
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: "action=setOrderFreeTest&questionId=" + questionId + "&order=" + order,
                success: function(response) {

                    $('#setOrderModal').modal('hide'); // Hide the modal
                    // Refresh the page after a short delay (adjust the delay as needed)
                    setTimeout(function() {
                        location.reload();
                    });
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