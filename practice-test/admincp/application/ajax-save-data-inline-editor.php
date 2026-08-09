<?php
include("../common/access.php");
include("../common/autoload.php");

if ($_SERVER['HTTP_HOST'] != "localhost") {
	include('../smtpmail.php');
}

if ($_POST['action']) {
	$array = array();

	switch ($_POST['action']) {
			
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
			
			case "getExplanationByQuestionId":
			if (isset($_POST['qid'])) {
				$qid = intval($_POST['qid']);
				$result = getDataByWhere("question", "*", array("qid" => $qid));

				echo json_encode([
					'status' => 'success',
					'explanation' => html_entity_decode(html_entity_decode($result['explanation']))
				]);
			
			} else {
				echo json_encode([
					'status' => 'error',
					'message' => 'No question ID provided.'
				]);
			}
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

	}


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