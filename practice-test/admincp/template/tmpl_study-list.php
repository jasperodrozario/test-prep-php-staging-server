<div id='response'></div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        
        <form method="get" action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>'>
            <table class='table table-bordered table-top-margin'>
                <tr class="tr-top-padding">
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
        
        <form method="post" id="studyBulkForm" action="study-list">
            <?php foreach ($filter as $filterKey => $filterValue): ?>
                <input type="hidden" name="<?= htmlspecialchars($filterKey, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($filterValue, ENT_QUOTES, 'UTF-8') ?>">
            <?php endforeach; ?>

            <div class="multi-edit-panel">
                <div class="multi-edit-heading">
                    <span>Multi edit</span>
                    <strong id="selectedCount">Selected: 0</strong>
                </div>
                <table class="table table-bordered" style="margin-bottom:0">
                    <tr>
                        <td colspan="2">
                            <input type="text" name="study_value" id="bulkValue" class="form-control" placeholder="New value for the selected studies">
                            <textarea name="study_value_content" id="bulkContent" class="form-control" rows="4" style="display:none" placeholder="New content for the selected studies"></textarea>
                            <select name="study_value_type" id="bulkType" class="form-control" style="display:none">
                                <option value="study">study</option>
                                <option value="skill">skill</option>
                            </select>
                        </td>
                        <td width="25%">
                            <select name="study_field" id="bulkField" class="form-control">
                                <option value="">Select field to edit</option>
                                <option value="study_title">Title</option>
                                <option value="tag">Tag</option>
                                <option value="type">Type</option>
                                <option value="content">Content</option>
                            </select>
                        </td>
                        <td width="25%">
                            <button type="submit" class="btn btn-success" name="updateStudyData" value="1" style="width:100%">Update Selected</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select name="hierarchy_subject" id="bulkHierarchySubject" class="form-control">
                                <option value="">Select Subject</option>
                                <?php foreach ($question_subjects as $subjectName): ?>
                                    <option value="<?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="hierarchy_chapter" id="bulkHierarchyChapter" class="form-control">
                                <option value="">Select Chapter</option>
                            </select>
                        </td>
                        <td>
                            <select name="hierarchy_topic" id="bulkHierarchyTopic" class="form-control">
                                <option value="">Select Topic</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-success" name="updateStudyHierarchy" value="1" style="width:100%">Set Subject, Chapter, and Topic</button>
                        </td>
                    </tr>
                </table>
            </div>

            <table class='table table-bordered study-list-table'>
                <thead style="background-color:#16A085">
                    <tr>
                        <th><input type="checkbox" id="ckbCheckAll"></th>
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
                <?php if ($total): ?>
                    <?php foreach ($db_list as $f): ?>
                        <?php
                        $studyId = (int)$f['study_id'];
                        $contentRaw = html_entity_decode(html_entity_decode((string)$f['content']));
                        $contentText = trim(preg_replace('/\s+/', ' ', strip_tags($contentRaw)));
                        if (strlen($contentText) > 140) {
                            $contentText = substr($contentText, 0, 140) . '...';
                        }
                        ?>
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="<?= $studyId ?>" class="study-check"></td>
                            <td><?= $studyId ?></td>
                            <td class="study-edit-wrap">
                                <div class="study-edit-cell">
                                    <a id="title-link-<?= $studyId ?>" href="view-study?study_id=<?= $studyId ?>"><span id="title-field-<?= $studyId ?>"><?= htmlspecialchars($f['study_title'], ENT_QUOTES, 'UTF-8') ?></span></a>
                                    <button type="button" class="study-field-edit study-text-edit" data-field="study_title" data-label="Title" data-study-id="<?= $studyId ?>" data-value="<?= htmlspecialchars($f['study_title'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-pencil"></i> Edit</button>
                                </div>
                            </td>
                            <td class="study-edit-wrap">
                                <div class="study-edit-cell">
                                    <span id="subject-field-<?= $studyId ?>"><?= htmlspecialchars($f['subject'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <button type="button" class="study-field-edit study-hierarchy-btn" data-level="subject" data-study-id="<?= $studyId ?>" data-subject="<?= htmlspecialchars($f['subject'], ENT_QUOTES, 'UTF-8') ?>" data-chapter="<?= htmlspecialchars($f['chapter'], ENT_QUOTES, 'UTF-8') ?>" data-topic="<?= htmlspecialchars($f['Topic'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-pencil"></i> Edit</button>
                                </div>
                            </td>
                            <td class="study-edit-wrap">
                                <div class="study-edit-cell">
                                    <span id="chapter-field-<?= $studyId ?>"><?= htmlspecialchars($f['chapter'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <button type="button" class="study-field-edit study-hierarchy-btn" data-level="chapter" data-study-id="<?= $studyId ?>" data-subject="<?= htmlspecialchars($f['subject'], ENT_QUOTES, 'UTF-8') ?>" data-chapter="<?= htmlspecialchars($f['chapter'], ENT_QUOTES, 'UTF-8') ?>" data-topic="<?= htmlspecialchars($f['Topic'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-pencil"></i> Edit</button>
                                </div>
                            </td>
                            <td class="study-edit-wrap">
                                <div class="study-edit-cell">
                                    <span id="topic-field-<?= $studyId ?>"><?= htmlspecialchars($f['Topic'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <button type="button" class="study-field-edit study-hierarchy-btn" data-level="topic" data-study-id="<?= $studyId ?>" data-subject="<?= htmlspecialchars($f['subject'], ENT_QUOTES, 'UTF-8') ?>" data-chapter="<?= htmlspecialchars($f['chapter'], ENT_QUOTES, 'UTF-8') ?>" data-topic="<?= htmlspecialchars($f['Topic'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-pencil"></i> Edit</button>
                                </div>
                            </td>
                            <td class="study-edit-wrap">
                                <div class="study-edit-cell">
                                    <span id="tag-field-<?= $studyId ?>"><?= htmlspecialchars($f['tag'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <button type="button" class="study-field-edit study-text-edit" data-field="tag" data-label="Tag" data-study-id="<?= $studyId ?>" data-value="<?= htmlspecialchars($f['tag'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-pencil"></i> Edit</button>
                                </div>
                            </td>
                            <td class="study-edit-wrap">
                                <div class="study-edit-cell">
                                    <span id="type-field-<?= $studyId ?>"><?= htmlspecialchars($f['type'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <button type="button" class="study-field-edit study-text-edit" data-field="type" data-label="Type" data-study-id="<?= $studyId ?>" data-value="<?= htmlspecialchars($f['type'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-pencil"></i> Edit</button>
                                </div>
                            </td>
                            <td class="study-edit-wrap">
                                <div class="study-edit-cell">
                                    <span id="content-field-<?= $studyId ?>"><?= htmlspecialchars($contentText, ENT_QUOTES, 'UTF-8') ?></span>
                                    <button type="button" class="study-field-edit study-content-edit" data-study-id="<?= $studyId ?>" data-value="<?= htmlspecialchars($contentRaw, ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-pencil"></i> Edit</button>
                                </div>
                            </td>
                            <td style="white-space:nowrap">
                                <i class="fa fa-edit"></i>
                                <a href="study_entryform?study_id=<?= $studyId ?>">Edit</a>
                                &nbsp;
                                <i class="fa fa-trash"></i>
                                <a href="#" onclick="if(confirm('Are you sure to delete?')){window.location='?del_id=<?= $studyId ?>&<?= htmlspecialchars($filter_query, ENT_QUOTES, 'UTF-8') ?>';} return false;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </form>

        <div class="modal fade study-center-modal" id="studyHierarchyModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit subject, chapter, and topic</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hierarchyStudyId">
                        <div class="form-group">
                            <label>Subject</label>
                            <select id="hierarchySubject" class="form-control">
                                <option value="">Select Subject</option>
                                <?php foreach ($question_subjects as $subjectName): ?>
                                    <option value="<?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Chapter</label>
                            <select id="hierarchyChapter" class="form-control">
                                <option value="Not set">Not set</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Topic</label>
                            <select id="hierarchyTopic" class="form-control">
                                <option value="Not set">Not set</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="hierarchySave">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade study-center-modal" id="studyFieldModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="studyFieldTitle">Edit field</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="fieldStudyId">
                        <input type="hidden" id="fieldStudyKey">
                        <input type="text" id="fieldEditInput" class="form-control">
                        <select id="fieldEditType" class="form-control" style="display:none">
                            <option value="study">study</option>
                            <option value="skill">skill</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="fieldEditSave">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade study-center-modal" id="studyContentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit content</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="contentStudyId">
                        <textarea id="contentEditInput" class="form-control" rows="8"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="contentEditSave">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .table-top-margin {
                margin-top: 16px;
            }
            .study-center-modal[style*="block"] {
                display: flex !important;
                align-items: center;
                justify-content: center;
                overflow-y: auto;
            }
            .study-center-modal .modal-dialog,
            .study-center-modal.fade .modal-dialog,
            .study-center-modal.in .modal-dialog {
                margin: 16px auto;
                transform: none;
                -webkit-transform: none;
            }
            .multi-edit-panel {
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 16px;
                overflow: hidden;
            }
            .multi-edit-heading {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 14px;
                background: #16A085;
                color: #fff;
                font-weight: 600;
            }
            .multi-edit-heading strong {
                font-weight: 600;
            }
            .multi-edit-panel .table {
                border: 0;
                margin-bottom: 0;
            }
            .study-list-table td.study-edit-wrap {
                position: relative;
                height: auto;
                vertical-align: top;
                padding: 10px 12px 36px !important;
            }
            .study-edit-cell {
                position: static;
                height: auto;
                line-height: 1.45;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
            .study-field-edit {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 26px;
                margin: 0;
                padding: 0;
                border: 0;
                border-radius: 0;
                background: rgba(52, 58, 64, 0.42);
                color: #fff;
                opacity: 0;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.04em;
                line-height: 26px;
                text-transform: uppercase;
                transition: opacity 0.16s ease, background 0.16s ease;
            }
            .study-field-edit .fa {
                margin-right: 4px;
            }
            .study-edit-wrap:hover .study-field-edit {
                opacity: 1;
            }
            .study-field-edit:hover,
            .study-field-edit:focus {
                opacity: 1;
                background: rgba(25, 113, 194, 0.92);
                outline: none;
            }
        </style>

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

function updateSelectedCount() {
    var count = $('.study-check:checked').length;
    $('#selectedCount').text('Selected: ' + count);
}

$('#ckbCheckAll').on('change', function() {
    $('.study-check').prop('checked', this.checked);
    updateSelectedCount();
});

$(document).on('change', '.study-check', updateSelectedCount);

$('#bulkField').on('change', function() {
    var field = $(this).val();
    $('#bulkValue, #bulkContent, #bulkType').hide();
    if (field === 'content') {
        $('#bulkContent').show();
    } else if (field === 'type') {
        $('#bulkType').show();
    } else {
        $('#bulkValue').show();
    }
});

$('#bulkHierarchySubject').on('change', function() {
    var subject = $(this).val() || '';
    $('#bulkHierarchyChapter').html("<option value=''>Select Chapter</option>");
    $('#bulkHierarchyTopic').html("<option value=''>Select Topic</option>");
    if (!subject) {
        return;
    }
    $.ajax({
        type: 'POST',
        url: studyAjaxUrl,
        dataType: 'json',
        data: { action: 'getQuestionChaptersBySubject', subject: subject }
    }).done(function(response) {
        fillHierarchySelect($('#bulkHierarchyChapter'), 'Select Chapter', response.options, '');
    });
});

$('#bulkHierarchyChapter').on('change', function() {
    var subject = $('#bulkHierarchySubject').val() || '';
    var chapter = $(this).val() || '';
    if (!subject || !chapter) {
        $('#bulkHierarchyTopic').html("<option value=''>Select Topic</option>");
        return;
    }
    if (chapter === 'Not set') {
        $('#bulkHierarchyTopic').html("<option value='Not set'>Not set</option>").val('Not set');
        return;
    }
    $.ajax({
        type: 'POST',
        url: studyAjaxUrl,
        dataType: 'json',
        data: { action: 'getQuestionTopicsBySubjectChapter', subject: subject, chapter: chapter }
    }).done(function(response) {
        fillHierarchySelect($('#bulkHierarchyTopic'), 'Select Topic', response.options, '');
    });
});

$('#studyBulkForm').on('submit', function(e) {
    var submitter = e.originalEvent && e.originalEvent.submitter;
    var action = submitter ? submitter.name : '';
    var count = $('.study-check:checked').length;
    if (!count) {
        alert('Select at least one study.');
        return false;
    }
    if (action === 'updateStudyHierarchy') {
        if (!$('#bulkHierarchySubject').val() || !$('#bulkHierarchyChapter').val() || !$('#bulkHierarchyTopic').val()) {
            alert('Choose a subject, chapter, and topic. Use Not set when there is no chapter or topic.');
            return false;
        }
        return confirm('Set this subject, chapter, and topic on ' + count + ' selected ' + (count === 1 ? 'study' : 'studies') + '?');
    }
    if (!$('#bulkField').val()) {
        alert('Choose a field to edit.');
        return false;
    }
    return confirm('Update ' + count + ' selected ' + (count === 1 ? 'study' : 'studies') + '?');
});

function hierarchyHasValue($select, value) {
    var found = false;
    $select.find('option').each(function() {
        if ($(this).val() === value) {
            found = true;
        }
    });
    return found;
}

function resetHierarchyChapter() {
    $('#hierarchyChapter').html("<option value=''>Select Chapter</option>");
}

function resetHierarchyTopic() {
    $('#hierarchyTopic').html("<option value=''>Select Topic</option>");
}

function fillHierarchySelect($select, placeholder, optionsHtml, selectedValue) {
    var $parsed = $('<select>' + (optionsHtml || '') + '</select>');
    $parsed.find('option[value=""]').remove();
    $select.html("<option value=''>" + placeholder + "</option><option value='Not set'>Not set</option>" + $parsed.html());
    $select.val(selectedValue && hierarchyHasValue($select, selectedValue) ? selectedValue : '');
}

function loadHierarchyChapters(subject, selectedChapter) {
    if (!subject) {
        resetHierarchyChapter();
        resetHierarchyTopic();
        return $.Deferred().resolve();
    }
    return $.ajax({
        type: 'POST',
        url: studyAjaxUrl,
        dataType: 'json',
        data: { action: 'getQuestionChaptersBySubject', subject: subject }
    }).done(function(response) {
        fillHierarchySelect($('#hierarchyChapter'), 'Select Chapter', response.options, selectedChapter);
    });
}

function loadHierarchyTopics(subject, chapter, selectedTopic) {
    if (!subject || !chapter) {
        resetHierarchyTopic();
        return $.Deferred().resolve();
    }
    if (chapter === 'Not set') {
        $('#hierarchyTopic').html("<option value='Not set'>Not set</option>").val('Not set');
        return $.Deferred().resolve();
    }
    return $.ajax({
        type: 'POST',
        url: studyAjaxUrl,
        dataType: 'json',
        data: { action: 'getQuestionTopicsBySubjectChapter', subject: subject, chapter: chapter }
    }).done(function(response) {
        fillHierarchySelect($('#hierarchyTopic'), 'Select Topic', response.options, selectedTopic);
    });
}

$('#hierarchySubject').on('change', function() {
    loadHierarchyChapters($(this).val(), '').done(function() {
        resetHierarchyTopic();
    });
});

$('#hierarchyChapter').on('change', function() {
    loadHierarchyTopics($('#hierarchySubject').val(), $(this).val(), '');
});

$(document).on('click', '.study-hierarchy-btn', function(e) {
    e.preventDefault();
    var level = $(this).data('level') || 'topic';
    var subject = $(this).attr('data-subject') || '';
    var chapter = $(this).attr('data-chapter') || '';
    $('#hierarchyStudyId').val($(this).data('study-id'));

    if (level === 'subject' || !hierarchyHasValue($('#hierarchySubject'), subject)) {
        $('#hierarchySubject').val('');
        subject = '';
        chapter = '';
    } else {
        $('#hierarchySubject').val(subject);
        if (level === 'chapter') {
            chapter = '';
        }
    }

    loadHierarchyChapters(subject, chapter).done(function() {
        if (level === 'topic' && chapter) {
            loadHierarchyTopics($('#hierarchySubject').val(), $('#hierarchyChapter').val(), '');
        } else {
            resetHierarchyTopic();
        }
    });
    $('#studyHierarchyModal').modal('show');
});

$('#hierarchySave').on('click', function() {
    var studyId = $('#hierarchyStudyId').val();
    var subject = $('#hierarchySubject').val();
    var chapter = $('#hierarchyChapter').val();
    var topic = $('#hierarchyTopic').val();
    if (!subject) {
        alert('Choose a subject.');
        return;
    }
    if (!chapter) {
        alert('Choose a chapter, or Not set.');
        return;
    }
    if (!topic) {
        alert('Choose a topic, or Not set.');
        return;
    }
    $.ajax({
        type: 'POST',
        url: studyAjaxUrl,
        dataType: 'json',
        data: {
            action: 'setStudyHierarchy',
            study_id: studyId,
            subject: subject,
            chapter: chapter,
            topic: topic
        }
    }).done(function(response) {
        if (!response || !response.ok) {
            alert((response && response.msg) ? response.msg : 'Could not update this study.');
            return;
        }
        $('#subject-field-' + studyId).text(response.subject);
        $('#chapter-field-' + studyId).text(response.chapter);
        $('#topic-field-' + studyId).text(response.topic);
        $('.study-hierarchy-btn[data-study-id="' + studyId + '"]')
            .attr('data-subject', response.subject)
            .attr('data-chapter', response.chapter)
            .attr('data-topic', response.topic);
        $('#studyHierarchyModal').modal('hide');
    }).fail(function() {
        alert('Could not update this study.');
    });
});

$(document).on('click', '.study-text-edit', function(e) {
    e.preventDefault();
    var field = $(this).data('field');
    $('#fieldStudyId').val($(this).data('study-id'));
    $('#fieldStudyKey').val(field);
    $('#studyFieldTitle').text('Edit ' + ($(this).data('label') || 'field'));
    if (field === 'type') {
        $('#fieldEditInput').hide();
        $('#fieldEditType').val($(this).attr('data-value') === 'skill' ? 'skill' : 'study').show();
    } else {
        $('#fieldEditType').hide();
        $('#fieldEditInput').val($(this).attr('data-value') || '').show();
    }
    $('#studyFieldModal').modal('show');
});

$('#fieldEditSave').on('click', function() {
    var studyId = $('#fieldStudyId').val();
    var field = $('#fieldStudyKey').val();
    var value = field === 'type' ? $('#fieldEditType').val() : $('#fieldEditInput').val();
    $.ajax({
        type: 'POST',
        url: studyAjaxUrl,
        dataType: 'json',
        data: { action: 'setStudyField', study_id: studyId, field: field, value: value }
    }).done(function(response) {
        if (!response || !response.ok) {
            alert((response && response.msg) ? response.msg : 'Could not update this study.');
            return;
        }
        var spanId = field === 'study_title' ? 'title-field-' + studyId : field + '-field-' + studyId;
        $('#' + spanId).text(response.display);
        $('.study-text-edit[data-study-id="' + studyId + '"][data-field="' + field + '"]').attr('data-value', response.value);
        $('#studyFieldModal').modal('hide');
    }).fail(function() {
        alert('Could not update this study.');
    });
});

$(document).on('click', '.study-content-edit', function(e) {
    e.preventDefault();
    $('#contentStudyId').val($(this).data('study-id'));
    $('#contentEditInput').val($(this).attr('data-value') || '');
    $('#studyContentModal').modal('show');
});

$('#contentEditSave').on('click', function() {
    var studyId = $('#contentStudyId').val();
    var value = $('#contentEditInput').val();
    $.ajax({
        type: 'POST',
        url: studyAjaxUrl,
        dataType: 'json',
        data: { action: 'setStudyField', study_id: studyId, field: 'content', value: value }
    }).done(function(response) {
        if (!response || !response.ok) {
            alert((response && response.msg) ? response.msg : 'Could not update this study.');
            return;
        }
        $('#content-field-' + studyId).text(response.display);
        $('.study-content-edit[data-study-id="' + studyId + '"]').attr('data-value', response.value);
        $('#studyContentModal').modal('hide');
    }).fail(function() {
        alert('Could not update this study.');
    });
});
</script>
