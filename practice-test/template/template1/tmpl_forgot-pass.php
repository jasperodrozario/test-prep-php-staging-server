 <!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Forgot Password</h1></div>
    </div>
</div> 
 
<!--about section-->
<div class="about_wrappers" >
       
  <div class="inner-body">

      <div class="container">
		<div class="register-body">
      		<div id='responseforgot'></div>
      	    <div id='response-success'></div> 
            <div id='response-error'></div> 
      
            <form  id="forgotForm11" method="post" enctype="multipart/form-data" >
                <input type='hidden' name='action' value='forgot-password'>
                
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <input type="email" class="form-control"   placeholder="Enter registered email id" name="email" required>
                </div>
              
                <div class="form-group text-center"><button type="submit" name="submit" class="btn btn-success">Submit</button></div>
                
                <div class="form-footer text-center">
                    <a href='<?=$configs['vpath']?>register' style='color:#111'>Are you new memeber? Register here.</a>
                </div>
            </form>
                      
        </div>	
      </div>
    </div>        
     
</div>
 