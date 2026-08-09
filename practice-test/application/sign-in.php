<?php 
include ('includes/header.php');

 
if(isset($_POST['SBMT'])):
    $r=mysqli_query($con,"select * from " . $prev. "login where  strcmp(\"" . md5($_POST['password']) . "\",password)=0 and  email='" . $_POST['email'] . "'  and status='Y'");
 
    $n=mysqli_num_rows($r);
    if($n):
        $d=mysqli_fetch_array($r);
        $_SESSION['user_id']      = $d["user_id"];
        $_SESSION['email']        = $d['email'];
        $_SESSION['mobile']       = $d['mobile'];
        $_SESSION['name']         = $d["fname"] . " " .$d["lname"];
       
        $_SESSION['reg_date']   = $d['reg_date'];
        $_SESSION['user_type']    = $d['user_type'];  

        /*if(!$d['profile_pic']):
          $_SESSION['profile_pic']='user_pic/no_pic.png';
        else:
          $_SESSION['profile_pic']=$d['profile_pic'];
        endif;
        */
        #update last login
        mysqli_query($con,"update " . $prev. "login set last_login=\"" . date("Y-m-d") . "\" where user_id=" . $d['user_id']);
         
        header("location:" . $vpath  . "my-dashboard");
   
    else:
        $msg="Invalid password.";
        $r=mysqli_query($con,"select * from " . $prev. "login where  strcmp(\"" . $_POST['password'] . "\",password)=0 and  email='" . $_POST['email'] . "'");
        if(@mysqli_num_rows($r)):
            $d=mysqli_fetch_array($r);
            $status=$d["status"];
            if($status=="D"):
                $msg="<p>Your_account_has_been_deactivated.<br><br><a href=\"contactus\" ><u>Contact to Admin</u></a></p>";
            endif;
        endif;
    endif;
endif;
?>   

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
      <?php if($error)
    {
        echo  $error;
         
    }
    if($msg)
    {
        echo $msg; 
    }
  
    ?>    
      
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" id="form" method="post" enctype="multipart/form-data" >
               
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email">
                </div>
                <div class="form-group">
                    <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" required>
                </div>
                 
                <div class="form-group">
                <span class="error" style="color:#0E6938"></span><br />
              </div>

                <div class="form-group text-center">
                    <button type="submit" name="submit" class="btn btn-default">Sign In</button>
                </div>
                
                <div class="form-footer">
                  
                  
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

include('includes/footer.php');
?>