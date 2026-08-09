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

            <!-- <form method="post" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">

                <input type='hidden' name="lesson_id" value="<?= $_GET['lesson_id']; ?>">
                <input type='hidden' name="cat_id" value="<?= $_GET['cat_id']; ?>">
                <input type='hidden' name="course_id" value="<?= $_GET['course_id']; ?>">

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

                </table>
            </form> -->

            <form method="get" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">

                <input type='hidden' name="lesson_id" value="<?= htmlspecialchars($_GET['lesson_id'] ?? '') ?>">
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


            <?
            if ($cat_name == "Flashcards") {
            ?>
                <form method="post" id="hw_form" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
                    <input type='hidden' name="cat_id" value="<?= $_GET['cat_id']; ?>">
                    <input type='hidden' name="course_id" value="<?= $_GET['course_id']; ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <h3>Flashcards Questions</h3>
                        </div>
                        <div class="col-md-3 text-right">
                            <input type='submit' value='Remove Multiple Questions' name='remove_cat' class='btn btn-primary'>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" onclick="window.location.href='add_lesson_questions?cat_id=<?= $_GET['cat_id']; ?>&course_id=<?= $_GET['course_id']; ?>&add_to=flashcards'">Add Questions In Flashcards</button>
                        </div>
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
                                        <td class='text-align:left' colspan=7>" . html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind]))) . "</td>
                                    </tr>";
                                        }
                                    }

                                    echo "
                            <tr style='background:#f5f5f5'>
                                <td class='text-align:left' colspan=10><b>Explanation: </b><br><br>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['explanation'])))) . "</td>
                            </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center;'>No Questions to View.</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </form>
            <?
            } else {
            ?>

                <form method="post" id="hw_form" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
                    <input type='hidden' name="lesson_id" value="<?= $_GET['lesson_id']; ?>">
                    <input type='hidden' name="cat_id" value="<?= $_GET['cat_id']; ?>">
                    <input type='hidden' name="course_id" value="<?= $_GET['course_id']; ?>">


                    <table class='table table-bordered'>
                        <tr>
                            <td>
                                <select name='move_lesson' id='move_lesson' class='form-control' size=1>
                                    <option value=''>Select Lesson</option>
                                    <?php
                                    foreach ($lesson_list as $l) {
                                        echo "<option value='" . $l['lesson_id'] . "'>" . $l['Lesson_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name='move_option' id='move_option' class='form-control' size=1>
                                    <option value=''>Select Option</option>
                                    <option value='HW'>HW</option>
                                    <option value='pt'>PT</option>
                                </select>
                            </td>
                            <td width=20%>
                                <input type='submit' value='Move to' name='move_lesson_btn' class='btn btn-primary' style='width:100%'>
                            </td>
                        </tr>
                    </table>

                    <div class="row align-items-center">
                        <!-- Homework title -->
                        <div class="col-md-3 d-flex align-items-center">
                            <h5 class="mb-0">Homework Questions (<?= $total_hw ?>)</h5>
                        </div>

                        <!-- Remove Questions -->
                        <div class="col-md-2">
                            <input type="submit" value="Remove" name="remove_hw" class="btn btn-primary w-100">
                        </div>

                        <!-- Add Questions Link -->
                        <div class="col-md-2">
                            <a class="btn btn-primary w-100" href="add_lesson_questions?lesson_id=<?= $_GET['lesson_id']; ?>&cat_id=<?= $_GET['cat_id']; ?>&course_id=<?= $_GET['course_id']; ?>&add_to=HW">
                                Add
                            </a>
                        </div>

                        <!-- Download HW Questions with embedded checkbox -->
                        <div class="col-md-3">
                            <button type="submit" name="download_hw_ques" class="btn btn-primary w-100 d-flex align-items-center justify-content-between" style="height: 34px;">
                                <span>Download </span>
                                <label class="mb-0 d-flex align-items-center" style="gap: 5px;">
                                    <input type="checkbox" name="with_id_hw" value="1" style="margin: 0;">
                                    <span style="color: white;">With ID</span>
                                    <input type="checkbox" name="with_img_hw" value="1" style="margin: 0;">
                                    <span style="color: white;">Image</span>
                                </label>
                            </button>
                        </div>

                        <!-- Move to PT -->
                        <div class="col-md-2">
                            <input type="submit" value="Move to PT" name="move_to_pt" class="btn btn-primary w-100">
                        </div>
                    </div>

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
                            if ($total_hw) {
                                foreach ($db_list as $f) {
                                    if ($f['HW']) {
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
                                    <a href='add_questions?cat_id=" . $f['cat_id'] . "&qid=" . $f['qid'] . "&limit=" . $_GET['limit'] . "'>Edit</a>
<br>
                                    <i class='fa fa-trash'></i> 							
                                    <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&del_id=" . $f['id'] . "';}\" >Remove from HW</a>
<br>
                                    <i class='fa fa-edit'></i> 
                                    <a href='questions_list?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&id=" . $f['id'] . "&function=move_pt&limit=" . $_GET['limit'] . "'>Move to PT</a>
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
                                echo "<tr><td colspan='5' style='text-align: center;'>No Questions to View.</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </form>
                <br>

                <form method="post" id="pt_form" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
                    <input type='hidden' name="lesson_id" value="<?= $_GET['lesson_id']; ?>">
                    <input type='hidden' name="cat_id" value="<?= $_GET['cat_id']; ?>">
                    <input type='hidden' name="course_id" value="<?= $_GET['course_id']; ?>">

                    <table class='table table-bordered'>
                        <tr>
                            <td>
                                <select name='move_lesson' id='move_lesson' class='form-control' size=1>
                                    <option value=''>Select Lesson</option>
                                    <?php
                                    foreach ($lesson_list as $l) {
                                        echo "<option value='" . $l['lesson_id'] . "'>" . $l['Lesson_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name='move_option' id='move_option' class='form-control' size=1>
                                    <option value=''>Select Option</option>
                                    <option value='HW'>HW</option>
                                    <option value='pt'>PT</option>
                                </select>
                            </td>
                            <td width=20%>
                                <input type='submit' value='Move to' name='move_lesson_btn' class='btn btn-primary' style='width:100%'>
                            </td>
                        </tr>
                    </table>

                    <div class="row align-items-center">
                        <!-- Practice Test title -->
                        <div class="col-md-3 d-flex align-items-center">
                            <h5 class="mb-0">Practice Test Questions (<?= $total_pt ?>)</h5>
                        </div>

                        <!-- Remove Questions -->
                        <div class="col-md-2 d-flex">
                            <input type="submit" value="Remove" name="remove_pt" class="btn btn-primary w-100" style="height: 34px;">
                        </div>

                        <!-- Add Questions Link -->
                        <div class="col-md-2 d-flex">
                            <a class="btn btn-primary w-100 d-flex justify-content-center align-items-center text-center"
                                href="add_lesson_questions?lesson_id=<?= $_GET['lesson_id']; ?>&cat_id=<?= $_GET['cat_id']; ?>&course_id=<?= $_GET['course_id']; ?>&add_to=pt"
                                style="height: 34px;">
                                Add 
                            </a>
                        </div>

                        <!-- Download PT Questions with embedded checkbox -->
                        <div class="col-md-3 d-flex">
                            <button type="submit" name="download_pt_ques"
                                class="btn btn-primary w-100 d-flex align-items-center justify-content-between"
                                style="height: 34px;">
                                <span>Download </span>
                                <label class="d-flex align-items-center" style="gap: 5px; margin: 0;">
                                    <input type="checkbox" name="with_id_pt" value="1" style="margin: 0;">
                                    <span style="color: white;"> With ID</span>
                                    <input type="checkbox" name="with_img_pt" value="1" style="margin: 0;">
                                    <span style="color: white;"> Image</span>
                                </label>
                            </button>
                        </div>

                        <!-- Move to HW -->
                        <div class="col-md-1 d-flex">
                            <input type="submit" value="Move to HW" name="move_to_hw" class="btn btn-primary w-100" style="height: 34px;">
                        </div>
                    </div>

                    <table class='table table-bordered' width=100%>
                        <thead style="background-color:#16A085">
                            <th width=1%><input type='checkbox' id='ckbCheckAll1'></input></th>
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
                            if ($total_pt) {
                                foreach ($db_list as $f) {
                                    if ($f['pt']) {
                                        $story = "";
                                        if ($f['story_id']) {
                                            $title = getFldValueById("story", "story_title", array("story_id" => $f['story_id']));
                                            $story = "<a class='story-link' href='" . $configs['vpath'] . $configs['admin_dir'] . "/view-story?story_id=" . $f['story_id'] . "' target='_blank'><font>Story ID: " . $f['story_id'] . " | " . $title . "</font></a>";
                                        }

                                        // <i class='fa fa-edit'></i>
                                        // <a href='#' class='set-order-btn' data-question-id=" .$f['id'] . " data-toggle='modal' data-target='#setOrderModal'>Set Order</a>

                                        echo "
                            <tr style='background-color:#CCD2D5'>
                                <td><input type='checkbox' name='ids[]' value='" . $f['id'] . "' class='checkBoxClass1'></td>
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
                                    <a href='add_questions?cat_id=" . $f['cat_id'] . "&qid=" . $f['qid'] . "&limit=" . $_GET['limit'] . "'>Edit</a>
<br>
                                    <i class='fa fa-trash'></i> 							
                                    <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&del_id=" . $f['id'] . "';}\" >Remove from PT</a>
<br>
                                    <i class='fa fa-edit'></i> 
                                    <a href='questions_list?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&id=" . $f['id'] . "&function=move_hw&limit=" . $_GET['limit'] . "'>Move to HW</a>

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
                                echo "<tr><td colspan='5' style='text-align: center;'>No Questions to View.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            <?
            }
            ?>

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

    $(document).on('click', '.set-order-btn', function (e) {
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
        $(function () {
            function saveOrder() {
                var questionId = $('#setOrderModal').data('question-id');
                var order = $('#orderInput').val();
                //var order = (orderVal.trim() === '') ? null : orderVal;

                $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: { action: 'setOrder', questionId: questionId, order: order },
                success: function (res) {
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
                error: function (xhr, status, error) {
                    alert("An error occurred: " + error);
                }
                });
            }

            // Click
            $(document).on('click', '#saveOrderBtn', function (e) {
                e.preventDefault();
                saveOrder();
            });

            // Enter on the input
            $(document).on('keydown', '#orderInput', function (e) {
                if (e.key === 'Enter' || e.which === 13) {
                e.preventDefault();
                saveOrder();
                }
            });
              $('#setOrderModal').on('shown.bs.modal', function () {
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