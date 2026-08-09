 	<form method='post' name="minform" action="<?=basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">

		<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">

		<table width="100%" border="0" align="left" cellspacing="0" cellpadding="4"   class="table table-bordered">
 
		<tr ><td valign="top" width="20%">Name</td><td><input type=text name='name'  size=30 value="<?=$db_value['name']?>" class='form-control'></td>

		</tr>

		<tr  ><td valign="top">E-mail </td><td><input name='email' type=text id="email" class='form-control' value="<?=$db_value['email']?>"  ></td>

		</tr>

		<tr ><td valign="top">Mobile </td><td><input name='mobile' type=text id="mobile" class='form-control' value="<?=$db_value['mobile']?>"  ></td>


		</tr>
		
         <tr ><td valign="top">Message </td><td><textarea name="message" id="message" class='form-control description' style='width100%; height100px'><?php echo html_entity_decode($db_value['message']);?></textarea></td></tr>  
 <tr ><td valign="top">Date </td><td><input name='date' type=text id="subject" class='form-control' value="<?=$db_value['date']?>"  > </td>	</tr>

        <tr ><td valign="top">Status </td>
        <td><input type="radio" name="status" id="status" value="Y" <?php if($db_value['status']=='Y'){ echo "checked";}?>>Active &nbsp;
			<input type="radio" name="status" id="status" value="N" <?php if($db_value['status']=='N'){ echo "checked";}?>>Inactive
		</td>
		 
	</tr>

	<tr  ><td></td><td height="20"><input type='submit'  name='SBMT_REG' value='Update' class='btn btn-success' > <input type=button   value='Cancel' onClick="javascript:history.back();" class='btn btn-danger' ></td>

	</tr>

	</table>
 
</form>