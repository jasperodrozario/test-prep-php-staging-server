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
								<h3>Flashcards</h3>
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
										<script type="text/javascript" src="//code.jquery.com/jquery-1.6.4.js"></script>
										<div id="big-image" class="new-qs-slider">
											<?php
											if ($total) {
												$i = 1;
												foreach ($questions as $f) {
											?>
													<div class="row flashcard">
														<item>
															<div class="col-md-12 flashcard-box">
																<table class="table table-borderless flashcard-table">
																	<tr>
																		<td>
																			<?=
																			html_entity_decode(
																				html_entity_decode(
																					str_replace("&#92;", "\\", $f['question'])
																				)
																			)
																			?>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2">
																			<div class="col-md-12 view-ans"
																				style="margin-bottom:20px;margin-left:2px;text-align: center;">
																				<a class="slick-prev"></a>

																				<a class="btn btn-info flashcard-btn-view-ans"
																					href="javascript://"
																					onClick="showDiv(<?= $i ?>);">See Answer</a>

																				<a class="slick-next"></a>
																			</div>

																			<div class="col-md-12 mtq_explanation flashcard-explanation"
																				id="ansdiv_<?= $i ?>">
																				<?php if ($f['explanation']) { ?>
																					<br>
																					<?=
																					html_entity_decode(
																						html_entity_decode(
																							html_entity_decode(
																								str_replace("&#92;", "\\", $f['explanation'])
																							)
																						)
																					)
																					?>
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
											}
											?>
										</div>
									<?php
									}
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