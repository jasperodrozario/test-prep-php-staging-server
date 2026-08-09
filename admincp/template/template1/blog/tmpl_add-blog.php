<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="blog"> 


<input type='hidden' name="id" value="<?=$_GET['id'];?>">

    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Name</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="name" value="<?php echo $db_value['name']?>" required>
      </div>
                                      
     
    </div>

   <!--  <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Category</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <select id="cat_id" class="form-control " name="cat_id"   required>
          <option value="">Choose a category</option>
          <?
          foreach($categories as $category)
          {   
               if($db_value['cat_id']==$category['id'] || $category['id']==$_GET['cat_id'])
               {
                  echo"<option value='". $category['id'] ."' selected>" . $category['name']  ."</option>";
               } 
               else
               {
                  echo"<option value='". $category['id'] ."'>" . $category['name']  ."</option>";
               }  
                
          }    
          ?> 
          </select>
      </div>
                                      
     
    </div> --> 

    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Author Name</label>
        </div>
        <div class="col-md-5 col-lg-5">
        <input class='form-control' type=text name="author" value="<?php echo $db_value['author']?>" required>


      </div>

    
        <div class="col-md-4 col-lg-4">
       <input class='form-control' type="date" name="date" value="<?php echo $db_value['date']?>" required>

        
      </div>
                                      
     
    </div>     



    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Meta Keys</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="meta_keys" value="<?php echo $db_value['meta_keys']?>" required>
      </div>
                                      
     
    </div>

     <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Meta Description</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="meta_desc" value="<?php echo $db_value['meta_desc']?>" required>
      </div>
                                      
     
    </div>


    

    <div class="row row-input-button">      

        <div class="col-md-3 col-lg-3">
        <label for="">Picture</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input type="file" class='form-control' name="pic" value="" >
        <?if($db_value['pic'])
        {
        ?>
          <br>
          <img src="<?php echo $configs['vpath'].$db_value['pic']; ?>" width="200">
        <?}?>
      </div>
                                      
     
    </div>


    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Description</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <textarea name='description' id='description' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['description']);?></textarea>
      </div>
                                      
     
    </div>

    

    <div class="row row-input-button">     

        <div class="col-md-3 col-lg-3">
        <label for="">Course Group</label>
        </div>
        <div class="col-md-9 col-lg-9">
            <?php
            $course_groups = getQuery("course_group","*",array());
            ?>
            
            <select name='course_group' class='form-control'>
                <option value=''>Select Course Group</option>
              <?
              foreach($course_groups as $c){
                   if($db_value['course_group']==$c['group_id'])
                   {
                      echo"<option value='". $c['group_id'] ."' selected>" . $c['group_name']  ."</option>";
                   } 
                   else
                   {
                      echo"<option value='". $c['group_id'] ."'>" . $c['group_name']  ."</option>";
                   }  

              }    
              ?> 
               </select>
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
 