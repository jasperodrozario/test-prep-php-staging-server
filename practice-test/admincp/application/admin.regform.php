<? 
include('includes/access.php');
include('includes/header.php');
 
$_POST[reg_date]=date("Y-m-d");

$msg='';


if($_POST[Update]):
	if(!$_POST[admin_id]):
        $q1="insert into " . $prev. "admin  set 
	    username=\"".$_POST['username']."\",
		password=\"".md5($_POST['password'])."\",
		email=\"" . $_POST['email'] . "\",
		fname=\"" . $_POST['fname'] . "\",			
		lname=\"" . $_POST['lname'] . "\",
		type=\"" . $_POST['type'] . "\",
		mobile=\"" . $_POST['mobile'] . "\",
		reg_date=\"" . date("Y-m-d") . "\",
		status=\"" . $_POST['status'] . "\"";
		$r=mysqli_query($con,$q1);
		$admin_id=mysqli_insert_id($con);
					 
	else:
        $q1="Update " . $prev. "admin  set 
	    username=\"".$_POST['username']."\",
		
		email=\"" . $_POST['email'] . "\",
		fname=\"" . $_POST['fname'] . "\",			
		lname=\"" . $_POST['lname'] . "\",
		mobile=\"" . $_POST['mobile'] . "\",		  
		type=\"" . $_POST['type'] . "\",
		status=\"" . $_POST['status'] . "\" where admin_id=".$_POST['admin_id'];
		$r=mysqli_query($con,$q1);
		if($_POST[password]):
		    $r1=mysqli_query($con,"update " . $prev. "admin  set password=\"" .md5($_POST['password']) . "\" where admin_id=".$_POST['admin_id']);
		endif;
	endif;
	if($r):
	    $msg="<h4 style='color:green'>Update successful.</h4>";
	endif;				
endif;		
if($_REQUEST['admin_id']):
 
	$r=mysqli_query($con,"select * from  " . $prev. "admin where admin_id=".$_REQUEST['admin_id']);
	$data=mysqli_fetch_array($r);
endif;

?>
<script language="javascript" type="text/javascript">
<!--
function isEmail(Ml)
{
  if(!Ml){return true}
  if(Ml.indexOf("@")<=0 || Ml.indexOf("@")==Ml.length-1 || Ml.indexOf(".")<=0 || Ml.indexOf(".")==Ml.length-1 || Ml.indexOf("..")!=-1 || Ml.indexOf("@@")!=-1 || Ml.indexOf("@.")!=-1 || Ml.indexOf(".@")!=-1)
  {
      return false
  }
  else
  {
      return true
  }
}


function Validemployee(register)
{
    var txt="";
	if(!register.fname .value)
	{
		txt+="     First Name  should not be empty.\n"
	}
	if(!register.lname.value)
	{
		txt+="     Last Name  should not be empty.\n"
	}
 
	 
	if(!register.email.value || !isEmail(register.email.value))
	{
	   txt+="     Enter valid Email address.\n"
       
 	}
	/*if(!register.status .value)
	{
		txt+="    Status  should not be empty.\n"
	}*/	

   	if(txt)
	{
   		alert("Sorry!! Following errors has been occured :\n\n"+ txt +"\n     Please Check");
  	 	return false
	}
    return true	
}//-->
</script>
<script language="JavaScript" type="text/javascript">
  function confirmDelete(anchor)
  {
    if (confirm('Are you sure want to delete?'))
    {
      anchor.href += '&confirm=1';
      return true;
    }
    return false;
  }
</script>
<div class="wrapper">
		<!-- BEGIN LEFTSIDE -->
        <?php include("includes/left.php");?>
		<!-- BEGIN RIGHTSIDE -->
        <div class="rightside bg-grey-100">
		<!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
			<h1 class="page-title">Admin Management</h1>
			<?php if($msg){echo"<p >" . $msg . "</p>";}?>
		</div>
		<!-- END PAGE HEADING -->
        <div class="container-fluid">
         <div class="panel-body no-padding-top bg-white">
		<h4 class="color-grey-700"><a href='admin.list.php'>Admin List</a> > Admin Reg Form</h4>
		<div class="table-responsive">
		
		<form method="post"  name="frm" action="<?php echo $_SERVER['PHP_SELF']?>" onSubmit="javascript:return Validemployee(this);">
		<input type=hidden name="admin_id" value="<?php echo $_REQUEST['admin_id'];?>">
		<table  width="100%" class="table table-bordered" border="0" align="center" cellpadding="4"  >

		<tr ><td width="34%" valign=top><b>Login Name<a class="lnkred">*</a> </b></td>
		<td width="66%"><input type=text size=20 class='form-control'  name='username' value="<?php if($data[username]) { ?><?php echo $data[username]?><? } else { echo $_POST[username] ; }?>" ></td></tr>
		<tr ><td valign=top><b>Password <a class="lnkred">*</a> </b> <br>[Enter new password if you like to change old password]</td><td><input type="text" class='form-control'  name='password' value="" ></td></tr>
		<tr ><td width="34%" valign=top><b>Email <a class="lnkred">*</a> </b></td>
		<td width="66%"><input type=text size=30 class='form-control' name='email' value="<?php if($data[email]) { ?><?php echo $data[email]?><? } else { echo $_POST[email] ; }?>" ></td></tr>
		<tr ><td valign=top><b>First Name <a class="lnkred">*</a> </b></td><td><input type=text size=30 class='form-control' name='fname' value="<?php if($data[fname]) { ?><?php echo $data[fname]?><? } else { echo $_POST[fname] ; }?>" ></td></tr>
		<tr ><td valign=top><b>Last Name <a class="lnkred">*</a> </b></td><td><input type=text size=30 class='form-control' name='lname' value="<?php if($data[lname]) { ?><?php echo $data[lname]?><? } else { echo $_POST[lname] ; }?>" ></td></tr>
		<tr ><td valign=top><b>Mobile  </b></td><td><input type=text size=30  name='mobile' class='form-control' value="<?php echo $data[mobile]?>" ></td></tr>
		<tr ><td valign=top><b> Type </b></td><td><input type="radio" name="type"  checked="checked" value="A" <?php if($data["type"]=="A" or $data["type"]==""){echo" checked";}?>>
		  Super Admin <input type="radio" name="type"  checked="checked" value="S" <?php if($data["type"]=="S"){echo" checked";}?>>
		  Sub Admin </td>
		</tr>
		<tr ><td valign=top><b> Status </b></td><td><input type="radio" name="status"  checked="checked" value="Y" <?php if($data["status"]=="Y"){echo" checked";}?>>
		  Online <input type="radio" name="status" value="N" <?php if($data["status"]=="N"){echo" checked";}?> />
		Offline</td>
		</tr>

		<tr><td></td><td><input type="submit" name="Update" value="Update" class='btn btn-ar btn-primary'>  <input type="submit" name="DELT" value="Delete" class='btn btn-ar btn-default' onClick="javascript: confirmDelete()"></td></tr>
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
 