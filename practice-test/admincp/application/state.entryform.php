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
        <div class="page-head bg-grey-100">
			<h1 class="page-title"><a href='state.list.php'>States Management</a></h1>
		</div>
		<!-- END PAGE HEADING -->
        <div class="container-fluid">
         <div class="panel-body no-padding-top bg-white">
		<h4 class="color-grey-700">State Add</h4>
		<div class="table-responsive">
<?
$msg="";
if(isset($_POST['STATE']))
{
//$state_desc=$_POST['state_id'];
	if($_POST['state_id']=="")
	{
		
	        $insert_query = "INSERT INTO " .$prev."state SET 
	        state_code='" . mysqli_escape_string($_POST['state_code']) .  "',
			state_name= '" . mysqli_escape_string($_POST['state_name']) . "',
		    cur_date= now(),
			status='" . mysqli_escape_string($_POST['status']) . "'";		
			$r=mysqli_query($con,$insert_query) or die("Error :". mysqli_error());
			//$state_id=mysqli_insert_id($con);
			$_SESSION['succ_message']='State added successfully';
			pageRedirect('state.list.php?menuid=116&menupid=113');
	}
   	else
	{
   		$upd_query = "UPDATE " .$prev."state SET 
		state_code='" . mysqli_escape_string($_POST['state_code']) .  "',
		state_name='" . mysqli_escape_string($_POST['state_name']) .  "',
		cur_date= now(),
		status='".mysqli_escape_string($_POST['status']) ."'
		where state_id='".$_POST['state_id']."'	";
		$r=mysqli_query($con,$upd_query) or die("Error :". mysqli_error());
		$_SESSION['succ_message']='State updated successfully';
		pageRedirect('state.list.php?menuid=116&menupid=113');
	   
	}
	
   	  
   
 }	
#if product exists /fetch data
if($_GET['state_id']!='')
{
	$state_id=$_GET['state_id'];
	$r=mysqli_query($con,"SELECT * FROM ".$prev."state WHERE state_id=" . $state_id);
	$d=@mysqli_fetch_array($r);
	$status = $d['status'];
}

/*if($msg):
   echo"<br><table align='center' cellpadding='5' align='center' cellspacing='0' width='100%' style='border:solid 1px $dark'><tr><td align='center' height='25'><div class='lnk'>" .$msg . "</div></td></tr></table><br>"; 
endif;*/



?>
 
<!--Script for validation-->
<script language="javascript">
function addRestaurantStateValidate() {
	//alert('aaaaaa');
	var txt = '';
	if(document.getElementById('state_name').value == "") {
			$("#resNamePerErr").html("Please select State Name");
			txt ='err';
			/*alert(txt);*/
		}else{
			$("#resNamePerErr").html("");
	}
	if(document.getElementById('state_code').value == "") {
			$("#resNamePriErr").html("Please select State Code");
			txt ='err';
			/*alert(txt);*/
		}else{
			$("#resNamePriErr").html("");
	}
	
	if(txt != "") {
		//alert("Hello guest, the following fields are mandatory:-\n\n" + txt);
		return false;
	}
}

	
</script>
<script>
$(function() {
$( document ).tooltip();
});
</script>
<form name="addNewResOffr" method="post" action="" onsubmit="return addRestaurantStateValidate(); " >
			<input type="hidden" name="state_id" id="state_id" value="<?php echo $_GET['state_id']?>">
	  
									
						
			<div class="addPageCont">
					<span class="addPageRightFont">State Name  <span class="color1">*</span></span>
					<span class="colon1">:</span>
					<input name="state_name" type="text" id="state_name" class="form-control" value="<?php echo $d['state_name']?>" />
					<script type="text/javascript">document.addNewResOffr.offer_percentage.focus();</script>
					<div class="tooltip" ><div class="HelpButton" title="Enter State Name">?</div></div>
					<!--<input type="hidden" name="offer_id" id="offer_id" value="" />-->
                    <span class="errClass" id="resNamePerErr"></span>
		</div>
			
			<div class="addPageCont">
					<span class="addPageRightFont">State Code<span class="color1">*</span></span>
					<span class="colon1">:</span>
					<input name="state_code" type="text" id="state_code" class="form-control" value="<?php echo $d['state_code']?>" />
			  <div class="tooltip"><div class="HelpButton" title="Enter State Code">?</div></div>
                    <span class="errClass" id="resNamePriErr"></span>
		</div>
			
			
			<div class="addPageCont">
                <span class="addPageRightFont">Status </span>
                <span class="colon1">:</span>
                <span class="radioBtn">
                <span class="labelcont"><input class="radiobotton" type="radio" name="status" id="restaurant_pickup_yes" value="Y"  <?php echo ($status=='Y')?'checked':''?> checked="checked"/><label class="labelfont" for="yes">&nbsp;Yes</label> </span>	
                <span class="labelcont"><input class="radiobotton" type="radio" name="status" id="restaurant_pickup_no" value="N"<?php echo ($status=='N')?'checked':''?>  /><label class="labelfont" for="no">&nbsp;No</label></span>
                </span>
                <div class="tooltip"><div class="HelpButton" title="Select any one  pickup option">?</div></div>
            </div>
			
			<div class="buttonCont2">
			  <input type="submit" class="btn btn-success" name="STATE" value="<?php echo ($_GET['state_id']!="")?"Update":"Add"?>" >
			 <input type='button' class="btn btn-danger" onClick="javascript:window.location.href='state.list.php'" value='Cancel'>
				<!--<a class="CanceButton" href="javascript:void(0);" onclick="tb_remove()">Cancel</a>-->
			</div>
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