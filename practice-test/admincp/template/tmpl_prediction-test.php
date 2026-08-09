<br>
<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
    <table  width="100%" class="table table-bordered table-striped" border="0"  cellspacing="1" cellpadding="4">
        <thead>
            <tr>
              <td><b>ID/Ord</b></td>
              <td><b>Prediction Test Name</b></td>
              <td><b>Ques Source</b></td>
              <td><b>Num of Ques</b></td>
              <td><b>Duration</b></td>
              <td><b>Free</b></td>
              <td><b>Online</b></td>
              <td  with=25%><b>Action</b></td>
            </tr>
        </thead>
        
        <tbody>
            <?php
            $j=0;
            foreach($db_list as $d)
            {
                if($total_predic[$d['course_id']])
                {
                ?>
                <tr style="background-color: #1ABC9C;">
                    <td id="course_id"><?php echo $d['course_id']?></td>
                    <td id="course_name"><?php echo $d['course_name'];?></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <?php
                
                    foreach($prediction_test[$d['course_id']] as $dd)
                    {
                ?>
                      <tr  style="background-color: #CDCDCD;">
                          <td id="Predic_test_id"><?php echo $dd['Predic_test_id']?>/<?php echo $dd['test_order']?>
                        </td>
                          <td id="Predic_test_name"><?php echo "<a  href='prediction_set_list?predic_test_id=" . $dd['Predic_test_id'] . "'><u>".$dd['test_name']."</u></a>";?></td>
                          <td>-</td>
                          <td>-</td>
                          <td>-</td>
                          <td ><?php echo $dd['free']?></td>
                          <td ><?php echo $dd['online']?></td>
                          <td >
                            <?php
                    
//                                echo "<a href='#' class='set-pred-set-btn' data-test-id=" . $dd['Predic_test_id'] . " data-toggle='modal' data-target='#setPredSetModal'><u>Add Category</u></a> | ";
                                
                                echo "<a href='pred-create-category?test_id=" . $dd['Predic_test_id'] . "&course_id=" . $d['course_id'] . "'><u>Add Category</u></a> | ";

                                echo"<a  href='prediction_entryform?Predic_test_id=" . $dd['Predic_test_id'] . "'><u>Edit</u></a> | ";

                                echo"<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $dd['test_name'] . "`?')){window.location.href='prediction-test?del_id=" . $dd['Predic_test_id'] . "'}\"><u>Delete</u></a>";
                            ?>
                           </td>
                       </tr>   
                <?php
                        foreach($pred_test_cat[$dd['Predic_test_id']] as $c)
                        {
                            $cat_name = getFldValueById("category","category_name",array("cat_id"=>$c['cat_id']));
                            $ques_source_name = getFldValueById("pred_test_category", "cat_name", array("pred_id" => $c['pred_qb_id']));
                ?>
                          <tr>
                              <td id="cat_id"><?php echo $c['id']?>/<?php echo $c['order_no']?></td>
                              <td id="cat_name"><?php echo $cat_name?></td>
                              <td><?php echo $ques_source_name?></td>
                              <td><?php echo $c['number_of_questions']?></td>
                              <td><?php echo $c['duration']?></td>
                              <td >-</td>
                              <td >-</td>
                              <td>
                               <?php
                                    echo"<a  href='pred-create-category?test_id=".$dd['Predic_test_id']."&cat_id=".$c['cat_id']."&id=".$c['id']."'><u>Edit</u></a> | ";
                            
                                    echo"<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $cat_name . "`?')){window.location.href='prediction-test?del_cat_id=" . $c['id']."&pred_test_id=" . $c['pred_test_id']."&cat_id=" . $c['cat_id']. "'}\"><u>Delete</u></a>";
                                ?>
                              </td>
                           </tr>
                    <?php
                        }
                    }

                }
            }	
            ?>
        </tbody>
    </table>
</form>

<!--
<div class="modal fade" id="setPredSetModal" tabindex="-1" role="dialog" aria-labelledby="setOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="setPredSetModalLabel">Add Prediction Test Category</h5>
                    </div>
                    <div class="modal-body">
                        <label for="orderInput">Enter Category Name:</label>
                        <div>
                            <select id="catInput" name='cat_id_drop' class='form-control'>
                                 <option value=''>Select Category</option>
                            </select>
                        </div>
                        <br>
                        <label for="orderInput">Enter Number of Questions:</label>
                        <input type="number" id="orderInput" class="form-control" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveOrderBtn">Add Category</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
-->

<style>
    .link {
        color: inherit; /* Set the default text color */
        text-decoration: none; /* Remove underline */
    }

    .link:hover {
        color: white; /* Change text color on hover */
    }
</style>

<script>
//$(document).ready(function() {
//    $('.set-pred-set-btn').click(function() {
//        var testId = $(this).data('test-id');
//        $('#saveOrderBtn').data('test-id', testId);
//
//        // Make an AJAX request to get categories for the selected test ID
//        $.ajax({
//            type: 'POST',
//            url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
//            data: "action=getPredCategories&testId=" + testId
//        }).done(function(data) {
//            $("#catInput").html(data.list);
//        });
//    });
//    
//    $('#saveOrderBtn').click(function() {
//        var predId = $(this).data('test-id');
//        var order = $('#orderInput').val();
//        var cat_id = $('#catInput').val();
//
//        // Perform an AJAX request to update the order in the database
//        $.ajax({
//            type: 'POST',
//            url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax", // Replace with the actual URL of your server-side script
//            data: "action=addPredCat&predId=" + predId + "&qnum=" + order + "&cat_id=" + cat_id
//        }).done(function(data) {
//            // Display the response message
//            $("#response").html(data.msg);
//            
//            // Close the modal/pop-up
//            $('#setPredSetModal').modal('hide');
//            
//            // Refresh the page after a short delay (adjust the delay as needed)
//            setTimeout(function() {
//                location.reload();
//            });
//        });
//    });
//});
</script>










