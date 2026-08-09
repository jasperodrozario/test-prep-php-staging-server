
<?
switch($_GET['action'])
{
   case "add-guest":

   ?>
      <div id='response'></div>
		<form method="post" id='lesson_form'    enctype="multipart/form-data" >
      <input type='hidden' name='user_id' value='<?=$_GET['user_id'];?>'> 
      <input type='hidden' name='action' value='add-guest'> 
		<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
		<tr><td align="left">Email *</td><td><input type="text"  name="email"  class='form-control' value="<?php echo $db_value['email'];?>" required></td></tr>
		<?
		if(!$_GET['user_id'])
		{?>
		<tr><td align="left">Password *</td><td><input type="text"  name="password"  class='form-control' value="<?php echo $db_value['password'];?>" required></td></tr>
		<?}?>
		<tr><td align="left">Name *</td><td><input type="text"  name="name"  class='form-control' value="<?php echo $db_value['name'];?>" required></td></tr>
		<tr><td align="left">Mobile *</td><td><input type="text"  name="mobile"  class='form-control' value="<?php echo $db_value['mobile'];?>" required></td></tr>
		<tr><td align="left">Referral Code *</td><td><input type="text"  name="code"  class='form-control' value="<?php echo $db_value['code'];?>" required></td></tr>
		<tr  ><td>Status *</td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?> required>Active <input type='radio' name="status" value='N' <?if($db_value['status']=="N"){echo"checked";}?> required>Hidden</td></tr>
		<tr><td></td><td ><input type='submit'  name='submit' value='Save' class='btn btn-success'> </td></tr>
		</table>
		</form>
	<?
	break;
	default:
    ?>

	<div class='clearfix'><br></div>
	<table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
	<thead>
	<tr >

	<td width="5%" align="left"><b>Id</b></td>
	<td width="20%" align="left"><b>Name</b></td>

	<td width="12%" align="center"><b>Reg. Date</b></td>
	<td width="12%" ><b>Ref. Code</b></td>

	<td width="5%" align="center"><b>Status</b></td>  
	<td width="20%" align="center"><b>Action</b></td>
	</tr>
	</thead>
	<tbody>
		<?php
		foreach($db_list as $d)
	    {
	       
	        ?>
			<tr>
				<td align="left"><?=$d['user_id'];?></td>
	            <td><?php echo ucwords($d['name']);?><br>
	             <?php echo $d['email'];?><br>
	             Mobile : <?php echo $d['mobile'];?>
	            </td>
				<td align=center><?php echo showdate($d['reg_date']);?></td>
				<td ><?php echo $d['code'];?></td>
				<td align="center"><?php echo $d['status'];?></td>
				<td align="center">
					<a  href="guest-list?action=add-guest&user_id=<?php echo $d['user_id'];?>">Edit</a> | <a  href="guest-list?action=referral-member&user=<?php echo $d['user_id'];?>">Referral Member</a> 
					 |&nbsp; <a href='javascript://' onClick="javascript:if(confirm('Are you deleting')){window.location='guest-list?delete_id=<?=$d['user_id'];?>'};">Delete</a>
				
				
				</td></tr>
		   <?php
	    }
	 
		?> 
	</tbody>
	</table>  
	<?if($total>20){echo"<ul class='pagination'>" . paging($total) . "</ul>";}?>
	<?

}
?>