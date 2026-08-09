<div id='response'></div>

<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
    <table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
              <td><b>ID</b></td>
              <td><b>Category Name</b></td>
              <td><b>Total Questions</b></td>
              <td align="center" with=25%><b>Action</b></td>
            </tr>
        </thead>
        
        <tbody>
            <?php
                $j=0;
                    foreach($db_list as $dd)
                    {
                        $cat_name = getFldValueById("category","category_name",array("cat_id"=>$dd['cat_id']));
                ?>
                      <tr>
                          <td><?php echo $dd['id']?></td>
                          <td ><?php echo "<a  href='pred_questions_list?predic_test_id=".$_GET['predic_test_id']."&set_id=" . $_GET['set_id'] ."&pred_cat_id=" .$dd['id']."'><u>".$cat_name."</u></a>";?></td>
                          <td><?php echo $dd['number_of_questions']?></td>
                          <td align="center">
                            <?php
                                echo"<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $cat_name . "`?')){window.location.href='prediction_cat_list?predic_test_id=".$_GET['predic_test_id']."&set_id=" . $_GET['set_id'] ."&pred_cat_id=" .$dd['id']."&del_id=" . $dd['id'] . "'}\"><u>Delete</u></a>";
                            ?>
                           </td>
                       </tr>
                <?php
                    }
                ?>
        </tbody>
    </table>
</form>

        <div class="modal fade" id="setPredSetModal" tabindex="-1" role="dialog" aria-labelledby="setPredSetModalLabel" aria-hidden="true">
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
                                 <?php
                                 foreach ($categories as $c) {
                                     echo "<option value='" . $c['cat_id'] . "'>" . $c['category_name'] . "</option>";
                                 }
                                ?>
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
$(document).ready(function() {
    $('.set-pred-set-btn').click(function() {
        var testId = $(this).data('test-id');
        $('#saveOrderBtn').data('test-id', testId);
    });

    $('#saveOrderBtn').click(function() {
        var predId = $(this).data('test-id');
        var order = $('#orderInput').val();
        var cat_id = $('#catInput').val();

        // Perform an AJAX request to update the order in the database
        $.ajax({
            type: 'POST',
            url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax", // Replace with the actual URL of your server-side script
            data: "action=addPredCat&predId=" + predId + "&qnum=" + order + "&cat_id=" + cat_id
        }).done(function(data) {
            // Display the response message
            $("#response").html(data.msg);
            
            // Close the modal/pop-up
            $('#setPredSetModal').modal('hide');
            
            // Refresh the page after a short delay (adjust the delay as needed)
            setTimeout(function() {
                location.reload();
            });
        });
    });
});
</script>





