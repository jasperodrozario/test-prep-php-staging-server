<?
include("includes/access.php");
include("includes/header.php");

if($_POST['SBMT_REG12']):


	
 




endif;



?>
<link href="assets/css/jquery.datepick.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="assets/js/jquery.plugin.min.js"></script>
<script src="assets/js/jquery.datepick.js"></script>
<script>
$(function() {
	
	$('#start_date').datepick({dateFormat: 'yyyy-mm-dd'});
	$('#finish_date').datepick({dateFormat: 'yyyy-mm-dd'});
 
});

</script>
<?php
$page_title="Question Entry";



$breadcrum=array(
     "question List"=>"question.list.php",  
     "Add question"=>""   
);

 

require_once("includes/header_common.php");

if($_REQUEST['id']) 
{ 
	 
	$r2=mysqli_query($con,"select * from " . $prev . "faq where id=" . $_REQUEST['id']);


	//echo "select * from " . $prev . "link where id=" . $_REQUEST['id'];

	$d=@mysqli_fetch_array($r2);
	if($d['size']!='Other'):
		$e=explode("x",$d['size']);
		$width=$e['0'];$height=$e['1'];
	endif;	
}


if(!$d['status']){$d['status']="Y";}
?>


 <?php 
if($_POST['SBMT_REG1']){


		if(!$_POST['id'])
	{
	
		 $r=mysqli_query($con,"insert into " . $prev . "faq set 	
		
		question=\"" . $_POST['question'] . "\",
	
		answers=\"" . $_POST['answers'] . "\",
		category=\"" . $_POST['category'] . "\",
		ord=\"" . $_POST['order'] . "\",
		status=\"" . $_POST['status'] . "\"");
		 $_POST['id']=mysqli_insert_id($con);

		  $msg="<h5 class='update-success'>Inserted successful.</h5>";

	
		
		
	}
	else
	{

		 $r=mysqli_query($con,"update " . $prev . "faq set question=\"" . $_POST['question'] . "\",
	
	  
	   answers=\"" . $_POST['answers'] . "\",
	   
	  
	   ord=\"" . $_POST['order'] . "\",
	   status=\"" . $_POST['status'] . "\"
	   where id=" . $_POST['id']);
	  
	   $d=@mysqli_fetch_array($r);

	   $msg="<h5 class='update-success'><img src='images/save.png' align='absmiddle'>Update successful.</h5>";


		
		
	}









	
	

}


    ?>



    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" onSubmit="javscript:return Validbanner(this);">



    <?php echo $msg;?>
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
    <table border="0" cellpadding="4" cellspacing="0" width="100%"  class='table table-bordered'>

    	<?php  

   $r2=mysqli_query($con,"select * from " . $prev . "faq where id=" . $_REQUEST['id']);
    
    $d=@mysqli_fetch_array($r2);
    ?>


    
	<tr ><td align="left"><b>Question</b></td><td align="left"><input type="text" class='form-control' name="question" size=30 value="<?php echo $d['question']?>" required></td>
	   </tr>
	 
	   <tr >
  <td valign="top" width="13%"><b>Answers</b><font color="#CC3300">*</font></td>
  <td width="51%"><textarea  cols="50" rows="5" name="answers"  class="form-control"><?php echo $d['answers'];?></textarea>
    
</td></tr>

	  
	

	   
	
   


	 <tr ><td align="left"><b>Order</b></td><td align="left"><input type="text"  name="order" id="order"  class='form-control' value="<?php echo $d['ord'];?>"></td></tr>
	  
    

     <tr ><td align="left"><b>Status</b></td><td align="left"><input type="radio" name="status" value="Y" <?if($d["status"]=="Y"){echo" checked";}?> >Active <input type="radio" name="status" value="N" <?if($d["status"]=="N"){echo" checked";}?>> Hidden </td></tr>
     <tr ><td></td><td height="25"  ><input type="submit" class="btn btn-success" name='SBMT_REG1' value='Add'> <?if($_POST['id']){?><input type="submit" class="btn btn-default" name='DELT' value='Delete'><?}?></td></tr>
</table>

</form>
    <br>
<?
 
include("includes/footer.php");
?>
 
