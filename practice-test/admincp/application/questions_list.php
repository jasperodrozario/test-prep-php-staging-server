<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

require '../../dompdf/vendor/autoload.php';
require_once('../../jpgraph/src/jpgraph.php');
require_once('../../jpgraph/src/jpgraph_bar.php');

use Dompdf\Dompdf;

if (!$_GET['lesson_id']) {
    $_GET['lesson_id'] = $_POST['lesson_id'];
}
if (!$_GET['cat_id']) {
    $_GET['cat_id'] = $_POST['cat_id'];
}
if (!$_GET['course_id']) {
    $_GET['course_id'] = $_POST['course_id'];
}

$data['lesson_id'] = $_GET['lesson_id'];
$data['cat_id'] = $_GET['cat_id'];
$data['course_id'] = $_GET['course_id'];

if ($_POST) {
    $_REQUEST = $_POST;
}

$data['categories'] = getQueryByJoin(array("question as q", "lesson_questions as l"), " DISTINCT q.cat_id", array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']), array("q.qid" => "l.qid"), "q.cat_id");

$data['subject_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), " DISTINCT q.subject", array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']), array("q.qid" => "l.qid"), "q.subject");

if (isset($_POST['move_lesson_btn'])) {
    if ($_POST['move_option'] == "HW") {
        $hw_pt = "HW";
        $zero = "pt";
    } else {
        $hw_pt = "pt";
        $zero = "HW";
    }

    $selectedQuestionIds = $_POST['ids'];
    foreach ($selectedQuestionIds as $questionId) {
        $r = updateTable("lesson_questions", array($hw_pt => 1, $zero => 0, "lesson_id" => $_POST['move_lesson']), array("id" => $questionId));
    }

    if ($r) {
        $data['msg'] = $configs['success_msg'];
    } else {
        $data['msg'] = $configs['error_msg'];
    }
}

$data["lesson_list"] = getQuery("lesson", "*", array("cat_id" => $_GET["cat_id"], "course_id" => $_GET["course_id"]), "lesson_id");
$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_GET['course_id']));
$data['lesson_name'] = getFldValueById("lesson", "Lesson_name", array("lesson_id" => $_GET['lesson_id'], "cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));
$data['option_no'] = 5;

if (!$data['lesson_name'] &&  $_GET['lesson_id']) {
    $data['page_error'] = "There is no Lesson to view.";
} else {

    if ($_GET['del_id']) {
        $r = deleteData("lesson_questions", array("id" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
        //    header("location: questions_list?lesson_id=".$_GET['lesson_id']."&cat_id=".$_GET['cat_id']."&course_id=".$_GET['course_id']);
    }

    if ($_GET['function'] == "move_pt" && $_GET['id']) {

        $r = updateTable("lesson_questions", array("HW" => 0, "pt" => 1), array("id" => $_GET['id']));

        if ($r) {
            $data['msg'] = $configs['success_msg'];
        } else {
            $data['msg'] = $configs['error_msg'];
        }
    }

    if ($_GET['function'] == "move_hw" && $_GET['id']) {

        $r = updateTable("lesson_questions", array("HW" => 1, "pt" => 0), array("id" => $_GET['id']));

        if ($r) {
            $data['msg'] = $configs['success_msg'];
        } else {
            $data['msg'] = $configs['error_msg'];
        }
    }

    if (isset($_POST['move_to_pt'])) {
        $selectedQuestionIds = $_POST['ids']; // Assuming the checkboxes have name='ids[]'

        foreach ($selectedQuestionIds as $questionId) {
            $r = updateTable("lesson_questions", array("HW" => 0, "pt" => 1), array("id" => $questionId));
        }

        if ($r) {
            $data['msg'] = $configs['success_msg'];
        } else {
            $data['msg'] = $configs['error_msg'];
        }
    }

    if (isset($_POST['move_to_hw'])) {
        $selectedQuestionIds = $_POST['ids']; // Assuming the checkboxes have name='ids[]'

        foreach ($selectedQuestionIds as $questionId) {
            $r = updateTable("lesson_questions", array("HW" => 1, "pt" => 0), array("id" => $questionId));
        }

        if ($r) {
            $data['msg'] = $configs['success_msg'];
        } else {
            $data['msg'] = $configs['error_msg'];
        }
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

    if (isset($_POST['remove_pt'])) {
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

    if (isset($_POST['remove_cat'])) {
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

    if (isset($_POST['download_pt_ques'])) {
        $selectedQuestionIds = $_POST['ids'] ?? []; // Checkboxes named 'ids[]'

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

            $withImg1 = isset($_POST['with_img_pt']);

            // Function to decode and clean HTML content for plain text
            $cleanText = function ($text) use ($withImg1) {
                $text = str_replace("&#92;", "\\", $text); // handle escaped backslashes
                $text = html_entity_decode(html_entity_decode(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8')); // decode HTML entities

                if ($withImg1) {
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

                $withId = isset($_POST['with_id_pt']); // true if checkbox was selected

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
        $file_name = $data['course_name'] . "-" . $data['cat_name'] . "-" . $data['lesson_name'] . "-pt.txt";

        // Set headers to download content as a .txt file
        header('Content-Type: text/plain');
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header('Content-Length: ' . strlen($content));

        // Output the content and stop script execution
        echo $content;
        exit;
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

    $conditions = array("l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']);

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
    if ($data['cat_name'] == "Flashcards") {
        $data['db_list'] = getQueryByJoin(
            ["question as q", "lesson_questions as l"],
            "q.*, l.order_no, l.id",
            $conditions,
            ["q.qid" => "l.qid"],
            "l.order_no"
        );

        $data['total'] = getTotalRowsByJoin(
            ["question as q", "lesson_questions as l"],
            $conditions,
            ["q.qid" => "l.qid"]
        );
    } else {
        $conditions = array_merge($conditions, ["l.lesson_id" => (int)$_GET['lesson_id']]);

        $data['db_list'] = getQueryByJoin(
            ["question as q", "lesson_questions as l"],
            "q.*, l.order_no, l.HW, l.pt, l.id",
            $conditions,
            ["q.qid" => "l.qid"],
            "l.order_no"
        );

        $data['total'] = getTotalRowsByJoin(
            ["question as q", "lesson_questions as l"],
            $conditions,
            ["q.qid" => "l.qid"]
        );

        foreach (['pt', 'HW'] as $field) {
            $data['total_' . strtolower($field)] = getTotalRowsByJoin(
                ["question as q", "lesson_questions as l"],
                array_merge($conditions, ["l.$field" => 1]),
                ["q.qid" => "l.qid"]
            );
        }
    }
}

$data['page_title'] = "Course: " . $data['course_name'] . " > Category: " . $data['cat_name'] . " > Lesson: " . $data['lesson_name'];
$data['breadcrumb'] = array(
    $data['course_name'] => $configs['vpath'] . "admincp/category_list?course_id=" . $_GET['course_id'], 
     " " .$data['lesson_name'] => "", " > ". $data['total']." questions"  => ""
);

echo template($data);
