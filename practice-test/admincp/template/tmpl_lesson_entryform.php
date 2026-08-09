<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>

<div id='response'></div>
<ul class="nav nav-tabs">
	<li <? if ($_REQUEST['page'] == "lesson") {
			echo "class='active'";
		} ?>><a href="?page=lesson&lesson_id=<?= $_REQUEST['lesson_id'] ?>&cat_id=<?= $_REQUEST['cat_id'] ?>&course_id=<?= $_REQUEST['course_id'] ?>"><? if ($_REQUEST['lesson_id']) {
																																																			echo "Edit ";
																																																		} else {
																																																			echo "Add ";
																																																		} ?> Lesson </a></li>
	<li <? if ($_REQUEST['page'] == "video") {
			echo "class='active'";
		} ?>><a href="?page=video&lesson_id=<?= $_REQUEST['lesson_id'] ?>&cat_id=<?= $_REQUEST['cat_id'] ?>&course_id=<?= $_REQUEST['course_id'] ?>" class='total'>Add Video</a></li>
	<!--<li <? if ($_REQUEST['page'] == "homework") {
				echo "class='active'";
			} ?>><a  href="?page=homework&lesson_id=<?= $_REQUEST['lesson_id'] ?>&cat_id=<?= $_REQUEST['cat_id'] ?>" class='total'>Add Homework</a></li>
	<li <? if ($_REQUEST['page'] == "homework-list") {
			echo "class='active'";
		} ?>><a  href="?page=homework-list&lesson_id=<?= $_REQUEST['lesson_id'] ?>&cat_id=<?= $_REQUEST['cat_id'] ?>" class='total'>Homework List</a></li>
	-->
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active">
		<?
		switch ($_REQUEST['page']) {

			case "lesson":
		?>
				<form method="post" id='lesson_form' enctype="multipart/form-data">
					<input type="hidden" name="action" value="lesson">

					<input type="hidden" name="lesson_id" value="<?php echo $_REQUEST['lesson_id'] ?>">
					<input type="hidden" name="cat_id" value="<?php echo $_REQUEST['cat_id'] ?>">
					<input type="hidden" name="course_id" value="<?php echo $_REQUEST['course_id'] ?>">

					<table border="0" cellpadding="4" cellspacing="0" width="100%" class='table table-bordered'>
						<tr>
							<td align="left">Lesson Name</td>
							<td align="left"><input type="text" class='form-control' name="Lesson_name" value="<?php echo $db_value['Lesson_name'] ?>" required></td>
						</tr>
						<tr>
							<td align="left">Lesson Title</td>
							<td align="left"><input type="text" class='form-control' name="Lesson_title" value="<?php echo $db_value['Lesson_title'] ?>"></td>
						</tr>

						<tr>
							<td width="15%">Course Name</td>
							<td>
								<select name='course_id_1' size=1 class='form-control' required>
									<option value='0'>Select Course</option>
									<?
									foreach ($course as $dd) {
										if ($_REQUEST['course_id'] == $dd['course_id']):
											echo "<option value=" . $dd['course_id'] . " selected>" . $dd['course_name'] . "</option>";
										else:
											echo "<option value=" . $dd['course_id'] . ">" . $dd['course_name'] . "</option>";
										endif;
									}

									?>
								</select>
							</td>
						</tr>

						<tr>
							<td width="15%">Category Name</td>
							<td>
								<select name='cat_id_1' size=1 class='form-control' required>
									<option value='0'>Select Category</option>
									<?
									foreach ($category as $dd) {
										if ($_REQUEST['cat_id'] == $dd['cat_id']):
											echo "<option value=" . $dd['cat_id'] . " selected>" . $dd['category_name'] . "</option>";
										else:
											echo "<option value=" . $dd['cat_id'] . ">" . $dd['category_name'] . "</option>";
										endif;
									}

									?>
								</select>
							</td>
						</tr>

						<? if ($_GET['lesson_id']) { ?>

							<tr>
								<td width="15%">Study Title</td>

								<td>
									<ul style="list-style-type: none; padding: 0;">
										<?php foreach ($added_study as $dd): ?>
											<?php
											$study_title = getFldValueById("study", "study_title", array("study_id" => $dd['study_id']));
											?>
											<li data-study-id="<?= $dd['id_lesson_study'] ?>" style="margin-bottom: 8px; background-color: #f8f9fa; border: 1px solid #ccc; border-radius: 4px; padding: 2px; display: flex; align-items: center;">
												<div style="flex: 1;">
													&nbsp;&nbsp;&nbsp;
													<?= $study_title ?>
												</div>
												<span class="btn delete-btn" style="color: white; background-color: #dc3545; border: none; padding: 3px 6px; border-radius: 4px; cursor: pointer; margin-left: 10px;" onclick="deleteStudy(<?= $dd['id_lesson_study'] ?>)">
													&#x2715;
												</span>
											</li>
										<?php endforeach; ?>
									</ul>

									<p><b>Newly added study:</b></p>
									<ul id="selected_studies">
										<!--                             Selected studies will be displayed here -->
									</ul>
									<br>
									<div style="display: flex; align-items: center;">
										<select name='study_id' id='study_id' size=1 class='form-control' required style="margin-right: 10px;">
											<option value='0'>Select Study Title</option>
											<?php foreach ($all_studies as $dd): ?>
												<option value="<?= $dd['study_id'] ?>" <?= ($db_value['study_id'] == $dd['study_id']) ? 'selected' : '' ?>>
													<?= $dd['study_title'] ?>
												</option>
											<?php endforeach; ?>
										</select>
										<button type="button" class="btn btn-success" onclick="addStudy()">Add Study</button>
									</div>
								</td>
							</tr>
						<? } ?>

						<tr>
							<td valign="top" width="13%">Description</td>
							<td width="51%"><textarea style='width:100%;height:200px' id='details' name="description" class="form-control"><?php echo html_entity_decode($db_value['description']); ?></textarea></td>
						</tr>
						
						<tr>
							<td align="left">Short Code</td>
							<td align="left"><input type="text" class='form-control' name="shortcode" value="<?php echo $db_value['shortcode'] ?>" required></td>
						</tr>

						<tr>
							<td align="left">Paid</td>
							<td align="left"><input type="radio" name="free" value="Y" <? if ($db_value['free'] == "Y") {
																							echo "checked";
																						} ?> required> Free Lesson <input type="radio" name="free" value="N" <? if ($db_value['free'] == "N") {
																																																			echo "checked";
																																																		} ?> required> Paid Lesson</td>
						</tr>

						<tr>
							<td align="left">Status</td>
							<td align="left"><input type="radio" name="online" value="Y" <? if ($db_value['online'] == "Y") {
																								echo "checked";
																							} ?> required> Online <input type="radio" name="online" value="N" <? if ($db_value['online'] == "N") {
																																																			echo "checked";
																																																		} ?> required> Offline</td>
						</tr>

						<tr>
							<td align="left">Display Order</td>
							<td align="left"><input type="number" name="lesson_order" value="<?= $db_value['lesson_order']; ?>" class="form-control"></td>
						</tr>

						<tr>
							<td></td>
							<td height="25"><input type="submit" class="btn btn-success" name='submit' value='Update'> <input type="button" class="btn btn-danger" onclick="javascript:history.back();" value='Cancel'></td>
						</tr>
					</table>

				</form>

			<?
				break;
			case "video":
			?>

				<div class='from-group row'>
					<div class='col-md-6'>
						<h5><b>Add Video Form</b></h5>
					</div>
					<div class='col-md-6'><? if ($_GET['id']) { ?><button class='btn btn-success pull-right' style='margin-top:10px' onclick="window.location='?page=<?= $_REQUEST['page'] . "&lesson_id=" . $_REQUEST['lesson_id']; ?>'">Add New</button><? } ?></div>
				</div>
				<form method="post" id="lesson_video_form" enctype="multipart/form-data">
					<input type="hidden" name="lesson_id" value="<?php echo $_REQUEST['lesson_id'] ?>">
					<input type="hidden" name="action" value="lesson-video">
					<input type="hidden" name="cat_id" value="<?php echo $_REQUEST['cat_id'] ?>">
					<input type="hidden" name="file_type" value="v">
					<input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
					<div id='lesson_video_form'>
						<table border="0" cellpadding="4" cellspacing="0" width="100%" class='table table-bordered'>
							<tr>
								<td align="left" width=25%>Video Title</td>
								<td align="left"><input type='text' class='form-control' name='title' value='<?= $db_value['title'] ?>'>

							<tr>
								<td align="left">Youtube Video Url</td>
								<td align="left"><input type='text' class='form-control' style='width:100%' value='<?= $db_value['file'] ?>' name="file" id='file'>

								</td>
							</tr>
							<tr>
								<td>Display Order</td>
								<td><input type='text' class='form-control' name='pos' value='<?= $db_value['pos'] ?>'> </td>
							</tr>

							<tr>
								<td></td>
								<td height="25"><input type="submit" class="btn btn-success" name='submit' value='Save'></td>
							</tr>
						</table>
					</div>

				</form>
				<h3>Video List</h3>
				<div id='lesson_video'>
					<table class='table table-bordered'>
						<thead>
							<th>Sr.No</th>
							<th>Title</th>
							<th>File</th>
							<th>Display Order</th>
							<th>Action</th>
						</thead>
						<tbody>
							<?
							$i = 0;


							foreach ($video_list as $f) {
								$i++;
								$file = $f['file'];
								if (substr_count($file, "youtu.be")) {
									$e = end(explode("/", $file));
								} else {
									$e1 = end(explode("?v=", $file));
								}

								$picture = "http://img.youtube.com/vi/" . $e . "/mqdefault.jpg";
								$youtube = '<iframe width="320"  src="https://www.youtube.com/embed/' . $e . '?autoplay=1" frameborder="0"  allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

								echo "<tr><td>" . $i . "</td><td>" . $f['title'] . "</td><td> " .
									$youtube . "</td><td>" . $f['pos'] . "</td> <td><button onClick=\"window.location='?page=video&lesson_id=" . $f['lesson_id'] . "&id=" . $f['id'] . "';\" class='btn btn-success'>Edit</button> <button onClick=\"window.location='?page=video&lesson_id=" . $f['lesson_id'] . "&del_id=" . $f['id'] . "';\" class='btn btn-danger'>Delete</button></td></tr>";
							}
							?>
						</tbody>
					</table>
				<?
				break;
			case "homework":
				?>
					<h5><b>Add Homework</b></h5>
					<p><br></p>
					<div id="form-response"></div>


					<form method="post" id="form" enctype="multipart/form-data">
						<input type='hidden' name="action" value="homework">
						<input type='hidden' name="id" value="<?= $_REQUEST['id']; ?>">
						<input type='hidden' name="lesson_id" value="<?= $_REQUEST['lesson_id']; ?>">

						<input type='hidden' name="homework" value="1">
						<input type="hidden" name="cat_id" value="<?php echo $_REQUEST['cat_id'] ?>">

						<div class='row form-group' id='options'>
							<div class='col-md-3'><label class='form-control-label'>Question Set No</label></div>
							<div class='col-md-9'>
								<select class='form-control' name='set_no' id='set_no' REQUIRED>
									<option value=''>Select --</option>
									<?

									for ($i = 1; $i <= 10; $i++) {
										if ($db_value['set_no'] == $i) {
											echo "<option value='" . $i . "' selected>Question Set " . $i . "</option>";
										} else {
											echo "<option value='" . $i . "' >Question Set " . $i . "</option>";
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class='row form-group'>
							<div class='col-md-3'><label class='form-control-label'>Question</label></div>
							<div class='col-md-9'><textarea class='form-control description' name='question' id='questions'><?= html_entity_decode($db_value['question']); ?></textarea>
							</div>
						</div>
						<?
						if ($total_answers) {
							$i = 0;
							foreach ($answers as $ans) {

								if ($ans['is_correct'] == "Y") {
									$checked = "checked";
								} else {
									$checked = "";
								}
								echo "<div class='row form-group'>
				<div class='col-md-3'><label class='form-control-label'>Answer " . ($i + 1) . "</label></div>
				<div class='col-md-9'><textarea  class='form-control description' name='answer[]' REQUIRED>" . html_entity_decode($ans['answer']) . "</textarea>
		        </div></div>";

								$i++;
							}
						} else {
							$i = 0;
							$answer_html = "";
							for ($i = 0; $i < 4; $i++) {

								echo "<div class='row form-group'>
					<div class='col-md-3'><label class='form-control-label'>Answer " . ($i + 1) . "</label></div>
					<div class='col-md-9'><textarea  class='form-control description' name='answer[]' REQUIRED>" . html_entity_decode($ans['answer']) . "</textarea>
			        </div></div>";
							}
						}
						?>
						<div class='row form-group'>
							<div class='col-md-3'><label class='form-control-label'>Correct Answer</label></div>
							<div class='col-md-9'><select name='answer_no' class='form-control' required>
									<option value=''>Select Answer</option>
									<option value='1' <? if ($db_value['answer_no'] == 1) {
															echo "selected";
														} ?>>Answer 1</option>
									<option value='2' <? if ($db_value['answer_no'] == 2) {
															echo "selected";
														} ?>>Answer 2</option>
									<option value='3' <? if ($db_value['answer_no'] == 3) {
															echo "selected";
														} ?>>Answer 3</option>
									<option value='4' <? if ($db_value['answer_no'] == 3) {
															echo "selected";
														} ?>>Answer 4</option>
								</select></div>
						</div>
						<div class='row form-group'>
							<div class='col-md-3'><label class='form-control-label'>Clarification</label></div>
							<div class='col-md-9'><textarea placeholder="Explanation" class='form-control description' name='explanation'><?= html_entity_decode($db_value['explanation']); ?></textarea>
							</div>
						</div>
						<div class='row form-group'>
							<div class='col-md-3'><label class='form-control-label'>Status</label></div>
							<div class='col-md-9'><input type='radio' name='status' id='status' value='Y' REQUIRED <? if ($db_value['status'] == 'Y') {
																														echo 'checked';
																													} ?>> Yes &nbsp<input type='radio' name='status' id='status' value='N' REQUIRED <? if ($db_value['status'] == 'N') {
																																																													echo 'checked';
																																																												} ?>> No &nbsp</div>
						</div>
						<div class='row form-group'>
							<div class='col-md-3'></div>
							<div class='col-md-9'><input type="submit" class="btn btn-success" name='submit' value='Save'></div>
						</div>

					</form>
				<?
				break;
			case "homework-list":
				?>
					<h5><b>Homework List</b></h5>
					<div id="form-response"></div>

					<table class='table table-bordered'>
						<thead>
							<th width=5%>Q.No</th>
							<th width=65%>Question</th>
							<th>Status</th>
							<th>Action</th>
						</thead>
						<tbody>
							<?
							$i = 0;
							foreach ($db_list as $f) {
								$i++;
								echo "<tr><td>" . $f['id'] . "</td><td>" . html_entity_decode($f['question']) . "</td><td>" . $f['status'] . "</td><td><button onClick=\"window.location='?page=homework&lesson_id=" . $f['lesson_id'] . "&cat_id=" . $f['cat_id'] . "&id=" . $f['id'] . "';\" class='btn btn-success'>Edit</button> <button onClick=\"if(confirm('Are you sure to delete?')){window.location='?page=homework-list&lesson_id=" . $f['lesson_id'] . "&cat_id=" . $f['cat_id'] . "&del_id=" . $f['id'] . "';}\" class='btn btn-danger'>Delete</button></td></tr>";
							}
							?>
						</tbody>
					</table>
			<?
				break;
		}
			?>
				</div>

	</div>
	<?
	if ($_GET['page'] == "lesson") { ?>

		<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>

		<script>
			CKEDITOR.replace('details', {
				extraPlugins: 'mathjax',
				mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
				height: 120,
				filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
				//filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files' 
				filebrowserUploadUrl: 'application/ck_upload.php',
				filebrowserUploadMethod: 'form',
				versionCheck: false
			});


			if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
				document.getElementById('ie8-warning').className = 'tip alert';
			}
		</script>
	<?
	}
	?>

	<script>
		let selectedStudies = [];

		function addStudy() {
			const studySelect = document.getElementById('study_id');
			const studyId = studySelect.value;
			const studyTitle = studySelect.options[studySelect.selectedIndex].text;

			var courseId = '<?= $_GET['course_id'] ?>';
			var catId = '<?= $_GET['cat_id'] ?>';
			var lessonId = '<?= $_GET['lesson_id'] ?>';

			if (studyId != '0' && !selectedStudies.some(study => study.id === studyId)) {
				selectedStudies.push({
					id: studyId,
					title: studyTitle
				});
				updateSelectedStudies();

				// Prepare the data to be sent
				var formData = new FormData();
				formData.append('action', 'add-study');
				formData.append('study_id', studyId);
				formData.append('course_id', courseId);
				formData.append('cat_id', catId);
				formData.append('lesson_id', lessonId);

				$.ajax({

						type: "POST",
						url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
						data: formData,
						contentType: false, // The content type used when sending data to the server.
						cache: false, // To unable request pages to be cached
						processData: false
					})
					.done(function(data) {
						//alert(data);

						//                $("#response").html(data);
						//
						//                $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);

					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						// just in case posting your form failed
						//                alert(textStatus);

					});

			}
		}

		function updateSelectedStudies() {
			const selectedStudiesList = document.getElementById('selected_studies');
			selectedStudiesList.innerHTML = '';

			selectedStudies.forEach(study => {
				const li = document.createElement('li');
				li.textContent = study.title;
				selectedStudiesList.appendChild(li);
			});

			// Move selected studies to the top of the dropdown
			const studySelect = document.getElementById('study_id');
			const allOptions = Array.from(studySelect.options);
			const sortedOptions = selectedStudies.map(study => {
				return allOptions.find(option => option.value == study.id);
			}).concat(allOptions.filter(option => !selectedStudies.some(study => study.id == option.value)));

			studySelect.innerHTML = '';
			sortedOptions.forEach(option => studySelect.appendChild(option));
		}

		function deleteStudy(studyId) {
			var formData = new FormData();
			formData.append('action', 'remove-study');
			formData.append('study_id', studyId);

			$('li[data-study-id="' + studyId + '"]').remove();

			$.ajax({
				type: "POST",
				url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
				data: formData,
				contentType: false,
				cache: false,
				processData: false
			});
		}
	</script>