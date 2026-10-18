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
