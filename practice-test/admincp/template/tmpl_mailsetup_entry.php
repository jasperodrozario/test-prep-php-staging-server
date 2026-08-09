 <form name='register' method='post' onSubmit="javscript:return ValidSet(this);">
     <input type='hidden' name="id" value="<?= $_REQUEST['id']; ?>">

     <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
         <tr>
             <td height=25><b>Email Setting : </b><br>Dont not change this</td>
             <td><input type='text' class="form-control" name='mail_type' value='<?= $db_value['mail_type'] ?>'></td>
         </tr>
         <tr>
             <td valign=top width=20%><b>Subject :</b></td>
             <td><input type='text' class='form-control' name='subject' value='<?= $db_value['subject']; ?>'></td>
         </tr>
         <tr>
             <td valign=top width=20%><b>Email :</b></td>
             <td><textarea name='template' id='template' class="form-control" data-uk-markdownarea="{mode:'tab'}" style="width:100%;height:200px;visibility:hidden;"><?= html_entity_decode($db_value['template']); ?></textarea>
             </td>
         </tr>
         <tr>
             <td></td>
             <td><input type='submit' class='btn btn-success' name='submit' value='Update'> <input type='button' class='btn btn-danger' value='Cancel' onclick="javascript:history.back();"></td>
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