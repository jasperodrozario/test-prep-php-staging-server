<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="course"> 


<!--<input type='hidden' name="cat_id" value="<?=$_GET['cat_id'];?>">-->

<input type='hidden' name="course_id" value="<?=$_GET['course_id'];?>">

    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 >Course Name </td><td><input type='text' class="form-control" name='name' value='<?=$db_value['name']?>'></td></tr>


    <tr><td width="15%">Category Name</td><td><select name='cat_id' size=1 class='form-control' required>
          <option value='0'>Select Category</option>  
          <? 
          foreach($category as $dd)
          {
            if($db_value['cat_id']==$dd['cat_id'] || $dd['cat_id']==$_GET['cat_id']):
              echo"<option value=". $dd['cat_id'] . " selected>" . $dd['cat_name'] . "</option>";
            else:
              echo"<option value=". $dd['cat_id'] . ">" . $dd['cat_name'] . "</option>";
            endif;
        
        }
         
        ?>
        </select></td></tr>
   


  <tr ><td valign="top" >Cost</td><td ><input type="text" class='form-control' name="cost" size=30 value="<?php echo $db_value['cost']?>" ></td></tr>


    <tr ><td valign=top width=20%>Details </td>
  <td ><textarea name='details' id='details' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['details']);?></textarea>
    </td></tr>


     <tr ><td valign=top width=20%>What Will I Learn </td>
  <td ><textarea name='learn' id='learn' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['learn']);?></textarea>
    </td></tr>

    <tr ><td align="left">Duration</td><td align="left">
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
      </td></tr>


      <!-- <tr ><td valign=top width=20%>Eligibility </td>
  <td ><textarea name='eligibility' id='eligibility' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['eligibility']);?></textarea>

    </td></tr>-->
    
    <tr ><td align="left">Eligibility</td><td align="left">
      <select class="form-control" name="eligibility" size=1 required>
        <option value=''>Select Eligibility</option>
        <?
     for($i=0;$i<count($eligibility_array);$i++):
     
      if($eligibility_array[$i]==$db_value['eligibility']):
        echo"<option value='" . $eligibility_array[$i] . "' selected>" . $eligibility_array[$i] ."</option>";
      else:
        echo"<option value='" . $eligibility_array[$i]. "'>" . $eligibility_array[$i] ."</option>";
      endif;  

     endfor;  
        ?>
      </select></td>
      </tr>



     <tr ><td align="left">Language</td><td align="left">
      <select class="form-control" name="language" size=1 required>
        <option value=''>Select Language</option>
        <?
     for($i=0;$i<count($lang_array);$i++):
     
      if($lang_array[$i]==$db_value['language']):
        echo"<option value='" . $lang_array[$i] . "' selected>" . $lang_array[$i] ."</option>";
      else:
        echo"<option value='" . $lang_array[$i]. "'>" . $lang_array[$i] ."</option>";
      endif;  

     endfor;  
        ?>
      </select></td>
      </tr>



      <tr ><td valign=top width=20%>Requirements </td>
  <td ><textarea name='requirement' id='requirement' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['requirement']);?></textarea>

    </td></tr>


 
 <tr ><td align="left">Picture</td><td align="left"><input type="file" class='form-control' name="picture" value="" >
    <?if($db_value['picture'])
    {
    ?>
      <br>
      <img src="<?php echo $configs['vpath'].$db_value['picture']; ?>" width="400" height="200">
    <?}?>
  </td>
     </tr>

      <tr ><td align="left">Thumb Pic</td><td align="left"><input type="file" class='form-control' name="thumb"  value="">
    <?if($db_value['thumb'])
    {
    ?>
        <br>
      <img src="<?php echo $configs['vpath'].$db_value['thumb']; ?>" width="80" height="50" >
        <?
        }
        ?>
      </td>
     </tr>



       <tr ><td align="left">Number of Classes</td><td align="left"><input type="number" class='form-control' name="class" size=30 value="<?php echo $db_value['class']?>" required></td> </tr>
      
      <tr ><td align="left">Number of Exams</td><td align="left"><input type="number" class='form-control' name="exam" size=30 value="<?php echo $db_value['exam']?>" required></td> </tr>



       <tr ><td align="left">Video</td><td align="left"><input type='file' class='form-control' style='width:100%' name="video" id='video'>
      <?
      if($db_value['video'])
      {
      ?>
          <video width="320" height="240" controls>
          <source src="<?=$configs['vpath'].$db_value['video']?>" type="video/mp4">
          </video>
    <?
        }
        ?></td> </tr>
 

   <tr ><td align="left">Assessment</td><td align="left"><input type="radio" name="assessment" value="Y" <?if($db_value["assessment"]=="Y"){echo" checked";}?> >Yes <input type="radio" name="assessment" value="N" <?if($db_value["assessment"]=="N"){echo" checked";}?>> No </td></tr> 


       <tr  ><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?>>Active <input type='radio' name=status value='N' <?if($db_value['status']=="N"){echo"checked";}?>>Hidden</td></tr>


    <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
 