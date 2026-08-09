<? 
include('includes/access.php');
include('includes/header.php');


if($_GET['del']==1):
    $r=mysqli_query($con,"delete from " . $prev . "admin where admin_id=". $_GET[admin_id]);
	 mysqli_query($con,"delete from " . $prev . "admin where admin_id=". $_GET[admin_id]);
endif;
if(!$_REQUEST[limit]){$_REQUEST[limit]=1;}
 

$page_title="Modarator List";
$links=array(
    array("Modarator List", "") 
);
$btn="<input type='button' value='Add New' class='btn btn-success' onclick=\"javascript:window.location.href='modarator.regform.php'\">";

require_once("includes/header_bottom.php"); 
if($msg)
{
   echo $msg;	
} 
?>	 
 

<table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4"  >
<thead>

<td align=left><b>Name</b></td>
<td align=left><b>Email</b></td><td align=left><b>Department</b></td>
<td align="center"><b>Status</b></td>
<td  align=center><b>Action</b></td>
</tr>
</thead><tbody>
<?

 
$r=mysqli_query($con,"select * from " . $prev . "admin  where type='S'");
 
 
$j=0;$k=0;
while($d=@mysqli_fetch_array($r)):

    if($d['status']=="Y"){$status="Active";}elseif($d['status']=="N"){$status="<a red><b>Ex-modarator</b></a>";}else{$status="<a>Suspended</a>";}

	echo"<tr>

	<td align=left><a   href='modarator.regform.php?admin_id=" . $d['admin_id'] . "'>" . ucwords($d['username']). "</a></td>
	<td align=left>" . ucwords($d['fname']). " " . ucwords($d['lname']) . "<br><a href='mailto:" . $d['email'] . "' >" . $d['email'] . "</a></td>
   <td align=left>". $d['department'] ."</td><td align=center>". $d['status'] ."</td>
	<td align='center'><a   href='modarator.regform.php?admin_id=" . $d['admin_id'] . "' class='btn btn-info btn-sm'>Edit</a>  <a   href='grant.php?admin_id=" . $d['admin_id'] . "' class='btn btn-primary btn-sm'>Grant</a>  <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='" . $_SERVER['PHP_SELF'] . "?admin_id=" . $d['admin_id'] . "&del=1';}\" class='btn btn-danger btn-sm'>Delete</a> ";
	
	echo"</td></tr>";
	$j++;
endwhile;
?>
</tbody>
</table>
 
<?
include('includes/footer.php');

?>
