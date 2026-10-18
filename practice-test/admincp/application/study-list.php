<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if (!$_GET['page']) {
    $_GET['page'] = 'study-list';
}

$data['btn']="<input type='button' value='Add New Lesson Study' class='btn btn-success' onClick=\"javascript:window.location='study_entryform'\">";

$data['option_no'] = 5;

$filter = array(
    'id' => isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '',
    'title' => isset($_REQUEST['title']) ? trim($_REQUEST['title']) : '',
    'content' => isset($_REQUEST['content']) ? trim($_REQUEST['content']) : '',
    'subject' => isset($_REQUEST['subject']) ? trim($_REQUEST['subject']) : '',
    'chapter' => isset($_REQUEST['chapter']) ? trim($_REQUEST['chapter']) : '',
    'topic' => isset($_REQUEST['topic']) ? trim($_REQUEST['topic']) : '',
    'tag' => isset($_REQUEST['tag']) ? trim($_REQUEST['tag']) : '',
    'type' => isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '',
    'course_id' => isset($_REQUEST['course_id']) ? (int)$_REQUEST['course_id'] : 0,
    'cat_id' => isset($_REQUEST['cat_id']) ? (int)$_REQUEST['cat_id'] : 0,
    'lesson_id' => isset($_REQUEST['lesson_id']) ? (int)$_REQUEST['lesson_id'] : 0,
);
$data['filter'] = $filter;

if (!empty($_POST['updateStudyData'])) {
    $fieldMap = array(
        'study_title' => 'study_title',
        'subject' => 'subject',
        'chapter' => 'chapter',
        'topic' => 'Topic',
        'tag' => 'tag',
        'type' => 'type',
        'content' => 'content',
    );
    $field = isset($_POST['study_field']) ? $_POST['study_field'] : '';
    $ids = (isset($_POST['ids']) && is_array($_POST['ids'])) ? $_POST['ids'] : array();
    if ($field === 'content') {
        $value = isset($_POST['study_value_content']) ? trim($_POST['study_value_content']) : '';
    } elseif ($field === 'type') {
        $value = isset($_POST['study_value_type']) ? trim($_POST['study_value_type']) : '';
    } else {
        $value = isset($_POST['study_value']) ? trim($_POST['study_value']) : '';
    }

    $updated = 0;
    if (!isset($fieldMap[$field])) {
        $data['msg'] = "Choose a field to update.";
    } elseif (!$ids) {
        $data['msg'] = "Select at least one study.";
    } elseif (($field === 'study_title' || $field === 'subject') && $value === '') {
        $data['msg'] = "Title and subject cannot be empty.";
    } elseif ($field === 'type' && $value !== 'study' && $value !== 'skill') {
        $data['msg'] = "Type must be study or skill.";
    } else {
        $column = $fieldMap[$field];
        if (($column === 'chapter' || $column === 'Topic') && $value === '') {
            $value = 'Not set';
        }
        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id > 0) {
                updateTable("study", array($column => $value), array("study_id" => $id));
                $updated++;
            }
        }
        $redirect = array();
        foreach ($filter as $key => $filterValue) {
            if ($filterValue !== '' && $filterValue !== 0) {
                $redirect[$key] = $filterValue;
            }
        }
        header("Location: study-list" . ($redirect ? "?" . http_build_query($redirect) : ""));
        exit;
    }
}

if (!empty($_POST['updateStudyHierarchy'])) {
    $ids = (isset($_POST['ids']) && is_array($_POST['ids'])) ? $_POST['ids'] : array();
    $subject = isset($_POST['hierarchy_subject']) ? trim($_POST['hierarchy_subject']) : '';
    $chapter = isset($_POST['hierarchy_chapter']) ? trim($_POST['hierarchy_chapter']) : '';
    $topic = isset($_POST['hierarchy_topic']) ? trim($_POST['hierarchy_topic']) : '';
    $redirect = array();
    foreach ($filter as $key => $filterValue) {
        if ($filterValue !== '' && $filterValue !== 0) {
            $redirect[$key] = $filterValue;
        }
    }
    $redirectUrl = "study-list" . ($redirect ? "?" . http_build_query($redirect) : "");

    if (!$ids) {
        $data['msg'] = "Select at least one study.";
    } elseif ($subject === '' || $chapter === '' || $topic === '') {
        $data['msg'] = "Choose a subject, chapter, and topic.";
    } else {
        $subjectSql = mysqli_real_escape_string($configs['con'], $subject);
        $subjectCount = getTotalRow("question", array("subject" => $subjectSql));
        $chapterOk = ($chapter === 'Not set');
        if (!$chapterOk) {
            $chapterSql = mysqli_real_escape_string($configs['con'], $chapter);
            $chapterOk = getTotalRow("question", array("subject" => $subjectSql, "chapter" => $chapterSql)) > 0;
        }
        if ($chapter === 'Not set') {
            $topic = 'Not set';
        }
        $topicOk = ($topic === 'Not set');
        if (!$topicOk && $chapterOk && $chapter !== 'Not set') {
            $chapterSql = mysqli_real_escape_string($configs['con'], $chapter);
            $topicSql = mysqli_real_escape_string($configs['con'], $topic);
            $topicOk = getTotalRow("question", array("subject" => $subjectSql, "chapter" => $chapterSql, "topic" => $topicSql)) > 0;
        }

        if (!$subjectCount) {
            $data['msg'] = "Choose a subject from the list.";
        } elseif (!$chapterOk) {
            $data['msg'] = "Choose a chapter listed under this subject, or Not set.";
        } elseif (!$topicOk) {
            $data['msg'] = "Choose a topic listed under this chapter, or Not set.";
        } else {
            foreach ($ids as $id) {
                $id = (int)$id;
                if ($id > 0) {
                    updateTable("study", array(
                        "subject" => $subject,
                        "chapter" => $chapter,
                        "Topic" => $topic,
                    ), array("study_id" => $id));
                }
            }
            header("Location: " . $redirectUrl);
            exit;
        }
    }
}

