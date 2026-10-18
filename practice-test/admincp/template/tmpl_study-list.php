<div id='response'></div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        
        <form method="get" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>'>
            <table class='table table-bordered'>
                <tr>
                    <td width="25%">
                        <input type="text" name="id" class="form-control" placeholder="Study ID" value="<?= htmlspecialchars($filter['id'], ENT_QUOTES, 'UTF-8') ?>">
                    </td>
                    <td width="25%">
                        <input type="text" name="title" class="form-control" placeholder="Study Title" value="<?= htmlspecialchars($filter['title'], ENT_QUOTES, 'UTF-8') ?>">
                    </td>
                    <td width="25%">
                        <input type="text" name="content" class="form-control" placeholder="Content contains" value="<?= htmlspecialchars($filter['content'], ENT_QUOTES, 'UTF-8') ?>">
                    </td>
                    <td width="25%">
                        <input type="text" name="tag" class="form-control" placeholder="Tag contains" value="<?= htmlspecialchars($filter['tag'], ENT_QUOTES, 'UTF-8') ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="subject" id="subject" class="form-control">
                            <option value="">Select Subject</option>
                            <?php foreach ($subject_options as $subjectName): ?>
                                <option value="<?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?>" <?= ($filter['subject'] === $subjectName) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="chapter" id="chapter" class="form-control">
                            <option value="">Select Chapter</option>
                            <?php foreach ($chapter_options as $chapterName): ?>
                                <option value="<?= htmlspecialchars($chapterName, ENT_QUOTES, 'UTF-8') ?>" <?= ($filter['chapter'] === $chapterName) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($chapterName, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="topic" id="topic" class="form-control">
                            <option value="">Select Topic</option>
                            <?php foreach ($topic_options as $topicName): ?>
                                <option value="<?= htmlspecialchars($topicName, ENT_QUOTES, 'UTF-8') ?>" <?= ($filter['topic'] === $topicName) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($topicName, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="type" class="form-control">
                            <option value="">Select Type</option>
                            <?php foreach ($type_options as $typeName): ?>
                                <option value="<?= htmlspecialchars($typeName, ENT_QUOTES, 'UTF-8') ?>" <?= ($filter['type'] === $typeName) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($typeName, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="course_id" id="course_id" class="form-control">
                            <option value="">Select Course</option>
                            <?php foreach ($course_options as $courseOption): ?>
                                <option value="<?= (int)$courseOption['course_id'] ?>" <?= ($filter['course_id'] == $courseOption['course_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($courseOption['course_name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="cat_id" id="cat_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($category_options as $categoryOption): ?>
                                <option value="<?= (int)$categoryOption['cat_id'] ?>" <?= ($filter['cat_id'] == $categoryOption['cat_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($categoryOption['category_name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="lesson_id" id="lesson_id" class="form-control">
                            <option value="">Select Lesson</option>
                            <?php foreach ($lesson_options as $lessonOption): ?>
                                <option value="<?= (int)$lessonOption['lesson_id'] ?>" <?= ($filter['lesson_id'] == $lessonOption['lesson_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lessonOption['Lesson_name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" value="Search" class="btn btn-success">
                        <a href="study-list" class="btn btn-default">Clear Filter</a>
                    </td>
                </tr>
            </table>
        </form>
        
        <div class='clearfix'><br></div>

        <table class='table table-bordered'>
			<thead style="background-color:#16A085">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Chapter</th>
                    <th>Topic</th>
                    <th>Tag</th>
                    <th>Type</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
            </thead>
            
            <tbody>
            <?
            if($total){
                foreach($db_list as $f){
                    $contentText = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode(html_entity_decode((string)$f['content'])))));
                    if (strlen($contentText) > 140) {
                        $contentText = substr($contentText, 0, 140) . '...';
                    }
                   echo"
					<tr>
						<td>" . (int)$f['study_id'] ."</td>
                        <td><a href='view-study?study_id=" . (int)$f['study_id'] . "'>" . htmlspecialchars($f['study_title'], ENT_QUOTES, 'UTF-8') . "</a></td>
                        <td>" . htmlspecialchars($f['subject'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($f['chapter'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($f['Topic'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($f['tag'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($f['type'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($contentText, ENT_QUOTES, 'UTF-8') . "</td>
                        <td style='white-space:nowrap'>
                            <i class='fa fa-edit'></i> 
							<a href='study_entryform?study_id=" . (int)$f['study_id'] . "&limit=" . $_GET['limit'] ."'>Edit</a> &nbsp;
							
							<i class='fa fa-trash'></i> 							
							<a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?del_id=" . (int)$f['study_id'] . "&" . htmlspecialchars($filter_query, ENT_QUOTES, 'UTF-8') . "';}\" >Delete</a>
						</td>
					</tr>";
                    
                }
            }
            ?>
            </tbody>

        </table>

    </div>
</div>

<script>
var studyAjaxUrl = "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax";

function resetStudyTopics() {
    $('#topic').html("<option value=''>Select Topic</option>");
}

$('#subject').on('change', function() {
    $('#chapter').html("<option value=''>Select Chapter</option>");
    resetStudyTopics();
    var subject = $(this).val() || '';
    if (!subject) {
        return;
    }
    $.ajax({
        type: "POST",
        url: studyAjaxUrl,
        dataType: "json",
        data: { action: "getStudyChapters", category: subject }
    }).done(function(response) {
        $('#chapter').html(response.options || "<option value=''>Select Chapter</option>");
    });
});

$('#chapter').on('change', function() {
    resetStudyTopics();
    var subject = $('#subject').val() || '';
    var chapter = $(this).val() || '';
    if (!subject || !chapter) {
        return;
    }
    $.ajax({
        type: "POST",
        url: studyAjaxUrl,
        dataType: "json",
        data: { action: "getStudyTopics", category: subject, chapter: chapter }
    }).done(function(response) {
        $('#topic').html(response.options || "<option value=''>Select Topic</option>");
    });
});

$('#course_id').on('change', function() {
    $('#lesson_id').html("<option value=''>Select Lesson</option>");
});
</script>
