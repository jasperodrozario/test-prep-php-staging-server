<?php

/**
 * Generic prediction-test score feedback.
 *
 * Family profiles, scales, and cutoffs (no course IDs here):
 *   asvab            AFQT percentile  1–99     excellence 65 / progress 50
 *   afoqt            overall percentile 1–99   excellence 70 / progress 50
 *   afoqt_pilot      Pilot composite    1–99   excellence 70 / progress 50 (rated floor 25)
 *   afoqt_cso        CSO composite      1–99   excellence 70 / progress 50 (rated floor 25)
 *   afoqt_abm        ABM composite      1–99   excellence 70 / progress 50 (rated floor 25)
 *   afoqt_academic   Academic Aptitude  1–99   excellence 70 / progress 50
 *   afoqt_verbal     Verbal composite   1–99   excellence 70 / progress 50 (floor 15)
 *   afoqt_quantitative Quantitative     1–99   excellence 70 / progress 50 (floor 10)
 *   oar              T-score          20–80    excellence 60 / progress 50
 *   sift             T-score          20–80    excellence 60 / progress 50
 *   astb             academic/OAR     20–80    excellence 60 / progress 50
 *   shsat_section    section scaled   14–400   excellence 280 / progress 250
 *   shsat_composite  composite        200–800  excellence 560 / progress 495  (only if stored score > 400)
 *   sol              Virginia SOL     0–600    excellence 500 / progress 400
 *   generic          fallback         1–99     excellence 65 / progress 50
 *
 * Course ID → family mapping lives in prediction_score_courses.php.
 */

function predictionScoreFeedbackPercent($correct, $total)
{
    $total = (int)$total;
    if ($total <= 0) {
        return 0;
    }

    return (int)round(((int)$correct / $total) * 100);
}

function predictionScoreFeedbackInterpolate($template, $score)
{
    return str_replace('{score}', (string)$score, (string)$template);
}

