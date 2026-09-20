<?php if (!empty($_SESSION['has_multi_courses'])) { ?>

	<div id="multiCourseAlert" class="multi-course-alert">
		<div class="multi-course-alert-content">
			<strong>Multiple Courses</strong>
			<p>
				You’re registered for multiple courses. Use the navbar to switch courses.
			</p>
		</div>

		<button
			type="button"
			class="multi-course-alert-close"
			onclick="closeMultiCourseAlert()"
			aria-label="Close">
			&times;
		</button>
	</div>

<?php } ?>

<!--courses section-->
<div class="userprofile_wrapper">
	<div class="container edu-body">
		<div class="userprofile-body midle-conarea mr-t88">
			<div class="col-lg-3 col-md-3 col-sm-3 cust-side ">

				<?
				include("../template/" . $configs['template'] . "/includes/tmpl_left_exam.php");
				?>
			</div>

			<div class="col-lg-9 col-md-9 col-sm-9 edu-right">

				<div class="panel dashbord-panel with-nav-tabs panel-default">
					<div id="myHeader" class="panel-heading">

						<?
						//head switch
						switch ($_GET['result']) {

							case "result":
						?>
								<h3>
									<a href="<?= $configs['vpath'] ?>exam/progress-report" style="color:#e19f2a">Result: </a>
									<?= $db_value['cat_name'] ?> - <?= $db_value['lesson_name'] ?>
								</h3>
							<?php
								break;

							case "progress-report":

								$is_staging = ($_SESSION['test_id'] == 27) ? "staging." : "staging-";

								if ($_SESSION['test_id'] >= 21 && $_SESSION['test_id'] <= 26) { // officer's candidate
									$prev_report_link = "https://" . $is_staging . "officers.asvab-tutoring.com/login/";
								} elseif ($_SESSION['test_id'] == 27) { // asvab tutor
									$prev_report_link = "https://www." . $is_staging . "asvab-tutoring.com/online-study/login/";
								} elseif ($_SESSION['test_id'] == 29) { // asvab gt
									$prev_report_link = "https://" . $is_staging . "prep.asvab-tutoring.com/login/";
								}
							?>

								<div class="mob-tab-menu nav-pred-prog">
									<ul class="nav nav-tabs">
										<li <?= ($_GET['cat_id'] == 2000) ? 'class="active"' : '' ?>>
											<a href="<?= $configs['vpath']; ?>exam/progress-report/2000-Overview">Overview</a>
										</li>

										<!-- Category List -->
										<?php foreach ($cat_list as $c): ?>
											<?php if ($c['category_name'] != 'Flashcards'): ?>
												<li <?= ($_GET['cat_id'] == $c['cat_id'] && strpos($_GET['cat_name'], 'prediction') == false) ? 'class="active"' : '' ?>>
													<a href="<?= $configs['vpath'] ?>exam/progress-report/<?= $c['cat_id'] . "-" . seo_url($c['category_name']) ?>" class="tab">
														<?= $c['shortcode'] ?>
													</a>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>

										<!-- Predicted Test List -->
										<?php foreach ($pred_cat_list as $c): ?>
											<li <?= ($_GET['cat_id'] == $c['Predic_test_id'] && strpos($_GET['cat_name'], 'prediction') == true) ? 'class="active"' : '' ?>>
												<a href="<?= $configs['vpath'] ?>exam/progress-report/<?= $c['Predic_test_id'] . "-" . seo_url($c['test_name']) ?>" class="tab">
													<?= $c['shortcode'] ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
								<?php
								break;

							case "prediction-test":
								$show_pt = (int)getFldValueById("user", "pt", ["user_id" => $_SESSION['user_id']]);

								if ($show_pt == 1 && !$alert) {
								?>
									<div class="mob-tab-menu nav-pred-prog">
										<ul class="nav nav-tabs">
											<?php
											$i = 0;
											$current = 0;

											foreach ($prediction_cat as $val) {
												$i++;
												$class = "";

												if ($_GET['child_cat_id'] == $val['cat_id']) {
													$class = "active";
													$current = $i;
												}
												if (!$_GET['child_cat_id'] && $i == 1) {
													$class = "active";
												}
											?>
												<li class="alert_msg <?= $class ?>">
													<a class="pred-nav-tabs answered-cat"
														href="<?= $configs['vpath'] ?>exam/prediction-test/<?= $_GET['cat_id'] . "-" . $_GET['cat_name'] . "/" . $val['cat_id'] . "-" . $val['shortcode'] ?>">
														<?= $val['shortcode'] ?> (<?= $val['completed'] ?>/<?= $val['total'] ?>)
													</a>
												</li>
											<?php } ?>
										</ul>
									</div>
									<?php
									// Prev/Next button logic (no direct HTML, just computed links)
									$i = 1;
									$prev_cat_id = $prev_shortcode = $next_cat_id = $next_shortcode = null;
									$val_total = $val_completed = null;

									foreach ($prediction_cat as $val) {
										if (($current - 1) == $i) {
											$prev_cat_id = $val['cat_id'];
											$prev_shortcode = $val['shortcode'];
										} elseif (($current + 1) == $i) {
											$next_cat_id = $val['cat_id'];
											$next_shortcode = $val['shortcode'];
										} elseif ($current == $i) {
											$val_total = $val['total'];
											$val_completed = $val['completed'];
										}
										$i++;
									}

									$prev = "";
									if (($current - 1) > 0) {
										$prev = "<a class='pull-left arrow-button prev-button' href='" .
											$configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" .
											$_GET['cat_name'] . "/" . $prev_cat_id . "-" . $prev_shortcode . "'></a>";
									}

									$next = "";
									if (($current + 1) < $i) {
										$next = "<a id='next-button' class='pull-right arrow-button next-button' href='" .
											$configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" .
											$_GET['cat_name'] . "/" . $next_cat_id . "-" . $next_shortcode . "'></a>";
									}
								}
								break;

							default:
								$p = "";
								if ($_GET['cat_id'] == 70) {
									//$p="final-test";
								}

								if ($lesson_total) {
									foreach ($lesson as $c) {
										// (no HTML shown here in your code, so skipped)
									}
								} elseif ($_GET['cat_id'] == 18 && $_GET['homework']) {
									?>
									<div class="mob-tab-menu nav-pred-prog">
										<ul class="nav nav-tabs">
											<?php for ($i = 1; $i <= $total_set; $i++): ?>
												<li <?= ($_GET['set_no'] == $i) ? 'class="active"' : '' ?>>
													<a href="<?= $configs['vpath'] ?>exam/<?= $_GET['cat_id'] . "-" . seo_url($db_value['cat_name']) ?>/set-<?= $i ?>">
														Set <?= $i ?>
													</a>
												</li>
											<?php endfor; ?>
										</ul>
									</div>
								<?php
								}
								break;
						}

						if ($_GET['result']) {
							if ($_GET['result'] == 'final-test') {
								?>
								Final Test
							<?php
							} elseif ($_GET['result'] == 'flashcards') {
							?>
								<h3>Flashcards<?= $flashcard_cat_name ? " - " . $flashcard_cat_name : "" ?></h3>
							<?php
							} elseif (substr_count($_SERVER['REQUEST_URI'], "progress-report")) {
							?>
								<!-- <a href="<?= $configs['vpath'] ?>exam/progress-report" style="color:#111">Progress Report</a> -->
							<?php
							} elseif ($_GET['result'] == 'view') {
							?>
								<h3>
									<a href="<?= $configs['vpath'] ?>exam/progress-report" style="color:#e19f2a">Progress Report: </a>
									Detail Info
								</h3>
							<?php
							}
						} elseif ($_GET['id'] && $_GET['page'] == 'category-list') {
							?>
							<h3>Select one of the following to start.</h3>
						<?php
						} elseif ($_GET['cat_id'] && $_GET['id']) {
						?>
							<h3><?= ucwords(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $_GET['cat_name'])) ?></h3>
							<?php
						}

						if ($_GET['cat_id']) {
							if ($_GET['cat_id'] < 2000) {
								if ($_GET['result'] == "prediction-test") {
									if (!empty($alert)) {
										$course_name = "";
										if ($_SESSION['test_name'] && in_array($_SESSION['test_id'], [24, 27, 40])) {
											$course_name = $_SESSION['test_name'] . " => ";
										}
							?>
										<div class="row">
											<div class="col-md-9">
												<?= $course_name . $db_value['test_name'] ?>
											</div>
										</div>
									<?php
									} else {
									?>
										<div class="lesson-controls">
											<div id="test-header">
												<div id="timer">
													<label id="minutes">00</label>m:<label id="seconds">00</label>s
												</div>
												<div id="answered-box">
													<span id="total-answered"><?= $completed_total ?></span> /
													<span id="totalq"><?= $total ?></span>
												</div>
											</div>
										</div>
						<?php
									}
								} else {
									if ($db_value['lesson_name']) {
										$page_heading = $db_value['cat_name'] . " > " . $db_value['lesson_name'];
									} else {
										// $page_heading = $db_value['category_name'];
									}
								}
							}
						}

						?>
					</div>
					<div class="div-scroll panel-body edu-contant">
						<!-----Start dashbard box-->
						<?

						if ($_GET['result']) {
							switch ($_GET['result']) {
								case "result": ?>

									<br>
									<table class="table table-bordered table-striped lesson-test-score">
										<tr>
											<th>Date</th>
											<th>Total Questions</th>
											<th>Correct Answer</th>
											<th>Score(%)</th>

										</tr>

										<tr>
											<td><?= date("M j, Y", strtotime($date)) ?></td>
											<td><?= $total ?></td>
											<td><?= $total_correct ?></td>
											<td><?= round($db_value['score']) ?></td>
										</tr>
									</table>
									<br>

									<h4 style="text-align: left !important; padding-left: 20px !important;">
										Question and Answer with clarification:
									</h4>

									<div style="margin-top:15px; display:flex; gap:10px; justify-content:center;">
										<a href="<?= $configs['vpath'] ?>exam/result/<?= $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $_GET['lesson_id'] . "/" . $_GET['result_id'] . "/all" ?>" class="filter-btn all <?= ($_GET['filter'] == 'all' || $_GET['filter'] == '') ? 'active' : '' ?>"> All <? echo "(" . $total_all_ques . ")" ?></a>

										<a href="<?= $configs['vpath'] ?>exam/result/<?= $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $_GET['lesson_id'] . "/" . $_GET['result_id'] . "/correct" ?>" class="filter-btn correct <?= ($_GET['filter'] == 'correct') ? 'active' : '' ?>"> Correct <? echo "(" . $total_correct_ques . ")" ?></a>

										<a href="<?= $configs['vpath'] ?>exam/result/<?= $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $_GET['lesson_id'] . "/" . $_GET['result_id'] . "/wrong" ?>" class="filter-btn wrong <?= ($_GET['filter'] == 'wrong') ? 'active' : '' ?>"> Wrong <? echo "(" . $total_wrong_ques . ")" ?></a>
									</div>

									<hr>

									<?php $i = 0; ?>

									<?php if (in_array($_GET['cat_id'], $story_question)): ?>

										<?php foreach ($story_list as $d): ?>
											<div class="story-block">
												<div class="story-text" align="justify">
													<?= html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&nbsp;", "", $story[$d['story_id']]))))) ?>
												</div>

												<table class="table table-borderless result_question_list">
													<?php foreach ($question_test[$d['story_id']] as $q): ?>
														<?php $i++; ?>
														<tr>
															<td class="question_no">
																<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($q['qid']) ?>">
																	<?= $i ?>
																</span>
															</td>
															<td class="questions">
																<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) ?>
															</td>
														</tr>

														<?php
														$correct_ans = "";
														for ($j = 1; $j <= $option_no; $j++):
															$ans = $alphabet[$j];
															$ind = 'option_' . strtolower($ans);

															if ($q[$ind] || $_GET['cat_id'] == 84):

																// Highlight user answer and correct answer
																if ($q['user_ans'] == $j) {
																	if ($q['user_ans'] == $q['answer']) {
																		// Correct selection → green
																		$ans = '<span class="badge badge-correct badge-green">' . $alphabet[$j] . '</span>';
																	} else {
																		// Wrong selection → red
																		$ans = '<span class="badge badge-danger badge-red">' . $alphabet[$j] . '</span>';
																	}
																} elseif ($q['answer'] == $j) {
																	// Show the actual correct answer → green
																	$ans = '<span class="badge badge-correct">' . $alphabet[$j] . '</span>';
																}
														?>
																<tr>
																	<td class="abcd"><?= $ans ?></td>
																	<td class="options">
																		<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) ?>
																	</td>
																</tr>
															<?php endif; ?>
														<?php endfor; ?>

														<?php if ($q['user_ans'] == $q['answer']): ?>
															<tr>
																<td class="iscoreect" colspan="2">
																	<b><span class="badge badge-correct">Correct</span></b>
																</td>
															</tr>
														<?php else: ?>
															<tr>
																<td class="iscoreect" colspan="2">
																	<b><span class="badge badge-wrong">Wrong</span>
																</td>
															</tr>
														<?php endif; ?>

														<?php if ($q['explanation']): ?>
															<tr>
																<td class="explanation" colspan="4">
																	<b>Explanation:</b>
																	<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) ?>
																</td>
															</tr>
														<?php endif; ?>

														<tr>
															<td colspan="3">
																<hr style="margin:0;">
															</td>
														</tr>
													<?php endforeach; ?>
												</table>
											</div>
										<?php endforeach; ?>

									<?php else: ?>

										<table class="table table-borderless result_question_list">
											<?php foreach ($question_test as $q): ?>
												<?php $i++; ?>
												<tr>
													<td class="question_no">
														<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($q['qid']) ?>">
															<?= $i ?>
														</span>
													</td>
													<td class="questions">
														<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) ?>
													</td>
												</tr>

												<?php
												$correct_ans = "";
												for ($j = 1; $j <= $option_no; $j++):
													$ans = $alphabet[$j];
													$ind = 'option_' . strtolower($ans);

													if ($q[$ind] || in_array($_GET['cat_id'], [83, 86, 64])):

														// Case 1: user selected this option
														if ($q['user_ans'] == $j) {
															if ($q['user_ans'] == $q['answer']) {
																// User selected correct option → green
																$ans = '<span class="badge badge-correct correct-ans">' . $alphabet[$j] . '</span>';
															} else {
																// User selected wrong option → red
																$ans = '<span class="badge badge-danger">' . $alphabet[$j] . '</span>';
															}
														}
														// Case 2: user was wrong, but this is the correct option
														elseif ($q['answer'] == $j) {
															$ans = '<span class="badge badge-correct">' . $alphabet[$j] . '</span>';
														}
												?>
														<tr>
															<td class="abcd"><?= $ans ?></td>
															<td class="options" style="text-align:left">
																<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) ?>
															</td>
														</tr>
													<?php endif; ?>
												<?php endfor; ?>

												<?php if ($q['user_ans'] == $q['answer']): ?>
													<tr>
														<td class="iscoreect" colspan="2">
															<b><span class="badge badge-correct">Correct</span></b>
														</td>
													</tr>
												<?php else: ?>
													<tr>
														<td class="iscoreect" colspan="2">
															<b><span class="badge badge-wrong">Wrong</span>
														</td>
													</tr>
												<?php endif; ?>

												<?php if ($q['explanation']): ?>
													<tr>
														<td class="explanation" colspan="2" style="color:blue">
															<p><b>Explanation: </b></p>
															<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) ?>
														</td>
													</tr>
												<?php endif; ?>

												<tr>
													<td colspan="3">
														<hr style="margin:0;">
													</td>
												</tr>
											<?php endforeach; ?>
										</table>

									<?php endif; ?>

									<!-- User feedback modal (only shows if feedback doesn't already exist for this result) -->
									<?php if (!$feedback_exists) { ?>
										<div id="feedbackModal" class="modal-overlay">
											<div class="modal-box">
												<h4>We'd love to hear about your experience. Your feedback helps us improve!</h4>

												<form method="post">
													<div class="stars-container">
														<div class="stars">
															<?php for ($i = 5; $i >= 1; $i--): ?>
																<input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>">
																<label for="star<?= $i ?>">★</label>
															<?php endfor; ?>
														</div>

														<small id="ratingHint" class="rating-hint">
															⭐ Please select a rating.
														</small>
													</div>

													<textarea name="feedback" rows="4" placeholder="Write your feedback..."></textarea>

													<button type="submit" name="submit_feedback" class="btn-submit">
														Submit
													</button>
												</form>

												<button class="close-btn" onclick="closeModal()">×</button>
											</div>
										</div>

									<?php
									}

									break;

								case "view":

									if ($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) { // full ASVAB score card
									?>
										<div class='text-center w-100'>
											<h4 class="w-100">ASVAB SCORE REPORT</h4>
										</div>
										<?php
										foreach ($full_asvab_result_list as $d) {
											if ($d['pred_name'] == "VE") {
												$ve_score = $d['score'];
											}
										}
										?>
										<div class="asvab-container">
											<table class="asvab-table user-name">
												<tr>
													<td class="text-left" width='180px'><strong>Testing Session Date:</strong></td>
													<td class="text-left"><?= $date ?></td>
												</tr>
												<tr>
													<td class="text-left"><strong>Applicant Name:</strong></td>
													<td class="text-left"><?= $_SESSION['name']  ?></td>
												</tr>
											</table>

											<hr style="border:none; height:2px; background:#ccc;">

											<table class="asvab-table text-wrap standard-score-table">
												<tr>
													<td class="text-wrap" rowspan="2" class="asvab-label-cell">Standard Scores:</td>
													<?php foreach ($pred_test_report as $key => $d): ?>
														<td><strong><?= getFldValueById("category", "shortcode", ["cat_id" => $d['cat_id']]) ?></strong></td>
													<?php endforeach; ?>
													<td><strong>VE</strong></td>
												</tr>
												<tr>
													<?php foreach ($pred_test_report as $key => $d): ?>
														<td><?= round($d['score']) ?></td>
													<?php endforeach; ?>
													<td><?= round($ve_score) ?></td>
												</tr>
											</table>

											<hr style="border:none; height:2px; background:#ccc;">

											<div class="asvab-section-title text-left">Composite Scores:</div>


											<table class="asvab-table composite-score-table">
												<?
												foreach ($full_asvab_result_list as $d) {
													if ($d['pred_name'] != "VE" && $d['pred_name'] != "AFQT") {
												?>
														<tr class="gt-name">
															<td rowspan="2" class="asvab-label-cell"><?php echo str_replace("GT", "", $d['pred_name']); ?></td>
															<td style="border-bottom: none;"><strong>GT</strong></td>
														</tr>
														<tr class="gt-score">
															<td><? echo round($d['score']); ?></td>
														</tr>
												<?
													} else if ($d['pred_name'] == "AFQT") {
														$afqt_score = round($d['score']);
													}
												}
												?>
											</table>

											<div class="asvab-section-title text-left">AFQT Percentile Score:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<? echo $afqt_score; ?></div>

											<hr style="border:none; height:2px; background:#ccc;">
										</div>

										<br>
										<h4>ASVAB Prediction Test Review</h4>

									<?php
									} elseif ($_SESSION['test_id'] == 24 && $_GET['cat_id'] == 40) { // full AFOQT score card
									?>
										<div class="text-left" id="afoqt_score">
											<h4>Air Force Officer Qualifying Test Scores: </h4>
										</div>
										<hr style="border:none; height:2px; background:#ccc;">

										<p style="padding-left:15px;">The test score of <strong><?= htmlspecialchars($_SESSION['name']) ?></strong></p>
										<table class="afoqt-subscore">
											<tr style="background:#f0f4fc;">
												<th>Date</th>
												<th data-tooltip="Pilot Score">PIL</th>
												<th data-tooltip="Combat Systems Officer">CSO</th>
												<th data-tooltip="Air Battle Manager">ABM</th>
												<th data-tooltip="Academic Aptitude">AA</th>
												<th data-tooltip="Verbal">V</th>
												<th data-tooltip="Quantitative">Q</th>
											</tr>
											<tr>
												<td><?= htmlspecialchars($date) ?></td>
												<?php foreach ($full_afoqt_result_list as $d): ?>
													<?php if ($d['pred_name'] != "AFOQT"): ?>
														<td><?= round($d['score']) ?></td>
													<?php endif; ?>
												<?php endforeach; ?>
											</tr>
										</table>

										<br>
										<h4>AFOQT Prediction Test Review</h4>
									<?php
									} elseif ($_SESSION['test_id'] == 24) { // partial AFOQT score card
										$pred_score_heading = "";
										$cat_num = 0;
										if ($_GET['cat_id'] == 15) {
											$pred_score_heading = $_SESSION['test_name'] . " (Pilot) Prediction Test Review";
											$cat_num = 1;
										} elseif ($_GET['cat_id'] == 16) {
											$pred_score_heading = $_SESSION['test_name'] . " (CSO) Prediction Test Review";
											$cat_num = 2;
										} elseif ($_GET['cat_id'] == 17) {
											$pred_score_heading = $_SESSION['test_name'] . " (ABM) Prediction Test Review";
											$cat_num = 3;
										} elseif ($_GET['cat_id'] == 18) {
											$pred_score_heading = $_SESSION['test_name'] . " (Academic Aptitude) Prediction Test Review";
											$cat_num = 4;
										} elseif ($_GET['cat_id'] == 19) {
											$pred_score_heading = $_SESSION['test_name'] . " (Verbal) Prediction Test Review";
											$cat_num = 5;
										} elseif ($_GET['cat_id'] == 20) {
											$pred_score_heading = $_SESSION['test_name'] . " (Quantitative) Prediction Test Review";
											$cat_num = 6;
										} else {
											$pred_score_heading = $_SESSION['test_name'] . " Prediction Test Review";
										}
									?>
										<div class="text-left" id="afoqt_score">
											<h4>Air Force Officer Qualifying Test Scores: </h4>
										</div>
										<hr style="border:none; height:2px; background:#ccc;">

										<h4 style="padding-left:15px; padding-bottom:15px; text-align:left">
											The test score of <strong><?= htmlspecialchars($_SESSION['name']) ?>.</strong>
										</h4>
										<table class="afoqt-subscore">
											<tr style="background:#f0f4fc;">
												<th>Date</th>
												<th data-tooltip="Pilot Score">PIL</th>
												<th data-tooltip="Combat Systems Officer">CSO</th>
												<th data-tooltip="Air Battle Manager">ABM</th>
												<th data-tooltip="Academic Aptitude">AA</th>
												<th data-tooltip="Verbal">V</th>
												<th data-tooltip="Quantitative">Q</th>
											</tr>
											<tr>
												<td><?= date("M j, Y", strtotime($date)) ?></td>
												<?php for ($i = 1; $i <= 6; $i++): ?>
													<?php if ($cat_num == $i): ?>
														<td><?= round($pred_score) ?></td>
													<?php else: ?>
														<td>-</td>
													<?php endif; ?>
												<?php endfor; ?>
											</tr>
										</table>

										<br>
										<h4><?= $pred_score_heading ?></h4>
									<?php
									} else {
										$pred_score_heading = $_SESSION['test_name'] . " Prediction Score : ";
									?>
										<div class="row pred-test-date-row">
											<div class="col-md-6">
												<h4>Date: <?= showdate($date) ?> | <?= $category['test_name'] ?></h4>
											</div>
											<div class="col-md-6"></div>
										</div>
									<?php
									}

									$pred_id_array = [40, 23, 15, 16, 17, 18, 19, 20];
									?>
									<br>
									<table class="table table-bordered table-striped afoqt_pre_test_review">
										<?php if (!in_array((int)$_GET['cat_id'], $pred_id_array)): ?>
											<thead>
												<tr>
													<th colspan="5">
														<h4 style="color:green">
															<?= $pred_score_heading . round($pred_score) ?>
														</h4>
													</th>
												</tr>
											</thead>
										<?php endif; ?>

										<thead>
											<tr>
												<th>SubTest</th>
												<th>Total Question</th>
												<th>Correct</th>
												<th>Score</th>
												<th>Review</th>
											</tr>
										</thead>

										<tbody>
											<?php foreach ($pred_test_report as $key => $d):
												// $cat_id = $d['cat_id'];$cat['shortcode']
												$cat_shortcode = getFldValueById("category", "shortcode", ["cat_id" => $d['cat_id']]);
												if ($cat_shortcode == "ASVAB" || $cat_shortcode == "AFQT") {
													$key = $cat_shortcode;
												}
											?>
												<tr>
													<td><?= $key ?></td>
													<td><?= $d['total_questions'] ?></td>
													<td><?= $d['correct_answers'] ?></td>
													<td><?= (int)$d['score'] ?></td>
													<td>
														<a href="<?= $configs['vpath'] ?>exam/view/<?= $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $d['cat_id'] . "/" . $_GET['result_id'] ?>"
															class="btn-review btn <?= ($cat_id == $d['cat_id']) ? "btn-success" : "btn-primary" ?>">
															View
														</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
									<br>

									<h4>Question and Answer with clarification: <b><?= $cat_name ?></b></h4>

									<?
									// echo "Total questions: ". $total_ques_check . "<br>". $checking_data;
									?>

									<div style="margin-top:15px; display:flex; gap:10px; justify-content:center;">
										<a href="<?= $configs['vpath'] ?>exam/view/<?= $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $_GET['lesson_id'] . "/" . $_GET['result_id'] . "/all" ?>" class="filter-btn all <?= ($_GET['filter'] == 'all' || $_GET['filter'] == '') ? 'active' : '' ?>"> All <? echo "(" . $total_all_ques . ")" ?></a>

										<a href="<?= $configs['vpath'] ?>exam/view/<?= $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $_GET['lesson_id'] . "/" . $_GET['result_id'] . "/correct" ?>" class="filter-btn correct <?= ($_GET['filter'] == 'correct') ? 'active' : '' ?>"> Correct <? echo "(" . $total_correct_ques . ")" ?></a>

										<a href="<?= $configs['vpath'] ?>exam/view/<?= $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $_GET['lesson_id'] . "/" . $_GET['result_id'] . "/wrong" ?>" class="filter-btn wrong <?= ($_GET['filter'] == 'wrong') ? 'active' : '' ?>"> Wrong <? echo "(" . $total_wrong_ques . ")" ?></a>
									</div>

									<hr>

									<div class="questions-review-container">
										<table class="table table-borderless test_review_table">
											<?php
											$i = 0;
											$key = $cat_id;

											if (in_array($key, $story_question)) {
												foreach ($story_list[$key] as $d) {
											?>
													<tr class="story_row">
														<td class="story_td" colspan="2">
															<?= html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&nbsp;", "", nl2br($story[$d['story_id']])))))) ?>
														</td>
													</tr>

													<?php foreach ($question_test[$d['story_id']] as $q): ?>
														<?php $i++; ?>
														<tr class="question-row">
															<td class="question_no">
																<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($q['qid']) ?>">
																	<?= $i ?>
																</span>
															</td>
															<td class="questions" colspan="2">
																<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) ?>
															</td>
														</tr>

														<?php for ($j = 1; $j <= $option_no; $j++):
															$ans = $alphabet[$j];
															$ind = 'option_' . strtolower($ans);

															if ($q[$ind]) {
																// Case 1: user selected this option
																if ($my_answers[$q['qid']] == $j) {
																	if ($my_answers[$q['qid']] == $q['answer']) {
																		// User selected correct option → green
																		$ans = '<span class="badge badge-correct correct-ans">' . $alphabet[$j] . '</span>';
																	} else {
																		// User selected wrong option → red
																		$ans = '<span class="badge badge-danger">' . $alphabet[$j] . '</span>';
																	}
																}
																// Case 2: user was wrong, but this is the correct option
																elseif ($q['answer'] == $j) {
																	$ans = '<span class="badge badge-correct">' . $alphabet[$j] . '</span>';
																}
														?>
																<tr class="option-row">
																	<td class="abcd"><?= $ans ?></td>
																	<td class="options">
																		<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) ?>
																	</td>
																</tr>
															<?php } ?>
														<?php endfor; ?>

														<?php if ($my_answers[$q['qid']] == $q['answer']): ?>
															<tr>
																<td class="Correct" colspan="3" style="text-align:left">
																	<b><span class="badge badge-success">Correct!</span></b>
																</td>
															</tr>
														<?php else: ?>
															<tr>
																<td class="wrong" colspan="2" style="text-align:left">
																	<b><span class="badge badge-wrong">Wrong!</span></b>
																</td>
															</tr>
														<?php endif; ?>

														<?php if ($q['explanation']): ?>
															<tr class="">

																<td colspan="2" class="explanation" style="color:blue">
																	Explanation: <?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) ?>
																</td>
															</tr>
														<?php endif; ?>

														<tr>
															<td colspan="2">
																<hr style="margin:0;">
															</td>
														</tr>
													<?php endforeach; ?>
												<?php
												}
											} else {
												foreach ($questions[$key] as $q) {
													$i++;
												?>
													<tr class="question-row">
														<td class="question_no">
															<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($q['qid']) ?>">
																<?= $i ?>
															</span>
														</td>
														<td class="questions" colspan="2">
															<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) ?>
														</td>
													</tr>

													<?php for ($j = 1; $j <= $option_no; $j++):
														$ans = $alphabet[$j];
														$ind = 'option_' . strtolower($ans);

														if ($q[$ind]):
															// Case 1: user selected this option
															if ($my_answers[$q['qid']] == $j) {
																if ($my_answers[$q['qid']] == $q['answer']) {
																	// User selected correct option → green
																	$ans = '<span class="badge badge-correct correct-ans">' . $alphabet[$j] . '</span>';
																} else {
																	// User selected wrong option → red
																	$ans = '<span class="badge badge-danger">' . $alphabet[$j] . '</span>';
																}
															}
															// Case 2: user was wrong, but this is the correct option
															elseif ($q['answer'] == $j) {
																$ans = '<span class="badge badge-correct">' . $alphabet[$j] . '</span>';
															}
													?>
															<tr class="">
																<td class="abcd"><?= $ans ?></td>
																<td class="options">
																	<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) ?>
																</td>
															</tr>
														<?php endif; ?>
													<?php endfor; ?>

													<?php if ($my_answers[$q['qid']] == $q['answer']): ?>
														<tr>
															<td class="correct" colspan="2">
																<b><span class="badge badge-success">Correct</span></b>
															</td>
														</tr>
													<?php else: ?>
														<tr class="">
															<td class="wrong" colspan="2">
																<b><span class="badge badge-wrong">Wrong</span></b>
															</td>
														</tr>
													<?php endif; ?>

													<?php if ($q['explanation']): ?>
														<tr>

															<td class="explanation" colspan="3" style="text-align:left">
																Explanation: <?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) ?>
															</td>
														</tr>
													<?php endif; ?>

													<tr>
														<td class="" colspan="3">
															<hr style="margin:0;">
														</td>
													</tr>
											<?php
												}
											}
											?>
										</table>
									</div>

									<!-- User feedback modal (only shows if feedback doesn't already exist for this result) -->
									<?php if (!$feedback_exists): ?>
										<div id="feedbackModal" class="modal-overlay">
											<div class="modal-box">
												<h4>We'd love to hear about your experience. Your feedback helps us improve!</h4>

												<form method="post">
													<div class="stars-container">
														<div class="stars">
															<?php for ($i = 5; $i >= 1; $i--): ?>
																<input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>">
																<label for="star<?= $i ?>">★</label>
															<?php endfor; ?>
														</div>

														<small id="ratingHint" class="rating-hint">
															⭐ Please select a rating.
														</small>
													</div>

													<textarea name="feedback" rows="4" placeholder="Write your feedback..."></textarea>

													<button type="submit" name="submit_feedback" class="btn-submit">
														Submit
													</button>
												</form>

												<button class="close-btn" onclick="closeModal()">×</button>
											</div>
										</div>
									<?php endif; ?>

									<?php
									break;

								case "flashcards": {
									?>
										<style>
											/* ===== Flashcard palette =====
											   Navy = structure/headings, orange/gold = primary accent - matches
											   the site's navbar color (#ffa031). */
											.flashcard-wrap {
												--fc-navy: #0d2b4e;
												--fc-navy-soft: #3a5878;
												--fc-gold: #ffa031;
												--fc-gold-dark: #e6900a;
												--fc-bg: #f6f7fb;
												--fc-border: #e7e9ee;
												--fc-text: #1f2933;
												--fc-muted: #667085;
												width: 100%;
												max-width: 780px;
												margin: 0 auto;
											}

											/* Toolbar: position in the deck, and a jump-to-card control */
											.flashcard-toolbar {
												display: flex;
												align-items: center;
												justify-content: space-between;
												flex-wrap: wrap;
												gap: 12px;
												padding: 0 4px 18px;
											}
											.flashcard-count {
												display: inline-flex;
												align-items: center;
												gap: 8px;
												padding: 7px 14px;
												background: #fff4e6;
												border: 1px solid #ffd9a3;
												border-radius: 999px;
												font-family: "Maven Pro", sans-serif;
												font-size: 14px;
												font-weight: 600;
												color: var(--fc-navy);
											}
											.flashcard-count i {
												color: var(--fc-gold-dark);
											}
											.flashcard-count span {
												color: var(--fc-gold-dark);
											}
											.flashcard-jump {
												display: flex;
												align-items: center;
												gap: 8px;
											}
											.flashcard-jump label {
												margin: 0;
												font-family: "Maven Pro", sans-serif;
												font-size: 13px;
												font-weight: 500;
												color: var(--fc-muted);
											}
											.flashcard-jump select {
												width: auto;
												min-width: 74px;
												height: 36px;
												padding: 4px 10px;
												border: 1px solid #d7dbe3;
												border-radius: 8px;
												background: #fff;
												color: var(--fc-text);
												font-size: 14px;
												cursor: pointer;
												transition: border-color .15s ease, box-shadow .15s ease;
											}
											.flashcard-jump select:hover {
												border-color: var(--fc-gold-dark);
											}
											.flashcard-jump select:focus {
												outline: none;
												border-color: var(--fc-gold-dark);
												box-shadow: 0 0 0 3px rgba(255, 160, 49, 0.25);
											}

											/* Deck frame */
											.flashcard-slider .flashcard,
											.flashcard-slider .flashcard .flashcard-box {
												min-height: 0;
											}
											.flashcard-slider .flashcard-box {
												padding: 4px 4px 30px;
											}
											/* A theme-wide mobile rule (".slick-initialized .row") forces every
											   slide's row to "width: auto" and "display: inherit", which fights
											   slick's own slide sizing and is the usual cause of cards stacking on
											   top of each other on small screens. Out-specificity it here so our
											   slide always fills its slick-slide the way slick expects - and zero
											   out Bootstrap's .row -15px gutter margins, since this row isn't
											   nested in a padded column to cancel them out (they were clipping the
											   card's left edge against the slider's overflow:hidden). */
											.flashcard-slider .row.flashcard {
												display: block !important;
												width: 100% !important;
												margin-left: 0 !important;
												margin-right: 0 !important;
											}

											/* Base card */
											.flashcard-card {
												width: 100%;
												max-width: 100%;
												margin: 0 auto 18px;
												padding: 26px 30px;
												background: #fff;
												border: 1px solid var(--fc-border);
												border-radius: 16px;
												box-shadow: 0 4px 18px rgba(13, 43, 78, 0.06);
											}

											/* Question card - the visual anchor of the deck. A clean, evenly
											   bordered white card raised off the page with a stacked elevation
											   shadow (tight contact shadow + mid shadow + a wide warm
											   theme-color glow, plus a top/bottom inset to suggest a bevel)
											   rather than a single accent-colored edge. */
											.flashcard-question-card {
												display: flex;
												flex-direction: column;
												align-items: center;
												justify-content: center;
												gap: 10px;
												min-height: 130px;
												padding: 28px 32px;
												background: linear-gradient(180deg, #ffffff 0%, #fcfbfa 100%);
												border: 1px solid var(--fc-border);
												box-shadow:
													inset 0 1px 0 rgba(255, 255, 255, 0.95),
													inset 0 -1px 0 rgba(13, 43, 78, 0.05),
													0 1px 2px rgba(13, 43, 78, 0.12),
													0 8px 12px rgba(13, 43, 78, 0.08),
													0 26px 45px rgba(230, 144, 10, 0.28);
											}
											.flashcard-category {
												margin-bottom: 12px;
												font-family: "Maven Pro", sans-serif;
												font-size: 13px;
												font-weight: 700;
												letter-spacing: 0.6px;
												text-transform: uppercase;
												color: var(--fc-navy);
											}
											.flashcard-eyebrow {
												font-family: "Maven Pro", sans-serif;
												font-size: 12px;
												font-weight: 700;
												letter-spacing: 1.5px;
												text-transform: uppercase;
												color: var(--fc-gold-dark);
											}
											.flashcard-question {
												width: 100%;
												text-align: center;
												font-size: 18px;
												line-height: 1.65;
												color: var(--fc-text);
											}
											/* Question/answer/explanation arrive as rich HTML from the CMS editor,
											   whose paragraphs/lists often carry their own inline alignment - force
											   everything inside these blocks to stay centered regardless. */
											.flashcard-question,
											.flashcard-question * {
												text-align: center !important;
											}

											/* Question/answer/explanation text arrives as rich HTML, so drop the
											   leading and trailing <p> margins that pad the card out. */
											.flashcard-question > *:first-child,
											.flashcard-answer > *:first-child,
											.flashcard-explanation-body > *:first-child {
												margin-top: 0;
											}
											.flashcard-question > *:last-child,
											.flashcard-answer > *:last-child,
											.flashcard-explanation-body > *:last-child {
												margin-bottom: 0;
											}

											/* Answer + Explanation live together in one card - same stacked
											   elevation treatment as the question card (slightly lighter, so
											   the question card still reads as the primary surface), no
											   single-sided accent edge. */
											.flashcard-answer-card {
												border-color: var(--fc-border);
												background: linear-gradient(180deg, #ffffff 0%, #fcfbfa 100%);
												box-shadow:
													inset 0 1px 0 rgba(255, 255, 255, 0.95),
													inset 0 -1px 0 rgba(13, 43, 78, 0.05),
													0 1px 2px rgba(13, 43, 78, 0.1),
													0 6px 10px rgba(13, 43, 78, 0.07),
													0 20px 36px rgba(230, 144, 10, 0.22);
											}
											.flashcard-card-label {
												display: flex;
												align-items: center;
												justify-content: center;
												gap: 7px;
												margin-bottom: 10px;
												font-family: "Maven Pro", sans-serif;
												font-size: 13px;
												font-weight: 700;
												letter-spacing: 0.6px;
												text-transform: uppercase;
											}
											.flashcard-label-answer {
												color: var(--fc-gold-dark);
											}
											.flashcard-label-explanation {
												color: var(--fc-navy-soft);
											}
											.flashcard-answer {
												text-align: center;
												font-size: 17px;
												font-weight: 600;
												line-height: 1.65;
												color: var(--fc-text);
											}
											.flashcard-answer,
											.flashcard-answer * {
												text-align: center !important;
											}
											.flashcard-divider {
												margin: 20px 0;
												border: 0;
												border-top: 1px dashed var(--fc-border);
											}
											.flashcard-explanation-box {
												padding: 16px 18px;
												background: var(--fc-bg);
												border-radius: 12px;
											}
											.flashcard-explanation-body {
												text-align: center;
												line-height: 1.7;
												color: var(--fc-muted);
												font-size: 15px;
											}
											.flashcard-explanation-body,
											.flashcard-explanation-body * {
												text-align: center !important;
											}

											/* Answer stays collapsed until "See Answer" is clicked */
											.flashcard-reveal {
												display: none;
											}
											.flashcard-reveal.is-open {
												display: block;
												animation: flashcardReveal .25s ease;
											}
											@keyframes flashcardReveal {
												from { opacity: 0; transform: translateY(-6px); }
												to { opacity: 1; transform: translateY(0); }
											}

											/* Controls: previous / see-answer / next */
											.flashcard-nav {
												display: flex;
												align-items: center;
												justify-content: center;
												gap: 16px;
												width: 100%;
												margin: 0 auto 20px;
											}
											.flashcard-slider .slick-prev,
											.flashcard-slider .slick-next {
												position: static !important;
												top: auto !important;
												right: auto !important;
												bottom: auto !important;
												left: auto !important;
												width: 48px !important;
												height: 48px !important;
												margin: 0 !important;
												padding: 0;
												display: inline-flex;
												align-items: center;
												justify-content: center;
												background: #fff !important;
												border: 1.5px solid var(--fc-border);
												border-radius: 50%;
												color: var(--fc-navy);
												font-size: 15px;
												line-height: 1;
												cursor: pointer;
												transition: background .15s ease, border-color .15s ease, transform .1s ease;
												-webkit-transform: none;
												-ms-transform: none;
												transform: none;
											}
											.flashcard-slider .slick-prev:hover,
											.flashcard-slider .slick-prev:focus,
											.flashcard-slider .slick-next:hover,
											.flashcard-slider .slick-next:focus {
												background: var(--fc-gold) !important;
												border-color: var(--fc-gold);
												color: #fff;
												outline: none;
											}
											.flashcard-slider .slick-prev:active,
											.flashcard-slider .slick-next:active {
												transform: scale(0.94);
											}
											.flashcard-slider .slick-prev.slick-disabled,
											.flashcard-slider .slick-next.slick-disabled {
												opacity: 0.4;
												cursor: not-allowed;
												pointer-events: none;
											}
											.flashcard-slider .slick-prev i,
											.flashcard-slider .slick-next i {
												color: inherit;
												font-size: 22px;
												line-height: 1;
												text-shadow: 0 0 0.5px currentColor, 0 0 0.5px currentColor;
											}

											.flashcard-slider .flashcard-btn-view-ans {
												position: static !important;
												top: auto !important;
												display: inline-flex !important;
												align-items: center;
												gap: 8px;
												margin: 0 !important;
												padding: 11px 26px;
												background: linear-gradient(135deg, var(--fc-gold) 0%, var(--fc-gold-dark) 100%) !important;
												border: none;
												border-radius: 999px;
												color: #fff !important;
												font-family: "Maven Pro", sans-serif;
												font-size: 15px;
												font-weight: 700;
												box-shadow: 0 4px 14px rgba(230, 144, 10, 0.35);
												transition: transform .12s ease, box-shadow .12s ease;
											}
											.flashcard-slider .flashcard-btn-view-ans:hover,
											.flashcard-slider .flashcard-btn-view-ans:focus {
												color: #fff !important;
												transform: translateY(-1px);
												box-shadow: 0 6px 18px rgba(230, 144, 10, 0.45);
												text-decoration: none;
											}
											.flashcard-slider .flashcard-btn-view-ans:active {
												transform: translateY(0);
											}

											@media (max-width: 767px) {
												.flashcard-slider .flashcard-box {
													padding: 4px 10px 24px;
												}
												.flashcard-toolbar {
													flex-direction: column;
													align-items: center;
													gap: 10px;
												}
												.flashcard-card {
													padding: 20px 18px;
												}
												.flashcard-question-card {
													min-height: 100px;
													padding: 22px 20px;
												}
												.flashcard-eyebrow {
													font-size: 11px;
												}
												.flashcard-question {
													font-size: 16px;
												}
												.flashcard-answer {
													font-size: 16px;
												}
												.flashcard-explanation-box {
													padding: 14px 15px;
												}
												.flashcard-explanation-body {
													font-size: 14px;
												}
												.flashcard-nav {
													flex-wrap: wrap;
													row-gap: 10px;
													gap: 10px;
												}
												.flashcard-slider .flashcard-btn-view-ans {
													padding: 10px 18px;
													font-size: 14px;
												}
											}

											/* Extra-small phones: tighten touch targets and spacing further */
											@media (max-width: 380px) {
												.flashcard-slider .flashcard-box {
													padding: 4px 6px 20px;
												}
												.flashcard-card {
													padding: 16px 14px;
												}
												.flashcard-question-card {
													min-height: 90px;
													padding: 18px 16px;
												}
												.flashcard-question {
													font-size: 15px;
												}
												.flashcard-nav {
													gap: 8px;
												}
												.flashcard-slider .slick-prev,
												.flashcard-slider .slick-next {
													width: 42px !important;
													height: 42px !important;
												}
												.flashcard-slider .slick-prev i,
												.flashcard-slider .slick-next i {
													font-size: 19px;
												}
												.flashcard-slider .flashcard-btn-view-ans {
													padding: 9px 14px;
													font-size: 13px;
												}
											}

											/* Sub-category picker, shown before a deck is chosen */
											.flashcard-picker-heading {
												margin: 0 0 20px;
												text-align: center;
												font-family: "Maven Pro", sans-serif;
												font-size: 20px;
												font-weight: 800;
												color: var(--fc-navy);
											}
											.flashcard-category-grid {
												display: grid;
												grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
												gap: 14px;
											}
											.flashcard-category-tile {
												display: flex;
												flex-direction: column;
												align-items: center;
												justify-content: center;
												gap: 6px;
												padding: 24px 16px;
												background: #fff;
												border: 1px solid var(--fc-border);
												border-radius: 14px;
												text-align: center;
												text-decoration: none;
												box-shadow: 0 4px 14px rgba(13, 43, 78, 0.08);
												transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
											}
											.flashcard-category-tile:hover,
											.flashcard-category-tile:focus {
												transform: translateY(-3px);
												border-color: var(--fc-gold);
												box-shadow: 0 10px 22px rgba(230, 144, 10, 0.18);
												text-decoration: none;
											}
											.flashcard-category-tile-name {
												font-family: "Maven Pro", sans-serif;
												font-weight: 700;
												font-size: 15px;
												color: var(--fc-text);
											}
											.flashcard-category-tile-count {
												font-size: 12.5px;
												font-weight: 600;
												color: var(--fc-gold-dark);
												text-transform: uppercase;
												letter-spacing: 0.4px;
											}
										</style>

										<?php if (!empty($flashcard_categories)) { ?>
											<div class="flashcard-wrap">
												<div class="flashcard-category-grid">
													<?php foreach ($flashcard_categories as $fc) { ?>
														<a class="flashcard-category-tile" href="<?= $configs['vpath'] ?>exam/flashcards-<?= (int)$_GET['flashcard_id'] ?>/<?= $fc['cat_id'] ?>-<?= seo_url($fc['category_name']) ?>">
															<span class="flashcard-category-tile-name"><?= $fc['category_name'] ?></span>
															<span class="flashcard-category-tile-count"><?= (int)$fc['total'] ?> Question<?= $fc['total'] == 1 ? '' : 's' ?></span>
														</a>
													<?php } ?>
												</div>
											</div>
										<?php } else { ?>
										<div class="flashcard-wrap">
											<?php if ($total) { ?>
												<div class="flashcard-toolbar">
													<div class="flashcard-count">
														<i class="fa fa-clone" aria-hidden="true"></i>
														<span id="flashcard-current">1</span>/<?= (int)$total ?>
													</div>

													<div class="flashcard-jump">
														<label for="flashcard-jump-select"></label>
														<select id="flashcard-jump-select">
															<?php for ($n = 1; $n <= (int)$total; $n++) { ?>
																<option value="<?= $n ?>"><?= $n ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
											<?php } ?>

											<div id="big-image" class="new-qs-slider flashcard-slider">
												<?php
												if ($total) {
													$i = 1;
													foreach ($questions as $f) {

														// A flashcard never shows the options - only the correct answer's
														// text and the explanation, and each is dropped when empty.
														$answer_text = "";
														if ($f['answer'] && $alphabet[$f['answer']]) {
															$ind = 'option_' . strtolower($alphabet[$f['answer']]);
															if ($f[$ind]) {
																$answer_text = html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])));
															}
														}

														$explanation_text = "";
														if ($f['explanation']) {
															$explanation_text = html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['explanation']))));
														}

														// Which flashcard sub-category this specific question sits
														// under - can differ per question when the deck isn't
														// filtered down to a single sub-category.
														$card_cat_name = $f['flashcard_cat_id']
															? getFldValueById("category", "category_name", array("cat_id" => $f['flashcard_cat_id']))
															: $flashcard_cat_name;
												?>
													<div class="row flashcard">
														<item>
															<div class="col-md-12 flashcard-box">

																<div class="flashcard-card flashcard-question-card">
																	<div class="flashcard-eyebrow">Question</div>
																	<div class="flashcard-question">
																		<?=
																			html_entity_decode(
																				html_entity_decode(
																					str_replace("&#92;", "\\", $f['question'])
																				)
																			)
																		?>
																	</div>
																</div>

																<div class="flashcard-nav view-ans">
																	<a class="slick-prev flashcard-nav-btn" href="javascript://" aria-label="Previous card"><i class="fa fa-angle-left" aria-hidden="true"></i></a>

																	<?php if ($answer_text || $explanation_text) { ?>
																		<a class="flashcard-btn-view-ans"
																			id="flashcard-btn-<?= $i ?>"
																			href="javascript://"
																			aria-expanded="false"
																			aria-controls="ansdiv_<?= $i ?>"
																			onClick="showFlashcardAnswer(<?= $i ?>);"><i class="fa fa-eye" aria-hidden="true"></i> See Answer</a>
																	<?php } ?>

																	<a class="slick-next flashcard-nav-btn" href="javascript://" aria-label="Next card"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
																</div>

																<div class="flashcard-reveal" id="ansdiv_<?= $i ?>">
																	<?php if ($answer_text || $explanation_text) { ?>
																		<div class="flashcard-card flashcard-answer-card">
																			<?php if ($answer_text) { ?>
																				<div class="flashcard-card-label flashcard-label-answer"><i class="fa fa-check-circle" aria-hidden="true"></i> Answer</div>
																				<div class="flashcard-answer"><?= $answer_text ?></div>
																			<?php } ?>

																			<?php if ($answer_text && $explanation_text) { ?>
																				<hr class="flashcard-divider">
																			<?php } ?>

																			<?php if ($explanation_text) { ?>
																				<div class="flashcard-card-label flashcard-label-explanation"><i class="fa fa-info-circle" aria-hidden="true"></i> Explanation</div>
																				<div class="flashcard-explanation-box">
																					<div class="flashcard-explanation-body"><?= $explanation_text ?></div>
																				</div>
																			<?php } ?>
																		</div>
																	<?php } ?>
																</div>
															</div>
														</item>
													</div>
												<?php
													$i++;
												}
											}
												?>
											</div>
										</div>
									<?php
									}
									} ?>
									<?php
									break;

								case "progress-report":

									if ($_GET['cat_id'] == 2000) {
										$img_path = $configs['template_path'] . "images/icon/download.png";
										$cat_id   = $_GET['cat_id'];
										$form_url = $configs['vpath'] . "generate-pdf";
									}
									?>

									<?php
									if ($_GET['cat_id'] == 2000) { ?>
										<div class="prog-report-heading">
											<div class="prog-report-title">
												<h2>Progress Report: Overview</h2>

											</div>
											<div class="prog-report-download">
												<form action="<?= $form_url ?>" method="POST" enctype="multipart/form-data">
													<input type="hidden" name="cat_id" value="<?= $cat_id ?>">
													<button type="submit" class="image-btn img-download">
														<img src="<?= $img_path ?>" alt="ASVAB tutoring Download Icon">
													</button>
												</form>
											</div>
										</div>
										<?php
									} else {
										$img_path = $configs['template_path'] . "images/icon/download.png";
										$form_url = $configs['vpath'] . "generate-pdf";
										$cat_id   = $_GET['cat_id'];

										if ($_GET['cat_id'] !== 2000) {
										?>
											<div class="prog-report-heading">
												<div class="prog-report-title">
													<h2>Progress Report: <? echo $cat_name; ?></h2>

												</div>
												<div class="prog-report-download">
													<form action="<?= $form_url ?>" method="POST" enctype="multipart/form-data">
														<input type="hidden" name="cat_id" value="<?= $cat_id ?>">
														<button type="submit" class="image-btn img-download">
															<img src="<?= $img_path ?>" alt="ASVAB tutoring Download Icon">
														</button>
													</form>
												</div>
											</div>

									<?php
										}
									}

									$is_staging = "";
									if ($_SESSION['test_id'] == 27) {
										$is_staging = "staging.";
									} else {
										$is_staging = "staging-";
									}

									if ($_SESSION['test_id'] >= 21 && $_SESSION['test_id'] <= 26) { //officer's candidate
										$prev_report_link = "https://" . $is_staging . "officers.asvab-tutoring.com/login/";
									} else if ($_SESSION['test_id'] == 27) { //asvab tutor
										$prev_report_link = "https://www." . $is_staging . "asvab-tutoring.com/online-study/login/";
									} else if ($_SESSION['test_id'] == 29) { //asvab gt
										$prev_report_link = "https://" . $is_staging . "prep.asvab-tutoring.com/login/";
									}
									?>
									<?
									if (isset($_SESSION['old_user_id']) && !empty($_SESSION['old_user_id'])) {
									?>
										<div class="mob-tab-menu nav-pred-prog" id="old-prog">
											<a href="<?php echo $prev_report_link . $_SESSION['email'] . "/" . $_SESSION['old_user_id']; ?>" target="_blank" class="progress-report-btn">
												📄 View Old Progress Report
											</a>
										</div>
									<?
									}
									?>

									<div class="tab-content" style='padding-top:20px;padding-bottom:20px'>
										<div id="overview" class="tab-pane fade in active overview-con">
											<?
											include("../template/" . $configs['template'] . "/tmpl_report.php");
											?>
										</div>
									</div>


									<?

									break;

								case "prediction-test":

									$show_pt = (int)getFldValueById("user", "pt", array("user_id" => $_SESSION['user_id']));

									if ($show_pt == 1) { ?>

										<?php
										if ($alert) { ?>
											<h3 class="mt-60"><?= $alert ?></h3>
										<?php
										} else { ?>

											<?php
											$course_name = "";
											if ($_SESSION['test_name'] && in_array($_SESSION['test_id'], [24, 27, 40])) {
												$course_name = $_SESSION['test_name'];
											}
											?>

											<div class="predic-title">
												<h2><?= $course_name ?></h2>
												<h3><?= $db_value['test_name'] ?></h3>
												<p class="pad-x-15">
													You are requested to answer <?= $total ?> Questions to get
													<u><?= $db_value['cat_name'] ?></u> Score.
												</p>
											</div>

											<form method="post" id="form">
												<input type="hidden" name="time" id="time">

												<div id="big-image">

													<?php
													$n = 0;
													$i = 0;
													$c = $_GET['child_cat_id']; ?>
													<div class="row">
														<div class="col-md-12 text-center">
															<h3>
																<?= $db_child_category['category_name'] ?>
																<span class="badge badge-success">
																	<?= $prediction_cat[$db_child_category['cat_id']]['total'] ?> Questions
																</span>
															</h3>
														</div>
													</div>

													<div class="row">
														<div class="col-md-12">
															<hr>
														</div>
													</div>

													<div class="col-md-12 prediction-test-box">
														<table class="table table-borderless prediction-test-table">

															<?php
															if (in_array($c, $story_question)) { ?>

																<?php
																$total_cat_ques = 0;
																foreach ($story_list[$c] as $d) {
																	$story[$d['story_id']] = str_replace("&nbsp;", "", html_entity_decode($story[$d['story_id']]));
																?>
																	<tr>
																		<td colspan="3" class="story_td">
																			<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&nbsp;", "", $story[$d['story_id']])))) ?>
																			<small class="story-id">[<?= $d['story_id'] ?>]</small><br>
																		</td>
																	</tr>

																	<?php foreach ($question_list[$d['story_id']] as $f) {
																		$total_cat_ques++;
																		$i++;
																		$badge = "";
																	?>
																		<tr class="question-row">
																			<td class="question_no">
																				<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($f['qid']) ?>"><?= $i ?></span>
																			</td>
																			<td class="questions" colspan="2">
																				<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question'])))) ?>
																			</td>
																		</tr>

																		<?php
																		$flag = false;
																		for ($j = 1; $j <= $option_no; $j++) {
																			$ans = $alphabet[$j];
																			$ind = 'option_' . strtolower($ans);
																			$chk = "";
																			if ($f[$ind] || in_array($c, [84])) {
																				if ($j == (int)$user_answers[$f['qid']]) {
																					$chk = "checked";
																					$flag = true;
																				}
																		?>
																				<tr class="option-row">
																					<td class="abcd"><?= $ans ?></td>
																					<td class="radio-btn">
																						<input type="radio" <?= $chk ?>
																							name="answer<?= $i ?>" id="<?= $i ?>"
																							onClick="getFinalAnswer('<?= $f['qid'] ?>','<?= $c ?>','<?= $j ?>');">
																					</td>
																					<td class="options">
																						<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) ?>
																					</td>
																				</tr>
																		<?php }
																		} ?>

																		<?php if ($flag) { ?>
																			<tr>
																				<td colspan="3">
																					<div id="response_<?= $f['qid'] ?>" class="response-text badge-correct">Answer Saved</div>
																				</td>
																			</tr>
																		<?php } ?>

																		<tr>
																			<td class="show-question-id" colspan="3" id="response_<?= $f['qid'] ?>"></td>
																		</tr>

																	<?php } // question_list 
																	?>
																<?php } // story_list 
																?>

															<?php } else { ?>

																<?php
																$total_cat_ques = 0;
																foreach ($question_test[$c] as $f) {
																	$total_cat_ques++;
																	$i++;
																	$badge = "";
																?>
																	<tr>
																		<td class="question_no">
																			<span class="badge <?= $badge ?>" data-tooltip="<?= htmlspecialchars($f['qid']) ?>"><?= $i ?></span>
																		</td>
																		<td colspan="2" class="questions">
																			<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question'])))) ?>
																		</td>
																	</tr>

																	<?php
																	$flag = false;
																	for ($j = 1; $j <= $option_no; $j++) {
																		$ans = $alphabet[$j];
																		$ind = 'option_' . strtolower($ans);
																		$chk = "";
																		if ($f[$ind] || in_array($c, [83, 86])) {
																			if ($j == $f['user_ans']) {
																				$chk = "checked";
																				$flag = true;
																			}
																	?>
																			<tr>
																				<td class="abcd"><?= $ans ?></td>
																				<td class="radio-btn">
																					<input type="radio" <?= $chk ?>
																						name="answer<?= $i ?>" id="<?= $i ?>"
																						onClick="getFinalAnswer('<?= $f['qid'] ?>','<?= $c ?>','<?= $j ?>');">
																				</td>
																				<td class="options">
																					<?= html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) ?>
																				</td>
																			</tr>
																	<?php }
																	} ?>

																	<?php if ($flag) { ?>
																		<tr>
																			<td class="" colspan="3">
																				<div id="response_<?= $f['qid'] ?>" class="response-text badge-correct">Answer Saved</div>
																			</td>
																		</tr>
																	<?php } ?>

																	<tr>
																		<td colspan="4" class="show-question-id" id="response_<?= $f['qid'] ?>"></td>
																	</tr>

																<?php } // question_test 
																?>

															<?php
															} 	?>

														</table>
													</div>
												</div>

												<div id="list"></div>

												<div class="sub-btn-test text-center total-qs">
													<div id="pred-total">
														<?= $alert_total ?>
													</div>
												</div>

											</form>

										<?php
										} // no alert 
										?>

									<?php
									} else {
									?>
										<div class="alert alert-warning" style="color: red; font-size: 16px; padding: 15px; margin-top: 20px;">
											<strong>Access Denied:</strong> You do not have permission to access this section.
											<a href="https://staging-staging-test-prep.asvab-tutoring.com/contact-us" style="color: red; text-decoration: underline;">
												Please contact your instructor for assistance.
											</a>
										</div>
									<?
									}
									break;
							} //ednd of switch
							//end of result parameter
						} else {

							if ($_GET['cat_id']) {
								if ($_GET['cat_id'] == 4444) {
									foreach ($prediction as $c) {
									?>
										<div class="col-sm-6 col-lg-4">
											<div class="card desh-top-item bg-flat-color-5 download-box">
												<div class="card-body pb-0">
													<div class="col-md-12 text-center">
														<div class="icon-image">
															<a href='<?= $configs['vpath'] . "exam/" . $_SESSION['test_id'] . "-" . $c['cat_id'] . "-" . seo_url($c['category_name']); ?>'> <img class="img-responsive" src="<?= $configs['vpath'] ?>img/lesson.png" /></a>
														</div>
													</div>
													<p class="text-light">
														<a class='ocust-text' href='<?= $configs['vpath'] . "exam/prediction-test/" . $c['Predic_test_id'] . "-" . seo_url($c['test_name']); ?>'><?= $c['test_name']; ?></a>
													</p>
												</div>

											</div>
										</div>
									<?
									}
								} else {  // the following lodad a category with lesson list (Not full lesson practice)

									foreach ($lesson as $c) {

										if ($c['Lesson_title']) {
											$style = "height: 235px;";
										}

									?>
										<div class="col-sm-6 col-lg-4">
											<div class="card desh-top-item bg-flat-color-5 download-box" style="<?= $style ?>">
												<div class="card-body pb-0">
													<div class="col-md-12 text-center">
														<div class="icon-image">
															<a href="<?= $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $c['lesson_id'] . "-" . seo_url($c['Lesson_name']); ?>">
																<img class="img-responsive" src="<?= $configs['vpath'] ?>img/lesson.png" alt="ASVAB_tutorig_lesson" />
															</a>
														</div>
													</div>
													<a class="ocust-text d-block"
														style="display:block; max-width:100%;"
														href="<?= $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $c['lesson_id'] . "-" . seo_url($c['Lesson_name']); ?>">
														<h4><?= $c['Lesson_name'] ?></h4>

														<p class="lesson-tilte"><?= $c['Lesson_title']; ?></p>


													</a>
												</div>
											</div>
										</div>
									<?
									}
								}
							} else {
								foreach ($categories as $c) {
									?>
									<div class="col-sm-6 col-lg-4">
										<div class="card desh-top-item bg-flat-color-5 download-box">
											<div class="card-body pb-0">
												<div class="col-md-12 text-center">
													<div class="icon-image">

														<a href='<?= $configs['vpath'] . "exam/" . $_SESSION['test_id'] . "-" . $c['cat_id'] . "-" . seo_url($c['category_name']); ?>'> <img class="img-responsive" src="<?= $configs['vpath'] ?>img/lesson.png" /></a>

													</div>
												</div>
												<?php
												if (substr_count(strtolower($c['category_name']), "prediction")) {
												?>
													<p class="text-light"><a class='ocust-text' href='<?= $configs['vpath'] . "exam/prediction-test/" . $c['cat_id'] . "-" . seo_url($c['category_name']); ?>'><?= $c['category_name']; ?></a></p>

												<?
												} elseif (substr_count(strtolower($c['category_name']), "flashcards")) {
												?>
													<p class="text-light"><a class='ocust-text' href='<?= $configs['vpath'] . "exam/" . seo_url($c['category_name']) . "-" . $c['cat_id']; ?>'><?= $c['category_name']; ?></a></p>
												<?
												} else {
												?>
													<p class="text-light"><a class='ocust-text' href='<?= $configs['vpath'] . "exam/" . $_SESSION['test_id'] . "-" . $c['cat_id'] . "-" . seo_url($c['category_name']); ?>'><?= $c['category_name']; ?></a></p>

												<?
												}
												?>


											</div>

										</div>
									</div>
								<?
								}

								$pred_cat_id = 5000;
								$pred_cat_name = "Prediction Test";
								?>

						<?
							}
						}
						?>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>

