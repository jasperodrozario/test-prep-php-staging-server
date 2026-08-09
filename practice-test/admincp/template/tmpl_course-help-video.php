<?
echo "<br>";

switch ($action) {

    case "course-help-video-list":
?>

        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID</b></td>
                    <td align="center"><b>Course</b></td>
                    <td align="center"><b>Thumbnail</b></td>
                    <td align="center"><b>Mobile Video</b></td>
                    <td align="center"><b>Desktop Video</b></td>
                    <td align="center"><b>Status</b></td>
                    <td align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_list as $d) {

                    $course_name = getFldValueById("course", "course_name", ["course_id" => $d['course_id']]);

                    $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    echo "
                        <tr>
                            <td align='center'>" . $d['c_id'] . "</td>

                            <td>" . $course_name . "</td>
                            
                            <td align='center'><img src='" . $configs['vpath'] . "image.php?src=" . $image_path . $d['thumbnail'] . "&w=60'></td>

                            <td align='center'>
                                <span style='color:" . (!empty($d['mobile_youtube_link']) ? "green" : "red") . "; font-weight:bold;'>
                                    " . (!empty($d['mobile_youtube_link']) ? "Available" : "Missing") . "
                                </span>
                            </td>

                            <td align='center'>
                                <span style='color:" . (!empty($d['desktop_youtube_link']) ? "green" : "red") . "; font-weight:bold;'>
                                    " . (!empty($d['desktop_youtube_link']) ? "Available" : "Missing") . "
                                </span>
                            </td>
                            
                            <td align='center'><span style='color:" . (($d['is_online'] == 'Y') ? "green" : "red") . "; font-weight:bold;'>" . (($d['is_online'] == 'Y') ? "Online" : "Offline") . "</span></td>
                            
                            <td align='center'> 

                                <a   href='course-help-video?action=add-course-help-video&cv_id=" . $d['c_id'] . "' class='btn btn-info btn-sm'>Edit</a>  

                                <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='course-help-video?action=course-help-video-list&del_cv_id=" . $d['c_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                                
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>

    <?
        break;

    case "add-course-help-video":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>?action=add-course-help-video&cv_id=<?= $_GET['cv_id'] ?>' enctype="multipart/form-data">

            <input type="hidden" name="cv_id" value="<?php echo $_GET['cv_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="400px">Course</td>
                    <td>
                        <select name="course_id" class="form-control" required>
                            <option value="">Select Course</option>

                            <?php foreach ($course_list as $course) {
                                $selected = ($course['course_id'] == $db_value['course_id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $course['course_id']; ?>" <?php echo $selected; ?>>
                                    <?php echo $course['course_name']; ?>
                                </option>
                            <?php } ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="left">Mobile YouTube Link</td>
                    <td>
                        <input
                            name="mobile_youtube_link"
                            id="mobile_youtube_link"
                            class="form-control"
                            value="<?php echo $db_value['mobile_youtube_link']; ?>">
                    </td>
                </tr>

                <tr>
                    <td align="left">Desktop YouTube Link</td>
                    <td>
                        <input
                            name="desktop_youtube_link"
                            id="desktop_youtube_link"
                            class="form-control"
                            value="<?php echo $db_value['desktop_youtube_link']; ?>">
                    </td>
                </tr>

                <tr>
                    <td align="left">Thumbnail</td>
                    <td>
                        <? if ($db_value['thumbnail']) {
                            echo "<img src='" . $configs['vpath'] . $db_value['thumbnail'] . "' 100 width=100 height=80><br />";
                        } ?>

                        <input type="file" name="thumbnail" value="<?php echo $db_value['thumbnail'] ?>" style="margin-top:10px;" />
                    </td>
                </tr>

                <tr>
                    <td align="left">Status</td>
                    <td>
                        <label>
                            <input type="radio" name="is_online" value="Y" <?php echo ($db_value['is_online'] == 'Y') ? 'checked' : ''; ?>> Active
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="is_online" value="N" <?php echo ($db_value['is_online'] == 'N') ? 'checked' : ''; ?>> Inactive
                        </label>
                    </td>

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
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<style>
    .link {
        font-size: 13px;
        /* Increase font size */
        font-weight: bold;
    }
</style>