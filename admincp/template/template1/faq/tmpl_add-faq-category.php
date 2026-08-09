<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="faq-category"> 


<input type='hidden' name="cat_id" value="<?=$_GET['id'];?>">

    <div class="row form-group first-row">     

        <div class="col-md-3 col-lg-3">
        <label for="">Name</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="cat_name" value="<?php echo $db_value['cat_name']?>" required>
      </div>
                                      
     
    </div>    



   <div class="row form-group">     

        <div class="col-md-3 col-lg-3">
        <label for="">Status</label>
        </div>
        <div class="col-md-9 col-lg-9">
             

               <input name='status' type='radio' value='Y'  <?php if($db_value['status']=="Y"){echo"checked";}?>>Online
              <input type='radio' name='status' value='N' <?php  if($db_value['status']=="N"){echo"checked";}?>>Offline

      </div>



                                      
     
    </div>

    <div class="row form-group">      

        <div class="col-md-3 col-lg-3">
        <label for="">&nbsp;</label>
        </div>
        <div class="col-md-9 col-lg-9">
               <input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();">
      </div>
                                      
     
    </div>



   
 </form>
 