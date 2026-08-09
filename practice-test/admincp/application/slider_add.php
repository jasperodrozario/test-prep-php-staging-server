<?php 
include_once("includes/access.php");
include("includes/header.php");
?>

<?php include("includes/left.php");?>
		<!-- BEGIN RIGHTSIDE -->
        <div class="rightside bg-grey-100">
		<!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
			<h1 class="page-title">Slider Management </h1> 
			<?php if($msg){echo"<table width=100% cellpadding=0 cellspacing=0 border=0 style='margin-top:12px'><tr><td>" .$msg ."</td><td  style='float:right;'><input type='button' value='Add New' class='btn btn-success'  onclick=\"javascript:window.location.href='page.editor.php?id=0'\" ></td></tr></table>";}?>
		    
		</div>
		
		<!-- END PAGE HEADING -->
		<? 
		// if($_REQUEST['id']){$id=$_REQUEST['id'];}
		// $r=mysqli_query($con,"select * from " . $prev . "banner where id=" . $id);
		// $d=@mysqli_fetch_array($r); 
		// if(!$d[status]){$d[status]="Y";}
		?>		
        <div class="container-fluid">
        
         <div class="panel-body no-padding-top bg-white">
		<h4 class="color-grey-700"><a href='slider.php'>Slider Page List</a> > <?php echo   'Slider' ;?></h4>
		<div class="table-responsive">
		
 

	   <SCRIPT language="JavaScript">
	   <!-- Begin
	   function textCounter(field,cntfield,maxlimit) {
	   if (field.value.length > maxlimit) { 
	   field.value = field.value.substring(0, maxlimit);
	   alert('Max lenght is 200 characters. You have typed ' + field.value.length + ' charachters.');
	   }
	   // otherwise, update 'characters left' counter
	   else
	   cntfield.value = maxlimit - field.value.length;
	   }
	   //  End -->

		</SCRIPT>
      <SCRIPT type="text/javascript">
					function submit_form(value) {
					if(value.meta_desc.value.length >200) {
						alert("The Abstract Contents must be between in 200 characters.");
						value.meta_desc.focus();
						return false;
					}
					else {
						return true;
					}
			}
		</SCRIPT>
		
		<SCRIPT type="text/javascript">
					function submit_form(value) {
					if(value.meta_key.value.length >200) {
						alert("The Contents must be in between 200 characters.");
						value.meta_key.focus();
						return false;
					}
					else {
						return true;
					}
			}
		</SCRIPT>
		<script>
function toggle() {
 if( document.getElementById("hidethis").style.display=='none' ){
   document.getElementById("hidethis").style.display = '';
 }else{
   document.getElementById("hidethis").style.display = 'none';
 }
}
</script>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method=post enctype="multipart/form-data"  name="minform">
<input type=hidden name='id' value="<?php echo $d[id]?>">
<table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>

<!--<tr ><td width="20%">Product Category</td><td><input type=checkbox value='Y' name=product_category  <?php if($d[product_category]=="Y"){echo"checked";}?>> Yes </td></tr>-->
<tr ><td width="20%">Page Image</td><td><?php if($d['img']){echo"<img src='../image.php?image=" .$d['img'] . "&width=132'><br />";}?>
<input type="hidden" name="change_image" value="<?php echo $d['img']?>" />
<input type="file" name="image" value="<?php echo $d['img']?>" />Display Picture : <input type="checkbox" name=display_picture value='Y' <?php if($d[display_picture]=="Y"){echo "Checked";}?> > Yes<br>
(936px × 232px)
</td></tr>

<tr ><td valign=top width="20%">Status </td>
<td><input type=radio value='Y' name=status  <?php if($d[status]=="Y"){echo"checked";}?>>Yes <input type=radio name=status value='N' <?php if($d[status]=="N"){echo"checked";}?>>No</td></tr>



<tr><td></td><td  height=20 ><input type=submit  name='SBMT_REG' value='Add' class='btn btn-success' >&nbsp;&nbsp;<input type="Reset" class="btn btn-default" value="Cancel"></td></tr>
</table>
</form></div>
</div>
<!-- BEGIN FOOTER -->
				<?php include("includes/footer-top.php");?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div><!-- /.rightside -->

<?php include("includes/index_footer.php");?>