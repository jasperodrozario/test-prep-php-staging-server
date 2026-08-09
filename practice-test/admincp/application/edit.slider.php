<?php

require_once("includes/access.php");

require_once("includes/header.php");

$a=$_REQUEST['id'];

?>

<script src="../calendar_new/js/jscal2.js" type="text/javascript"></script>

<script src="../calendar_new/js/lang/en.js" type="text/javascript"></script>

<link type="text/css" rel="stylesheet" href="../calendar_new/css/jscal2.css" />

<link type="text/css" rel="stylesheet" href="../calendar_new/css/border-radius.css" />

<link type="text/css" rel="stylesheet" href="../calendar_new/css/steel/steel.css" />

<?php

if($_POST[SBMT])
{
   
		
	if(!$_POST['id'])
	{
	  
		$sql="insert into ".$prev."services set  
		
		title='".$_POST['title_name']."', 		
		
		contents ='".htmlentities($_POST['contents_name'])."',  		 		
		
		status ='".$_POST['status']."', 
		
		site_title ='".$_POST['site_title']."', 
		
		meta_keys ='".$_POST['meta_keys'] ."',
		
		meta_desc ='".$_POST['meta_desc'] ."'";	
		
		$rr=mysqli_query($con,$sql);
		
		$_POST['id']=mysqli_insert_id($con);
			
	}

    else

    { 

		$sql_update="update  ".$prev."services set  

		title='".$_POST['title_name']."', 		

		contents ='".htmlentities($_POST['contents_name'])."',  		 		

		status ='".$_POST['status']."', 

		site_title ='".$_POST['site_title']."', 

		meta_keys ='".$_POST['meta_keys'] ."',

		meta_desc ='".$_POST['meta_desc'] ."' 
		where id='".$_POST['id']."'";


		echo "update  ".$prev."services set  

		title='".$_POST['title_name']."', 		

		contents ='".htmlentities($_POST['contents_name'])."',  		 		

		status ='".$_POST['status']."', 

		site_title ='".$_POST['site_title']."', 

		meta_keys ='".$_POST['meta_keys'] ."',

		meta_desc ='".$_POST['meta_desc'] ."' 
		where id='".$_POST['id']."'";



		$rr=mysqli_query($con,$sql_update);

	}

   

   	if($rr)
   	{
		if($_FILES['pictures']['name'])
		{
			
		
			$fl=$_FILES['pictures']['name'];
			
			$ext=substr($fl,-3,3);
			
			$rand=rand();
			
			$pathh="../upload_images/".$rand.".".$ext;
			$path1="upload_images/".$rand.".".$ext;
			
			move_uploaded_file($_FILES["pictures"]["tmp_name"],$pathh);
	        
	        $sql_update=mysqli_query($con,"update  ".$prev."services set  pictures ='".$path1."' where id=" .  $_POST['id']); 			
	        
        }
        header('Location: services.list.php');

	}

    else
    {

      $txt="Update failure";

	}

}

 
$page_title="Office Regform";
$breadcrum=array("slider List"=>"change_slider.php",
    array("view Slider"=>"" 
);

require_once("includes/header_common.php"); 

if($_REQUEST['id']):
 	$rr=mysqli_query($con,"select * from " . $prev ."services  where id=".$_REQUEST['id']);
    $data=mysqli_fetch_array($rr);
endif;
?>


<script type="text/javascript">
  function validate(form) 
    {
	if ( (form.title_name.value == "")) {

       	   alert('Please specify title name!');

		   form.title_name.focus();

	   return false;

	   }
	   if ( (form.contents_name.value == "")) {

       	   alert('Please specify content name!');

		   form.contents_name.focus();

	   return false;

	   }
	   if ( (form.status.value == "")) {

       	   alert('Please specify status!');

		   form.status.focus();

	   return false;

	   }
	
	
	}

</script>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" onSubmit="return validate(this);" enctype="multipart/form-data" autocomplete="off">

<?php if(!$data[order_date]){$data[order_date]=date("Y-m-d");}?>
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>" />
<table width="100%" border="0"  cellspacing="0"  cellpadding="4" class='table table-bordered'>
  
		  <tr>

			<td colspan="3" align="left" ><strong>Services Details</strong></td>
		  </tr>
           <tr>
             <td align="right"  ><span class="required">*</span></td>
             <td align="left"  >Title:</td>
             <td align="left"  ><input name="title_name" class='form-control' type="text" id="title_name" class="form-control" value="<?php echo $data['title']; ?>" /></td>
           </tr>
          
           <tr>
             <td align="right"  >&nbsp;</td>
             <td align="left"  >Pictures:</td>
             <td align="left"  >
			 
			 <input type="file" class='form-control' name="pictures"  id="pictures" size="25"   value="<?php echo $data['pictures'];?>" />&nbsp; * Please upload only .jpg,.jpeg,.png image<span><?php if($_REQUEST['id']){?><img src="<?php echo $vpath;?><?php echo $data['pictures'];?>" height="100" width="100" /><?php }?></span><span><?php echo $txt;?></span></td>
           </tr>
           
           <tr>
             <td align="right"  >&nbsp;</td>
             <td align="left"  >Site Title:</td>
             <td align="left"  ><input name="site_title" class='form-control' type="text" id="site_title" class="form-control" value="<?php echo $data['site_title']; ?>" /></td>
           </tr>
           
           
  			<tr>
             <td align="right"  ><span class="required">*</span></td>
             <td align="left"  >Contents:</td>
             <td align="left"  ><textarea rows=5 name="contents_name"   id="contents_name" class="form-control" /><?php echo html_entity_decode($data['contents']); ?></textarea></td>
           </tr>
 			<tr>
             <td align="right"  ><span class="required">*</span></td>
             <td align="left"  >Status:</td>
             <td align="left"  >
			 
			 <input type="radio" name="status" id="gender_1"  value="Y" <?php if($data['status']=='Y') {echo 'checked';}?>/>Yes

			<input type="radio" name="status" value="N" <?php if($data['status']=='N') {echo 'checked';}?>/>No	
			</td>
           </tr>
<tr ><td colspan=2></td><td ><?php if($_REQUEST['id']!=0){ ?><input type="submit" name="SBMT" class="btn btn-success" value="Update" onClick="return chk_validity();"><?php }else { ?><input type="submit" name="SBMT" class="btn btn-success" value="Add" onClick="return chk_validity();"><?php }?> <input type="button" name="Submit" value="Back"  class="btn btn-default" onclick="window.location='services.list.php'"/> </td></tr>

  </table>

</form>

<?php

 include('includes/footer.php');?>