 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="mailsetup"> 
   <input type='hidden' name="id" value="<?=$_GET['id'];?>">
   

    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 ><b>Email Setting : </b><br>Dont not change this</td><td><input type='text' class="form-control" name='mail_type' value='<?=$db_value['mail_type']?>'></td></tr>
    <tr ><td valign=top width=20%><b>Subject :</b></td>
	<td ><input type='text' class='form-control' name='subject' value='<?=$db_value['subject'];?>'></td></tr>
    <tr ><td valign=top width=20%><b>Email :</b></td>
	<td ><textarea name='template' id='template' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['template']);?></textarea>
    </td></tr>
    <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
 