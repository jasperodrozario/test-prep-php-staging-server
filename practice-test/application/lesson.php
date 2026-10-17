<?php
include("../configs/autoload.php");
chklogin(str_replace("/practice-test/", "", $_SERVER['REQUEST_URI']));

if ($_SESSION['status'] == "N") {
	header("location:" . $configs['vpath'] . "renew-subscription");
	exit();
}

if ($_SERVER['REQUEST_URI'] == "/practice-test/lesson/84-246-lesson-1") {

	header("Location: " . $configs['vpath'] . "lesson/84-246-lesson-1/homework");
	exit;
}

$option_no = 5;

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

$data['option_no'] = $option_no;

$query = array("cat_id" => $_GET['cat_id']);
$jquery = [];

$data['has_study'] = getTotalRow("lesson_study", array("cat_id" => $_GET['cat_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']));

if (!$_GET['in']) {
	if ($data['has_study']) {
		$_GET['in'] = "study";
	} else {
		$_GET['in'] = "homework";
	}
}

if ($_GET['lesson_id']) {
	$data['db_value'] = getDataByWhere("lesson", "*", array("lesson_id" => $_GET['lesson_id']));

	$query = array_merge($query, array("lesson_id" => $_GET['lesson_id']));
}

$data['category'] = getDataByWhere("category", "*", array("cat_id" => $_GET['cat_id']));

$data['cat_name'] = $data['category']['category_name'];

$data['video_exists'] = (int)getTotalRow("file", array("file_type" => "v", "lesson_id" => $_GET['lesson_id']));

