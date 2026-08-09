


<form class="form-horizontal"  id="enquiry_form"  accept-charset="UTF-8" method="post" style='padding:20px;'>
<div id='enquiry_response'></div>
<input name="action" type="hidden" value="enquiry" />
 <input name="id" type="hidden" value="<?=$_GET['id'];?>" />
 
     <div class="row form-group">
 
	    <div class="col-md-6">
	        <label class="control-label" > Name </label>
	        <input type="text" class='form-control' name='name'  value="<?php echo $db_value['name']?>" required>
        </div>
    
        <div class="col-md-6">
	        <label class="control-label" > Email </label>
	        <input type="email" class='form-control' name='email'  value="<?php echo $db_value['email']?>" required>
        </div>
    </div>
     
     
	 <div class="col-md-6">
         	<label class="control-label" >Mobile Number</label>
	        <input type="text" class='form-control' name='mobile'  value="<?php echo $db_value['mobile']?>" required>
	       
	    </div>
    </div>
    <div class="row form-group">
	    
		 <div class="col-md-12">
	        <label class="control-label" >Subject <span class='co-red'>*</span></label>
	        <input type="text" class='form-control' name='subject'  value="<?php echo $db_value['subject']?>" required>
        </div>
    </div>
 
     <div class="row form-group">    

         <div class="col-md-12">
	        <label class="control-label" >Message</label>
	        <textarea   rows="4" name="message" id="message" class="form-control" required><?php echo html_entity_decode($db_value['message']);?></textarea>
        </div>
    </div>
     <div class="row form-group">

	    <div class="col-md-12">
         	<label class="control-label" >Status</label>
	        
	       		
  	        <input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?> required>Checked <input type='radio' name='status' value='N' <?if($db_value['status']=="N"){echo"checked";}?> required>New
	       
	    </div>

	</div>

	<hr>
    <div class="row form-group">    

<div class="col-md-12">
   <label class="control-label" >Admin Notes</label>
   <textarea   rows="4" name="notes" id="notes" class="form-control" required><?php echo html_entity_decode($db_value['notes']);?></textarea>
</div>
</div>

  <div class="clearfix margin-bottom-10"></div>

  <div class="form-group text-center"><button type="submit" class="btn btn-success" >Save </button></div> 
  
  
</form>