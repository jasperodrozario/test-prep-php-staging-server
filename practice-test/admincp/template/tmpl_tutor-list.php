<?
echo "<br>";
switch ($action) {
	case "employee-list":
?>
		<div class="row">

			<form method='post' action='<?= $configs['admin_vpath'] ?>tutor-list?action=employee-list'>
				<div class='col-md-4'>
					<input type='text' name='search' class='form-control' placeholder='Enter Search Key'>
				</div>
				<div class='col-md-2'><input type='submit' name='search_btn' class='btn btn-success' value='Search' style='width: 100%;'></div>

				<div class="col-md-4">
					<a class="link" href="<?= str_replace("practice-test/", "", $configs['vpath']) ?>tutor-register" id="linkToCopy" target="_blank"><?= str_replace("practice-test/", "", $configs['vpath']) ?>tutor-register</a>
				</div>
				<div class='col-md-2'><button class="btn btn-success copy-btn" onclick="copyLink()" style='width:80%'>Copy Link</button></div>
			</form>

		</div>

		<hr>

		<table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
			<thead>
				<tr>
					<td with=5% align="left"><b>Pic</b></td>
					<td align="left"><b>Name</b></td>
					<td><b>Reg. Date</b></td>
					<td><b>Ref. Code</b></td>
					<td align=center><b>Status</b></td>
					<td width="20%" align=center><b>Action</b></td>
				</tr>
			</thead>
			<tbody>
				<?php

				foreach ($db_list as $d) {
					//		    if(!$d['photo'] || !file_exists($configs['apath'].$d['photo']))
					//		    {
					//		    	$d['photo']="uploads/no-user.png";
					//		    }	
					if (!$d['photo']) {
						$d['photo'] = "uploads/no-user.png";
					}

					$image_path = str_replace("practice-test/", "", $configs['vpath']);

					echo "
            <tr>
                <td>
                    <img src='" . $configs['vpath'] . "image.php?src=" . $image_path . $d['photo'] . "&w=60'>
                </td>
                <td>" . ucwords($d['first_name'] . " " . $d['last_name']) . "<br>"
						. $d['email'] . "<br>
                 Mobile : " . $d['mobile'] . "
                </td>
                <td>" . $d['reg_date'] . "</td>
                <td>" . $d['referral_code'] . "</td>
                <td align=center>" . $d['activation_status'] . "</td>
                <td align='center'> 
                    <a   href='tutor-list?action=add-employee&tutor_id=" . $d['user_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                    <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='tutor-list?action=employee-list&del_tutor_id=" . $d['user_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
				}
				?>
			</tbody>
		</table>

		<?
		if ($total > 20) {
			echo '<ul class="pagination pagination-primary m-b-0">';
			echo paging($total, $param, "employee?action=employee-list");
			echo '</ul>';
		}
		break;

	case "add-employee":
		?>
		<form method="post" action='<?= $configs['admin_vpath'] ?>tutor-list?action=add-employee' enctype="multipart/form-data">
			<input type="hidden" name="tutor_id" value="<?php echo $_GET['tutor_id'] ?>">

			<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

				<tr>
					<td align="left" width="400px">First Name</td>
					<td><input type="text" name="first_name" class='form-control' value="<?php echo $db_value['first_name']; ?>" required></td>
				</tr>

				<tr>
					<td align="left">Last Name</td>
					<td><input type="text" name="last_name" class='form-control' value="<?php echo $db_value['last_name']; ?>" required></td>
				</tr>

				<tr>
					<td align="left">Password</td>
					<td><input type="text" name="password" class='form-control' placeholder="Leave empty if you do not wish to change the password"></td>
				</tr>

				<tr>
					<td align="left">Email</td>
					<td><input type="text" name="email" class='form-control' value="<?php echo $db_value['email']; ?>" required></td>
				</tr>

				<tr>
					<td align="left">Mobile</td>
					<td><input type="text" name="mobile" class='form-control' value="<?php echo $db_value['mobile']; ?>" required></td>
				</tr>

				<tr>
					<td align="left">Paypal ID</td>
					<td><input type="text" name="paypal_id" class='form-control' value="<?php echo $db_value['paypal_id']; ?>"></td>
				</tr>

				<tr>
					<td align="left">Activation Status </td>
					<td>
						<input type="radio" value='Y' name="activation_status" <? if ($db_value['activation_status'] == "Y") {
																					echo "checked";
																				} ?> required>Active &nbsp;&nbsp;&nbsp;

						<input type='radio' name="activation_status" value='N' <? if ($db_value['activation_status'] == "N") {
																					echo "checked";
																				} ?> required>Hidden
					</td>
				</tr>
				<tr>
					<td align="left">Do you want to change the password?</td>
					<td>
						<input type="checkbox" name="change_password" id="change_password"> Yes
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<input type='submit' name='submit' value='Save' class='btn btn-success'>&nbsp;&nbsp;
						<? if ($id) { ?>
							<input type='submit' name='SBMT_DEL' class='btn btn-danger' value='Delete'>
						<? } ?>
					</td>
				</tr>
			</table>
		</form>
	<?
		break;
	case "grant":
	?>
		<form id='form1' method='post' action='<?= $configs['admin_vpath'] ?>tutor-list?action=grant&admin_id=<?php echo $_GET['admin_id'] ?>'>
			<input type='hidden' name='admin_id' value='<?php echo $_GET['admin_id'] ?>'>
			<input type='hidden' name='action' value='grant-access'>
			<table border=0 cellpadding=4 cellspacing=1 width=100% align="center" class='table table-bordered table-striped'>
				<tr>
					<td height=30 align="left" colspan=2><b>Check/uncheck all</b> <input type='checkbox' name="checkall" id="checkall" onClick="check_uncheck_checkbox(this.checked);"> <input type='submit' name='SBMT_REG' class='btn btn-success pull-right' value='Update'>&nbsp;&nbsp;<input type="Reset" class='btn btn-danger pull-right'>&nbsp;&nbsp;</td>
				</tr>

				<?
				foreach ($menus as $d) {
					echo '<tr ><td colspan="2"><b>' . $d['name'] . '</b></td></tr>';
					$sub = $submenus[$d['id']];
					if (count($sub)) {
						foreach ($sub as $s) {
							$chk = "";
							if (in_array($s['id'], $grants)) {
								$chk = "checked";
							}
				?>
							<tr>
								<td>&nbsp;</td>
								<td><input class='customcheckbox' type='checkbox' name='progid[]' value="<?php echo $s['id'] ?>,<?php echo $s['parent_id'] ?>" <?php echo $chk ?>> <?php echo $s['name']; ?></td>
							</tr>
				<?
						}
					}
				}

				?>

				<tr>
					<td colspan=2 height=25 align=center><input type='submit' name="SBMT_REG" class='btn btn-ar btn-primary' value='Submit'></td>
				</tr>
			</table>
		</form>

<?
		break;
}
?>
<script>
	function check_uncheck_checkbox(isChecked) {
		if (isChecked) {
			$('.customcheckbox').each(function() {
				this.checked = true;
			});
		} else {
			$('.customcheckbox').each(function() {
				this.checked = false;
			});
		}
	}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
	function copyLink() {
		// Get the link element
		var copyText = document.getElementById("linkToCopy");

		// Create a temporary input to hold the text to copy
		var tempInput = document.createElement("input");
		tempInput.style.position = "absolute";
		tempInput.style.left = "-9999px";
		tempInput.value = copyText.href;
		document.body.appendChild(tempInput);

		// Select the text field
		tempInput.select();
		tempInput.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		document.execCommand("copy");

		// Remove the temporary input
		document.body.removeChild(tempInput);

		// Alert the copied text (Optional)
		alert("Copied the link: " + tempInput.value);
	}
</script>

<style>
	.link {
		font-size: 13px;
		/* Increase font size */
		font-weight: bold;
	}
</style>