 
  	<form action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>?story_id=<?=$_REQUEST['story_id']?>" method='post' enctype="multipart/form-data"  name="minform">
	<input type='hidden' name='story_id' value="<?php echo  $db_value['story_id']?>">
	
	<table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>

	<tr ><td width="20%">Story Title</td><td><input type='text' name='story_title'   value="<?php echo $db_value['story_title'];?>" class='form-control' required></td></tr>

	<tr ><td width="20%">Author</td><td><input type='text' name='author'   value="<?php echo $db_value['author'];?>" class='form-control' ></td></tr>
	 
	<tr><td valign="top" width="20%">Story</td><td align="left" valign="top"> <textarea  name='story' id="story" class='form-control' style="width:100%;height:200px"><?=html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($db_value["story"]))));?></textarea> </td></tr>
	 
	<tr><td></td><td  height=20 ><input type='submit'  name='submit' value='Save' class='btn btn-success' >&nbsp;&nbsp;<input type="button" class="btn btn-danger" onClick="javascript:history.back();" value='Cancel'></td></tr>
	</table>
	</form>
 
<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>
<script>
var editor;
KindEditor.ready(function(K) 
{
	editor = K.create('#story', {
		allowFileManager : true
	});
});
</script>