function predictionScoreFeedbackCatalog()
{
    $shared_titles = array(
        'excellence' => 'Excellence Achieved!',
        'progress' => 'Good Progress!',
        'focus' => 'Focus Required',
    );

    return array(
        'asvab' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 65,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} AFQT (percentile). That’s Category II or better — an excellent score that opens far more job options across the branches.',
                'progress' => 'You scored {score} AFQT. You’re at or above the national average (50) and in Category IIIA, where enlistment incentives often start. Keep practicing to reach 65+ (Category II).',
                'focus' => 'You scored {score} AFQT (Category IIIB or below). Most branch minimums are around 31–40, but better jobs usually need 50–65+. Aim for 65+ on our platform.',
            ),
        ),
        'afoqt' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 70,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} (percentile 1–99). That’s in the competitive range for rated and non-rated boards — well above the published Pilot/CSO/ABM minimum of 25.',
                'progress' => 'You scored {score}. You clear commissioning floors (Verbal 15 / Quantitative 10) and the rated minimum of 25. Keep working toward 70+ so the composite stands out.',
                'focus' => 'You scored {score}. Rated tracks need much more than the published 25 minimum to be competitive. Aim for 70+ on our platform.',
            ),
        ),
        'afoqt_pilot' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 70,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the Pilot composite (percentile 1–99). That’s in the competitive range for rated boards — well above the published Pilot minimum of 25.',
                'progress' => 'You scored {score} on Pilot. You clear the rated minimum of 25. Keep working toward 70+ so this composite stands out.',
                'focus' => 'You scored {score} on Pilot. Rated tracks need much more than the published 25 minimum to be competitive. Aim for 70+ on our platform.',
            ),
        ),
        'afoqt_cso' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 70,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the CSO composite (percentile 1–99). That’s in the competitive range for rated boards — well above the published CSO minimum of 25.',
                'progress' => 'You scored {score} on CSO. You clear the rated minimum of 25. Keep working toward 70+ so this composite stands out.',
                'focus' => 'You scored {score} on CSO. Rated tracks need much more than the published 25 minimum to be competitive. Aim for 70+ on our platform.',
            ),
        ),
        'afoqt_abm' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 70,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the ABM composite (percentile 1–99). That’s in the competitive range for rated boards — well above the published ABM minimum of 25.',
                'progress' => 'You scored {score} on ABM. You clear the rated minimum of 25. Keep working toward 70+ so this composite stands out.',
                'focus' => 'You scored {score} on ABM. Rated tracks need much more than the published 25 minimum to be competitive. Aim for 70+ on our platform.',
            ),
        ),
        'afoqt_academic' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 70,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on Academic Aptitude (percentile 1–99). That’s a strong academic composite for commissioning boards.',
                'progress' => 'You scored {score} on Academic Aptitude. Keep working toward 70+ so this composite stands out.',
                'focus' => 'You scored {score} on Academic Aptitude. Aim for 70+ on our platform so the academic composite is competitive.',
            ),
        ),
        'afoqt_verbal' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 70,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the Verbal composite (percentile 1–99). That’s a strong Verbal result — well above the commissioning floor of 15.',
                'progress' => 'You scored {score} on Verbal. You clear the Verbal commissioning floor of 15. Keep working toward 70+ so this composite stands out.',
                'focus' => 'You scored {score} on Verbal. The published commissioning floor is 15, but competitive packages sit much higher. Aim for 70+ on our platform.',
            ),
        ),
        'afoqt_quantitative' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 70,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the Quantitative composite (percentile 1–99). That’s a strong Quant result — well above the commissioning floor of 10.',
                'progress' => 'You scored {score} on Quantitative. You clear the Quantitative commissioning floor of 10. Keep working toward 70+ so this composite stands out.',
                'focus' => 'You scored {score} on Quantitative. The published commissioning floor is 10, but competitive packages sit much higher. Aim for 70+ on our platform.',
            ),
        ),
        'oar' => array(
            'scale_min' => 20,
            'scale_max' => 80,
            'excellence_threshold' => 60,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the OAR (20–80). That’s above the typical 40–60 range and a strong score for most Navy officer programs.',
                'progress' => 'You scored {score}. You’re at or above the typical OAR average (~50) and at the floor many communities use (Intel/IP 50). Keep pushing toward 60+ to stand out.',
                'focus' => 'You scored {score}. Some programs sit near 40–45, but 50 is the safer competitive target. Aim for 60+ on our platform.',
            ),
        ),
        'sift' => array(
            'scale_min' => 20,
            'scale_max' => 80,
            'excellence_threshold' => 60,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the SIFT (20–80). That’s a strong first-attempt score for Army aviation. A qualifying score cannot be retaken, so this is a solid number to lock in.',
                'progress' => 'You scored {score}. You’re at or above the SIFT average (~50) and well above the 40 qualifying minimum. Competitive packets usually sit here or higher — aim for 60+ before you test.',
                'focus' => 'You scored {score}. 40 is the qualifying minimum; below 40 you are not eligible. 50+ is the typical competitive range, and you only get two lifetime attempts. Aim for 60+ on our platform before you sit the real SIFT.',
            ),
        ),
        'astb' => array(
            'scale_min' => 20,
            'scale_max' => 80,
            'excellence_threshold' => 60,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} on the ASTB academic / OAR scale (20–80). That’s a strong academic result — above the typical 40–60 range.',
                'progress' => 'You scored {score}. You’re at or above the typical average (~50). Keep studying toward 60+ on the academic battery.',
                'focus' => 'You scored {score}. That’s below the competitive academic range. Aim for 60+ on our platform. Aviation selection also uses AQR/PFAR/FOFAR stanines (1–9), which are separate from this 20–80 academic score.',
            ),
        ),
        'shsat_section' => array(
            'scale_min' => 14,
            'scale_max' => 400,
            'excellence_threshold' => 280,
            'progress_threshold' => 250,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} (section scale ~14–400). That’s Stuyvesant-range on this section and strong enough that a matching Math/ELA pair would clear every specialized high school.',
                'progress' => 'You scored {score}. You’re in range for several specialized high schools (Brooklyn Latin is about 250 per section / ~495 composite). Keep pushing toward 280+ for Stuyvesant-level.',
                'focus' => 'You scored {score}. Recent specialized-high-school cutoffs start around 495 composite (~250 per section). Aim for 280+ on each section.',
            ),
        ),
        'shsat_composite' => array(
            'scale_min' => 200,
            'scale_max' => 800,
            'excellence_threshold' => 560,
            'progress_threshold' => 495,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score} (composite ~200–800). That’s Stuyvesant-range and strong enough to clear every specialized high school.',
                'progress' => 'You scored {score}. You’re in range for several specialized high schools (Brooklyn Latin is about ~495 composite). Keep pushing toward 560+ for Stuyvesant-level.',
                'focus' => 'You scored {score}. Recent specialized-high-school cutoffs start around 495 composite. Aim for 560+.',
            ),
        ),
        'sol' => array(
            'scale_min' => 0,
            'scale_max' => 600,
            'excellence_threshold' => 500,
            'progress_threshold' => 400,
            'titles' => array(
                'excellence' => 'Pass Advanced',
                'progress' => 'Pass / Proficient',
                'focus' => 'Fail SOL',
            ),
            'official_labels' => array(
                'excellence' => 'Pass Advanced',
                'progress' => 'Pass / Proficient',
                'focus' => 'Fail SOL',
            ),
            'messages' => array(
                'excellence' => 'You scored {score} on the 0–600 SOL scale. That’s Pass Advanced — above the 500 advanced cut. Keep reviewing so this holds on test day.',
                'progress' => 'You scored {score}. That’s Pass / Proficient (400–499). You’re at grade-level. Aim for 500+ to reach Pass Advanced.',
                'focus' => 'You scored {score}. Below 400 is Fail on the SOL. 400 is Pass / Proficient; 500 is Pass Advanced. Aim for 500+ on our platform.',
            ),
        ),
        'generic' => array(
            'scale_min' => 1,
            'scale_max' => 99,
            'excellence_threshold' => 65,
            'progress_threshold' => 50,
            'titles' => $shared_titles,
            'messages' => array(
                'excellence' => 'You scored {score}. That’s a strong prediction-test result. Keep reviewing so this holds on test day.',
                'progress' => 'You scored {score}. You’re making good progress. Keep practicing to reach 65+.',
                'focus' => 'You scored {score}. Focus on weaker areas and aim for 65+ on our platform.',
            ),
        ),
    );
}

