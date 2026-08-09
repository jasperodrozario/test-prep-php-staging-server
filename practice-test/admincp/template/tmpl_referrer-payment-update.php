<div id='response'></div>
<form method="post" id="lesson_form">
<input type="hidden" name="user_id" value="<?php echo $_GET['user_id']?>">
 <input type="hidden" name="month" value="<?php echo $_GET['month']?>">
  <input type="hidden" name="action" value="payout">
   <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
  <tr><td align="left" width=25%>Pay Amount</td>
  <td><input type="text" class='form-control' name='amount'  value="<?php echo $_GET['amount']?>" required></td></tr>
  <tr><td align="left">Payment Method</td>
  <td> 
    <select name="payment_method" class='form-control' required>
    <option value=''>Payment Method</option>  
    <option value="P" <?if($db_value['payment_method']=="P"){echo" selected";}?>> Paypal</option>
    </select></td></tr> 
  <tr><td align="left">Payment Date</td>
  <td><input type="date"  name="date"  class='form-control' value="<?php echo $db_value['date'];?>" required></td></tr>
  <tr><td>Status </td><td><input type="radio" value='Y' name="status"  <?if($db_value['status']=="Y"){echo"checked";}?> required>Paid <input type='radio' name=status value='N' <?if($db_value['status']=="N"){echo"checked";}?> required>Due</td></tr>
  <tr><td></td><td ><input type='submit'  name='submit' value='Update' class='btn btn-success'> <input type='button'   class='btn btn-danger' value='Cancel' onClick="history.back();" ></td></tr>
  </table>
</form>
