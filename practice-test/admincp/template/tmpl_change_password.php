      

      
<form class="form-horizontal" name="forgotpass" method="post" action="<?php echo BASENAME($_SERVER['SCRIPT_NAME'],".php");?>" onSubmit="return validate(this);">
    <table width=100% border=0 cellPadding=4 cellSpacing=1 class='table table-responsive'>
    <tr><td width=25%>Old Password</td><td><input class="form-control" type="text" name="old_password" id="password" placeholder="Old Password"></td></tr>
    <tr><td>New Password</td><td><input class="form-control" type="text" name="new_password" id="New-Password" placeholder="New Password"></td></tr>
    <tr><td>Cnfirm Password</td><td><input class="form-control" type="text" name="confirm_password" id="New-Password" placeholder="Confirm Password"></td></tr>
    <tr><td></td><td><input type="submit" class="btn  btn-success" name="change_password" value="Change Password"></td></tr>
    </table>
</form>