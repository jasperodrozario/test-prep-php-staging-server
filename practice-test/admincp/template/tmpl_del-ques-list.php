<div id='response'></div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">

        <!-- <form method="post" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">

            <table class='table table-bordered table-striped'>
                <tr>
                    <td colspan=2>
                        <input type='text' class='form-control' name='search' placeholder='Search By Question Phrase'>
                    </td>
                    <td>
                        <input type='text' class='form-control' name='question_tag' placeholder='Enter Question Tags'>
                    </td>
                    <td>
                        <input type='text' class='form-control' name='search_id' placeholder='Search By Question ID'>
                    </td>
                </tr>

                <tr>
                    <td>
                        <select name='chapter' id='chapter' class='form-control' size=1>
                            <option value=''>Select Chapter</option>
                            <?php
                            foreach ($chapter_list as $c) {
                                echo "<option value='" . $c['chapter'] . "'>" . $c['chapter'] . "</option>";
                            }
                            ?>
                        </select>
                    </td>

                    <td>
                        <select name='topic' id='topic' class='form-control' size=1>
                            <option value=''>Select Topic</option>
                        </select>
                    </td>

                    <td>
                        <select name='cat_id_drop' class='form-control'>
                            <option value=''>Select Category</option>
                            <?php
                            foreach ($categories as $c) {
                                echo "<option value='" . $c['cat_id'] . "'>" . $c['cat_id'] . "</option>";
                            }
                            ?>
                        </select>
                    </td>

                    <td width=20%>
                        <input type='submit' value='Search' name='ok' class='btn btn-success' style='width:100%'>
                    </td>
                </tr>

            </table>
        </form> -->

        <form method="get" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">

            <table class='table table-bordered table-striped'>
                <tr>
                    <td colspan=2>
                        <input type='text' class='form-control' name='search' placeholder='Search By Question Phrase' value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </td>
                    <td>
                        <input type='text' class='form-control' name='question_tag' placeholder='Enter Question Tags' value="<?= htmlspecialchars($_GET['question_tag'] ?? '') ?>">
                    </td>
                    <td>
                        <input type='text' class='form-control' name='search_id' placeholder='Search By Question ID' value="<?= htmlspecialchars($_GET['search_id'] ?? '') ?>">
                    </td>
                </tr>

                <tr>
                    <td>
                        <select name='chapter' id='chapter' class='form-control' size=1>
                            <option value=''>Select Chapter</option>
                            <?php foreach ($chapter_list as $c): ?>
                                <option value='<?= $c['chapter'] ?>' <?= (($_GET['chapter'] ?? '') == $c['chapter']) ? 'selected' : '' ?>>
                                    <?= $c['chapter'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td>
                        <select name='topic' id='topic' class='form-control' size=1>
                            <option value=''>Select Topic</option>
                            <!-- Populate dynamically if needed -->
                        </select>
                    </td>

                    <td>
                        <select name='cat_id_drop' class='form-control'>
                            <option value=''>Select Category</option>
                            <?php foreach ($categories as $c): ?>
                                <option value='<?= $c['cat_id'] ?>' <?= (($_GET['cat_id_drop'] ?? '') == $c['cat_id']) ? 'selected' : '' ?>>
                                    <?= $c['cat_id'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td width=20%>
                        <input type='submit' value='Search' name='ok' class='btn btn-success' style='width:100%'>
                    </td>
                </tr>
            </table>
        </form>


        <form method="post" id="qTypeForm" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">

            <?php
            if ($total > 100) {
                echo "<ul class='pagination'>" . paging($total, 100, "cat_id_drop=" . $category . "&search_id=" . $search_id . "&search=" . $search_phrage . "&question_tag=" . $ques_type . "&chapter=" . $chapter) . "</ul>";
            }
            ?>
            <div style="width: 25%; float: right;">
                <button type="submit" class="btn btn-success" name="deleteMultiQuestions" style='width:100%'>Delete Multiple Questions</button>
            </div>
            <br><br>

            <table class='table table-bordered'>
                <thead style="background-color:#16A085">
                    <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                    <th width=2%>Q.ID</th>
                    <th width=5%>Order</th>
                    <th width=10%>Category</th>
                    <th width=10%>Story ID</th>
                    <th width=5%>Source</th>
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
                                //		                $story="<font color='#1c8348'>Story Id : " .$f['story_id'] ." | ".$title ."</font>";
                                $story = "<a class='story-link' href='" . $configs['vpath'] . $configs['admin_dir'] . "/view-story?story_id=" . $f['story_id'] . "' target='_blank'><font>Story ID: " . $f['story_id'] . " | " . $title . "</font></a>";
                            }

                            echo "
					<tr style='background-color:#CCD2D5'>
						<td><input type='checkbox' name='ids[]' value='" . $f['qid'] . "' class='checkBoxClass'></td>
						<td>" . $f['qid'] . "</td>
						<td>" . (int)$f['order_no'] . "</td>
						<td>" . $f['cat_id'] . "</td>
						<td>" . $story . "</td>
                        <td>" . $f['source'] . "</td>
                        <td>
                            " . $f['chapter'] . "
                        </td>
                        <td>
                            " . $f['topic'] . "
				        </td> 
                        <td>
                            " . $f['question_tag'] . "
				        </td>
                        <td>
                            <i class='fa fa-edit'></i>
    
							<a href='del-ques-list?qid=" . $f['qid'] . "&action=restore'>Restore</a> &nbsp;
							
							<i class='fa fa-trash'></i> 							
							<a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?cat_id=" . $f['cat_id'] . "&del_id=" . $f['qid'] . "&limit=" . $_GET['limit'] . "';}\" >Delete</a>
						</td>
					</tr>";

                            echo "
					<tr style='background:#f5f5f5'>
						<td colspan=10>" . html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question']))) . "</td>
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
                                <td class='text-align:left' colspan=9>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) . "</td>
                            </tr>";
                                }
                            }

                            echo "
                    <tr style='background:#f5f5f5'>
                        <td class='text-align:left' colspan=10><b>Explanation: </b><br><br>" . html_entity_decode(html_entity_decode(html_entity_decode($f['explanation']))) . "</td>
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

        <!-- Chapter Modal -->
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
                        <label for="chapterInput">Enter Question Topic:</label>
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
            echo "<ul class='pagination'>" . paging($total, 100, "cat_id_drop=" . $category . "&search_id=" . $search_id . "&search=" . $search_phrage . "&question_tag=" . $ques_type . "&chapter=" . $chapter) . "</ul>";
        }
        ?>
    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>

<script>
    $("#ckbCheckAll").click(function() {
        $(".checkBoxClass").prop('checked', $(this).prop('checked'));
    });

    $("#ckbCheckAll1").click(function() {
        $(".checkBoxClass1").prop('checked', $(this).prop('checked'));
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
                    chapter: selectedChapter,
                    table: 'question_deleted'
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