<?include("includes/header.php");
checkLogin();
if($_REQUEST['course_id']):
    $data=getDataByWhere("course","*","course_id=" .$_REQUEST['course_id']);
else:
    $data=$_POST;
endif;    
if(isset($_POST['pay'])):
    ?>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" name='pay'>
    <input type="hidden" name="cmd" value="_xclick" />
    <input type="hidden" name="business" value="<?=$setting['paypal'];?>" />
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="amount" value="<?php echo $_POST['amount']; ?>">
    <input type="hidden" name="custom" value="<?=$_SESSION['user_id'];?>">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="<?=$setting['currency'];?>" />
    <input type="hidden" name="first_name" value="<?=$_SESSION['name']?>">
    <input type="hidden" name="last_name" value="<?=$_SESSION['name']?>">
    <input type="hidden" name="email" value="<?=$_SESSION['email']?>">
    <input type="hidden" name="item_name" value="Membership" />
    <input type="hidden" name="notify_url" value="<?=$vpath;?>notify.php" />
    <input type="hidden" name="cancel_return" value="<?=$vpath;?>dashboard">
    <input type="hidden" name="return" value="<?=$vpath;?>thank-you" /> 
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="cbt" value="Return to The Tendor.com">
    </form>
    <script>document.forms['pay'].submit();</script>
    <?
endif;
      
   

?>
        <section id="page-content">
            <!--Start Page Heading-->
       
             
            <div class="inner-body userbody">
             <div class="container">
               <div class="row">
               
                   <div class="custbread bread-area">
                   
            <ol class="breadcrumb">
            <li><a href="<?=$vpath?>index.php" class="icofont icofont-home"></a></li>
                <li><a href="#">Membership</a></li>
            </ol>
            
            <div class="notifi">
            <!--<div class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
              <i class="icofont icofont-notification"></i> 
              <span class="notification-red">6</span>
              </a>
              
              </div>-->
            </div>
           </div>
					
               </div>
             </div>
             
            <div  class="user-body-con">
            	<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="vertical-tab" role="tabpanel">
               <div class="row">
                <!-- Nav tabs -->
                <div class="col-md-3 paddingl0">
                <div class="dashbord-sidebar">
                  <?include("includes/left_dashboard.php");?>
            
                </div>
                </div>
                <!-- Tab panes -->
                <div class="col-md-9 paddingr0">
                <div class="tab-content tabs">
                 <div role="tabpanel" class="tab-pane fade in active" id="Section1">
                        <div class="right-sechead"><h3>Membership</h3></div>
                        <div class="dashbord-right-con">
                  
                  <div class="tender-list-itembody">
                   
                        <form method='post' id="minform" action="" enctype="multipart/form-data" >
                        <input type='hidden' name='amount' value='<?=$setting['membership_charges'];?>'>
                         <input type='hidden' name='currency' value='<?=$setting['currency'];?>'>

                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="4"   class="table table-bordered">
                        
                        <tr ><td width=23%>Charges Per Year</td><td><?=$setting['currency_symbol'] .$setting['membership_charges'] . " ". $setting['currency'];?></td></tr>
                        <tr ><td valign="top" >Select Payment Gateway</td><td > <p><input type='radio' name='gateway' value='paypal' required> Paypal <img src='img/paypal_logo.gif'></p>
                        <p><input type='radio' name='gateway' value='visa' required> <img src='img/payment-gate.png'></p></td></tr>
                        <tr ><td valign="top" ></td><td ><input type='checkbox' name='term' value=1 required> I agree with Terms & Privacy Policy</td></tr>
                        <tr  ><td ></td><td height="20" ><input type='submit'  name='pay' value='Upgrade to Primium' class='btn btn-success' > <input type='button'   value='Cancel' onClick="javascript:history.back();" class='btn btn-danger' ></td></tr>
                 
                       </table>
                     
                        </form>
                        </div>
                        
                       </div>
                 
               </div>
                    </div>
             
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
            
            </div>
             
           </div>
        </section>
        
<?include("includes/footer.php");?>