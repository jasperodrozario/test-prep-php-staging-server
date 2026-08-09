<div id="left-mob-menu">
	<div class="profile-toppart">
		<div class="profile-img">
			<img class="img-responsive" src="<?= $configs['vpath'] ?>img/lesson.png">


		</div>
		<div class="profile-headtext">
			<h4><?= $_SESSION['test_name'] ?> Test Prep</h4>
		</div>
	</div>

	<div class="course-features-info left-sidemenu">

		<ul class="sidenav sidebar-menu">
			<?
			$i = 0;
			$show_fp = (int)getFldValueById("user", "fp", array("user_id" => $_SESSION['user_id']));
			foreach ($cat_list as $c) {
				// if(($show_fp==1 && $c['category_name']=="Final Practice Test") || ($c['category_name']!="Final Practice Test")){
				$active = "";
				$bold = "";
				if ($c['cat_id'] == $_GET['cat_id']) {
					$active = "active";
					$bold = "class='bold'";
				}
				if ($cat_lesson_total[$c['cat_id']]) {
					$practice_test = "";
					if ($c['cat_id'] == -87 || $c['cat_id'] == -88 || $c['cat_id'] == -90) {
						$practice_test = "/practice-test";
					}

					echo "<li class='lectures-feature treeview " . $active . "'><a class='dropdown-btn' href='javascript:void(0);'>" . $c['category_name'] . " <i class='fa fa-angle-down'></i></a>";

					echo "<ul class='dropdown-container treeview-menu'>";
					foreach ($cat_lesson[$c['cat_id']] as $l) {
						if ($l['lesson_id'] == $_GET['lesson_id']) {
							echo "<li class='lectures-feature lactive' >
								<a href='" . $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $l['lesson_id'] . "-" . seo_url($l['Lesson_name']) . $practice_test . "'> " . $l['Lesson_name'] . "<p class='left-lesson-title active'>" . $l['Lesson_title'] . "</p></a></li>";
						} else {
							echo "<li class='lectures-feature'><a href='" . $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . $l['lesson_id'] . "-" . seo_url($l['Lesson_name']) . $practice_test . "'> " . $l['Lesson_name'] . "<p class='left-lesson-title'>" . $l['Lesson_title'] . "</p></a></li>";
						}
					}

					echo "</ul></li>";
				} elseif ($c['category_name'] == "Flashcards") {
					echo "<li class='lectures-feature " . $active . "'><a href='" . $configs['vpath'] . "exam/flashcards-" . $c['cat_id'] . "'> " . $c['category_name'] . "</a></li>";
				} else {
					echo "<li class='lectures-feature " . $active . "'><a href='" . $configs['vpath'] . "lesson/" . $c['cat_id'] . "-" . seo_url($c['category_name']) . "'> " . $c['category_name'] . "</a></li>";
				}
				$i++;
				// }
			}

			if ((int)$num_pred_test) {
				$show_pred = (int)getFldValueById("user", "pt", array("user_id" => $_SESSION['user_id']));

				if ($num_pred_test > 1) {
					$active = "";
					$bold = "";
					if ($_GET['result'] == 'prediction-test') {
						$active = "active";
						$bold = "class='bold'";
					}
					echo "<li class='lectures-feature " . $active . "'><a class='dropdown-btn' href='javascript:void(0);'> Prediction Test <i class='fa fa-angle-down'></i></a>";
					echo "<ul class='dropdown-container treeview-menu'>";
					foreach ($pred_test_list as $p) {
						if ($p['Predic_test_id'] == $_GET['cat_id']) {
							echo "<li class='lectures-feature lactive' ><a href='" . $configs['vpath'] . "exam/prediction-test/" . $p['Predic_test_id'] . "-" . seo_url($p['shortcode']) . "'> " . $p['test_name'] . "</a></li>";
						} else {
							echo "<li class='lectures-feature'><a href='" . $configs['vpath'] . "exam/prediction-test/" . $p['Predic_test_id'] . "-" . seo_url($p['shortcode']) . "'> " . $p['test_name'] . "</a></li>";
						}
					}
					echo "</ul></li>";
				} else {
					echo "<li class='lectures-feature'><a href='" . $configs['vpath'] . "exam/prediction-test/" . $pred_test_list['Predic_test_id'] . "-" . seo_url($pred_test_list['test_name']) . "'> " . $pred_test_list['test_name'] . "</a></li>";
				}
			}

			echo "<li class='lectures-feature '><a href='" . $configs['vpath'] . "exam/progress-report/2000-Overview'> Progress Report</a></li>";

			?>

		</ul>

	</div>

</div>

<style>
	.left-lesson-title {
		font-size: 12px;
		/* Adjust the size as needed */
		font-weight: normal;
		/* Removes bold styling */
		margin-bottom: 0px;
	}
</style>