<!-- Feedback Submission Toast -->
<?php if (isset($feedback_submitted) && $feedback_submitted): ?>
	<div id="toast" class="toast success">
		<span class="toast-icon">✓</span>
		<div class="toast-content">
			<div class="toast-title">Thank you!</div>
			<div class="toast-message">Your feedback has been submitted successfully.</div>
		</div>
	</div>
<?php endif; ?>

<!-- Modal (pure CSS/JS, no Bootstrap dependency) -->
<div class="custom-modal-overlay" id="missedModalOverlay" aria-hidden="true" role="dialog" aria-modal="true">
	<div class="custom-modal" role="document" aria-labelledby="missedModalTitle">
		<div class="custom-modal-header">
			<h3 id="missedModalTitle" class="custom-modal-title">Unanswered Questions</h3>
			<button class="close-x" id="closeModalBtn" aria-label="Close">&times;</button>
		</div>
		<div class="custom-modal-body" id="missedMessage">
			<!-- Filled dynamically -->
		</div>
		<div class="custom-modal-footer">
			<button class="btn btn-secondary" id="stayBtn">Stay on Page</button>
			<button class="btn btn-primary" id="continueBtn">Continue</button>
		</div>
	</div>
</div>

<script>
	const form = document.querySelector('#feedbackModal form');
	const ratingHint = document.getElementById('ratingHint');

	form.addEventListener('submit', function(e) {
		const selectedRating = document.querySelector('input[name="rating"]:checked');

		if (!selectedRating) {
			e.preventDefault();
			ratingHint.style.display = 'block';
		}
	});

	document.querySelectorAll('input[name="rating"]').forEach(input => {
		input.addEventListener('change', function() {
			ratingHint.style.display = 'none';
		});
	});
