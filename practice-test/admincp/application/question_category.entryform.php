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
$page_title="Question Category Entry";



$breadcrum=array(
    "Question Category List"=>"question_category_list.php",  
    "Add Question Category"=>""   
);

 

require_once("includes/header_common.php");


if($_REQUEST['cat_id']) 
{ 
   
  $r2=mysqli_query($con,"select * from " . $prev . "faq_category where id=" . $_REQUEST['id']);


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


    if(!$_POST['cat_id'])
  {
  
     $r=mysqli_query($con,"insert into " . $prev . "faq_category set    
    cat_name=\"" . $_POST['question'] . "\",   
    ord=\"" . $_POST['order'] . "\",
    status=\"" . $_POST['status'] . "\"");
     $_POST['cat_id']=mysqli_insert_id($con);

      $msg="<h5 class='update-success'>Inserted successful.</h5>";

  

   
    
  }
  else
  {

     $r=mysqli_query($con,"update " . $prev . "faq_category set cat_name=\"" . $_POST['question'] . "\",  
     ord=\"" . $_POST['order'] . "\",
     status=\"" . $_POST['status'] . "\"
     where cat_id=" . $_POST['cat_id']);

     
    
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

   $r=mysqli_query($con,"select * from " . $prev . "faq_category where cat_id=" . $_REQUEST['cat_id']);

 
    
    $d=@mysqli_fetch_array($r);
    ?>


    
  <tr ><td align="left"><b>Category name</b></td><td align="left"><input type="text" class='form-control' name="question" size=30 value="<?php echo $d['cat_name']?>" required></td>
     </tr>
   
     

    
  

     
  
   


   <tr ><td align="left"><b>Order</b></td><td align="left"><input type="text"  name="order" id="order"  class='form-control' value="<?php echo $d['ord'];?>"></td></tr>
    
    

     <tr ><td align="left"><b>Status</b></td><td align="left"><input type="radio" name="status" value="Y" <?if($d["status"]=="Y"){echo" checked";}?> >Active <input type="radio" name="status" value="N" <?if($d["status"]=="N"){echo" checked";}?>> Hidden </td></tr>
     <tr ><td></td><td height="25"  ><input type="submit" class="btn btn-success" name='SBMT_REG1' value='Add'> <?if($_POST['cat_id']){?><input type="submit" class="btn btn-default" name='DELT' value='Delete'><?}?></td></tr>
</table>

</form>
    <br>
<?
 
include("includes/footer.php");
?>
 
