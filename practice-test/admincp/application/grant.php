<?
include("includes/access.php");
include("includes/header.php");
$r=mysqli_query($con,"select * from " . $prev. "admin where admin_id=" . $_REQUEST['admin_id']);
$username=@mysqli_result($r,0,"username");
$s=0;$f=0;
if($_REQUEST['SBMT_REG']):
   	mysqli_query($con,"delete from " . $prev. "admin_grant where admin_id=" . $_REQUEST['admin_id']);
   	$counter = $_REQUEST[tot];
	for($i=0; $i<$counter; $i++):
	    $progid="progid" . $i;
	  	$prog=explode(",",$_REQUEST[$progid]);
		$prog_id = $prog[0];
	   	$proghead_id=$prog[1];
	   	$adminid = $_REQUEST[admin_id];
	   	if($prog_id && $proghead_id):
			$query = "insert into " . $prev. "admin_grant(admin_id,prog_id,proghead_id) values('" . $adminid . "','" .$prog_id . "','" . $proghead_id ."')";
		  	$r1=mysqli_query($con,$query);
		  	$query = "insert into " . $prev. "admin_grant(admin_id,prog_id,proghead_id) values('" . $adminid . "','" .$prog_id . "','" . $proghead_id ."')";
		  	$r1=mysqli_query($con,$query);		
		endif;
  	endfor;
	$msg="<h4 style='color:green'>Update successful.</h4>";
endif;


$array_id=array();
$sql7=mysqli_query($con,"select * from " . $prev. "admin_grant where admin_id=".$_REQUEST[admin_id]);
while($ppp=@mysqli_fetch_array($sql7)):
	$array_id[]=$ppp['prog_id'];
endwhile;
?>
<script language='JavaScript'>
      checked = false;
      function checkedAll ()
	  {
       if (checked == false){checked = true}else{checked = false}
	   for (var i = 0; i < document.getElementById('myform').elements.length; i++)
	    {
	   document.getElementById('myform').elements[i].checked = checked;
        }
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
		<h4 class="color-grey-700"><a href='admin.list.php'>Admin List</a> > Grant Module  </h4>
		
		<div class="table-responsive">
		  
   <form id="myform" method=post action="<?php echo $_SERVER['PHP_SELF'];?>"  onsubmit="return checkreg(this);">
   <input type=hidden name=admin_id value=<?php echo $_REQUEST['admin_id']?>>
   <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" class='table table-bordered'>
   <tr  ><td height=30  align="left"><?php if($_REQUEST[admin_id]){echo "Update User : " . $username;}else{echo" New Admin";}?></td><td align=right  valign="middle"><b>Check/uncheck all</b> <input type='checkbox' name='checkall' onclick='checkedAll();' >  <input type=submit  name='SBMT_REG' value='Update'>&nbsp;&nbsp;<input type="Reset" >&nbsp;&nbsp;</td></tr>
   <tr><td align=left valign=top colspan=2>
   <table border=0 cellpadding=4 cellspacing=1 width=100% align="center" class='table table-bordered'>
    <?
	$k=0;
    $rrrrr=mysqli_query($con,"select * from " . $prev. "adminmenu where parent_id='0' and status='Y' order by ord");
    while($ddd=@mysqli_fetch_array($rrrrr)):
		  echo'<tr ><td colspan="2"><h4>' .$ddd[name] .'</h4></td></tr>';
          $n1=mysqli_query($con,"select * from " . $prev. "adminmenu where parent_id= " .$ddd[id] . " and status='Y'");
		  while($nnn=@mysqli_fetch_array($n1)):
		   	  $chk="";
			  if($_REQUEST[type]=="S" || $_REQUEST['type']=="S" || in_array($nnn[id],$array_id))
			  { 
			     $chk="checked"; 
			  }
			 ?>
              <tr  ><td align="ledt" colspan="2"><input type=checkbox name='progid<?php echo $k?>' value="<?php echo $nnn[id]?>,<?php echo $nnn[parent_id]?>" <?php echo $chk?> ><?php echo $nnn[name]?></td></tr>
             <?
		     $k++;
		   endwhile;
     endwhile;
     ?>    
</table>
<input type=hidden name='tot' value=<?php echo $k?>>
</td></tr>
<tr ><td colspan=2 height=25 align=center >
<!--<input type=button   value='Cancel' onClick="javascript:window.location.href='admin.list.php'">-->
<input type=submit name="SBMT_REG" class='btn btn-ar btn-primary'  value='Submit'>
</td></tr>
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
