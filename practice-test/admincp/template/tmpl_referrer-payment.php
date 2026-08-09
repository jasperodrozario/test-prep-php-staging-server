<div id='response'></div>
<form method="post" id='form11' enctype="multipart/form-data">
    <input type='hidden' name='action' value='payment-update'>
    <input type="hidden" name="user_id" value="<?= $_GET['user_id'] ?>">
    <h3><?= $page_title ?></h3>
    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
        <tr>
    <td align="left" width="20%">User Info</td>
    <td>
        <div style="display: grid; grid-template-columns: 150px auto; row-gap: 6px; column-gap: 10px;">
            <div><strong>User ID:</strong></div>
            <div><?= $db_value['user_id']; ?></div>

            <div><strong>Name:</strong></div>
            <div><?= $name; ?></div>

            <div><strong>Payment Method:</strong></div>
            <div><?= ucfirst($db_value['payment_method'] ?? 'N/A'); ?></div>

            <div><strong>Payment Info:</strong></div>
            <div><?= $db_value['payment_info'] ?? 'N/A'; ?></div>
        </div>
    </td>
</tr>
        <tr>
            <td align="left" width=20%>Paid Amount</td>
            <td><input type="text" class='form-control' name='amount' value="<?php echo $due_amount; ?>" required></td>
        </tr>
        <tr>
            <td align="left" width=20%>Payment Method</td>
            <td><input type="text" class='form-control' name='payment_method' value="<?php echo $db_value['payment_method']; ?>" required></td>
        </tr>
        <tr>
            <td align="left" width="20%">Payment Date</td>
            <td>
                <input type="date" class="form-control" name="date"
                    value="<?= date('Y-m-d'); ?>" required>
            </td>
        </tr>

        <tr>
            <td></td>
            <td><input type='submit' name='SBMT_REG' value='Update' class='btn btn-success'></td>
        </tr>
    </table>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script>
    $("#form11").submit(function(event) {
        event.preventDefault();

        // 👇 confirmation step
        var confirmAction = confirm("Are you sure you want to update this payment?");
        if (!confirmAction) {
            return false;
        }

        $('#response').html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");
        $.ajax({
                dataType: "json",
                type: "POST",
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax",
                data: new FormData(this),
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false
            })
            .done(function(data) {


                $("#response").html(data.msg);
                // $('.modal-content').empty();  


            })
    });
</script>