</script>


<script>
	function showDiv(x, y = 0, z = 0, a = 0) {
		var ord = x;
		var div = document.getElementById("ansdiv_" + x);
		var currentDisplay = window.getComputedStyle(div).display;

		if (currentDisplay === "none") {
			viewAnswer(y, z, a, ord);
			div.style.display = "block";
		} else {
			div.style.display = "none";
		}
	}

	// Flashcards reveal the answer/explanation cards in place - no progress is
	// recorded for them, so this deliberately does not call viewAnswer().
	function showFlashcardAnswer(x) {
		var div = document.getElementById("ansdiv_" + x);
		var btn = document.getElementById("flashcard-btn-" + x);

		if (!div) {
			return;
		}

		var open = div.classList.contains("is-open");

		div.classList.toggle("is-open", !open);
		if (btn) {
			btn.setAttribute("aria-expanded", open ? "false" : "true");
			btn.innerHTML = open
				? '<i class="fa fa-eye" aria-hidden="true"></i> See Answer'
				: '<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Answer';
		}

		// slick caches the slide height, so let it grow with the revealed cards
		var list = document.querySelector(".flashcard-slider .slick-list");
		if (list) {
			list.style.height = "auto";
		}
	}

	function collapseFlashcards() {
		var opened = document.querySelectorAll(".flashcard-reveal.is-open");
		for (var i = 0; i < opened.length; i++) {
			opened[i].classList.remove("is-open");
		}

		var buttons = document.querySelectorAll(".flashcard-btn-view-ans");
		for (var j = 0; j < buttons.length; j++) {
			buttons[j].setAttribute("aria-expanded", "false");
			buttons[j].innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i> See Answer';
		}
	}

	// Moving to another card collapses whatever was revealed on the previous one
	document.addEventListener("click", function(e) {
		if (!e.target.closest) {
			return;
		}
		if (!e.target.closest(".flashcard-slider .slick-prev, .flashcard-slider .slick-next")) {
			return;
		}

		collapseFlashcards();
	});

	// jQuery and slick load in the footer, so wait for the whole page before
	// wiring the counter and the jump-to-card select onto the slider.
	window.addEventListener("load", function() {
		var slider = document.querySelector(".flashcard-slider");
		var counter = document.getElementById("flashcard-current");
		var jump = document.getElementById("flashcard-jump-select");

		if (!slider || !jump || typeof window.jQuery === "undefined") {
			return;
		}

		var $slider = window.jQuery(slider);
		if (!$slider.hasClass("slick-initialized")) {
			return;
		}

		// If slick measured the slider while its container was still
		// settling (webfonts, layout shifts on mobile), slides can end up
		// stacked on top of each other instead of side by side. Forcing a
		// recompute once everything is loaded - and again on resize /
		// orientation change - keeps the slide widths honest.
		var refreshFlashcardSlider = function() {
			$slider.slick("setPosition");
		};
		refreshFlashcardSlider();
		window.addEventListener("resize", refreshFlashcardSlider);
		window.addEventListener("orientationchange", refreshFlashcardSlider);

		// Fires for the arrows and for slickGoTo alike
		$slider.on("afterChange", function(event, slick, index) {
			if (counter) {
				counter.innerHTML = index + 1;
			}
			jump.value = index + 1;
			collapseFlashcards();
		});

		jump.addEventListener("change", function() {
			var target = parseInt(jump.value, 10);
			if (target > 0) {
				$slider.slick("slickGoTo", target - 1);
			}
		});
	});
