<?php 
include 'includes/header.php';
include("smtpmail.php");  
if(isset($_POST['submit']))
{
    $error="";


    $n=mysqli_num_rows(mysqli_query($con,"select * from ".$prev."login where email='".$_POST['email']."'"));



    if($n)
    {
    
       
         $error='<div class="alert alert-warning alert-dismissible fade in">
        <a href="#" class="close reg-close" data-dismiss="alert" aria-label="close">&times;</a>
        <h4>This email id already exits!. For login <a class="here" href="'.$vpath.'login">click here</a>.</h4> 
        </div>';
    }
    if(!$error)
    {
          
          $activation_code=time();
          $r=mysqli_query($con,"insert into ".$prev."login set
                    fname    =\"".$_POST['fname'] . "\",
                    lname    =\"".$_POST['lname'] . "\",
                    email   =\"".$_POST['email']."\",
                    status  =\"N\",
                    password  =\"".md5($_POST['pass'])."\",activation_code=\"" . $activation_code . "\",activation_status=\"N\",                    
                    
                    reg_date=\"" .date("Y-m-d") . "\"");


          
           $msg='<div class="alert alert-warning alert-dismissible fade in">
          <a href="#" class="close reg-close" data-dismiss="alert" aria-label="close">&times;</a>
         <h4>You are successfully registerted in '.$dotcom.' For login <a class="here" href="'.$vpath.'login">click here</a>.</h4> 
        </div>';
        
        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Register"));
        $body=html_entity_decode($mail['template']);
        $subject=$mail['subject'];

         
        $mail_body="Thanks for registration.<br>Your Login Details:<br>Login:" . $_POST['email']."<br>Password:" . $_POST['pass']."<br>Please activate the account.{activation}<b><br>Thanks<br>".ucwords($dotcom);
        $subject="Thanks for registration";
       
          $activation_code="<a href='" . $vpath . "activation/" . $activation_code . "' target='_new'>Activate</a>";


        $mail_body=str_replace("{name}",$_POST['fname'] ." ". $_POST['lname'],$body);
        $mail_body=str_replace("{activation_code}",$activation_code,$mail_body);
        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
        $mail_body=str_replace("{password}",$_POST['password'],$mail_body);
        $mail_body=html_entity_decode($mail_body);
     
             
        smtpmailer($_POST['email'],$setting['support_mail'], ucwords($dotcom),$subject, $mail_body);  
        smtpmailer($setting['admin_mail'],$setting['support_mail'], $dotcom,$subject, $mail_body);  

     }
  }
     
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
                   <span class="form-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="First Name" required="" name="fname">
                </div>
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Last Name" name="lname">
                </div>
                <div class="form-group">
                   <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email">
                </div>
                <div class="form-group">
                    <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="confpass" id="confpass" required placeholder="Repeat Password">
                </div>
                <div class="form-group">
                <span class="error" style="color:#0E6938"></span><br />
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