<?php 
include ('includes/header.php');
chklogin();
$data=array();$error="";
$success="";
 

?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>PAYMENT HISTORY</h1></div>
          </div>
</div>  

<!--courses section-->
  <div class="userprofile_wrapper">
      <div class="container">
        
            <div class="userprofile-body">
            <div class="col-lg-3 col-md-12">
            
                
                <?
                include("includes/left_dashboard.php");
                ?>
               
            </div>
            
            <div class="col-lg-9 col-md-12">
           
        

               <div class="panel dashbord-panel with-nav-tabs panel-default">
                  <div class="panel-heading">Payment History</div>
                  <div class="panel-body">


                     <p style='text-align:center;height:450px;vertical-align: middle;padding:100px'>Development on Progress</p>
                       
                  </div>
            
             
               </div>
          </div>
            </div>
            </div>
            
            </div>
            
      </div>         
    </div>    

<!--footer section-->
<?php 
include 'includes/footer.php';
?> 