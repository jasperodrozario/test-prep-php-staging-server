<?include($configs['admin_template_include']."includes/tmpl_breadcrumb.php");?> 

<form id="form" method="post"   enctype="multipart/form-data" >
<input type='hidden' name='id'  value='1'>
<input type='hidden' name='action'  value='site-setting'> 
 
<table id="tableListContent" width="100%" class="table  table-bordered" border="0" align="center" cellspacing="1" cellpadding="4">

<tr >

  <td valign=top width=20%>Meta Title :</td>

    <td ><input type=text class='form-control' name='meta_title' value="<?php echo $db_value['meta_title']?>" > 

</tr>

<tr  >

  <td valign=top width="25%">Meta Keys :</td>

    <td ><textarea name='meta_keys' cols=70 rows=5 class='form-control' ><?php echo $db_value['meta_keys']?></textarea></td>

</tr>

<tr  >

  <td valign=top width="25%">Meta Description :</td>

    <td ><textarea name='meta_desc' cols=70 rows=5 class='form-control' ><?php echo $db_value['meta_desc']?></textarea></td>

</tr>

<tr >

  <td width="25%">Admin Email :</td>

    <td ><input type=text class='form-control' name='admin_mail' value="<?php echo $db_value['admin_mail'];?>" > 

  </td>

</tr>

 <tr >

  <td width="25%">WebSite Contact Number :</td>

    <td ><input type=text class='form-control' name='admin_contact' value="<?php echo $db_value['admin_contact'];?>" > 

  </td>

</tr>

<tr >

  <td width="25%">Support Email :</td>

    <td ><input type=text class='form-control' name='support_mail' value="<?php echo $db_value['support_mail']?>" > 

  </td>

</tr>

<tr >

  <td width="25%">Tollfree Number:</td>

    <td ><input type=text class='form-control' name='tollfree_number' value="<?php echo $db_value['tollfree_number'];?>" > 

  </td>

</tr>

<tr >
   <td width="25%">Logo :</td>

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
   <td width="25%">Favicon :</td>

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

  <td width="25%">Facebook URL :</td>

    <td ><input type=text class='form-control' name='facebook_url' value="<?php echo $db_value['facebook_url'];?>" > </td>

</tr>
<tr >

  <td width="25%">Twitter URL :</td>

    <td ><input type=text class='form-control' name='twitter_url' value="<?php echo $db_value['twitter_url'];?>" > </td>

</tr>
<tr >

  <td width="25%">Linkedin Url :</td>

    <td ><input type=text class='form-control' name='linkedin_url' value="<?php echo $db_value['linkedin_url'];?>" > </td>

</tr>


<tr >

  <td width="25%">Google Plus URL :</td>

    <td ><input type=text class='form-control' name='google_plus' value="<?php echo $db_value['google_plus'];?>" > </td>

</tr>

<tr >

  <td width="25%">Instagram URL :</td>

    <td ><input type=text class='form-control' name='instagram_url' value="<?php echo $db_value['instagram_url'];?>" > </td>

</tr> 
 <tr >

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

</tr> 
 <tr >

  <td width="25%">Paypal Url :</td>

    <td ><input type=text class='form-control' name='paypal_url' value="<?php echo $db_value['paypal_url'];?>" > </td>

</tr>

 <tr >

  <td width="25%">Paypal Email :</td>

    <td ><input type=text class='form-control' name='paypal_mail' value="<?php echo $db_value['paypal_mail'];?>" > </td>

</tr>
 


<tr >

  <td width="25%">Contact Details : </td>

    <td ><textarea name="contact_details" class="form-control" rows="4" ><?php echo html_entity_decode($db_value['contact_details']);?></textarea><br></td>

</tr>

<tr >

  <td width="25%">Opening Hours : </td>

    <td ><textarea name="opening_hours" class="form-control" rows="4" ><?php echo html_entity_decode($db_value['opening_hours']);?></textarea><br></td>

</tr>

 

<tr >

  <td width="25%">Currency Symbol : </td>

    <td ><input type=text name="currency_symbol" class="form-control"  value='<?=$db_value['currency_symbol'];?>'></td>

</tr>
 <tr >

  <td width="25%">Currency: </td>

    <td ><input type=text name="currency" class="form-control"  value='<?=$db_value['currency'];?>'></td>

</tr> 
    <tr >
      <td width="25%"><b>Product Discount :</b></td>
        <td ><input type='number'  class='form-control' name="ref_discount" value="<?=(int)$db_value['ref_discount'];?>" >
      </td>
    </tr> 
    <tr >
      <td width="25%"><b>Referral Commission :</b></td>
        <td ><input type='number' class='form-control' name="ref_commission" value="<?=(int)$db_value['ref_commission'];?>">
      </td>
    </tr> 
<tr> 
  <td width="25%">Site Under Maintenance : </td>

    <td><input type="radio" name="maintenance_status" id="maintenance1" value="1" <?php if($db_value['maintenance_status']==1){ echo 'checked="checked"';} ?>><label>Yes</label><input type="radio" name="maintenance_status" id="maintenance2" value="0" <?php if($db_value['maintenance_status']==0){ echo 'checked="checked"';} ?> style="margin-left:10px;"><label>No</label></td>

</tr> 
<tr ><td></td><td height=25>

  <input type='submit' class="btn btn-success" name='submit' value='Save'>

</td></tr>



</table>
</form>






 


