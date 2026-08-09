
<?php
include("../configs/autoload.php");
$data['meta_title']="Renew Subscription";

if(isset($_POST['renew']))
{
   
 ?>
 	       <form action="https://sandbox.paypal.com/cgi-bin/webscr" method="post" name='pay'>
			<input type="hidden" name="cmd" value="_xclick" />
			<input type="hidden" name="business" value="<?=$setting['paypal_mail'];?>" />
		 	<input type="hidden" name="amount" value="<?=$amount;?>">
			<input type="hidden" name="custom" value="<?=$_SESSION['user_id']?>,<?=$_SESSION['test_id'];?>,<?=$_POST['subscription'];?>,<?=$_POST['referral_id'];?>,<?=$setting[$_POST['subscription']];?>,renew">
			<input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="notify_url" value="<?=$configs['vpath']?>notify" />
		 
			<input type="hidden" name="currency_code" value="USD" />
			<input type="hidden" name="first_name" value="<?=$_SESSION['name']?>">
			<input type="hidden" name="last_name" value="<?=$_SESSION['name']?>">
			<input type="hidden" name="email" value="<?=$_SESSION['email']?>">
			<input type="hidden" name="item_name" value="<?=$data['course_array'][$_SESSION['test_id']]?>" />
			
			<input type="hidden" name="cancel_return" value="<?=$configs['vpath'];?>">
			<input type="hidden" name="return" value="<?=$configs['vpath'];?>thank-you" /> 
		 
			</form> 
           
		     <script>document.forms['pay'].submit();</script> 
        <?
 
}
	 
 
echo template($data);
?>










 