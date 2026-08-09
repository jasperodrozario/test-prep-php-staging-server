<form method='post' action="<?=basename($_SERVER['PHP_SELF'],".php");?>">
 
<div class='row'><div class='col-md-2'>
<select name='status' class='form-control' onchange='window.location=this.value'>
 <option value=''>Any</option>
 <option value='Open'>Open</option>
 <option value='Closed'>Closed</option> 
</select></div><div class='col-md-2'><input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'></div>


</div>
</form>
<br>		 
<form method="post" action="<?=basename($_SERVER['PHP_SELF'],".php");?>">
 
<table id="table-1" width="100%"  border="0" align="center" cellspacing="1" cellpadding="4" class='table table-stripe table-bordered'>
<thead>
<tr ><td width=7% height=22><b>ID</b></td><td><b>Date</b></td><td width=50%><b>Error</b></td><td><b>Status</b></td><td  align=center><b>Action</b></td></tr>
</thead><tbody>
<?
if($total)
{

 foreach($db_list as $d) 
 {
  
	if($d['status']=="Open"){$change="Closed";}else{$change="Open";}
	
	echo"<tr  ><td >" . $d['id'] . "</td><td>"  . showdate($d['date']). "</td><td>"  . html_entity_decode($d['error'])  . "</td><td>"  . $d['status']. "</td><td align=center><a   href='".basename($_SERVER['PHP_SELF'],".php") ."?id=" . $d['id'] . "&status=" . $change."'>Change Status</a> | <a   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?del_id=" . $d['id'] . "';}\">Delete</a></td></tr>";
	
 }
	
}
else
{
	echo"<font color=red>No Record found.</font>";
}
?>
</tbody>
</table>
</form>

<?if($total>20){echo"<ul class='pagination'>" . paging($total,"","status=".$param). "</ul>";}?>


 


<!-- BEGIN FOOTER -->
<?include("includes/footer.php");?>