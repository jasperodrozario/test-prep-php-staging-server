  <script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<!--  <div id='response'></div> -->
 <form method='post' id='form-content' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="add-course"> 


<input type='hidden' name="course_id" value="<?=$_GET['course_id'];?>">

    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 >Course Name </td><td><input type='text' class="form-control" name='name' value='<?=$db_value['name']?>' required></td></tr>

 <tr ><td height=25 >Course Header </td><td><input type='text' class="form-control" name='header' value='<?=$db_value['header']?>' required></td></tr>
  <tr ><td height=25 >Course Tagline </td><td><input type='text' class="form-control" name='tagline' value='<?=$db_value['tagline']?>' required></td></tr>
   <!-- <tr><td width="15%">Category Name</td><td>
    <select name='cat_id' class='form-control' size=1>
    	 
		<?php
		    foreach($category as $d)
			{
				if($d['cat_id']==$db_value['cat_id']):
				    echo"<option value=" . $d['cat_id'] . " selected> " . $d['cat_name'] ."</option>";
				else:
				    echo"<option value=" . $d['cat_id'] . "  value=" . $d['cat_id'] . " > " . $d['cat_name'] ."</option>";
				endif;
			}
				
		   ?>
	    
	     </select>  </td></tr>  -->
    <tr ><td valign=top width=20%>Details </td>
  <td ><textarea name='details' id='details' class="form-control"><?=html_entity_decode($db_value['details']);?></textarea>
    </td></tr>
     <tr ><td valign=top width=20%>Curriculum </td>
  <td ><textarea name='curriculum' id='curriculum' class="form-control"><?=html_entity_decode($db_value['curriculum']);?></textarea>
    </td></tr>


 
    <tr ><td align="left">Duration</td><td align="left"><input type='text' class="form-control" name="duration" value='<?=$db_value['duration'];?>' ></td></tr>

 
      <tr ><td align="left">Eligibility</td><td align="left"> <input type='text' class="form-control" name="eligibility" value='<?=$db_value['eligibility'];?>' > </td>
      </tr>  
    <tr ><td align="left">Video</td><td align="left"><div class='row'><div class='col-md-6'><label>Video</label><input type="file" class='form-control' name="picture" value="" >
    <?if($db_value['picture'] && file_exists($configs['apath'].$db_value['picture']))
    {
    ?>
      <video width="320" height="240" controls="">
          <source src="<?php echo $configs['vpath'].$db_value['picture']; ?>" type="video/mp4">
        </video>
      
    <?}?>
    </div><div class='col-md-6'><label>Thumb Pic</label><input type="file" class='form-control' name="thumb"  value="">
    <?if($db_value['thumb'] && file_exists($configs['apath'].$db_value['thumb']))
    {
    ?>
        <br>
      <img src="<?php echo $configs['vpath'].$db_value['thumb']; ?>" width="80" height="50" >
        <?
        }
        ?></div></div>
    </td></tr>
     
  <tr ><td width="20%">Meta Title</td><td><input type="text" class='form-control' name='meta_title'  value="<?php echo $db_value['meta_title']?>" ></td></tr>
	
	<tr><td valign="top" width="20%">Meta Keys</td>
	  <td ><textarea  cols="50" rows="2" name="meta_keys" class='form-control' ><?php echo $db_value['meta_keys']?>
	</textarea></td>
	</tr>
	<tr >
	  <td valign="top" >Meta Description</td>
	  <td ><textarea  cols="50" rows="2" name="meta_desc"  class='form-control' ><?php echo $db_value['meta_desc']?></textarea><br />
	  </td>
	</tr>
	 <tr >
	  <td valign="top" >Course Video Urls (seperated by comma)</td>
	  <td ><textarea  cols="50" rows="2" name="video"  class='form-control' ><?php echo $db_value['video']?></textarea><br />
	  </td>
	</tr>
	
	
	<tr ><td width="20%">Seo Url (Optional)</td><td><input type=text class='form-control' name='seo_url'  size=55 value="<?php echo $db_value['seo_url']?>" > </td></tr>
 
    <tr  ><td>Display Order </td><td><input type='number' class="form-control" name="ord" value='<?=$db_value['ord'];?>'></td></tr>
    
    <tr  ><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?>>Active <input type='radio' name=status value='N' <?if($db_value['status']=="N"){echo"checked";}?>>Hidden</td></tr>
    <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
 
 <script>
    CKEDITOR.replace('details', {
      height: 220,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form',
      versionCheck: false
    });
	CKEDITOR.replace('curriculum', {
      height: 220,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form',
      versionCheck: false
    });
   
    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
      document.getElementById('ie8-warning').className = 'tip alert';
    }
  </script> 