
	<section class="courses-area ptb-70">
		<div class="container">
			<div class="section-title">
				<span>Popular Courses</span>
				<h2>Select a Course to Learn More</h2>
			</div>
			<div class="row">
				<?
					foreach ($courses as $c) { ?>
					<div class="col-lg-6 col-md-6">
						<div class="single-course">
							<a href="<?= $configs['vpath'] ?>course/<?= $c['seo_url'] ?>">
								<img src="<?= $configs['vpath'] ?>image.php?image=<?= $configs['vpath'] . $c['thumb'] ?>&w=591&h=350" alt="<?= $c['name'] ?>" width=580 height=350 class='himage'>
							</a>
							<div class="course-content">

								<a href="<?= $configs['vpath'] ?>course/<?= $c['seo_url'] ?>">
									<h3 style="font-size: 24px; font-weight: bold;"><?= $c['name'] ?></h3>
								</a>
								<ul class="rating">
									<li>
										<i class="bx bxs-star"></i>
									</li>
									<li>
										<i class="bx bxs-star"></i>
									</li>
									<li>
										<i class="bx bxs-star"></i>
									</li>
									<li>
										<i class="bx bxs-star"></i>
									</li>
									<li>
										<i class="bx bxs-star"></i>
									</li>
									<li>
										<span>5</span>
										<a href="#">(5 rating)</a>
									</li>
								</ul>
								<p align='justify'><?= substr(strip_tags(html_entity_decode($c['details'])), 0, 200); ?>...</p>

							</div>
						</div>
					</div>
				<?
					}
					?>
			</div>
		</div>
	</section>

	<section class="feedback-area f5f6fa-bg-color ptb-100">
		<div class="container">
			<div class="section-title">
				<span>Feedback</span>
				<h2>What Students Say</h2>
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
						<p align="justify" class='feedback'><?= html_entity_decode($d['feedback']); ?></p>
						<div class="feedback-title">
							<img src="<?= $configs['vpath'] . $d['pic'] ?>" alt="<?= $d['name'] ?>" class='rimage'>
							<h3><?= $d['name']; ?></h3>
							<!--<span>Designer</span>-->
						</div>
					</div>
				<?
					}

					?>
			</div>
		</div>
	</section>

	<div class="app-buttons">
		<div class="app-button">
			<a href="https://apps.apple.com/us/developer/debbrata-das/id1518771903" target="_blank">
				<img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="Download on the App Store">
			</a>
		</div>
		<div class="app-button">
			<a href="https://play.google.com/store/apps/developer?id=ASVAB+Tutoring" target="_blank">
				<img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png" alt="Get it on Google Play" style="width: 185px; height: auto;">
			</a>
		</div>
	</div>

	<br>
</main>
 <style>
 	.app-buttons {
 		display: flex;
 		justify-content: center;
 		align-items: center;
 		margin-top: 20px;
 	}

 	.app-button {
 		margin: 0 10px;
 		text-align: center;
 	}

 	.app-button img {
 		width: 150px;
 		/* Set the width of the images */
 		height: auto;
 		/* Maintain aspect ratio */
 	}
 </style>