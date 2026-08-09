<br>
<table id="table-content" width="100%" class="table table-bordered" border="0" cellspacing="1" cellpadding="4">
<thead>
<tr >
<td height="25" width="5%" align="left"><b>Id</b></td>
<td height="25" width="65%" align="left"><b>Mail Type</b></td>
 
<td height="25" width="30%" align="center"><b>Action</b></td>
</tr>
</thead>
<tbody>
<?php

foreach($mailsetup as $main_fetch) 
{
 
	$j++;
	if($main_fetch['status'] == "Y")
		$status = "Active";
	else
		$status = "Offline";

	?>
	<tr  >
	<td align="left"><?php echo stripslashes($main_fetch['id']);?></td>
	<td align="left"><?php echo stripslashes($main_fetch['mail_type']);?></td>

	<td align="center"><i class='fa fa-pencil-square-o'></i> <a href="mailsetup_entry?id=<?php echo $main_fetch['id'];?>">Edit</a> | <i class='fa fa-pencil-square-o'></i> <a href="sending-emails?id=<?php echo $main_fetch['id'];?>">Send Email</a> | <i class='fa fa-times'></i>  <?="<a  href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location.href='" . BASENAME($_SERVER['SCRIPT_NAME'],".php") . "?del_id=" . $main_fetch['id'] . "';}\">Delete</a>";?></td>
	</tr>
<?
}
?>   
</tbody>
</table> 