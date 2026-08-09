<?php 
include 'includes/header.php';
$error="";
if(isset($_POST['activation_code'])):

  #check if already activated
     $r=mysqli_query($con,"select * from " . $prev. "login where  activation_code='" .$_POST['activation_code'] . "' and activation_status='Y' and strcmp(\"" . md5($_POST['password']) . "\",password)=0 and  email='" . $_POST['email'] . "'");

  
      $n=mysqli_num_rows($r);
      if($n):  
          
        header("location:" . $vpath . "sign_in");
        exit;
      endif;

    #check if not activated
  $r=mysqli_query($con,"select * from " . $prev. "login where  activation_code='" .$_POST['activation_code'] . "' and activation_status='N' and strcmp(\"" . md5($_POST['password']) . "\",password)=0 and  email='" . $_POST['email'] . "'");

  echo "select * from " . $prev. "login where  activation_code='" .$_POST['activation_code'] . "' and activation_status='N' and strcmp(\"" . md5($_POST['password']) . "\",password)=0 and  email='" . $_POST['email'] . "'";
  $n=mysqli_num_rows($r);
  if($n):
     
        $d=mysqli_fetch_array($r);
        $_SESSION['user_id']      = $d["user_id"];
        $_SESSION['email']        = $d['email'];
        $_SESSION['mobile']       = $d['mobile'];
        $_SESSION['name']         = $d["fname"] ."".$_SESSION['lname'];
     
        
        $_SESSION['reg_date']   = $d['reg_date'];
       
            #update last login
            
        mysqli_query($con,"update " . $prev. "login set activation_status='Y', status='Y',l_date=\"" . date("Y-m-d") . "\" where user_id=" . $d['user_id']);


       
        
          
          header("location:" . $vpath . "dashboard");
   
  else:
         $error="<p>Invalid Activation<br></p>";
  endif;
endif;
?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Activation</h1></div>
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
  
  
    ?>    
      
            <form action="activation/<?php echo $_REQUEST['activation_code'];?>" id="form" method="post" enctype="multipart/form-data" >
              <input type='hidden' name='activation_code' value='<?php echo $_REQUEST['activation_code'];?>'> 
            
               
                <div class="form-group">
                  <label>Email ID</label>
                   <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email">
                </div>
                <div class="form-group">
                  <label>Password</label
                    <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                </div>
                
                <div class="form-group">
                <span class="error" style="color:#0E6938"></span><br />
              </div>

                <div class="form-group text-center">
                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
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