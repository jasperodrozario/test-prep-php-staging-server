<!--banner section-->

   

<!--contact section-->
  <div class="contact_body">
        <div class="container">
            <div class="midle-conarea mr-t88">
       
              <div class='col-md-3'></div>
              <div class="col-lg-6 col-sm-6">
                    <div class="contact_box">

                    
                        <div class="contact_heading">
                            <h2>REPORT ERROR </h2>
                        </div>
                        <div id='response-success'></div> 
                        <div id='response-error'></div> 
                        <div class="contact_text list">
                         
                            <form class="form-contact" id='form' method="POST">
                              <input type='hidden' name='cat_id' value='<?=$_GET['cat_id']?>'>
                              <input type='hidden' name='lesson_id' value='<?=$_GET['lesson_id']?>'>
                              <input type='hidden' name='lesson_name' value='<?=$_GET['name']?>'>
                              <input type='hidden' name='url' value='<?=$_GET['url']?>'>
                              <input type='hidden' name='in' value='<?=$_GET['in']?>'>
                              <input type='hidden' name='hw_order' value='<?=$_GET['hw_ord']?>'>
                              <input type='hidden' name='name' value='<?=$_SESSON['name']?>'>
                              <input type='hidden' name='email' value='<?=$_SESSON['email']?>'>
                              <input type='hidden' name='action' value='report-error'> 
                              <? if($cat_name){ ?>
                              <div class="form-group">
                                <div class="col-sm-12 padding0">
                                    <input type="text" class='form-control' name='cat_name' value="<?php echo $cat_name; ?>" readonly>
                                </div>
                              </div>
                            
                             <div class="form-group">
                                <div class="col-sm-12 padding0">
                                    <input type="text" class='form-control' name='lesson_name' value="<?php echo $lesson_name; ?>" readonly>
                                </div>
                              </div>
                              <?
                                if($_GET['in']=="practice-test"){
                              ?>
                                  <div class="form-group">
                                    <div class="col-sm-12 padding0">
                                        <input type="number" class='form-control' name='question_order' placeholder="Enter the question order number" required>
                                    </div>
                                  </div>
                                <?
                                }
                                ?>
                            <? } if($pred_cat){ ?>
                              <div class="form-group">
                                <div class="col-sm-12 padding0">
                                    <input type="text" class='form-control' name='cat_name' value="<?php echo $pred_cat; ?>" readonly>
                                </div>
                              </div>
                             <div class="form-group">
                                <div class="col-sm-12 padding0">
                                    <input type="number" class='form-control' name='question_order' placeholder="Enter the question order number" required>
                                </div>
                              </div>
                            <?}?>
                              
                                
                              <div class="form-group">
                                <div class="col-sm-12 padding0">
                                  <textarea class="form-control" rows="10" placeholder="Describe error" name="message"></textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-12 padding0">
                                  <button type="submit" class="cust-btn" name="Submit">Submit</button>
                                </div>
                              </div>
                            </form>    
                        </div>
                         <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-3">
                   
                </div>





              <div class="clearfix"></div>
            </div>
        </div>
    </div>

<!--footer section-->



