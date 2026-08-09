<div class="tab-content" style='overflow-x: auto;'>
	<div   class="tab-pane fade in active" >
        <table class='table table-bordered' width=100%>
            <thead style="background-color: #16A085">
				<th width=5%>Q.ID</th>
                <th width=5%>Order</th>
                <th width=15%>Story ID</th>
            </thead>
			
			<tbody>
			<?
            if($_GET['id']){
                foreach($db_list as $f){
                    $ques =  $f;
                    if($f['story_id'])
                    {
                        $title=getFldValueById("story","story_title",array("story_id"=>$f['story_id']));
                        $story="<font color='#1c8348'>Story Id : " .$f['story_id'] ." | ".$title ."</font>";
                    }

                    echo"
                    <tr style='background-color:#CCD2D5'>
                        <td>" . $f['qid'] ."</td>
                        <td>" . $f['order_no'] ."</td>
                        <td>" . $story . "</td>
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
        
        <br>
        
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="lesson_id" value="<?=isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';?>">
            <input type="hidden" name="cat_id" value="<?=isset($_GET['cat_id']) ? $_GET['cat_id'] : '';?>">
            <input type="hidden" name="course_id" value="<?=isset($_GET['course_id']) ? $_GET['course_id'] : '';?>">
            <input type="hidden" name="id" value="<?=isset($_GET['id']) ? $_GET['id'] : '';?>">

            <div class="container mt-3">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="homework" value="1" <?php if($ques['HW']==1) echo "checked"; ?> class="form-check-input">
                                        &nbsp; HW &nbsp;
                                    </label>
                                    <label class="form-check-label ml-3">
                                        <input type="checkbox" name="practice_test" value="1" <?php if($ques['pt']==1) echo "checked"; ?> class="form-check-input">
                                        &nbsp; PT &nbsp;
                                    </label>
                                </div>

                                <div class="form-group row">
                                    <label for="order_no" class="col-sm-4 col-form-label">Question Order No.</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="order_no" id="order_no" value="<?php echo $ques['order_no']?>" placeholder="Enter Question Order No.">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="order_no" class="col-sm-4 col-form-label">Online</label>
                                    <div class="col-sm-6">
                                        <input type="radio" value='Y' name="online"  <?if($ques['online']=="Y"){echo"checked";}?>>
                                        <label class="form-check-label">Yes</label>&nbsp;&nbsp;

                                        <input type='radio' name='online' value='N' <?if($ques['online']=="N"){echo"checked";}?>>
                                        <label class="form-check-label">No</label>
                                    </div>
                                </div>

                                <!-- Move the Save button to the left -->
                                <div class="form-group text-left">
                                    <input type="submit" value="Save" name="save_button" class="btn btn-success">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
        </form>
    </div>
</div>