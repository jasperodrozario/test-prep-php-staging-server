<?
include("includes/access.php");
include("includes/header.php");
?>


<? $msg='';
$success=false;
if($_POST['uploadVid'])
{
	if($_FILES['video']['name'])
	{
		$path_info=pathinfo($_FILES['video']['name']);
		if($path_info[extension]=='wmv' || $path_info[extension]=='avi' || $path_info[extension]=='mpeg' || $path_info[extension]=='mpg')
		{
			$name=time().".".$path_info[extension];
			$check=copy($_FILES['video']['tmp_name'],"../video/".$name);
			
			if($check)
			{
				$res= mysqli_query($con,"update " . $prev."video set user_id=".$_REQUEST[user_id].",video='".$name."',video_title='".$_REQUEST[title]."' where id=".$_REQUEST[id]);
				//print("update ed_video set user_id=".$_REQUEST[user_id].",video='".$name."',video_title='".$_REQUEST[title]."' where id=".$_REQUEST[id]);
			}
			if($res)
			{
				$msg="Video Uploaded Successfully.";
				$success=true;
			}
			else
			{
				$msg="Video Not Uploaded.";
			}
		}
		else
		{
			$msg="Extension not supported.";
		}
	}
	else
	{
		$msg="please select a video";
	}
}
 
$page_title="Add    Escort Videos";
$breadcrum=array(
    array("Escort List", "escortcol_member.list.php"),
    array($select_user_name['first_name'] . "'s Video", "video.php?user_id=" . $_GET['user_id']),
    array("Add Escorts Video", ""),
);

$btn="<a href='image.list.php?user_id=" . $_REQUEST['user_id'] . "' class='btn btn-success'>Add Picture</a>   <a class='btn btn-success' href='video.list.php?user_id=" . $_POST['user_id'] . "'>Add Video</a>";

require_once("includes/header_common.php");
?>
<p align=center><?php echo $msg;?></p>

<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>" onSubmit="javascript: return user_valid(this);" enctype="multipart/form-data">
<input type=hidden name="id" value="<?php echo $_REQUEST[id];?>">
<input type=hidden name="user_id" value="<?php echo $_REQUEST[user_id];?>">


<table width=100% border=0 align=center cellpadding=4 cellspacing=1 class='table table-bordered'>
 

<tr >
  <td valign=top width="20%" align="left">Embeded code</td>
  <td width="50%" align="left"><textarea name='video'class='form-control'><?php echo $d['video']?></textarea></td></tr>
 
 
  <tr  ><td>&nbsp;</td><td   ><input type="submit" name="uploadVid" value="Update" class='btn btn-success' />
    <input name="button" type="button" class='btn btn-default' onClick="JavaScript:history.back();" value="Back" /></td>
</tr>
</table>

</form>

<br>
<? 
include('includes/footer.php'); 
?>
