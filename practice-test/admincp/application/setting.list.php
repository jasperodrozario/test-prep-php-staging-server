<?php
include("includes/access.php");
include("includes/header.php");
if($_REQUEST['del']):
  mysqli_query($con,"delete from " .TABLE_SETTINGS." where config_id=" . $_REQUEST['config_id']);
  pageRedirect('setting.list.php');
  exit();
endif;
?>
<div class="wrapper">
		<!-- BEGIN LEFTSIDE -->
        <?php include("includes/left.php");?>
		<!-- BEGIN RIGHTSIDE -->
        <div class="rightside bg-grey-100">
		<!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
			<h1 class="page-title">Delivery Boy</h1>
		</div>
		<!-- END PAGE HEADING -->
        <div class="container-fluid">
         <div class="panel-body no-padding-top bg-white">
		<h4 class="color-grey-700">Delivery Boy List</h4>
		<div class="table-responsive">
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
<table width="100%" border="0" align="center" cellspacing="0" bgcolor="silver" cellpadding="4" >
	<tr  >
		<td class="header">Settings List 
	    <input type="button" class="btn btn-success" onClick="javascritp:window.location.href='setting.entryform.php?config_id=0&cat_id=<?php echo $cat_id?>'" value="Add New Settings">		</td><td width="40%" align="right">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr >
					<td>
					<select name="parama" size="1" >
						<option value="config_id" <?php if($_REQUEST['parama']=="config_id"){echo" selected";}?>>ID</option>
						<option value="config_variable" <?php if($_REQUEST['parama']=="config_variable"){echo" selected";}?>>Variable</option>
						<option value="config_value" <?php if($_REQUEST['parama']=="config_value"){echo" selected";}?>>Value</option>
					  </select>					</td>
					<td> == <input type="text" size="8" name="search"  value="<?php echo $_REQUEST[search]?>" > &nbsp;</td>
					<td><input type="submit" class="btn btn-success" name="SBMT_SEARCH"  value="  Search  "></td>
				</tr>
			</table>		</td>
	</tr>
</table>
<table id="table-1" width="100%" class="table table-bordered" border="0" align="center" cellspacing="1" cellpadding="4"  >
	<thead>
	
		<tr>
		<td width="6" height="25"><b>ID</b></td>
		<td   height="25"><b>Variable</b></td>
		<td width="16" ><b>Value</b></td>
		<td  ><b>Description</b></td>
		<td  width="16%" ><b>Key</b></td>
		<td  width="16%" align="center"><b>Action</b></td>
		</tr>
	</thead>
	<tbody>
		<?
		if(!$_REQUEST[limit]){$_REQUEST[limit]=1;}
		
		if($_REQUEST[SBMT_SEARCH] && $_REQUEST[parama] && $_REQUEST[search]):
   			$cond=" and ".$_REQUEST[parama] . " ='" . $_REQUEST[search] . "'";
		endif;
		
		$r=mysqli_query($con,"select count(*) as total from " .TABLE_SETTINGS." where 1 " . $cond);
		$total=@mysqli_result($r,0,"total");
		
		
		$r=mysqli_query($con,"select * from ".TABLE_SETTINGS." where 1 ". $cond ." order by config_id desc limit " . ($_REQUEST['limit']-1)*10 . ",10");
	
		if(!$total):
   			?>
				<tr class='lnkred'><td colspan='7' align='center'>No record found.</td></tr>
			<?
		endif;
		$j=0;
		while($d=@mysqli_fetch_array($r)):
			
    		if(!($j%2)){$class="even";}else{$class="odd";}
			
			
			?>
			<tr  bgcolor='#ffffff' class="<?php echo $class?>">
				<td width="6">
				<a href='setting.entryform.php?config_id=<?php echo $d['config_id']?>' class='lnk'><?php echo $d['config_id']?></a>
				</td>
				<td ><?php echo $d['config_variable']?></td>
				<td width="16%"><?php echo $d['config_value']?></td>
				<td width="16%"><?php echo $d['description']?></td>
				<td width="16%"><?php echo $d['config_key']?></td>
				
				<td width="16%" align=center><a href='setting.entryform.php?config_id=<?php echo $d['config_id']?>' class='lnk'><u>Edit</u></a> |
<a href="<?php echo $_SERVER['PHP_SELF']?>?config_id=<?php echo $d['config_id']?>&del=1" class='lnk'  onclick="return confirm('Are you sure you want to delete?')"><u>Delete</u></a></td>
			</tr>
			<?
    		
			
		
   			$j++;
		endwhile;   
		?>
	</tbody>
	<?php if($total>10):?>
	<tr><td align="center"  colspan=9 bgcolor=<?php echo $lightgray?> height=28><?php echo paging($total,10,"$param","lnk")?></td></tr>
<? endif;?>
</table>
<?
if($_REQUEST[cat_id]):
	$param="&cat_id=" . $_REQUEST[cat_id];
elseif($_REQUEST[cat_id]):
	$param="&cat_id=" . $_REQUEST[cat_id];
else:
	$param="";
endif;
?>

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