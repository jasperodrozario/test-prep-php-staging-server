<main>

	<div class="page-title-area bg-1">
		<div class="container">
			<div class="page-title-content">
				<h2>About Asvab Tutoing</h2>

			</div>
		</div>
	</div>


	<section class="education-area-two ptb-100">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="education-content">
						<span class="top-title">About Us</span>
						<?= html_entity_decode(html_entity_decode(html_entity_decode($db_value['contents']))); ?>

					</div>
				</div>
				<div class="col-lg-6">
					<div class="education-img-wrap">
						<div class="education-img-2">
							<img src="<?= $configs['template_path'] ?>assets/img/education-img-2.jpg" alt="Image">
						</div>
						<div class="education-img-3">
							<img src="<?= $configs['template_path'] ?>assets/img/education-img-3.jpg" alt="Image">
						</div>
						<div class="education-img-4">
							<img src="<?= $configs['template_path'] ?>assets/img/education-img-4.jpg" alt="Image">
						</div>
						<div class="education-shape-1">
							<img src="<?= $configs['template_path'] ?>assets/img/education-shape-1.jpg" alt="Image">
						</div>
						<div class="education-shape-2">
							<img src="<?= $configs['template_path'] ?>assets/img/education-shape-2.png" alt="Image">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="affordable-area f5f6fa-bg-color pt-100 pb-70">
		<div class="container">
			<div class="section-title">
				<span>Our Affordable</span>
				<h2>Your Benefits with ASVAB Tutoring</h2>
			</div>
			<div class="row">
				<div class="col-lg-3 col-sm-6">
					<div class="single-affordable one">
						<i class="flaticon-investment"></i>
						<h3>We are affordable</h3>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="single-affordable two">
						<i class="flaticon-balance"></i>
						<h3>Updated Questions</h3>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="single-affordable three">
						<i class="flaticon-online-education"></i>
						<h3>High Score Guaranteed</h3>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="single-affordable four">
						<i class="flaticon-route"></i>
						<h3>No Unnecessary Questions</h3>
					</div>
				</div>
			</div>
		</div>
	</section>




	<section class="feedback-area feedback-area-three feedback-area-style ptb-100">
		<div class="container">
			<div class="section-title">
				<span>Feedback</span>
				<h2>What student say</h2>
			</div>
			<div class="feedback-slider owl-theme owl-carousel">

				<?
				foreach ($feedbacks as $d) {
					if (!$d['pic']) {
						$d['pic'] = "img/noimage.png";
					}
				?>
					<div class="feedback-item">
						<i class="flaticon-quotation"></i>
						<p><?= html_entity_decode($d['fd_desc']); ?></p>
						<div class="feedback-title">
							<img src="<?= $configs['vpath'] . $d['pic'] ?>" alt="<?= $d['fd_by'] ?>" class='rimage'>
							<h3><?= $d['fd_by']; ?></h3>
							<!--<span>Designer</span>-->
						</div>
					</div>
				<?
				}

				?>

			</div>
		</div>
	</section>
</main>