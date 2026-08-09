	<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>

	<form method='post' id='form-content' enctype="multipart/form-data">
	<input type="hidden"   name='action'  value="add-page"> 
	<input type='hidden' name='id' value="<?php echo $_GET['id']?>">
	<input type='hidden' name='parent_id' value="<?php echo $_GET['parent_id']?>">
	<div class="table-responsive">
	 
	<table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>

	
	<tr ><td width="20%">Title</td><td><input type='text' name='title'  size=30 value="<?php echo $db_value['title'];?>" class='form-control'> 
	</td></tr>

	<tr ><td width="20%">Image</td><td><?if($db_value['img']){echo"<img class='page-editor' src='" .$configs['vpath'].$db_value['img'] . "' width='300'><br />";}?>
 
	<input type="file" name="img" value="<?php echo $db_value['img']?>" style="margin-top:10px;" />
	 
	</td></tr>
	 
	<tr><td valign="top" width="20%"> Description</td><td align="left" valign="top">
		  <textarea  name='contents' id="contents" ><?=html_entity_decode(html_entity_decode($db_value["contents"]));?></textarea>
		 </td>
	  </tr></table>
  
	
	<table  class='table table-bordered table striped'>
	<tr><td valign="top" width="20%">Meta Keys</td>
	  <td ><textarea  cols="50" rows="2" name="meta_keys" class='form-control'  onKeyDown="textCounter(minform.meta_key,minform.remLen1,200)" onKeyUp="textCounter(minform.meta_key,minform.remLen1,200)" ><?php echo $db_value['meta_keys']?>
	</textarea></td>
	</tr>
	<tr ><td width="20%">Meta Title</td><td><input type="text" class='form-control' name='meta_title' size="30" value="<?php echo $db_value['meta_title']?>" ></td></tr>
	<tr >
	  <td valign="top" >Meta Description</td>
	  <td ><textarea  cols="50" rows="2" name="meta_desc"  class='form-control' onKeyDown="textCounter(minform.meta_desc,minform.remLen12,200)" onKeyUp="textCounter(minform.meta_desc,minform.remLen12,200)" ><?php echo $db_value['meta_desc']?></textarea><br />
	  </td>
	</tr>
	 
	
	
	<tr ><td width="20%">Seo Url (Optional)</td><td><input type=text class='form-control' name='seo_url'  size=55 value="<?php echo $db_value['seo_url']?>" > </td></tr>
	
	</table>
	<table  class='table table-bordered table striped'> 
   
	 
	<tr ><td width="20%">Display Order</td><td><input type=text name='ord' class='form-control' size=30 value="<?php echo $db_value[ord]?>" > 
	<br />Example: 10</td></tr>

	
	<tr><td valign="top" width="20%">Status </td>
	<td><input type="radio" value='Y' name="status"  <?php if($db_value['status']=="Y"){echo"checked";}?>>Online <input type=radio name="status" value='N' <?php if($db_value['status']=="N"){echo"checked";}?>>Offline</td></tr>
  
	<tr><td width="20%"></td><td  ><input type='submit'  name='submit' <?php if($db_value['id']){ ?>value='Update'<?php }else{ ?>value='Add'<?php } ?> class='btn btn-success' >&nbsp;&nbsp;<input type="button" class="btn btn-danger" onClick="javascript:history.back();" value='Cancel'></td></tr>
	</table>
	</form>
	</div>

<?
if($db_value['menu']=='Y')
{
	?>
	<script type="text/javascript">
	toggle('Y');
	</script>
	<?
	
}
?>	
 
<script>
    CKEDITOR.replace('contents', {
      height: 220,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
 
   
    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
      document.getElementById('ie8-warning').className = 'tip alert';
    }
  </script> 