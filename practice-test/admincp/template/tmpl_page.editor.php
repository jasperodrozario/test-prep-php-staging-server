    <form action="<?php echo BASENAME($_SERVER['SCRIPT_NAME'], ".php"); ?>" method='post' enctype="multipart/form-data" name="minform" role="form">
    	<input type='hidden' name='id' value="<?php echo $_GET['id'] ?>">
    	<input type='hidden' name='parent_id' value="<?php echo $_GET['parent_id'] ?>">

    	<table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>
    		<tr>
    			<td width="20%">Title</td>
    			<td><input type='text' name='title' size=30 value="<?php echo $db_value['title']; ?>" class='form-control'>
    			</td>
    		</tr>

    		<tr>
    			<td width="20%">Image</td>
    			<td><? if ($db_value['img']) {
						echo "<img src='" . $configs['vpath'] . $db_value['img'] . "' 100 width=100 height=80><br />";
					} ?>

    				<input type="file" name="img" value="<?php echo $db_value['img'] ?>" style="margin-top:10px;" />

    			</td>
    		</tr>

    		<tr>
    			<td valign="top" width="20%"> Description</td>
    			<td align="left" valign="top">
    				<textarea name='contents' id="contents" class='form-control description' style="width:100%;height:400px!important;"><?= html_entity_decode(html_entity_decode($db_value["contents"])); ?></textarea>
    			</td>
    		</tr>
    	</table>

    	<table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>

    		<tr>
    			<td width="20%">Menu</td>
    			<td><input type='checkbox' name='menu' value='Y' <?php if ($db_value['menu'] == "Y") {
																		echo "checked";
																	} ?>> Yes </td>
    		</tr>
    		<tr>
    			<td width="20%">Menu Location</td>
    			<td><input type='checkbox' name='location_header' value='1' <?php if ($db_value['location_header'] == "1") {
																				echo "checked";
																			} ?>> Header <input type='checkbox' name='location_footer' value='1' <?php if ($db_value['location_footer'] == "1") {
																																						echo "checked";
																																					} ?>> Footer </td>
    		</tr>

    		<tr>
    			<td width="20%">Parent Menu</td>
    			<td>
    				<select name='parent_id' size=1 class='form-control'>
    					<option value='0'>None</option>
    					<?
						foreach ($parent_list as $d) {
							if ($db_value['parent_id'] == $d['id']) {
								echo "<option value='" . $d['id'] . "' selected>" . $d['title'] . "</option>";
							} else {
								echo "<option value='" . $d['id'] . "'>" . $d['title'] . "</option>";
							}
						}
						?>
    				</select>
    			</td>
    		</tr>
    	</table>
    	<table class='table table-bordered table striped'>
    		<tr>
    			<td valign="top" width="20%">Meta Keys</td>
    			<td><textarea cols="50" rows="2" name="meta_keys" class='form-control' onKeyDown="textCounter(minform.meta_key,minform.remLen1,200)" onKeyUp="textCounter(minform.meta_key,minform.remLen1,200)"><?php echo $db_value['meta_keys'] ?>
	</textarea><br />Use comma "," between 2 meta keys
    				<br />
    				<input readonly="readOnly" size="5" value="200" name="remLen1" />
    			</td>
    		</tr>
    		<tr>
    			<td width="20%">Meta Title</td>
    			<td><input type="text" class='form-control' name='meta_title' size="30" value="<?php echo $db_value['meta_title'] ?>"></td>
    		</tr>
    		<tr>
    			<td valign="top">Meta Description</td>
    			<td><textarea cols="50" rows="2" name="meta_desc" class='form-control' onKeyDown="textCounter(minform.meta_desc,minform.remLen12,200)" onKeyUp="textCounter(minform.meta_desc,minform.remLen12,200)"><?php echo $db_value['meta_desc'] ?></textarea><br />
    				<input readonly="readOnly" size="5" value="200" name="remLen12" />
    			</td>
    		</tr>


    		<tr>
    			<td width="20%">Seo Url (Optional)</td>
    			<td><input type=text class='form-control' name='seo_url' size=55 value="<?php echo $db_value['seo_url'] ?>"> </td>
    		</tr>
    	</table>

    	<table class='table table-bordered table striped'>
    		<tr>
    			<td width="20%">Display Order</td>
    			<td><input type=text name='ord' class='form-control' size=30 value="<?php echo (int)$db_value[ord] ?>">
    				<br />Example: 10
    			</td>
    		</tr>


    		<tr>
    			<td valign="top" width="20%">Status </td>
    			<td><input type="radio" value='Y' name="status" <?php if ($db_value['status'] == "Y") {
																	echo "checked";
																} ?>>Online <input type=radio name="status" value='N' <?php if ($db_value['status'] == "N") {
																															echo "checked";
																														} ?>>Offline</td>
    		</tr>

    		<tr>
    			<td></td>
    			<td height=20><input type='submit' name='submit' <?php if ($db_value['id']) { ?>value='Update' <?php } else { ?>value='Add' <?php } ?> class='btn btn-success'>&nbsp;&nbsp;<input type="button" class="btn btn-danger" onClick="javascript:history.back();" value='Cancel'></td>
    		</tr>
    	</table>
    </form>





    <?
	if ($db_value['menu'] == 'Y') {
	?>
    	<script type="text/javascript">
    		toggle('Y');
    	</script>
    <?

	}
	?>

    <script>
    	var editor;
    	KindEditor.ready(function(K) {
    		editor = K.create('#contents', {
    			allowFileManager: true
    		});
    	});
    </script>