<script language="javascript" type="text/javascript">

function ValidEditor(register)
{
    var txt="";

	if(!register.cont_title .value)
	{
		txt+="     Title should not be empty.\n"
	}

   	if(txt)
	{
   		alert("Sorry!! Following errors has been occured :\n\n"+ txt +"\n     Please Check");
  	 	return false
	}
    return true
}
</script>
 
 
<?php  
if($_REQUEST['fd_id']):

	$r=mysqli_query($con,"select * from " . $prev . "feedback where fd_id=" . $_REQUEST[fd_id]);
    //echo "select * from " . $prev . "feedback where fd_id=" . $_REQUEST[fd_id];
   	
   	$d=@mysqli_fetch_array($r);

endif;

if(!$d['admin_chk']){$d['admin_chk']="Y";}
?> 

<!-- <ol class="breadcrumb bc-3" >
	<li>
		<a href="index.php"><i class="fa fa-home"></i>Home</a>
	</li>
	<li ><a href='feedback_list.php'>Feedback Management</a></li>
	<li >Add | Edit Feedback</li>
</ol> -->

<?php  
if($msg)
{
   echo $msg;	
}
?>	 
<!-- <h4 class="color-grey-700">Feedback Add/Edit</h4>
		<br /> -->
	
			<div class="col-md-12">
				
				<div class="panel panel-primary" data-collapsed="0">
						
					<div class="panel-body">
 

<form method='post' name="minform" action="<?php  echo basename($_SERVER['PHP_SELF'],".php")?>" enctype="multipart/form-data" onSubmit="javascript:return ValidEditor(this);">

<input type="hidden" name="fd_id" value="<?php  echo $_REQUEST['fd_id'];?>">
<input type="hidden" name="city_id" value="<?php  echo $d['city_id'];?>">

<table width="100%" border="0" align="center" cellspacing="0" cellpadding="4"   class="table table-bordered table-striped">



	<tr>
		<td valign="top" width="20%">Feedback By<font color="#CC3300">*</font></td>
		<td><input type=text name='fd_by' class='form-control' size=30 value="<?php  echo $d['fd_by']?>"  ></td>
	</tr>
	<tr>
			<td valign="top" width="20%">City<font color="#CC3300">*</font></td>
		<td>
	<!-- <input type=text name='city_id'  size=30 value="<?php  echo $cityname?>" class='form-control'> -->
		<select name="city_id" id="city_id" class='form-control'>
			<option value=''>Select</option>
			<? 
			foreach ($cities as $c) {

				$cityname=getFldValueById('city',"name",array("id"=>$c['id']));

					if($c['id']==$d['city_id']):
			echo"<option value='" . $c['id'] . "' selected>" . $cityname . "</option>\n";
		else:
	    	echo"<option value='" . $c['id'] . "'>" . $cityname . "</option>\n";
	 	endif;

	 	}

				?>


			
    
  
    

    
 
    </select>
	</td>
	</tr>

	<tr>
		<td valign="top">Description :</td>
		<td><textarea name="fd_desc" id="fd_desc" cols="46" rows="10"  class='form-control'><?php  echo html_entity_decode($d['fd_desc']);?></textarea></td>
	</tr>
    <!--<tr>
		<td valign="top" width="20%">Feedback By Picture<font color="#CC3300">*</font></td>
		<td><input type=file name='fd_pic'  size=30 value="<?php  echo $d['fd_pic']?>" class='form-control'>
		<?php  if($d['fd_pic']){echo"<br><img src='../" . $d[fd_pic] . "' width=100>";}?>
		</td>
	</tr> -->
	<tr>
			<td valign="top" width="20%">Feedback on Date<font color="#CC3300">*</font></td>
			<td><input type=text name='fd_dt' id="fd_dt"  size=30 value="<?php  echo $d['fd_dt']?>" class='form-control'><br>[yyyy-mm-dd]</td>
	</tr>
		<!-- <TR><TD  height="25" align=left valign="top" > Expiry Date11  [Optional]</TD>
	<TD><INPUT name="exp_date" id="exp_date" type="text" class='form-control' value="<?php  echo $db_value["exp_date"];?>" autocomplete="off">
	</TD></TR> -->

	<tr>
		<td valign="top" width="20%">Feedback Picture<font color="#CC3300">*</font></td>
		<td>
			<? 
			if($d['fd_pic']){
				?>
				<img src="<?=$configs['vpath'].$d['fd_pic'] ?>" style="width:100px; height: 80px;">

				<?

			}
			?>
			<input type="file" name='fd_pic' class='form-control' value="<?php  echo $d['fd_pic']?>"  ></td>
	</tr>
 

	<tr ><td valign="top" width="20%">Status</td>
		<td>
			<input name='admin_chk' type='radio' value='Y'  <?php   if($d['admin_chk']=="Y"){echo"checked";}?>>Online
			<input type='radio' name='admin_chk' value='N' <?php   if($d['admin_chk']=="N"){echo"checked";}?>>Offline
		</td>
	</tr>

	<tr >
		<td></td>
		<td height="20"><input type='submit'  name='SBMT_REG' value=' <?php   if($fd_id){?>Update <?php   }else{?>Add<?php   }?> ' class='btn btn-success' > <input type='button'  onClick="javascript:history.back();" value='Cancel' class='btn btn-default' ></td>
	</tr>
</table>

</form>
</div>
</div>
</div>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

<script type="text/javascript">
 
  var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

    $("#start_date").datepicker({
    
   dateFormat: "yy-mm-dd",
        minDate: today
   
       
    });
  $("#fd_dt").datepicker({
    
   dateFormat: "yy-mm-dd",
        minDate: today
   
       
    });
</script>  