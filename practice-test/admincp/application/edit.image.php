<?
include('includes/access.php');
include('includes/header.php');

$extensions = array("jpeg","jpg","png");
$msg='';$errors=array();
if($_POST['Update'])
{
	
    foreach($_FILES['files']['tmp_name'] as $key => $tmp_name )
	{
	    $notok=0;
	    $file_name = $key.$_FILES['files']['name'][$key];
	    $file_size =$_FILES['files']['size'][$key];
	    $file_tmp =$_FILES['files']['tmp_name'][$key];
	    $file_type=$_FILES['files']['type'][$key];
	    
	    $file_ext=explode('.',$file_name);
		$file_ext=end($file_ext);  
		$file_ext=strtolower(@end(explode('.',$file_name)));  
		if(in_array($file_ext,$extensions ) === false)
		{
			$errors[]="extension not allowed";
			$notok++;
		}  
		if($file_size > 2097152)
		{
			$errors[]='File size must be less tham 2 MB';
			$notok++;
		}    
		if($notok==0):
		    $file_name=str_replace(" ","-",$file_name);
		    move_uploaded_file($file_tmp,"../uploads/".$file_name);
		    $r=mysqli_query($con,"insert into ".$prev."picture set  file=\"uploads/" . $file_name . "\",title='". $file_name ."',  type='". $_POST['type'] ."', user_id='".$_POST['user_id']."', file_type='p'");	    
		   
		endif;      
	}
    if($r):
		$msg="<span ><b>Update Successfull.</b></span>";
		header("location:image.list.php?user_id=" . $_POST['user_id']);
	else:
		$msg="<span red><b>Update Failure.</b></span>";
	endif;
}

if($_REQUEST['user_id'])
{
	$select_user_name = @mysqli_fetch_array(mysqli_query($con,"select * from ".$prev."member where user_id=".$_GET['user_id']));
}
$page_title="Add | Edit  Member Picture";
$breadcrum=array("Member List", "tender_member.list.php"),
    array($select_user_name['nickname'], ""),
    array("Add | Edit Member Picture", ""),
);

$btn="<a href='image.list.php?user_id=" . $_REQUEST['user_id'] . "' class='btn btn-success'>Add Picture</a> ";

require_once("includes/header_common.php");
 
 

?>
<p align=center><?=$msg;?></p>
<form method="post" action="<?=$_SERVER[PHP_SELF]?>" onSubmit="javascript: return user_valid(this);" enctype="multipart/form-data">
<?
	if($_REQUEST['id']){ ?><input type=hidden name="id" value="<?=$_REQUEST[id];?>"><? } 
	if($_REQUEST['user_id']){ ?><input type=hidden name="user_id" value="<?=$_REQUEST[user_id];?>"><? } ?>
<table width=100% border=0 align=center cellpadding=4 cellspacing=1 class='table table-bordered'>
 
 <tr  >
  <td valign=top width="20%" align="left">Picture</td>
  <td width="50%" align="left">
 
  <input name="files[]" type="file" size="40" multiple class='form-control'/></td></tr>

<tr ><td valign=top width="20%" align="left">Type </td>
<td width="50%" align="left">
<input type="radio" name='type' value="1"<?php if($d[type]=='1'){echo"checked";}?>   checked>
&nbsp;Public
<input type="radio" name='type' value="2"<?php if($d[type]=='2'){echo"checked";}?>   >
&nbsp;Private
<input type="radio" name='type' value="3"<?php if($d[type]=='3'){echo"checked";}?>   >&nbsp;Profile</td></tr>
<tr><td></td>
  <td ><input type="submit" name="Update" value="Update" class='btn btn-success' />
    <input name="button" type="button" class='btn btn-default' onClick="JavaScript:history.back();" value="Back" /></td>
</tr>
</table>
</form>

<br>
<? 
include('includes/footer.php'); 
?>