function predictionScoreFeedbackUserIsSubscribed($context = array())
{
    if (!is_array($context)) {
        return false;
    }

    if (!empty($context['user']) && is_array($context['user']) && isset($context['user']['activation_status'])) {
        return $context['user']['activation_status'] == 'Y';
    }

    $user_id = 0;
    if (!empty($context['user']) && is_array($context['user']) && !empty($context['user']['user_id'])) {
        $user_id = (int)$context['user']['user_id'];
    } elseif (!empty($context['user_id'])) {
        $user_id = (int)$context['user_id'];
    }

    if ($user_id > 0 && function_exists('getFldValueById')) {
        return getFldValueById("user", "activation_status", array("user_id" => $user_id)) == 'Y';
    }

    return false;
}

function buildPredictionScoreFeedbackByFamily($family, $score, $context = array())
{
    if ($score === null || $score === '' || !is_numeric($score)) {
        return null;
    }

    $score = (int)round((float)$score);
    $catalog = predictionScoreFeedbackCatalog();
    $family = (string)$family;
    if ($family === '' || !isset($catalog[$family]) || !is_array($catalog[$family])) {
        $family = 'generic';
    }

    $config = $catalog[$family];
    $excellence = (int)$config['excellence_threshold'];
    $progress = (int)$config['progress_threshold'];

    if ($score >= $excellence) {
        $band = 'excellence';
    } elseif ($score >= $progress) {
        $band = 'progress';
    } else {
        $band = 'focus';
    }

    $title = isset($config['titles'][$band]) ? $config['titles'][$band] : '';
    $message = isset($config['messages'][$band]) ? $config['messages'][$band] : '';
    $subscribed = predictionScoreFeedbackUserIsSubscribed($context);
    $show_boost_cta = ($score < $excellence && !$subscribed);
    $official_label = null;
    if (!empty($config['official_labels'][$band])) {
        $official_label = $config['official_labels'][$band];
    }

    return array(
        'band' => $band,
        'title' => $title,
        'official_label' => $official_label,
        'message' => predictionScoreFeedbackInterpolate($message, $score),
        'score' => $score,
        'scale_min' => (int)$config['scale_min'],
        'scale_max' => (int)$config['scale_max'],
        'excellence_threshold' => $excellence,
        'progress_threshold' => $progress,
        'show_boost_cta' => $show_boost_cta,
        'boost_cta_label' => $show_boost_cta ? '🚀 Start Studying Now to Boost Your Score' : null,
    );
}
