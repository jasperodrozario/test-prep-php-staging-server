
<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data" onSubmit="javscript:return Validbanner(this);">
<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
 

  <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
  <tr><td align="left">Question</td>
  <td><input type="text" class='form-control' name='question'  value="<?php echo $db_value['question']?>" required></td></tr>

   <tr><td align="left">Answers</td>
  <td><textarea  cols="50" rows="5" name="answers" id="answers" class="form-control" required><?php echo html_entity_decode($db_value['answers']);?></textarea></td></tr>

  <!-- <tr><td align="left">Category</td>
  <td>
   

    <select name="category" class='form-control' required>
    <option value=''>Select</option>
  
    <option value="S" <?if($db_value['category']=="S"){echo" selected";}?>> Student</option>

    <option value="C" <?if($db_value['category']=="C"){echo" selected";}?>> College</option>

    
 
    </select>
  </td></tr> -->


  <tr><td align="left">Order</td>
  <td><input type="text"  name="ord" id="ord"  class='form-control' value="<?php echo $db_value['ord'];?>" required></td></tr>


   
  <tr  ><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?> required>Active <input type='radio' name=status value='N' <?if($db_value['status']=="N"){echo"checked";}?> required>Hidden</td></tr>
  <tr><td></td><td ><input type='submit'  name='submit' value='Update' class='btn btn-success'>&nbsp;&nbsp;<? if($id){?><input type='submit'  name='SBMT_DEL' class='btn btn-danger' value='Delete' ><? }?></td></tr>
  </table>
</form>
<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>
<script>
var editor;
KindEditor.ready(function(K) 
{
  editor = K.create('#contents', {
    allowFileManager : true
  });
});
</script>