<!--  <div id='response'></div> -->
<form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="category"> 


<input type='hidden' name="cat_id" value="<?=$_GET['cat_id'];?>">

<input type='hidden' name='cuisine_id' value='0'>

<input type="hidden" name="rest_id" id="rest_id" value="1">



    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Name</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="cat_name" value="<?php echo $db_value['cat_name']?>" required>
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
 