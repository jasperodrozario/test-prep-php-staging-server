<div id='response'></div>

<form method="post" action="<?php echo basename($_SERVER['PHP_SELF'],".php");?>" enctype="multipart/form-data">
    <table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
              <td><b>ID</b></td>
              <td><b>Prediction Test Set Name</b></td>
              <td align="center" with=25%><b>Action</b></td>
            </tr>
        </thead>
        
        <tbody>
            <?php
                $j=0;
                    foreach($db_list as $dd)
                    {
                ?>
                      <tr>
                          <td><?php echo $dd['set_id']?></td>
                          <td ><?php echo "<a  href='prediction_cat_list?predic_test_id=".$_GET['predic_test_id']."&set_id=" . $dd['set_id'] . "'><u>".$dd['set_name']."</u></a>";?></td>
                          <td align="center">
                            <?php
                                echo"<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $dd['set_name'] . "`?')){window.location.href='prediction_set_list?predic_test_id=".$_GET['predic_test_id']."&del_id=" . $dd['set_id'] . "'}\"><u>Delete</u></a>";
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
                        <h5 class="modal-title" id="setPredSetModalLabel">Create Prediction Test Set</h5>
                    </div>
                    <div class="modal-body">
                        <label for="orderInput">Enter Set Name:</label>
                        <input type="text" id="orderInput" class="form-control" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveOrderBtn">Create Set</button>
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

        // Perform an AJAX request to update the order in the database
        $.ajax({
            type: 'POST',
            url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax", // Replace with the actual URL of your server-side script
            data: "action=createPredSet&predId=" + predId + "&setName=" + order
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







