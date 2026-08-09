<?
include("includes/access.php");
include("includes/header.php");

if($_POST['SBMT_REG']):
 	if($_FILES['logo'][name]!=""):
	 	 $ext=strtolower(substr($_FILES['logo']['name'],-3,3));	         
         if($ext=="peg"){$ext="jpg";}
		 $pic="img/logo." . $ext; 
		 copy($_FILES['logo']['tmp_name'],"../" . $pic);
		 $r=mysqli_query($con,"update  " . $prev. "setting set site_logo=\"" . $pic . "\" where id=1");
	endif;     
    $msg="<h4><i class='fa fa-check-square-o'></i> Update Successful.</h4>";
endif;

$r=mysqli_query($con,"select * from  " . $prev. "setting where id=1");
if(@mysqli_num_rows($r)):
   $d=mysqli_fetch_array($r);
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
<h4 class="color-grey-700">Update Logo</h4>
<div class="table-responsive">
<?
$r=mysqli_query($con,"select site_logo from " . $prev . "setting where id=1");
$d=@mysqli_fetch_array($r);
?>
    <form name="setting" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
 
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dataTable"><br />
                            
                            <tr >
                            <td valign=top width=20%><b>Add Logo :</b></td>
                            <td >
                            <?
                            if($d['site_logo']):
                                 echo"<img src='../image300.php?img=" . $d['site_logo'] . "&target=100'><br>";
                            endif;
                            ?>  
                            <input type=file class='form-control' name='logo'  size=40> 
                            </td>
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