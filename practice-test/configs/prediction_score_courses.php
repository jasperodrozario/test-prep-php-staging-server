<?php

/**
 * Course-specific prediction scoring + family mapping for the mobile APIs.
 *
 * State Math grades are not hardcoded: they are active rows in
 * asvab_course_variants whose parent is asvab_course.course_code
 * "state-math-test-prep" (asvab_course.course_id 60). The parent itself
 * is not a scored course. Other apps resolve via asvab_course.course_code
 * and course_id_mapping.main_course_id.
 */

if (!function_exists('predictionScoreFeedbackCatalog')) {
    include_once __DIR__ . '/prediction_score_feedback.php';
}

function predictionScoreAsvabCourseIdByCode($courseCode)
{
    if (!function_exists('getFldValueById')) {
        return 0;
    }

    return (int)getFldValueById(
        'asvab_course',
        'course_id',
        array('course_code' => $courseCode)
    );
}

function predictionScoreAsvabCodeForMainCourse($courseId)
{
    $courseId = (int)$courseId;
    if ($courseId <= 0 || !function_exists('getFldValueById') || !function_exists('getTotalRow')) {
        return '';
    }

    if ((int)getTotalRow('course_id_mapping', array('main_course_id' => $courseId)) < 1) {
        return '';
    }

    $asvabId = (int)getFldValueById(
        'course_id_mapping',
        'asvab_course_id',
        array('main_course_id' => $courseId)
    );
    if ($asvabId <= 0) {
        return '';
    }

    return strtolower(trim((string)getFldValueById(
        'asvab_course',
        'course_code',
        array('course_id' => $asvabId)
    )));
}

function predictionScoreStateMathParentCourseId()
{
    static $parentId = null;
    if ($parentId !== null) {
        return $parentId;
    }

    $parentId = predictionScoreAsvabCourseIdByCode('state-math-test-prep');
    return $parentId;
}

function isStateMathCourseId($courseId)
{
    $courseId = (int)$courseId;
    $parentId = predictionScoreStateMathParentCourseId();
    if ($courseId <= 0 || $parentId <= 0 || !function_exists('getFldValueById') || !function_exists('getTotalRow')) {
        return false;
    }

    if ((int)getTotalRow(
        'asvab_course_variants',
        array('main_course_id' => $courseId, 'is_active' => 1)
    ) < 1) {
        return false;
    }

    $variantParent = (int)getFldValueById(
        'asvab_course_variants',
        'parent_asvab_course_id',
        array('main_course_id' => $courseId, 'is_active' => 1)
    );

    return $variantParent === $parentId;
}

function isShsatCourseId($courseId)
{
    $courseId = (int)$courseId;
    if ($courseId <= 0) {
        return false;
    }

    return predictionScoreAsvabCodeForMainCourse($courseId) === 'shsat';
}

function isAsvabAfqtCourseId($courseId)
{
    $courseId = (int)$courseId;
    $code = predictionScoreAsvabCodeForMainCourse($courseId);
    if ($code === 'asvab' || $code === 'asvab-gt') {
        return true;
    }

    // ASVAB Tutoring is not in course_id_mapping; same AFQT overall score.
    $name = '';
    if (function_exists('getFldValueById') && function_exists('getTotalRow')
        && (int)getTotalRow('course', array('course_id' => $courseId)) > 0
    ) {
        $name = strtolower(trim((string)getFldValueById(
            'course',
            'course_name',
            array('course_id' => $courseId)
        )));
    }

    return ($name === 'asvab tutoring');
}

function scaleStateMathSolScore($percent)
{
    global $data;

    $percent = (int)round((float)$percent);
    if ($percent < 0) {
        $percent = 0;
    } elseif ($percent > 100) {
        $percent = 100;
    }

    $scaled_to_50 = (int)round(($percent / 100) * 50);
    if ($scaled_to_50 < 0) {
        $scaled_to_50 = 0;
    } elseif ($scaled_to_50 > 50) {
        $scaled_to_50 = 50;
    }

    $table = (isset($data['state_math_test_prep_score_array']) && is_array($data['state_math_test_prep_score_array']))
        ? $data['state_math_test_prep_score_array']
        : array();

    if (isset($table[$scaled_to_50])) {
        return (int)$table[$scaled_to_50];
    }

    return 0;
}

