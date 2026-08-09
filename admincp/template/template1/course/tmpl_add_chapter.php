<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="chapter">

<input type='hidden' name="course_id" value="<?=$_GET['course_id'];?>">

<input type='hidden' name="chapter_id" value="<?=$_GET['chapter_id'];?>">

    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 >Name </td><td><input type='text' class="form-control" name='name' value='<?=$db_value['name']?>'></td></tr>


   
  


    <tr ><td valign=top width=20%>Details </td>
  <td ><textarea name='description' id='description' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['description']);?></textarea>
    </td></tr>


   

    <tr ><td align="left">Duration</td>

      <td align="left">

        <div class='row'><div class='col-md-6'>
          <input type='number' class="form-control" name="duration" value='<?=$db_value['duration'];?>' required>
      </div><div class='col-md-6'>
          <select class="form-control" name="unit" required>
      <option value=''>Select Unit</option> 
       <?
       for($i=0;$i<count($unit_array);$i++):
       
        if($unit_array[$i]==$db_value['unit']):
          echo"<option value='" . $unit_array[$i] . "' selected>" . $unit_array[$i] ."</option>";
        else:
          echo"<option value='" . $unit_array[$i] . "'>" . $unit_array[$i] ."</option>";
        endif;  

       endfor;  
           ?>
        </select>
          
         </div></div>
         
      </td>

    </tr>


 <tr  >
  <td align="left">Display Order</td>
  <td><input type="number" class='form-control' name='ord'   value="<?php echo $db_value['ord']?>" ></td></tr>


    <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
 