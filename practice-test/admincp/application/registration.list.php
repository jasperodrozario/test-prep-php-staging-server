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
<div class="page-head bg-grey-100"><h1 class="page-title">Register Member Management</h1>
</div>
<!-- END PAGE HEADING -->
<div class="container-fluid">
 <div class="panel-body no-padding-top bg-white">
<h4 class="color-grey-700">Register Member</h4>
<div class="table-responsive">
<?
$pid=($_REQUEST['pid']=='')?0:$_REQUEST['pid'];
if($_REQUEST['del']):
  mysqli_query($con,"delete from " .$prev."user where user_id=" . $_REQUEST['user_id']);
  pageRedirect('registration.list.php?menuid=52&menupid=46');
  //exit();
endif;

if(isset($_REQUEST['status'])){
	   $update=mysqli_query($con,"UPDATE ".$prev."user SET status='".$_REQUEST['status']."' WHERE user_id=".$_REQUEST['user_id']);	
	}
?>
<form name="form1" method="post" action="registration.list.php?menuid=52&menupid=46">
<table width="100%" border=0>
<tr ><td ><input type="button" class="btn btn-success" onClick="javascritp:window.location.href='registration.entryform.php'" value="Add New Member"></td>
<td width="40%" align="right">
<select name="parama" size="1" class="form-control">
	<option value="user_id" <?php if($_REQUEST['parama']=="user_id"){echo" selected";}?>>ID</option>
	<option value="f_name" <?php if($_REQUEST['parama']=="f_name"){echo" selected";}?>>First Name</option>
	<option value="l_name" <?php if($_REQUEST['parama']=="l_name"){echo" selected";}?>>Last Name</option>
</select></td><td> == </td><td><input type="text" size="8" name="search"  value="<?php echo $_REQUEST['search']?>" class="form-control"></td>
<td align=right><input type="submit" class="btn btn-success" name="SBMT_SEARCH"  value="  Search  "></td></tr>
</table><br>
<table id="table-1" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="1" cellpadding="4">
	<thead>
	
		<tr>
		<td width="5%" height="25"><b>ID</b></td>
		<td  width="20%" height="25"><b>Email</b></td>
        <td  width="20%" align="left"><b>Name</b></td>
		<td  width="10%" align="left"><b>Phone No.</b></td>
		<td  width="10%" align="left"><b>Mobile</b></td>
		<td  width="10%" align="left"><b>City</b></td>
        <td  width="10%" align="center"><b>Zip</b></td>
		<td  width="5%" align="center"><b>Status</b></td>
		<td  width="10%" align="center"><b>Action</b></td>
		</tr>
	</thead>
	<tbody>
		<?
		$conds='';
		if(!$_REQUEST[limit]){$_REQUEST[limit]=1;}
		
		if($_REQUEST[SBMT_SEARCH] && $_REQUEST[parama] && $_REQUEST[search]):
   			$cond=" ".$_REQUEST[parama] . " ='" . $_REQUEST[search] . "'";
		endif;
		if($cond){
			$conds=' where '.$cond;
			
		}
		
		$r=mysqli_query($con,"select count(*) as total from " .$prev."user " . $conds);
		$total=@mysqli_result($r,0,"total");
		
		$r=mysqli_query($con,"select * from ".$prev."user ". $conds ." order by user_id desc limit " . ($_REQUEST['limit']-1)*10 . ",10");
	
		if($total=='0')
		{
   		?>
			<tr class='lnkred'><td colspan='9' align='center'>No record found.</td></tr>
		<?php
		}
		else
		{
		$j=0;
		while($d=@mysqli_fetch_array($r))
		{
			
    		if(!($j%2)){$class="even";}else{$class="odd";}
			if($d['registration_status']=="A"){$registration_status="Active";}else{$registration_status="<span class='lnkred'>In Active</span>";}
			
			?>
			<tr >
				<td align="center" height="25"><?php echo $d['user_id']?></td>
				<td align="left"><?php echo $d['email_id']?></td>
				<td align="left"><?php echo $d['f_name']." ".$d['l_name']?></td>
				<td align="left"><?php echo $d['contact_1']?></td>
				<td align="left"><?php echo $d['contact_2']?></td>
				<td align="left"><?php echo getCitynameById($d['city_id'])?></td>
                <td align="center"><?php echo getZipcodeById($d['zip_id'])?></td>
				<td align="center"  id="chgstatus1987">
					<?php if($d['status']=='Y'){?>
                     <a href="registration.list.php?user_id=<?php echo $d['user_id']?>&status=N">
                        <img src='images/icon_active.png' alt='Active' title='Active' style="cursor:pointer;"  />
                    </a>
                    <?php }else{?>
                    <a href="registration.list.php?user_id=<?php echo $d['user_id']?>&status=Y">
                   <img src='images/delete.png' alt='Inactive' title='Inactive' style="cursor:pointer;"  />
                    </a>
                    <?php }?>
				</td>

				<td align="center" >
					<span class="">
						<a href="registration.entryform.php?user_id=<?php echo $d['user_id']?>">
							<img width="16" height="16" title="Edit" alt="Edit" src="images/icon_edit.png">
						</a>
					</span>&nbsp;
					<span class="">
						<a href="<?php echo $_SERVER['PHP_SELF']?>?user_id=<?php echo $d['user_id']?>&del=1" class='lnk'  onclick="return confirm('Are you sure you want to delete this record?')">
						<img src="images/icon_delete.png" width="14" height="14" alt="Delete" title="Delete"  border="0" style="cursor:pointer;" />
						</a>
					</span>
					<span class="">
						<a href="view_customer_orders.php?user_id=<?php echo $d['user_id']?>&view=order" class='lnk'>
						<img src="images/order-list.png" width="20" height="20" alt="View Orders" title="View Orders"  border="0" style="cursor:pointer;" />
						</a>
					</span>
				</td>
			</tr>
			<?php
   			$j++;
		} 
		}  
		?>
	</tbody></table>
	<?php 
	if($total>10):?>
	<?php echo paging($total,10,"$param")?>
    <?php endif;?>



</form>
<script cat_id="text/javascript">
//<![CDATA[
function addClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var l = p.length;
	for (var i = 0; i < l; i++) {
		if (p[i] == sClassName)
			return;
	}
	p[p.length] = sClassName;
	el.className = p.join(" ");

}

function removeClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var np = [];
	var l = p.length;
	var j = 0;
	for (var i = 0; i < l; i++) {
		if (p[i] != sClassName)
			np[j++] = p[i];
	}
	el.className = np.join(" ");
}
var st = new SortableTable(document.getElementById("table-1"),
	["Number","None","String","String","Number","date","date","String","None"]);
	// restore the class names
st.onsort = function () {
	var rows = st.tBody.rows;
	var l = rows.length;
	for (var i = 0; i < l; i++) {
		removeClassName(rows[i], i % 2 ? "odd" : "even");
		addClassName(rows[i], i % 2 ? "even" : "odd");
	}
};
//]]>
</script>

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