switch ($_GET['in']) {
	case "video":

		$data['video_list'] = getQuery("file", "*", array("file_type" => "v", "lesson_id" => $_GET['lesson_id']), 'pos');

		break;

	case "study":
		$lesson_study_list = getQuery("lesson_study", "*", array("cat_id" => $_GET['cat_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']));

		$data['total_study'] = getTotalRow("lesson_study", array("cat_id" => $_GET['cat_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']));

		$data['study_list'] = array();

		foreach ($lesson_study_list as $ls) {
			$data['study_list'][] = getDataByWhere("study", "*", array("study_id" => $ls['study_id']));
		}

		break;

	case "homework":

		//********** start bookmark feature *********
		$bookmarked_list = getQuery("homework_bookmark", "*", array("user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id']), "ord");
		$data['bookmarked_list'] = $bookmarked_list;
		$data['bookmarked_questions'] = [];
		$data['bookmarked_stories'] = []; // This will store question_id => story_id mapping

		foreach ($bookmarked_list as $b) {
			$data['bookmarked_questions'][] = $b['question_id'];
			$story_id = getFldValueById("question", "story_id", array("qid" => $b['question_id']));

			if ($story_id) {
				$data['bookmarked_stories'][$b['question_id']] = $story_id;
				// Also store the order of the story
				$story_ord = getFldValueById("homework_story", "ord", ["story_id" => $story_id, "lesson_id" => $_GET['lesson_id']]);
				$data['bookmarked_story_orders'][$b['question_id']] = $story_ord;
			}
		}
		//********** end bookmark feature *********

		$data['homework_total'] = getTotalRow("homework_view", array("user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']));
		$_GET['home_limit_sr'] = 0;
		if ($data['homework_total']) {
			$home_list = [];
			$home_list_ord = [];
			$homelist = getQuery("homework_view", "*", array("user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']));
			foreach ($homelist as $d) {
				$home_list[] = $d['question_id'];
				$home_list_ord[] = $d['ord'];
			}
			$data['home_list'] = $home_list;
			$data['home_list_ord'] = $home_list_ord;
		}

		//        print_r($home_list);
		//        print_r($home_list_ord);

		$qtype = ['l.HW' => 1];

		if ($_GET['cat_id'] && $_GET['lesson_id']) {

			if (in_array($_GET['cat_id'], $data['story_question'])) {
				$data['story_total'] = getTotalRowsByJoin(array("lesson_questions as l", "question as q"), array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.HW" => 1, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), '', 'q.story_id');


				$ord = getFldValueById("homework_view", "max(ord)", ["lesson_id" => $_GET['lesson_id'], "user_id" => $_SESSION['user_id']]);


				$exist_story_table = getTotalRow("homework_story", ["lesson_id" => $_GET['lesson_id']]);


				if (!$exist_story_table) {
					$story_list = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.lesson_id" => $_GET['lesson_id'], "l.HW" => 1, "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), "", '', '', "q.story_id");

					$h = 0;
					foreach ($story_list as $d) {
						$h++;
						$qno = getTotalRowsByJoin(array("lesson_questions as l", "question as q"), array("q.story_id" => $d['story_id'], "l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.HW" => 1, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"));

						insertTable("homework_story", ["story_id" => $d['story_id'], "qno" => $qno, "lesson_id" => $_GET['lesson_id'], "ord" => $h, "grade" => $_SESSION['test_id']]);
					}
				}
				// include qno so we can build Q1..Qn mapping to story ord
				$data['qlist'] = getQuery("homework_story", "story_id,ord,qno", array("lesson_id" => $_GET['lesson_id'], "grade" => $_SESSION['test_id']), "ord");

				// Build ranges: for each story (ord), which global Q numbers belong to it?
				$story_q_ranges = [];                // e.g. [ ['ord'=>1,'start'=>1,'end'=>4], ['ord'=>2,'start'=>5,'end'=>8], ... ]
				$story_q_start_for_ord = [];         // e.g. [1=>1, 2=>5, ...]  (first Q number for each story ord)
				$acc = 0;
				foreach ($data['qlist'] as $d) {
					$start = $acc + 1;
					$end   = $acc + (int)$d['qno'];
					$story_q_ranges[] = ['ord' => (int)$d['ord'], 'start' => $start, 'end' => $end, 'story_id' => (int)$d['story_id']];
					$story_q_start_for_ord[(int)$d['ord']] = $start;
					$acc = $end;
				}
				$data['story_q_ranges'] = $story_q_ranges;
				$data['story_q_start_for_ord'] = $story_q_start_for_ord;

				// --- Build mapping of qid -> global Q number (across ALL stories in order) ---
				$qid_to_global = [];
				$global_ord = 0;

				foreach ($data['qlist'] as $row) { // qlist = every story with ord & qno
					$sid  = (int)$row['story_id'];

					// get all questions for this story in correct order
					$story_questions = getQueryByJoin(
						array("question as q", "lesson_questions as l"),
						"q.qid,l.order_no",
						array(
							"l.lesson_id" => $_GET['lesson_id'],
							"l.cat_id"    => $_GET['cat_id'],
							"l.HW"        => 1,
							"l.course_id" => $_SESSION['test_id'],
							"q.story_id"  => $sid
						),
						array("l.qid" => "q.qid"),
						"l.order_no",
						"",
						"",
						"q.qid"
					);

					foreach ($story_questions as $sq) {
						$global_ord++;
						$qid_to_global[(int)$sq['qid']] = $global_ord; // e.g. qid 123 -> Q 17
					}
				}

				// --- Convert the user's viewed qids to global Q numbers ---
				$viewed_global_ord = [];
				if (!empty($data['home_list'])) {            // home_list = viewed question_id list you already build
					foreach ($data['home_list'] as $qid) {
						$qid = (int)$qid;
						if (isset($qid_to_global[$qid])) {
							$viewed_global_ord[] = $qid_to_global[$qid];
						}
					}
				}

				// expose to template
				$data['viewed_global_ord'] = $viewed_global_ord;

				$storyok = [];
				foreach ($data['qlist'] as $d) {
					$tn = getTotalRow("homework_view", array("story_id" => $d['story_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']));

					$tq = getTotalRowsByJoin(array("lesson_questions as l", "question as q"), array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.HW" => 1, "l.course_id" => $_SESSION['test_id'], "q.story_id" => $d['story_id']), array("q.qid" => "l.qid"));

					if ($tn == $tq) {
						$storyok[] = $d['story_id'];
					}
				}
				$data['storyok'] = $storyok;


				$data['total'] = getTotalRowsByJoin(array("lesson_questions as l", "question as q"), array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.HW" => 1, "l.course_id" => $_SESSION['test_id'], "q.story_id >" => 0), array("q.qid" => "l.qid"));

				//echo $data['story_total'] . "|" . $ord;

				if (!$_GET['home_limit']) {
					if ($ord && $data['story_total'] > $ord) {
						if ($_GET['home_limit'] > 1) {
							$data['story_list'] = getQuery("homework_story", "story_id,ord", array("lesson_id" => $_GET['lesson_id'], "ord" => $_GET['home_limit'], "grade" => $_SESSION['test_id']));

							$_GET['home_limit_sr'] = (int)getFldValueById("homework_story", "sum(qno)", ["lesson_id" => $_GET['lesson_id'], "ord < " => $_GET['home_limit'], "grade" => $_SESSION['test_id']]);
						} else {
							$data['story_list'] = getQuery("homework_story", "story_id,ord", array("lesson_id" => $_GET['lesson_id'], "ord" => ($ord + 1), "grade" => $_SESSION['test_id']));

							$_GET['home_limit_sr'] = (int)getFldValueById("homework_story", "sum(qno)", ["lesson_id" => $_GET['lesson_id'], "ord < " => $ord, "grade" => $_SESSION['test_id']]);

							$_GET['home_limit'] = $ord;
						}
					}
				} 
				else {
					$data['story_list'] = getQuery("homework_story", "story_id,ord", array("lesson_id" => $_GET['lesson_id'], "ord" => $_GET['home_limit'], "grade" => $_SESSION['test_id']));
					$_GET['home_limit_sr'] = (int)getFldValueById("homework_story", "sum(qno)", ["lesson_id" => $_GET['lesson_id'], "ord < " => $_GET['home_limit'], "grade" => $_SESSION['test_id']]);
				}
				$data['testing'] = $_GET['home_limit'];
				$story = array();
				$homework_list = [];

				foreach ($data['story_list'] as $a) {
					$story[$a['story_id']] = getFldValueById("story", "story", array("story_id" => $a['story_id']));

					$homework_list[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_GET['lesson_id'], "l.HW" => 1, "l.cat_id" => $_GET['cat_id'], "q.story_id" => $a['story_id']), array("l.qid" => "q.qid"), "l.order_no", '', '', "q.qid");
				}
				$data['story'] = $story;
				$data['homework_list'] = $homework_list;

				$data['homework_paging'] = homework_paging($data['story_total'], (int)$_GET['home_limit'], $_GET['cat_id'], $_GET['lesson_id'], $_GET['name']);
			} 
			else {
				if ($_GET['cat_id'] == -93) {
					$data['home_list'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array("q.lesson_id" => (int)$_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.qtype" => 2, "q.status" => "Y"), array("qa.question_id" => "q.id"), 'q.id');

					//echo getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),'q.id','',1);
					$data['total'] = getTotalRowsByJoin(array("questions as q", "question_answer as qa"), array("q.lesson_id" => (int)$_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.qtype" => 2, "q.status" => "Y"), array("qa.question_id" => "q.id"));


					if (!$data['total']) {
						$qtype = [];
						$data['home_list'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array("q.lesson_id" => (int)$_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y"), array("qa.question_id" => "q.id"), 'q.id');
						// echo getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.status"=>"Y"),array("qa.question_id"=>"q.id"),'q.id','',1);
						$data['total'] = getTotalRowsByJoin(array("questions as q", "question_answer as qa"), array("q.lesson_id" => (int)$_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y"), array("qa.question_id" => "q.id"));
					}

					//  if($data['total']>30){$data['total']=30;}

					if ($data['homework_total']) {
						if (!substr_count($_SERVER["QUERY_STRING"], "home_limit")) {
							if ($data['homework_total'] == $data['total']) {
								$_GET['home_limit'] = 1;
								$data['homework_list'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array_merge($qtype, array("q.lesson_id" => $_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y")), array("qa.question_id" => "q.id"), "q.id", "1", "", "q.id");
							} else {
								$last_question_id = getFldValueById("homework_view", "max(question_id)", ["user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id']]);

								$_GET['home_limit'] = 1 + getFldValueById("homework_view", "ord", ["question_id" => $last_question_id, "user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id']]);

								$data['homework_list'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array_merge($qtype, array("q.lesson_id" => $_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y", "q.id >" => $last_question_id)), array("qa.question_id" => "q.id"), "q.id", "1", "", "q.id");

								//echo getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array_merge($qtype,array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.status"=>"Y","q.id >"=>$last_question_id)),array("qa.question_id"=>"q.id"),"q.id","1","1","q.id");

								$exist = getTotalRowsByJoin(array("questions as q", "question_answer as qa"), array_merge($qtype, array("q.lesson_id" => $_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y", "q.id >" => $last_question_id)), array("qa.question_id" => "q.id"));
								if (!$exist) {
									$_GET['home_limit'] = 1;
									$data['homework_list'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array_merge($qtype, array("q.lesson_id" => $_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y")), array("qa.question_id" => "q.id"), "q.id", "1", "", "q.id");
								}
							}
						} else {
							$data['homework_list'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array_merge($qtype, array("q.lesson_id" => $_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y")), array("qa.question_id" => "q.id"), "q.id", $configs['home_limit'], "", "q.id");
						}
					} else {
						$data['homework_list'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array_merge($qtype, array("q.lesson_id" => $_GET['lesson_id'], "q.cat_id in " => "(47,57)", "q.status" => "Y")), array("qa.question_id" => "q.id"), "q.id", $configs['home_limit'], "", "q.id");
					}
				} else {
					$data['home_list'] = getQueryByJoin(array("lesson_questions as l", "question as q"), "q.*,l.*", array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.HW" => 1, "course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), 'l.order_no');

					$data['total'] = getTotalRowsByJoin(array("lesson_questions as l", "question as q"), array("l.lesson_id" => (int)$_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.HW" => 1, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"));

					if ($data['homework_total']) {

						if (!substr_count($_SERVER["QUERY_STRING"], "home_limit")) {

							if ($data['homework_total'] == $data['total']) {
								$_GET['home_limit'] = 1;

								$data['homework_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], 'l.HW' => 1, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), "l.order_no", "1", "", "q.qid");
							} else {
								$last_question_ord = getFldValueById("homework_view", "max(ord)", ["user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']]);

								$_GET['home_limit'] = 1 + getFldValueById("homework_view", "ord", ["ord" => $last_question_ord, "user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']]);

								$data['homework_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array_merge($qtype, array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.order_no >" => $last_question_ord, "l.course_id" => $_SESSION['test_id'])), array("l.qid" => "q.qid"), "l.order_no", "1", "", "q.qid");

								$exist = getTotalRowsByJoin(array("question as q", "lesson_questions as l"), array_merge($qtype, array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.order_no >" => $last_question_ord, "l.course_id" => $_SESSION['test_id'])), array("q.qid" => "l.qid"));

								if (!$exist) {
									$_GET['home_limit'] = 1;

									$data['homework_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array_merge($qtype, array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'])), array("l.qid" => "q.qid"), "l.order_no", "1", "", "q.qid");
								}
							}


							$flag = 1;
						} else {
							$data['homework_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array_merge($qtype, array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'])), array("q.qid" => "l.qid"), "l.order_no", $configs['home_limit'], "", "q.qid");
						}
					} else {
						$data['homework_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "l.HW" => 1), array("l.qid" => "q.qid"), "l.order_no", $configs['home_limit'], "", "l.qid");
						$flag = 3;
					}
				}

				$data['homework_paging'] = homework_paging($data['total'], (int)$_GET['home_limit'], $_GET['cat_id'], $_GET['lesson_id'], $_GET['name']);
			}
		} 
		// else {

		// 	echo  "lesson id missing";
		// 	exit;
		// }


		// if (!in_array($_GET['cat_id'], $data['story_question'])) {
		// 	$viewed_homelist = getQuery("homework_view", "*", array("user_id" => $_SESSION['user_id'], "lesson_id" => $_GET['lesson_id'], "course_id" => $_SESSION['test_id']));

		// 	$home_alist = [];
		// 	foreach ($viewed_homelist as $a) {
		// 		$home_alist[] = $a['question_id'];
		// 	}

		// 	$data['home_alist'] = $home_alist;
		// }

		// $data['pending'] = $data['total'] - $data['homework_total'];
		// $green = round(($data['homework_total'] / $data['total']) * 100);
		// $yellow = round(($data['pending'] / $data['total']) * 100);
		// $data['style'] = '';


		// if ($data['homework_total'] &&  $data['pending']) {
		// 	$data['msg'] = "<table class='table'><tr><td style='background-color:green;color:#fff' width='" . $green . "%'>" . $data['homework_total'] . "</td><td style='background-color:yellow;color:#111' width='" . $yellow . "%'>" . $data['pending'] . "</td></tr></table>";
		// }
		// if ($data['total'] && $data['homework_total'] >= $data['total'] && !$data['pending']) {
		// 	$data['msg'] = "<table class='table'><tr><td style='background-color:green;color:#fff' width='" . $green . "%'>All checked.</td></tr></table>";
		// 	$data['style'] = "style='color:green'";
		// }
		// if ($_GET['home_limit'] > $data['total']) {
		// 	$_GET['home_limit'] = $data['total'];
		// }

		break;

	case "practice-test":

		if ($_GET['action'] == "freshtest") {
			$result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

			deleteData($exam_table, array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

			deleteData("result", array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));
		}


		if ($_GET['cat_id'] && $_GET['lesson_id']) {
			$has_pt_questions = getTotalRow("lesson_questions", array("cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "pt" => 1, "course_id" => $_SESSION['test_id']));

			if ($has_pt_questions) {
				$pt_hw = 'pt';
			} else {
				$pt_hw = 'HW';
			}

			if (in_array($_GET['cat_id'], $data['story_question'])) {
				$story_total = (int)getFldValueById("category", "num_of_story_pt", array("cat_id" => $_GET['cat_id'], "course_id" => $_SESSION['test_id']));

				if (!$story_total) {
					$story_total = 4;
				}

				if ($story_total) {
					$qno = 4;
					$order = "rand()";

					$data['total'] = getFldValueById("category", "num_of_ques_pt", array("cat_id" => $_GET['cat_id'], "course_id" => $_SESSION['test_id']));
					$data['total_questions'] = $data['total'];

					if (!$data['total']) {
						$data['total'] = 20;
					}


					$qset = 0;
					$qset = getTotalRow($exam_table, array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

					if ($_GET['cat_id']) {
						if ($_GET['action'] == "continue" || $qset) {
							//                            echo "if condition<br>";

							$data['story_list'] = getQueryByJoin(array("question as q", "$exam_table as e", "lesson_questions as l"), "q.story_id", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "l.$pt_hw" => 1, "e.user_id" => $_SESSION['user_id'], "e.is_result" => 0), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "q.story_id", $story_total, "", "q.story_id");
						} else {
							//                            echo "else condition<br>";

							$data['story_list'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.story_id", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "l.$pt_hw" => 1), array("q.qid" => "l.qid"), "q.story_id", $story_total, "", "q.story_id");
						}
					}

					$story = array();
					$test_questions = [];
					$total = 0;

					$result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

					$result_exist = getFldValueById("result", "result_id", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));

					//                    echo "result_id: " . $result_exist . "<br>";

					if (!$result_exist) {
						$result_id = insertTable("result", ["user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => $_GET['lesson_id'], "total_questions" => $data['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1]);
					}

					foreach ($data['story_list'] as $a) {
						//                        echo $a['story_id'] . "<br>";

						$story[$a['story_id']] = getFldValueById("story", "story", array("story_id" => $a['story_id']));

						$has_order_no = getTotalRowsByJoin(array("question as q", "lesson_questions as l"), array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $_SESSION['test_id'], "q.story_id" => $a['story_id'], "l.order_no>" => 0), array("q.qid" => "l.qid"));
						//                        echo "Has order no: " . $has_order_no . "<br>";

						if ($has_order_no) {
							$order_by = 'l.order_no';
						} else {
							$order_by = "q.qid";
						}

						if ($_GET['action'] == "continue" || $qset) {
							//                            echo "from continue";

							$test_questions[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_table as e"), "q.*,l.*", array("e.lesson_id" => $_GET['lesson_id'], "e.cat_id" => $_GET['cat_id'], "l.course_id" => $_SESSION['test_id'], "e.user_id" => $_SESSION['user_id'], "l.$pt_hw" => 1, "e.is_result" => 0, "q.story_id" => $a['story_id']), array("q.qid" => "l.qid", "l.qid" => "e.qid"), $order_by, '', '', "q.qid");
						} else {
							$test_questions[$a['story_id']] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $_SESSION['test_id'], "q.story_id" => $a['story_id']), array("q.qid" => "l.qid"), $order_by, '', '', "l.qid");
						}

						$exist_prev_ques = getTotalRowsByJoin(array("question as q", "$exam_table as e"), array("e.cat_id" => $_GET['cat_id'], "e.user_id" => $_SESSION['user_id'], "e.lesson_id" => (int)$_GET['lesson_id'], "q.story_id" => $a['story_id'], "is_result" => 0), array("q.qid" => "e.qid"));

						$ord = 0;
						foreach ($test_questions[$a['story_id']] as $d) {
							$total++;
							if ($_GET['action'] != "continue" && !$exist_prev_ques) {
								$ord++;
								insertTable($exam_table, ["user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "result_id" => $result_id, "lesson_id" => $_GET['lesson_id'], "qid" => $d['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0]);
							}
						}
					}
					if ($data['total'] > $total) {
						$data['total'] = $total;
					}

					$data['story'] = $story;
					$data['practicetest'] = $test_questions;
				}
			} else {

				$data['total'] = getFldValueById("category", "num_of_ques_pt", array("cat_id" => $_GET['cat_id'], "course_id" => $_SESSION['test_id']));
				if (!$data['total']) {
					$data['total'] = 15;
				}

				$data['total_questions'] = $data['total'];

				$_GET['in'] = "practice-test";

				$qset = 0;
				$qset = getTotalRow($exam_table, array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

				if ($_GET['action'] == "continue" && $qset) {
					$data['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_table as e"), "q.*,l.*", array("e.lesson_id" => $_GET['lesson_id'], "e.cat_id" => $_GET['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $_SESSION['test_id'], "e.user_id" => $_SESSION['user_id'], "e.is_result" => 0), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "e.id", $data['total'], "", "q.qid");
				} else {
					$data['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.lesson_id" => $_GET['lesson_id'], "l.cat_id" => $_GET['cat_id'], "l.$pt_hw" => 1, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), "l.order_no", $data['total'], "", "q.qid");
				}
			}
		} else {
			$qset = 0;

			$qset = getTotalRow($exam_table, array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

			if ($_GET['action'] == "continue" || $qset) {
				$data['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l", "$exam_table as e"), "q.*,l.*", array("l.cat_id" => $_GET['cat_id'], "l.pt" => 1, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid", "e.qid" => "q.qid"), "e.id", $data['total']);
			} else {
				$data['practicetest'] = getQueryByJoin(array("question as q", "lesson_questions as l"), "q.*,l.*", array("l.cat_id" => $_GET['cat_id'], "l.pt" => 1, "l.course_id" => $_SESSION['test_id']), array("q.qid" => "l.qid"), "rand()", $data['total']);
			}
		}
		if (!in_array($_GET['cat_id'], $data['story_question'])) {
			$result_id = (int)getFldValueById($exam_table, "result_id", array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

			$result_exist = getFldValueById("result", "result_id", array("user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => (int)$_GET['lesson_id'], "result_id" => $result_id));

			if (!$result_exist) {
				$result_id = insertTable("result", ["user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "lesson_id" => $_GET['lesson_id'], "total_questions" => $data['total'], "result_date" => date('Y-m-d H:i:s'), "score" => -1]);
			}

			$exist_prev_ques = getTotalRow($exam_table, array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0));

			$ord = 0;
			$total = 0;
			foreach ($data['practicetest'] as $a) {
				$total++;
				if ($_GET['action'] != "continue" && !$exist_prev_ques) {
					$ord++;
					insertTable($exam_table, ["user_id" => $_SESSION['user_id'], "cat_id" => $_GET['cat_id'], "result_id" => $result_id, "lesson_id" => $_GET['lesson_id'], "qid" => $a['qid'], "exam_date" => date('Y-m-d H:i:s'), "is_result" => 0]);
				}
			}
			if ($data['total'] > $total) {
				$data['total'] = $total;
			}
		}

		$data['alert'] = "";
		$data['last_exam'] = [];

		$data["lastexam_total"] = getTotalRow($exam_table, array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "user_ans > " => 0, "is_result" => 0));

		if ($data["lastexam_total"]) {
			$lastexam_list = getQuery($exam_table, "*", array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "is_result" => 0, "user_ans>" => 0));

			//echo getQuery("running_exam","*",array("cat_id"=>$_GET['cat_id'],"test_id"=>$_SESSION['test_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"report_id"=>0),"","",1);

			$lastexam = [];
			$last_ans = [];

			foreach ($lastexam_list as $d) {
				$lastexam[] = $d['qid'];
				$last_ans[$d['qid']] = $d['user_ans'];
			}

			if ($_GET['cat_id']) {
				$_POST['cat_id'] = $_GET['cat_id'];
			}
			if ($_GET['lesson_id']) {
				$_POST['lesson_id'] = $_GET['lesson_id'];
			}

			if (!$category_name) {
				$category_name = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id']));
			}

			$data['lastexam'] = $lastexam;
			$data['last_ans'] = $last_ans;
			if ($data['total']) {
				$url = $configs['vpath'] . "exam/result/"
					. $_POST['cat_id'] . "-" . seo_url($category_name) . "/"
					. $_POST['lesson_id'] . "/"
					. $result_id;

				$data['submit'] = "<div class=\"btn btn-success practice-test-submit text-center\" 
									onClick=\"window.location='{$url}'\">Submit</div>";
			}


			$data['alert'] = "<center><h4>You already answered " . $data["lastexam_total"] . " question(s) from this lesson. </h4><br><a href='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/practice-test/continue' class='btn btn-success'>Continue ?</a> or <a href='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/practice-test/freshtest' class='btn btn-warning'>Start Over?</a></center>";
		}

		if ($_GET['action'] == "continue") {
			$data['alert'] = "";
		}

		break;

	case "final-test":

		if ($_GET['lesson_id'] && $_GET['cat_id']) {
			$data['term'] = getFldValueById("report", "max(term)", array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id'], "test_id" => $_SESSION['test_id']));
			deleteData("running_exam", array("cat_id" => $_GET['cat_id'], "term" => ((int)$data['term'] + 1), "user_id" => $_SESSION['user_id'], "lesson_id" => (int)$_GET['lesson_id']));
		} else {
			$data['term'] = getFldValueById("report", "max(term)", array("cat_id" => $_GET['cat_id'], "user_id" => $_SESSION['user_id'], "test_id" => $_SESSION['test_id']));
			deleteData("running_exam", array("cat_id" => $_GET['cat_id'], "term" => ((int)$data['term'] + 1), "user_id" => $_SESSION['user_id']));
		}


		$flag = 0;

		if ($_GET['lesson_id'] && $_GET['cat_id']) {
			$data['total'] = getTotalRowsByJoin(array("questions as q", "question_answer as qa"), array("q.lesson_id" => $_GET['lesson_id'], "q.status" => "Y"), array("qa.question_id" => "q.id"));
		} else {
			$data['total'] = getTotalRowsByJoin(array("questions as q", "question_answer as qa"), array("q.cat_id" => $_GET['cat_id'], "q.status" => "Y"), array("qa.question_id" => "q.id"));
		}

		if ($data['total'] > 30) {
			$data['total'] = 30;
		}



		if ($_GET['lesson_id'] && $_GET['cat_id']) {
			$data['final_test'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array("q.lesson_id" => $_GET['lesson_id'], "q.status" => "Y"), array("qa.question_id" => "q.id"), "rand()", $data['total']);
		} else {
			$data['final_test'] = getQueryByJoin(array("questions as q", "question_answer as qa"), "q.*,qa.*", array("q.cat_id" => $_GET['cat_id'], "q.status" => "Y"), array("qa.question_id" => "q.id"), "rand()", $data['total']);
		}

		$answers = array();

		foreach ($data['final_test'] as $a) {
			$answers[$a['question_id']] = getQuery("answers", "*", array("question_id" => $a['question_id']), "id", $option_no);
		}

		$data['answers'] = $answers;


		break;
}


$data['timestamp'] = time() + (60 * 60);

//$data['question_list']=getQuery("questions","*",array("cat_id"=>$data['db_value']['cat_id']),"id",15);


echo template($data);
