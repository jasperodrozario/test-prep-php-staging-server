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

function scaleShsatSectionScore($percent)
{
    $percent = (float)$percent;
    if ($percent < 0) {
        $percent = 0;
    } elseif ($percent > 100) {
        $percent = 100;
    }

    $score = (int)round(14 + ($percent / 100) * 386);
    if ($score < 14) {
        return 14;
    }
    if ($score > 400) {
        return 400;
    }

    return $score;
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
        // Always the official section scale (14–400, 280/250). Score > 400 is
        // treated as ELA+Math composite (200–800). Do not use the old AFOQT
        // 20–75 / percent-accuracy fallback.
        if ($score > 400) {
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
        $score
    );
}
