<?php

/**
 * Pull migrated officers exam history over HTTP and reshape it into the
 * current progress-report / detail payload. The copied officers PHP in this
 * workspace is not executed; it points at this site's database.
 */

define('LEGACY_OFFICERS_API_URL', 'https://officers.asvab-tutoring.com/app-api-final.php');

function legacyOfficersRequest($fields)
{
    if (!function_exists('curl_init')) {
        return null;
    }

    $ch = curl_init(LEGACY_OFFICERS_API_URL);
    curl_setopt_array($ch, array(
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $fields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 6,
        CURLOPT_CONNECTTIMEOUT => 3,
    ));
    $body = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false || $code >= 400) {
        return null;
    }

    $data = json_decode($body, true);
    if (!is_array($data) || isset($data['error'])) {
        return null;
    }

    return $data;
}

function legacyOfficersResults($oldUserId)
{
    static $cache = array();
    $oldUserId = (int)$oldUserId;
    if ($oldUserId < 1) {
        return null;
    }
    if (!array_key_exists($oldUserId, $cache)) {
        $cache[$oldUserId] = legacyOfficersRequest(array(
            'action' => 'results-using-user-id',
            'user_id' => $oldUserId,
        ));
    }
    return $cache[$oldUserId];
}

function legacyFetchRows($table, $fields, $cond)
{
    $result = getQuery($table, $fields, $cond);
    $rows = array();
    if ($result instanceof mysqli_result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function legacyNormName($name)
{
    $name = strtolower(trim((string)$name));
    $name = preg_replace('/\s+/', ' ', $name);
    return $name;
}

function legacyOfficersCatalog($courseId)
{
    static $cache = array();
    $courseId = (int)$courseId;
    if (isset($cache[$courseId])) {
        return $cache[$courseId];
    }

    $cats = array();
    $lessons = array();
    foreach (legacyFetchRows("category", "cat_id, category_name, shortcode", array("course_id" => $courseId)) as $cat) {
        $cats[legacyNormName($cat['category_name'])] = $cat;
        $lessonMap = array();
        foreach (legacyFetchRows("lesson", "lesson_id, Lesson_name", array("cat_id" => $cat['cat_id'])) as $lesson) {
            $lessonMap[legacyNormName($lesson['Lesson_name'])] = $lesson;
        }
        $lessons[(int)$cat['cat_id']] = $lessonMap;
    }

    $preds = array();
    foreach (legacyFetchRows("predic_test", "Predic_test_id, test_name, shortcode, course_id, test_order, online, free", array("course_id" => $courseId)) as $pred) {
        $preds[legacyNormName($pred['test_name'])] = $pred;
    }

    $cache[$courseId] = array(
        'cats' => $cats,
        'lessons' => $lessons,
        'preds' => $preds,
    );
    return $cache[$courseId];
}

function legacyResolveCategory($catalog, $name)
{
    $key = legacyNormName($name);
    if ($key !== '' && isset($catalog['cats'][$key])) {
        return $catalog['cats'][$key];
    }
    return null;
}

function legacyLessonCandidates($lessonName)
{
    $name = trim((string)$lessonName);
    $candidates = array();
    if ($name !== '') {
        $candidates[] = $name;
    }
    if (preg_match('/^(WK|AR|MK|RC|VA|BC|TR|IC|MC|ANI|SD|AAI|SA)\s+(.+)$/i', $name, $match)) {
        $candidates[] = trim($match[2]);
    }
    if (preg_match('/^Final Practice Test\s+(\d+)$/i', $name, $match)) {
        $candidates[] = 'AR Final Practice Test ' . $match[1];
        $candidates[] = 'MK Final Practice Test ' . $match[1];
    }
    return $candidates;
}

function legacyResolveLesson($catalog, $category, $lessonName)
{
    if (empty($category['cat_id'])) {
        return null;
    }
    $map = isset($catalog['lessons'][(int)$category['cat_id']]) ? $catalog['lessons'][(int)$category['cat_id']] : array();
    foreach (legacyLessonCandidates($lessonName) as $candidate) {
        $key = legacyNormName($candidate);
        if ($key !== '' && isset($map[$key])) {
            return $map[$key];
        }
    }
    return null;
}

function legacyPredNameByOldCat($oldCatId)
{
    $map = array(
        75 => 'OAR Prediction Test',
        76 => 'ASTB Prediction Test',
        86 => 'AFOQT Prediction test',
        92 => 'SIFT Prediction Test',
        101 => 'Pilot',
        102 => 'CSO',
        103 => 'ABM',
        104 => 'Academic Aptitude',
        105 => 'Verbal',
        106 => 'Quantitative',
    );
    $oldCatId = (int)$oldCatId;
    return isset($map[$oldCatId]) ? $map[$oldCatId] : '';
}

function legacyResolvePrediction($catalog, $row)
{
    $names = array();
    if (!empty($row['category'])) {
        $names[] = $row['category'];
    }
    $alias = legacyPredNameByOldCat(isset($row['cat_id']) ? $row['cat_id'] : 0);
    if ($alias !== '') {
        $names[] = $alias;
    }
    foreach ($names as $name) {
        $key = legacyNormName($name);
        if ($key !== '' && isset($catalog['preds'][$key])) {
            return $catalog['preds'][$key];
        }
    }
    return null;
}

function legacyEncodeResultId($type, $term, $oldCatId, $oldLessonId)
{
    // Widths are term 3 digits, category 3 digits, lesson 4 digits.
    // A 5-digit term slot overflows once cat_id >= 100 (AFOQT cats 101-106).
    $value = ((int)$type * 10000000000)
        + ((int)$term * 10000000)
        + ((int)$oldCatId * 10000)
        + (int)$oldLessonId;
    return -$value;
}

function legacyMapLessonRow($row, $catalog)
{
    $legacyCatName = trim((string)(isset($row['category']) ? $row['category'] : (isset($row['cat_name']) ? $row['cat_name'] : '')));
    $legacyLessonName = trim((string)(isset($row['lesson_name']) ? $row['lesson_name'] : ''));
    $category = legacyResolveCategory($catalog, $legacyCatName);
    $catId = 0;
    $lessonId = 0;
    $catName = $legacyCatName;
    $lessonName = $legacyLessonName;

    if ($category) {
        $catId = (int)$category['cat_id'];
        $catName = $category['category_name'];
        $lesson = legacyResolveLesson($catalog, $category, $legacyLessonName);
        if ($lesson) {
            $lessonId = (int)$lesson['lesson_id'];
            $lessonName = $lesson['Lesson_name'];
        }
    }

    $total = isset($row['total_questions']) ? (int)$row['total_questions'] : 0;
    $correct = isset($row['correct']) ? (int)$row['correct'] : (isset($row['correct_answers']) ? (int)$row['correct_answers'] : 0);
    $wrong = isset($row['wrong']) ? (int)$row['wrong'] : ($total - $correct);

    return array(
        "result_id" => legacyEncodeResultId(1, isset($row['term']) ? $row['term'] : 0, isset($row['cat_id']) ? $row['cat_id'] : 0, isset($row['lesson_id']) ? $row['lesson_id'] : 0),
        "cat_id" => $catId,
        "cat_name" => $catName,
        "lesson_id" => $lessonId,
        "lesson_name" => $lessonName,
        "total_questions" => $total,
        "correct_answers" => $correct,
        "wrong_answer" => $wrong,
        "total_answered" => $total,
        "result_date" => isset($row['date']) ? $row['date'] : '',
        "score" => round(isset($row['score']) ? (float)$row['score'] : 0),
    );
}

function legacyMapPredictionListItem($row, $catalog, $courseId, $userName)
{
    $prediction = legacyResolvePrediction($catalog, $row);
    $predId = $prediction ? (int)$prediction['Predic_test_id'] : 0;
    $predName = $prediction ? $prediction['test_name'] : trim((string)$row['category']);
    if ($predName === '') {
        return null;
    }

    $score = round(isset($row['score']) ? (float)$row['score'] : 0);
    $date = isset($row['date']) ? $row['date'] : '';
    $item = array(
        "result_id" => legacyEncodeResultId(2, isset($row['term']) ? $row['term'] : 0, isset($row['cat_id']) ? $row['cat_id'] : 0, 0),
        "pred_test_id" => $predId,
        "pred_test_name" => $predName,
        "total_questions" => 0,
        "correct_answers" => 0,
        "wrong_answer" => 0,
        "total_answered" => 0,
        "result_date" => $date,
        "score" => $score,
    );

    if ((int)$courseId === 24) {
        $item['date'] = $date;
        $item['session_date'] = $date;
        $item['applicant_name'] = $userName;
    }

    if (function_exists('buildPredictionScoreFeedback')) {
        $feedback = buildPredictionScoreFeedback((int)$courseId, $score, array(
            'pred_test_id' => $predId,
            'pred_name' => $predName,
        ));
        if ($feedback !== null) {
            $item['score_feedback'] = $feedback;
        }
    }

    return array($predName => $item);
}

function mergeLegacyOfficersIntoProgressReport(&$response, $user, $courseId, $userName)
{
    $oldUserId = isset($user['old_user_id']) ? (int)$user['old_user_id'] : 0;
    if ($oldUserId < 1) {
        return;
    }

    $payload = legacyOfficersResults($oldUserId);
    if (!is_array($payload)) {
        return;
    }

    $catalog = legacyOfficersCatalog($courseId);

    if (!isset($response['lesson_result']) || !is_array($response['lesson_result'])) {
        $response['lesson_result'] = array();
    }
    if (!empty($payload['result']) && is_array($payload['result'])) {
        foreach ($payload['result'] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $response['lesson_result'][] = legacyMapLessonRow($row, $catalog);
        }
    }

    if (!isset($response['prediction-test']) || !is_array($response['prediction-test'])) {
        $response['prediction-test'] = array();
    }
    if (!empty($payload['prediction-test']) && is_array($payload['prediction-test'])) {
        foreach ($payload['prediction-test'] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $item = legacyMapPredictionListItem($row, $catalog, $courseId, $userName);
            if ($item) {
                $response['prediction-test'][] = $item;
            }
        }
    }
}