$studyLike = function ($value) use ($configs) {
    return "'%" . mysqli_real_escape_string($configs['con'], $value) . "%'";
};

$studyDistinct = function ($column, $where) {
    $values = array();
    $rows = getQuery("study", "DISTINCT " . $column, $where, $column);
    if ($rows) {
        while ($row = mysqli_fetch_assoc($rows)) {
            if (isset($row[$column]) && $row[$column] !== '') {
                $values[] = $row[$column];
            }
        }
    }
    return $values;
};

if ($_GET['page']) {

    if ($_GET['del_id']) {
        $r = deleteData("study", array("study_id" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
    }

    $query = array();
    if ($filter['id'] !== '') {
        $ids = array_filter(array_map('intval', array_map('trim', explode(',', $filter['id']))), function ($id) {
            return $id > 0;
        });
        $query['study_id in '] = $ids ? '(' . implode(',', $ids) . ')' : '(0)';
    }
    if ($filter['title'] !== '') {
        $query['study_title like '] = $studyLike($filter['title']);
    }
    if ($filter['content'] !== '') {
        $query['content like '] = $studyLike($filter['content']);
    }
    if ($filter['subject'] !== '') {
        $query['subject'] = mysqli_real_escape_string($configs['con'], $filter['subject']);
    }
    if ($filter['chapter'] !== '') {
        $query['chapter'] = mysqli_real_escape_string($configs['con'], $filter['chapter']);
    }
    if ($filter['topic'] !== '') {
        $query['Topic'] = mysqli_real_escape_string($configs['con'], $filter['topic']);
    }
    if ($filter['tag'] !== '') {
        $query['tag like '] = $studyLike($filter['tag']);
    }
    if ($filter['type'] !== '') {
        $query['type'] = mysqli_real_escape_string($configs['con'], $filter['type']);
    }

    $linkWhere = array();
    if ($filter['course_id']) {
        $linkWhere[] = "course_id=" . $filter['course_id'];
    }
    if ($filter['cat_id']) {
        $linkWhere[] = "cat_id=" . $filter['cat_id'];
    }
    if ($filter['lesson_id']) {
        $linkWhere[] = "lesson_id=" . $filter['lesson_id'];
    }
    if ($linkWhere) {
        $query['study_id in  '] = "(SELECT study_id FROM " . $configs['prev'] . "lesson_study WHERE " . implode(" AND ", $linkWhere) . ")";
    }

    $data['db_list'] = getQuery("study", "*", $query, "study_title");
    $data['total'] = getTotalRow("study", $query);
}

$data['subject_options'] = $studyDistinct("subject", "subject IS NOT NULL AND subject != ''");
$data['question_subjects'] = array();
$questionSubjectRows = getQuery("question", "DISTINCT subject", "subject IS NOT NULL AND subject != ''", "subject");
if ($questionSubjectRows) {
    while ($row = mysqli_fetch_assoc($questionSubjectRows)) {
        if ($row['subject'] !== '') {
            $data['question_subjects'][] = $row['subject'];
        }
    }
}
$data['chapter_options'] = array();
$data['topic_options'] = array();
if ($filter['subject'] !== '') {
    $subjectSql = mysqli_real_escape_string($configs['con'], $filter['subject']);
    $data['chapter_options'] = $studyDistinct("chapter", "subject=\"" . $subjectSql . "\" AND chapter IS NOT NULL AND chapter != ''");
    if ($filter['chapter'] !== '') {
        $chapterSql = mysqli_real_escape_string($configs['con'], $filter['chapter']);
        $data['topic_options'] = $studyDistinct("Topic", "subject=\"" . $subjectSql . "\" AND chapter=\"" . $chapterSql . "\" AND Topic IS NOT NULL AND Topic != ''");
    }
}
$data['type_options'] = $studyDistinct("type", "type IS NOT NULL AND type != ''");

$data['course_options'] = array();
$courseRows = getQuery("course", "course_id, course_name", "", "course_name");
if ($courseRows) {
    while ($row = mysqli_fetch_assoc($courseRows)) {
        $data['course_options'][] = $row;
    }
}

$data['category_options'] = array();
if ($filter['course_id']) {
    $categoryRows = getQuery("category", "cat_id, category_name", array("course_id" => $filter['course_id']), "category_name");
    if ($categoryRows) {
        while ($row = mysqli_fetch_assoc($categoryRows)) {
            $data['category_options'][] = $row;
        }
    }
}

$data['lesson_options'] = array();
if ($filter['cat_id']) {
    $lessonRows = getQuery("lesson", "lesson_id, Lesson_name", array("cat_id" => $filter['cat_id']), "Lesson_name");
    if ($lessonRows) {
        while ($row = mysqli_fetch_assoc($lessonRows)) {
            $data['lesson_options'][] = $row;
        }
    }
}

$filterQuery = array();
foreach ($filter as $key => $value) {
    if ($value !== '' && $value !== 0) {
        $filterQuery[$key] = $value;
    }
}
$data['filter_query'] = http_build_query($filterQuery);

$data['page_title'] = "Lesson Study List";
$data['breadcrumb'] = array("Study List" => "",
    " > Total Studies: " . $data['total'] => "");

echo template($data);
?>
