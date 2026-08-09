<?php
include("includes/access.php");
include("includes/header.php");

$self_page_name = basename($_SERVER['PHP_SELF']);
$main_path = $vpath . 'admin/' . $self_page_name;

if($_GET[del]):
   mysqli_query($con,"delete from " . $prev . "video where id=" . $_REQUEST[id]);
endif;
if($_GET[user_id]){
	$select_user_name = mysqli_fetch_array( mysqli_query($con,"select * from ".$prev."member where user_id=".$_GET[user_id]));
}

?>
<script type="text/javascript">
function delConfirm(user_id) {
	var confirmVal = confirm('Are you sure you wish to delete?');
	if( confirmVal ) {
		var full_path = '<?php echo $main_path?>?user_id=' + user_id + '&del=1';
		window.location = full_path;
	}
}
</script>
<?php 
$page_title="Add | Edit  Video";
$breadcrum=array(
    array("Escort List", "escortcol_member.list.php"),
    array($select_user_name['first_name'] . "'s Video", "video.list.php?user_id=" . $_GET['user_id']),
  
    array("Add | Edit Video", ""),
);

$btn="<a href='image.list.php?user_id=" . $_REQUEST['user_id'] . "' class='btn btn-success'>Add Picture</a>   <a class='btn btn-success' href='edit.video.php?user_id=" . $_REQUEST['user_id'] . "'>Add Video</a>";

require_once("includes/header_common.php");
?>
<form method=post action="<?php echo $_SERVER[PHP_SELF]?>" enctype="multipart/form-data">

<table id="table-1" width="100%"  border="0" align="center" cellspacing="1" cellpadding="4"  class='table table-bordered'>
<thead>
<tr>
<td ><b>Video</b></td>
<td ><b>Video Title</b></td>
<td><strong>Action</strong></td></thead><tbody>
<?php
if(!$_REQUEST[limit]){$_REQUEST[limit]=1;}
$r= mysqli_query($con,"select * from " . $prev . "video where user_id=".$_REQUEST[user_id]);
$total=mysqli_num_rows($r);
$r= mysqli_query($con,"select * from  " . $prev . "video where user_id=".$_REQUEST['user_id']);
$j=0;
while($d=mysqli_fetch_array($r)):
	if(!($j%2)){$class="even";}else{$class="odd";}
   echo"<tr >";?>
   <td onclick="javascript:window.open('play_video2.php?id=<?php echo $d[id]?>','puskar','width=440, height=400, scrollbar=no')"><img src='../images/play.jpg' height='50' width='50' border='0' style="cursor:pointer;"></td>
<?php
   echo "<td>" . $d['video_title'] . "</td>
   <td><a href='edit.video.php?user_id=".$d['user_id']."&id=".$d[id]."' >Edit</a> | <a title='Delete this Image'   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location='" . $_SERVER['PHP_SELF'] . "?user_id=".$d[user_id]."&id=" . $d[id] . "&del=1';}\">Delete</a></td></tr>\n";
   $j++;
endwhile;
if($total==0){?>
<tr><td colspan="3"   align="center">No Video Found</td></tr>

<?php
}
?>
</tbody>
</table>
<?
if($total>20):?>
<table width="100%" border="0" align="center" HEIGHT="25" cellspacing="0" cellpadding="4" >
	<tr ><td align="center" ><?php echo paging($total,20,$parama,"btn")?></td></tr>
</table>
<? endif;?></form>
 
<?php
include("includes/footer.php");
?>