<?
echo "<br>";
switch ($action) {
    case "add-service-content":
?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-services-content?action=add-service-content' enctype="multipart/form-data">

            <input type="hidden" name="service_id" value="<?php echo $_GET['service_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="300px">Title</td>
                    <td><input type="text" name="title" class='form-control' value="<?php echo $db_value['title']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left">Description</td>
                    <td>
                        <textarea name="description" id="description" class="form-control" rows="5"><?php echo $db_value['description']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Content Heading</td>
                    <td><input type="text" name="course_name" class='form-control' value="<?php echo $db_value['course_name']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left">Service Type</td>
                    <td>
                        <select name="service_type" class='form-control'>
                            <option value=''>Select a Service Type</option>
                            <?php
                            $courses = array(
                                "1-on-1 Tutoring" => "one",
                                "Group Tutoring"  => "group",
                                "Self-Study"      => "subscribe"
                            );

                            foreach ($courses as $course_name => $service_id) {
                                $selected = ($db_value['service_type'] == $service_id) ? "selected" : "";
                                echo "<option value='{$service_id}' {$selected}>{$course_name}</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="left">Is Online</td>
                    <td>
                        <select name="is_online" class='form-control'>
                            <option value="Y" <?php if ($db_value['is_online'] == 'Y') {
                                                    echo "selected";
                                                } ?>>Yes</option>
                            <option value="N" <?php if ($db_value['is_online'] == 'N') {
                                                    echo "selected";
                                                } ?>>No</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="left">Order</td>
                    <td><input type="number" name="ord" class='form-control' value="<?php echo $db_value['ord']; ?>"></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='submit' value='Save' class='btn btn-success'>
                    </td>
                </tr>
            </table>
        </form>
    <?
        break;

    case "service-list":
    ?>
        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID</b></td>
                    <td align="center"><b>Title</b></td>
                    <td align="center"><b>Heading</b></td>
                    <td align="center"><b>Online</b></td>
                    <td align="center"><b>Order</b></td>
                    <td align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_value as $d) {

                    echo "
            <tr>
                <td align='center'>" . $d['service_id'] . "</td>
                <td>" . $d['title'] . "</td>
                <td>" . $d['course_name'] . "</td>
                <td>" . ($d['is_online'] == 'Y' ? 'Yes' : 'No') . "</td>
                <td>" . $d['ord'] . "</td>
                <td align='center'> 
                    <a   href='asvab-services-content?action=add-service-content&service_id=" . $d['service_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                    <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-services-content?action=service-list&del_service_id=" . $d['service_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>
<?
        break;
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    CKEDITOR.replace('description', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 200,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form',
        versionCheck: false
    });

    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
        document.getElementById('ie8-warning').className = 'tip alert';
    }
</script>

<style>
    .link {
        font-size: 13px;
        /* Increase font size */
        font-weight: bold;
    }
</style>