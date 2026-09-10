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
if ($_GET['chapter']) {
    $_POST['chapter'] = $_GET['chapter'];
}
if ($_GET['subject']) {
    $_POST['subject'] = $_GET['subject'];
}
if ($_GET['topic']) {
    $_POST['topic'] = $_GET['topic'];
}
if (!$_GET['add_to']) {
    $_GET['add_to'] = $_POST['add_to'];
}
if (!$_GET['predic_test_id']) {
    $_GET['predic_test_id'] = $_POST['predic_test_id'];
}
if (!$_GET['set_id']) {
    $_GET['set_id'] = $_POST['set_id'];
}
if (!$_GET['pred_cat_id']) {
    $_GET['pred_cat_id'] = $_POST['pred_cat_id'];
}
if (!$_GET['flashcard_cat_id']) {
    $_GET['flashcard_cat_id'] = $_POST['flashcard_cat_id'];
}

if ($_POST) {
    $_REQUEST = $_POST;
}

$data['cat_id'] = $_GET['cat_id'];
$data['lesson_id'] = $_GET['lesson_id'];
$data['course_id'] = $_GET['course_id'];
$data['flashcard_cat_id'] = $_GET['flashcard_cat_id'];

$data['course'] = getQuery("course", "*");

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_GET['course_id']));
$data['lesson_name'] = getFldValueById("lesson", "lesson_name", array("lesson_id" => $_GET['lesson_id']));
$data['subject_list'] = getQuery("question", "DISTINCT subject", array("subject!" => ""), "subject");
$data['option_no'] = 5;

$data['categories'] = getQuery("question", " DISTINCT cat_id", "", "cat_id");

$query = array("is_disable"=>0);

# -------- Start Question ID List of already added questions ---------

if ($_GET['add_to'] == "HW") {

    // $question_list = getQuery("lesson_questions", "qid", array("cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));
    $question_list = getQuery("lesson_questions", "qid", array("course_id" => $_GET['course_id']));

} else if ($_GET['add_to'] == "pt") {

    // $question_list = getQuery("lesson_questions", "qid", array("cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));
    $question_list = getQuery("lesson_questions", "qid", array("course_id" => $_GET['course_id']));

} else if ($_GET['add_to'] == "flashcards") {

    // $question_list = getQuery("lesson_questions", "qid", array("cat_id" => $_GET['cat_id'], "course_id" => $_GET['course_id']));
    $question_list = getQuery("lesson_questions", "qid", array("course_id" => $_GET['course_id']));

} else if ($_GET['add_to'] == "pred_set") {
    
    // $question_list = getQuery("predic_questions", "qid", array("pred_cat_id" => $_GET['pred_cat_id'], "set_id" => $_GET['set_id'], "predic_test_id" => $_GET['predic_test_id']));
    $question_list = getQuery("predic_questions", "qid", array("predic_test_id" => $_GET['predic_test_id']));

}


if ($_GET['add_to']) {
    $question_id_list = array();
    foreach ($question_list as $q) {
        $question_id_list[] = $q['qid'];
    }
    $not_in_clause = implode(',', $question_id_list);
    if ($not_in_clause) {
        $query = array_merge($query, array("qid not in " => "($not_in_clause)"));
    }
}

# -------- End Question ID List of already added questions ---------

if (!$_POST['search_id']) {
    $_POST['search_id'] = $_GET['search_id'];
}
if (!$_POST['search']) {
    $_POST['search'] = $_GET['search'];
}

if (!$_POST['cat_id_drop']) {
    $_POST['cat_id_drop'] = $_GET['cat_id_drop'];
}
if (!$_POST['question_tag']) {
    $_POST['question_tag'] = $_GET['question_tag'];
}

if ($_POST['search_id']) {
    // Split by comma, trim spaces, keep only valid integers
    $ids = array_filter(
        array_map('intval', array_map('trim', explode(',', $_POST['search_id']))),
        function ($v) {
            return $v > 0;
        }
    );

    if (!empty($ids)) {
        $query['qid in '] = '(' . implode(',', $ids) . ')';
    } else {
        $query['qid in '] = '(0)'; // force no result if invalid input
    }
    // $query=array_merge($query,array("qid"=>(int)$_POST['search_id']));
} elseif ($_POST['search']) {
    $data['search_phrage'] = $_POST['search'];
    $query = array_merge($query, array("question like " => "'%" . $_POST['search'] . "%'"));
}

