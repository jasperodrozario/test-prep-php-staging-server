<?php
include("../common/access.php");
include("../common/autoload.php");

if ($_SERVER['HTTP_HOST'] != "localhost") {
	include('../smtpmail.php');
}

// Load questions by lesson

if (isset($_POST['action'])) {

	// 1. Get Categories by Course
	if ($_POST['action'] === "getCategories" && !empty($_POST['course_id'])) {
		$course_id = (int) $_POST['course_id'];

		// Fetch only required columns as array
		$categories = getQuery("category", "cat_id, category_name", ["course_id" => $course_id], "", "", 0);

		if (!empty($categories)) {
			echo "<option value=''>Select Category</option>";
			foreach ($categories as $cat) {
				echo "<option value='{$cat['cat_id']}'>{$cat['category_name']}</option>";
			}
		} else {
			echo "<option value=''>No Category Found</option>";
		}

		exit;
	}

	// 2. Get Lessons by Category
	if ($_POST['action'] === "getLessons" && !empty($_POST['cat_id'])) {
		$cat_id = (int) $_POST['cat_id'];

		// Fetch only required columns as array
		$lessons = getQuery("lesson", "lesson_id, Lesson_name", ["cat_id" => $cat_id], "", "", 0);

		if (!empty($lessons)) {
			echo "<option value=''>Select Lesson</option>";
			foreach ($lessons as $lesson) {
				echo "<option value='{$lesson['lesson_id']}'>{$lesson['Lesson_name']}</option>";
			}
		} else {
			echo "<option value=''>No Lesson Found</option>";
		}

		exit;
	}
}


