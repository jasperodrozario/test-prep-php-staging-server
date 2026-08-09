<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
 
<tr  >
  <td align="left" width=20%>Name</td>
  <td><input type="text" class='form-control' name='name'   value="<?php echo $db_value['name']?>" ></td></tr>
<tr  >
  <td align="left">Icon</td>
  <td>
  <?if($db_value['icon'])
  {
     echo"<img src='".$configs['vpath'].$db_value['icon']."'><br>";	
  }
  ?>
  <input type="file" class='form-control' name='icon'></td></tr>
<tr  ><td>Catgory</td><td>
<?

foreach($categories as $c)
{
	if(in_array($c['cat_id'],$clist))
	{
		echo "<input type='checkbox' name='cat_ids[]' value='". $c['cat_id'] . "' checked> " . $c['cat_name'] . "<br>"; 
	}
	else
	{
		echo "<input type='checkbox' name='cat_ids[]' value='". $c['cat_id'] . "'> " . $c['cat_name'] . "<br>"; 
	
	}
		
}		
?>	
</td></tr>
<tr  ><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?>>Active <input type='radio' name=status value='N' <?if($db_value['status']=="N"){echo"checked";}?>>Hidden</td></tr>
<tr  >
  <td align="left">Display Order</td>
  <td><input type="number" class='form-control' name='ord'   value="<?php echo $db_value['ord']?>" ></td></tr>

<tr><td></td><td ><input type=submit  name='SBMT' value='Save' class='btn btn-success'></td></tr>
</table>
</form>