<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

require '../../dompdf/vendor/autoload.php';
require_once('../../jpgraph/src/jpgraph.php');
require_once('../../jpgraph/src/jpgraph_bar.php');

use Dompdf\Dompdf;

if (!$_GET['pred_id']) {
    $_GET['pred_id'] = $_POST['pred_id'];
}

$data['pred_id'] = $_GET['pred_id'];

if ($_POST) {
    $_REQUEST = $_POST;
}

$data['categories'] = getQueryByJoin(array("question as q", "lesson_questions as l"), " DISTINCT q.cat_id", array("l.pred_id" => (int)$_GET['pred_id']), array("q.qid" => "l.qid"), "q.cat_id");

$data['subject_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), " DISTINCT q.subject", array("l.pred_id" => (int)$_GET['pred_id']), array("q.qid" => "l.qid"), "q.subject");

$data['pred_name'] = getFldValueById("pred_test_category", "cat_name", array("pred_id" => $_GET['pred_id']));
$data['pred_code'] = getFldValueById("pred_test_category", "cat_code", array("pred_id" => $_GET['pred_id']));
$data['pred_desciption'] = getFldValueById("pred_test_category", "description", array("pred_id" => $_GET['pred_id']));

$data['option_no'] = 5;

if (!$_GET['pred_id']) {
    $data['page_error'] = "There is no prediciton category's question to view.";
} else {

    if ($_GET['del_id']) {
        $r = deleteData("lesson_questions", array("id" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
        //    header("location: questions_list?lesson_id=".$_GET['lesson_id']."&cat_id=".$_GET['cat_id']."&course_id=".$_GET['course_id']);
    }

    if (isset($_POST['remove_hw'])) {
        $selectedQuestionIds = $_POST['ids']; // Assuming the checkboxes have name='ids[]'

        foreach ($selectedQuestionIds as $questionId) {
            $r = deleteData("lesson_questions", array("id" => (int)$questionId));
        }

        if ($r) {
            $data['msg'] = $configs['delete_msg'];
        } else {
            $data['msg'] = $configs['error_msg'];
        }
    }

    if (isset($_POST['download_hw_ques'])) {

        $selectedQuestionIds = $_POST['ids'] ?? []; // 'ids[]' from checkbox inputs

        if (empty($selectedQuestionIds)) {
            // Only run this query when no IDs are selected
            $id_list = getQueryByJoin(array("question as q", "lesson_questions as l"), "l.id",  array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']), ["q.qid" => "l.qid"], "l.order_no, l.id");

            foreach ($id_list as $i) {
                $selectedQuestionIds[] = $i['id'];
            }
        }

        if (!empty($selectedQuestionIds)) {
            $content = "";
            $qno = 1;
            $alphabet = ['A', 'B', 'C', 'D', 'E'];

            $withImg2 = isset($_POST['with_img_hw']);

            // Function to decode and clean HTML content for plain text
            $cleanText = function ($text) use ($withImg2) {
                $text = str_replace("&#92;", "\\", $text); // handle escaped backslashes
                $text = html_entity_decode(html_entity_decode(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8')); // decode HTML entities

                if ($withImg2) {
                    $text = preg_replace_callback('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', function ($matches) {
                        return "Image: " . $matches[1]; // formatted image URL
                    }, $text);
                }

                $text = strip_tags($text); // remove all other HTML tags
                $text = str_replace(['\\(', '\\)'], '', $text); // remove \( and \)
                $text = trim($text); // trim spaces/newlines
                return $text;
            };


            $qno = 1;
            $content = "";

            foreach ($selectedQuestionIds as $id) {

                $lesson_question = getDataByWhere("lesson_questions", "*", array("id" => $id));
                $question = getDataByWhere("question", "*", ["qid" => $lesson_question['qid']]);

                $withId = isset($_POST['with_id_hw']); // true if checkbox was selected

                $content .= "Question {$qno}:\n";
                if ($withId) {
                    // Generate questions WITH ID
                    $content .= "ID: {$lesson_question['qid']}\n";
                }
                $content .= $cleanText($question['question']) . "\n\n";

                $content .= "A. " . $cleanText($question['option_a']) . "\n";
                $content .= "B. " . $cleanText($question['option_b']) . "\n";
                $content .= "C. " . $cleanText($question['option_c']) . "\n";
                $content .= "D. " . $cleanText($question['option_d']) . "\n\n";

                $content .= "The answer is " . $alphabet[$question['answer'] - 1] . ".\n\n";

                if ($question['explanation']) {
                    $content .= "Explanation:\n" . $cleanText($question['explanation']) . "\n\n";
                } else {
                    $content .= "Explanation:\n\n";
                }

                $qno++;
            }
        } else {
            $content = "Empty questions selected. Please select question(s) to download.\n";
        }

        // Prepare file name
        $file_name = $data['course_name'] . "-" . $data['cat_name'] . "-" . $data['lesson_name'] . "-hw.txt";

        // Set headers to download content as a .txt file
        header('Content-Type: text/plain');
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header('Content-Length: ' . strlen($content));

        // Output the content and stop script execution
        echo $content;
        exit;
    }

    $conditions = array("q.is_disable" => 0, "l.pred_id" => (int)$_GET['pred_id']);

    // Add POST parameters to conditions if they have values
    $filterParams = ['cat_id_drop', 'question_tag', 'search_id', 'subject', 'chapter', 'search', 'topic'];
    foreach ($filterParams as $param) {
        if (!empty($_POST[$param])) {
            if ($param == 'search') {
                $conditions["q.question like "] = "'%" . $_POST[$param] . "%'";
            } else {
                if ($param == 'cat_id_drop') {
                    $param_index = 'cat_id';
                } else if ($param == 'search_id') {
                    $param_index = 'qid';
                } else {
                    $param_index = $param;
                }
                $conditions["q.$param_index"] = $_POST[$param];
            }
        }
    }

    // Determine category type

    $data['db_list'] = getQueryByJoin(
        ["question as q", "lesson_questions as l"],
        "q.*, l.order_no, l.pred_id, l.id",
        $conditions,
        ["q.qid" => "l.qid"],
        "l.order_no"
    );

    $data['total'] = getTotalRowsByJoin(
        ["question as q", "lesson_questions as l"],
        $conditions,
        ["q.qid" => "l.qid"]
    );
}

$data['breadcrumb'] = array(
    'Prediction Category List' => "prediction-test-list",
    ' Prediction Question List' => ""
);

echo template($data);
