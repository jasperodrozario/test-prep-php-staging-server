<table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4"  >

<thead>
<td align=left><b>Pic</b></td>
<td align=left><b>Name</b></td>
 

<td align=left><b>Email</b></td><td align=left><b>Department</b></td>
<td align="center"><b>Status</b></td>
<td  align=center><b>Action</b></td>
</tr>
</thead><tbody>
<?

 
$j=0;$k=0;

foreach($modarators as $d)
{
	

	 
    if($d['status']=="Y"){$status="Active";}elseif($d['status']=="N"){$status="<a red><b>Ex-modarator</b></a>";}else{$status="<a>Suspended</a>";}
    if($d['photo']){
    	
    	
    	$user_image = '<img src="'. $configs['vpath'].'image.php?image='.$configs['vpath'].$d['photo'].'&width=60&height=60&cropratio=2:1">';
    }
    else{

    	$user_image = '<img src="'. $configs['vpath'].'admincp/assets/img/user.png"/ style="width:40px; height:40px;">';

    }
	echo"<tr>
	<td align=left>".$user_image."</td>
	<td align=left><a   href='modarator.regform.php?admin_id=" . $d['admin_id'] . "'>" . ucwords($d['username']). "</a></td>
 

	<td align=left>" . ucwords($d['fname']). " " . ucwords($d['lname']) . "<br><a href='mailto:" . $d['email'] . "' >" . $d['email'] . "</a></td>
   <td align=left>". $d['department'] ."</td><td align=center>". $d['status'] ."</td>
	<td align='center'><i class='fa fa-pencil-square-o'> <a   href='modarator.regform.php?admin_id=" . $d['admin_id'] . "' >Edit</a> | <a   href='grant.php?admin_id=" . $d['admin_id'] . "' >Grant</a> | <a   href='modarator.regform.php?suspend_admin_id=" . $d['admin_id'] . "' >Suspend</a> | <i class='fa fa-times'></i> <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='" . $_SERVER['PHP_SELF'] . "?admin_id=" . $d['admin_id'] . "&del=1';}\" >Delete</a> ";
	
	echo"</td></tr>";
	$j++;
}

?>
</tbody>
</table>