<!--<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">-->
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="study_id" value="<?php echo $_GET['study_id']?>">
    
    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
        <tr>
            <td align="left" width=20%>Study Title</td>
            <td>
                <input type="text" class='form-control' name='study_title'   value="<?php echo $db_value['study_title']?>"  required>
            </td>
        </tr>

        <tr>
            <td align="left" width=20%>Subject</td>
            <td>
                <?php $currentSubject = isset($db_value['subject']) ? $db_value['subject'] : ''; ?>
                <select name="subject" id="subject" class="form-control" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subject_list as $subjectName): ?>
                        <option value="<?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?>" <?= ($currentSubject === $subjectName) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if ($currentSubject !== '' && !in_array($currentSubject, $subject_list, true)): ?>
                        <option value="<?= htmlspecialchars($currentSubject, ENT_QUOTES, 'UTF-8') ?>" selected>
                            <?= htmlspecialchars($currentSubject, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Chapter</td>
            <td>
                <?php $currentChapter = isset($db_value['chapter']) ? $db_value['chapter'] : ''; ?>
                <select name="chapter" id="chapter" class="form-control">
                    <option value="">Select Chapter</option>
                    <?php foreach ($chapter_list as $chapterName): ?>
                        <option value="<?= htmlspecialchars($chapterName, ENT_QUOTES, 'UTF-8') ?>" <?= ($currentChapter === $chapterName) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($chapterName, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if ($currentChapter !== '' && !in_array($currentChapter, $chapter_list, true)): ?>
                        <option value="<?= htmlspecialchars($currentChapter, ENT_QUOTES, 'UTF-8') ?>" selected>
                            <?= htmlspecialchars($currentChapter, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Topic</td>
            <td>
                <?php $currentTopic = isset($db_value['Topic']) ? $db_value['Topic'] : ''; ?>
                <select name="Topic" id="Topic" class="form-control">
                    <option value="">Select Topic</option>
                    <?php foreach ($topic_list as $topicName): ?>
                        <option value="<?= htmlspecialchars($topicName, ENT_QUOTES, 'UTF-8') ?>" <?= ($currentTopic === $topicName) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($topicName, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if ($currentTopic !== '' && !in_array($currentTopic, $topic_list, true)): ?>
                        <option value="<?= htmlspecialchars($currentTopic, ENT_QUOTES, 'UTF-8') ?>" selected>
                            <?= htmlspecialchars($currentTopic, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Tag</td>
            <td>
                <input type="text" class='form-control' name='tag'   value="<?php echo $db_value['tag']?>"  required>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Type</td>
            <td>
                <select name='type' id='type' class='form-control' size=1>
                    <option value='study' <?php echo ($db_value['type'] == 'study') ? 'selected' : ''; ?>>Study</option>
                    <option value='skill' <?php echo ($db_value['type'] == 'skill') ? 'selected' : ''; ?>>Skill</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td valign="top" width="20%">Content</td>
            <td align="left" valign="top"> 
                <textarea  name='content' id="content" class='form-control' style="width:100%;height:200px"><?=html_entity_decode(html_entity_decode($db_value["content"]));?></textarea> 
            </td>
        </tr>

        <tr>
            <td></td>
            <td >
                <input type=submit  name='SBMT_REG' value='Save' class='btn btn-success'>
            </td>
        </tr>
    </table> 
</form>

<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>
<script>
var editor;
KindEditor.ready(function(K) 
{
	editor = K.create('#content', {
		allowFileManager : true
	});
});

var studyAjaxUrl = "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax";

function resetStudyTopic() {
	$('#Topic').html("<option value=''>Select Topic</option>");
}

$('#subject').on('change', function() {
	$('#chapter').html("<option value=''>Select Chapter</option>");
	resetStudyTopic();
	var subject = $(this).val() || '';
	if (!subject) {
		return;
	}
	$.ajax({
		type: "POST",
		url: studyAjaxUrl,
		dataType: "json",
		data: { action: "getQuestionChaptersBySubject", subject: subject }
	}).done(function(response) {
		$('#chapter').html(response.options || "<option value=''>Select Chapter</option>");
	});
});

$('#chapter').on('change', function() {
	resetStudyTopic();
	var subject = $('#subject').val() || '';
	var chapter = $(this).val() || '';
	if (!subject || !chapter) {
		return;
	}
	$.ajax({
		type: "POST",
		url: studyAjaxUrl,
		dataType: "json",
		data: { action: "getQuestionTopicsBySubjectChapter", subject: subject, chapter: chapter }
	}).done(function(response) {
		$('#Topic').html(response.options || "<option value=''>Select Topic</option>");
	});
});
</script>