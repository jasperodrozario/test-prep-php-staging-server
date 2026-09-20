<?php
include("../configs/autoload.php");
chklogin(str_replace("/practice-test/", "", $_SERVER['REQUEST_URI']));
if ($_SESSION['status'] == "N") {
    header("location:" . $configs['vpath'] . "renew-subscription");
    exit();
}

// Utility function to calculate composite score
function calculate_composite_score($categories, $cat_scores, $score_array)
{
    $correct = 0;
    $total = 0;
    foreach ($categories as $cat) {
        $correct += $cat_scores[$cat]['correct'];
        $total += $cat_scores[$cat]['total_question'];
    }
    $percentage = ($total > 0) ? round(($correct / $total) * 100) : 0;
    return $score_array[$percentage];
}

$data['option_no'] = 5;

$test_id = (int)$_SESSION['test_id'];

if (in_array($test_id, [27, 40, 28, 38, 39])) {

    $exam_table = "asvab_exam";
    $exam_record_table = "asvab_exam_records";
} elseif (in_array($test_id, [21, 24, 25, 26])) {

    $exam_table = "ocs_exam";
    $exam_record_table = "ocs_exam_records";
} elseif (in_array($test_id, [20, 33, 34])) {

    $exam_table = "health_care_exam";
    $exam_record_table = "health_care_exam_records";
} else {
    $exam_table = "exam";
    $exam_record_table = "exam_records";
}

if (!$_GET['cat_id'] && $_GET['result'] != 'flashcards') {
    $data['categories'] = [];

    if (strpos($_GET['cat_name'], 'prediction') == false) {

        $category_list = getQuery("category", "*", array("course_id" => $_SESSION['test_id'], "online" => "Y"), "category_order");
        foreach ($category_list as $p) {
            $data['categories'][] = ["cat_id" => $p['cat_id'], "category_name" => $p['category_name']];
        }
    } else {

        $pred_test_list = getQuery("predic_test", "*", array("course_id" => $_SESSION['test_id'], "online" => "Y"), "test_order");
        foreach ($pred_test_list as $p) {
            $data['categories'][] = ["cat_id" => $p['Predic_test_id'], "category_name" => $p['test_name'], "pred_test" => 1];
        }
    }
}
if ($_GET['cat_id']) {
    if ($_GET['result'] == "prediction-test") {
        $data['db_value'] = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_GET['cat_id']));
    } else {
        if (strpos($_GET['cat_name'], 'prediction') == true) {
            $data['db_value'] = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_GET['cat_id']));
            $data['db_value']['category_name'] = $data['db_value']['test_name'];
        } else {
            $data['db_value'] = getDataByWhere("category", "*", array("cat_id" => $_GET['cat_id']));
            $data['lesson'] = getQuery("lesson", "*", array("cat_id" => $_GET['cat_id'], "online" => "Y"));
            $data['lesson_total'] = getTotalRow("lesson", array("cat_id" => $_GET['cat_id'], "online" => "Y"));
        }
    }
}

if ($_GET['cat_id'] == 4444) {
    $data['prediction'] = getQuery("predic_test", "*", array("course_id" => $_SESSION['test_id'], "online" => "Y"));
    $data['prediction_total'] = getTotalRow("predic_test", array("course_id" => $_SESSION['test_id'], "online" => "Y"));
}

$data['feedback_exists'] = getTotalRow("prediction_test_feedback", array("user_id" => $_SESSION['user_id']));

$data['$feedback_submitted'] = false;

if (isset($_POST['submit_feedback'])) {
    $rating = (int)$_POST['rating'];
    $feedback = trim($_POST['feedback']);

    if ($data['feedback_exists']) {

        updateTable(
            "prediction_test_feedback",
            [
                "rating" => $rating,
                "feedback" => $feedback
            ],
            [
                "user_id" => $_SESSION['user_id'],
                "course_id" => $_SESSION['test_id']
            ]
        );
    } else {

        insertTable(
            "prediction_test_feedback",
            [
                "user_id" => $_SESSION['user_id'],
                "course_id" => $_SESSION['test_id'],
                "rating" => $rating,
                "feedback" => $feedback
            ]
        );
    }

    $data['feedback_submitted'] = true;
    
    $data['feedback_exists'] = getTotalRow("prediction_test_feedback", array("user_id" => $_SESSION['user_id']));
}

