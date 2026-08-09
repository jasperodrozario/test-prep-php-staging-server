
		
				
		<!-- 		<div class="panel panel-primary" data-collapsed="0">

<div class="panel-body"> -->

<form method="post" action="<?php  echo basename($_SERVER['PHP_SELF'],".php")?>" enctype="multipart/form-data">

<table width="100%" border="0" align="center" cellspacing="4" cellpadding="5" class='table'>
<tr ><td height="25" class='vertical-middle'><!-- <input type="button" value='Add New' class='btn btn-success'  onclick="javascript:window.location.href='feedback.entryform?fd_id=0'"> -->
</td><td  width=100 class='vertical-middle'><select name="param" size="1" class='form-control' style='width:150px'>
<option value='fd_id' <?php  if($param=="fd_id"){echo" selected";}?>>ID</option>
<option value='fd_by'   <?php  if($param=="fd_by"){echo" selected";}?>>Feedback By</option>
</select></td><td class='vertical-middle'><input type="text" size="28" name="search" class='form-control'></td>
<td   class='vertical-middle'><input type="submit" class="btn btn-default" name='SBMT_SEARCH'  value='  Search  '></td></tr></table>
 
</form>

<table   width="100%" border="0" align="center" cellspacing="1" cellpadding="4"  class="table table-bordered table-striped">
<thead>
<tr height="30">
    <th width="5%" height=25><b>ID</b></th>
   <!-- <th width="5%" height=25><b>Picture</b></th> -->
	<th width="10%"><b>Feedback By</b></th>
	<th><b>Description</b></th>
	<th width="12%"><b>Date</b></th>
	<th align=center style='text-align:center'><b>Status</b></th>
	<th align=center style='text-align:center'><b>Action</b></th>
</tr>
</thead>
<tbody>

<?php 
$cond=array();
if(!$_REQUEST['limit']){$_REQUEST['limit']=1;}

if($_POST['param'] && $_POST['search']):

   $cond[]=$_POST['param']  . " rlike '" . $_POST['search'] . "'";

endif;

if(count($cond)){$cond2=" where  " . implode(" and ",$cond);}

$r=mysqli_query($con,"select * from " . $prev . "feedback " . $cond2);

$total=@mysqli_num_rows($r);


/*$r=mysqli_query($con,"select * from " . $prev . "enquiry " . $cond2);
$total=@mysqli_num_rows($r);
$r=mysqli_query($con,"select * from " . $prev . "enquiry" . $cond2 . " limit  " . $offset . "," . $num_records_per_page);*/


if(!$total):
   echo"<tr class='lnkred'><td colspan='7' align='center'>No Record Found.</td></tr>";
endif;

$r=mysqli_query($con,"select * from " . $prev . "feedback " . $cond2 . " limit " . ($_REQUEST['limit']-1)*50 . ",50");
//echo "select * from " . $prev . "feedback " . $cond2 . " limit " . ($_REQUEST['limit']-1)*50 . ",50";
$j=0;$k=0;
while($d=@mysqli_fetch_array($r)):
	if(!($j%2)){$class="odd";}else{$class="even";}
    if($d['admin_chk']=="Y"){$status="Active";}else{$status="Offline";}
	?>
    <tr  >
		<td><?php  echo $d['fd_id']?></td>
		<td ><a   href="feedback.entryform?fd_id="<?php  echo $d['fd_id']?>><?php  echo $d['fd_by']?> </a></td>
		<td ><a   href="feedback.entryform?fd_id=<?php  echo $d['fd_id']?>"><?php  echo substr(strip_tags($d['fd_desc']),0,60)?></a></td>
		<td><?php  echo showdate($d['fd_dt'])?></td>
		<td align=center><?php  echo $status?></td><td align=center>
			<a   href="feedback.entryform?fd_id=<?php  echo $d['fd_id']?>">Edit</a> |
			<?php 
			echo"<a   href=\"javascript:if(confirm('Are you sure you want to delete it?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?fd_id=" . $d['fd_id'] . "&del=1';}\">Delete</a> ";
			?>
		</td>
	</tr>

	<?php 
	$j++;
endwhile;

$parama="&search=" . $_REQUEST[search] . "&param=" . $_REQUEST[param] . "&lang=" . $_REQUEST[lang];
?>

</tbody>

</table>

<?php  if($total>50):?>

<table  width="100%"  border="0" align="center" HEIGHT=25 cellspacing="0" cellpadding="4" >
  <tr  ><td  align=center ><?php  echo paging($total,50);?></td></tr>
</table>
<?php   endif;?>
</form>
<!-- </div>
</div> -->

