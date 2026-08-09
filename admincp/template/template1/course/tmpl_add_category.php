<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="category"> 
<input type="hidden" name="parent_id" value="0">
<input type='hidden' name="cat_id" value="<?=$_GET['cat_id'];?>">

    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 ><b>Name </td><td><input type='text' class="form-control" name='cat_name' value='<?=$db_value['cat_name']?>'></td></tr>


      <tr  >
  <td align="left">Icon</td>
  <td>
  <?if($db_value['icon'])
  {
     echo"<img src='".$configs['vpath'].$db_value['icon']."'><br>"; 
  }
  
  ?>
  <input type="file" class='form-control' name='icon'></td></tr>
   


  


    <tr ><td valign=top width=20%>Description </td>
  <td ><textarea name='description' id='description' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['description']);?></textarea>
    </td></tr>



    <tr  ><td>Add Course</td><td><input type="checkbox" value='1' name="lesson"  <?if($db_value['lesson']=="1"){echo"checked";}?>> Yes</td></tr>



    <tr  ><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?>>Active <input type='radio' name=status value='N' <?if($db_value['status']=="N"){echo"checked";}?>>Hidden</td></tr>

    <tr  >
  <td align="left">Display Order</td>
  <td><input type="number" class='form-control' name='ord'   value="<?php echo $db_value['ord']?>" ></td></tr>

    



    <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
 