<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="faq"> 


<input type='hidden' name="id" value="<?=$_GET['id'];?>">

    <div class="row form-group first-row">     

        <div class="col-md-3 col-lg-3">
        <label for="">Question</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type=text name="question" value="<?php echo $db_value['question']?>" required>
      </div>
                                      
     
    </div>

    <div class="row form-group">     

        <div class="col-md-3 col-lg-3">
        <label for="">Course</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <select id="course_id" class="form-control " name="course_id" >
          <option value="">Choose a course</option>
          <?
          foreach($courses as $c)
          {   
               if($db_value['course_id']==$c['course_id'] || $c['course_id']==$_GET['course_id'])
               {
                  echo"<option value='". $c['course_id'] ."' selected>" . $c['name']  ."</option>";
               } 
               else
               {
                  echo"<option value='". $c['course_id'] ."'>" . $c['name']  ."</option>";
               }  
                
          }    
          ?> 
          </select>
      </div> 
    </div> 
    <div class="row form-group"> 
        <div class="col-md-3 col-lg-3">
        <label for="">Answers</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <textarea name='answers' id='answers' class="form-control description"  data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?=html_entity_decode($db_value['answers']);?></textarea>
      </div> 
    </div> 

    <div class="row form-group"> 
        <div class="col-md-3 col-lg-3">
        <label for="">Display Order</label>
        </div>
        <div class="col-md-9 col-lg-9">
        <input class='form-control' type="number" placeholder="Enter Order Number" name="ord" value="<?php echo $db_value['ord']?>" required>
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
 