<!--courses section-->
<div class="userprofile_wrapper">
	<div class="container">

		<div class="userprofile-body midle-conarea mr-t88">
			<div class="col-lg-1 col-md-1 cust-side">
			</div>

			<div class="col-lg-9 col-md-9 col-sm-12">
				<!-- <div class="prediction-overly">
          	 <p>You do not have access in it. <a href='<?= $configs['vpath'] ?>contact'>Contact to your tutor</a></p>
          	</div> -->
				<div class="panel dashbord-panel with-nav-tabs panel-default border-none">
					<div id="myHeader" class="panel-heading">
						<?
						if ($_GET['result']) {
							if ($_GET['result'] == 'final-test') {
								echo "Final Test";
							} elseif ($_GET['result'] == 'flashcards') {
								echo "Flashcards";
							} elseif (substr_count($_SERVER['REQUEST_URI'], "progress-report")) {

								echo "<a href='" . $configs['vpath'] . "exam/progress-report' style='color:#111'>Progress Report</a> ";
							} elseif ($_GET['result'] == 'view') {
								echo "<a href='" . $configs['vpath'] . "exam/progress-report' style='color:#111'>Progress Report</a> > Detail Info  ";
							}
						} elseif ($_GET['id'] && $_GET['page'] == 'category-list') {
							echo "Category List";
						} elseif ($_GET['cat_id'] && $_GET['id']) {
							echo ucwords(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $_GET['cat_name']));
						}

						if ($_GET['cat_id']) {
							if ($_GET['cat_id'] < 2000) {
								if ($_GET['result'] == "prediction-test") {
									echo "<div class=row><div class='col-md-9'>" . $db_value['test_name'] . "</div>";
						?>
									<div id='timer' class='pull-right time-dox2'>Timer : <label id='minutes'>00</label>m:<label id='seconds'>00</label>s</div>

						<?
									echo "</div>";
								} else {

									if ($_GET['result'] == 'result') {
										$db_value['category_name'] = "Progress Report > Detail Info";
									}

									$img_path = "https://officers.asvab-tutoring.com/template/template1/images/download_button_final.png";

									echo "<br>";

									echo "<div style='display: flex; justify-content: space-between; align-items: center;'>
                                <p>" . $db_value['category_name'] . "</p>
                                <form action='" . $configs['vpath'] . "generate-pdf' method='POST' enctype='multipart/form-data'>
                                    <input type='hidden' name='cat_id' value='" . $_GET['cat_id'] . "'>
                                    <input type='hidden' name='student_id' value='" . $_GET['student_id'] . "'>

                                    <button type='submit' class='image-btn'>     
                                        <img src='" . $img_path . "' alt='Download Icon'>
                                    </button>
                                </form>
                              </div>";
								}
							}


							if ($_GET['homework']) {
								echo "> Homework ";
							}
							if ($_GET['practice-test']) {
								echo " > Practice Test";
							}

							if ($_GET['cat_id'] == 2000) {
								$img_path = "https://officers.asvab-tutoring.com/template/template1/images/download_button_final.png";

								echo "<br>";

								echo "<div style='display: flex; justify-content: space-between; align-items: center;'>
                            <p>Overview</p>
                            <form action='" . $configs['vpath'] . "generate-pdf' method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='cat_id' value='" . $_GET['cat_id'] . "'>
                                <input type='hidden' name='student_id' value='" . $_GET['student_id'] . "'>
                                
                                <button type='submit' class='image-btn'>     
                                    <img src='" . $img_path . "' alt='Download Icon'>
                                </button>
                            </form>
                          </div>";
							}
						}

						?>
					</div>
					<div class="panel-body div-scroll">
						<!-----Start dashbard box-->
						<?

						if ($_GET['result']) {
							switch ($_GET['result']) {
								case "result":

									echo '<br><table class="table table-bordered table-striped">';
									echo "<tr><td>Subject</td><td >" . $db_value['cat_name'] . "</td><td>Date</td><td >" . showdate($date) . "</td></tr>";
									echo "<tr><td>Lesson</td><td>" . $db_value['lesson_name'] . "</td><td>Total Questions</td><td>" . $total . "</td></tr>";
									echo "<tr><td>Total Answered</td><td>" . $total_answer_given . "</td><td>Total Correct Answer</td><td>" . $total_correct . "</td></tr>
				    <tr><td>Wrong Answer</td><td>" . $total_wrong . "</td><td><b>Score(%)</b></td><td><b>" . round($db_value['score'])  . "</b></td></tr>";
									echo "</table><br>";
									echo "<h4>Question & Answer with clarification for your test on  " . $db_value['cat_name'] . "</h4>";
									echo "<hr>";

									echo '<table class="table table-borderless">';
									$i = 0;
									if (in_array($_GET['cat_id'], $story_question)) {
										foreach ($story_list as $d) {
											//                            echo "<h1>".$d['story_id']."</h1>";

											echo '<tr><td colspan=3 align=justify>' . html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&nbsp;", "", $story[$d['story_id']]))))) . '</td></tr>';

											foreach ($question_test[$d['story_id']] as $q) {
												$i++;
												echo '<tr><td width=5><span class="badge ">' . $i . '</span></td><td colspan=2>' . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) . "</td></tr>";

												$correct_ans = "";
												for ($j = 1; $j <= $option_no; $j++) {
													$ans = $alphabet[$j];
													$ind = 'option_' . strtolower($ans);
													if ($q[$ind] || $_GET['cat_id'] == 84) {
														if ($q['user_ans'] == $j) {
															$correct_ans = $alphabet[$j];
															$ans = '<span class="badge badge-primary">' . $alphabet[$j] . '</span>';
														}

														echo "<tr><td></td><td width=5>" . $ans . "</td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) . "</td></tr>";
													}
												}

												if (!$q['user_ans']) {
													$text = "<span class='badge_no'>No Answer</span>";
													echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>Correct answer is  " . $alphabet[$q['answer']] . " | " . $text . "</b></td></tr>";
												} elseif ($q['user_ans'] == $q['answer']) {
													$text = '<span class="badge badge-success">Correct</span>';
													echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . "</b></td></tr>";
												} else {
													$text = '<span class="badge badge-wrong">Wrong Answer</span>';
													echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . " | " . "Correct answer is  " . $alphabet[$q['answer']] . "</b></td></tr>";
												}


												if ($q['explanation']) {
													echo "<tr><td width=5></td><td class='text-align:left' colspan=2 style='color:blue'>Explanation: " . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) . "</td></tr>";
												}
												echo "<tr><td colspan=3><hr style='margin:0;'></td></tr>";
											}
										}
									} else {
										foreach ($question_test as $q) {
											$i++;
											echo '<tr><td width=5><span class="badge ">' . $i . '</span></td><td colspan=2>' . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) . "</td></tr>";

											$correct_ans = "";
											for ($j = 1; $j <= $option_no; $j++) {
												$ans = $alphabet[$j];
												$ind = 'option_' . strtolower($ans);
												if ($q[$ind] || in_array($_GET['cat_id'], array(83, 86, 64))) {
													if ($q['user_ans'] == $j) {
														$correct_ans = $alphabet[$j];
														$ans = '<span class="badge badge-primary">' . $alphabet[$j] . '</span>';
													}

													echo "<tr><td></td><td width=5>" . $ans . "</td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) . "</td></tr>";
												}
											}

											if (!$q['user_ans']) {
												$text = "<span class='badge_no'>No Answer</span>";
												echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . " | Correct answer is  " . $alphabet[$q['answer']] . "</b></td></tr>";
											} elseif ($q['user_ans'] == $q['answer']) {
												$text = '<span class="badge badge-success">Correct</span>';
												echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . "</b></td></tr>";
											} else {
												$text = '<span class="badge badge-wrong">Wrong Answer</span>';
												echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . " | Correct answer is  " . $alphabet[$q['answer']] . "</b></td></tr>";
											}

											if ($q['explanation']) {
												echo "<tr><td width=5></td><td class='text-align:left' colspan=2 style='color:blue'>Explanation: " . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) . "</td></tr>";
											}
											echo "<tr><td colspan=3><hr style='margin:0;'></td></tr>";
										}
									}
									echo "</table>";

									break;

								case "view":

									if ($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) { // full asvab score card
										echo "<div class='row text-center'><h4>PICAT SCORE REPORT</h4></div>";

										foreach ($full_asvab_result_list as $d) {
											if ($d['pred_name'] == "VE") {
												$ve_score = $d['score'];
											}
										}
										?>

										<div class="asvab-container">

											<table class="asvab-table">
												<tr>
													<td width='180px'><strong>Testing Session Date:</strong></td>
													<td><?= $date?></td>
												</tr>
												<tr>
													<td><strong>Applicant Name:</strong></td>
													<td><?=$_SESSION['name']  ?></td>
												</tr>
											</table>

											<hr style="border: none; height: 2px; background-color: #ccc;">

											<table class="asvab-table">
												<tr>
													<td rowspan="2" class="asvab-label-cell">Standard Scores:</td>
													<?
													foreach ($pred_test_report as $key => $d) {
														$short_code = getFldValueById("category", "shortcode", array("cat_id" => $d['cat_id']));
													?>
														<td><strong><? echo $short_code; ?></strong></td>
													<?
													}
													?>
													<td><strong>VE</strong></td>
												</tr>
												<tr>
													<?
													foreach ($pred_test_report as $key => $d) {
													?>
														<td><? echo round($d['score']); ?></td>
													<?
													}
													?>
													<td><? echo round($ve_score); ?></td>
												</tr>
											</table>

											<hr style="border: none; height: 2px; background-color: #ccc;">

											<div class="asvab-section-title">Composite Scores:</div>

											<hr style="border: none; height: 2px; background-color: #ccc;">
											
											<table class="asvab-table">
												<?
												foreach ($full_asvab_result_list as $d) {
													if ($d['pred_name'] != "VE" && $d['pred_name'] != "AFQT") {
													?>
													<tr>
														<td rowspan="2" class="asvab-label-cell"><?php echo str_replace("GT", "", $d['pred_name']); ?></td>
														<td><strong>GT</strong></td>
													</tr>
													<tr class="bottom_border">
														<td><? echo round($d['score']);?></td>
													</tr>
													<?
													}
													else if($d['pred_name'] == "AFQT"){
														$afqt_score = round($d['score']);
													}
												}
												?>
											</table>
											
											<div class="asvab-section-title">AFQT Percentile Score: <? echo $afqt_score; ?> </div>
											
											<hr style="border: none; height: 2px; background-color: #ccc;">
											
										</div>

									<?

										echo "<br><h4>ASVAB Prediction Test Review</h4>";
									} else {
										echo "<div class='row'><div class='col-md-8'><h4>Date :" . showdate($date) . " | " . $category['test_name'] . "</h4></div><div class='col-md-4'><h4 style='color:green' class='pull-right'>" . $_SESSION['test_name'] . " Prediction Score : " . round($pred_score) . "</h4></div></div>";
									}

									echo '<br><table class="table table-bordered table-striped">';
									echo "<thead><th>Lesson Name</th><th>Total Question</th><th>Correct</th><th>Wrong</th><th>Exam Clarifiction</th></thead>";
									echo "<tbody>";

									foreach ($pred_test_report as $key => $d) {
										if ($cat_id == $d['cat_id']) {

											echo "<tr><td>" . $key . "</td><td>" . $d['total_questions'] . "</td><td>" . $d['correct_answers'] . "</td><td>" . ($d['total_questions'] - $d['correct_answers']) . "</td><td><a href='" . $configs['vpath'] . "tutor-view-report/".$_GET['student_id']."/view/" . $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $d['cat_id'] . "/" . $_GET['result_id'] . "' class='btn btn-success'>View Exam</a></td></tr>";
										} else {
											echo "<tr><td>" . $key . "</td><td>" . $d['total_questions'] . "</td><td>" . $d['correct_answers'] . "</td><td>" . ($d['total_questions'] - $d['correct_answers']) . "</td><td><a href='" . $configs['vpath'] . "tutor-view-report/".$_GET['student_id']. "/view/" . $_GET['cat_id'] . "-" . seo_url($category['test_name']) . "/" . $d['cat_id'] . "/" . $_GET['result_id'] . "' class='btn btn-primary'>View Exam</a></td></tr>";
										}
									}
									echo "</tbody></table><br>";

									echo "<h4>Question & Answer with clarification for your test on  <u>" . $cat_name . "</u></h4>";
									echo "<hr>";

									echo '<table class="table table-borderless">';
									$i = 0;

									$key = $cat_id;

									//echo'<tr><td colspan=3><h4>'.$v['cat_name'].'</h4></td></tr>';

									if (in_array($key, $story_question)) {

										foreach ($story_list[$key] as $d) {
											echo '<tr><td colspan=3 align=justify>' . html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&nbsp;", "", nl2br($story[$d['story_id']])))))) . '</td></tr>';

											foreach ($question_test[$d['story_id']] as $q) {
												$i++;

												echo '<tr><td width=5><span class="badge ">' . $i . '</span></td><td colspan=2>' . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) . "</td></tr>";

												$correct_ans = "";
												for ($j = 1; $j <= $option_no; $j++) {
													$ans = $alphabet[$j];
													$ind = 'option_' . strtolower($ans);

													if ($q[$ind]) {
														if ($j == (int)$my_answers[$q['qid']]) {
															$ans = '<span class="badge badge-primary">' . $ans . '</span>';
														}
														echo "<tr><td></td><td width=5>" . $ans . "</td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) . "</td></tr>";
													}
												}


												if (!$my_answers[$q['qid']]) {
													$text = "<span class='badge_no'>No Answer</span>";

													echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>Correct answer is  " . $alphabet[$q['answer']] . " | " . $text . "</b></td></tr>";
												} elseif ($my_answers[$q['qid']] == $q['answer']) {
													$text = '<span class="badge badge-success">Correct</span>';

													echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . "</b></td></tr>";
												} else {
													$text = '<span class="badge badge-wrong">Wrong Answer</span>';

													echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . " | Correct answer is  " . $alphabet[$q['answer']] . "</b></td></tr>";
												}

												if ($q['explanation']) {
													echo "<tr><td width=5></td><td class='text-align:left' colspan=2 style='color:blue'>Explanation: " . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) . "</td></tr>";
												}
												echo "<tr><td colspan=3><hr style='margin:0;'></td></tr>";
											}
										}
									} else {

										foreach ($questions[$key] as $q) {
											$i++;
											echo '<tr><td width=5><span class="badge ">' . $i . '</span></td><td colspan=2>' . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['question'])))) . "</td></tr>";

											$correct_ans = "";
											for ($j = 1; $j <= $option_no; $j++) {
												$ans = $alphabet[$j];
												$ind = 'option_' . strtolower($ans);

												if ($q[$ind]) {
													if ($j == (int)$my_answers[$q['qid']]) {
														$ans = '<span class="badge badge-primary">' . $ans . '</span>';
													}
													echo "<tr><td></td><td width=5>" . $ans . "</td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q[$ind])))) . "</td></tr>";
												}
											}

											if (!$my_answers[$q['qid']]) {
												$text = "<span class='badge_no'>No Answer</span>";

												echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>Correct answer is  " . $alphabet[$q['answer']] . " | " . $text . "</b></td></tr>";
											} elseif ($my_answers[$q['qid']] == $q['answer']) {
												$text = '<span class="badge badge-success">Correct</span>';

												echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . "</b></td></tr>";
											} else {
												$text = '<span class="badge badge-wrong">Wrong Answer</span>';

												echo "<tr><td width=5></td><td class='text-align:left' colspan=2><b>" . $text . " | Correct answer is  " . $alphabet[$q['answer']] . "</b></td></tr>";
											}

											if ($q['explanation']) {
												echo "<tr><td width=5></td><td class='text-align:left' colspan=2 style='color:blue'>Explanation: " . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $q['explanation'])))) . "</td></tr>";
											}
											echo "<tr><td colspan=3><hr style='margin:0;'></td></tr>";
										}
									}

									echo "</table>";

									break;

								case "results":

									echo "<h4>Results</h4>";
									echo '<br><table class="table table-bordered table-striped">';
									echo "<thead><th>Date</th><th>Subject</th><th>Lesson_name</th><th>View Details</th></thead>";
									echo "<tbody>";
									foreach ($results as $d) {
										$lesson_name = getFldValueById("lesson", "name", array("lesson_id" => $d['lesson_id']));
										echo "<tr><td>" . showdate($d['date']) . "</td><td>" . $d['cat_name'] . "</td><td>" . $lesson_name . "</td><td><a href='" . $configs['vpath'] . "exam/" . $d['cat_id'] . "-" . seo_url($lesson_name) . "/" . $d['lesson_id'] . "/result' class='btn btn-info'>View Score</a></td></tr>";
									}
									echo "</tbody></table>";
									break;
								case "flashcards":
						?>
									<script type="text/javascript" src="//code.jquery.com/jquery-1.6.4.js"></script>
									<div id="big-image" class="new-qs-slider">
										<?
										//$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"question_explanation"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));

										if ($total) {
											$i = 1;
											foreach ($questions as $f) {

												echo '<div class=row><item>';
												echo '<div class="col-md-12">';
												echo '<table class="table table-borderless">
							<tr><td >' . html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question']))) . "</td></tr>";


												//echo"</table>";	 
												echo "<tr><td colspan=2><div class='col-md-12 view-ans' style='margin-bottom:20px;margin-left:2px;text-align: center;'><a class='slick-prev'><i class='fa fa-angle-left'></i>Previous </a><a class='btn btn-info' href='javascript://'  onClick='showDiv(" . $i . ");'>Check Answer</a><a class='slick-next'>Next <i class='fa fa-angle-right'></i></a></div>";
												echo "<div class='col-md-12 mtq_explanation' id='ansdiv_" . $i . "' style='margin-left: 15px;display: none;background: left top repeat-y rgb(240, 240, 240);padding-top: 10px;right: 0px;transition: all 2s ease 0s;bottom: 10px;'>";

												if ($f['explanation']) {
													echo "<br>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['explanation']))));
												}

												echo "<div class='close-btn'><i class='fa fa-times'></i></div></div></td></tr>";

												echo "</table></div></item></div>";
												$i++;
											}
										}
										break;

									case "score-chart":
										?>
										<style type="text/css">
											#chart-container {
												width: 860PX;
												height: auto;
											}
										</style>

										<script type="text/javascript" src="<?= $configs['template_path']; ?>js/Chart.min.js"></script>
										<div id="chart-container">
											<canvas id="graphCanvas"></canvas>
										</div>
										<script>
											$(document).ready(function() {
												showGraph();
											});
										</script>
									<?


										break;

									case "progress-report":
									?>

										<div class="mob-tab-menu">
											
											<select name='cat_id' class='form-control' onChange='window.location=this.value'>

												<option value='' disabled>View Progress - Select Exam</option>

												<?
												if ($_GET['cat_id'] == 2000) {

													echo "<option value='" . $configs['vpath'] . "tutor-view-report/" . $_GET['student_id'] . "/progress-report/2000-Overview' selected>Overview</option>";
												} else {
													echo "<option value='" . $configs['vpath'] . "tutor-view-report/" . $_GET['student_id'] . "/progress-report/2000-Overview'>Overview</option>";
												}

												foreach ($cat_list as $c) {
													if ($c['category_name'] != "Flashcards") {
														if ($_GET['cat_id'] == $c['cat_id']) {
															echo "<option value='" . $configs['vpath'] . "tutor-view-report/" . $_GET['student_id'] . "/progress-report/" . $c['cat_id'] . "-" . seo_url($c['category_name']) . "' selected>" . $c['category_name'] . "</option>";
														} else {
															echo "<option value='" . $configs['vpath'] . "tutor-view-report/" . $_GET['student_id'] . "/progress-report/" . $c['cat_id'] . "-" . seo_url($c['category_name']) . "'>" . $c['category_name'] . "</option>";
														}
													}
												}

												foreach ($pred_cat_list as $c) {
													if ($_GET['cat_id'] == $c['Predic_test_id']) {
														echo "<option value='" . $configs['vpath'] . "tutor-view-report/" . $_GET['student_id'] . "/progress-report/" . $c['Predic_test_id'] . "-" . seo_url($c['test_name']) . "' selected>" . $c['test_name'] . "</option>";
													} else {
														echo "<option value='" . $configs['vpath'] . "tutor-view-report/" . $_GET['student_id'] . "/progress-report/" . $c['Predic_test_id'] . "-" . seo_url($c['test_name']) . "'>" . $c['test_name'] . "</option>";
													}
												}
												?>
											</select>
										</div>

										<div class="div-scroll">

											<div class="tab-content" style='padding-top:20px;padding-bottom:20px'>

												<div id="overview" class="tab-pane fade in active overview-con">

													<?

													include("../template/" . $configs['template'] . "/tmpl_tutor-report.php");
													?>
												</div>
											</div>
										</div>

									</div>
									<?

										break;

									case "prediction":

										echo "<h4>AFQT Prediction</h4>";
										echo '<br><table class="table table-bordered table-striped">';
										echo "<thead><th>Subject</th><th>Score</th></thead>";
										echo "<tbody>";
										$st = 0;

										foreach ($score as $d) {

											$st += $d['score'];
											echo "<tr><td>" . $d['name'] . "</td><td>" . (int)$d['score'] . "</td></tr>";
										}

										echo "<tr><td><b>Total Raw Score</b></td><td>" . (int)$raw_score . "</td></tr>";
										echo "<tr><td><b>Your AFQT</b></td><td>" . (int)$scaled_score . "</td></tr>";
										echo "</tbody></table>";

										break;

									case "prediction-test":

										if ($alert) {
											echo "<h3 style='margin-top:60px'>" . $alert . "</h3>";
										} else {

									?>
										<div style='padding:10px'>
											<h4>You are requested to answer <?= $total ?> Questions to get <u><?= $db_value['cat_name'] ?></u> Score</h4><br>
											<p>

											<div class="mob-tab-menu ">
												<ul class="nav nav-tabs">

													<?
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

														echo "<li class='" . $class . "'><a href='" . $configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" . $_GET['cat_name'] . "/" . $val['cat_id'] . "-" . $val['shortcode'] . "'>" . $val['shortcode'] . " (" . $val['total'] . "/<span id='" . $val['cat_id'] . "total'>" . $val['completed'] . "</span>)</a></li>";
													}

													//                            code for creating Prev and Next button    

													$i = 1;
													$prev_cat_id = null;
													$prev_shortcode = null;
													$next_cat_id = null;
													$next_shortcode = null;
													$val_total = null;
													$val_completed = null;

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
														$prev = "<a class='pull-left arrow-button prev-button' href='" . $configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" . $_GET['cat_name'] . "/" . $prev_cat_id . "-" . $prev_shortcode . "'></a>";
													}

													if (($current + 1) < $i) {
														$next = "<a id='next-button' class='pull-right arrow-button next-button'  href='" . $configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" . $_GET['cat_name'] . "/" . $next_cat_id . "-" . $next_shortcode . "'></a>";
													}



													?>
												</ul>

											</div>

											</p>
										</div>

										<form method='post' id='form'>
											<input type='hidden' name='time' id='time'>
											<div id="big-image" style='padding:10px'>
												<?
												$n = 0;
												$i = 0;

												echo "<div class='row'><div class='col-md-12 text-center'><h3 >" . $db_child_category['category_name'] .  "  <span class='badge badge-success'>" . $prediction_cat[$db_child_category['cat_id']]['total'] . " Questions</span></h3></div></div>
									<div class='row'><div class='col-md-12'><hr></div></div>";
												echo '<div class="col-md-12 custom-story-box">
									<table class="table table-borderless">';
												$c = $_GET['child_cat_id'];

												if (in_array($c, $story_question)) {

													foreach ($story_list[$c] as $d) {
														$story[$d['story_id']] = str_replace("&nbsp;", "", html_entity_decode($story[$d['story_id']]));

														echo '<tr><td colspan=4>' . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&nbsp;", "", $story[$d['story_id']])))) . '<small style="color: #85929E; float: right;">[' . $d['story_id'] . ']<small><br>' . '</td></tr>';

														foreach ($question_list[$d['story_id']] as $f) {
															$i++;
															$badge = "";

															//												if($f['user_ans'])
															//												{
															//													$badge="badge-success";
															//												}

															//echo'<item>
															echo '									 
												<tr><td width=10><span class="badge ' . $badge . '">' . $i . '</span></td><td colspan=3>' . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question'])))) . "</td></tr>";

															$correct_ans = "";
															for ($j = 1; $j <= $option_no; $j++) {
																$ans = $alphabet[$j];
																$ind = 'option_' . strtolower($ans);
																$chk = "";
																if ($f[$ind] || in_array($_GET['child_cat_id'], array(84))) {
																	if ($j == (int)$user_answers[$f['qid']]) {
																		$chk = "checked";
																	}
																	echo "<tr><td width=5>" . $ans . "</td><td width=5><input type='radio' " . $chk . " name='answer" . $i . "' id='" . $i . "' onClick=\"getFinalAnswer('" . $f['qid'] . "','" . $c . "','" . $j . "');\" ></td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) . "</td></tr>";
																}
															}
															echo '<tr><td colspan=4 id="response_' . $f['qid'] . '"></td></tr>';
														}
													}
												} else {

													foreach ($question_test[$c] as $f) {
														$i++;
														$badge = "";

														echo '
												
											<tr><td width=10><span class="badge ' . $badge . '">' . $i . '</span></td><td colspan=3>' . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f['question'])))) . "</td></tr>";
														$j = 0;

														$correct_ans = "";
														for ($j = 1; $j <= $option_no; $j++) {
															$ans = $alphabet[$j];
															$ind = 'option_' . strtolower($ans);
															$chk = "";
															if ($f[$ind] || in_array($_GET['child_cat_id'], array(83, 86))) {
																if ($j == $f['user_ans']) {
																	$chk = "checked";
																}
																echo "<tr><td width=5>" . $ans . "</td><td width=5><input type='radio' " . $chk . " name='answer" . $i . "' id='" . $i . "' onClick=\"getFinalAnswer('" . $f['qid'] . "','" . $c . "','" . $j . "');\" ></td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind])))) . "</td></tr>";
															}
														}
														echo '<tr><td colspan=4 id="response_' . $f['qid'] . '"></td></tr>';
													}
												}
												echo "</table></div>";

												?>
											</div>
											<div id='list'></div>

											<div class='row'>
												<?php

												if ($total > $completed_total) { ?>
													<div class="col-md-12 text-center total-qs social-float-parent">
														<div id="social-float">
															<div id='total'>
																<center>There are <?= ($total - $completed_total) ?> questions to complete.</center>
															</div>
														</div>
													</div>

													<div class="col-md-6 text-left"><?= $prev; ?></div>

													<div class="col-md-6 text-right"><?= $next; ?></div>

												<? }
												if ($total == $completed_total) {
													echo '<div class="col-md-12 text-center total-qs social-float-parent">' . $alert_total . '</div></div>';
												}
												?>
											</div>

										</form>
										<?

										}
										break;
									default:
										$p = "";
										if ($_GET['cat_id'] == 70) {
											//$p="final-test";
										}
										if ($lesson_total) {
											foreach ($lesson as $c) {
										?><div class="col-sm-6 col-lg-4">
												<div class="card desh-top-item bg-flat-color-5 download-box">
													<div class="card-body pb-0">
														<div class="col-md-12 text-center">
															<div class="icon-image">

																<a href='<?= $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $c['lesson_id'] . "-" . seo_url($c['Lesson_name']); ?>'> <img class="img-responsive" src="<?= $configs['vpath'] ?>img/lesson.png" /></a>

															</div>
														</div>

														<p class="text-light"><a class='ocust-text' href='<?= $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $c['lesson_id'] . "-" . seo_url($c['Lesson_name']); ?>'><?= $c['Lesson_name']; ?></a></p>


													</div>

												</div>
											</div>
										<?
											}
										} elseif ($_GET['cat_id'] == 18 && $_GET['homework']) {

										?>
										<div class="mob-tab-menu">
											<ul class="nav nav-tabs">
												<?
												for ($i = 1; $i <= $total_set; $i++) {
													if ($_GET['set_no'] == $i) {
														echo "<li class='active'><a  href='" . $configs['vpath'] . "exam/" . $_GET['cat_id'] . "-" . seo_url($db_value['cat_name']) . "/set-" . $i . "'> Set " . $i . "</a></li>";
													} else {
														echo "<li ><a  href='" . $configs['vpath'] . "exam/" . $_GET['cat_id'] . "-" . seo_url($db_value['cat_name']) . "/set-" . $i . "'> Set " . $i . "</a></li>";
													}
												}
												?>
											</ul>
										</div>

										<div class="tab-content">
											<div id="overview" class="tab-pane fade in active">
												<div class="flip-slider">
													<?

													foreach ($question_test as $f) {

														echo '<div>
						    <div class="flip-box">
						    <div class="flip-box-inner">
							<div class="flip-box-front">' . html_entity_decode($f['question']) . '</div>';
														echo '<div class="flip-box-back">';

														echo html_entity_decode($answers[$f['question_id']]);

														echo "</div></div></div>";
														echo "</div>";
													}

													?>
												</div>
											<?

										}
										break;
								}
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
									} else {
										foreach ($lesson as $c) {
											?><div class="col-sm-6 col-lg-4">
													<div class="card desh-top-item bg-flat-color-5 download-box">
														<div class="card-body pb-0">
															<div class="col-md-12 text-center">
																<div class="icon-image">

																	<a href='<?= $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $c['lesson_id'] . "-" . seo_url($c['Lesson_name']); ?>'> <img class="img-responsive" src="<?= $configs['vpath'] ?>img/lesson.png" /></a>

																</div>
															</div>
															<p class="text-light"><a class='ocust-text' href='<?= $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $c['lesson_id'] . "-" . seo_url($c['Lesson_name']); ?>'><?= $c['Lesson_name']; ?></a></p>
														</div>

													</div>
												</div>
											<?
										}
									}
								} else {
									foreach ($categories as $c) {
											?><div class="col-sm-6 col-lg-4">
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
										<!--
                <div class="col-sm-6 col-lg-4">
		            <div class="card desh-top-item bg-flat-color-5 download-box">
		                  <div class="card-body pb-0">
		                      <div class="col-md-12 text-center">
		                          <div class="icon-image">
		                        
									<a href='<?= $configs['vpath'] . "exam/" . $_SESSION['test_id'] . "-" . $pred_cat_id . "-" . seo_url($pred_cat_name); ?>'> <img class="img-responsive" src="<?= $configs['vpath'] ?>img/lesson.png" /></a>
								  
                                  </div>
		                      </div>
								
                              <p class="text-light"><a class='ocust-text' href='<?= $configs['vpath'] . "exam/prediction-test/" . $pred_cat_id . "-" . seo_url($pred_cat_name); ?>'><?= $pred_cat_name; ?></a></p>            
      
		                  </div>
		              </div>
		          </div>
-->
								<?
								}
							}
								?>
											</div>

										</div>
					</div>

				</div>

			</div>


			<div id="sclfooter"></div>
		</div>

	</div>
	<?

	/*?>   
<script>
	function showGraph()
        {
            {
                $.post("<?=$configs['vpath']."chart-data/".$_GET['cat_id']."/".(int)$_GET['lesson_id'];?>",
                function (score_data)
                {
                    console.log(score_data);
                    var date = [];
                    var score = [];

                    for (var i in score_data) {
                        date.push(score_data[i].date);
                        score.push(score_data[i].score);
                    }

                    var chartdata = {
                        labels: date,
                        datasets: [
                            {
                                label: '<?=$lesson_name;?> Score',
                                backgroundColor: '#49e2ff',
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: score
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata,
                        
                    });
                });
            }
        }
        
 
</script><?*/ ?>
	<script>
		function showDiv(x) {
			var x = document.getElementById("ansdiv_" + x);
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
		}


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


	<script>
		// Function to check if all questions are answered
		function checkAllQuestionsAnswered() {
			var totalQuestions = <?= $val_total ?>;
			var radioButtons = document.querySelectorAll('input[type="radio"]:checked');
			var answeredQuestions = radioButtons.length;

			if (answeredQuestions === totalQuestions) {
				document.getElementById('next-button').click();
			}
		}

		// Attach the event listener to radio buttons
		document.addEventListener('DOMContentLoaded', function() {
			var radioButtons = document.querySelectorAll('input[type="radio"]');
			radioButtons.forEach(function(radio) {
				radio.addEventListener('click', checkAllQuestionsAnswered);
			});
		});
	</script>


	<style>
		/* Style for Prev and Next Button */

		.arrow-button {
			padding: 25px 40px;
			background-size: 45px;
			/* Adjust size based on your arrow image size */
			background-repeat: no-repeat;
			background-position: center;
			text-align: center;
			cursor: pointer;
		}

		.prev-button {
			background-image: url('images/prev_round_button.png');
		}

		.next-button {
			background-image: url('images/circle_next_arrow_2.png');
		}

		.arrow-button:hover {
			transform: scale(1.07);
		}


		.ui-tabs .ui-tabs-panel {
			display: block;
			border-width: 0px;
			padding: 0em 0em !important;
			background: none;
		}

		.ui-tabs .ui-tabs-nav {
			margin: 0;
			padding: 0 0 0 !important;
		}

		.midle-conarea ul {
			padding-left: 0px !important;
			list-style-type: square !important;
		}

		.ui-corner-all,
		.ui-corner-bottom,
		.ui-corner-right,
		.ui-corner-br {
			border-bottom-right-radius: 0px !important;
		}

		.ui-corner-all,
		.ui-corner-bottom,
		.ui-corner-left,
		.ui-corner-bl {
			border-bottom-left-radius: 0px;
		}

		.ui-corner-all,
		.ui-corner-top,
		.ui-corner-right,
		.ui-corner-tr {
			border-top-right-radius: 0px !important;
		}

		.ui-corner-all,
		.ui-corner-top,
		.ui-corner-left,
		.ui-corner-tl {
			border-top-left-radius: 0px !important;
		}

		.ui-widget-header {
			border: 0px solid #dddddd !important;
			background: #fff !important;
			color: #333333;
			font-weight: bold;
		}

		.ui-widget.ui-widget-content {
			border: 0px solid #c5c5c5 !important;
		}

		.ui-state-default a,
		.ui-state-default a:link,
		.ui-state-default a:visited,
		a.ui-button,
		a:link.ui-button,
		a:visited.ui-button,
		.ui-button {
			color: #454545;
			text-decoration: none;
			border-bottom: solid 1px silver !important;
		}

		.ui-state-active,
		.ui-widget-content .ui-state-active,
		.ui-widget-header .ui-state-active,
		a.ui-button:active,
		.ui-button:active,
		.ui-button.ui-state-active:hover {
			border: 0px solid #003eff;
			background: #fcbd4f;
			font-weight: normal;
			color: #ffffff;
		}

		.story-div-style {
			background-color: #f7f7f7;
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 15px;
			box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
			max-width: 100%;
			margin: 0 auto;
			text-align: justify;
			font-family: Arial, sans-serif;
			line-height: 1.6;
			color: #333;
		}

		.story-order-list-style {
			background-color: #f7f7f7;
			padding: 4%;
			border-radius: 8px;
			border: 1px solid #ddd;
			box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
			font-family: Arial, sans-serif;
			line-height: 1.6;
			color: #333;
		}

		/* Remove any default styles from the button */
		.image-btn {
			background: none;
			border: none;
			padding: 0;
			margin: 0;
			cursor: pointer;
		}

		/* Ensure the image scales properly */
		.image-btn img {
			display: block;
			width: 150px;
			/* Set the width of the image as desired */
			height: auto;
			/* Maintain aspect ratio */
		}

		/* Hover effect */
		.image-btn:hover {
			transform: translateY(-2px);
			/* Slight raise effect */
		}

		/* Active state (when the button is clicked) */
		.image-btn:active {
			transform: translateY(1px);
			/* Button pressed down */
		}
	</style>

<style>
		.asvab-label-cell {
			vertical-align: middle;
			width: 160px;
			font-weight: bold;
		}

		.asvab-container {
			font-family: Arial, sans-serif;
			margin: 20px;
		}

		.asvab-heading {
			font-size: 24px;
			margin-bottom: 10px;
		}

		.asvab-section-title {
			font-weight: bold;
			margin-top: 20px;
			margin-bottom: 5px;
		}

		.asvab-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 25px;
		}

		.asvab-table td {
			text-align: left;
			padding: 4px 8px;
		}

		.asvab-afqt-score {
			margin-top: 10px;
			font-size: 16px;
		}

		.bottom_border {
			border-bottom: 2px solid #ccc; /* light grey line */
		}
	</style>