function shsatCategorySection($cat)
{
    if (!is_array($cat)) {
        return '';
    }

    $code = strtolower(trim((string)($cat['shortcode'] ?? '')));
    $name = strtolower(trim((string)($cat['category_name'] ?? '')));
    $haystack = $code . ' ' . $name;

    if (strpos($haystack, 'ela') !== false || strpos($haystack, 'english') !== false) {
        return 'english';
    }
    if (strpos($haystack, 'math') !== false) {
        return 'math';
    }

    return '';
}

function scaleShsatRawScore($raw)
{
    global $data;

    $raw = (int)$raw;
    if ($raw < 0) {
        $raw = 0;
    } elseif ($raw > 57) {
        $raw = 57;
    }

    $table = (isset($data['shsat_pred_score_array']) && is_array($data['shsat_pred_score_array']))
        ? $data['shsat_pred_score_array']
        : array();

    if (isset($table[$raw])) {
        return (int)$table[$raw];
    }

    return 0;
}

function scaleShsatSectionScore($percent)
{
    $percent = (int)round((float)$percent);
    if ($percent < 0) {
        $percent = 0;
    } elseif ($percent > 100) {
        $percent = 100;
    }

    return scaleShsatRawScore((int)round(($percent / 100) * 57));
}

function computeShsatSectionScaledScore($correct, $total)
{
    $correct = (int)$correct;
    $total = (int)$total;
    if ($correct < 0) {
        $correct = 0;
    }
    if ($total > 0 && $correct > $total) {
        $correct = $total;
    }

    // Official table is raw correct answers (0–57). Unanswered are already
    // excluded from $correct. Do not percent-scale a 5/10/42-item ELA part.
    return scaleShsatRawScore($correct);
}

function computeShsatCompositeScore($englishCorrect, $englishTotal, $mathCorrect, $mathTotal)
{
    $english = ((int)$englishTotal > 0)
        ? computeShsatSectionScaledScore($englishCorrect, $englishTotal)
        : 0;
    $math = ((int)$mathTotal > 0)
        ? computeShsatSectionScaledScore($mathCorrect, $mathTotal)
        : 0;

    return array(
        'english' => $english,
        'math' => $math,
        'total' => $english + $math,
    );
}

/**
 * SHSAT web prediction test: the ELA backend categories (Part A, Part B,
 * Reading Comprehension) are shown and reported as one "English Language Arts"
 * section; Mathematics stays its own section. Each group is keyed by its first
 * backend cat_id. Returns an empty array for every other course.
 */
function shsatPredictionCategoryGroups($courseId, $predCatList)
{
    // $predCatList may be an array or the mysqli_result returned by getQuery().
    if (!isShsatCourseId($courseId) || !(is_array($predCatList) || $predCatList instanceof Traversable)) {
        return array();
    }

    $groups = array();
    $catToGroup = array();
    $englishKey = 0;

    foreach ($predCatList as $p) {
        $cat = getDataByWhere('category', '*', array('cat_id' => $p['cat_id']));
        if (!is_array($cat) || empty($cat['cat_id'])) {
            continue;
        }
        $catId = (int)$cat['cat_id'];
        $section = shsatCategorySection($cat);
        $isEnglish = ($section === 'english');

        if ($isEnglish && $englishKey) {
            $key = $englishKey;
        } else {
            $key = $catId;
            $groups[$key] = array(
                'cat_id' => $key,
                'category_name' => $isEnglish ? 'English Language Arts' : $cat['category_name'],
                'shortcode' => $isEnglish ? 'ELA' : $cat['shortcode'],
                'section' => $section,
                'cat_ids' => array(),
                'total' => 0,
                'duration' => 0,
            );
            if ($isEnglish) {
                $englishKey = $key;
            }
        }

        $groups[$key]['cat_ids'][] = $catId;
        $groups[$key]['total'] += (int)$p['number_of_questions'];
        $groups[$key]['duration'] += (int)$p['duration'];
        $catToGroup[$catId] = $key;
    }

    return array('groups' => $groups, 'cat_to_group' => $catToGroup);
}

function predictionScoreAfoqtFamilyFromLabel($label)
{
    $n = strtolower(trim((string)$label));
    $n = str_replace(array('_', '-'), ' ', $n);

    switch ($n) {
        case 'pilot':
        case 'pil':
            return 'afoqt_pilot';
        case 'cso':
            return 'afoqt_cso';
        case 'abm':
            return 'afoqt_abm';
        case 'academic aptitude':
        case 'aa':
            return 'afoqt_academic';
        case 'verbal':
        case 'v':
            return 'afoqt_verbal';
        case 'quantitative':
        case 'q':
            return 'afoqt_quantitative';
        case 'afoqt':
        case 'afoqt prediction test':
            return 'afoqt';
        default:
            return '';
    }
}

