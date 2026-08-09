<p></p>
<script src="<?= $configs['template_path']; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?= $configs['template_path']; ?>js/Chart.min.js"></script>

<style type="text/css">
	#chart-container {
		width: 660PX;
		height: auto;
	}
</style>

<?

// echo "pred total: " . $pred_result_total;

if ($pred_result_total) {
	if ($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23 && $result_id) {
		// full asvab score card
		?>
		<div class='text-center w-100'>
			<h4 class="w-100">ASVAB SCORE REPORT</h4>
		</div>
		<?
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

			<hr style="border: none; height: 2px; background-color: #ccc;">

			<table class="asvab-table text-wrap standard-score-table">
				<tr class="standard-scores-label-row">
					<td colspan="<?= count($pred_test_report) + 1 ?>"><strong>Standard Scores:</strong></td>
				</tr>
				<tr>
					<td class="text-wrap asvab-label-cell standard-scores-label-desktop" rowspan="2"><strong>Standard Scores:</strong></td>
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

			<div class="asvab-section-title text-left">Composite Scores:</div>

			

			<table class="asvab-table composite-score-table">
				<?
				foreach ($full_asvab_result_list as $d) {
					if ($d['pred_name'] != "VE" && $d['pred_name'] != "AFQT") {
				?>
						<tr class="gt-name" >
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

			<hr style="border: none; height: 2px; background-color: #ccc;">

		</div>
		<?
	}
}

if ((int)$_GET['cat_id'] == 2000) {
	$cat_name = "Overall";
	?>

	<h3>Overall Progress Chart</h3><br>
	<div id="chart-container">
		<canvas id="graphCanvas"></canvas>
	</div>
	<script>
		$(document).ready(function() {
			showGraph();
		});
	</script>

<?

} else {
	
	$category_name = getFldValueById("category", "category_name", array("cat_id" => $_GET['cat_id'], "category_name" => $_GET['cat_name']));
	$pred_test_name = getFldValueById("predic_test", "test_name", array("Predic_test_id" => $_GET['cat_id'], "test_name" => $_GET['cat_name']));
	
	if ($category_name) {
		$cat_name = $category_name;
	} else if ($pred_test_name) {
		$cat_name = $pred_test_name;
	}
	?>

	<h3>Progress Chart </h3><br>

	<div style="overflow-x:auto; width:100%;">
		<div id="chart-container"> 
			<canvas id="graphCanvas"></canvas>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			showGraph();
		});
	</script>
	<p><br></p>

<?php if ($lesson_total): ?>
    <?php foreach ($lesson_list as $c): ?>
        <?php if ($result_total[$c['lesson_id']]): ?>
            <p></p>
            <div class="row">
                <div class="col-md-12">
                    <h4><?= $db_value['category_name'] ?> > <?= $c['Lesson_name'] ?> Score</h4>
                </div>
            </div>

            <br>
            <div class="table-responsive">
                <table class="table table-bordered table-striped progress_report_table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Question</th>
                            <th>Correct</th>
                            <th>Score(%)</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        <?php foreach ($result[$c['lesson_id']] as $l): ?>
                            <?php $i++; ?>
                            <tr>
                                <td><?= date("M j, Y", strtotime($l['result_date'])) ?></td>
                                <td><?= $l['total_questions'] ?></td>
                                <td><?= $l['correct_answers'] ?></td>
                                <td><?= round($l['score']) ?></td>
                                <td>
                                    <a href="<?= $configs['vpath'] ?>exam/result/<?= $c['cat_id'] . "-" . seo_url($db_value['category_name']) ?>/<?= $l['lesson_id'] ?>/<?= $l['result_id'] ?>"
                                       class="btn btn-info pt-review-btn tab">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br>
        <?php endif; ?>
    <?php endforeach; ?>

<?php elseif ($pred_result_total): ?>
    <?php $pred_th = ($_GET['cat_id'] == 23) ? "AFQT Prediction Score" : "Prediction Score"; ?>

    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped progress_report_table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total Question</th>
                    <th>Correct</th>
                    <th><?= $pred_th ?></th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $c): ?>
                    <tr>
                        <td><?= date("M j, Y", strtotime($c['result_date'])) ?></td>
                        <td><?= $c['total_questions'] ?></td>
                        <td><?= $c['correct_answers'] ?></td>
                        <td><?= round($c['score']) ?></td>
                        <td style="text-align:center">
                            <a href="<?= $configs['vpath'] ?>exam/view/<?= $_GET['cat_id'] . "-" . seo_url($_GET['cat_name']) ?>/0/<?= $c['result_id'] ?>"
                               class="btn btn-info pred-test-review-btn">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <br>
<?php endif; 
}?>


<script>
	function showGraph() {
		{
			$.post("<?= $configs['vpath']; ?>chart-data/<?= $_GET['cat_id'] ?>/<?= $_GET['cat_name'] ?>",
				function(score_data) {
					console.log(score_data);
					var date = [];
					var score = [];
					var result_ids = [];

					for (var i in score_data) {
						date.push(score_data[i].date);
						score.push(score_data[i].score);
						result_ids.push(score_data[i].result_id); // Storing result IDs
					}

					var chartdata = {
						labels: date,
						datasets: [{
							label: 'Average Score',
							backgroundColor: '#49e2ff',
							borderColor: '#ffaa00',
							hoverBackgroundColor: '#ffaa00',
							hoverBorderColor: '#666666',
							data: score
						}]
					};

					var graphTarget = $("#graphCanvas");
					var barGraph = new Chart(graphTarget, {
						type: 'bar',
						data: chartdata,
						options: {
							responsive: true,
							maintainAspectRatio: false,   // 👈 this lets your CSS height apply

							onClick: function(event) {
								var activePoints = barGraph.getElementsAtEvent(event);
								if (activePoints.length > 0) {
									// Get the index properly
									var index = activePoints[0]._index;
									var selected_result_id = result_ids[index];
									console.log("Selected Result ID:", selected_result_id);

									// Redirecting with the new result_id
									if (selected_result_id) {
										window.location.href = "<?= $configs['vpath']; ?>exam/progress-report/" + <?= $_GET['cat_id'] ?> + "-" + "<?= seo_url($_GET['cat_name']) ?>" + "/" + selected_result_id;
									} else {
										console.error("Result ID is undefined.");
									}
								}
							},
							scales: {
								yAxes: [{
									ticks: {
										min: 0,
										max: 100,
										padding: 20,
										stepSize: 10,
									}
								}]
							}
						},

						
					});
				});
		}
	}
</script>