</script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const toast = document.getElementById('toast');

		if (toast) {
			setTimeout(() => {
				toast.classList.add('hide');

				setTimeout(() => {
					toast.remove();
				}, 350);
			}, 3500);
		}
	});
</script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// make sure this produces an integer in server-side render
		const totalQuestions = parseInt(<?= $total_cat_ques ?>, 10) || 0;
		let nextUrl = null;

		const overlay = document.getElementById('missedModalOverlay');
		const missedMessageEl = document.getElementById('missedMessage');
		const continueBtn = document.getElementById('continueBtn');
		const stayBtn = document.getElementById('stayBtn');
		const closeModalBtn = document.getElementById('closeModalBtn');

		function getMissedQuestions() {
			const missed = [];
			for (let i = 1; i <= totalQuestions; i++) {
				if (!document.querySelector(`input[name="answer${i}"]:checked`)) {
					missed.push(i);
				}
			}
			return missed;
		}

		function openModal(missed, url) {
			nextUrl = url;
			const badges = missed.map(n => `<span class="badge-q">${n}</span>`).join(' ');
			missedMessageEl.innerHTML =
				`<p>You missed the following question(s):</p>
       <div>${badges}</div>
       <p style="margin-top:10px;font-size:13px;color:#555">Click <strong>Continue</strong> to leave this page or <strong>Stay on Page</strong> to answer them.</p>`;
			overlay.classList.add('open');
			overlay.setAttribute('aria-hidden', 'false');
			continueBtn.focus();
			document.addEventListener('keydown', handleKeyDown);
		}

		function closeModal() {
			overlay.classList.remove('open');
			overlay.setAttribute('aria-hidden', 'true');
			nextUrl = null;
			document.removeEventListener('keydown', handleKeyDown);
		}

		function handleKeyDown(e) {
			if (e.key === 'Escape') closeModal();
			if (e.key === 'Enter') {
				// if Enter and focus is not inside input/textarea, treat as continue
				if (document.activeElement && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
					if (nextUrl) window.location.href = nextUrl;
				}
			}
		}

		// Delegated click handler for nav tabs (works even when tabs are rendered later)
		const navContainer = document.querySelector('.mob-tab-menu .nav-tabs');
		if (navContainer) {
			navContainer.addEventListener('click', function(e) {
				const a = e.target.closest('a');
				if (!a) return;
				e.preventDefault();
				const url = a.getAttribute('href');
				const missed = getMissedQuestions();
				if (missed.length > 0) openModal(missed, url);
				else window.location.href = url;
			});
		} else {
			// Fallback: attach to all links inside .mob-tab-menu
			const navLinks = document.querySelectorAll('.mob-tab-menu a');
			navLinks.forEach(link => {
				link.addEventListener('click', function(e) {
					e.preventDefault();
					const url = this.getAttribute('href');
					const missed = getMissedQuestions();
					if (missed.length > 0) openModal(missed, url);
					else window.location.href = url;
				});
			});
		}


		// Next button handling (supports <a href="..."> or data-href)
		const nextBtn = document.getElementById('next-button');
		if (nextBtn) {
			nextBtn.addEventListener('click', function(e) {
				e.preventDefault();
				const url = this.getAttribute('href') || this.dataset.href;
				const missed = getMissedQuestions();
				if (missed.length > 0) openModal(missed, url);
				else window.location.href = url;
			});
		}

		// Modal controls
		continueBtn.addEventListener('click', function() {
			if (nextUrl) window.location.href = nextUrl;
		});
		stayBtn.addEventListener('click', closeModal);
		closeModalBtn.addEventListener('click', closeModal);
		overlay.addEventListener('click', function(e) {
			if (e.target === overlay) closeModal();
		});
	});
