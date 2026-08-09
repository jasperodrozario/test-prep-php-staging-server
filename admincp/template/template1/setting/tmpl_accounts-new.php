
<form class="form-horizontal"  id="form"  accept-charset="UTF-8" method="post">
<input name="table" type="hidden" value="bank_account" />
<input name="auto_id" type="hidden" value="id" />
<input name="id" type="hidden" value="<?=$_REQUEST['id'];?>" />

    <div class="row form-group"> 
	    <div class="col-md-6">
	        <label class="control-label" >Bank <span class='co-red'>*</span></label>
	         <select class="form-control" name="bank_id" >
            <option value="">Select Bank</option>
			<?
			for($i=1;$i<=count($banks);$i++)
			{
				if($db_value['bank_id']==$i)
				{
					echo"<option value='" .$i ."' selected>" .$banks[$i] ."</option>";
			
				}
				else
				{
					echo"<option value='" .$i ."'>" .$banks[$i]."</option>";
			
				}
			}
			?> 
			</select>
        </div>
        <div class="col-md-6">
         	<label class="control-label" >Account Open Date *</label>
	        
	        <input class="form-control date" required="required" placeholder="Account Open Date" type="text" value='<?=$db_value['account_open_date'];?>' name="account_open_date"  />
	       
	    </div>
	</div>
	<div class="row form-group">     
	    <div class="col-md-6"> 
	        <label class="control-label" >Account No. *</label>
	        
	        <input class="form-control" placeholder="Account No" type="text" value='<?=$db_value['account_no'];?>' name="account_no" required />
	         
	    </div>
	    <div class="col-md-6"> 
	        <label class="control-label" >IFSC Code *</label>
	        
	        <input class="form-control" placeholder="IFSC Code" type="text" value='<?=$db_value['ifsc_code'];?>' name="ifsc_code" required />
	         
	    </div> 
	    
	</div>    
	<div class="row form-group">      
	          
	   <div class="col-md-6">     
	   
	        <label class="control-label" >Account Type *</label>
	        <select name='account_type' class='form-control' size=1 required>
	         <option value=''>Account type</option>
	         <option value='current' <?if($db_value['account_type']="current"){echo"selected";}?>>Current </option>
	         <option value='saving' <?if($db_value['account_type']="saving"){echo"selected";}?>>Saving </option>
	        </select>
	    </div>
        <div class="col-md-6">
	          <label class="control-label" >Address *</label>
            
              <textarea class="form-control" placeholder="Address"  name="address"  /><?=$db_value['address'];?></textarea>
        </div>  
        
	</div>
	<div class="row form-group">      
	          
	    <div class="col-md-6">
	           <label class="control-label" >Account Active * </label>
            
              <input  type="radio" name="account_active" value='Y' <?if($db_value['account_active']=="Y"){echo"checked";};?>'  /> Yes
                    
              <input  type="radio" name="account_active" value='N' <?if($db_value['account_active']=="N"){echo"checked";};?>'  /> No
        </div>  
        <div class="col-md-6">
	           <label class="control-label" >Use for Printing</label>
            
              <input type="radio" name="printing" value='Y' <?if($db_value['printing']=="Y"){echo"checked";}?>  /> Yes 
              <input type="radio" name="printing" value='N' <?if($db_value['printing']=="N"){echo"checked";}?>  /> NO
        </div>  
        
	</div>

  <div class="row form-group">      
	    <div class="col-md-12"><b>Link with Software</b></div> 
	    <div class='clearfix'><br><br></div>     
	    <div class="col-md-6">
	           <label class="control-label" >Compny Branch* </label>
	           <select name='branch_id' class='form-control' size=1>
	           	 <option value=''>Select Branch</option>
	           	        
                  
	           </select>
         </div>  
        <div class="col-md-6">
	           <label class="control-label" >Accounting Bank *</label>
                <select name='accounting_bank_id' class='form-control' size=1>
	           	<option value=''>Select Accounting Branch</option>
	           	        
                  
	            </select>
        </div>  
        
	</div>

  <div class="clearfix margin-bottom-10"></div>

  <div class="form-group text-center">
      <button type="submit" class="btn btn-success" >Save</button>
      <a class="btn btn-danger" onClick='history.back()'>Cancel</a>
      <button class="btn btn-warning" type="reset">Reset</button>

  </div>
</form>