function predictionScorePredTestLabel($predTestId)
{
    $predTestId = (int)$predTestId;
    if ($predTestId <= 0 || !function_exists('getFldValueById') || !function_exists('getTotalRow')) {
        return '';
    }
    if ((int)getTotalRow('predic_test', array('Predic_test_id' => $predTestId)) < 1) {
        return '';
    }

    $short = trim((string)getFldValueById(
        'predic_test',
        'shortcode',
        array('Predic_test_id' => $predTestId)
    ));
    if ($short !== '') {
        return $short;
    }

    return trim((string)getFldValueById(
        'predic_test',
        'test_name',
        array('Predic_test_id' => $predTestId)
    ));
}

function predictionScoreAfoqtReportCompositeMap()
{
    return array(
        'PIL' => 'Pilot',
        'CSO' => 'CSO',
        'ABM' => 'ABM',
        'AA' => 'Academic Aptitude',
        'V' => 'Verbal',
        'Q' => 'Quantitative',
    );
}

function predictionScoreAttachFeedback(&$row, $courseId, $context = array())
{
    if (!is_array($row) || !isset($row['score'])) {
        return;
    }
    $feedback = buildPredictionScoreFeedback($courseId, $row['score'], $context);
    if ($feedback !== null) {
        $row['score_feedback'] = $feedback;
    }
}

function predictionScoreFeedbackFamily($courseId, $score, $context = array())
{
    $courseId = (int)$courseId;
    if (!is_array($context)) {
        $context = array();
    }
    $code = predictionScoreAsvabCodeForMainCourse($courseId);

    if (isAsvabAfqtCourseId($courseId)) {
        return 'asvab';
    }
    if ($code === 'afoqt') {
        $label = '';
        if (!empty($context['pred_name'])) {
            $label = $context['pred_name'];
        } elseif (!empty($context['pred_test_id'])) {
            $label = predictionScorePredTestLabel($context['pred_test_id']);
        }
        $family = predictionScoreAfoqtFamilyFromLabel($label);
        return $family !== '' ? $family : 'afoqt';
    }
    if ($code === 'oar') {
        return 'oar';
    }
    if ($code === 'sift') {
        return 'sift';
    }
    if ($code === 'astb') {
        return 'astb';
    }
    if (isShsatCourseId($courseId)) {
        $totalQuestions = isset($context['total_questions']) ? (int)$context['total_questions'] : 0;
        if (!empty($context['shsat_composite']) || $score > 400 || $totalQuestions >= 114) {
            return 'shsat_composite';
        }
        return 'shsat_section';
    }
    if (isStateMathCourseId($courseId)) {
        // Always official Virginia SOL 0–600 (500 Pass Advanced / 400 Pass).
        // Do not use the old AFOQT 20–75 fallback.
        return 'sol';
    }

    return 'generic';
}

function buildPredictionScoreFeedback($courseId, $score, $context = array())
{
    return buildPredictionScoreFeedbackByFamily(
        predictionScoreFeedbackFamily($courseId, $score, $context),
        $score,
        $context
    );
}

/**
 * Share-score ranges for submit-app-review, keyed by subscription course_id.
 * Final = official exam; initial = in-app prediction score (optional).
 */
function getAppReviewScoreRanges($course_id)
{
    $course_id = (int)$course_id;

    if ($course_id == 30) {
        return array(
            'final_min' => 200,
            'final_max' => 800,
            'initial_min' => 14,
            'initial_max' => 400,
            'final_label' => 'SHSAT composite score between 200 and 800',
            'initial_label' => 'SHSAT section score between 14 and 400',
        );
    }

    if (in_array($course_id, array(46, 47, 48, 49, 50, 51, 60), true)) {
        return array(
            'final_min' => 0,
            'final_max' => 600,
            'initial_min' => 0,
            'initial_max' => 600,
            'final_label' => 'SOL score between 0 and 600',
            'initial_label' => 'SOL score between 0 and 600',
        );
    }

    if (in_array($course_id, array(21, 25, 26), true)) {
        return array(
            'final_min' => 20,
            'final_max' => 80,
            'initial_min' => 20,
            'initial_max' => 80,
            'final_label' => 'score between 20 and 80',
            'initial_label' => 'score between 20 and 80',
        );
    }

    return array(
        'final_min' => 1,
        'final_max' => 99,
        'initial_min' => 1,
        'initial_max' => 99,
        'final_label' => 'score between 1 and 99',
        'initial_label' => 'score between 1 and 99',
    );
}