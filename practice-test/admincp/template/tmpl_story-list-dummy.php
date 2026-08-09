<div id='response'></div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        
        <form method="post" method='post' action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
            <input type='hidden' name="page" value="question-list">
            <input type='hidden' name="limit" value="<?= $_REQUEST['limit']; ?>">
            
            <div class="btn-toolbar" role="toolbar" aria-label="Question Actions">
                <button type="button" class="btn btn-primary" onclick="window.location.href='add_story'" style="width: 200px;">Add Story</button>
            </div>
            <br>
            
            <table class='table table-bordered table-striped'>
                <tr>
                    <td>
                        <select name='param' size=1 class='form-control'>
                            <option value=''>Select Search Param</option>
                            <option value='id'>Story ID</option>
                            <option value='question'>Story Title</option>
                        </select>
                    </td>
                    <td colspan=3><input type='text' class='form-control' name='search' placeholder='Enter Search Key'></td>
                    <td width=20%><input type='submit' value='Search' name='ok' class='btn btn-success' style='width:100%'></td>
                </tr>
            </table>
        </form>

        <table class='table table-bordered'>
			<thead style="background-color:#16A085">
                <th width=2%>ID</th>
                <th width=25%>Title</th>
                <th width=20%>Number of questions</th>
                <th width=15%>Action</th>
            </thead>
            
            <tbody>
                <?
                if($total){
                    foreach($db_list as $f){
                        $total_questions = getTotalRow("question", array("story_id"=>$f['story_id']));
                        echo"
                        <tr>
                            <td>" . $f['story_id'] ."</td>
                            <td ><a  href='story-view?story_id=" . $f['story_id'] . "'><u>".$f['story_title']."</u></a></td>
                            <td>" . $total_questions."</td>
                            <td>
                                <i class='fa fa-edit'></i> 
                                <a href='add-story?story_id=" .$f['story_id'] ."&limit=" . $_GET['limit'] ."'>Edit</a> &nbsp;

                                <i class='fa fa-trash'></i> 							
                                <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?del_id=" .$f['story_id'] ."&limit=" . $_GET['limit'] ."';}\" >Delete</a>
                            </td>
                        </tr>";
                    }
                }
                ?>
            </tbody>

        </table>

        <?php
        if ($total > 20) {
            echo "<ul class='pagination'>" . paging($total, 20, "cat_id=" . $_GET['cat_id']) . "</ul>";
        }
        ?>

    </div>
</div>
