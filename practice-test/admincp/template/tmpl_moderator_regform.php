 
<form method="post"  name="frm" action="<?=$_SERVER['PHP_SELF'];?>" onSubmit="javascript:return Validemployee(this);" enctype='multipart/form-data'>
<input class='form-control' type=hidden name="admin_id" value="<?=$_REQUEST['admin_id'];?>">
<table width=100% border=0 align=center cellpadding=4 cellspacing=1   class="table table-bordered">

	<? 
	if($_REQUEST['admin_id']):
    $db_value=getDataByWhere("admin","*", array("admin_id"=>$_REQUEST['admin_id']));
	endif;
	$city_name=getFldNameById("city","city_name",array("city_id"=>$db_value['city_id']));

	?>

<tr ><td width="24%" valign=top ><b>User Name<a class="lnkred">*</a> </b></td>
<td   align="left"><input class='form-control' type=text size=20  name='username' value="<? if($db_value['username']) { ?><?=$db_value['username']?><? } else { echo $_REQUEST['username'] ; }?>" ></td></tr>
<tr ><td valign=top ><b>Password <a class="lnkred">*</a> </b></td><td align="left"><input class='form-control' type="text" size=20  name='password' value="" >[keep empty if you dont want to change password]</td></tr>

<tr ><td width="24%" valign=top ><b>Email <a class="lnkred">*</a> </b></td>
<td   align="left"><input class='form-control' type=text size=30  name='email' value="<? if($db_value['email']) { ?><?=$db_value['email']?><? } else { echo $_REQUEST['email'] ; }?>" ></td></tr>
<tr ><td valign=top ><b>First Name <a class="lnkred">*</a> </b></td><td align="left"><input class='form-control' type=text size=30  name='fname' value="<? if($db_value['fname']) { ?><?=$db_value['fname']?><? } else { echo $_REQUEST['fname'] ; }?>" ></td></tr>
<tr ><td valign=top ><b>Last Name <a class="lnkred">*</a> </b></td><td align="left"><input class='form-control' type=text size=30  name='lname' value="<? if($db_value['lname']) { ?><?=$db_value['lname']?><? } else { echo $_REQUEST['lname'] ; }?>" ></td></tr>

<tr ><td valign=top>Country</td><td>
<select name="country_id" id="country_id"   class='form-control' required>
<option value="">Select Country</option>
<?
if(!$db_valueata['country_id']){$db_valueata['country_id']=29;}
$countrylist=getQuery("country","*",array("status"=>"Y"),"name");
foreach($countrylist as $list):
?>
  <option value="<? echo $list['id'];?>" <? if($db_valueata['country_id']==$list['id']){?> selected <? } ?>><? echo $list['name'];?></option>
 <?php endforeach;?>
 </select></td></tr>

 <tr ><td valign=top>City Name</td><td>
  <input type='text' value='<?=$city_name;?>' id='city_name' name='city_name' class='form-control' placeholder='Enter City Name' required>
  <input type='hidden' name='city_id' id='city_id' value='<?=$db_value['city_id'];?>'>
  <div id="city-suggesstion-box"></div>	
</td></tr>
<tr ><td valign=top ><b>Department<a class="lnkred">*</a> </b></td><td align="left">


<select name='department' size=1 class='form-control' required="">
	<option value=''>Select Department</option>
	<option  value='Subadmin' <?if($db_value['department']=="Subadmin"){echo"selected";}?>>Subadmin</option>
	<option  value='Writer' <?if($db_value['department']=="Writer"){echo"selected";}?>>Writer</option>
</select>
 </td></tr>


<tr  >
	<td valign="top" align="left"><b> Picture  :</b></td>
	<td>	

		<?if($db_value['photo']!=''): ?>
	<img src="<?=$configs['vpath']?>image.php?image=<?=$configs['vpath'].$db_value['photo']?>&width=200&height=200&cropratio=2:1"/>
		<?
		endif;
		?>
		<br/>
	<input type="file"  name="photo" class='form-control' />



		</td>
</tr>

<tr ><td valign=top ><b> Status </b></td><td align="left"><input   type="radio" name="status"  checked="checked" value="Y" <?if($db_value["status"]=="Y"){echo" checked";}?>>
  Online <input   type="radio" name="status" value="N" <? if($db_value["status"]=="N"){echo" checked";}?> /> Offline
  </td></tr>

<tr  ><td height=26></td><td ><input class='btn btn-success' type="submit" name="Update" value="Update" class=buttonx>  <? if($_REQUEST['admin_id']){?><input class='btn btn-default' type="submit" name="DELT" value="Delete"   onClick="javascript: confirmDelete()"><?}?>  <input  type="Button" onclick="goBack()" class='btn btn-default' value="Back"></td></tr>
</table>
</form>