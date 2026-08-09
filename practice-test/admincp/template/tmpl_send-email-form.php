<form method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="send_email">
<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
 
<tr><td align="left" width=20%>To Email</td><td><input type="text" class='form-control' name='email'   value="<?php echo $_GET['email']?>"  required></td></tr>

<tr><td align="left" width=20%>To Name</td><td><input type="text" class='form-control' name='name'   value="<?php echo $_GET['name']?>"  required></td></tr>
    
<tr><td align="left" width=20%>Subject</td><td><input type="text" class='form-control' name='subject' required></td></tr>

<tr><td>Body </td><td><textarea class='form-control' name='body'></textarea></td></tr>

<tr><td></td><td ><input type=submit  name='SBMT_EMAIL' value='Send Email' class='btn btn-success'></td></tr>
</table>
</form>