
	<form name="register" method="post"  action="<?php echo BASENAME($_SERVER['SCRIPT_NAME'],".php");?>"   enctype="multipart/form-data" >

    <table id="tableListContent" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="1" cellpadding="4">
 
    <tr >

    	<td width="25%"><b>Admin Email :</b></td>

        <td ><input type=text class='form-control' name='admin_mail' value="<?php echo $db_value['admin_mail'];?>" > 

      </td>

    </tr>
   
    
     <tr >

      <td width="25%"><b>Site Contact Number :</b></td>

        <td ><input type=text class='form-control' name='admin_contact' value="<?php echo $db_value['admin_contact'];?>" > 

      </td>

    </tr>

    <tr >

    	<td width="25%"><b>Support Email :</b></td>

        <td ><input type=text class='form-control' name='support_mail' value="<?php echo $db_value['support_mail']?>" > 

      </td>

    </tr>
    <tr >

    	<td width="25%"><b>Payment Email :</b></td>

        <td ><input type=text class='form-control' name='paypal_mail' value="<?php echo $db_value['paypal_mail']?>" > 

      </td>

    </tr>
   
   <tr >
  	   <td width="25%"><b>Logo :</b></td>

        <td >
        
        <?php if(file_exists($configs['apath'].$db_value['logo']))
        {
            echo"<img src='" . $configs['vpath']. "" . $db_value['logo']. "' width=100 height=100><br>";
        }
        ?>
        <input type='file' class='form-control' name='logo' value="<?php echo $db_value['logo']?>" ><br>
        [file type should be .png, max size:250X100]
      </td>

    </tr>
    <tr >
  	   <td width="25%"><b>Favicon :</b></td>

        <td >
        <?php if(file_exists($configs['apath'].$db_value['favicon']))
        {
            echo"<img src='" . $configs['vpath']. "" . $db_value['favicon']. "' width=30 height=30><br>";
        }
        ?>
        <input type='file' class='form-control' name='favicon' value="<?php echo $db_value['favicon']?>" ><br>[picture muct be .png file,Size:16px x 16px]

      </td>

    </tr>

	 
 
    <tr >

    	<td width="25%"><b>Twitter URL :</b></td>

        <td ><input type=text class='form-control' name=twitter_url value="<?php echo $db_value['twitter_url'];?>" > </td>

    </tr>

    <tr >

    	<td width="25%"><b>Facebook URL :</b></td>

        <td ><input type=text class='form-control' name=facebook_url value="<?php echo $db_value['facebook_url'];?>" > </td>

    </tr>
 

	   <tr >

    	<td width="25%"><b>Linkedin Url :</b></td>

        <td ><input type=text class='form-control' name=linkedin_url value="<?php echo $db_value['linkedin_url'];?>" > 

   </td>

    </tr>

    <tr >

        <td width="25%"><b>Contact Details : </b></td>

        <td ><textarea name="contact_details" class="form-control" rows="4" ><?php echo html_entity_decode($db_value['contact_details']);?></textarea><br></td>

    </tr>

  <!--  <tr >

      <td width="25%">1 Month Subscription Charges:</td>

        <td ><input type='number' class='form-control' name='1month' value="<?php echo (int)$db_value['1month'];?>" > </td>

      </tr> 

      <tr >

      <td width="25%">3 Months Subscription Charges:</td>

        <td ><input type='number' class='form-control' name='3month' value="<?php echo (int)$db_value['3month'];?>" > </td>

      </tr> 
      <tr >

      <td width="25%">6 Month Subscription Charges:</td>

        <td ><input type='number' class='form-control' name='6month' value="<?php echo (int)$db_value['6month'];?>" > </td>

      </tr> 
      <tr >

      <td width="25%">12 Months Subscription Charges:</td>

        <td ><input type='number' class='form-control' name='12month' value="<?php echo (int)$db_value['12month'];?>" > </td>

      </tr> -->
        
      <tr >

      <td width="25%"><b>Referral Discount %: </b></td>

        <td ><input type='number' placeholder="Referral Discount" name="ref_discount" class="form-control"  value='<?=$db_value['ref_discount'];?>'></td>

    </tr>
    <tr >

      <td width="25%"><b>Referral Commission : </b></td>

        <td ><input type='number' placeholder="Referral Commission" name="ref_commission" class="form-control"  value='<?=$db_value['ref_commission'];?>'></td>

    </tr>
    
    <tr>

        <td valign=top width="25%"><b>Auto Approve Regitrationn :</b></td><td ><input type='checkbox' name='auto_approve_member' value=1 <?if($db_value['auto_approve_member']){echo "checked";}?></td>

    </tr>
    <tr>

        <td valign=top width="25%"><b>Subscription Type :</b></td><td ><input type='radio' name='subscription_type' value="P" <?if($db_value['subscription_type']=="P"){echo "checked";}?> > Paid <input type='radio' name='subscription_type' value="F" <?if($db_value['subscription_type']=="F"){echo "checked";}?> > Free</td>

    </tr>
 
    <tr ><td></td><td height=25><input type='submit' class="btn btn-success" name='submit' value='Update'></td></tr>

   </table>
 </form>


 

