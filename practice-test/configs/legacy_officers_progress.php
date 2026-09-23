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

function legacyDecodeResultId($resultId)
{
    if ($resultId === '' || $resultId === null || !is_numeric($resultId)) {
        return null;
    }
    $resultId = (int)$resultId;
    if ($resultId >= 0) {
        return null;
    }
    $value = -$resultId;
    $type = intdiv($value, 10000000000);
    $value = $value % 10000000000;
    $term = intdiv($value, 10000000);
    $value = $value % 10000000;
    $catId = intdiv($value, 10000);
    $lessonId = $value % 10000;
    if ($type !== 1 && $type !== 2) {
        return null;
    }
    return array(
        'type' => $type,
        'term' => $term,
        'cat_id' => $catId,
        'lesson_id' => $lessonId,
    );
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

function legacyTryReviewTest(&$response, $user, $courseId)
{
    $decoded = legacyDecodeResultId(isset($_POST['result_id']) ? $_POST['result_id'] : '');
    if (!$decoded || $decoded['type'] !== 1 || empty($user['old_user_id'])) {
        return false;
    }

    $detail = legacyOfficersRequest(array(
        'action' => 'review-test-using-user-id',
        'user_id' => (int)$user['old_user_id'],
        'cat_id' => $decoded['cat_id'],
        'lesson_id' => $decoded['lesson_id'],
        'term' => $decoded['term'],
    ));
    if (empty($detail['report']) || !is_array($detail['report'])) {
        return false;
    }

    $report = $detail['report'];
    $catalog = legacyOfficersCatalog($courseId);
    $mapped = legacyMapLessonRow(array(
        'category' => isset($report['cat_name']) ? $report['cat_name'] : '',
        'cat_id' => $decoded['cat_id'],
        'lesson_name' => isset($report['lesson_name']) ? $report['lesson_name'] : '',
        'lesson_id' => $decoded['lesson_id'],
        'term' => $decoded['term'],
        'date' => isset($report['date']) ? $report['date'] : '',
        'correct' => isset($report['correct']) ? $report['correct'] : 0,
        'total_questions' => isset($report['total_questions']) ? $report['total_questions'] : 0,
        'wrong' => isset($report['wrong']) ? $report['wrong'] : 0,
        'score' => isset($report['score']) ? $report['score'] : 0,
    ), $catalog);

    $response['report'] = array(
        "result_id" => $mapped['result_id'],
        "cat_id" => $mapped['cat_id'],
        "cat_name" => $mapped['cat_name'],
        "lesson_id" => $mapped['lesson_id'],
        "lesson_name" => $mapped['lesson_name'],
        "course_id" => (int)$courseId,
        "date" => !empty($report['date']) ? showdate($report['date']) : '',
        "correct" => isset($report['correct']) ? $report['correct'] : 0,
        "total_questions" => isset($report['total_questions']) ? $report['total_questions'] : 0,
        "wrong" => isset($report['wrong']) ? $report['wrong'] : 0,
        "score" => round(isset($report['score']) ? (float)$report['score'] : 0),
    );

    $qlist = array();
    if (!empty($detail['qlist']) && is_array($detail['qlist'])) {
        foreach ($detail['qlist'] as $item) {
            if (!is_array($item)) {
                continue;
            }
            $explanation = isset($item['explanation']) ? $item['explanation'] : '';
            if (is_string($explanation) && $explanation !== '') {
                $explanation = html_entity_decode(html_entity_decode(str_replace('&#92;', '\\', $explanation)));
            }
            $item['explanation'] = $explanation;
            $qlist[] = $item;
        }
    }
    $response['qlist'] = $qlist;
    return true;
}

function legacyPracticeReportPayload($courseId, $decoded, $report, $roundScore)
{
    $catalog = legacyOfficersCatalog($courseId);
    $mapped = legacyMapLessonRow(array(
        'category' => isset($report['cat_name']) ? $report['cat_name'] : '',
        'cat_id' => $decoded['cat_id'],
        'lesson_name' => isset($report['lesson_name']) ? $report['lesson_name'] : '',
        'lesson_id' => $decoded['lesson_id'],
        'term' => $decoded['term'],
        'date' => isset($report['date']) ? $report['date'] : '',
        'correct' => isset($report['correct']) ? $report['correct'] : 0,
        'total_questions' => isset($report['total_questions']) ? $report['total_questions'] : 0,
        'wrong' => isset($report['wrong']) ? $report['wrong'] : 0,
        'score' => isset($report['score']) ? $report['score'] : 0,
    ), $catalog);
    $score = isset($report['score']) ? (float)$report['score'] : 0;

    return array(
        "result_id" => $mapped['result_id'],
        "cat_id" => $mapped['cat_id'],
        "cat_name" => $mapped['cat_name'],
        "lesson_id" => $mapped['lesson_id'],
        "lesson_name" => $mapped['lesson_name'],
        "course_id" => (int)$courseId,
        "date" => !empty($report['date']) ? showdate($report['date']) : '',
        "correct" => isset($report['correct']) ? $report['correct'] : 0,
        "total_questions" => isset($report['total_questions']) ? $report['total_questions'] : 0,
        "wrong" => isset($report['wrong']) ? $report['wrong'] : 0,
        "score" => $roundScore ? round($score) : (int)$score,
    );
}

function legacyTryPracticeTestResult(&$response, $user, $courseId)
{
    $decoded = legacyDecodeResultId(isset($_POST['result_id']) ? $_POST['result_id'] : '');
    if (!$decoded || $decoded['type'] !== 1 || empty($user['old_user_id'])) {
        return false;
    }

    $detail = legacyOfficersRequest(array(
        'action' => 'result-using-user-id',
        'user_id' => (int)$user['old_user_id'],
        'cat_id' => $decoded['cat_id'],
        'lesson_id' => $decoded['lesson_id'],
        'term' => $decoded['term'],
    ));
    if (empty($detail['report']) || !is_array($detail['report'])) {
        return false;
    }

    $response['report'] = legacyPracticeReportPayload($courseId, $decoded, $detail['report'], false);
    return true;
}

function legacyLookupPredictionScore($oldUserId, $oldCatId, $term)
{
    $payload = legacyOfficersResults($oldUserId);
    if (empty($payload['prediction-test']) || !is_array($payload['prediction-test'])) {
        return 0;
    }
    foreach ($payload['prediction-test'] as $row) {
        if (is_array($row) && (int)$row['cat_id'] === (int)$oldCatId && (int)$row['term'] === (int)$term) {
            return isset($row['score']) ? $row['score'] : 0;
        }
    }
    foreach ($payload['prediction-test'] as $row) {
        if (is_array($row) && (int)$row['cat_id'] === (int)$oldCatId) {
            return isset($row['score']) ? $row['score'] : 0;
        }
    }
    return 0;
}

function legacyPredictionDetail($user, $decoded)
{
    $detail = legacyOfficersRequest(array(
        'action' => 'view-prediction-result-using-user-id',
        'user_id' => (int)$user['old_user_id'],
        'cat_id' => $decoded['cat_id'],
        'term' => $decoded['term'],
    ));
    if (!is_array($detail)) {
        return null;
    }

    $score = isset($detail['afqt_score']) ? $detail['afqt_score'] : null;
    if ($score === null || $score === '') {
        $score = legacyLookupPredictionScore((int)$user['old_user_id'], $decoded['cat_id'], $decoded['term']);
    }

    return array(
        'detail' => $detail,
        'score' => (int)round((float)$score),
        'date' => !empty($detail['date']) ? showdate($detail['date']) : '',
        'result_id' => legacyEncodeResultId(2, $decoded['term'], $decoded['cat_id'], 0),
        'pred_id' => 0,
    );
}

function legacyResolvePostedPredId($catalog, $detail, $decoded)
{
    $postedPredId = isset($_POST['pred_test_id']) ? (int)$_POST['pred_test_id'] : 0;
    if ($postedPredId > 0) {
        return $postedPredId;
    }
    $resolved = legacyResolvePrediction($catalog, array(
        'category' => isset($detail['cat_name']) ? $detail['cat_name'] : '',
        'cat_id' => $decoded['cat_id'],
    ));
    return $resolved ? (int)$resolved['Predic_test_id'] : 0;
}

function legacyPredictionTestMeta($predTestId, $isPaidUser)
{
    $predTestId = (int)$predTestId;
    $row = $predTestId > 0 ? getDataByWhere("predic_test", "*", array("Predic_test_id" => $predTestId)) : null;
    if (!is_array($row) || empty($row['Predic_test_id'])) {
        return array(
            "Predic_test_id" => $predTestId,
            "course_id" => null,
            "test_name" => "",
            "test_order" => null,
            "shortcode" => "",
            "online" => null,
            "free" => null,
            "display_pred_test_review" => $isPaidUser,
        );
    }
    return array(
        "Predic_test_id" => $row['Predic_test_id'],
        "course_id" => $row['course_id'],
        "test_name" => $row['test_name'],
        "test_order" => $row['test_order'],
        "shortcode" => $row['shortcode'],
        "online" => $row['online'],
        "free" => $row['free'],
        "display_pred_test_review" => $isPaidUser,
    );
}

function legacyPredictionSubjects($catalog, $detail, $courseId, $predId, $resultId, $date)
{
    $subjects = array();
    if (empty($detail['afqt_report']) || !is_array($detail['afqt_report'])) {
        return $subjects;
    }
    foreach ($detail['afqt_report'] as $subject => $subjectScore) {
        $category = legacyResolveCategory($catalog, $subject);
        $catName = $category ? $category['category_name'] : trim((string)$subject);
        if ($catName === '') {
            continue;
        }
        $subjects[$catName] = array(
            "result_id" => $resultId,
            "cat_id" => $category ? (int)$category['cat_id'] : 0,
            "cat_name" => $catName,
            "shortcode" => $category ? $category['shortcode'] : '',
            "pred_test_id" => $predId,
            "course_id" => (int)$courseId,
            "date" => $date,
            "correct" => 0,
            "total_questions" => 0,
            "wrong" => 0,
            "score" => (int)round((float)$subjectScore),
        );
    }
    return $subjects;
}

function legacyTryPredictionTestResult(&$response, $user, $courseId, $userName, $isPaidUser)
{
    $decoded = legacyDecodeResultId(isset($_POST['result_id']) ? $_POST['result_id'] : '');
    if (!$decoded || $decoded['type'] !== 2 || empty($user['old_user_id'])) {
        return false;
    }

    $loaded = legacyPredictionDetail($user, $decoded);
    if ($loaded === null) {
        return false;
    }

    $catalog = legacyOfficersCatalog($courseId);
    $predId = legacyResolvePostedPredId($catalog, $loaded['detail'], $decoded);
    $response['prediction_test'] = legacyPredictionTestMeta($predId, $isPaidUser);

    $report = array();
    $report['prediction_score'] = array(
        "result_id" => $loaded['result_id'],
        "cat_id" => $predId,
        "pred_test_id" => $predId,
        "course_id" => (int)$courseId,
        "date" => $loaded['date'],
        "correct" => 0,
        "total_questions" => 0,
        "wrong" => 0,
        "score" => $loaded['score'],
    );
    foreach (legacyPredictionSubjects($catalog, $loaded['detail'], $courseId, $predId, $loaded['result_id'], $loaded['date']) as $catName => $subject) {
        $report[$catName] = $subject;
    }

    if (function_exists('buildPredictionScoreFeedback')) {
        $feedback = buildPredictionScoreFeedback((int)$courseId, $loaded['score'], array(
            'pred_test_id' => $predId,
            'pred_name' => isset($response['prediction_test']['test_name']) ? $response['prediction_test']['test_name'] : '',
        ));
        if ($feedback !== null) {
            $report['score_feedback'] = $feedback;
        }
    }

    if ((int)$courseId === 24) {
        $report['applicant_name'] = $userName;
        $report['session_date'] = $loaded['date'];
        $report['afqt_percentile_score'] = array(
            "result_id" => $loaded['result_id'],
            "user_id" => (int)$user['user_id'],
            "cat_id" => $predId,
            "Predic_test_id" => $predId,
            "score" => (string)$loaded['score'],
            "result_date" => $loaded['date'],
            "pred_name" => "AFOQT",
        );
    }

    $response['report'] = $report;
    return true;
}

function legacyTryReviewPredictionTest(&$response, $user, $courseId)
{
    $decoded = legacyDecodeResultId(isset($_POST['result_id']) ? $_POST['result_id'] : '');
    if (!$decoded || $decoded['type'] !== 2 || empty($user['old_user_id'])) {
        return false;
    }

    $loaded = legacyPredictionDetail($user, $decoded);
    if ($loaded === null) {
        return false;
    }

    $catalog = legacyOfficersCatalog($courseId);
    $predId = legacyResolvePostedPredId($catalog, $loaded['detail'], $decoded);
    $subjects = legacyPredictionSubjects($catalog, $loaded['detail'], $courseId, $predId, $loaded['result_id'], $loaded['date']);

    $scores = array();
    $selected = null;
    $postedCatId = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;
    foreach ($subjects as $catName => $subject) {
        $scores[$catName] = array(
            "cat_name" => $catName,
            "result_id" => $loaded['result_id'],
            "cat_id" => $subject['cat_id'],
            "pred_test_id" => $predId,
            "total_questions" => 0,
            "correct_answers" => 0,
            "wrong_answers" => 0,
            "score" => $subject['score'],
            "result_date" => $loaded['date'],
            "parent_id" => 0,
        );
        if ($selected === null || ($postedCatId > 0 && (int)$subject['cat_id'] === $postedCatId)) {
            $selected = $subject;
        }
    }

    $response['pred_test_cat_wise_scores'] = $scores;
    $response['prediction_score'] = $loaded['score'];
    $response['cat_id'] = $selected ? $selected['cat_id'] : $postedCatId;
    $response['cat_name'] = $selected ? $selected['cat_name'] : '';
    $response['questions'] = array();
    return true;
}

