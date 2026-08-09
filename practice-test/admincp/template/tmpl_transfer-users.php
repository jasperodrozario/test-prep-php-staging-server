<br>
<div class="card mt-3">
    <div class="card-header bg-primary text-white">
        <b><? echo $message?></b>
    </div>
    <div class="card-body">
        <form method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
                <tr>
                    <td align="left" width=20%>User ID:</td>
                    <td><input type="number" id="user_id" name="user_id" class="form-control" placeholder="Enter User ID" required></td>
                </tr>

                <tr>
                    <td colspan="4" align="center">
                        <input type="submit" name="transfer_user" value="Transfer User" class="btn btn-success">
                    </td>
                </tr>
        </form>
    </div>
</div>