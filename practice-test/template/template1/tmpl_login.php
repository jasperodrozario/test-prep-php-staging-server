 <!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Sign In</h1></div>
    </div>
</div> 
 
<!--about section-->
<div class="about_wrappers" >
       
  <div class="inner-body">

      <div class="container">
		<div class="register-body">
      		<div id='login-response'></div> 
      
            <form  id="loginForm1" method="post" enctype="multipart/form-data" >
                <input type='hidden' name='action' value='login'>
                <input type='hidden' name='referer' value='<?=$_GET['referer'];?>'>
                
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <input type="email" class="form-control"   placeholder="Email" name="email" required>
                </div>
                <div class="form-group">
                    <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="password" id="pass" placeholder="Password" required>
                </div>
                 
              
              
                <div class="form-group text-center">
                <a  href='<?=$configs['vpath']?>forgot-pass' style='color:#111'>Forgot Password</a><br>
                    
                    <button type="submit" name="submit" class="btn btn-success text-center">Sign In</button></center>
                </div>
                
                
            </form>
                      
        </div>	
      </div>
    </div>        
     
</div>
 