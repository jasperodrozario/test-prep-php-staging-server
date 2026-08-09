<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="questions">

<input type='hidden' name="course_id" value="<?=$_GET['course_id'];?>">

<input type='hidden' name="chapter_id" value="<?=$_GET['chapter_id'];?>">


<input type='hidden' name="cat_id" value="<?=$_GET['cat_id'];?>">

<input type='hidden' name="id" value="<?=$_GET['id'];?>">





    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>


        <tr><td width="15%">Chapter</td><td><select name='chapter_id' size=1 class='form-control' required>
          <option value='0'>Select Chapter</option>  
          <? 
          foreach($chapter as $dd)
          {
            if($db_value['chapter_id']==$dd['chapter_id'] || $dd['chapter_id']==$_GET['chapter_id']):
              echo"<option value=". $dd['chapter_id'] . " selected>" . $dd['name'] . "</option>";
            else:
              echo"<option value=". $dd['chapter_id'] . ">" . $dd['name'] . "</option>";
            endif;
        
        }
         
        ?>
        </select></td></tr>

        <tr ><td align="left">Question For</td><td align="left"><input type="radio" name="qtype" value="2" <?if($db_value["qtype"]=="2"){echo" checked";}?> > Homework <input type="radio" name="qtype" value="1" <?if($db_value["qtype"]=="1"){echo" checked";}?>> Practice Test <input type="radio" name="qtype" value="3" <?if($db_value["qtype"]=="3"){echo" checked";}?>> Final Test </td></tr> 



    <tr ><td valign=top width=20%>Question </td>
  <td ><textarea name='question' id='question' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['question']);?></textarea>
    </td></tr>



    <tr ><td valign=top width=20%>Question Explanation</td>
  <td ><textarea name='question_explanation' id='question_explanation' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['question_explanation']);?></textarea>
    </td></tr>


     <tr  ><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?>>Active <input type='radio' name=status value='N' <?if($db_value['status']=="N"){echo"checked";}?>>Hidden</td></tr>



    <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
 