switch ($_GET['result']) {

    case "result":

        $result_id = (int)$_GET['result_id'];

        if (!$result_id) {
            $result_id = getFldValueById("result", "result_id", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "score" => -1));
        }

        $exits_score = (int)getFldValueById("result", "score", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));

        if ($exits_score == -1) {
            $data_table = $exam_table;
        } else {
            $data_table = $exam_record_table;
        }

        $data['date'] = date('Y-m-d H:i:s');

        $data['lesson_name'] = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $_GET['lesson_id']));

        $data['total'] = getTotalRow($data_table, array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));

        $data['total_correct'] = getTotalRow($data_table, array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id, "iscorrect" => 1));

        $data['total_wrong'] = getTotalRow($data_table, array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id, "iscorrect" => 0));

        $data['total_answer_given'] = $data['total_correct'] + $data['total_wrong'];

        $data['total_answer_not_given'] = $data['total'] - $data['total_answer_given'];

        $data['score'] = round(($data['total_correct'] / $data['total']) * 100);

        if ($exits_score == -1) {

            updateTable("result", array("correct_answers" => $data['total_correct'], "score" => $data['score'], "result_date" => date('Y-m-d H:i:s')), array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));

            $exam_list = getQuery($exam_table, "*", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));

            foreach ($exam_list as $r) {

                insertTable($exam_record_table, ["user_id" => $_SESSION['user_id'], "cat_id" => $r['cat_id'], "result_id" => $r['result_id'], "lesson_id" => $r['lesson_id'], "qid" => $r['qid'], "exam_date" => $r['exam_date'], "is_result" => 1, "user_ans" => $r['user_ans'], "iscorrect" => $r['iscorrect']]);
            }

            deleteData($exam_table, array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));
        }

        $data['db_value'] = getDataByWhere("result", "*", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));
        $data['db_value']['lesson_name'] = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $data['db_value']['lesson_id']));
        $data['db_value']['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));

        $has_pt_questions = getTotalRow("lesson_questions", array("cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "pt" => 1, "course_id" => $_SESSION['test_id']));

        $question_test = [];
        $story = [];
        if (in_array($_GET['cat_id'], $data['story_question'])) {

            if ($_GET['filter'] == 'correct') {

                $data['story_list'] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.lesson_id" => $_GET['lesson_id'], "e.cat_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.result_id" => $result_id, "e.iscorrect" => 1), array("e.qid" => "q.qid"), "e.qid", "", "", "q.story_id");
            } else if ($_GET['filter'] == 'wrong') {

                $data['story_list'] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.lesson_id" => $_GET['lesson_id'], "e.cat_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.result_id" => $result_id, "e.iscorrect" => 0), array("e.qid" => "q.qid"), "e.qid", "", "", "q.story_id");
            } else {

                $data['story_list'] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.lesson_id" => $_GET['lesson_id'], "e.cat_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.result_id" => $result_id), array("e.qid" => "q.qid"), "e.qid", "", "", "q.story_id");
            }

            foreach ($data['story_list'] as $a) {
                $story[$a['story_id']] = getFldValueById("story", "story", array("story_id" => $a['story_id']));

                $question_test[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_record_table as e"), "q.*,l.*,e.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.user_id" => $_SESSION['user_id'], "e.result_id" => $result_id, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid", "l.qid" => "e.qid"), "e.id", '', '', "q.qid");
            }
            $data['story'] = $story;
            $data['question_test'] = $question_test;
        } else {

            if ($_GET['filter'] == 'correct') {

                $data['question_test'] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_record_table as e"), "q.*,l.*,e.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.result_id" => $result_id, "e.iscorrect" => 1), array("q.qid" => "l.qid", "l.qid" => "e.qid"), 'l.order_no', '', '', 'l.qid');
            } else if ($_GET['filter'] == 'wrong') {

                $data['question_test'] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_record_table as e"), "q.*,l.*,e.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.result_id" => $result_id, "e.iscorrect" => 0), array("q.qid" => "l.qid", "l.qid" => "e.qid"), 'l.order_no', '', '', 'l.qid');
            } else {

                $data['question_test'] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_record_table as e"), "q.*,l.*,e.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.result_id" => $result_id), array("q.qid" => "l.qid", "l.qid" => "e.qid"), 'l.order_no', '', '', 'l.qid');
            }
        }

        $data['total_correct_ques'] = getTotalRowsByJoin(array("question as q", "lesson_questions as l", "$exam_record_table as e"), array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.result_id" => $result_id, "e.iscorrect" => 1), array("q.qid" => "l.qid", "l.qid" => "e.qid"));

        $data['total_wrong_ques'] = getTotalRowsByJoin(array("question as q", "lesson_questions as l", "$exam_record_table as e"), array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.result_id" => $result_id, "e.iscorrect" => 0), array("q.qid" => "l.qid", "l.qid" => "e.qid"));

        $data['total_all_ques'] = getTotalRowsByJoin(array("question as q", "lesson_questions as l", "$exam_record_table as e"), array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.result_id" => $result_id), array("q.qid" => "l.qid", "l.qid" => "e.qid"));

        break;

    case "view":

        $data['category'] = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_GET['cat_id']));
        $data['date'] = getFldValueById("result", "result_date", array("result_id" => $_GET['result_id']));
        $pred_set_id = (int)getFldValueById("result", "pred_set_id", array("result_id" => $_GET['result_id']));
        $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_GET['cat_id']));
        $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_GET['cat_id']), "", "", "", "id");

        if (($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 39)) { // Full ASVAB Score
            $data['full_asvab_result_list'] = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "pred_name!" => "Full ASVAB", "parent_id" => $_GET['result_id']), "result_id desc");
        }

        if ($_SESSION['test_id'] == 24 && $_GET['cat_id'] == 40) { // Full AFOQT Score
            $data['full_afoqt_result_list'] = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "pred_name!" => "AFOQT Prediction test", "parent_id" => $_GET['result_id']), "result_id");
        }

        $pred_test_report = [];
        $pred_cat_name = [];
        $total_array = [];
        $i = 0;
        foreach ($pred_cat_list as $p) {
            if (!$i && !$_GET['lesson_id']) {
                $_GET['lesson_id'] = $p['cat_id'];
            }
            $i++;
            $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
            $cat_id = $p['cat_id'];
            $cat_name = $cat['category_name'];
            $cat_shortcode = $cat['shortcode'];
            $total_array[$cat['cat_id']] = $p['number_of_questions'];
            $pred_cat_name[] = ["cat_id" => $cat_id, "cat_name" => $cat_name];

            $tmp_score = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "pred_set_id" => $pred_set_id, "score!" => -1, "parent_id" => $_GET['result_id']), "result_id desc", 1);
            foreach ($tmp_score as $t) {
                if ($data['category']['shortcode'] == "ASVAB" || $data['category']['shortcode'] == "AFQT") {
                    $pred_test_report[$cat_shortcode] = $t;
                } else {
                    $pred_test_report[$cat_name] = $t;
                }
            }
        }
        $data['pred_cat_name'] = $pred_cat_name;
        $data['pred_test_report'] = $pred_test_report;
        $total = getFldValueById("result", "total_questions", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "result_id" => $_GET['result_id'], "pred_set_id" => $pred_set_id));
        $total_correct = getFldValueById("result", "correct_answers", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "result_id" => $_GET['result_id'], "pred_set_id" => $pred_set_id));
        $data['pred_score'] = (int)getFldValueById("result", "score", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "result_id" => $_GET['result_id'], "pred_set_id" => $pred_set_id));

        $total_wrong = $total - $total_correct;

        $my_answers = array();
        $answers = array();
        $story = array();
        $questions = [];
        $story_list = [];

        $cat_id = $_GET['lesson_id'];

        $data['cat_name'] = getFldValueByid("category", "category_name", array("cat_id" => $cat_id));

        $data['cat_id'] = $cat_id;

        $data['option_no'] = 5;

        if (in_array($cat_id, $data['story_question'])) {
            $cat_result_id = getFldValueByid("result", "result_id", array("parent_id" => $_GET['result_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id));

            if ($_GET['filter'] == 'correct') {

                $story_list[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.cat_id" => (int)$cat_id, "e.Predic_test_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.pred_set_id" => $pred_set_id, "e.result_id" => $cat_result_id, "e.iscorrect" => 1), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");
            } else if ($_GET['filter'] == 'wrong') {

                $story_list[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.cat_id" => (int)$cat_id, "e.Predic_test_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.pred_set_id" => $pred_set_id, "e.result_id" => $cat_result_id, "e.iscorrect" => 0), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");
            } else {

                $story_list[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.story_id", array("e.cat_id" => (int)$cat_id, "e.Predic_test_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.pred_set_id" => $pred_set_id, "e.result_id" => $cat_result_id), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");
            }

            foreach ($story_list[$cat_id] as $a) {
                $story[$a['story_id']] = getFldValueById("story", "story", array("story_id" => $a['story_id']));

                if ($_GET['filter'] == 'correct') {

                    $data['question_test'][$a['story_id']] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*,e.*", array("e.cat_id" => (int)$cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id, "q.story_id" => $a['story_id'], "e.iscorrect" => 1), array("q.qid" => "e.qid"), 'e.id', '', '', "q.qid");
                } else if ($_GET['filter'] == 'wrong') {

                    $data['question_test'][$a['story_id']] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*,e.*", array("e.cat_id" => (int)$cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id, "q.story_id" => $a['story_id'], "e.iscorrect" => 0), array("q.qid" => "e.qid"), 'e.id', '', '', "q.qid");
                } else {

                    $data['question_test'][$a['story_id']] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*,e.*", array("e.cat_id" => (int)$cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id, "q.story_id" => $a['story_id']), array("q.qid" => "e.qid"), 'e.id', '', '', "q.qid");
                }

                foreach ($data['question_test'][$a['story_id']] as $d) {
                    $user_ans = (int)getFldValueById($exam_record_table, "user_ans", array("cat_id" => (int)$cat_id, "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "pred_set_id" => $pred_set_id, "qid" => $d['qid']));

                    if ($user_ans) {
                        $my_answers[$d['qid']] = $user_ans;
                    }
                }
            }
        } else {
            $cat_result_id = getFldValueByid("result", "result_id", array("parent_id" => $_GET['result_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id));

            if ($_GET['filter'] == 'correct') {

                $questions[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*, e.*", array("e.cat_id" => $cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id, "e.iscorrect" => 1), array("e.qid" => "q.qid"), "e.id");
            } else if ($_GET['filter'] == 'wrong') {

                $questions[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*, e.*", array("e.cat_id" => $cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id, "e.iscorrect" => 0), array("e.qid" => "q.qid"), "e.id");
            } else {

                $questions[$cat_id] = getQueryByJoin(array("question as q", "$exam_record_table as e"), "q.*, e.*", array("e.cat_id" => $cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id), array("e.qid" => "q.qid"), "e.id");
            }

            foreach ($questions[$cat_id] as $d) {
                $my_answers[$d['qid']] = $d['user_ans'];
            }
        }

        $data['story_list'] = $story_list;
        $data['questions'] = $questions;
        $data['story'] = $story;
        $data['my_answers'] = $my_answers;

        $data['total_correct_ques'] = getTotalRowsByJoin(array("question as q", "$exam_record_table as e"), array("e.cat_id" => $cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id, "e.iscorrect" => 1), array("e.qid" => "q.qid"));

        $data['total_wrong_ques'] = getTotalRowsByJoin(array("question as q", "$exam_record_table as e"), array("e.cat_id" => $cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id, "e.iscorrect" => 0), array("e.qid" => "q.qid"));

        $data['total_all_ques'] = getTotalRowsByJoin(array("question as q", "$exam_record_table as e"), array("e.cat_id" => $cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.result_id" => $cat_result_id), array("e.qid" => "q.qid"));

        break;

    case "score-chart":

        $data['lesson_name'] = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $_GET['lesson_id']));

        break;

    case "progress-report":

        $result = [];
        $lesson_list = [];
        $result_total = [];

        if (!$_GET['cat_id']) {
            $_GET['cat_id'] = '2000';
        }

        switch ($_GET['cat_id']) {
            case "2000":

                $_GET['cat_id'] = "2000";

                $cats = getQuery("category", "*", array("course_id" => $_SESSION['test_id']), "category_order");
                $data['category_list'] = $cats;

                foreach ($cats as $cat) {
                    $report[] = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "cat_id" => $cat['cat_id']), "result_id desc", 5);
                }

                $data['pred_cat_list'] = getQuery("predic_test", "*", array("course_id" => $_SESSION['test_id'], "online" => 'Y'), "test_order");

                $data['report'] = $report;

                $data['report_total'] = $report_total;

                $data['cat_name'] = "Overall Report";

                break;

            default:
                // $cat=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id'], "category_name"=>$_GET['cat_name']));
                // $pred_cat=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id'], "test_name"=>$_GET['cat_name']));

                $cat = getDataByWhere("category", "*", array("cat_id" => $_GET['cat_id']));
                $pred_cat = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_GET['cat_id']));

                $data['pred_cat_list'] = getQuery("predic_test", "*", array("course_id" => $_SESSION['test_id'], "online" => 'Y'), "test_order");

                $prediction_ids = array(15, 16, 17, 18, 19, 20);

                if ($pred_cat && (strpos($_GET['cat_name'], 'prediction') == true || in_array($_GET['cat_id'], $prediction_ids))) {
                    $cat = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_GET['cat_id']));
                    $data['cat_name'] = $cat['test_name'];
                    $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_GET['cat_id']), "", "", "", "id");

                    $pred_cats = [];
                    $total = 0;
                    $total_cat = 0;

                    if (($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 39)) {
                        $total_results = getTotalRow("result", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "score!" => -1, "pred_name" => "Full ASVAB"));

                        $result_list = getQuery("result", "*", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "score!" => -1, "pred_name" => "Full ASVAB"), "result_id desc");

                        foreach ($result_list as $result) {
                            $data['result_id'] = $result['result_id'];
                            break;
                        }

                        if (!empty($data['result_id'])) {

                            if ($_GET['lesson_id']) {
                                $result_id = $_GET['lesson_id'];
                            } else {
                                $result_id = $data['result_id'];
                            }

                            $data['result_id'] = $result_id;

                            $data['category'] = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_GET['cat_id']));
                            $data['date'] = getFldValueById("result", "result_date", array("result_id" => $result_id));
                            $pred_set_id = (int)getFldValueById("result", "pred_set_id", array("result_id" => $result_id));
                            $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_GET['cat_id']));
                            $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_GET['cat_id']), "", "", "", "id");

                            $data['full_asvab_result_list'] = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "pred_name!" => "Full ASVAB", "parent_id" => $result_id), "result_id desc");

                            $pred_test_report = [];
                            $pred_cat_name = [];
                            $total_array = [];
                            $i = 0;
                            foreach ($pred_cat_list as $p) {
                                if (!$i && !$_GET['lesson_id']) {
                                    $_GET['lesson_id'] = $p['cat_id'];
                                }
                                $i++;
                                $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                                $cat_id = $p['cat_id'];
                                $cat_name = $cat['category_name'];
                                $total_array[$cat['cat_id']] = $p['number_of_questions'];
                                $pred_cat_name[] = ["cat_id" => $cat_id, "cat_name" => $cat_name];

                                $tmp_score = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "pred_set_id" => $pred_set_id, "score!" => -1, "parent_id" => $result_id), "result_id desc", 1);
                                foreach ($tmp_score as $t) {
                                    $pred_test_report[$cat_name] = $t;
                                }
                            }
                            $data['pred_cat_name'] = $pred_cat_name;
                            $data['pred_test_report'] = $pred_test_report;
                        }
                    } else if ($_SESSION['test_id'] == 24 && $_GET['cat_id'] == 40) {
                        $total_results = getTotalRow("result", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "score!" => -1, "pred_name" => "AFOQT Prediction test"));

                        $result_list = getQuery("result", "*", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "score!" => -1, "pred_name" => "AFOQT Prediction test"), "result_id");

                        foreach ($result_list as $result) {
                            $data['result_id'] = $result['result_id'];
                            break;
                        }

                        if (!empty($data['result_id'])) {

                            if ($_GET['lesson_id']) {
                                $result_id = $_GET['lesson_id'];
                            } else {
                                $result_id = $data['result_id'];
                            }

                            $data['result_id'] = $result_id;

                            $data['category'] = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_GET['cat_id']));
                            $data['date'] = getFldValueById("result", "result_date", array("result_id" => $result_id));
                            $pred_set_id = (int)getFldValueById("result", "pred_set_id", array("result_id" => $result_id));
                            $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_GET['cat_id']));
                            $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_GET['cat_id']), "", "", "", "id");

                            $data['full_asvab_result_list'] = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "pred_name!" => "AFOQT Prediction test", "parent_id" => $result_id), "result_id desc");

                            $pred_test_report = [];
                            $pred_cat_name = [];
                            $total_array = [];
                            $i = 0;
                            foreach ($pred_cat_list as $p) {
                                if (!$i && !$_GET['lesson_id']) {
                                    $_GET['lesson_id'] = $p['cat_id'];
                                }
                                $i++;
                                $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                                $cat_id = $p['cat_id'];
                                $cat_name = $cat['category_name'];
                                $total_array[$cat['cat_id']] = $p['number_of_questions'];
                                $pred_cat_name[] = ["cat_id" => $cat_id, "cat_name" => $cat_name];

                                $tmp_score = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "pred_set_id" => $pred_set_id, "score!" => -1, "parent_id" => $result_id), "result_id desc", 1);
                                foreach ($tmp_score as $t) {
                                    $pred_test_report[$cat_name] = $t;
                                }
                            }
                            $data['pred_cat_name'] = $pred_cat_name;
                            $data['pred_test_report'] = $pred_test_report;
                        }
                    } else {
                        foreach ($pred_cat_list as $p) {
                            $category = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
                            $pred_cats[] = [$category['cat_id'] => $category['cat_name']];
                            $total = $total + $p['number_of_questions'];
                            $total_cat++;
                        }
                        $data['pred_cats'] = $pred_cats;
                        $total_results = getTotalRow("result", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id']));
                    }

                    if ($total_results > 5) {
                        $limit = ($total_results - 5) . ",5";
                    } else {
                        $limit = 5;
                    }

                    if (($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 39)) {
                        $data['result'] = getQuery("result", "*", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "score!" => -1, "pred_name" => "Full ASVAB"), "result_id", $limit);
                    } else if ($_SESSION['test_id'] == 24 && $_GET['cat_id'] == 40) {
                        $data['result'] = getQuery("result", "*", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "score!" => -1, "pred_name" => "AFOQT Prediction test"), "result_id", $limit);
                    } else {
                        $data['result'] = getQuery("result", "*", array("cat_id" => $_GET['cat_id'], "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "score!" => -1), "result_id", $limit);
                    }

                    $data['pred_result_total'] = $total_results;
                } else if ($cat) {

                    $data['lesson_list'] = getQuery("lesson", "*", array("course_id" => $_SESSION['test_id'], "cat_id" => $_GET['cat_id'], "online" => "Y"), "lesson_order");
                    $data['lesson_total'] = getTotalRow("lesson", array("course_id" => $_SESSION['test_id'], "cat_id" => $_GET['cat_id'], "online" => "Y"));

                    if ($data['lesson_total']) {
                        foreach ($data['lesson_list'] as $l) {
                            $result[$l['lesson_id']] = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => $l['lesson_id'], "score!" => -1), "result_id desc", 5);
                            $result_total[$l['lesson_id']] = getTotalRow("result", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => $l['lesson_id'], "score!" => -1));
                        }
                    }

                    $data['result'] = $result;

                    $data['result_total'] = $result_total;

                    $data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));
                }
        }

        break;
    case "flashcards":

        $flashcard_cond = array("l.cat_id" => $_GET['flashcard_id'], "l.course_id" => $_SESSION['test_id']);

        if ($_GET['flashcard_cat_id']) {
            $flashcard_cond["l.flashcard_cat_id"] = (int)$_GET['flashcard_cat_id'];
            $data['flashcard_cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['flashcard_cat_id']));

            $data['total'] = getTotalRowsByJoin(array("question as q", "lesson_questions as l"), $flashcard_cond, array("q.qid" => "l.qid"));
            if ($data['total']) {
                // Also pull each row's own flashcard_cat_id - a question can
                // still be re-tagged after the fact, so the category shown
                // on a card is looked up per question, not assumed from the
                // filter above.
                $data['questions'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*, l.flashcard_cat_id", $flashcard_cond, array("q.qid" => "l.qid"), "rand()");
            }
        } else {
            // No sub-category picked yet (the navbar/homepage "Flashcards"
            // link goes straight here with no sub-category). Offer a picker
            // of the sub-categories that actually have flashcards - same
            // list the left sidebar builds (tmpl_left_exam.php) - instead of
            // silently dumping every sub-category's questions into one deck.
            $data['flashcard_categories'] = array();
            foreach ($data['cat_list'] as $c) {
                if ($c['category_name'] == "Flashcards" || $c['category_name'] == "Final Practice Test") {
                    continue;
                }

                $fc_total = getTotalRowsByJoin(
                    array("question as q", "lesson_questions as l"),
                    array_merge($flashcard_cond, array("l.flashcard_cat_id" => $c['cat_id'])),
                    array("q.qid" => "l.qid")
                );
                if ($fc_total) {
                    $data['flashcard_categories'][] = array(
                        "cat_id" => $c['cat_id'],
                        "category_name" => $c['category_name'],
                        "total" => $fc_total,
                    );
                }
            }

            if (!$data['flashcard_categories']) {
                // Nothing tagged with a sub-category at all (flat legacy
                // deck) - fall back to the old behaviour of showing every
                // question together, since there's nothing to pick from.
                $data['total'] = getTotalRowsByJoin(array("question as q", "lesson_questions as l"), $flashcard_cond, array("q.qid" => "l.qid"));
                if ($data['total']) {
                    $data['questions'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*, l.flashcard_cat_id", $flashcard_cond, array("q.qid" => "l.qid"), "rand()");
                }
            }
        }

        break;

    case "prediction-result":

        $total_questions = 0;
        $total_correct_ans = 0;
        $total_cat_score = 0;
        $num_category = (int)getTotalRow("prediction_test_category", array("pred_test_id" => $_GET['cat_id']));
        $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_GET['cat_id']), "", "", "", "id");

        if ($_SESSION['test_id'] == 27 || $_SESSION['test_id'] == 40 || $_SESSION['test_id'] == 24) {
            $cat_scores = array();
        }

        foreach ($pred_cat_list as $p) {
            $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));
            $cat_id = $p['cat_id'];
            $cat_score = 0;
            $num_correct_ans = (int)getTotalRow($exam_table, array("iscorrect" => 1, "user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "is_result" => 0));

            if ($num_correct_ans) {
                if ($_SESSION['test_id'] == 24) { // AFOQT Score Calculation

                    $category_score_per = round(($num_correct_ans / $p['number_of_questions']) * 100);
                    $cat_score = $data["oc_afoqt_pred_score_array"][$category_score_per];

                    $cat_scores[$cat['shortcode']]['correct'] = $num_correct_ans;
                    $cat_scores[$cat['shortcode']]['total_question'] = $p['number_of_questions'];
                } else if (in_array($_SESSION['test_id'], [21, 25, 26])) { // SIFT, ASTB and OAR Score Calculation

                    $category_score_per = round(($num_correct_ans / $p['number_of_questions']) * 100);

                    if ($category_score_per < 25) {
                        $cat_score = 20;
                    } elseif ($category_score_per > 100) {
                        $cat_score = 80;
                    } else {
                        $cat_score = $data["oc_pred_score_array"][$category_score_per];
                    }
                }
                // else if (($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 39)) { 
                else if ($_SESSION['test_id'] == 27 || $_SESSION['test_id'] == 40) {
                    $correct_ans_percentage = round(($num_correct_ans / $p['number_of_questions']) * 100);
                    $cat_score = round(20 + $correct_ans_percentage * 0.55);
                    $cat_scores[$cat['shortcode']] = $cat_score;
                } else {
                    $correct_ans_percentage = round(($num_correct_ans / $p['number_of_questions']) * 100);
                    $cat_score = round(20 + $correct_ans_percentage * 0.55);
                }
            }
            $total_cat_score += $cat_score;
            $num_wrong_ans = $p['number_of_questions'] - $num_correct_ans;
            $total_questions += $p['number_of_questions'];
            $total_correct_ans += $num_correct_ans;

            $pred_set_id = (int)getFldValueById("result", "pred_set_id", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "score" => -1));

            $result_data = getQuery("result", "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "score" => -1), "result_id desc", 1);

            foreach ($result_data as $r) {
                $result_id = $r['result_id'];
            }

            // $result_id = getFldValueById("result", "result_id", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "score" => -1));

            updateTable("result", ["score" => $cat_score, "correct_answers" => $num_correct_ans], ['user_id' => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "result_id" => $result_id]);
        }
        $data['pred_cat_list'] = $pred_cat_list;

        if ($_SESSION['test_id'] == 24) {

            //Full AFOQT Score Calculation
            if ($_SESSION['test_id'] == 24 && $_GET['cat_id'] == 40) {
                $full_afoqt_scores = array();

                // Composite: Pilot (MK + TR + IC + AI)
                $pilot_categories = ['MK', 'TR', 'IC', 'AI'];
                $full_afoqt_scores['Pilot'] = calculate_composite_score($pilot_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                // Composite: CSO (WK + MK + TR + BC)
                $cso_categories = ['WK', 'MK', 'TR', 'BC'];
                $full_afoqt_scores['CSO'] = calculate_composite_score($cso_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                // Composite: ABM (VA + MK + TR + IC + BC + AI)
                $abm_categories = ['VA', 'MK', 'TR', 'IC', 'BC', 'AI'];
                $full_afoqt_scores['ABM'] = calculate_composite_score($abm_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                // Composite: Academic Aptitude (VA + AR + WK + MK + RC)
                $academic_categories = ['VA', 'AR', 'WK', 'MK', 'RC'];
                $full_afoqt_scores['Academic Aptitude'] = calculate_composite_score($academic_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                // Composite: Verbal (VA + WK + RC)
                $verbal_categories = ['VA', 'WK', 'RC'];
                $full_afoqt_scores['Verbal'] = calculate_composite_score($verbal_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                // Composite: Quantitative (AR + MK)
                $quant_categories = ['AR', 'MK'];
                $full_afoqt_scores['Quantitative'] = calculate_composite_score($quant_categories, $cat_scores, $data["oc_afoqt_pred_score_array"]);

                // Full AFOQT score
                $pre_score = round(($total_correct_ans / $total_questions) * 100);
                $full_afoqt_scores['AFOQT'] = $data["oc_afoqt_pred_score_array"][$pre_score];
            } else {
                $pre_score = round(($total_correct_ans / $total_questions) * 100);
                $final_pred_score = $data["oc_afoqt_pred_score_array"][$pre_score];
            }
        } else if (in_array($_SESSION['test_id'], [21, 25, 26])) {
            $pre_score = round(($total_correct_ans / $total_questions) * 100);
            $final_pred_score = $data["oc_pred_score_array"][$pre_score];
        }
        //Full ASVAB Score Calculation
        else if (($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 39)) {
            $full_asvab_scores = array();

            // VE Score
            $ve_score = round(($cat_scores['PC'] + $cat_scores['WK']) / 2);
            $full_asvab_scores['VE'] = $ve_score;

            // AFQT Score
            $raw_score = $ve_score + $cat_scores['AR'] + $cat_scores['MK'];
            if ($raw_score > 215) {
                $raw_score = 215;
            }
            $afqt_score = $data['full_asvab_pred_score_array'][$raw_score];
            $full_asvab_scores['AFQT'] = $afqt_score;

            // Army GT Score
            $army_gt_score = round($ve_score + $cat_scores['AR'] + rand(0, 4));
            if ($army_gt_score > 140) {
                $army_gt_score = 140;
            }
            $full_asvab_scores['Army GT'] = $army_gt_score;

            // Navy GT Score
            $navy_gt_score = round($ve_score + $cat_scores['AR']);
            if ($navy_gt_score > 140) {
                $navy_gt_score = 140;
            }
            $full_asvab_scores['Navy GT'] = $navy_gt_score;

            // Marine GT Score
            $marine_gt_score = round($ve_score + $cat_scores['AR'] + ($cat_scores['MC'] - $cat_scores['AR']));
            if ($marine_gt_score > 140) {
                $marine_gt_score = 140;
            }
            $full_asvab_scores['Marine GT'] = $marine_gt_score;
        } else if ($_SESSION['test_id'] == 28 || ($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 37) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 38)) { //AFQT App and web score
            // VE Score
            $ve_score = round(($cat_scores['PC'] + $cat_scores['WK']) / 2);

            // AFQT Score
            $raw_score = $ve_score + $cat_scores['AR'] + $cat_scores['MK'];
            if ($raw_score > 215) {
                $raw_score = 215;
            }
            $afqt_score = $data['full_asvab_pred_score_array'][$raw_score];
            $final_pred_score = $afqt_score;
        } else {
            $final_pred_score = round($total_cat_score / $num_category);
        }


        //Full ASVAB Score Result
        if (($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 39)) {
            $total_pred_score = 0;

            $reslt_id =  insertTable("result", array("pred_name" => "Full ASVAB", "score" => $full_asvab_scores['AFQT'], "user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "total_questions" => $total_questions, "correct_answers" => $total_correct_ans, "result_date" => date("Y-m-d")));

            foreach ($full_asvab_scores as $key => $value) {
                $total_pred_score += $value;

                insertTable("result", array("pred_name" => $key, "user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "score" => $value, "parent_id" => $reslt_id, "result_date" => date("Y-m-d")));
            }
        }
        // Full AFOQT Score Result
        else if ($_SESSION['test_id'] == 24 && $_GET['cat_id'] == 40) {
            $total_pred_score = 0;

            $reslt_id =  insertTable("result", array("pred_name" => "AFOQT Prediction test", "score" => $full_afoqt_scores['AFOQT'], "user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "total_questions" => $total_questions, "correct_answers" => $total_correct_ans, "result_date" => date("Y-m-d")));

            foreach ($full_afoqt_scores as $key => $value) {

                $total_pred_score += $value;

                insertTable("result", array("pred_name" => $key, "user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "score" => $value, "parent_id" => $reslt_id, "result_date" => date("Y-m-d")));
            }
        } else {
            $reslt_id =  insertTable("result", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $_GET['cat_id'], "score" => $final_pred_score, "result_date" => date("Y-m-d"), "pred_set_id" => $pred_set_id, "total_questions" => $total_questions, "correct_answers" => $total_correct_ans));
        }

        $result_ids = getQuery($exam_table, "DISTINCT(result_id)", array("is_result" => 0, 'user_id' => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "pred_set_id" => $pred_set_id));

        foreach ($result_ids as $id) {
            updateTable("result", array('parent_id' => $reslt_id), array('user_id' => $_SESSION['user_id'], "result_id" => $id['result_id']));
        }

        $exam_record_list = getQuery($exam_table, "*", array('is_result' => 0, 'user_id' => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "pred_set_id" => $pred_set_id));

        foreach ($exam_record_list as $r) {
            insertTable($exam_record_table, array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $r['Predic_test_id'], "cat_id" => $r['cat_id'], "result_id" => $r['result_id'], "qid" => $r['qid'], "user_ans" => $r['user_ans'], "iscorrect" => $r['iscorrect'], "exam_date" => $r['exam_date'], "is_result" => 1, "pred_set_id" => $r['pred_set_id']));
        }

        deleteData($exam_table, array('is_result' => 0, 'user_id' => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "pred_set_id" => $pred_set_id));

        header("location:" . $configs['vpath'] . "exam/view/" . $_GET['cat_id'] . "-" . seo_url($_GET['cat_name']) . "/0/" . $reslt_id);
        exit;
        break;

    case "prediction-test":

        if ($_GET['action'] == "freshtest") {
            $result_id = (int)getFldValueById($exam_table, "result_id", array("Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "is_result" => 0));
            deleteData($exam_table, array("Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "is_result" => 0));
            deleteData("result", array("Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "result_id" => $result_id));
        }

        $question_test = [];
        $question_list = [];
        $prediction_cat = [];
        $story = array();
        $total = 0;
        $i = 0;
        $completed_total = 0;
        $flag = 1;

        $pred_cat_list = getQuery("prediction_test_category", "*", array("pred_test_id" => $_GET['cat_id']), "", "", "", "id");

        foreach ($pred_cat_list as $p) {
            $total += $p['number_of_questions'];
            $cat = getDataByWhere("category", "*", array("cat_id" => $p['cat_id']));

            if (!$i && !$_GET['child_cat_id']) {
                $_GET['child_cat_id'] = $cat['cat_id'];
                $pred_qb_id = $p["pred_qb_id"];

                $flag = 0;
            } else if ($cat['cat_id'] == $_GET['child_cat_id']) {
                $pred_qb_id = $p["pred_qb_id"];
            }

            $completed = (int)getTotalRow($exam_table, ['Predic_test_id' => $_GET['cat_id'], 'user_id' => $_SESSION['user_id'], 'cat_id' => $cat['cat_id'], 'user_ans>' => 0, 'is_result' => 0]);

            if ($completed > $p['number_of_questions']) {
                $completed = $p['number_of_questions'];
            }
            $completed_total += $completed;

            $prediction_cat[$cat['cat_id']] = ["cat_name" => $cat['category_name'], "pred_qb_id" => $cat["pred_qb_id"], "shortcode" => $cat["shortcode"], "cat_id" => $cat['cat_id'], "total" => $p['number_of_questions'], "completed" => $completed];

            $i++;
        }

        // $data['testing_var'] = $pred_qb_id;

        $data['exam_duration'] = (int)getFldValueById("prediction_test_category", "duration", array("pred_test_id" => $_GET['cat_id'], "cat_id" => $_GET['child_cat_id']));

        $data['completed_total'] = $completed_total;
        $data['total'] = $total;
        $data['prediction_cat'] = $prediction_cat;

        $data['alert'] = "";
        if ($completed_total == $total) {
            //                $cat_name = getFldValueById("predic_test", "test_name", array("Predic_test_id"=>$_GET['cat_id'], "course_id"=>$_SESSION['test_id']));

            $data['alert_total'] = "<a  class=\"btn btn-success text-center\" href=\"" . $configs['vpath'] . "exam/prediction-result/" . $_GET['cat_id'] . "-" . seo_url($_GET['cat_name']) . "\">Submit</a>";
        } else {
            $data['alert_total'] = "<a  class=\"btn btn-success disabled text-center\" href=\"" . $configs['vpath'] . "exam/prediction-result/" . $_GET['cat_id'] . "-" . seo_url($_GET['cat_name']) . "\">Submit</a>";
        }

        if ($completed_total && $completed_total < $total && !$flag && $_GET['action'] != 'continue') {
            $data['alert'] = "<center><h4>You already answered " . $data["completed_total"] . " question(s) from this Prediction Test. Still pending " . ($total - $completed_total) . " questions.</h4><br><a href='" . $configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" . $_GET['cat_name'] . "/continue' class='btn btn-success'>Continue ?</a> or <a href='" . $configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" . $_GET['cat_name'] . "/freshtest' class='btn btn-warning'>Start Over?</a></center>";
        } else {
            $data['db_child_category'] = getDataByWhere("category", "*", ["cat_id" => $_GET['child_cat_id']]);
            $cat_id = $_GET['child_cat_id'];
            $limit = $prediction_cat[$_GET['child_cat_id']]["total"];
            $lessons_text = [];
            $set_id = 0;
            if ($cat_id == -87 || $cat_id == -80) {
                $data['option_no'] = 4;
            } else {
                $data['option_no'] = 5;
            }

            if (in_array($cat_id, $data['story_question'])) {
                if (in_array($cat_id, array(84, 63, 65))) {
                    $limit = (int)$limit / 5;
                } else {
                    $limit = (int)$limit / 4;
                }

                $story_cat_limit = 5;
                $tot = 0;
                $story_list_array = [];
                $story_list = [];

                $exist_prev_question = getTotalRow($exam_table, array("cat_id" => $cat_id, "user_id" => $_SESSION['user_id'], "Predic_test_id" => (int)$_GET['cat_id'], "is_result" => 0));

                if ((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed'] > 0) || $exist_prev_question) {
                    $story_list_array = getQueryByJoin(array("question as q", "$exam_table as e"), "q.story_id", array("e.cat_id" => (int)$cat_id, "e.Predic_test_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.is_result" => 0), array("e.qid" => "q.qid"), "e.id", "", "", "q.story_id");
                } else {
                    $attempted_set_rows = getQuery($exam_record_table, "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "is_result" => 1), "", "", "", "pred_set_id");
                    $attempted_set_list = array();
                    foreach ($attempted_set_rows as $r) {
                        $attempted_set_list[] = $r['pred_set_id'];
                    }

                    $set_rows = getQuery("predic_questions", "*", array("course_id" => $_SESSION['test_id'], "predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id), "", "", "", "set_id");
                    $set_list = array();
                    foreach ($set_rows as $r) {
                        if (!in_array($r['set_id'], $attempted_set_list)) {
                            $set_list[] = $r['set_id'];
                        }
                    }
                    if (empty($set_list)) {
                        $set_id = $attempted_set_list[array_rand($attempted_set_list)];
                    } else {
                        $set_id = $set_list[array_rand($set_list)];
                    }

                    if ($set_id) {
                        $story_list_array = getQueryByJoin(array("question as q", "predic_questions as p"), "q.story_id", array("p.cat_id" => (int)$cat_id, "p.set_id" => $set_id, "q.story_id >" => 0, "p.predic_test_id" => $_GET['cat_id'], "p.course_id" => $_SESSION['test_id']), array("q.qid" => "p.qid"), "", "", "", "q.story_id");
                    } else {
                        $story_total = (int)$limit;

                        $shrotcode = $_GET['shortcode'];

                        // if ($shrotcode == "TR" || $shrotcode == "BC") {
                        //     $pt_hw = array("pt", "HW");
                        //     $random_value = $pt_hw[array_rand($pt_hw)];

                        //     $story_list_array = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.cat_id" => (int)$cat_id, "l.course_id" => $_SESSION['test_id'], "l.$random_value" => 1), array("q.qid" => "l.qid"), "l.order_no", "", "", "q.story_id");
                        // } else {
                        //     $story_list_array = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.cat_id" => (int)$cat_id, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), "", "", "", "q.story_id");
                        // }

                        $story_list_array = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.pred_id" => $pred_qb_id), array("q.qid" => "l.qid"), "", "", "", "q.story_id");
                    }
                }

                $data['story_list'][$cat_id] = [];

                $result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $cat_id, "user_id" => $_SESSION['user_id'], "Predic_test_id" => (int)$_GET['cat_id'], "is_result" => 0));

                $result_exist = getFldValueById("result", "result_id", array("user_id" => $_SESSION['user_id'], "cat_id" => $cat_id, "Predic_test_id" => (int)$_GET['cat_id'], "result_id" => $result_id));

                //                    echo "result: " . $result_exist;

                if (!$set_id) {
                    $set_id = 0;
                }

                if (!$result_exist) {
                    $result_id = insertTable("result", ["user_id" => $_SESSION['user_id'], "cat_id" => $cat_id, "Predic_test_id" => (int)$_GET['cat_id'], "total_questions" => $prediction_cat[$cat_id]['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1, "pred_set_id" => $set_id]);
                }

                $total_story_ques = $prediction_cat[$_GET['child_cat_id']]["total"];

                foreach ($story_list_array as $a) {

                    if ($total_story_ques <= 0) {
                        break;
                    }

                    $data['story_list'][$cat_id][] = $a;

                    $story[$a['story_id']] = getFldValueById("story", "story", array("story_id" => $a['story_id']));

                    if ($set_id) {
                        $question_list[$a['story_id']] = getQueryByJoin(array("question as q", "predic_questions as p"), "q.*,p.*", array("p.cat_id" => (int)$cat_id, "p.predic_test_id" => $_GET['cat_id'], "p.course_id" => $_SESSION['test_id'], "q.story_id" => $a['story_id']), array("q.qid" => "p.qid"), 'q.qid', '', '', "q.qid");
                    } else {
                        //                            echo " story id: ". $a['story_id'];
                        // $question_list[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.cat_id" => (int)$cat_id, "l.course_id" => $_SESSION['test_id'], "q.story_id" => $a['story_id']), array("q.qid" => "l.qid"), "q.qid", $total_story_ques, '', "l.qid");

                        $question_list[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.pred_id" => $pred_qb_id, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid"), "q.qid", $total_story_ques, '', "l.qid");

                        $n_question = 0;
                        foreach ($question_list[$a['story_id']] as $questions) {
                            $n_question += 1;
                        }

                        $total_story_ques -= $n_question;

                        // echo $n_question . " question(s) found for story id: " . $a['story_id'] . "<br>";
                    }

                    foreach ($question_list[$a['story_id']] as $d) {
                        if (!$prediction_cat[$cat_id]['completed'] && !$exist_prev_question) {
                            insertTable($exam_table, ["user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0, "pred_set_id" => $set_id]);
                        }

                        if ((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed'] > 0) || $exist_prev_question) {
                            $user_ans = (int)getFldValueById($exam_table, "user_ans", array("cat_id" => (int)$cat_id, "Predic_test_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "is_result" => 0, "qid" => $d['qid']));

                            if ($user_ans) {
                                $user_answers[$d['qid']] = $user_ans;
                            }
                        }
                    }
                }
                $data['user_answers'] = $user_answers;
                $data['story'] = $story;
                $data['question_list'] = $question_list;
            } else {
                $exist_prev_question = getTotalRow($exam_table, array("cat_id" => $cat_id, "user_id" => $_SESSION['user_id'], "Predic_test_id" => (int)$_GET['cat_id'], "is_result" => 0));

                if ((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed'] > 0) || $exist_prev_question) {

                    $question_test[$cat_id] = getQueryByJoin(array("question as q", "$exam_table as e"), "q.*, e.*", array("e.cat_id" => $cat_id, "e.user_id" => $_SESSION['user_id'], "e.Predic_test_id" => $_GET['cat_id'], "e.is_result" => 0), array("e.qid" => "q.qid"), "e.id", $limit);
                } else {

                    $attempted_set_rows = getQuery($exam_record_table, "*", array("user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "is_result" => 1), "", "", "", "pred_set_id");

                    $attempted_set_list = array();

                    foreach ($attempted_set_rows as $r) {
                        $attempted_set_list[] = $r['pred_set_id'];
                    }

                    $set_rows = getQuery("predic_questions", "*", array("course_id" => $_SESSION['test_id'], "predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id), "", "", "", "set_id");

                    $set_list = array();
                    foreach ($set_rows as $r) {
                        if (!in_array($r['set_id'], $attempted_set_list)) {
                            $set_list[] = $r['set_id'];
                        }
                    }
                    if (empty($set_list)) {
                        $set_id = $attempted_set_list[array_rand($attempted_set_list)];
                    } else {
                        $set_id = $set_list[array_rand($set_list)];
                    }

                    if ($set_id) {
                        $question_test[$cat_id] = getQueryByJoin(array("predic_questions as p", "question as q"), "q.*,p.*", array("p.set_id" => $set_id, "p.course_id" => $_SESSION['test_id'], "p.predic_test_id" => $_GET['cat_id'], "p.cat_id" => $cat_id), array("p.qid" => "q.qid"), "rand()", $limit);
                    } else {
                        // $question_test[$cat_id] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.cat_id" => (int)$cat_id, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), "rand()", $limit, "", "q.qid");

                        $question_test[$cat_id] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.pred_id" => (int)$pred_qb_id), array("q.qid" => "l.qid"), "rand()", $limit, "", "q.qid");
                    }
                }
            }
            if (!in_array($cat_id, $data['story_question'])) {
                if (!$set_id) {
                    $set_id = 0;
                }

                $result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $cat_id, "user_id" => $_SESSION['user_id'], "Predic_test_id" => (int)$_GET['cat_id'], "is_result" => 0));

                $result_exist = getFldValueById("result", "result_id", array("user_id" => $_SESSION['user_id'], "cat_id" => $cat_id, "Predic_test_id" => (int)$_GET['cat_id'], "result_id" => $result_id));

                if (!$result_exist) {
                    $result_id = insertTable("result", ["user_id" => $_SESSION['user_id'], "cat_id" => $cat_id, "Predic_test_id" => (int)$_GET['cat_id'], "total_questions" => $prediction_cat[$cat_id]['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1, "pred_set_id" => $set_id]);
                }

                $exist_prev_ques = getTotalRow($exam_table, array("cat_id" => $cat_id, "user_id" => $_SESSION['user_id'], "Predic_test_id" => (int)$_GET['cat_id'], "is_result" => 0));

                foreach ($question_test[$cat_id] as $d) {
                    if (!$prediction_cat[$cat_id]['completed'] && !$exist_prev_ques) {
                        //                            echo "insert code: ".$prediction_cat[$cat_id]['completed'];

                        insertTable($exam_table, ["user_id" => $_SESSION['user_id'], "Predic_test_id" => $_GET['cat_id'], "cat_id" => $cat_id, "result_id" => $result_id, "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0, "pred_set_id" => $set_id]);
                    }
                }
                $data['question_test'] = $question_test;
            }
        }

        break;
}

echo template($data);
