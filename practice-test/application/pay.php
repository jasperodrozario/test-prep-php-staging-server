<?php 
include 'includes/header.php';
if($_REQUEST['course_id']):
    $data=getDataByWhere("course","*","course_id=" .$_REQUEST['course_id']);
else:
    $data=$_POST;
endif;    
if(isset($_POST['pay'])):
    ?>
    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name='pay'>
    <input type="hidden" name="cmd" value="_xclick" />
    <input type="hidden" name="business" value="<?=$setting['paypal'];?>" />
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="amount" value="<?php echo $_POST['total_amt']; ?>">
    <input type="hidden" name="custom" value="<?=$_SESSION['user_id'];?>">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="<?=$setting['currency'];?>" />
    <input type="hidden" name="first_name" value="<?=$_SESSION['name']?>">
    <input type="hidden" name="last_name" value="<?=$_SESSION['name']?>">
    <input type="hidden" name="email" value="<?=$_SESSION['email']?>">
    <input type="hidden" name="item_name" value="<?=$data['name'];?>" />
    <input type="hidden" name="notify_url" value="<?=$vpath;?>notify.php" />
    <input type="hidden" name="cancel_return" value="<?=$vpath;?>course-info/<?=$_REQUEST['course_id']?>/<?=seo_url($data['name']);?>">
    <input type="hidden" name="return" value="<?=$vpath;?>thank_you" /> 
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="cbt" value="Return to The Store">
    </form>
    <script>document.forms['pay'].submit();</script>
    <?
endif;
      
?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Payment For</h1><h3 style='color:#fff'><?=$data['name']?></h3></div>
  </div>
</div>     

<!--contact section-->
  <div class="contact_wrapper">
        <div class="container">
            <div class="contact_wrap">
              
              <div class="col-lg-8 col-sm-8">
                    <div class="contact_box">

                    
                        <div class="contact_heading">
                            <h2>Payment </h2>
                        </div>
                        <div class="contact_text">
                         

                            <form method='post' id="minform" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" >
                            <?if($data['cost']):?>   
                                <input type='hidden' name='total_amt' value="<?=$data['cost'];?>">
                                <input type='hidden' name='course_id' value="<?=$_REQUEST['course_id'];?>">
                            <?endif;?>
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="4"   class="table table-borderless">
                            <?if($data['cost']):?>   
                            <tr ><td width=35%>Charges For Course</td><td><?=$setting['currency']?><?=$data['cost']?></td></tr>
                            <?else:?>
                            
                                 <tr ><td width=35%>Payment  For Course</td><td><input type='text' name='item_name' value="" class='form-control' required></td></tr>

                                <tr ><td width=35%>Payment  Amount [Rs.]</td><td><input type='text' name='total_amt' value="" class='form-control' required></td></tr>
                            <?endif;?>

                            <tr ><td valign="top" >Select Payment Gateway</td><td > <p><input type='radio' name='gateway' value='paypal'> Paypal <img src='img/paypal_logo.gif'></p>
                            <p><input type='radio' name='gateway' value='visa' disabled> <img src='img/payment-gate.png'></p></td></tr>
                            <tr ><td valign="top" ></td><td ><input type='checkbox' name='term' value=1 required> I agree with <a href='<?=$vpath?>info/terms-of-use' target='_new' style='color:#111'>Terms & Privacy Policy</a></td></tr>
                            <tr  ><td ></td><td height="20" ><input type='submit'  name='pay' value='Pay' class='cust-btn' >  </td></tr>
                     
                           </table>
                   
                      </form>
                        </div>
                         <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <div class="contact_box">
                        <div class="contact_heading">
                            <h2>Call us For Payment Issue</h2>
                        </div>
                        <div class="contact_text">
                             
                       
                            <p>Address: <?php echo $setting['contact_details']; ?></p>
                            <p>Phone: <?php echo $setting['admin_contact']; ?></p>
              
                            <!-- <p>Email: <?php echo $setting['admin_mail']; ?>p>-->
                            <ul class="list">
                                <li><a href="#"><i class="fa fa-envelope"></i><!-- education.co.in --><?php echo $setting['admin_mail']; ?></a></li>
                              

                            </ul>    
                        </div>
                         
                    </div>
                </div>
 
              <div class="clearfix"></div>
            </div>
        </div>
    </div>

<!--footer section-->

<?php 
include 'includes/footer.php';
?> 