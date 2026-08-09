<div id='response'></div>
 
 <ul class="nav nav-tabs">
 
 </ul> 
<div class="tab-content" style='overflow-x: auto;'>
	<div   class="tab-pane fade in active" >
	    <form method="post" id="ques_form" action='<?=basename($_SERVER['PHP_SELF'],".php");?>'  enctype="multipart/form-data">
			<input type='hidden' name="predic_test_id" value="<?=$_GET['predic_test_id'];?>">
			<input type='hidden' name="set_id" value="<?=$_GET['set_id'];?>">
            <input type='hidden' name="pred_cat_id" value="<?=$_GET['pred_cat_id'];?>">
            
			<table class='table table-bordered'>
			    <tr>
                    <td>
                        <input type='text' class='form-control' name='search' placeholder='Enter Question Search Key'>
                    </td>
                    <td width=20%>
                        <input type='submit' value='Search' name='ok' class='btn btn-success' style='width:100%'>
                    </td>
                </tr>
			</table>
        </form>
 
        <form method="post" id="hw_form" action='<?=basename($_SERVER['PHP_SELF'],".php");?>'  enctype="multipart/form-data">
            <input type='hidden' name="predic_test_id" value="<?=$_GET['predic_test_id'];?>">
			<input type='hidden' name="set_id" value="<?=$_GET['set_id'];?>">
            <input type='hidden' name="pred_cat_id" value="<?=$_GET['pred_cat_id'];?>">
            
            <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-3 text-right">
                    <input type='submit' value='Remove Multiple Questions' name='remove' class='btn btn-primary'>
                </div>
                <div class="col-md-2">
                    
                    <button type="button" class="btn btn-primary" onclick="window.location.href='add_lesson_questions?predic_test_id=<?= $_GET['predic_test_id']; ?>&set_id=<?= $_GET['set_id']; ?>&pred_cat_id=<?= $_GET['pred_cat_id']; ?>&add_to=pred_set'">Add Questions In Pred Set</button>

                </div>
            </div>  
            
            <br>
            
            <table class='table table-bordered' width=100%>
                <thead style="background-color: #16A085">
                    <th width=1%><input type='checkbox' id='ckbCheckAll'></input></th>
                    <th width=5%>Q.ID</th>
                    <th width=20%>Story ID</th>
                    <th width=27%>Question Tags</th>
                    <th width=27%>Question Category</th>
                    <th width=20%>Action</th>
                </thead>

                <tbody>
                <?
                if($total){
                    foreach($db_list as $f){
                        
                            $story="";
                            if($f['story_id'])
                            {
                                $title=getFldValueById("story","story_title",array("story_id"=>$f['story_id']));
                                $story="<font color='#1c8348'>Story Id : " .$f['story_id'] ." | ".$title ."</font>";
                            }

                            echo"
                            <tr style='background-color:#CCD2D5'>
                                <td><input type='checkbox' name='ids[]' value='" . $f['id'] ."' class='checkBoxClass'></td>
                                <td>" . $f['qid'] ."</td>
                                <td>" . $story . "</td>
                                <td>" . $f['question_tag'] . "</td>
                                <td>" . $f['cat_id'] . "</td>
                                <td>
                                    <i class='fa fa-edit'></i> 
                                    <a href='add_questions?cat_id=" .$f['cat_id']."&qid=" .$f['qid'] ."&limit=" . $_GET['limit'] ."'>Edit</a> &nbsp;
                                    
                                    <i class='fa fa-trash'></i> 							
                                    <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?predic_test_id=" . $_GET['predic_test_id'] . "&set_id=" . $_GET['set_id'] . "&pred_cat_id=" . $_GET['pred_cat_id'] ."&del_id=" .$f['id']."';}\" >Delete</a> &nbsp;

                                </td>
                            </tr>";

                            echo"
                            <tr style='background:#f5f5f5'>
                                <td colspan=9>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['question']))) . "</td>
                            </tr>";

                            for ($i = 1; $i <= $option_no; $i++) {
                                $ans = $alphabet[$i];
                                $ind = 'option_'.strtolower($ans);
                                if($f[$ind]){
                                    if ($f['answer'] == $i) {
                                        $ans = '<span class="badge badge-success">' . $alphabet[$i] . '</a>';
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
                }
                else{
                    echo"<tr><td colspan='5' style='text-align: center;'>No Questions to View.</td></tr>";
                }
                ?>
                </tbody>

            </table>
        </form>  
	</div>	     
</div>	
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>
<script>
$("#ckbCheckAll").click(function () {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});   
</script>