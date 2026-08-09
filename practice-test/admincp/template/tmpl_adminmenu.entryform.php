<form method="post" action="<?=basename($_SERVER['PHP_SELF'],".php");?>" role='form'>
<input type="hidden" name="parent_id" value='<?=$_REQUEST['parent_id']?>'>
<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
<input type="hidden" name="tid" value="<?=$_REQUEST['tid']?>">
<table width="100%" border="0" align="center" cellpadding=3 cellspacing=1 class="table table-bordered">

<tr class="vertical-middle" ><td valign=top align="left">Name</td><td align="left"><input type="text" size=55 name="name" value="<?=$db_value['name']?>" class="form-control"></td></tr>
<tr class="vertical-middle" ><td valign=top align="left">Url</td><td align="left"><input type="text" size=55 name="url" value="<?=$db_value[url]?>" class="form-control"></td></tr>
<tr class="vertical-middle" ><td valign=top align="left">Menu Order</td><td align="left"><input type="text" size=5 name="ord" value="<?=$db_value[ord]?>" class="form-control"> [Example. 2]</td></tr>
<tr  class="vertical-middle"><td align="left">Status</td><td align="left"><input type=radio name=status value="Y" <?if($db_value["status"]=="Y"){echo" checked";}?> >Online <input type=radio name=status value="N" <?if($db_value["status"]=="N"){echo" checked";}?>> Offline </td></tr>
<tr><td></td><td><input type="submit" name="submit" value="Update" class='btn bg-green-500 color-white btn-dark padding-left-40 padding-right-40'>&nbsp;&nbsp;<?if($_REQUEST['id']){?><input type="submit" name="DELT" value="Delete" class='btn danger'><?}?></td></tr>
</table><br>
</form>