if ($_POST['cat_id_drop']) {
    $data['category'] = $_POST['cat_id_drop'];
    $query = array_merge($query, array("cat_id" => $_POST['cat_id_drop']));
}
if ($_POST['question_tag']) {
    //	$query=array_merge($query,array("question_tag like "=>"'%".$_POST['question_tag'] ."%'"));

    $questionTypes = $_POST['question_tag'];
    $questionTypesArray = explode(',', $questionTypes);
    foreach ($questionTypesArray as $qtype) {
        $query = array_merge($query, array("question_tag like " => "'%" . $qtype . "%'"));
    }
    $data['ques_type'] = $_POST['question_tag'];
}

if ($_POST['subject']) {
    $data['subject'] = $_POST['subject'];
    $query = array_merge($query, array("subject" => $_POST['subject']));
}
if ($_POST['chapter']) {
    $data['chapter'] = $_POST['chapter'];
    $query = array_merge($query, array("chapter" => $_POST['chapter']));
}
if ($_POST['topic']) {
    $data['topic'] = $_POST['topic'];
    $query = array_merge($query, array("topic" => $_POST['topic']));
}

if (isset($_POST['ok'])) {
    $data['db_list'] = getQuery("question", "*", $query, "qid desc", $configs['limit']);
    $data['total'] = getTotalRow("question", $query);
} else {
    //    $data['db_list'] = getQueryByJoin(array("question as q","lesson_questions as l"), "q.*",array("l.lesson_id"=>(int)$_GET['lesson_id'], "l.cat_id"=>(int)$_GET['cat_id'], "l.course_id"=>(int)$_GET['course_id'],  "l.HW"=>1),array("q.qid !"=>"l.qid"), "l.order_no","","", "q.qid");
    $data['db_list'] = getQuery("question", "*", $query, "", $configs['limit']);
    $data['total'] = getTotalRow("question", $query);
}

if ($_GET['del_id']) {
    $r = deleteData("question", array("qid" => $_GET['del_id']));
    deleteData("lesson_questions", array("qid" => $_GET['del_id']));
    $data['msg'] = $configs['delete_msg'];
}

