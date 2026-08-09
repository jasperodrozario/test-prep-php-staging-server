<br>

<form name="send_mail" method='post' onSubmit="javscript:return ValidSet(this);">

    <input type="hidden" name="id" value="<?= $_REQUEST['id']; ?>">

    <table border="0" cellpadding="4" cellspacing="0" width="100%" class="table table-bordered">

        <!-- Subject Preview -->
        <tr>
            <td width="20%"><b>Subject :</b></td>
            <td>
                <input type="text"
                    class="form-control"
                    value="<?= htmlspecialchars($db_value['subject']); ?>"
                    readonly>
            </td>
        </tr>

        <!-- Email Body Preview -->
        <tr>
            <td valign="top"><b>Email Body :</b></td>
            <td>
                <textarea name="template" id="template" class="form-control"
                    style="width:100%;height:300px;visibility:hidden;" readonly><?= html_entity_decode($db_value['template']); ?></textarea>
            </td>
        </tr>

        <!-- Receiver Email Field -->
        <tr>
            <td><b>Send To (Email) :</b></td>
            <td>
                <input type="email"
                    name="receiver_email"
                    class="form-control"
                    placeholder="Enter receiver email address"
                    required>
            </td>
        </tr>

        <!-- Submit Button -->
        <tr>
            <td></td>
            <td>
                <input type="submit" name="send" value="Send Email" class="btn btn-success">
            </td>
        </tr>

    </table>

</form>

<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>
<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('#template', {
            allowFileManager: true
        });
    });
</script>