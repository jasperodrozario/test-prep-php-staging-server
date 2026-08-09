<?
include("includes/access.php");
include("includes/header.php");
 
if($_POST['SBMT_REG']):
     
   	 if($_POST['cat_id']):
   	    $r= mysqli_query($con,"update " . $prev . "category set  cat_name=\"" . $_POST['cat_name'] . "\",parent_id=\"" . $_POST['parent_id'] . "\",status=\"" . $_POST['status'] . "\" where cat_id=" . $_POST['cat_id']);
	       
		$cat_id=$_POST['cat_id'];
	 else:
	     $r= mysqli_query($con,"insert into " . $prev . "category set cat_name=\"" . $_POST['cat_name'] . "\",parent_id=\"" . $_POST['parent_id'] . "\",status=\"" . $_POST['status'] . "\"");
		  
		$cat_id= mysqli_insert_id($con);
	 endif;
	 if($r):
	 	$msg="Update successful.";
	 	header("location:category.list.php");
    	
	 endif;
elseif($_POST['SBMT_DEL']):
     $r=mysqli_query($con,"delete from " . $prev . "category where cat_id=" . $_POST['cat_id']);
 
	 if($r):
	     $msg="Deletion successfull";
	     header("location:category.list.php");
	 endif;
endif;

$page_title="Add  Sub Category";
$breadcrum=array(
    array("Category List", "category.list.php"),
    array("Add Sub Category", ""),
);
 
require_once("includes/header_common.php");
if($_REQUEST['cat_id']):
	$r= mysqli_query($con,"select * from " . $prev . "category where cat_id=" . $_REQUEST['cat_id']);
	$data=mysqli_fetch_array($r);
endif;
if(!$data['status']){$data['status']='Y';}
if($msg):
  echo $msg;
 
endif;
?>
<!-- <form method="post" action="<?//php echo $_SERVER['PHP_SELF'];?>"> -->
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" onSubmit="javscript:return Validbanner(this);">
<input type="hidden" name="cat_id" value="<?php echo $_GET['cat_id']?>">

<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
 
<tr  >
  <td align="left">Name</td>
  <td><input type="text" class='form-control' name='cat_name'  size=40 value="<?php echo $data['cat_name']?>" ></td></tr>
 <tr  >
  <td align="left">Parent Category</td>
  <td>
    <select name='parent_id' size=1 class='form-control'>
    <option value='0'>No Parent Category</option>	
    <? 
  	$rr= mysqli_query($con,"select * from " . $prev . "category where parent_id=0");
	while($dd=mysqli_fetch_array($rr)):
	    if($data['parent_id']==$dd['cat_id'] || $dd['cat_id']==$_GET['parent_id']):
	   		echo"<option value=". $dd['cat_id'] . " selected>" . $dd['cat_name'] . "</option>";
	    else:
	    	echo"<option value=". $dd['cat_id'] . ">" . $dd['cat_name'] . "</option>";
	    endif;
	endwhile;
	?>
	</select>
	
  </td></tr>

<tr  ><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($data['status']=="Y"){echo"checked";}?>>Active <input type='radio' name=status value='N' <?if($data['status']=="N"){echo"checked";}?>>Hidden</td></tr>
<tr><td></td><td ><input type=submit  name='SBMT_REG' value='Update' class='btn btn-success'>&nbsp;&nbsp;<? if($id){?><input type='submit'  name='SBMT_DEL' class='btn btn-danger' value='Delete' onClick="javascript:return confirmDelete();"><? }?></td></tr>
</table>
</form>

<?
include("includes/footer.php");
?>
