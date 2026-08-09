
  <div class="userprofile_wrapper">
      <div class="container">
        
            <div class="userprofile-body midle-conarea mr-t88">
<!--
            <div class="col-lg-3 col-md-12">
            
                 
                <?
//                include("includes/tmpl_left_dashboard.php");
                ?>
               
            </div>
-->
            
            <div class="col-lg-12 col-md-12">
           
               <div id='response'></div>  
              
              <div class="panel dashbord-panel with-nav-tabs panel-default">
                  <div class="panel-heading">&nbsp; Edit Profile</div>
                  <div class="panel-body">
                

                <form   method="post" id="form" enctype="multipart/form-data" >
                <input type='hidden' name='action' value='edit-profile'>
                <div class="form-group">
                    <div class='col-md-3'><label>First Name</label></div>
                    <div class='col-md-9'><input type="text" class="form-control"  placeholder="First Name" value="<?=$db_value['first_name'];?>" id="first_name" required="" name="first_name"></div>
                </div>
                <div class="form-group">
                   <div class='col-md-3'><label>Last Name</label></div>
                    <div class='col-md-9'><input type="text" class="form-control" placeholder="Last Name" value="<?=$db_value['last_name'];?>" id="last_name" name="last_name"></div>
                </div>
                <div class="form-group">
                    <div class='col-md-3'><label>Email</label></div>
                    <div class='col-md-9'><input type="email" value="<?=$db_value['email'];?>" class="form-control" placeholder="Email"  id="email" name="email"></div>
                </div>
                <div class="form-group">
                    <div class='col-md-3'><label>Mobile</label></div>
                    <div class='col-md-9'><input type="text" value="<?=$db_value['mobile'];?>" class="form-control" id="mobile" placeholder="Mobile" name="mobile"></div>
                </div>
                 <div class="form-group">
                    <div class='col-md-3'><label>State</label></div>
                    <div class='col-md-9'>
                    <select name='state_id' id='state_id' class="form-control" size=1 required>
                    <option value=''>Select State</option>
                    <?
                    foreach($state_list as $s)
                    {
						if($db_value['state_id']==$s['id'])
						{
							echo"<option value='".$s['id']."' selected>".$s['name']."</option>";
						}
						else
						{
							echo"<option value='".$s['id']."'>".$s['name']."</option>";
						
						}
						
					}
                    ?>	
                    </select>
                    </div>
                </div>
                 <div class="form-group">
                    <div class='col-md-3'><label>City</label></div>
                    <div class='col-md-9'>
                     <select name='city_id' id='city_id' class="form-control" size=1 required>
                    <option value=''>Select City</option>
                    <?
                    foreach($city_list as $s)
                    {
						if($db_value['city_id']==$s['id'])
						{
							echo"<option value='".$s['id']."' selected>".$s['name']."</option>";
						}
						else
						{
							echo"<option value='".$s['id']."'>".$s['name']."</option>";
						
						}
						
					}
                    ?>	
                    </select>	
                    	
                    	
                    </div>
                </div>
                 <div class="form-group">
                    <div class='col-md-3'><label>ZIp Code</label></div>
                    <div class='col-md-9'><input type="text" value="<?=$db_value['zip_code'];?>" class="form-control" id="zip_code" placeholder="Zip code" name="zip_code" required></div>
                </div>
                
                <!--<div class="form-group">
                 <div class='col-md-3'><label>Select a Test</label></div>
                    <div class='col-md-9'><select name='test_id' class='form-control' required>
                    <option value=''>Select Test Name</option>
                    <?
	                foreach($test_names as $t)
	                {
	                    if($t['id']==$db_value['test_id'])
	                    {
	                    	echo"<option  value='" . $t['id'] ."' required selected>".$t['name']  . "</option>";
	                    }
	                    else
	                    {
	                    	echo"<option  value='" . $t['id'] ."'>".$t['name']  . "</option>";
	                    }		
	                   	
	                }
	                ?>
	                </select></div>	
               	</div>-->
                <div class="form-group">
                <span class="error" style="color:#0E6938"></span><br />
              </div>
				
                <div class="form-group text-center">
                <div class='col-md-3'></div>
                <div class='col-md-9 '>
                    <button type="submit" name="submit" class="btn btn-success pull-left" style="margin-right:8px; width:155px">Update</button>
                    <a class="btn btn-success pull-left" href="<?=$configs['vpath']?>change-password" style="width:155px">Change Password</a>   
                </div>
                </div>
                
                 
            </form>
                    
                  </div>
            
             
               </div>
          </div>
            </div>
            </div>
            
            </div>
            
      </div>         
    </div>    
  