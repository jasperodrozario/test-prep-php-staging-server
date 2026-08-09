<?php
include("includes/access.php");
include("includes/header.php");
if($_GET[del]):
   mysqli_query($con,"delete from " . $prev . "payment_sec where id=" . $_GET[id]);
   @unlink("../" . $_GET[file1]);
endif;

if($_REQUEST['del2']==2){
	 mysqli_query($con,"delete from " . $prev . "price_sec where price_id=" . $_GET[id]);
	}
if(!$_REQUEST[lang]){$_REQUEST[lang]="E";}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>?lang=<?php echo $_REQUEST[lang]?>" enctype="multipart/form-data">

<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" bgcolor=<?php echo $light?> >
	<tr bgcolor=<?php echo $light?>>
		<td width="54%" height=25 class=header_tr>&nbsp;&nbsp;<a href="payment_management.php" class="header_tr">Payment Management </a>&nbsp;&nbsp;&nbsp;
		<input type=button value='Add New' class=lnk onClick="javascript:window.location.href='payment_maneg.editor.php?id=0'"></td>
		<td bgcolor="<?php echo $light?>" width=46% align=right></td>
	</tr>
</table>

<table id="table-1" width="100%" class="table table-bordered" border="0" align="center" cellspacing="1" cellpadding="4"  >
<thead>
	<tr bgcolor=<?php echo $graywhite2?>>
		<td width=7%><b>ID</b></td>
		<td width="55%"><b>Days</b></td>
		<td width="19%" align=center><b>Position</b></td>
		<td width="19%"  align=center><b>Action</b></td>
	</tr>
</thead>

<tbody>
<?
if(!$_REQUEST[limit]){$_REQUEST[limit]=1;}
if($_REQUEST[param] && $_REQUEST[search]):
   $cond=$_REQUEST[param]  . " rlike '" . $_REQUEST[search] . "'";
endif;
if($cond){$cond2=" where  " . $cond;}
$r= mysqli_query($con,"select count(*) as total from " . $prev . "payment_sec " . $cond2);
$total=mysqli_result($r,0,"total");
if(!$total):
   echo"<tr class='lnkred'><td colspan='7' align='center'>No Record Found.</td></tr>";
endif;
$r= mysqli_query($con,"select * from " . $prev . "payment_sec " . $cond2 . " limit " . ($_REQUEST['limit']-1)*20 . ",20");
$j=0;$k=0;
while($d=mysqli_fetch_array($r)):
	if(!($j%2)){$class="even";}else{$class="odd";}
    if($d[status]=="Y"){$status="Active";}else{$status="Hidden";}
	?>
    <tr bgcolor=#ffffff class="even<?php //echo  $class;?>">
		<td ><a class=lnk  href='page.editor.php?id=<?php echo  $d[id] ;?>'><?php echo $d[id];?></a></td>
		<td ><?php echo $d[day].'&nbsp;'.$d[day_2];?></td>
		<td align=center><?php echo $d[position];?></td>
		<?php
		echo"<td align=center><a class=lnk  href='price_maneg.editor.php?id=0&day_id=".$d['id']."'><u>Add Price</u></a> | <a class=lnk  href='payment_maneg.editor.php?id=" . $d[id] . "'><u>Edit</u></a> | ";
		echo"<a class=lnk  href=\"javascript:if(confirm('Are you sure you want to delete it?')){window.location='" . $_SERVER['PHP_SELF'] . "?id=" . $d[id] . "&del=1';}\"><u>Delete</u></a> </td>";
		?>
	</tr>
	
	<?php
	$select_currency="select ".$prev."price_sec.*,".$prev."currency.* from ".$prev."price_sec,".$prev."currency where ".$prev."price_sec.day_id=".$d['id']." and ".$prev."price_sec.currency_id=".$prev."currency.id";
	$rec_currency= mysqli_query($select_currency);
	$num_currency= mysqli_num_rows($rec_currency);
	if($num_currency > 0)
		{
			while($row_currency= mysqli_fetch_array($rec_currency))
				{
					?>
					<tr>
						<td></td>
						<td colspan="2">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr>
									<td align="left"  style="width:40px;">&nbsp;&nbsp;<img src="images/direc.png" /></td>
									<td align="left" style="width:50px;"><?php  echo $row_currency['currency_code'].'&nbsp;'.$row_currency['price'];?></td>
									<td><?php  echo $row_currency['currency_name'];?></td>
									<td  style="color:#000000;">|</td>
									<td   align="left" style="width:140px;"><?php  echo $row_currency['equals'];?></td>
									<td  style="color:#000000;">|</td>
									<td  align="left" style="width:140px;"><?php  echo $row_currency['monthly'].'&nbsp;'.$row_currency['currency_name'] .'&nbsp;'.$row_currency['monthly_day'];?></td>
									<td>
									<?php
									echo"<td align=center><a class=lnk  href='price_maneg.editor.php?id=" . $row_currency['price_id'] . "'><u>Edit</u></a> | ";
									echo"<a class=lnk  href=\"javascript:if(confirm('Are you sure you want to delete it?')){window.location='" . $_SERVER['PHP_SELF'] . "?id=" . $row_currency['price_id'] . "&del2=2';}\"><u>Delete</u></a> </td>";
									?>
									</td>
									<td style="width:100px;"></td>
								</tr>
							</table>
						</td>
						<td></td>
					</tr>
					<?php
					}
				} ?>
			<?php
			$j++;
		endwhile;
		$parama="&search=" . $_REQUEST[search] . "&param=" . $_REQUEST[param] . "&lang=" . $_REQUEST[lang]; ?>
	</tbody>
</table>

<? if($total>20):?>
<table  width="98%"  border="0" align="center" HEIGHT=25 cellspacing="0" cellpadding="4" >
  <tr bgcolor=<?php echo $light?>>
    <td align=center ><?php echo paging($total,20);?></td>
  </tr>
</table>
<? endif;?>
</form>


<script type="text/javascript">

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
	["Number","String","String","String","String","String","String","String","Number","String","None"]);
	// restore the class names
st.onsort = function () {
	var rows = st.tBody.rows;
	var l = rows.length;
	for (var i = 0; i < l; i++) {
		removeClassName(rows[i], i % 2 ? "odd" : "even");
		addClassName(rows[i], i % 2 ? "even" : "odd");
	}
};
</script>

<?
include("includes/footer.php");
?>