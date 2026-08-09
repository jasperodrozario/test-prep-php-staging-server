<?php 
include ('includes/header.php');
chklogin();
 
//$total_tender=getTotalRow("tender",array("user_id"=>$_SESSION['user_id']));
//$total_tender_open=getTotalRow("tender",array("user_id"=>$_SESSION['user_id'],"status"=>"Y"));
//$total_tender_close=getTotalRow("tender",array("user_id"=>$_SESSION['user_id'],"status"=>"C"));

?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>MY DASHBOARD</h1></div>
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
                  <div class="panel-heading">Dashbord</div>
                  <div class="panel-body panel-body1">
                  <!-----Start dashbard box-->

                  
                   <div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
     					<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='course-enrolled'><img class="img-responsive" src="images/icon/mortarboard.png" /></a>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <h4 class="mb-0">
                            <span class="count">10</span>
                        </h4></div>
                        <p class="text-light"><a href='course-enrolled'>Total Courses Enrolled</a></p>            
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5 download-box">
                    <div class="card-body pb-0">
                      <div class="col-md-12 text-center">
                        <div class="icon-image">
                       <a href='download'> <img class="img-responsive" src="images/icon/cloud-storage-download.png" /></a>
                        </div>
                        </div>
                        <!--<div class="col-md-6">
                        <h4 class="mb-0">
                            <span class="count">10</span>
                        </h4>
                        </div>-->
                        <p class="text-light"><a href='downoad'>Download</a></p>            
                    </div>
                    

                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                     
						<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='favorite-course'><img class="img-responsive" src="images/icon/graduation-hat-and-diploma.png" /></a>
                        </div>
                        </div>
                        
                        <p class="text-light"><a href='favorite-course'>Favorite Course</a></p>
              <div class="chart-wrapper px-0" style="height:70px;" height="70">
                            
                        </div>
                    </div>

                </div>
            </div>
           
           <div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                     
					<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='change-password'><img class="img-responsive" src="images/icon/password.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='change-password'>Change Password</a></p>
              
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                    <div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='edit-profile'><img class="img-responsive" src="images/icon/user.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='edit-profile'>Edit Profile</a></p>
             
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                      
						<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <img class="img-responsive" src="images/icon/round-account-button-with-user-inside.png" />
                        </div>
                        </div>
                        <p class="text-light">Close Account</p>
              <div class="chart-wrapper px-0" style="height:70px;" height="70">
                            
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                      
						<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='payment'><img class="img-responsive" src="images/icon/credit-cards-payment.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='pay'>Payment</a></p>
              <div class="chart-wrapper px-0" style="height:70px;" height="70">
                            
                        </div>
                    </div>

                </div>
            </div>
<div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                     
					<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <img class="img-responsive" src="images/icon/payment-history.png" />
                        </div>
                        </div>
                        <p class="text-light"><a href='payment-history'>Payment History</a></p>
              
                    </div>

                </div>
            </div>
<div class="col-sm-6 col-lg-4">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                     
                      <div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='notification'><img class="img-responsive" src="images/icon/laptop.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='notification'>Notification</p>
              <div class="chart-wrapper px-0" style="height:70px;" height="70"></a>
                            
                        </div>
                    </div>

                </div>
            </div>


 <!-----end dashbard box-->
                           
                        </div>
                        
                    </div>
               
           
    
                <div class="col-md-12 padding0 related-post">
                <h2> Newly Added Courses</h2>
                <div class="row">
                <div id="news-slider1" class="owl-carousel">
                
                 <?

                 $datas=getQuery("course","course_id,thumb_pic,name,details,cost,cat_id,duration","featured='Y'","course_id desc","12");
                 foreach($datas as $data)
                 {

                 ?>
                        <div class="single-courses-box">
                          <div class="single-courses">
                          <figure>
                            <div class="figure-img"><img src="image.php?image=<?=$vpath.$data['thumb_pic']?>&width=240&height=135&cropratio=2:1" alt="<?=$data['name']?>" alt="" class="img-responsive"></div>
                            <figcaption>
                              <div><a href="<?=$vpath;?>course/<?php echo $data['course_id']. "/" . seo_url($data['name']); ?>" class="read_more-btn">Apply Now</a></div>
                            </figcaption>
                          </figure>
                          <div class="courses-content-box">
                            <div class="courses-content">
                              <h3><a href="<?=$vpath;?>course/<?php echo $data['course_id']. "/" . seo_url($data['name']); ?>"> English Online Course</a></h3>
                              <div class="rank-icons">
                                <ul class="list-unstyled">
                                  <li><i class="fa fa-star"></i></li>
                                  <li><i class="fa fa-star"></i></li>
                                  <li><i class="fa fa-star"></i></li>
                                  <li><i class="fa fa-star"></i></li>
                                  <li><i class="fa fa-star"></i></li>                 
                                </ul>
                                <span>20 Reviews</span>
                              </div>  
                              
                            </div>
                            <div class="courses-content-bottom">
                              <ul class="list-unstyled">
                                <li><i class="fa fa-user"></i> 80</li>
                                <li><i class="fa fa-heart"></i> 35</li>
                                <li><span>$90</span></li>
                              </ul>
                            </div>
                          </div>
                        </div><!-- Ends: .single courses -->
                      </div>
                  <?}?>
            
                  
           
            
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