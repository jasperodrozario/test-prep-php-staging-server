<?php
include("includes/access.php");
include("includes/header.php");

?>
<div class="wrapper">
<!-- BEGIN LEFTSIDE -->
<?php include("includes/left.php");?>
<!-- BEGIN RIGHTSIDE -->
<div class="rightside bg-grey-100">
<!-- BEGIN PAGE HEADING -->
<div class="page-head bg-grey-100"><h1 class="page-title"><a href='registration.list.php'>Register Member Managment</a></h1>
</div>
<!-- END PAGE HEADING -->
<div class="container-fluid">
 <div class="panel-body no-padding-top bg-white">
<h4 class="color-grey-700">Register Member</h4>
<div class="table-responsive">
<?php
$msg="";
if($_REQUEST[Update])
{
	if($_REQUEST['user_id']=='0')
	{
		$sql_chk=mysqli_query($con,"SELECT email_id FROM ".$prev."user WHERE email_id='".$_REQUEST['email_id']."'");
		$n=mysqli_num_rows($sql_chk);
		if($n>0)
		{
		$msg="This Email ID already already exist";
		}
		else
		{

			$user_insert =mysqli_query($con,"INSERT INTO ".$prev."user SET
			f_name='".mysqli_real_escape_string($_POST['f_name'])."',
			l_name='".mysqli_real_escape_string($_POST['l_name'])."',
			apart_ment='".mysqli_real_escape_string($_POST['apart_ment'])."',
			street ='".mysqli_real_escape_string($_POST['street'])."',
			cross_street ='".mysqli_real_escape_string($_POST['cross_street'])."',
			land_mark ='".mysqli_real_escape_string($_POST['land_mark'])."',
			area ='".mysqli_real_escape_string($_POST['area'])."',
			state_id='".mysqli_real_escape_string($_POST['state_id'])."',
			city_id='".mysqli_real_escape_string($_POST['city_id'])."',
			zip_id ='".mysqli_real_escape_string($_POST['zip_id'])."',
			contact_1 ='".mysqli_real_escape_string($_POST['contact_1'])."',
			contact_2 ='".mysqli_real_escape_string($_POST['contact_2'])."',
			addr_label ='".mysqli_real_escape_string($_POST['addr_label'])."',
			email_id='".mysqli_real_escape_string($_POST['email_id'])."',	 
			pass_word='".mysqli_real_escape_string($_POST['pass_word'])."',
			status ='N'");
			
			$id=mysqli_insert_id($con);
			if($user_insert)
			{
				$to  = $_REQUEST['email_id'];
				$from=$setting['admin_mail'];
				$message="Please Click on the following link to validate your mail";
				$link=$vpath."user_email_valid.php?id=$id";

				// subject
				$subject = 'Email validation';

				// message
				$message = "
				<html>
				<head>
				 <title>Email validation</title>
				</head>
				<body>
					<table>
						 <tr>
							<td>$message</b></td>
						 </tr>
						
						 <tr>
							<td>$link</td>
						 </tr>
					</table>
				</body>
				</html>
				";

				// To send HTML mail, the Content-type header must be set
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'From:'.$from . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				// Mail it
				mail($to, $subject, $message, $headers);
			}

			$msg= "Registration Successfull, please check your mail.";
			//pageRedirect('registration.list.php?menuid=52&menupid=46');
		
		}
		
	}
   	else
	{
   		$user_update =mysqli_query($con,"UPDATE ".$prev."user SET
		f_name='".mysqli_real_escape_string($_POST['f_name'])."',
		l_name='".mysqli_real_escape_string($_POST['l_name'])."',
		apart_ment='".mysqli_real_escape_string($_POST['apart_ment'])."',
		street ='".mysqli_real_escape_string($_POST['street'])."',
		cross_street ='".mysqli_real_escape_string($_POST['cross_street'])."',
		land_mark ='".mysqli_real_escape_string($_POST['land_mark'])."',
		area ='".mysqli_real_escape_string($_POST['area'])."',
		state_id='".mysqli_real_escape_string($_POST['state_id'])."',
		city_id='".mysqli_real_escape_string($_POST['city_id'])."',
		zip_id ='".mysqli_real_escape_string($_POST['zip_id'])."',
		contact_1 ='".mysqli_real_escape_string($_POST['contact_1'])."',
		contact_2 ='".mysqli_real_escape_string($_POST['contact_2'])."',
		addr_label ='".mysqli_real_escape_string($_POST['addr_label'])."',
		email_id='".mysqli_real_escape_string($_POST['email_id'])."',	 
		pass_word='".mysqli_real_escape_string($_POST['pass_word'])."' WHERE user_id='".$_REQUEST['user_id']."'");
		
		if($user_update)
		{
			$msg= "Updated Successfully";
			//pageRedirect('registration.list.php?menuid=52&menupid=46');
		}
	   
	}
 }	
#if product exists /fetch data
if($_REQUEST['user_id']!='0')
{
	$user_id=$_REQUEST['user_id'];
	$r=mysqli_query($con,"select * from ".$prev."user where user_id=" . $user_id);
	$d=@mysqli_fetch_array($r);
	
}

if($msg):
   echo"<br><table align='center' cellpadding='5' align='center' cellspacing='0' width='100%' style='border:solid 1px $dark'><tr><td align='center' height='25'><div class='lnk'>" .$msg . "</div></td></tr></table><br>"; 
endif;
?>
<script>
		$(function() {
$( document ).tooltip();
});



$(document).ready(function(){
	$('#state_id').change(function(){
		var state_id=$(this).val(); 
		//alert(state_id);
		
	  	var dataString='state_id='+state_id;
				 $.ajax({
					type:"POST",
					data:dataString,
					url:"<?php echo $vpath?>ajax/get_city.php",
					success:function(return_data)
					{
				$('#ajax_city').html(return_data);
					}
				});
			
		});
		});
</script>
<!--Script for validation-->
<script type="text/javascript"> 
function valid()
{
var f_name=document.cust_reg.f_name.value;
var l_name=document.cust_reg.l_name.value;
var street=document.cust_reg.street.value;
var apart_ment=document.cust_reg.apart_ment.value;
var cross_street=document.cust_reg.cross_street.value;
var area=document.cust_reg.area.value;
var state_id=document.cust_reg.state_id.value;
var city_id=document.cust_reg.city_id.value;
var zip_id=document.cust_reg.zip_id.value;
var contact_1=document.cust_reg.contact_1.value;
var contact_2=document.cust_reg.contact_2.value;

var email_id=document.cust_reg.email_id.value;
var at=email_id.indexOf('@');
var dot=email_id.lastIndexOf('.');
var pass_word=document.cust_reg.pass_word.value;
var conf_pass_word=document.cust_reg.conf_pass_word.value;


	if(f_name.search(/\S/)==-1)
	{
	alert("Please enter First name");
	document.cust_reg.f_name.focus();
	return false;
	}
	
	if(l_name.search(/\S/)==-1)
	{
	alert("Please enter Last name");
	document.cust_reg.l_name.focus();
	return false;
	}
	
	if(street.search(/\S/)==-1)
	{
	alert("Please enter Street address");
	document.cust_reg.street.focus();
	return false;
	}
	
	if(apart_ment.search(/\S/)==-1)
	{
	alert("Please enter Apt/Suite/Building");
	document.cust_reg.apart_ment.focus();
	return false;
	}
	
	if(cross_street.search(/\S/)==-1)
	{
	alert("Please enter Cross Street");
	document.cust_reg.cross_street.focus();
	return false;
	}
	
	if(area.search(/\S/)==-1)
	{
	alert("Please enter Area");
	document.cust_reg.area.focus();
	return false;
	}
	
	if(state_id.search(/\S/)==-1)
	{
	alert("Please select State");
	document.cust_reg.state_id.focus();
	return false;
	}

	if(city_id.search(/\S/)==-1)
	{
	alert("Please select City");
	document.cust_reg.city_id.focus();
	return false;
	}

	if(zip_id.search(/\S/)==-1)
	{
	alert("Please Select Zip");
	document.cust_reg.zip_id.focus();
	return false;
	}

	if(contact_1.search(/\S/)==-1)
	{
	alert("Please enter Phone number");
	document.cust_reg.contact_1.focus();
	return false;
	}
	if(isNaN(contact_1))
	{
	alert("Please enter numeric value only");
	return false;
	}

	if(contact_2.search(/\S/)==-1)
	{
	alert("Please enter Mobile number");
	document.cust_reg.contact_2.focus();
	return false;
	}
	if(isNaN(contact_2))
	{
	alert("Please enter numeric value only");
	return false;
	}
	 
	if(email_id.search(/\S/)==-1)
	{
	alert("Please enter Email");
	document.cust_reg.email_id.focus();
	return false;
	}
	if(at<1 || dot<=(at+2) ||(dot+2)>=(email_id.length))
	{
	alert("Email format invalid");
	document.cust_reg.email_id.focus();
	return false;
	}

	if(pass_word.search(/\S/)==-1)
	{
	alert("Please enter Password");
	document.cust_reg.pass_word.focus();
	return false;
	}

	if(conf_pass_word.search(/\S/)==-1)
	{
	alert("Please enter Confirm Password");
	document.cust_reg.conf_pass_word.focus();
	return false;
	}
	
	if(conf_pass_word!=pass_word)
	{
	alert("Confirm Password not match");
	document.cust_reg.conf_pass_word.focus();
	return false;
	}
}
</script> 

	<form method="post" name="cust_reg" id="cust_reg" action="" onSubmit="return valid()">
	<input type="hidden" name="registration_id" value="<?php echo $registration_id?>">

	<table id="table-1" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="1" cellpadding="4">
    <input name="email_id" type="hidden" id="email_id" class='form-control' value="<?php echo $d['email_id']?>"  >
 	<tr >
		<td width="33%"  >First name  <span style="color:red;">* </span></b></td>
		<td   ><input name="f_name" type="text" id="f_name" class='form-control' value="<?php echo $d['f_name']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Last name  <span style="color:red;">* </span></b></td>
		<td   ><input name="l_name" type="text" id="l_name" class='form-control' value="<?php echo $d['l_name']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Street address  <span style="color:red;">* </span></b></td>
		<td   ><input name="street" type="text" id="street" class='form-control' value="<?php echo $d['street']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Apt/Suite/Building  <span style="color:red;">* </span></b></td>
		<td   ><input name="apart_ment" type="text" id="apart_ment" class='form-control' value="<?php echo $d['apart_ment']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Cross street  <span style="color:red;">* </span></b></td>
		<td   ><input name="cross_street" type="text" id="cross_street" class='form-control' value="<?php echo $d['cross_street']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Land Mark  </td>
		<td   ><input name="land_mark" type="text" id="land_mark" class='form-control' value="<?php echo $d['land_mark']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Area  <span style="color:red;">* </span></b></td>
		<td   ><input name="area" type="text" id="area" class='form-control' value="<?php echo $d['area']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >State  <span style="color:red;">* </span></b></td>
		<td   >
			<? $state_query=mysqli_query($con,"SELECT * FROM ".$prev."state WHERE status='Y' ");?>
			<select  name="state_id" id="state_id" class="form-control">
			<option value="">--Select State--</option>
			<?	$sql_state=mysqli_query($con,"SELECT `state_id`,`state_name` FROM ".$prev."state WHERE status='Y'");
				if(mysqli_num_rows($sql_state)){
							  while($row=mysqli_fetch_object($sql_state)){?>
				<option value="<?php echo  $row->state_id?>" <?php if($d['state_id']==$row->state_id){?> selected='selected' <? }?>><?php echo  $row->state_name?></option>  
							  <? }
							  mysqli_free_result($sql_state);
							}
							?>
			</select>
		</td>
	</tr>
	
	<tr  >
		<td width="33%"  >City  <span style="color:red;">* </span></b></td>
		<td   >
			<? $city_query =mysqli_query($con,"SELECT * FROM ".$prev." city WHERE status='Y'");?>
			<div id="ajax_city">
			   <select name="city_id" id="city_id"  class="form-control">
					<option value="">--Select State--</option>
					<?
					$sql=mysqli_query($con,"SELECT `city_id`,`city_name` FROM ".$prev."city WHERE status='Y'");
									if(mysqli_num_rows($sql)){
									  while($row=mysqli_fetch_object($sql)){?>
					<option value="<?php echo  $row->city_id?>" <?php if($d['city_id']==$row->city_id){?> selected="selected" <? }?>><?php echo  $row->city_name?></option>  
									  <? }
									  mysqli_free_result($sql);
									}
							?>
					
				</select>
			</div>
		</td>
	</tr>
	
	<tr  >
		<td width="33%"  >Zip  <span style="color:red;">* </span></b></td>
		<td   >
			<div id="ajax_zip">
				<select name="zip_id" id="zip_id"  class="form-control">
					<option value="">--Select Zip--</option>
						<?php
						$sql=mysqli_query($con,"SELECT `zip_id`,`area_name`,`zip_code` FROM ".$prev."zip WHERE status='Y'");
							if(mysqli_num_rows($sql)){
							while($row=mysqli_fetch_object($sql)){?>
						<option value="<?php echo $row->zip_id?>" <?php if($d['zip_id']==$row->zip_id){?> selected="selected" <? }?>><?php echo  $row->zip_code.' - '.$row->area_name;?></option>  
									  <?php }
									  mysqli_free_result($sql);
									}
						?>	
				</select>
            </div>
		</td>
	</tr>
	
	<tr  >
		<td  >Phone  <span style="color:red;">* </span></b></td>
		<td align="left"><input name="contact_1" type="text" id="contact_1" class='form-control' value="<?php echo $d['contact_1']?>"  ></td>
	</tr>
	      	
	<tr  > 
		<td  >Mobile  <span style="color:red;">* </span></b></td>
		<td align="left"><input name="contact_2" type="text" id="contact_2" class='form-control' value="<?php echo $d['contact_2']?>"  ></td>
	</tr>
	<tr  >
		<td width="33%"  >Address Label  </td>
		<td   >
		<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
		  <tr>
			<td width="6%"><input name="addr_label" type="radio" value="Home" <?php if($d['addr_label']=='Home'){?> checked="checked" <?php } ?>/></td>
			<td width="17%" align="left">Home</td>
			<td width="6%"><input name="addr_label" type="radio" value="Office" <?php if($d['addr_label']=='Office'){?> checked="checked" <?php } ?>/></td>
			<td width="17%" align="left">Office</td>
			<td width="6%"><input name="addr_label" type="radio" value="Other" <?php if($d['addr_label']=='Other'){?> checked="checked" <?php } ?>/></td>
			<td width="48%" align="left">Other</td>
		  </tr>
		</table>
		</td>
	</tr>
	<tr  >
		<td width="33%"  >&nbsp;</b></td>
		<td   >&nbsp;</td>
	</tr>
	<?php
	if($_REQUEST['user_id']=='0')
	{
	?>
	<tr  >
		<td width="33%"   align="right">LOGIN INFO  </td>
		<td   >&nbsp;</td>
	</tr>
	<tr  >
		<td width="33%"  >Email  <span style="color:red;">* </span></b></td>
		<td   ><input name="email_id" type="text" id="email_id" class='form-control' value="<?php echo $d['email_id']?>"  ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Password  <span style="color:red;">* </span></b></td>
		<td   ><input name="pass_word" type="password" id="pass_word" class='form-control'   ></td>
	</tr>
	
	<tr  >
		<td width="33%"  >Confirm Password  <span style="color:red;">* </span></b></td>
		<td   ><input name="conf_pass_word" type="password" id="conf_pass_word" class='form-control'  ></td>
	</tr>
	<?php
	}
	?>
	<tr   >
		<td></td>
		<td>
		<input type="submit" name="Update" value="Update" class="btn btn-success">&nbsp;&nbsp;
		<input type="reset" value="Reset" class="btn btn-alert">
		</td>
	</tr>
</table>
</form>

			</div>
</div>
<!-- BEGIN FOOTER -->
				<?php include("includes/footer-top.php");?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div><!-- /.rightside -->
    </div><!-- /.wrapper -->
	<!-- END CONTENT -->
	<?php include("includes/index_footer.php");?>