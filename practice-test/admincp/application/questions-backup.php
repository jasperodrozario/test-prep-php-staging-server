<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));

$data['page_title'] = "Add Question in <u>" . $data['cat_name'] . "</u>";
$data['breadcrumb'] = array(
    "Category List" => "category_list",
    "Add Question in <u>" . $data['cat_name'] . "</u>" => ""
);

$data['categories'] = getQuery("question", " DISTINCT cat_id", "", "cat_id");
$data['course'] = getQuery("course", "*");
$data['subject_list'] = getQuery("question", "DISTINCT subject", array("subject!" => ""), "subject");
//$data['chapter_list'] = getQuery("question", "DISTINCT chapter", array("chapter!"=>""),"chapter");

if (!$_GET['page']) {
    $_GET['page'] = 'question-list';
}


$data['option_no'] = 5;

if ($_GET['page']) {

    if ($_GET['del_id']) {
        $q_data = getDataByWhere("question", "*", array("qid" => $_GET['del_id']));

        if ($q_data) {
            // Insert data into the "question_deleted" table
            // Prepare data for insertion, only adding columns with data
            $insert_data = array();

            if (!empty($q_data["qid"])) {
                $insert_data["qid"] = $q_data["qid"];
            }
            if (!empty($q_data["question"])) {
                $insert_data["question"] = $q_data["question"];
            }
            if (!empty($q_data["option_a"])) {
                $insert_data["option_a"] = $q_data["option_a"];
            }
            if (!empty($q_data["option_b"])) {
                $insert_data["option_b"] = $q_data["option_b"];
            }
            if (!empty($q_data["option_c"])) {
                $insert_data["option_c"] = $q_data["option_c"];
            }
            if (!empty($q_data["option_d"])) {
                $insert_data["option_d"] = $q_data["option_d"];
            }
            if (!empty($q_data["option_e"])) {
                $insert_data["option_e"] = $q_data["option_e"];
            }
            if (!empty($q_data["answer"])) {
                $insert_data["answer"] = $q_data["answer"];
            }
            if (!empty($q_data["explanation"])) {
                $insert_data["explanation"] = $q_data["explanation"];
            }
            if (!empty($q_data["question_tag"])) {
                $insert_data["question_tag"] = $q_data["question_tag"];
            }
            if (!empty($q_data["cat_id"])) {
                $insert_data["cat_id"] = $q_data["cat_id"];
            }
            if (!empty($q_data["source"])) {
                $insert_data["source"] = $q_data["source"];
            }
            if (!empty($q_data["story_id"])) {
                $insert_data["story_id"] = $q_data["story_id"];
            }
            if (!empty($q_data["old_story_id"])) {
                $insert_data["old_story_id"] = $q_data["old_story_id"];
            }
            if (!empty($q_data["old_ord"])) {
                $insert_data["old_ord"] = $q_data["old_ord"];
            }
            if (!empty($q_data["chapter"])) {
                $insert_data["chapter"] = $q_data["chapter"];
            }
            if (!empty($q_data["subject"])) {
                $insert_data["subject"] = $q_data["subject"];
            }
            if (!empty($q_data["topic"])) {
                $insert_data["topic"] = $q_data["topic"];
            }

            // Insert only if there's valid data to insert
            if (!empty($insert_data)) {
                insertTable("question_deleted", $insert_data);
            }
        }

        $r = deleteData("question", array("qid" => $_GET['del_id']));
        deleteData("lesson_questions", array("qid" => $_GET['del_id']));
        $data['msg'] = $configs['delete_msg'];
    }

    if (isset($_POST['deleteMultiQuestions'])) {
        $selectedQuestionIds = $_POST['ids'];

        foreach ($selectedQuestionIds as $questionId) {
            $r = deleteData("question", array("qid" => (int)$questionId));
            deleteData("lesson_questions", array("qid" => (int)$questionId));
        }

        if ($r) {
            $data['msg'] = $configs['success_msg'];
        } else {
            $data['msg'] = $configs['error_msg'];
        }
    }
		if (isset($_POST['updateQuestionData'])) {
		// Handle updating question types here
		$selectedQuestionData = $_POST['selectedQuestionData'];
		$selectedQuestionIds = $_POST['ids']; // Assuming the checkboxes have name='ids[]'

	if ($_POST['select_option'] == "qtype") {
		foreach ($selectedQuestionIds as $questionId) {
			$existing_ques_type = getFldValueById("question", "question_tag", array("qid" => (int)$questionId));
			$updated_ques_type = $existing_ques_type . ", " . $selectedQuestionData;
			$r = updateTable("question", array("question_tag" => $updated_ques_type), array("qid" => (int)$questionId));
		}
	} else if ($_POST['select_option'] == "qcategory") {
		foreach ($selectedQuestionIds as $questionId) {
			$r = updateTable("question", array("cat_id" => $selectedQuestionData), array("qid" => (int)$questionId));
		}
	} else if ($_POST['select_option'] == "qsubject") {
		foreach ($selectedQuestionIds as $questionId) {


			$r = updateTable("question", array("subject" => $selectedQuestionData), array("qid" => (int)$questionId));
		}
	} else if ($_POST['select_option'] == "qchapter") {
		foreach ($selectedQuestionIds as $questionId) {
			$r = updateTable("question", array("chapter" => $selectedQuestionData), array("qid" => (int)$questionId));
		}
	} else if ($_POST['select_option'] == "qtopic") {
		foreach ($selectedQuestionIds as $questionId) {
			$r = updateTable("question", array("topic" => $selectedQuestionData), array("qid" => (int)$questionId));
		}
	}

	if ($r) {
		$data['msg'] = $configs['success_msg'];
	} else {
		$data['msg'] = $configs['error_msg'];
	}
}
	
	
//Filtering questions
// Ensure $_POST receives values if coming via GET
	if ($_GET['cat_id_drop']) {
    $_POST['cat_id_drop'] = $_GET['cat_id_drop'];
	}
	if ($_GET['question_tag']) {
		$_POST['question_tag'] = $_GET['question_tag'];
	}
	if ($_GET['search_id']) {
		$_POST['search_id'] = $_GET['search_id'];
	}
	if ($_GET['search']) {
		$_POST['search'] = $_GET['search'];
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

	// filters questions by lesson
	if (!empty($_GET['course_id'])) {
		$_POST['course_id'] = $_GET['course_id'];
	}
    
    if (!empty($_GET['cat_id'])) {
        $_POST['cat_id'] = $_GET['cat_id'];
    }
    if (!empty($_GET['lesson_id'])) {
        $_POST['lesson_id'] = $_GET['lesson_id'];
    }
    
    //testing


$query = array();        // filters for question table
$query_lesson = array(); // filters for lesson_questions

// 1. Build $query with question table filters
if (!empty($_POST['search_id'])) {
    $data['search_id'] = $_POST['search_id'];
    $query['qid'] = (int)$_POST['search_id'];
}
elseif (!empty($_POST['search'])) {
    $data['search_phrage'] = $_POST['search'];
    $query['question like '] = "'%" . $_POST['search'] . "%'";
}

if (!empty($_POST['cat_id_drop'])) {
    $data['category'] = $_POST['cat_id_drop'];
    $query['cat_id'] = $_POST['cat_id_drop']; // string cat_id in question table
}
if (!empty($_POST['question_tag'])) {
    $qtypes = explode(',', $_POST['question_tag']);
    foreach ($qtypes as $qtype) {
        $query['question_tag like '] = "'%" . $qtype . "%'";
    }
    $data['ques_type'] = $_POST['question_tag'];
}
if (!empty($_POST['subject'])) {
    $data['subject'] = $_POST['subject'];
    $query['subject'] = $_POST['subject'];
}
if (!empty($_POST['chapter'])) {
    $data['chapter'] = $_POST['chapter'];
    $query['chapter'] = $_POST['chapter'];
}
if (!empty($_POST['topic'])) {
    $data['topic'] = $_POST['topic'];
    $query['topic'] = $_POST['topic'];
}

// 2. Build $query_lesson for lesson_questions table
if (!empty($_POST['course_id'])) {
    $data['course_id'] = $_POST['course_id'];
    $query_lesson['course_id'] = (int)$_POST['course_id'];
}

if (!empty($_POST['cat_id'])) {
     $data['cat_id'] = $_POST['cat_id'];
    $query_lesson['cat_id'] = (int)$_POST['cat_id'];
}
if (!empty($_POST['lesson_id'])) {
     $data['lesson_id'] = $_POST['lesson_id'];
    $query_lesson['lesson_id'] = (int)$_POST['lesson_id'];
}
// Debugging
//$data['query_lesson_test'] = $query_lesson;

// 3. If any lesson filter is set, get qids

if (!empty($query_lesson)) {
    $lessonQues = getQuery("lesson_questions", "qid", $query_lesson);
$qid_array = [];
if ($lessonQues && $lessonQues->num_rows > 0) {
    while ($row = $lessonQues->fetch_assoc()) {
        $qid_array[] = (int)$row['qid'];
    }
}

$qids = $qid_array;
$data['qids_test'] = $qids;

    if (!empty($qids)) {
        $query["qid in "] = "(" . implode(",", array_map('intval', $qids)) . ")";
    } else {
        $query["qid in "] = "(0)"; // ensures no result
    }
}

// === Load Questions ===
if (isset($_POST['ok'])) {
    // Default order
	$orderBy = !empty($_GET['order_by_question']) ? "question ASC" : "qid DESC";
	$data['db_list'] = getQuery("question", "*", $query, $orderBy, $configs['limit']);
	$data['total']   = getTotalRow("question", $query);

} else {
    // Default fallback load
	$orderBy = !empty($_GET['order_by_question']) ? "question ASC" : "qid DESC";
    $data['db_list'] = getQuery("question", "*", $query, $orderBy, $configs['limit']);
    $data['total']   = getTotalRow("question", $query);
}
//============================================

    if (isset($_POST['download_ques'])) {

        $selectedQuestionIds = $_POST['ids'] ?? []; // 'ids[]' from checkbox inputs

        if (empty($selectedQuestionIds)) {
            // Only run this query when no IDs are selected
            $id_list = getQuery("question", "*", $query, "qid desc");
            $total = getTotalRow("question", $query);

            foreach ($id_list as $i) {
                $selectedQuestionIds[] = $i['qid'];
            }
        }

        if (!empty($selectedQuestionIds)) {
            $content = "";
            $qno = 1;
            $alphabet = ['A', 'B', 'C', 'D', 'E'];

            $withImg = isset($_POST['with_img']);

            // Function to decode and clean HTML content for plain text
            $cleanText = function ($text) use ($withImg) {
                $text = str_replace("&#92;", "\\", $text); // handle escaped backslashes
                $text = html_entity_decode(html_entity_decode(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8')); // decode HTML entities

                if ($withImg) {
                    $text = preg_replace_callback('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', function($matches) {
                        return "Image: " . $matches[1]; // formatted image URL
                    }, $text); 
                } 
                
                $text = strip_tags($text); // remove all other HTML tags
                $text = str_replace(['\\(', '\\)'], '', $text); // remove \( and \)
                $text = trim($text); // trim spaces/newlines
                return $text;
            };

            foreach ($selectedQuestionIds as $id) {

                $withId = isset($_POST['with_id']); // true if checkbox was selected
                $question = getDataByWhere("question", "*", ["qid" => $id]);

                $content .= "Question {$qno}:\n";
                if ($withId) {
                    // Generate questions WITH ID
                    $content .= "ID: {$id}\n";  
                } 
                $content .= $cleanText($question['question']) . "\n\n";

                $content .= "A. " . $cleanText($question['option_a']) . "\n";
                $content .= "B. " . $cleanText($question['option_b']) . "\n";
                $content .= "C. " . $cleanText($question['option_c']) . "\n";
                $content .= "D. " . $cleanText($question['option_d']) . "\n\n";

                $content .= "The answer is " . $alphabet[$question['answer'] - 1] . ".\n\n";

                if ($question['explanation']) {
                    $content .= "Explanation:\n" . $cleanText($question['explanation']) . "\n\n";
                }
                else{
                    $content .= "Explanation:\n\n";
                }
                
                $qno++;
            }
        } else {
            $content = "Empty questions selected. Please select question(s) to download.\n";
        }

        // Prepare file name
        $file_name = "question_list_" . $total . ".txt";

        // Set headers to download content as a .txt file
        header('Content-Type: text/plain');
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header('Content-Length: ' . strlen($content));

        // Output the content and stop script execution
        echo $content;
        exit;
    }
}

$data['cat_name'] = getFldValueById("category", "category_name", array("cat_id" => $_POST['cat_id']));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $_POST['course_id']));
$data['lesson_name'] = getFldValueById("lesson", "lesson_name", array("lesson_id" => $_POST['lesson_id']));

$data['page_title'] = "Course " . $data['course_name'] . " > Category " . $data['cat_name'] . " > Lesson " . $data['lesson_name'];
$data['breadcrumb'] = array("Question List" => "", " > Total Questions: " . $data['total'] => "");



echo template($data);