if ($_POST['action']) {
	$array = array();

	switch ($_POST['action']) {

		case "load-categories":

			$course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;

			$out = array(
				"status" => "ok",
				"categories" => array()
			);

			if ($course_id <= 0) {
				$out["status"] = "error";
				$out["message"] = "Invalid course_id";
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($out);
				break;
			}

			// Load categories for the course, ordered by category_order (INT)
			$res = getQuery(
				"category",
				"cat_id, category_name",
				array("course_id" => $course_id),
				"category_order ASC"
			);

			if ($res && $res->num_rows > 0) {
				while ($row = $res->fetch_assoc()) {
					$out["categories"][] = array(
						"cat_id" => (int)$row["cat_id"],
						"category_name" => $row["category_name"]
					);
				}
			}

			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($out);

			break;



		case 'deletePredCat':
			// Fetch the necessary parameters
			$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
			$predTestId = isset($_POST['pred_test_id']) ? (int)$_POST['pred_test_id'] : 0;
			$catId = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;

			if ($id && $predTestId && $catId) {
				// Proceed to delete the category
				$r = deleteData("prediction_test_category", array("id" => $id));

				if ($r) {
					// Return success response
					echo json_encode([
						'status' => 'success',
						'msg' => 'Category deleted successfully'
					]);
				} else {
					// Return error response
					echo json_encode([
						'status' => 'error',
						'msg' => 'Failed to delete the category.'
					]);
				}
			} else {
				// Invalid input
				echo json_encode([
					'status' => 'error',
					'msg' => 'Invalid data provided.'
				]);
			}
			break;

		case "getOptionByOptionId":
			if (isset($_POST['qid']) && isset($_POST['oid'])) {
				$qid = intval($_POST['qid']);
				$oid = intval($_POST['oid']);

				$option_label = chr(96 + $oid);

				// Adjust table/column names as needed
				$result = getDataByWhere("question", "*", array("qid" => $qid));

				if (!empty($result['option_' . $option_label])) {
					echo json_encode([
						'status' => 'success',
						'option' => html_entity_decode(html_entity_decode($result['option_' . $option_label]))
					]);
				} else {
					echo json_encode([
						'status' => 'error',
						'message' => 'Option not found.'
					]);
				}
			} else {
				echo json_encode([
					'status' => 'error',
					'message' => 'Missing parameters.'
				]);
			}
			flush();
			break;


		case "getQuestionByQuestionId":
			if (isset($_POST['qid'])) {
				$qid = intval($_POST['qid']);
				$result = getDataByWhere("question", "*", array("qid" => $qid));

				if (!empty($result['question'])) {
					echo json_encode([
						'status' => 'success',
						'question' => html_entity_decode(html_entity_decode($result['question']))
					]);
				} else {
					echo json_encode([
						'status' => 'error',
						'message' => 'Question not found.'
					]);
				}
			} else {
				echo json_encode([
					'status' => 'error',
					'message' => 'No question ID provided.'
				]);
			}
			flush();

			break;

		case "updateQuestionContent":
			// Sanitize and prepare the content
			$_POST['content'] = str_replace("\\", "&#92;", $_POST['content']);
			$_POST['content'] = str_replace("\"", "'", $_POST['content']);
			$_POST['content'] = htmlentities($_POST['content']);

			$flag = updateTable("question", array("question" => $_POST['content']), array("qid" => $_POST['questionId']));

			//$_POST['content'] = str_replace("\"", "'", $_POST['content']); // Optional
			//$flag = updateTable("question", ["question" => $_POST['content']], ["qid" => $_POST['questionId']]);


			if ($flag) {
				$array['msg'] = "Question has been updated successfully.";
			} else {
				$array['msg'] = "Question has not been updated.";
			}
			echo json_encode($array);
			flush();

			break;

		case "updateOptionContent":

			$optionIndex = (int) $_POST['optionId']; // 1 for A, 2 for B, ...
			$alphabet = ["", "a", "b", "c", "d", "e"]; // Adjust if you have more options
			$optionField = "option_" . $alphabet[$optionIndex];

			if (!in_array($optionField, ["option_a", "option_b", "option_c", "option_d", "option_e"])) {
				echo json_encode(["msg" => "Invalid option field"]);
				flush();
				break;
			}

			// Sanitize and prepare the content
			$_POST['content'] = str_replace("\\", "&#92;", $_POST['content']);
			$_POST['content'] = str_replace("\"", "'", $_POST['content']);
			$_POST['content'] = htmlentities($_POST['content']);

			$flag = updateTable("question", array($optionField  => $_POST['content']), array("qid" => $_POST['questionId']));

			//$_POST['content'] = str_replace("\"", "'", $_POST['content']); // Optional
			//$flag = updateTable("question", [$optionField => $_POST['content']], ["qid" => $_POST['questionId']]);

			if ($flag) {
				$array['msg'] = "Option has been updated successfully.";
			} else {
				$array['msg'] = "Option has not been updated.";
			}
			echo json_encode($array);
			flush();

			break;

		case "updateExplanationContent":

			// Sanitize and prepare the content
			$_POST['content'] = str_replace("\\", "&#92;", $_POST['content']);
			$_POST['content'] = str_replace("\"", "'", $_POST['content']);
			$_POST['content'] = htmlentities($_POST['content']);

			$flag = updateTable("question", array("explanation" => $_POST['content']), array("qid" => $_POST['questionId']));

			//$_POST['content'] = str_replace("\"", "'", $_POST['content']); // Optional
			//$flag = updateTable("question", ["explanation" => $_POST['content']], ["qid" => $_POST['questionId']]);


			if ($flag) {
				$array['msg'] = "Explanation has been updated successfully.";
			} else {
				$array['msg'] = "Explanation has not been updated.";
			}
			echo json_encode($array);
			flush();

			break;

		case "getExplanationByQuestionId":
			if (isset($_POST['qid'])) {
				$qid = intval($_POST['qid']);
				$result = getDataByWhere("question", "*", array("qid" => $qid));

				if (!empty($result['explanation'])) {
					echo json_encode([
						'status' => 'success',
						'explanation' => html_entity_decode(html_entity_decode($result['explanation']))
					]);
				} else {
					echo json_encode([
						'status' => 'error',
						'message' => 'Explanation not found.'
					]);
				}
			} else {
				echo json_encode([
					'status' => 'error',
					'message' => 'No question ID provided.'
				]);
			}
			flush();
			break;



		case "sendEmail":

			$remail = getFldValueById("enquiry", "email", array("id" => $_POST['id']));

			if ($_SERVER['HTTP_HOST'] != "localhost") {
				smtpmailer($remail, $configs['support_mail'], ucwords($configs['dotcom']), $_POST['subject'], $_POST['msg']);
			}

			$array['msg'] = "Email has been sent successfully.$remail";

			echo json_encode($array);
			flush();

		case "getPredCategories":

			$course_id = getFldValueById("predic_test", "course_id", array("Predic_test_id" => $_POST['testId']));
			$categories = getQuery("category", "*", array("course_id" => $course_id), "category_name");
			$option = "<option value=''>Select Category</option>";
			foreach ($categories as $c) {
				$option .= "<option value='" . $c['cat_id'] . "'>" . $c['category_name'] . "</option>";
			}
			$array['list'] = "";

			$array['msg'] = "";
			echo json_encode($array);
			flush();
			break;

		case "getChapterBySubject":

			$table_name = (!empty($_POST['table'])) ? "question_deleted" : "question";
			$subject    = isset($_POST['subject']) ? trim($_POST['subject']) : "";

			$option = "<option value=''>Select Chapter</option>";

			if ($subject !== "") {

				$chapters = getQuery(
					$table_name,
					"DISTINCT chapter",
					array(
						"subject"   => $subject,
						"chapter !" => ""
					),
					"chapter"
				);

				if ($chapters && mysqli_num_rows($chapters) > 0) {
					while ($row = mysqli_fetch_assoc($chapters)) {
						$ch = $row['chapter'];
						$option .= "<option value='" . $ch . "'>" . $ch . "</option>";
					}
				}
			}

			echo json_encode(array("options" => $option));
			flush();
			break;



		case "getTopicsByChapter":

			$table_name = (!empty($_POST['table'])) ? "question_deleted" : "question";
			// $subject    = isset($_POST['subject']) ? trim($_POST['subject']) : "";
			$chapter    = isset($_POST['chapter']) ? trim($_POST['chapter']) : "";

			$option = "<option value=''>Select Topic</option>";

			if ($chapter !== "") {
				$topics = getQuery(
					$table_name,
					"DISTINCT topic",
					array(
						// "subject" => $subject,
						"chapter" => $chapter,
						"topic !" => ""
					),
					"topic"
				);

				if ($topics && mysqli_num_rows($topics) > 0) {
					while ($row = mysqli_fetch_assoc($topics)) {
						$tp = $row['topic'];
						$option .= "<option value='" . $tp . "'>" . $tp . "</option>";
					}
				}
			}

			echo json_encode(array("options" => $option));
			flush();
			break;


		case "setOrder":
			if ($_POST['questionId']) {
				//$order = isset($_POST['order']) && $_POST['order'] !== '' ? (int)$_POST['order'] : null;
				$order = $_POST['order'];

				if ($order) {
					$r = updateTable("lesson_questions", array("order_no" => $order), array("id" => $_POST['questionId']));
				} else {
					$r = updateTable("lesson_questions", array("order_no" => null), array("id" => $_POST['questionId']));
				}

				if ($r) {
					$array['msg'] = "Order has been updated successfully.";
					$array['order'] = $order;
				} else {
					$array['msg'] = "Order has not been updated.";
				}
			} else {
				$array['msg'] = "Question ID is missing. Contact the web developer.";
			}
			echo json_encode($array);
			flush();
			break;


		case "setOrderFreePredTest":
			if ($_POST['questionId']) {
				if ($_POST['order']) {
					$r = updateTable("pred_free_test_questions", array("order_no" => $_POST['order']), array("test_id" => $_POST['questionId']));
				} else {
					$r = updateTable("pred_free_test_questions", array("order_no" => 0), array("test_id" => $_POST['questionId']));
				}

				if ($r) {
					$array['msg'] = "Order has been updated successfully.";
					$array['order'] = $_POST['order'];
				} else {
					$array['msg'] = "Order has not been updated.";
				}
			} else {
				$array['msg'] = "Question ID is missing. Contact the web developer.";
			}
			echo json_encode($array);
			flush();
			break;

		case "setOrderFreeTest":
			if ($_POST['questionId']) {
				if ($_POST['order']) {
					$r = updateTable("free_test_questions", array("order_no" => $_POST['order']), array("id" => $_POST['questionId']));
				} else {
					$r = updateTable("free_test_questions", array("order_no" => 0), array("id" => $_POST['questionId']));
				}

				if ($r) {
					$array['msg'] = "Order has been updated successfully.";
					$array['order'] = $_POST['order'];
				} else {
					$array['msg'] = "Order has not been updated.";
				}
			} else {
				$array['msg'] = "Question ID is missing. Contact the web developer.";
			}
			echo json_encode($array);
			flush();
			break;

		case "setQuesSubject":
			if ($_POST['questionId']) {
				$r = updateTable("question", array("subject" => $_POST['subject']), array("qid" => $_POST['questionId']));
				if ($r) {
					$array['msg'] = "Subject has been updated successfully.";
					$array['subject'] = $_POST['subject'];
				} else {
					$array['msg'] = "Subject has not been updated.";
				}
			} else {
				$array['msg'] = "Question ID is missing. Contact the web developer.";
			}

			echo json_encode($array);
			flush();
			break;

		case "setQuesChapter":
			if ($_POST['questionId']) {
				$r = updateTable("question", array("chapter" => $_POST['chapter']), array("qid" => $_POST['questionId']));
				if ($r) {
					$array['msg'] = "Chapter has been updated successfully.";
					$array['chapter'] = $_POST['chapter'];
				} else {
					$array['msg'] = "Chapter has not been updated.";
				}
			} else {
				$array['msg'] = "Question ID is missing. Contact the web developer.";
			}

			echo json_encode($array);
			flush();
			break;

		case "setQuesType":

			$r = updateTable("question", array("question_tag" => $_POST['question_tag']), array("qid" => $_POST['questionId']));
			if ($r) {
				$array['msg'] = "Question tag has been updated successfully.";
				$array['type'] = $_POST['question_tag'];
			} else {
				$array['msg'] = "Question tag has not been updated.";
			}

			echo json_encode($array);
			flush();
			break;

		case "setQuesTopic":

			$r = updateTable("question", array("topic" => $_POST['topic']), array("qid" => $_POST['questionId']));
			if ($r) {
				$array['msg'] = "Question topic has been updated successfully.";
				$array['topic'] = $_POST['topic'];
			} else {
				$array['msg'] = "Question topic has not been updated.";
			}

			echo json_encode($array);
			flush();
			break;

		case "createPredSet":
			$course_id = getFldValueById("predic_test", "course_id", array("Predic_test_id" => $_POST['predId']));

			$r = insertTable("predic_question_set", array("set_name" => $_POST['setName'], "predic_test_id" => $_POST['predId'], "course_id" => $course_id));

			if ($r) {
				$array['msg'] = "Prediction Test Set has been created successfully.";
			} else {
				$array['msg'] = "Prediction Test Set has not been created.";
			}

			echo json_encode($array);
			flush();
			break;

		case "createGroup":

			$r = insertTable("course_group", array("group_name" => $_POST['group_name'], "ord" => $_POST['order']));

			if ($r) {
				$array['msg'] = "Course Group has been created successfully.";
			} else {
				$array['msg'] = "Course Group has not been created.";
			}

			echo json_encode($array);
			flush();
			break;

		case "updateGroup":

			$r = updateTable("course_group", array("group_name" => $_POST['group_name'], "ord" => $_POST['order']), array("group_id"=>$_POST['group_id']));

			if ($r) {
				$array['msg'] = "Course Group has been updated successfully.";
			} else {
				$array['msg'] = "Course Group has not been updated.";
			}

			echo json_encode($array);
			flush();
			break;

		case "addPredCat":

			$r = insertTable("prediction_test_category", array("cat_id" => $_POST['cat_id'], "number_of_questions" => $_POST['qnum'], "pred_test_id" => $_POST['predId']));

			if ($r) {
				$array['msg'] = "Prediction Test Category has been added successfully.";
			} else {
				$array['msg'] = "Prediction Test Category has not been added.";
			}

			echo json_encode($array);
			flush();
			break;

		case "getQuestionChaptersBySubject":

			$subject = isset($_POST['subject']) ? trim($_POST['subject']) : "";
			$option = "<option value=''>Select Chapter</option>";

			if ($subject !== "") {
				$subjectSql = mysqli_real_escape_string($configs['con'], $subject);
				$chapters = getQuery("question", "DISTINCT chapter", "subject=\"" . $subjectSql . "\" AND chapter IS NOT NULL AND chapter != ''", "chapter");
				if ($chapters) {
					while ($row = mysqli_fetch_assoc($chapters)) {
						$chapter = $row['chapter'];
						if ($chapter === '') {
							continue;
						}
						$safe = htmlspecialchars($chapter, ENT_QUOTES, 'UTF-8');
						$option .= "<option value='" . $safe . "'>" . $safe . "</option>";
					}
				}
			}

			echo json_encode(array("options" => $option));
			flush();
			break;

		case "getQuestionTopicsBySubjectChapter":

			$subject = isset($_POST['subject']) ? trim($_POST['subject']) : "";
			$chapter = isset($_POST['chapter']) ? trim($_POST['chapter']) : "";
			$option = "<option value=''>Select Topic</option>";

			if ($subject !== "" && $chapter !== "") {
				$subjectSql = mysqli_real_escape_string($configs['con'], $subject);
				$chapterSql = mysqli_real_escape_string($configs['con'], $chapter);
				$topics = getQuery("question", "DISTINCT topic", "subject=\"" . $subjectSql . "\" AND chapter=\"" . $chapterSql . "\" AND topic IS NOT NULL AND topic != ''", "topic");
				if ($topics) {
					while ($row = mysqli_fetch_assoc($topics)) {
						$topic = $row['topic'];
						if ($topic === '') {
							continue;
						}
						$safe = htmlspecialchars($topic, ENT_QUOTES, 'UTF-8');
						$option .= "<option value='" . $safe . "'>" . $safe . "</option>";
					}
				}
			}

			echo json_encode(array("options" => $option));
			flush();
			break;

		case "getStudyChapters":

			$category = isset($_POST['category']) ? trim($_POST['category']) : "";
			$option = "<option value=''>Select Chapter</option>";

			if ($category !== "") {
				$categorySql = mysqli_real_escape_string($configs['con'], $category);
				$chapters = getQuery("study", "DISTINCT chapter", "subject=\"" . $categorySql . "\" AND chapter IS NOT NULL AND chapter != ''", "chapter");
				if ($chapters) {
					while ($row = mysqli_fetch_assoc($chapters)) {
						$chapter = $row['chapter'];
						if ($chapter === '') {
							continue;
						}
						$option .= "<option value='" . htmlspecialchars($chapter, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($chapter, ENT_QUOTES, 'UTF-8') . "</option>";
					}
				}
			}

			echo json_encode(array("options" => $option));
			flush();
			break;

		case "getStudyTopics":

			$category = isset($_POST['category']) ? trim($_POST['category']) : "";
			$chapter = isset($_POST['chapter']) ? trim($_POST['chapter']) : "";
			$option = "<option value=''>Select Topic</option>";

			if ($category !== "" && $chapter !== "") {
				$categorySql = mysqli_real_escape_string($configs['con'], $category);
				$chapterSql = mysqli_real_escape_string($configs['con'], $chapter);
				$topics = getQuery("study", "DISTINCT Topic", "subject=\"" . $categorySql . "\" AND chapter=\"" . $chapterSql . "\" AND Topic IS NOT NULL AND Topic != ''", "Topic");
				if ($topics) {
					while ($row = mysqli_fetch_assoc($topics)) {
						$topic = $row['Topic'];
						if ($topic === '') {
							continue;
						}
						$option .= "<option value='" . htmlspecialchars($topic, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($topic, ENT_QUOTES, 'UTF-8') . "</option>";
					}
				}
			}

			echo json_encode(array("options" => $option));
			flush();
			break;

		case "getFilteredStudies":

			$category = isset($_POST['category']) ? trim($_POST['category']) : "";
			$chapter = isset($_POST['chapter']) ? trim($_POST['chapter']) : "";
			$topic = isset($_POST['topic']) ? trim($_POST['topic']) : "";
			$option = "<option value='0'>" . ($category === "" ? "Select a subject first" : "Select Study Title") . "</option>";

			if ($category !== "") {
				$cond = array("subject" => mysqli_real_escape_string($configs['con'], $category));
				if ($chapter !== "") {
					$cond["chapter"] = mysqli_real_escape_string($configs['con'], $chapter);
				}
				if ($topic !== "") {
					$cond["Topic"] = mysqli_real_escape_string($configs['con'], $topic);
				}
				$studies = getQuery("study", "study_id, study_title", $cond, "study_title");
				if ($studies) {
					while ($row = mysqli_fetch_assoc($studies)) {
						$option .= "<option value='" . (int) $row['study_id'] . "'>" . htmlspecialchars($row['study_title'], ENT_QUOTES, 'UTF-8') . "</option>";
					}
				}
			}

			echo json_encode(array("options" => $option));
			flush();
			break;

		case "add-study":

			$r = insertTable("lesson_study", array("study_id" => $_POST['study_id'], "course_id" => $_POST['course_id'], "cat_id" => $_POST['cat_id'], "lesson_id" => $_POST['lesson_id']));

			if ($r) {
				$array['msg'] = "Study has been added successfully.";
			} else {
				$array['msg'] = "Please try again!";
			}

			echo json_encode($array);
			flush();
			break;

		case "remove-study":

			$r = deleteData("lesson_study", array("id_lesson_study" => $_POST['study_id']));
			if ($r) {
				$array['msg'] = "success";
			} else {
				$array['msg'] = "Please try again!";
			}

			echo json_encode($array);
			flush();
			break;

		case "get_category_option":

			$option = "<option value=''>Select Category</option>";
			$list = getQuery("category", "*", array("course_id" => (int)$_POST['course_id']), "category_order");

			foreach ($list as $l) {
				$option .= "<option value='" . $l['cat_id'] . "'>" . $l['category_name'] . "</option>";
			}
			$array['list'] = $option;

			$array['msg'] = "";
			echo json_encode($array);
			flush();
			break;

		case "get_lesson_option":

			$option = "<option value=''>Select Lesson</option>";
			$list = getQuery("lesson", "*", array("cat_id" => (int)$_POST['cat_id']), "lesson_order");

			foreach ($list as $l) {
				$option .= "<option value='" . $l['lesson_id'] . "'>" . $l['Lesson_name'] . "</option>";
			}
			$array['list'] = $option;

			$array['msg'] = "";
			echo json_encode($array);
			flush();
			break;

		case "lesson":

			$_POST['description'] = str_replace("\\", "&#92;", $_POST['description']);
			$_POST['description'] = str_replace("\"", "'", $_POST['description']);

			if (!$_POST['lesson_id']) {
				unset($_POST['lesson_id']);

				$lesson_id = insertTable("lesson", $_POST);
			} else {
				$r = updateTable("lesson", array("Lesson_name" => $_POST['Lesson_name'], "Lesson_title" => $_POST['Lesson_title'], 'description' => $_POST['description'], 'lesson_order' => $_POST['lesson_order'], "cat_id" => $_POST['cat_id_1'], "course_id" => $_POST['course_id_1'], "online" => $_POST['online'], "free" => $_POST['free'], "shortcode" => $_POST['shortcode']), array("lesson_id" => $_POST['lesson_id'], "cat_id" => $_POST['cat_id'], "course_id" => $_POST['course_id']));
				$lesson_id = $_POST['lesson_id'];
			}
			$error = mysqli_errno($configs['con']);
			if (!$error) {
				$array['msg'] = $configs['success_msg'];
				$array['url'] = $configs['vpath'] . $configs['admin_dir'] . "/category_list?course_id=" . $_POST['course_id'];
			} else {
				$array['msg'] = $configs['error_msg'];
			}
			echo json_encode($array);
			flush();
			break;
		case "lesson-video":

			if ($_POST['id']) {
				updateTable("file", array("file_type" => "v", "title" => $_POST['title'], "file" => $_POST['file'], "lesson_id" => $_POST['lesson_id'], "pos" => $_POST['pos']), array("id" => $_POST['id']), array("thumb"));
			} else {
				$id = insertTable("file", array("file_type" => "v", "title" => $_POST['title'], "file" => $_POST['file'], "lesson_id" => $_POST['lesson_id'], "pos" => $_POST['pos']), array("thumb"));
			}

			$error = mysqli_errno($configs['con']);

			if (!$error) {
				$array['msg'] = $configs['success_msg'] . "<meta http-equiv='refresh' content='3'>";
			} else {
				$array['msg'] = $configs['error_msg'];
			}

			echo json_encode($array);
			flush();
			break;

		case "homework":

			$_POST['homework'] = (int)$_POST['homework'];

			$_POST['question'] = str_replace("\\", "&#92;", $_POST['question']);
			$_POST['question'] = str_replace("\"", "'", $_POST['question']);

			if ($_POST['explanation']) {

				$_POST['explanation'] = str_replace("\\", "&#92;", $_POST['explanation']);
				$_POST['explanation'] = str_replace("\"", "'", $_POST['explanation']);
			}

			if ($_POST['id']):
				$r = updateTable("questions", $_POST, array("id" => $_POST['id']));
				$data['id'] = $_POST['id'];
			else:
				unset($_POST['id']);
				$r = insertTable("questions", $_POST);
				$data['id'] = $r;
				$_POST['id'] = $r;
			endif;
			if ($r):

				deleteData("answers", array("question_id" => $_POST['id']));
				deleteData("question_answer", array("question_id" => $_POST['id']));

				for ($i = 0; $i < count($_POST['answer']); $i++) {
					$_POST['answer'][$i] = str_replace("\\", "&#92;", $_POST['answer'][$i]);
					$_POST['answer'][$i] = str_replace("\"", "'", $_POST['answer'][$i]);


					$r = insertTable("answers", array("question_id" => $_POST['id'], "answer" => htmlentities($_POST['answer'][$i]), "answer_no" => ($i + 1)));
				}

				insertTable("question_answer", array("question_id" => $_POST['id'], "explanation" => htmlentities($_POST['explanation']), "answer_no" => $_POST['answer_no']));

				$array['msg'] = $configs['success_msg'] . "<meta http-equiv=\"refresh\" content=\"3;URL='?page=homework-list&lesson_id=" . $_POST['lesson_id'] . "&cat_id=" . $_POST['cat_id'] . "'\" />";
			endif;
			//print_r($array);
			echo json_encode($array);
			flush();
			break;

		case "add-question":
			$_POST['story_id'] = (int)$_POST['story_id'];

			$_POST['question'] = str_replace("\\", "&#92;", $_POST['question']);
			$_POST['question'] = str_replace("\"", "'", $_POST['question']);
			$_POST['question'] = htmlentities($_POST['question']);

			if ($_POST['explanation']) {

				$_POST['explanation'] = str_replace("\\", "&#92;", $_POST['explanation']);
				$_POST['explanation'] = str_replace("\"", "'", $_POST['explanation']);
				$_POST['explanation'] = htmlentities($_POST['explanation']);
			}

			$options = array('option_a', 'option_b', 'option_c', 'option_d', 'option_e');
			foreach ($options as $option) {
				$_POST[$option] = str_replace("\\", "&#92;", $_POST[$option]);
				$_POST[$option] = str_replace("\"", "'", $_POST[$option]);
				$_POST[$option] = htmlentities($_POST[$option]);
			}


			if ($_POST['qid']):
				//    			$r=updateTable("question",$_POST,array("qid"=>$_POST['qid']));
				$data_array = [
					"question" => $_POST['question'],
					"option_a" => $_POST['option_a'],
					"option_b" => $_POST['option_b'],
					"option_c" => $_POST['option_c'],
					"option_d" => $_POST['option_d'],
					"option_e" => $_POST['option_e'],
					"answer" => $_POST['answer'],
					"explanation" => $_POST['explanation'],
					"question_tag" => $_POST['question_tag'],
					"cat_id" => $_POST['cat_id'],
					"source" => $_POST['source'],
					"story_id" => $_POST['story_id'],
					"chapter" => $_POST['chapter']
				];
				$r = updateTable("question", $data_array, array("qid" => $_POST['qid']));
				$data['qid'] = $_POST['qid'];
			else:
				unset($_POST['qid']);
				$r = insertTable("question", $_POST);
				$data['qid'] = $r;
				$_POST['qid'] = $r;
			endif;

			if ($r):

				if ($_POST['all']) {
					//					$array['msg']=$configs['success_msg']."<meta http-equiv=\"refresh\" content=\"3;URL='\" />";
					$array['msg'] = $configs['success_msg'];
				} else {
					//                    $array['msg'] = $configs['success_msg'] . "<meta http-equiv=\"refresh\" content=\"3;URL='\" />";
					$array['msg'] = $configs['success_msg'];

					//					$array['msg']=$configs['success_msg']."<meta http-equiv=\"refresh\" content=\"3;URL='?page=question-list&lesson_id=".$_POST['lesson_id']."&cat_id=".$_POST['cat_id']."'\" />";

				}
			endif;
			//print_r($array);
			echo json_encode($array);
			flush();
			break;

		case "add-free-question":

			$_POST['question'] = str_replace("\\", "&#92;", $_POST['question']);
			$_POST['question'] = str_replace("\"", "'", $_POST['question']);

			if ($_POST['explanation']) {

				$_POST['explanation'] = str_replace("\\", "&#92;", $_POST['explanation']);
				$_POST['explanation'] = str_replace("\"", "'", $_POST['explanation']);
			}

			if ($_POST['id']):
				$r = updateTable("questions_free", $_POST, array("id" => $_POST['id']));
				$data['id'] = $_POST['id'];
			else:
				unset($_POST['id']);
				$r = insertTable("questions_free", $_POST);
				$data['id'] = $r;
				$_POST['id'] = $r;
			endif;
			if ($r):

				deleteData("answers", array("question_id" => $_POST['id'], "free" => 1));
				deleteData("question_answer", array("question_id" => $_POST['id'], "free" => 1));

				for ($i = 1; $i <= 4; $i++) {
					$answer = "answer" . $i;

					$_POST[$answer] = str_replace("\\", "&#92;", $_POST[$answer]);
					$_POST[$answer] = str_replace("\"", "'", $_POST[$answer]);


					$r = insertTable("answers", array("question_id" => $_POST['id'], "answer" => htmlentities($_POST[$answer]), "answer_no" => $i, "free" => 1));
				}
				insertTable("question_answer", array("question_id" => $_POST['id'], "explanation" => htmlentities($_POST['explanation']), "answer_no" => $_POST['answer_no'], "free" => 1));

				$array['msg'] = $configs['success_msg'] . "<meta http-equiv=\"refresh\" content=\"3;URL='?page=question-free-list&cat_id=" . $_POST['cat_id'] . "&limit=" . $_POST['limit'] . "'\" />";


			endif;
			//print_r($array);
			echo json_encode($array);
			flush();
			break;
		case "login":

			$_POST['fp'] = isset($_POST['fp']) ? 1 : 0;
			$_POST['pt'] = isset($_POST['pt']) ? 1 : 0;

			if ($_POST['password']) {
				$r = updateTable("user", array("activation_status" => $_POST['status'], "password" => md5($_POST['password']), "fp" => $_POST['fp'], "pt" => $_POST['pt']), array("user_id" => $_POST['user_id']));
			} else {
				$r = updateTable("user", array("activation_status" => $_POST['status'], "fp" => $_POST['fp'], "pt" => $_POST['pt']), array("user_id" => $_POST['user_id']));
			}

			$old_expire_date = getFldValueById("subscription", "expire_date", array("user_id" => $_POST["user_id"]));

			if (empty($old_expire_date)) {
				$expire_date = date('Y-m-d', strtotime('+6 months'));
				updateTable("subscription", array("expire_date" => $expire_date), array("user_id" => $_POST['user_id']));
			}

			if ($r) {
				$array['msg'] = $configs['success_msg'];
			} else {
				$array['msg'] = $configs['error_msg'];
			}

			echo json_encode($array);

			flush();

			break;

		case "info":

			$flag1 = updateTable("subscription", array("expire_date" => $_POST['exp_date'], "course_id" => (int)$_POST['test_id']), array("user_id" => $_POST['user_id']));

			$flag2 = updateTable("user", array("first_name" => $_POST['first_name'], "last_name" => $_POST['last_name'], "mobile" => $_POST['mobile'], "activation_status" => $_POST['paid']), array("user_id" => $_POST['user_id']));

			if ($flag1 || $flag2) {
				$array['msg'] = $configs['success_msg'];
			} else {
				$array['msg'] = $configs['error_msg'];
			}

			echo json_encode($array);
			flush();

			break;

		case "add-tutor-login":

			if ($_POST['password']) {
				$_POST['password'] = md5($_POST['password']);
			}

			$exist = getTotalRow("login", array("email" => $_POST['email']));
			if ($exist) {
				$array['msg'] = "<font color=red>Email Id Exits!</font>";
			} else {
				$_POST['reg_date'] = date("Y-m-d");
				$_POST['last_login'] = date("Y-m-d");
				$_POST['status'] = "Y";
				$_POST['user_type'] = "T";
				$_POST['activation_key'] = mt_rand(100000, 999999);
				$_POST['activation_status'] = "Y";

				if ($_POST['user_id']) {
					updateTable("login", $_POST, array("user_id" => $_POST['user_id']));
				} else {
					unset($_POST['user_id']);
					$_POST['user_id'] = insertTable("login", $_POST);
					insertTable("tutor", $_POST, array("photo", "attachment"));
				}

				$error = mysqli_errno($configs['con']);
				if (!$error) {
					$array['msg'] = str_replace("{table}", "Tutor ", $configs['success_msg']);
					$array['next'] = $configs['vpath'] . $configs['admin_dir'] . "/tutor-reg?user_id=" . $_POST['user_id'];
				} else {
					$array['msg'] = "3" . $configs['error_msg'];
				}
			}
			echo json_encode($array);
			flush();
			break;
		case "tutor-info":

			if (!$_POST["password"]) {
				unset($_POST["password"]);
			}

			updateTable("tutor", $_POST, array("user_id" => $_POST['user_id']), array("photo", "attachment"));

			$error = mysqli_errno($configs['con']);
			if (!$error) {
				$array['msg'] = str_replace("{table}", "Tutor ", $configs['success_msg']);
			} else {
				$array['msg'] = $configs['error_msg'];
			}
			echo json_encode($array);
			flush();
			break;
		case "tutor-skill":

			// deleteTable("tutor_skill",array("user_id"=>$_POST['user_id']));

			for ($i = 0; $i < count($_POST['skill']); $i++) {
				insertTable("tutor_skill", array("skill_id" => $_POST['skill'][$i], "user_id" => $_POST['user_id']));
			}

			$error = mysqli_errno($configs['con']);
			if (!$error) {
				$array['msg'] = $configs['success_msg'];
			} else {
				$array['msg'] = $configs['error_msg'];
			}

			echo json_encode($array);
			flush();
			break;
		case "assign-tutor":

			deleteData("tutor_student", array("student_id" => $_POST['user_id']));

			for ($i = 0; $i < count($_POST['tutor']); $i++) {
				insertTable("tutor_student", array("tutor_id" => $_POST['tutor'][$i], "student_id" => $_POST['user_id']));
			}
			$error = mysqli_errno($configs['con']);

			if (!$error) {
				$array['msg'] = $configs['success_msg'];
			} else {
				$array['msg'] = $configs['error_msg'];
			}

			$array['msg'] = str_replace("{table}", "Tutor student ", $array['msg']);
			echo json_encode($array);
			flush();
			break;
		case "payment-update":

			//			$_POST['status']="Y";
			//			$_POST['payment_method']="Paypal";
			$r = insertTable("referrer_payout", $_POST);

			$array['msg'] = str_replace("{table}", "Payout", $configs['success_msg']);

			echo json_encode($array);
			flush();
			break;
		case "paypal-update":
			updateTable("user", array("paypal_id" => $_POST['paypal_id']), array("user_id" => $_POST['user_id']));
			$array['msg'] = str_replace("{table}", "Paypal", $configs['success_msg']);
			echo json_encode($array);
			flush();
			break;
		case "add-guest":

			if ($_POST['user_id']) {
				if (!$_POST['password']) {
					unset($_POST['password']);
				}

				updateTable("login", $_POST, array("user_id" => $_POST['user_id']));
				$r = updateTable("guest", $_POST, array("user_id" => $_POST['user_id']));
			} else {
				unset($_POST['user_id']);
				$exist = getTotalRow("login", array("email" => $_POST['email']));
				$exist2 = getTotalRow("login", array("code" => $_POST['code']));
				if ($exist) {
					$array['msg'] = "<font color=red>Email Exists!</font>";
				} elseif ($exist2) {
					$array['msg'] = "<font color=red>Affiliate Code Exists!</font>";
				} else {
					$_POST['reg_date'] = date("Y-m-d");

					$_POST['user_type'] = "G";
					$_POST['referrer'] = "Y";
					$_POST['reg_at'] = "web";


					$_POST['user_id'] = insertTable("login", $_POST);
					if ($_POST['user_id']) {
						$r = insertTable("guest", $_POST);
					}
				}
			}
			if ($r) {

				$array['msg'] = "User are successfully registered as affiliate guest member.";
				$array['msg'] .= "<script>setTimeout(\"location.href = '" . $configs['admin_vpath'] . "guest-list';\",2500);</script>";
			} else {

				$array['msg'] = "<font color=red>Please Try different email id.</font>";
			}


			echo json_encode($array);
			flush();


			break;
	}

	//print_r($array);




}

if ($_POST['set_update']) {
	for ($i = 0; $i < count($_POST['ids']); $i++) {

		$r = insertTable("questions_set_questions", array("set_id" => $_POST['set_id'], "question_id" => $_POST['ids'][$i]));
	}

	if ($r) {
		echo $configs['success_msg'];
	} else {
		echo $configs['error_msg'];
	}
}

if ($_POST['questions_set']) {



	if ($_POST['id']) {


		$r = updateTable("questions_set", $_POST, array("id" => $_POST['id']), array(""));
	} else {
		unset($_POST['id']);
		$r = insertTable("questions_set", $_POST);
	}
	if ($r) {
		echo $configs['success_msg'];
	} else {
		echo $configs['error_msg'];
	}
}

if ($_POST['questions_set_form']  && $_POST['delete']) {

	$r = deleteData("questions_set", array("id" => $_POST['id']));
	echo $configs['delete_msg'];
}
if ($_POST['questions_form']) {
	unset($_POST['questions_form']);
	unset($_POST['submit']);


	$query = array("questions" => htmlentities($_POST['questions']), "set_id" => $_POST['set_id'], "type" => $_POST['type'], "explanations" => htmlentities($_POST['explanations']), "course_id" => $_POST['course_id'], "sub_id" => $_POST['sub_id'], "chapter_id" => $_POST['chapter_id'], "status" => $_POST['status'], "no_options" => $_POST['no_options']);



	if ($_POST['id']) {

		$r = updateTable("questions", $query, array("id" => $_POST['id']));
		$_POST['question_id'] = $_POST['id'];
	} else {

		$r = insertTable("questions", $query);
	}
	$error = mysqli_errno($configs['con']);
	if ($r && !$error) {
		$_POST['question_id'] = $r;


		deleteData("answers", array("question_id" => $_POST['question_id']));

		if ($_POST['type'] == "mcq") {

			for ($i = 0; $i < count($_POST['answer']); $i++) {
				if ($_POST['is_correct'][$i]) {
					$_POST['is_correct'][$i] = "Y";
					$_POST['percent_correct'][$i] = 100;
				} else {
					$_POST['is_correct'][$i] = 'N';
					$_POST['percent_correct'][$i] = 0;
				}
				$r = insertTable("answers", array("question_id" => $_POST['question_id'], "answer" => htmlentities($_POST['answer'][$i]), "is_correct" => $_POST['is_correct'][$i], "explanation" => htmlentities($_POST['explanation'][$i])));
			}
		}

		echo "<div class='row'><div class='col-md-6 pull-left'>" . $configs['success_msg'] . "</div>";
		echo "<div class='col-md-6 pull-right'><a href='questions_form.php?sub_id=" . $_POST['sub_id'] . "&chapter_id=" . $_POST['chapter_id'] . "&course_id=" . $_POST['course_id'] . "' class='btn btn-success'>Add New Question</a></div></div>";
	} else {
		echo $configs['error_msg'];
	}
}

if ($_POST['questions_form']  && $_POST['delete']) {

	$r = deleteData("questions", array("id" => $_POST['id']));
	echo $configs['delete_msg'];
}
if ($_POST['cat_id'] && $_POST['subcat']) {
	echo "<option value=''>Select Chapter...</option>";
	$sub = getQuery("exam_category", "*", array("parent_id" => $_POST['cat_id']), "name", '', 1);
	foreach ($sub as $s) {
		echo "<option value='" . $s['id'] . "'>" . $s['name'] . "</option>";
	}
}

if ($_POST['answer_fld'] && $_POST['nos']) {
	for ($i = 0; $i < $_POST['nos']; $i++) {

?>
		<div class='row form-group'>
			<div class='col-md-3'><label class='form-control-label'>Answer <?= ($i + 1); ?></label></div>
			<div class='col-md-9'><textarea class='form-control' name='answer[]' REQUIRED></textarea>
				Mark This Correct</b> <input type='radio' name='is_correct_" . $i ."' value='Y'>

				Need Explanation ? <input type='checkbox' onClick="toggle('expl<?= ($i + 1); ?>');">
			</div>
		</div>

		<div class='row form-group' id='expl<?= ($i + 1); ?>' style='display:none'>
			<div class='col-md-3'><label class='form-control-label'></label></div>
			<div class='col-md-9'><textarea placeholder="Explanation of Answer <?= ($i + 1); ?>" class='form-control description' name='explanation[]'></textarea>
			</div>
		</div>
<?
	}
}

if ($_POST['get_subject'] && $_POST['course_id']) {

	$row = getQuery("course_subject", "*", array("course_id" => $_POST['course_id']));
	if (count($row)) {
		echo "<option selected='selected'>Select Subject</option>";
		foreach ($row as $d) {

			echo "<option value='" . $d['sub_id'] . "'>" . $d['subject'] . "</option>";
		}
	}
}

if ($_POST['get_chapter'] && $_POST['sub_id']) {

	$row = getQuery("chapter", "*", array("sub_id" => $_POST['sub_id']));
	if (count($row)) {
		echo "<option selected='selected'>Select Chapter</option>";
		foreach ($row as $d) {

			echo "<option value='" . $d['id'] . "'>" . $d['name'] . "</option>";
		}
	}
}
?>