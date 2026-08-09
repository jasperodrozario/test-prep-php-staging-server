<br>
<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">
    <input type="hidden" name="pred_id" value="<?php echo $_GET['pred_id'] ?>">
    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

        <tr>
            <td align="left" width=20%>Category Name(Admin)</td>
            <td><input type="text" class='form-control' name='cat_name' value="<?php echo $db_value['cat_name'] ?>" required></td>
        </tr>

        <tr>
            <td>Category Code(User View)</td>
            <td><input type="text" class='form-control' name='cat_code' value="<?php echo $db_value['cat_code'] ?>" required></td>
        </tr>

        <tr>
            <td>Description</td>
            <td><textarea class='form-control' name='description'><?= html_entity_decode($db_value['description']); ?></textarea></td>
        </tr>

        <tr>
            <td align="left">Display Order</td>
            <td><input type="number" class='form-control' name='ord' value="<?php echo $db_value['ord'] ?>"></td>
        </tr>

        <tr>
            <td colspan="4" align="center">
                <input type="submit" name="SBMT_REG" value="Submit" class="btn btn-success">
            </td>
        </tr>


    </table>
</form>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
