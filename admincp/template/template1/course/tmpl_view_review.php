<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="course_review">

<input type='hidden' name="course_id" value="<?=$_GET['course_id'];?>">

<input type='hidden' name="id" value="<?=$_GET['id'];?>">

    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 >Name </td><td><input type='text' class="form-control" name='name' value='<?=$db_name['fname']?> <?=$db_name['lname']?>'></td></tr>


   
  


    <tr ><td valign=top width=20%>Review </td>
  <td ><textarea name='description' id='description' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['review']);?></textarea>
    </td></tr>


   <tr ><td height=25 >Rate </td><td><input type='text' class="form-control" name='rate' value='<?=$db_value['rate']?>'></td></tr>

    

<tr ><td height=25 >Status  </td><td>
          <input name='status' type='radio' value='Y'  <?php   if($db_value['status']=="Y"){echo"checked";}?>>Online
      <input type='radio' name='status' value='N' <?php   if($db_value['status']=="N"){echo"checked";}?>>Offline
           
           </td></tr>


</table>
 </form>
 