<!--  <div id='response'></div> -->
<form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="category"> 


<input type='hidden' name="cat_id" value="<?=$_GET['cat_id'];?>">

  <?php
  if($_GET['parent_id'])
  {
   echo "<input type='hidden' name='parent_id' value='".$_GET['parent_id']."'>"; 
    
  }
  else{
     
     echo "<input type='hidden' name='parent_id' value='0'>";

  }
   ?>



    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Name</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="cat_name" value="<?php echo $db_value['cat_name']?>" required>
      </div>
                                      
     
    </div>



     <?php
    if($_GET['parent_id'])
    {
    ?>


    <div class="row row-input-button">      

        <div class="col-md-3 col-lg-3">
        <label for="">Icon</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input type="file" class='form-control' name="icon" value="" >
        <?if($db_value['icon'])
        {
        ?>
          <br>
          <img src="<?php echo $configs['vpath'].$db_value['icon']; ?>" width="100">
        <?}?>
      </div>
                                      
     
    </div>


      <div class="row row-input-button">      

        <div class="col-md-3 col-lg-3">
        <label for="">Description</label>
        </div>
        <div class="col-md-9 col-lg-9">
            <textarea name='contents' id='contents' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['contents']);?></textarea>
      </div>
                                      
     
    </div>

  <? } ?>

  

   

<!--     <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Display Order</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="ord" value="<?php echo $db_value['ord']?>" required>
      </div>
                                      
     
    </div> -->

     <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Top</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input type="checkbox" name="top"  value="1" <? if($db_value['top']): echo 'checked="checked"'; endif; ?> >
      </div>
                                      
     
    </div>

    

   <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Status</label>
        </div>
        <div class="col-md-9 col-lg-9">
               <select name='status' class='form-control'>
                     <option value=''>Select </option>
                     <option value="Y" <?php   if($db_value['status']=="Y"){echo"selected";}?>>Active</option>
                      <option value="N" <?php   if($db_value['status']=="N"){echo"selected";}?>>Inactive</option>
                     
                    
              </select>
      </div>
                                      
     
    </div>

    <div class="row row-input-button">      

        <div class="col-md-3 col-lg-3">
        <label for="">&nbsp;</label>
        </div>
        <div class="col-md-9 col-lg-9">
               <input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();">
      </div>
                                      
     
    </div>



   
 </form>
 