<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        
        <form action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>?story_id=<?=$_REQUEST['story_id']?>" method='post' enctype="multipart/form-data"  name="minform">
            <input type='hidden' name='story_id' value="<?php echo  $db_value['story_id']?>">

            <table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>

                <!-- Remove input fields for title and author -->
                <tr>
                    <td width="20%">Story Title</td>
                    <td><?php echo $db_value['story_title'];?></td>
                </tr>

                <tr>
                    <td width="20%">Author</td>
                    <td><?php echo $db_value['author'];?></td>
                </tr>

                <tr>
                    <td valign="top" width="20%">Story</td>
                    <td align="left" valign="top">
<!--                        <textarea name='story' id="story" class='form-control' style="width:100%;height:200px" readonly>-->
                            
                            <?=html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($db_value["story"]))));?>
                            
<!--                        </textarea>-->
                    </td>
                </tr>
            </table>
        </form>

        <table class='table table-bordered'>
			<thead style="background-color:#16A085">
                <th width=5%>Q.ID</th>
                <th width=20%>Category</th>
                <th width=15%>Story ID</th>
                <th width=10%>Action</th>
            </thead>
            
            <tbody>
            <?
                foreach($db_value_ques as $f){
                    $cat_name = getFldValueById("category", "category_name", array("cat_id" => $f['cat_id']));
                    $story="";
                    if($f['story_id'])
					{
						$title=getFldValueById("story","story_title",array("story_id"=>$f['story_id']));
		                $story="<font color='#1c8348'>Story Id : " .$f['story_id'] ." | ".$title ."</font>";
					}
                    
                    echo"
					<tr style='background-color:#CCD2D5'>
						<td>" . $f['qid'] ."</td>
						<td>" . $cat_name."</td>
						<td>" . $story . "</td>
                        <td>
                            <i class='fa fa-edit'></i> 
							<a href='add_questions?cat_id=" .$f['cat_id']."&qid=" .$f['qid'] ."&limit=" . $_GET['limit'] ."'>Edit</a> &nbsp;
							
							<i class='fa fa-trash'></i> 							
							<a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?cat_id=" . $f['cat_id'] . "&del_id=" .$f['qid'] ."&limit=" . $_GET['limit'] ."';}\" >Delete</a>
						</td>
					</tr>";
                    
                    echo"
					<tr style='background:#f5f5f5'>
						<td colspan=9>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['question']))) . "</td>
					</tr>";
                    $alphabet = range('A', 'Z');
                    for ($i = 1; $i <= $option_no; $i++) {
                        $ans = $alphabet[$i-1];
//                        echo "<tr><td width=5>" . $ans . "</td></tr>";
                        $ind = 'option_'.strtolower($ans);
                        if($f[$ind]){
                            if ($f['answer'] == $i) {
                                $ans = '<span class="badge badge-success">' . $alphabet[$i-1] . '</a>';
                            }
                            echo "
                            <tr>
                                <td width=5>" . $ans . "</td>
                                <td class='text-align:left' colspan=7>" . html_entity_decode(html_entity_decode(str_replace("&#92;", "\\", $f[$ind]))) . "</td>
                            </tr>";
                        }
                    }                    
                    
                    echo"
                    <tr style='background:#f5f5f5'>
                        <td class='text-align:left' colspan=9><b>Explanation: </b><br><br>" . html_entity_decode(html_entity_decode($f['explanation'])) . "</td>
                    </tr>";
                    
                }
            
            ?>
            </tbody>

        </table>
        
        <form method="post" method='post' action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
            <input type='hidden' name="story_id" value="<?php echo $_GET['story_id']?>">
            
            <div class="btn-toolbar" role="toolbar" aria-label="Question Actions">
                <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='add_questions?story_id=<?= $_GET['story_id'] ?>'">Add New Questions to Story</button>
                    
                </div>
            </div>
        </form>
        
        <div class="tab-content" style='overflow-x: auto;'>
            <div class="tab-pane fade in active">

                <div class="connected-info">
                    <h3>The story is connected with the following lesson:</h3>
                    <?php
                    foreach ($story_connection_list as $s) {
                        $lesson_name = getFldValueById("lesson","Lesson_name",array("lesson_id"=>$s['lesson_id']));
                        $cat_name = getFldValueById("category","category_name",array("cat_id"=>$s['cat_id']));
                        $course_name = getFldValueById("course","course_name",array("course_id"=>$s['course_id']));
                    ?>
                        <div class="connected-info">
                            <ul>
                                <li>
                                    <strong>Course:</strong>
                                    <?php echo "<span>".$course_name."</span>"; ?>
                                </li>

                                <li>
                                    <strong>Categorie:</strong>
                                    <?php echo "<span>".$cat_name."</span>"; ?>
                                </li>

                                <li>
                                    <strong>Lesson:</strong>
                                    <?php
                                    if( !empty($lesson_name) ) {
                                        echo "<span>".$lesson_name."</span>";
                                    } else {
                                        echo "<span class='text-danger'>No lesson connected</span>";
                                    }
                                    ?>
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
        
    </div>
</div>

<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>

<style>
    .story-div-style {
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
    
    .story-order-list-style{
        background-color: #f7f7f7;
        padding: 4%;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
    }
</style>
