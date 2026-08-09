<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        
        <form action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" method='post' enctype="multipart/form-data"  name="minform">
            <input type='hidden' name='study_id' value=<?=$_GET['study_id'];?>>

            <table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>

                <!-- Remove input fields for title and author -->
                <tr>
                    <td width="20%">Study Title</td>
                    <td><?php echo $db_value['study_title'];?></td>
                </tr>
                
                <tr>
                    <td width="20%">Category</td>
                    <td><?php echo $db_value['category'];?></td>
                </tr>
                
                <tr>
                    <td width="20%">Chapter</td>
                    <td><?php echo $db_value['chapter'];?></td>
                </tr>
                
                <tr>
                    <td width="20%">Topic</td>
                    <td><?php echo $db_value['Topic'];?></td>
                </tr>
                
                <tr>
                    <td width="20%">Tag</td>
                    <td><?php echo $db_value['tag'];?></td>
                </tr>
                
                <tr>
                    <td width="20%">Type</td>
                    <td><?php echo $db_value['type'];?></td>
                </tr>

                <tr>
                    <td valign="top" width="20%">Content</td>
<!--                    <td align="left" valign="top" style="padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333;">   -->
                    <td align="left" valign="top">
                        <div class="study-div-style">
                            <?= html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($db_value["content"])))); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
        
        
        <div class="connected-info">
            <h3>The study is connected with the following lesson:</h3>
            <?php
                foreach ($study_connection_list as $s) {
                    $lesson_name = getFldValueById("lesson","Lesson_name",array("lesson_id"=>$s['lesson_id']));
                    $cat_name = getFldValueById("category","category_name",array("cat_id"=>$s['cat_id']));
                    $course_name = getFldValueById("course","course_name",array("course_id"=>$s['course_id']));
            ?>
                    <div class="connected-info">
                        <ul>
                            <li>
                                <strong>Courses:</strong>
                                <?php echo "<span>".$course_name."</span>"; ?>
                            </li>

                            <li>
                                <strong>Categories:</strong>
                                <?php echo "<span>".$cat_name."</span>"; ?>
                            </li>

                            <li>
                                <strong>Lessons:</strong>
                                <?php echo "<span>".$lesson_name."</span>"; ?>
                            </li>
                        </ul>
                    </div>
                    <br>
                <?php
                }
                ?>
        </div>
    </div>
</div>


<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>

<style>
    .study-div-style {
        background-color: #f7f7f7;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 100%;
        margin: 0 auto;
        text-align: justify;
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
    }
</style>