if (isset($_POST['download_hw_ques'])) {

        $selectedQuestionIds = $_POST['ids'] ?? []; // 'ids[]' from checkbox inputs

        if (empty($selectedQuestionIds)) {
            // Only run this query when no IDs are selected
            $id_list = getQuery("question", "*", $query);
            // getQueryByJoin(array("question as q", "lesson_questions as l"), "l.id",  array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => (int)$_GET['cat_id'], "l.course_id" => (int)$_GET['course_id']), ["q.qid" => "l.qid"], "l.order_no, l.id");

            foreach ($id_list as $i) {
                $selectedQuestionIds[] = $i['qid'];
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

                // $lesson_question = getDataByWhere("lesson_questions", "*", array("id" => $id));
                $question = getDataByWhere("question", "*", ["qid" => $id]);

                $withId = isset($_POST['with_id_hw']); // true if checkbox was selected

                $content .= "Question {$qno}:\n";
                if ($withId) {
                    // Generate questions WITH ID
                    $content .= "ID: {$question['qid']}\n";
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


//  ************ Start Insert Questions ***********            
if (isset($_POST['save_button'])) {

    // Extract form data
    $targetCourse = $_GET['course_id'];
    $targetCategoryId = $_GET['cat_id'];
    $targetLessonId = $_GET['lesson_id'];
    $targetPredCatId = $_GET['pred_cat_id'];
    $targetSetId = $_GET['set_id'];
    $targetPredTestId = $_GET['predic_test_id'];
    $selectedQuestionIds = $_REQUEST['ids'];
    $count = count($selectedQuestionIds);
    $data['copy_msg'] = $count . " Questions has been copied successfully!";
    $hw = 0;
    $pt = 0;
    if ($_GET['add_to'] == "HW") {
        $hw = 1;
    } else if ($_GET['add_to'] == "pt") {
        $pt = 1;
    }

    foreach ($selectedQuestionIds as $questionId) {
        if ($_GET['lesson_id']) {
            // Create a new question data array
            $newQuestionData = array(
                'qid' => $questionId,
                'lesson_id' => $targetLessonId,
                'cat_id' => (int)$targetCategoryId,
                'course_id' => $targetCourse,
                'HW' => $hw,
                'pt' => $pt
            );
            $newQuestionId = insertTable("lesson_questions", $newQuestionData);
        } else if ($_GET['pred_cat_id']) {
            $course_id = getFldValueById("predic_test", "course_id", array("Predic_test_id" => $_GET['predic_test_id']));
            $cat_id = getFldValueById("prediction_test_category", "cat_id", array("id" => $_GET['pred_cat_id']));
            $newQuestionData = array(
                'qid' => $questionId,
                'set_id' => (int)$targetSetId,
                'predic_test_id' => $targetPredTestId,
                'pred_cat_id' => $targetPredCatId,
                'cat_id' => $cat_id,
                'course_id' => $course_id
            );
            $newQuestionId = insertTable("predic_questions", $newQuestionData);
        } else { // flashcard question insertion
            $newQuestionData = array(
                'qid' => $questionId,
                'cat_id' => (int)$targetCategoryId,
                'course_id' => $targetCourse,
                'flashcard_cat_id' => (int)$_GET['flashcard_cat_id']
            );
            $newQuestionId = insertTable("lesson_questions", $newQuestionData);
        }
    }
    $data['msg'] = $configs['success_msg'];

    if ($_GET['lesson_id']) {

        $data['msg'] = $configs['success_msg'];

        // header("location:add_lesson_questions?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id']. "&add_to=" . $_GET['add_to']);

    } else if ($_GET['pred_cat_id']) {
        header("location:pred_questions_list?predic_test_id=" . $_GET['predic_test_id'] . "&set_id=" . $_GET['set_id'] . "&pred_cat_id=" . $_GET['pred_cat_id']);
    } else {
        header("location:questions_list?cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&flashcard_cat_id=" . $_GET['flashcard_cat_id']);
    }
}
//  ************ End Insert Questions ***********     

if ($_GET['pred_cat_id']) {
    $data['pred_test_name'] = getFldValueById("predic_test", "test_name", array("predic_test_id" => $_GET['predic_test_id']));
    $data['set_name'] = getFldValueById("predic_question_set", "set_name", array("set_id" => $_GET['set_id']));
    $cat_id = getFldValueById("prediction_test_category", "cat_id", array("id" => $_GET['pred_cat_id']));
    $data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $cat_id));

    $data['page_title'] = "Prediction Test: " . $data['pred_test_name'] . " > Set Name: " . $data['set_name'] . " > Category Name: " . $data['cat_name'] . " > " . strtoupper($_GET['add_to']);
} else if ($_GET['flashcard_cat_id']) {
    $data['flashcard_cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['flashcard_cat_id']));

    $data['page_title'] = "Course: " . $data['course_name'] . " > Category: " . $data['cat_name'] . " > " . $data['flashcard_cat_name'] . " > " . strtoupper($_GET['add_to']);
} else {
    $data['page_title'] = "Course: " . $data['course_name'] . " > Category: " . $data['cat_name'] . " > Lesson: " . $data['lesson_name'] . " > " . strtoupper($_GET['add_to']);
}
$data['breadcrumb'] = array(
    "Lesson Question List" => "https://staging-test-prep.asvab-tutoring.com/practice-test/admincp/questions_list?lesson_id=" . $_GET['lesson_id'] . "&cat_id=" . $_GET['cat_id'] . "&course_id=" . $_GET['course_id'] . "&flashcard_cat_id=" . $_GET['flashcard_cat_id'],
    " Total Questions: " . $data['total'] => ""
);

echo template($data);
