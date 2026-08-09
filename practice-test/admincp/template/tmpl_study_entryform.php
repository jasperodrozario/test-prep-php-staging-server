<!--<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">-->
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="study_id" value="<?php echo $_GET['study_id']?>">
    
    <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
        <tr>
            <td align="left" width=20%>Study Title</td>
            <td>
                <input type="text" class='form-control' name='study_title'   value="<?php echo $db_value['study_title']?>"  required>
            </td>
        </tr>

        <tr>
            <td align="left" width=20%>Category</td>
            <td>
                <input type="text" class='form-control' name='category'   value="<?php echo $db_value['category']?>"  required>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Chapter</td>
            <td>
                <input type="text" class='form-control' name='chapter'   value="<?php echo $db_value['chapter']?>"  required>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Topic</td>
            <td>
                <input type="text" class='form-control' name='Topic'   value="<?php echo $db_value['Topic']?>" required>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Tag</td>
            <td>
                <input type="text" class='form-control' name='tag'   value="<?php echo $db_value['tag']?>"  required>
            </td>
        </tr>
        
        <tr>
            <td align="left" width=20%>Type</td>
            <td>
                <select name='type' id='type' class='form-control' size=1>
                    <option value='study' <?php echo ($db_value['type'] == 'study') ? 'selected' : ''; ?>>Study</option>
                    <option value='skill' <?php echo ($db_value['type'] == 'skill') ? 'selected' : ''; ?>>Skill</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td valign="top" width="20%">Content</td>
            <td align="left" valign="top"> 
                <textarea  name='content' id="content" class='form-control' style="width:100%;height:200px"><?=html_entity_decode(html_entity_decode($db_value["content"]));?></textarea> 
            </td>
        </tr>

        <tr>
            <td></td>
            <td >
                <input type=submit  name='SBMT_REG' value='Save' class='btn btn-success'>
            </td>
        </tr>
    </table> 
</form>

<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>
<script>
var editor;
KindEditor.ready(function(K) 
{
	editor = K.create('#content', {
		allowFileManager : true
	});
});
</script>