</script>



<script>
	var examDurationMinutes = <?= (int)$exam_duration ?>;
	var totalSeconds = examDurationMinutes * 60;

	function startTimer() {
		var timerInterval = setInterval(function() {
			var minutes = Math.floor(totalSeconds / 60);
			var seconds = totalSeconds % 60;

			document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
			document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');

			if (totalSeconds <= 0) {
				clearInterval(timerInterval);
				alert("Time is over! Your exam has ended.");
			}

			totalSeconds--;
		}, 1000);
	}

	// Start timer on page load
	window.onload = startTimer;
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



	(function() {
		// If your questions live in a wrapper, put its selector here:
		const SCOPE = document.querySelector('#exam-questions') || document;

		function countAnswered() {
			// radios named like answer1, answer2, ...
			const radios = SCOPE.querySelectorAll('input[type="radio"][name^="answer"]');
			const allNames = new Set();
			const checkedNames = new Set();

			radios.forEach(r => {
				allNames.add(r.name);
				if (r.checked) checkedNames.add(r.name);
			});

			return {
				answered: checkedNames.size,
				total: allNames.size
			};
		}

		function updateUI() {
			const {
				answered,
				total
			} = countAnswered();

			// Optional: show a global progress somewhere if you add this span
			const out = document.getElementById('answeredProgress');
			if (out) out.textContent = `${answered}/${total}`;

			// Optional: update the ACTIVE tab text "(x/y)" live
			const activeLink = document.querySelector('.nav-tabs .alert_msg.active .answered-cat');
			if (activeLink) {
				const t = activeLink.textContent;
				activeLink.textContent = /\(\d+\/\d+\)/.test(t) ?
					t.replace(/\(\d+\/\d+\)/, `(${answered}/${total})`) :
					`${t} (${answered}/${total})`;
			}
		}

		document.addEventListener('DOMContentLoaded', updateUI);
		// Recount whenever a choice is selected
		document.addEventListener('change', e => {
			if (e.target.matches('input[type="radio"][name^="answer"]')) updateUI();
		});
	})();
