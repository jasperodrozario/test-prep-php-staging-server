<div class="userprofile_wrapper">
      <div class="container">
        
            <div class="userprofile-body midle-conarea mr-t88">
<!--            <div class="col-lg-3 col-md-12">-->
             
                <?
//                include("includes/tmpl_left_dashboard.php");
                ?>
               
<!--            </div>-->
            
            <div class="col-lg-12 col-md-12">
            
               <div class="panel dashbord-panel with-nav-tabs panel-default">
                  <div class="panel-heading">&nbsp; Change Password</div>
                  <div class="panel-body">


            				 <?
                            if($msg){echo "<p align=center>" .$msg."</p>";}
                           
                            ?>
                
                <form class="form-horizontal" id="change_pass_form" method='post' >
           
                        <input type='hidden' name='action' value='change-password'>
                           
                        <div class="form-group">
                           <div class='col-md-3'><label>Old password</label></div>

                            <div class="col-sm-9 password-container">
                               
                                <input class="form-control" id="old_password" name='old_password' placeholder="Old Password" type="password" required>
                                
                                <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                                
                            </div>
                        </div>
                        <div class="form-group">  
                            <div class='col-md-3'><label>New password</label></div>
                            <div class="col-sm-9 password-container">
                                <input class="form-control" id="new_password" name='new_password' placeholder="New Password" type="password" required>
                                <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                            </div>
                        </div>

                          
                            <div class="form-group">  
                            <div class='col-md-3'><label>Confirm password</label></div>
                            <div class="col-sm-9 password-container">
                                <input class="form-control" name='conf_password' id="conf_password" placeholder="Confirm Password" type="password" required>
                                <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                            </div>
                        </div>
                       <div class="form-group">
                         <div class='col-md-3'></div>
                         <div class="col-sm-9">
                            <button type="submit" name='SBMT' class="btn btn-default"> Change Password</button>
                        </div>
                    
                </form>

                           
                       
                  </div>
            
             
               </div>
          </div>
            </div>
            </div>
            
            </div>
            
      </div>  

<style>
    .password-container {
      position: relative;
    }
    .password-container i {
      position: absolute;
      top: 50%;
      right: 5%;
      transform: translateY(-50%);
      cursor: pointer;
    }
    #change_pass_response {
        display: none;
        padding: 10px; /* Add some padding for better spacing */
        border-radius: 5px; /* Rounded corners */
/*        margin-top: 8px;   */
    }
  </style>


<script>
function togglePasswordVisibility(eyeIcon) {
  const passwordInput = eyeIcon.previousElementSibling; // Get the input element before the clicked icon

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    eyeIcon.classList.remove("fa-eye");
    eyeIcon.classList.add("fa-eye-slash");
  } else {
    passwordInput.type = "password";
    eyeIcon.classList.remove("fa-eye-slash");
    eyeIcon.classList.add("fa-eye");
  }
}
</script>
 