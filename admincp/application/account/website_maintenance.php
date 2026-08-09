<?
include("includes/access.php");
include("includes/header.php");

if($_POST['SBMT_REG']):
   //mysqli_query($con,"delete from " . $prev . "setting");
   $r=mysqli_query($con,"update  " . $prev. "setting set 
   site_maintenance=\"" . $_POST['site_maintenance'] . "\",
   maintenance_msg=\"" . htmlentities($_POST['maintenance_msg']) . "\"  where id='1'"); 
          
   $msg="<h4><i class='fa fa-check-square-o'></i> Update Successful.</h4>";
endif;

    
?>
<div class="wrapper">
<!-- BEGIN LEFTSIDE -->
<?php include("includes/left.php");?>
<!-- BEGIN RIGHTSIDE -->
<div class="rightside bg-grey-100">
<!-- BEGIN PAGE HEADING -->
<div class="page-head bg-grey-100">
<h1 class="page-title">Site Setting</h1>
<?php if($msg)
	{
	  echo $msg;	
	}
?>
</div>
<div class="container-fluid">
 <div class="panel-body no-padding-top bg-white">
<h4 class="color-grey-700">Website Maintenance</h4>
<div class="table-responsive">
<?
$r=mysqli_query($con,"select * from " . $prev . "setting where id=1");
$d=@mysqli_fetch_array($r);
?>
    <form name="setting" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" onSubmit="javscript:return ValidSet(this);">
 
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable"><br />
        
        <tr >
        <td valign=top width=20%><b>Wbsite Maintenance :</b></td>
        <td ><input type=radio  name='site_maintenance' value="N" <?php if($d['site_maintenance']=='N'){echo"Checked";}?>> No
        <input type=radio  name='site_maintenance' value="Y" <?php if($d['site_maintenance']=="Y"){echo"Checked";}?>> Yes
        </td>
        </tr>
      
        
        <tr><td colspan=2><b>Maintenance Description :</b>
         <script src="../ckeditor/ckeditor.js"></script>
     <script src="../ck.js"></script>
  
	<textarea  id="maintenance_msg" name="maintenance_msg"><?php echo html_entity_decode($d["maintenance_msg"]);?></textarea>
	<script>
	CKEDITOR.replace( 'maintenance_msg',{height:300,
		fullPage: true,
		allowedContent: true,
		extraPlugins: 'wysiwygarea',
		
		filebrowserBrowseUrl: '/makemyecommerce/glcleads/ckfinder/ckfinder',
	    filebrowserImageBrowseUrl: '/makemyecommerce/glcleads/ckfinder/ckfinder?Type=Images',
	    filebrowserUploadUrl: '/makemyecommerce/glcleads/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	    filebrowserImageUploadUrl: '/makemyecommerce/glcleads/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	    filebrowserWindowWidth : '1000',
	    filebrowserWindowHeight : '700'
	    
	});
   </script>
        
         </tr>
        
         
        <tr ><td></td><td height=25 align="left">
        <input type=submit class="btn btn-success" name='SBMT_REG' value='Update'>
        </td></tr>
        </table>
        <input type='hidden' name='id' value='<?php echo $d['id'];?>'>
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