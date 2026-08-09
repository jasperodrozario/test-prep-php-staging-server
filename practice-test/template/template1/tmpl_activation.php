 


 <hr class="topbar"/>
            <div class="container">
    <br />
    <div class="row" style="height: 450px;">

        <div class="col-sm-12">
            
            <div class="row">
             <div class="col-md-4">
               <!-- <h1>Activate account</h1> -->
            </div> 
            <div class="col-md-4 col-sm-4 col-xs-12">
               <!--  <div class="btn-div"> 
                        
                        <a href="https://www.facebook.com/sharer.php?u=<?=$configs['vpath']."product/".$list['product_slug'];?>" target="_blank" title="Share this page on facebook" class="pop share-icon share-icon-facebook"><i class="fa fa-facebook" aria-hidden="true"></i> Continue with Facebook</a>
                    </div> -->
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="btn-div"> 
                        <!-- <button data-toggle="modal" data-target="#make-offer" class="btn btn-warning social google" type="button"><i class="fa fa-google-plus" aria-hidden="true"></i>
                         Continue with Google</button> -->
                         <!--  <a href="https://plus.google.com/share?url=<?=$configs['vpath']."product/".$list['product_slug'];?>" target="_blank" title="Share this page on googleplus" class="pop share-icon share-icon-googleplus"><i class="fa fa-google-plus" aria-hidden="true"></i>
                         Continue with Google</a> -->

                    </div>
            </div>
        </div>

            
            <div class="row">
                <div class="col-md-3 col-sm-3">
                        </div>

                <div class="col-sm-6 col-md-6">
                     <h1 class="activation">Activate account</h1>
                    <hr class="hr-resister" />
                   <?php 
               if($wrong_msg)
          {
              echo  $wrong_msg;
               
          }
        
          ?>   


                 <!--<form action="<?=$configs['site_url']?>activation/<?php echo $_REQUEST['activation_code'];?>" id="activationform" method="post" enctype="multipart/form-data" >-->
                     
                     
                     <form method="post" enctype="multipart/form-data">
              <input type='hidden' name='activation_code' value='<?php echo $_REQUEST['activation_code'];?>'> 
            
               
                <div class="form-group">
                <span class="form-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                  <label>Email ID</label>
                   
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email">
                </div>
                <div class="form-group">
                <span class="form-icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                  <label>Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                </div>
                
                <div class="form-group">
                <span class="error" style="color:#0E6938"></span><br />
              </div>

                <div class="form-group text-center">
                    <button type="submit" name="submit" class="btn btn-info activation">Submit</button>
                </div>
                
                
            </form>

                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>

                    <!--     <div class="col-md-6 col-sm-12 account-sidebar hidden-sm">

                            <div class="row">
                                <div class="col-sm-3" style="text-align: center;">
                                    <img src="<?=$configs['template']?>images/icons/Crest.png" width="50"/>
                                </div>
                                <div class="col-sm-8">
                                    <h3>Why us?</h3>
                                    <p>We're one of the most recognisable brands, attracting thousands of buyers every month.<p>
                                    </div>
                            </div>
                            <br />

                            <div class="row">
                                <div class="col-sm-3" style="text-align: center;">
                                    <img src="<?=$configs['template']?>images/icons/Pie-Chart.png" width="40"/>
                                </div>
                                <div class="col-sm-8">
                                    <h3>Magnet for buyers</h3>
                                    <p>We make sure your listings receive maximum exposure and is presented in an engaging way</p>
                                </div>
                            </div>
                            <br />

                            <div class="row">
                                <div class="col-sm-3" style="text-align: center;">
                                    <img src="<?=$configs['template']?>images/icons/Search.png" width="40"/>
                                </div>
                                <div class="col-sm-8">
                                    <h3>Focused searches</h3>
                                    <p>Our technology and algorithm matches potential buyers directly to your listings</p>
                                </div>
                            </div>
                            <br />
                          <div class="row">
                                <div class="col-sm-3" style="text-align: center;">
                                    <img src="<?=$configs['template']?>images/icons/Telephone.png" width="40"/>
                                </div>
                                <div class="col-sm-8">
                                    <h3>Mobile web</h3>
                                    <p>Your listings will always be accessible to everyone, even when they are on the move, via our responsive mobile website</p>
                                </div>
                            </div> 

                        </div> -->

                    </div>
                    <br />







        </div>
    </div>
</div>




</div>
</div>
