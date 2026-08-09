

 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="feedback"> 
<input type='hidden' name="id" value="<?=$_GET['id'];?>">

<div class='table-responsive'>
    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
    <tr ><td height=25 >Feedback By <font>*</font> </td><td><input type='text' class="form-control" name='name' value='<?=$db_value['name']?>' required></td></tr>
   
    <tr ><td height=25 >Email <font>*</font> </td><td><input type='email' class="form-control" name='email' value='<?=$db_value['email']?>' ></td></tr>
  
    <tr ><td height=25 >City <font>*</font> </td><td><input type='text' class="form-control" name='city' value='<?=$db_value['city']?>' ></td></tr>
    
    
    <tr ><td height=25 >Rating <font>*</font> </td><td><input type='number' class="form-control" name='rating' value='<?=$db_value['rating']?>' ></td></tr>
 
    <tr ><td valign=top width=20%>Feedback </td>
	<td ><textarea name='feedback' id='feedback' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['feedback']);?></textarea>
    </td></tr>
    
     <tr ><td height=25 >Feedback on Date <font>*</font> </td><td><input type='date' id='date' class="form-control" name='date' value='<?=$db_value['date']?>' ></td></tr>
   
      <tr ><td height=25 >Feedback Picture <font>*</font> </td><td>
          <? 
			if($db_value['pic'])
			{
				?>
				<img src="<?=$configs['vpath'].$db_value['pic'] ?>" style="width:100px; height: 80px;">
                <br><br>
				<?

			}
			
			?>
			
          <input type='file' class="form-control" name='pic' value='<?=$db_value['pic']?>'></td></tr> 
         <tr ><td height=25 >For Course <font>*</font> </td><td> 
           <select name='course_id' class='form-control' size=1 required>
            <option value='0'> Select Course</option>
            <?
            foreach($courses as $c)
            {
               if($c['course_id']==$db_value['course_id'])
               {
                  echo "<option value='" . $c['course_id'] . "' selected>" . $c['name'] . "</option>";
               }
               else
               {
                  echo "<option value='" . $c['course_id'] . "'>" . $c['name'] . "</option>";
               }

               
            }
            ?>    
        </select>
       </td></tr>   
       <tr ><td height=25 >Status <font>*</font> </td><td>
        <input name='status' type='radio' value='Y'  <?php   if($d['status']=="Y"){echo"checked";}?>>Online
		<input type='radio' name='status' value='N' <?php   if($d['status']=="N"){echo"checked";}?>>Offline </td></tr>          
        <tr ><td></td><td ><input type='submit' class='btn btn-success' name='submit' value='Save'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>
</div> 
 