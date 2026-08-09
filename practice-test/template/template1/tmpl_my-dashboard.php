
<!--banner section-->
<!--<div class="banner_wrapper">
 
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>MY DASHBOARD</h1></div>
          </div>
</div> -->

<!--courses section-->
  <div class="userprofile_wrapper">
      <div class="container">
        
            <div class="userprofile-body midle-conarea mr-t88">
<!--
            <div class="col-lg-3 col-md-12">
            <?
//            include("../template/" .$configs['template'] ."/includes/tmpl_left_dashboard.php");
            ?> 
            </div>
-->
            
          <div class="col-lg-12 col-md-12">
           
               <div class="panel dashbord-panel with-nav-tabs panel-default border-none">
                  <div class="panel-heading">Dashboard</div>
                  <div class="panel-body">
                  <!-----Start dashbard box-->
					<div class="row">
           

            <div class="col-sm-6 col-lg-6">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                    <div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='<?=$configs['vpath']?>edit-profile'><img class="img-responsive" src="images/icon/user.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='<?=$configs['vpath']?>edit-profile'>Edit Profile</a></p>
             
                    </div>

                </div>
            </div> 
         
            <div class="col-sm-6 col-lg-6">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                      
						<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='<?=$configs['vpath'];?>exam/<?=$_SESSION['test_id'] ."-" . seo_url($_SESSION['test_name']);?>'><img class="img-responsive" src="images/icon/round-account-button-with-user-inside.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='<?=$configs['vpath'];?>exam/<?=$_SESSION['test_id'] ."-" . seo_url($_SESSION['test_name']);?>'>Online Exam</p>
              			<div class="chart-wrapper px-0" style="height:70px;" height="70">
                            
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-lg-6">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                      
						<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='<?=$configs['vpath'];?>exam/progress-report'><img class="img-responsive" src="images/icon/credit-cards-payment.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='<?=$configs['vpath'];?>exam/progress-report'>Progress Report</a></p>
              			<div class="chart-wrapper px-0" style="height:70px;" height="70">
                            
                        </div>
                    </div>

                </div>
            </div>
            <!--
            <div class="col-sm-6 col-lg-6">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                      
						<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='<?=$configs['vpath'];?>exam/<?=$_SESSION['test_id']?>-70-final-practice-test'><img class="img-responsive" src="images/icon/credit-cards-payment.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='<?=$configs['vpath'];?>exam/<?=$_SESSION['test_id']?>-70-final-practice-test'>Final Test</a></p>
              			<div class="chart-wrapper px-0" style="height:70px;" height="70">
                            
                        </div>
                    </div>

                </div>
            </div>


			<div class="col-sm-6 col-lg-6">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                     
                      <div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='<?=$configs['vpath'];?>exam/<?=$_SESSION['test_id']?>-prediction-test'><img class="img-responsive" src="images/icon/laptop.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='<?=$configs['vpath'];?>exam/<?=$_SESSION['test_id']?>-prediction-test'>AFQT Prediction</a></p>
             
                    </div>

                </div>
            </div>-->
             <div class="col-sm-6 col-lg-6">
                <div class="card desh-top-item bg-flat-color-5">
                    <div class="card-body pb-0">
                     
					<div class="col-md-12 text-center">
                        <div class="icon-image">
                        <a href='<?=$configs['vpath'];?>change-password'><img class="img-responsive" src="images/icon/password.png" /></a>
                        </div>
                        </div>
                        <p class="text-light"><a href='<?=$configs['vpath'];?>change-password'>Change Password</a></p>
              
                    </div>

                </div>
            </div>

			</div>


 <!-----end dashbard box-->
                           
                        </div>
                        
                    </div>
               
           
    
               
            </div>
            
            </div>
            
      </div>         
    </div>    
