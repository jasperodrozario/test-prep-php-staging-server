<?php
include("includes/access.php");
include("includes/header.php");

$self_page_name = basename($_SERVER['PHP_SELF']);
$main_path = $vpath . 'admin/' . $self_page_name;

if($_GET[del]):
   mysqli_query($con,"delete from " . $prev . "picture where id=" . $_REQUEST[id]);
endif;
if($_GET[user_id]){
	$select_user_name = @mysqli_fetch_array(mysqli_query($con,"select * from ".$prev."member where user_id=".$_GET[user_id]));
}
?>
<script type="text/javascript">
function delConfirm(user_id) {
	var confirmVal = confirm('Are you sure you wish to delete?');
	if( confirmVal ) {
		var full_path = '<?=$main_path?>?user_id=' + user_id + '&del=1';
		window.location = full_path;
	}
}
</script>
<?php 
$page_title="Add | Edit  Member Picture";
$breadcrum=array(
    array("Member List", "tender_member.list.php"),
     array($select_user_name['nickname'], ""),
     array("Add | Edit Member Picture", ""),
);
$btn="<a href='edit.image.php?user_id=" . $_REQUEST['user_id'] . "' class='btn btn-success'>Add Picture</a>";
require_once("includes/header_common.php");
?>

<form method='post' action="<?=$_SERVER['PHP_SELF']?>">
 
<table  width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

<?php
if(!$_REQUEST[limit]){$_REQUEST[limit]=1;}
$r=mysqli_query($con,"select * from " . $prev . "picture where user_id=".$_REQUEST['user_id']);
$total=@mysqli_num_rows($r);
$r=mysqli_query($con,"select * from  " . $prev . "picture where user_id=".$_REQUEST['user_id']." order by user_id");
$j=0; 
echo"<tr >";
while($d=@mysqli_fetch_array($r)):
	if(!($j%2)){$class="even";}else{$class="odd";}
	$type = $d[type];
	if($d[type]=='1'){
		$type = 'Public';
	}
	elseif($d[type]=='2'){
		$type = 'Private';
	}	
	elseif($d[type]=='3'){
		$type = 'Profile';
	}

  
   echo"<td valign=top ><img src='image.php?image=" .$vpath. $d['file'] . "&width=200&height=250' border=0></a><br><br>Id:" . $d['id'] . " | 
   Type : " . $type . " |
   <a title='Delete this Image'   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location='" . $_SERVER['PHP_SELF'] . "?user_id=".$d['user_id']."&id=" . $d[id] . "&del=1';}\">Delete</a></td>\n";
   $j++;
   if(!($j%4)){echo"</tr><tr>";}
endwhile;

?>
</table>
<?
if($total>20):?>
<table width="100%" border="0" align="center" HEIGHT="25" cellspacing="0" cellpadding="4" >
	<tr ><td align="center" class="lnk"><?=paging($total,20,"$parama","btn")?></td></tr>
</table>
<? endif;?></form>

<?php
include("includes/footer.php");
?>