<?php 

include 'includes/header.php';
?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Register</h1></div>
          </div>
</div>    

<!--about section-->
<div class="about_wrappers" >          
  <div class="inner-body">
      <div class="container">
		<div class="register-body">
     
            <form action=""  method="post">
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="First Name" required="">
                </div>
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Last Name">
                </div>
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                </div>
                <div class="form-group">
                    <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="confpass" id="confpass" required placeholder="Repeat Password">
                </div>
               
                <div class="form-group text-center">
                    <button type="submit" name="submit" class="btn btn-default">Sign up</button>
                </div>
                
                <div class="form-footer">
                 <div class="form-footer-con">
                   <div class="line-heading">Already a member? </div>
                   <a class="button" href="#">Sign In</a>
                  
                  </div>
                  
                  <div class="form-group fb-login">
                                    <div class="fb-loginbtn">
                                      <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a>
                                    </div>
                                </div>
                 </div>
            </form>
                      
        </div>	
      </div>
    </div>        
     
</div>

<!--footer section-->
<?php 

include 'includes/footer.php';
?>