</script>

<script>
	window.onload = function() {
		setTimeout(function() {
			let modal = document.getElementById("feedbackModal");
			if (modal) {
				modal.style.display = "flex";
			}
		}, 5000); // 5 seconds
	}

	function closeModal() {
		document.getElementById("feedbackModal").style.display = "none";
	}
</script>

<script>
	function closeMultiCourseAlert() {
		const alert = document.getElementById('multiCourseAlert');

		if (alert) {
			alert.remove();
			localStorage.setItem('multiCourseAlertClosed', '1');
		}
	}

	document.addEventListener('DOMContentLoaded', function() {
		const alert = document.getElementById('multiCourseAlert');

		if (
			alert &&
			localStorage.getItem('multiCourseAlertClosed') === '1'
		) {
			alert.remove();
		}
	});
</script>

<style>
	.rating-hint {
		display: none;
		margin-top: 10px;
		padding: 8px 12px;
		border-radius: 8px;
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #b91c1c;
		font-size: 13px;
		font-weight: 500;
		text-align: center;
		animation: fadeIn 0.2s ease;
	}

	@keyframes fadeIn {
		from {
			opacity: 0;
			transform: translateY(-3px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	/* Overlay (modern glass effect) */
	.modal-overlay {
		position: fixed;
		inset: 0;
		background: rgba(15, 15, 15, 0.6);
		backdrop-filter: blur(6px);
		/* display: flex; */
		display: none;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		padding: 16px;
	}

	/* Modal Box */
	.modal-box {
		background: #ffffff;
		width: 100%;
		max-width: 420px;
		border-radius: 16px;
		padding: 24px;
		position: relative;
		text-align: center;
		box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
		animation: popIn 0.18s ease-out;
		font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
	}

	/* Title */
	.modal-box h4 {
		margin-bottom: 16px;
		font-size: 16px;
		font-weight: 600;
		color: #111827;
		line-height: 1.4;
	}

	/* Textarea */
	.modal-box textarea {
		width: 100%;
		margin-top: 10px;
		padding: 12px 10px;
		border: 1px solid #e5e7eb;
		border-radius: 10px;
		font-size: 14px;
		background: #f9fafb;
		resize: none;
		outline: none;
		transition: all 0.2s ease;
	}

	.modal-box textarea:focus {
		border-color: #6366f1;
		background: #ffffff;
		box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
	}

	/* Stars */
	.stars {
		display: flex;
		flex-direction: row-reverse;
		justify-content: center;
		gap: 6px;
		margin: 12px 0;
	}

	.stars input {
		display: none;
	}

	.stars label {
		font-size: 26px;
		color: #d1d5db;
		cursor: pointer;
		transition: transform 0.15s ease, color 0.15s ease;
	}

	.stars label:hover {
		transform: scale(1.1);
	}

	.stars label:hover,
	.stars label:hover~label,
	.stars input:checked~label {
		color: #fbbf24;
	}

	/* Button (modern gradient feel) */
	.btn-submit {
		margin-top: 16px;
		padding: 11px 14px;
		background: linear-gradient(135deg, #4f46e5, #6366f1);
		color: #fff;
		border: none;
		cursor: pointer;
		border-radius: 10px;
		font-size: 14px;
		font-weight: 500;
		width: 100%;
		transition: transform 0.15s ease, box-shadow 0.15s ease;
	}

	.btn-submit:hover {
		transform: translateY(-1px);
		box-shadow: 0 10px 20px rgba(99, 102, 241, 0.25);
	}

	/* Close button */
	.close-btn {
		position: absolute;
		top: 2px;
		right: 2px;
		background: transparent;
		border: none;
		font-size: 26px;
		cursor: pointer;
		color: #9ca3af;
		transition: 0.2s;
	}

	.close-btn:hover {
		color: #111827;
	}

	/* Animation */
	@keyframes popIn {
		from {
			transform: scale(0.96);
			opacity: 0;
		}

		to {
			transform: scale(1);
			opacity: 1;
		}
	}

	.toast {
		position: fixed;
		top: 24px;
		right: 24px;
		min-width: 320px;
		max-width: 380px;
		background: #fff;
		border-radius: 14px;
		padding: 14px 16px;
		display: flex;
		align-items: flex-start;
		gap: 12px;
		box-shadow:
			0 10px 25px rgba(0, 0, 0, 0.12),
			0 4px 8px rgba(0, 0, 0, 0.06);
		border-left: 4px solid #22c55e;
		z-index: 10000;
		animation: slideIn 0.35s ease forwards;
	}

	.toast-icon {
		width: 28px;
		height: 28px;
		border-radius: 50%;
		background: #22c55e;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: 700;
		flex-shrink: 0;
	}

	.toast-title {
		font-size: 14px;
		font-weight: 600;
		color: #111827;
		margin-bottom: 2px;
	}

	.toast-message {
		font-size: 13px;
		color: #6b7280;
		line-height: 1.4;
	}

	.toast.hide {
		animation: slideOut 0.35s ease forwards;
	}

	@keyframes slideIn {
		from {
			opacity: 0;
			transform: translateX(100%);
		}

		to {
			opacity: 1;
			transform: translateX(0);
		}
	}

	@keyframes slideOut {
		from {
			opacity: 1;
			transform: translateX(0);
		}

		to {
			opacity: 0;
			transform: translateX(100%);
		}
	}

	@media (max-width: 480px) {
		.toast {
			right: 16px;
			left: 16px;
			min-width: auto;
			max-width: none;
		}
	}
</style>

<style>
	.pred-test-date-row {
		padding-top: 12px;
		/* increase/decrease as you like */
	}

	.filter-btn {
		flex: 1;
		/* equal width */
		text-align: center;
		padding: 6px 0;
		border-radius: 30px;
		background: linear-gradient(135deg, #667eea, #764ba2);
		color: #fff;
		font-size: 14px;
		font-weight: 500;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		text-decoration: none;
		display: inline-block;
		max-width: 120px;
		/* optional */
	}

	.filter-btn:hover {
		transform: translateY(-3px);
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
	}

	.filter-btn.active {
		border: 2px solid rgba(0, 0, 0, 0.25);
	}

	/* ALL - Blue */
	.filter-btn.all {
		background: linear-gradient(135deg, #667eea, #764ba2);
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
	}

	/* CORRECT - Green */
	.filter-btn.correct {
		background: linear-gradient(135deg, #28a745, #1e7e34);
		box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
	}

	/* WRONG - Red */
	.filter-btn.wrong {
		background: linear-gradient(135deg, #dc3545, #a71d2a);
		box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
	}
</style>

<style>
	.multi-course-alert {
		position: fixed;
		top: 110px;
		right: 25px;
		z-index: 2147483647;

		width: 360px;
		max-width: calc(100vw - 30px);

		background: #fff;
		border: 1px solid #ddd;
		border-left: 4px solid #007bff;
		border-radius: 6px;

		padding: 15px 40px 15px 18px;

		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);

		display: flex;
		align-items: flex-start;
	}

	.multi-course-alert-content strong {
		display: block;
		font-size: 16px;
		margin-bottom: 5px;
	}

	.multi-course-alert-content p {
		margin: 0;
		font-size: 14px;
		line-height: 1.5;
		color: #555;
	}

	.multi-course-alert-close {
		position: absolute;
		top: 8px;
		right: 10px;

		border: 0;
		background: transparent;

		font-size: 24px;
		line-height: 1;

		color: #777;
		cursor: pointer;
	}

	.multi-course-alert-close:hover {
		color: #000;
	}

	@media (max-width: 767px) {
		.multi-course-alert {
			top: 70px;
			right: 15px;
			left: 15px;
			width: auto;
		}
	}
</style>