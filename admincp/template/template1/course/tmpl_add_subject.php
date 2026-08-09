<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="subject"> 
<input type='hidden' name="course_id" value="<?=$_GET['course_id'];?>">

<input type='hidden' name="id" value="<?=$_GET['id'];?>">

    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 >Name </td><td><input type='text' class="form-control" name='name' value='<?=$db_value['name']?>'></td></tr>
   





    <tr ><td valign=top width=20%>Description </td>
  <td ><textarea name='description' id='description' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['description']);?></textarea>
    </td></tr>


 

 

  <tr ><td valign=top width=20%>Seo Url </td>
  <td ><input type='text' class='form-control' name='seo_url' value='<?=$db_value['seo_url'];?>'></td></tr>

 

  <tr ><td height=25 >Status  </td><td>
          <input name='status' type='radio' value='Y'  <?php   if($d['status']=="Y"){echo"checked";}?>>Online
      <input type='radio' name='status' value='N' <?php   if($d['status']=="N"){echo"checked";}?>>Offline
           
           </td></tr>


    <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
 