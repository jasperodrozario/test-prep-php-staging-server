 <!--courses section-->
 <div class='clearfix'></div>
 <div class="userprofile_wrapper">
 	<div class="container edu-body">
 		<div class="userprofile-body midle-conarea mr-t88">
 			<div class="col-lg-3 col-md-3 col-sm-3 cust-side edu-left">
 				<? include("../template/" . $configs['template'] . "/includes/tmpl_left_exam.php"); ?>
 			</div>
 			<div class="col-lg-9 col-md-9 col-sm-9 edu-right">

 				<div class="panel dashbord-panel with-nav-tabs panel-default">
 					<div id="myHeader">
 						<!--<div class="panel-heading">
							<?= $cat_name; ?><? if ($_GET['lesson_id']) {
													echo " > " . $db_value['Lesson_name'];
												} ?>
						</div> -->

 						<? if ($_GET['lesson_id']) {
								$val = $_GET['lesson_id'] . "-" . seo_url($db_value['Lesson_name']);
							} else {
								$val = seo_url($cat_name);
								$db_value['Lesson_name'] = $cat_name;
							} ?>
 						<div class="mob-tab-menu lesson-tab-menu">
 							<ul class="nav nav-tabs">
 								<!-- creating the study, lesson video, Home work and Practice Test tabs -->
 								<? if ($has_study) { ?>
 									<li <? if ($_GET['in'] == "study") {
												echo 'class="active"';
											} ?>><a href="<?= $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $val . "/study"; ?>">Study</a></li>
 								<?
									}
									if ($video_exists) {
									?>
 									<li <? if ($_GET['in'] == "video") {
												echo 'class="active"';
											} ?>><a href="<?= $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $val . "/video"; ?>" class='total'>Lesson Video</a></li>
 								<?
									}
									?>

 								<li <? if ($_GET['in'] == "homework") {
											echo 'class="active"';
										} ?>><a href="<?= $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $val . "/homework"; ?>">Home Work</a></li>

 								<li <? if ($_GET['in'] == "practice-test") {
											echo 'class="active"';
										} ?>><a href="<?= $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $val . "/practice-test"; ?>" class='total'>Practice Test</a></li>
 							</ul>
 						</div>


 						<!-- Controls below the tabs -->
 						<div class="lesson-controls">
 							<?php
								// creating the study, lesson video, Homework and Practice Test controls
								if (
									isset($_GET['in']) &&
									$_GET['in'] === "practice-test" &&
									$_GET['in'] !== 'homework' &&
									$_GET['in'] !== 'study' &&
									$_GET['in'] !== 'video'
								) { ?>
 								<!-- Practice Test: show timer only -->
 								<div id="test-header">
 									<div id="timer">
 										<label id="minutes">00</label>m:<label id="seconds">00</label>s
 									</div>
 									<div id="answered-box">
 										<span id="answered">0</span>/<span id="totalq"><?= $total ?></span>
 									</div>
 								</div>

 								<?php
								} else {
									// Show controls when not study/video/practice-test (i.e., Homework)
									if (isset($_GET['in']) && $_GET['in'] !== 'study' && $_GET['in'] !== 'video' && $_GET['in'] !== 'practice-test') {
									?>
 									<div class="left-controls">
 										<!-- Primary question selector -->
 										<div class="ctrl">
 											<select name="qid" class="form-control" size="1" onChange="window.location=this.value;">
 												<?php
													if (isset($story_list) && $story_list) {
														// current story selection (keeps your existing behavior)
														$selected_ord = isset($_GET['home_limit']) ? (int)$_GET['home_limit'] : 1;
														$start_for_selected = isset($story_q_start_for_ord[$selected_ord])
															? (int)$story_q_start_for_ord[$selected_ord] : 1;

														// viewed global Q numbers from backend
														$viewed_global_ord = isset($viewed_global_ord) ? $viewed_global_ord : [];

														if (!empty($story_q_ranges)) {
															foreach ($story_q_ranges as $range) {
																$ord    = (int)$range['ord'];
																$startQ = (int)$range['start'];
																$endQ   = (int)$range['end'];

																for ($q = $startQ; $q <= $endQ; $q++) {
																	$selected = ($q === $start_for_selected) ? "selected" : "";
																	$chk = in_array($q, $viewed_global_ord, true) ? ": Viewed" : "";
																	echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $val . "/homework/" . $ord . "' $selected>Q " . $q . $chk . "</option>";
																}
															}
														}
													} else {
														$j = 0;
														if (!empty($home_list)) {
															foreach ($home_list as $d) {
																$j++;
																$chk = (!empty($home_list_ord) && in_array($j, $home_list_ord)) ? ": Viewed" : "";
																if (isset($_GET['home_limit']) && $_GET['home_limit'] == $j) {
																	echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/homework/" . $j . "' selected>Q " . $j  . $chk . "</option>";
																} else {
																	echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/homework/" . $j . "'>Q "  . $j . $chk . " </option>";
																}
															}
														}
													}
													?>
 											</select>
 										</div>

 										<?php if (empty($story_list)) { ?>
 											<!-- Bookmarked (non-story) -->
 											<div class="ctrl">
 												<select name="qid" class="form-control" size="1" onChange="window.location=this.value;">
 													<?php
														echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/homework/1' class='disabled-option'>Bookmarked Q</option>";
														if (!empty($bookmarked_list)) {
															foreach ($bookmarked_list as $d) {
																if (isset($_GET['home_limit']) && $_GET['home_limit'] == $d['ord']) {
																	echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/homework/" . $d['ord'] . "' selected>Q " . $d['ord'] . " </option>";
																} else {
																	echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/homework/" . $d['ord'] . "'>Q "  . $d['ord'] . "</option>";
																}
															}
														}
														?>
 												</select>
 											</div>
 										<?php } else { ?>
 											<!-- Bookmarked (story-aware) -->
 											<div class="ctrl">
 												<select name="qid" class="form-control" size="1" onChange="window.location=this.value;">
 													<option value="<?= $configs['vpath'] ?>lesson/<?= $_GET['cat_id'] ?>-<?= $_GET['lesson_id'] ?>-<?= $_GET['name'] ?>/homework/1" class="disabled-option">Bookmarked Q</option>
 													<?php
														if (!empty($bookmarked_list)) {
															foreach ($bookmarked_list as $d) {
																// Check if this question is part of a story
																if (
																	isset($bookmarked_stories[$d['question_id']]) &&
																	isset($bookmarked_story_orders[$d['question_id']])
																) {
																	// For story questions, link to the story's order number
																	$ord = $bookmarked_story_orders[$d['question_id']];
																	$selected = (isset($_GET['home_limit']) && $_GET['home_limit'] == $ord) ? 'selected' : '';
																	echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/homework/" . $ord . "' $selected>Q " . $d['ord'] . "</option>";
																} else {
																	// For regular questions
																	$selected = (isset($_GET['home_limit']) && $_GET['home_limit'] == $d['ord']) ? 'selected' : '';
																	echo "<option value='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] . "-" . $_GET['name'] . "/homework/" . $d['ord'] . "' $selected>Q " . $d['ord'] . "</option>";
																}
															}
														}
														?>
 												</select>
 											</div>
 										<?php } ?>
 									</div>
 							<?php
										/* ---------------- View count on Homework) ---------------- */
										if (isset($story_list) && $story_list) {
											$viewed_count = !empty($viewed_global_ord) ? count($viewed_global_ord) : 0;
										} else {
											$viewed_count = !empty($home_list_ord) ? count($home_list_ord) : 0;
										}
										$total = isset($total) ? $total : (isset($home_list) ? count($home_list) : 0);
										if (isset($_GET['in']) && $_GET['in'] === 'homework') {
											echo "<div id='total' style='font-weight:600;'>
												<span>" . $viewed_count . "</span>/<span>" . $total . "</span>
											</div>";
										}
									}
								}
								?>
 						</div>
 					</div>


 					<div class='clearfix'></div>

 					<div class="div-scroll panel-body edu-contant">
 						<div class="tab-content" style='padding-top:5px;padding-bottom:20px'>
 								<?
								$show_fp = (int)getFldValueById("user", "fp", array("user_id" => $_SESSION['user_id']));
								if (($show_fp == 1 && stripos($db_value['Lesson_name'], 'Final') == true) || (stripos($db_value['Lesson_name'], 'Final') == false)) {

									switch ($_GET['in']) {

										case "study":
								?>
							<div class="tab-heading">
								<h3><?= $db_value['Lesson_name'] ?> Study</h3>
							</div>
							<div class="study-dropdown">
								<?php
									$sr = 1;
									if (!$_GET['home_limit']) {
										$view_order = 1;
									} else {
										$view_order = $_GET['home_limit'];
									}

									foreach ($study_list as $s) {
										$isActive = ($view_order == $sr) ? "active" : "";
									?>
									<div class="dropdown-item">
										<button class="dropdown-btn study-dropdown-btn <?= $isActive ?>" data-target="content-<?= $sr ?>">
											<?= $s['study_title'] ?>
										</button>
										<div id="content-<?= $sr ?>" class="dropdown-content"
											style="<?= ($view_order == $sr) ? 'display:block;' : '' ?>">
											<?= html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($s['content'])))); ?>
										</div>
									</div>
								<?php
										$sr++;
									}
									?>
							</div>
 										<?
										break;

										case "video":
										?>
							<div class="tab-heading">


								<h3><?= $db_value['Lesson_name'] ?> Videos</h3>
							</div>


							<div class="video-list">
								<?php
												if (count($video_list)) {
													foreach ($video_list as $f) {
														$file = $f['file'];
														if (substr_count($file, "youtu.be")) {
															$e = end(explode("/", $file));
														} else {
															$e1 = explode("?v=", $file);
															$e = $e1[1];
														}
														$picture = "http://img.youtube.com/vi/" . $e . "/mqdefault.jpg";
									?>
										<div class="video-item" data-id="<?= $e ?>">
											<div class="video-wrapper">
												<div class="video-thumbnail">
													<img src="<?= $configs['vpath'] ?>image.php?src=<?= $picture ?>&w=600" alt="Video Thumbnail">
													<div class="play-icon">▶</div>
												</div>
											</div>
											<div class="video-details">
												<p class="video-title"><?= $f['title'] ?></p>
											</div>
										</div>
								<?php
													}
												} else {
													echo "<p>No video found.</p>";
												}
									?>
							</div>
 										<? 
										break;

										case "homework":
										?>
							<script type="text/javascript" src="//code.jquery.com/jquery-1.6.4.js"></script>

											<?php 
											if (stripos($cat_name, "Final Practice Test") !== false){
												?>
												<h3 id="homework-head"><?= $db_value['Lesson_name'] ?></h3>
												<?
											} else {
												?>
												<h3 id="homework-head"><?= $cat_name; ?> - <?= $db_value['Lesson_name'] ?></h3>	
												<?

											}
											?>

							

 											<?php
											$description = getFldValueById("category", "description", array("cat_id" => $_GET['cat_id']));
											if ($description) {
											?>
 											<div class='description-container'>
 												<?= html_entity_decode(str_replace("&#92;", "\\", $description)) ?>
 											</div>
 										<?php
											}
											?>
											<br>

 						

							<form method='post' id='form'>

								<div id="big-image" class="new-qs-slider homework-questions <? if ($_GET['cat_id'] == 18) { echo "wk"; } ?>">

 											<?

											if ($total) {
												$i = 1;
												if ($_GET['home_limit']) {
													$i = $_GET['home_limit'];
												}
												$var = "";

												if (!in_array($_GET['cat_id'], $story_question)) {

													foreach ($homework_list as $f) {

														$mark = "";
														if (in_array($f['qid'], $home_alist)) {
															$mark = "<img src='" . $configs['vpath'] . "img/on.png'>";
														}
									?>
 											<div class="row no-story">
 												<item id="slide<?= $i ?>">
 													<div class="col-md-12" <?php if ($_GET['cat_id'] == 18) {
																				echo 'style="margin-top:-40px"';
																			} ?>>
 														<table class="table table-borderless homework-table">
 															<tr class="item-ques question-row">
 																<td class="question_no">
 																	<span class="badge <?= $badge ?>" data-tooltip="<?= $f['qid'] ?>">
 																		<?= $_GET['home_limit'] ?>
 																	</span>
 																</td>
 																<td class="questions"><?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question'])))) ?></td>
 																<td class="bookmark-icon">
 																	<label class="bookmark-label">
 																		<input
 																			type="checkbox"
 																			class="bookmark-checkbox"
 																			data-id="<?= $f['qid'] ?>"
 																			data-ord="<?= $i ?>"
 																			story-id="<?= $story_id ?>"
 																			data-lesson-id="<?= $_GET['lesson_id'] ?>" <?php 
																			if (in_array($f['qid'], $bookmarked_questions)) {
																				echo " checked";
																			} ?>>
 																		<span class="bookmark-custom-checkbox"></span>
 																	</label>
 																</td>
 															</tr>

 															<p></p>

 															<?php
																$correct_ans = "";
																for ($j = 1; $j <= $option_no; $j++) {
																	$ans = $alphabet[$j];
																	$ind = 'option_' . strtolower($ans);
																	if ($f[$ind]) {
																		if ($f['answer'] == $j) {
																			$correct_ans = $alphabet[$j];
																		}
																?>
 																	<tr>
 																		<td class="abcd"><?= $ans ?></td>
 																		<td class="options" colspan="2"><?= html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind]))) ?></td>
 																	</tr>
 															<?php
																	}
																}
																?>

 															<tr class="text-center">
 																<td class="view_answer" colspan="3">
 																	<div class="col-md-12 view-ans" style="margin-bottom:00px">
 																		<?= $homework_paging['prev'] ?>
 																		<a id='view-answer' class="btn btn-info" href="javascript://" onClick="showDiv('<?= $i ?>','<?= $f['qid'] ?>','<?= $_GET['lesson_id'] ?>','<?= $total ?>');">View Answer</a>
 																		<?= $homework_paging['next'] ?>
 																	</div>

 																	<?php if (in_array($_GET['cat_id'], array(83, 86, 64))) { ?>
 																		<div class="col-md-12  mtq_explanation" id="ansdiv_<?= $i ?>" style="margin-left:15px;display:none;1px solid #d6d6d6;background:left top repeat-y #f0f0f0;padding-top:10px;margin-top:30px">
 																			<b>Answer : <?= $alphabet[$f['answer']] ?></b>
 																		<?php } else { ?>
 																			<div class="col-md-12  mtq_explanation explanation_nonstory" id="ansdiv_<?= $i ?>" style="margin-left:15px;display:none;1px solid #d6d6d6;background:left top repeat-y #f0f0f0;padding-top:10px;margin-top:30px">
 																				<b>Answer : <?= $correct_ans ?></b>
 																			<?php } ?>

 																			<?php if ($f['explanation']) { ?>
 																				<br><b>Explanation : </b><br><?= html_entity_decode(html_entity_decode($f['explanation'])) ?>
 																			<?php } ?>

 																			<div class="close-btn"><i class="fa fa-times"></i></div>
 																			</div>
 																</td>
 															</tr>
 														</table>
 													</div>
 												</item>
 											</div>
 										<?php
														$i++;
													}
												} else {
													$sr = 0;
													if ($_GET['home_limit_sr']) {
														$sr = $_GET['home_limit_sr'];
													}
													if ($_GET['home_limit']) {
														$sr = ($_GET['home_limit'] - 1) * 4;
													}

													foreach ($story_list as $d) {
														$story[$d['story_id']] = str_replace("&nbsp;", "", html_entity_decode($story[$d['story_id']]));
											?>
 											<div class="row with-story">
 												
 													<div class="col-md-12 custom-story-box">
 														<div class="story-con">
 															<?= html_entity_decode(html_entity_decode(html_entity_decode($story[$d['story_id']]))) ?>
 															<small style="color: #85929E; float: right;">[<?= $d['story_id'] ?>]</small>
 														</div>
 														<?php
															$sr = (int) getFldValueById(
																"homework_story",
																"sum(qno)",
																["lesson_id" => $_GET['lesson_id'], "ord < " => $d['ord'], "grade" => $_SESSION['test_id']]
															);

															foreach ($homework_list[$d['story_id']] as $f) {
																$sr++;
																$mark = "";
																if (in_array($f['qid'], $home_list)) {
																	$mark = "<img src='" . $configs['vpath'] . "img/on.png'>";
																}
															?>
															<div class="story-questions">
																<table class="table table-borderless homework-table">
																	<tr class="item-ques question-row">
																		<td class="question_no">
																			<span class="badge <?= $badge ?>" data-tooltip="<?= $f['qid'] ?>"><?= $sr ?></span>
																		</td>
																		<td class="questions"><?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question'])))) ?></td>
																		<td class="bookmark-icon">
																			<label class="bookmark-label">
																				<input
																					type="checkbox"
																					class="bookmark-checkbox"
																					data-id="<?= $f['qid'] ?>"
																					data-ord="<?= $sr ?>"
																					data-lesson-id="<?= $_GET['lesson_id'] ?>"
																					<?php if (in_array($f['qid'], $bookmarked_questions)) {
																							echo "checked";
																						} ?>>
																				<span class="bookmark-custom-checkbox"></span>
																			</label>
																			<!-- keep the old target for viewStoryAnswer but hide it -->
																			<span id="<?= $f['qid'] ?>_mark" style="display:none"></span>
																		</td>
																	</tr>
																	<?php
																		$correct_ans = "";
																		for ($j = 1; $j <= $option_no; $j++) {
																			$ans = $alphabet[$j];
																			$ind = 'option_' . strtolower($ans);
																			if ($f[$ind]) {
																				if ($f['answer'] == $j) {
																					$correct_ans = $alphabet[$j];
																				}
																		?>
																			<tr class="option-row">
																				<td class="abcd"><?= $ans ?></td>
																				<td class="options" colspan="2"><?= html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind]))) ?></td>
																			</tr>
																	<?php
																			}
																		}
																		?>
																	<tr>
																		<td  colspan="3" style="position:relative;">
																			<div class="col-md-12 raja mtq_explanation story-ans" id="ansdiv_<?= $f['qid'] ?>" style="margin-left:15px;display:none;1px solid #d6d6d6;background:left top repeat-y #f0f0f0;padding-top:10px;">
																				<b>Answer : <?= $alphabet[$f['answer']] ?></b>
																				<?php if ($f['explanation']) { ?>
																					<br><b>Explanation : </b><br><?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['explanation'])))) ?>
																				<?php } ?>
																				<div class="close-btn"><i class="fa fa-times"></i></div>
																			</div>
																		</td>
																	</tr>

																	<tr class="text-center">
																		<td class="view_answer" colspan="3">
																			<div class="col-md-12 view-ans" >
																				<a id='view-answer'
																					class="btn btn-info"
																					href="javascript://"
																					onClick="showDivStory('<?= $f['qid'] ?>','<?= $_GET['lesson_id'] ?>','<?= $total ?>','<?= $d['story_id'] ?>','<?= $d['order_no'] ?>');">View Answer</a>

																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															
 														<?php
															} // end foreach homework_list
															?>
															<div class="story-slicon">
 																<?= $homework_paging['prev'] ?> <?= $homework_paging['next'] ?>
 															</div>

 													
 													</div>
 													
 												
 											</div>
								</div>
 											<?php
													} 
												}
											} ?>
							</form>

 					
 											<?
											break;

										case "practice-test":
											?>
 							<h3 id="practice-test-head"><?= $cat_name; ?> - <?= $db_value['Lesson_name'] ?></h3>
 											<?php
											$description = getFldValueById("category", "description", array("cat_id" => $_GET['cat_id']));
											if ($description) { ?>
							<div class='description-container'>
									<?= html_entity_decode(str_replace("&#92;", "\\", $description)) ?>
							</div>
 												<?php } ?>
 					
 																	<?php
											if ($alert) {
												echo $alert;
											} else { ?>
 							<script type="text/javascript" src="https://code.jquery.com/jquery-1.6.4.js"></script>

 							<form method='post' id='form'>

								<input type='hidden' name='time' id='time'>

								<div id="big-image" class="practice-test-question-container">
									<div class="col-md-12 practice-test-box">
												<?php
													$i = 0;
													
													if (in_array($_GET['cat_id'], $story_question)) {
														foreach ($story_list as $d) { ?>
															

															<!-- NEW WRAPPER FOR STORY -->
															<div class="story-con">
																<?= html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($story[$d['story_id']])))) ?>
																<small style="color: #85929E; float: right;">[<?= $d['story_id'] ?>]</small><br>
															</div>
															<!-- END STORY WRAPPER -->

															<table class="table table-borderless practice_test_table">
														<?php
															foreach ($practicetest[$d['story_id']] as $f) {
															$i++;
															$last_answer = "";
															if ($lastexam_total && in_array($f['qid'], $lastexam)) {
																$last_answer = "<span class='badge-success'>Answer Saved</span>";
																$margine_bottom = 'style="margin-bottom: 0px;"';
															}
														?>
																<!-- Question row -->
															<tr class="question-row">
																<td class="question_no">
																	<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($f['qid']) ?>"><?= $i ?></span>
																</td>
																<td class="questions" colspan="2">
																	<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question'])))) ?>
																</td>
															</tr>
														<?php 
															for ($j = 1; $j <= $option_no; $j++) {
																$ans = $alphabet[$j];
																$ind = 'option_' . strtolower($ans);
																$chk = "";
																if ($f[$ind] || $_GET['cat_id'] == 84 || $_GET['cat_id'] == 63) {
																if ($j == $last_ans[$f['qid']]) {
																	$chk = "checked";
																}
														?>
																<tr class="option-row">
																<td class="abcd" ><?= $ans ?></td>
																<td class="radio-btn" >
																	<input type="radio" <?= $chk ?> name="answer<?= $i ?>" id="<?= $i ?>" onClick="getAnswer('<?= $f['qid'] ?>','<?= $j ?>');">
																</td>
																<td class="options" >
																	<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) ?>
																</td>
																</tr>
														<?php
																}
															}
														?>
																<tr>
																<td colspan="3">
																	<div id="response_<?= $f['qid'] ?>" class="response-text" <?= $margine_bottom ?? '' ?>><?= $last_answer ?></div>
																</td>
																</tr>
														<?php } // end questions ?>
															</table>
															<hr>
															
														<?php
														} // end foreach story_list
														} else {
														foreach ($practicetest as $f) {
															$i++;
															$badge = "";
															$last_answer = "";

															if ($lastexam_total && in_array($f['qid'], $lastexam)) {
																$last_answer = "<span class='badge-success'>Answer Saved</span>";
															}

															if ($f['user_ans']) {
																$badge = "badge-success";
															}
											?>
											<table class="table table-borderless practice_test_table">
												<tr class="question-row">
													<td class="question_no">
														<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($f['qid']) ?>"><?= $i ?></span>
													</td>
													<td class="questions" colspan="2">
														<?= html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question']))) ?>
													</td>
												</tr>
												<?php
															$correct_ans = "";
															if ($_GET['cat_id'] == 64) {
																$option_no = 4;
															}
															for ($j = 1; $j <= $option_no; $j++) {
																$ans = $alphabet[$j];
																$ind = 'option_' . strtolower($ans);
																$chk = "";
																if ($f[$ind] || in_array($_GET['cat_id'], array(83, 86, 64))) {
																	if ($j == $last_ans[$f['qid']]) {
																		$chk = "checked";
																	}
													?>
														<tr class="option-row">
															<td class="abcd"><?= $ans ?></td>
															<td class="radio-btn" >
																<input type="radio" <?= $chk ?> name="answer<?= $i ?>" id="<?= $i ?>" onClick="getAnswer('<?= $f['qid'] ?>','<?= $j ?>');">
															</td>
															<td class="options" >
																<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) ?>
															</td>
														</tr>
												<?php
																}
															}
													?>
												<tr>
													<td colspan="3">
														<div id="response_<?= $f['qid'] ?>" class="response-text"><?= $last_answer ?></div>
													</td>
												</tr>
											</table>
													<?php
														} // end foreach $practicetest
													} // end story vs non-story
														?>

										<div>
										<div class="sub-btn-test text-center total-qs">
											<div id='total'>
										<?php if($submit){   echo $submit; } ?>
												
											</div>
										</div>
										</div>
									</div>
								</div>
 							</form>
 											<?php
											} 
											break;
											} ?>
						</div>
					</div>
 				</div>
 								<?
								} else {
								?>
 				<div class="alert alert-warning" style="color: red; font-size: 16px; padding: 15px; margin-top: 20px;">
 					<strong>Access Denied:</strong> You do not have permission to access this section.
 					<a href="https://staging-test-prep.asvab-tutoring.com/contact-us" style="color: red; text-decoration: underline;">
 						Please contact your instructor for assistance.
 					</a>
 				</div>
 								<?
								}
								?>
 			</div>
 		</div>
 	</div>
 </div>
 </div>
 </div>
 </div>


 <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
 <script type="text/javascript" async
 	src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_SVG">
 </script>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
 	function getAnswer(x, y) {

 		$("#response_" + x).html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");
 		var time = $("#time").val();
 		$.ajax({
 				type: "POST",
 				dataType: "json",
 				url: vpath + "ajax-home",
 				data: "action=test&id=<?= $_GET['id']; ?>&total=<?= (int)$total; ?>&term=<?= (int)$term; ?>&cat_id=<?= (int)$_GET['cat_id']; ?>&lesson_id=<?= (int)$_GET['lesson_id']; ?>&question_id=" + x + "&answer_no=" + y + "&time=" + time

 			})
 			.done(function(data) {
 				//alert(data);
 				$("#response_" + x).html(data.msg);
 				$("#total").html(data.total);
 			})
 			.fail(function() {

 				// just in case posting your form failed
 				alert("Posting failed.");
 			});

 	}


 	$(".video-item").click(function() {
 		const videoId = $(this).data("id");
 		const wrapper = $(this).find(".video-wrapper");

 		// Stop all other videos
 		$(".video-wrapper").each(function() {
 			const originalThumb = $(this).data("thumb");
 			if (originalThumb) {
 				$(this).html(originalThumb);
 				$(this).removeData("thumb");
 			}
 		});

 		// Save current thumbnail before replacing
 		if (!wrapper.data("thumb")) {
 			wrapper.data("thumb", wrapper.html());
 		}

 		// Replace with iframe
 		wrapper.html(`
      <iframe width="100%" height="360"
        src="https://www.youtube.com/embed/${videoId}?autoplay=1"
        frameborder="0" allow="autoplay; encrypted-media"
        allowfullscreen></iframe>
    `);
 	});
 </script>

 <script>
 	$(document).ready(function() {
 		$("input[type=radio]").on("change", function() {
 			let answeredCount = $("input[type=radio]:checked").length;
 			$("#answered").text(answeredCount);
 		});

 		// Initial count (for pre-checked answers)
 		let initialCount = $("input[type=radio]:checked").length;
 		$("#answered").text(initialCount);
 	});

 	document.addEventListener("DOMContentLoaded", function() {
 		let buttons = document.querySelectorAll(".study-dropdown-btn");

 		buttons.forEach(btn => {
 			btn.addEventListener("click", function() {
 				let content = document.getElementById(this.dataset.target);

 				// Collapse if already open
 				if (content.style.display === "block") {
 					content.style.display = "none";
 					this.classList.remove("active");
 				} else {
 					// Close other open contents
 					document.querySelectorAll(".dropdown-content").forEach(c => c.style.display = "none");
 					document.querySelectorAll(".study-dropdown-btn").forEach(b => b.classList.remove("active"));

 					// Open current
 					content.style.display = "block";
 					this.classList.add("active");
 				}
 			});
 		});
 	});
 </script>


 <script>
 	(function() {
 		// local set to prevent double counting within this page view
 		const viewedOnce = new Set();

 		// Return the first (primary) question selector used for navigation
 		function getPrimarySelector() {
 			return document.querySelector(".lesson-controls .left-controls .ctrl select.form-control");
 		}

 		// Is the currently selected option already marked as viewed?
 		function currentOptionAlreadyViewed(sel) {
 			if (!sel || sel.selectedIndex < 0) return false;
 			return /:\s*Viewed/i.test(sel.options[sel.selectedIndex].text);
 		}

 		// Append ": Viewed" once to the current option
 		function markCurrentOptionViewed(sel) {
 			if (!sel || sel.selectedIndex < 0) return;
 			const opt = sel.options[sel.selectedIndex];
 			if (!/:\s*Viewed/i.test(opt.text)) {
 				// remove any stale tag then append
 				opt.text = opt.text.replace(/\s*:? ?Viewed/i, "") + ": Viewed";
 			}
 		}

 		// Increment the left span (viewed) in #total once

 		function incrementHeaderViewedOnce() {
 			const spans = document.querySelectorAll("#total span");
 			if (spans.length >= 2) {
 				const left = spans[0];
 				let v = parseInt(left.textContent.trim(), 10);
 				if (Number.isNaN(v)) v = 0;
 				left.textContent = String(v + 1);
 			}
 		}


 		// Persist to backend (only on first view)
 		function saveViewed(ord, qid, lessonId, total, storyId) {
 			const fd = new FormData();
 			fd.append("action", "view-homework");
 			fd.append("ord", ord);
 			fd.append("question_id", qid);
 			fd.append("lesson_id", lessonId);
 			fd.append("story_id", storyId || 0);
 			fd.append("total", total);

 			return $.ajax({
 				type: "POST",
 				url: "<?= $configs['vpath'] ?>ajax-home",
 				data: fd,
 				processData: false,
 				contentType: false,
 				dataType: "json"
 			});
 		}

 		// Override
 		window.showDiv = function(idx, qid = 0, lessonId = 0, total = 0, storyId = 0) {
 			const ans = document.getElementById("ansdiv_" + idx);
 			const wasHidden = !ans || getComputedStyle(ans).display === "none";
 			if (ans) ans.style.display = wasHidden ? "block" : "none";

 			// Only act the first time the explanation is opened for this question
 			if (!wasHidden) return;

 			// If we already counted this Q in this page session, do nothing
 			if (viewedOnce.has(idx)) return;

 			const sel = getPrimarySelector();

 			// If dropdown already shows ": Viewed", do not count/send again
 			if (currentOptionAlreadyViewed(sel)) {
 				viewedOnce.add(idx); // remember so toggling doesn't try again
 				return;
 			}

 			// First time view: update UI and persist
 			markCurrentOptionViewed(sel);
 			incrementHeaderViewedOnce();
 			viewedOnce.add(idx);

 			// Save to homework_view via backend handler (idempotent on server side)
 			saveViewed(idx, qid, lessonId, total, storyId);
 		};
 	})();

 	//Only For showDivStory (story questions)


 	(function() {
 		const viewedOnce = new Set();

 		function saveViewed(qid, lessonId, total, storyId, ord) {
 			const fd = new FormData();
 			fd.append("action", "view-homework");
 			fd.append("question_id", qid);
 			fd.append("lesson_id", lessonId);
 			fd.append("total", total);
 			fd.append("story_id", storyId);
 			fd.append("ord", ord || 0);

 			return $.ajax({
 				type: "POST",
 				url: "<?= $configs['vpath'] ?>ajax-home",
 				data: fd,
 				processData: false,
 				contentType: false,
 				dataType: "json"
 			});
 		}

 		window.showDivStory = function(qid, lessonId = 0, total = 0, storyId = 0, ord = 0) {
 			const ansDiv = document.getElementById("ansdiv_" + qid);
 			if (!ansDiv) return;

 			const wasHidden = getComputedStyle(ansDiv).display === "none";
 			ansDiv.style.display = wasHidden ? "block" : "none";

 			if (!wasHidden || viewedOnce.has(qid)) return;

 			// increment viewed counter

 			const spans = document.querySelectorAll("#total span");
 			if (spans.length >= 2) {
 				const left = spans[0];
 				let v = parseInt(left.textContent.trim(), 10);
 				if (Number.isNaN(v)) v = 0;
 				left.textContent = String(v + 1);
 			}


 			// update dropdown for this qid
 			const sel = document.querySelector(".lesson-controls .left-controls .ctrl select.form-control");
 			if (sel) {
 				const qidStr = String(qid).trim();
 				for (let i = 0; i < sel.options.length; i++) {
 					const opt = sel.options[i];
 					if (String(opt.value).trim() === qidStr) {
 						if (!/:\s*Viewed/i.test(opt.text)) {
 							opt.text = opt.text + " : Viewed";
 						}
 						sel.selectedIndex = i; // bring forward
 						sel.dispatchEvent(new Event("change")); // ensure navigation reacts
 						break;
 					}
 				}
 			}

 			viewedOnce.add(qid);
 			saveViewed(qid, lessonId, total, storyId, ord);
 		};
 	})();


 	$(".close-btn").click(function() {
 		$(".anszone").fadeToggle();
 	});



 	//Timer

 	var minutesLabel = document.getElementById("minutes");
 	var secondsLabel = document.getElementById("seconds");
 	var totalSeconds = 0;
 	setInterval(setTime, 1000);

 	function setTime() {
 		++totalSeconds;
 		secondsLabel.innerHTML = pad(totalSeconds % 60);
 		minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
 		document.getElementById("time").value = pad(parseInt(totalSeconds / 60)) + "." + pad(totalSeconds % 60);
 	}

 	function pad(val) {
 		var valString = val + "";
 		if (valString.length < 2) {
 			return "0" + valString;
 		} else {
 			return valString;
 		}
 	}
 </script>

 <script type="text/javascript">
 	$(document).ready(function() {
 		$('.bookmark-checkbox').on('change', function() {
 			var questionId = $(this).data('id');
 			var order = $(this).data('ord');
 			var lessonId = $(this).data('lesson-id');
 			var isChecked = $(this).is(':checked');

 			var formData = new FormData();
 			formData.append('question_id', questionId);
 			formData.append('ord', order);
 			formData.append('lesson_id', lessonId);
 			formData.append('action', isChecked ? 'add-bookmark' : 'remove-bookmark');

 			$.ajax({
 				type: 'POST',
 				dataType: "json",
 				url: "<?= $configs['vpath'] ?>ajax-home",
 				data: formData,
 				contentType: false, // The content type used when sending data to the server.
 				cache: false, // To unable request pages to be cached
 				processData: false,
 				success: function(response) {


 				}
 			});
 		});
 	});

 	// Function to show a toast notification
 	function showToast(message) {
 		let toast = document.createElement("div");
 		toast.id = "toast-notification";
 		toast.innerText = message;
 		document.body.appendChild(toast);

 		// Apply styles dynamically
 		Object.assign(toast.style, {
 			position: "fixed",
 			top: "20px",
 			right: "20px",
 			background: "#4CAF50",
 			color: "white",
 			padding: "10px 20px",
 			borderRadius: "5px",
 			zIndex: "1000",
 			fontSize: "14px",
 			opacity: "0",
 			transition: "opacity 0.5s ease-in-out",
 			marginTop: "80px"
 		});

 		// Make toast visible
 		setTimeout(() => {
 			toast.style.opacity = "1";
 		}, 100);

 		// Hide after 3 seconds
 		setTimeout(() => {
 			toast.style.opacity = "0";
 			setTimeout(() => {
 				document.body.removeChild(toast);
 			}, 500);
 		}, 3000);
 	}
 </script>



 <script>
 	function copyQidToClipboard(qid) {
 		// Create a temporary input element
 		const tempInput = document.createElement("input");
 		tempInput.value = qid;
 		document.body.appendChild(tempInput);

 		// Select and copy the text
 		tempInput.select();
 		document.execCommand("copy");

 		// Remove the temporary input
 		document.body.removeChild(tempInput);

 		// Show a toast notification
 		//showToast("QID " + qid + " copied to clipboard!");
 	}

 	// Make all question badges clickable to copy QID
 	document.addEventListener('DOMContentLoaded', function() {
 		const badges = document.querySelectorAll('.badge[data-tooltip]');
 		badges.forEach(badge => {
 			badge.style.cursor = 'pointer';
 			badge.addEventListener('click', function() {
 				const qid = this.getAttribute('data-tooltip').replace('', '');
 				copyQidToClipboard(qid);
 			});
 		});
 	});

 </script>

<style>


</style>