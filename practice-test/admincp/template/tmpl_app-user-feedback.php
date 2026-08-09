<br>
<div id="response"></div>

<form method='post' action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">
    <div class='row'>
        <div class='col-md-8'><input type='text' class='form-control' name='key' placeholder='Search By ID/ Email/ Platform' autocomplete="off"></div>
        <div class='col-md-2'><input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'></div>
        <div class='col-md-2'>

        </div>

    </div>
</form>
<br>
<form method="post" action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">

    <table id="table-1" width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-stripe table-bordered'>
        <thead>
            <tr>
                <td align=center><b>ID/User ID</b></td>
                <td align=center><b>Sent By</b></td>
                <td align=center><b>Course Name</b></td>
                <td align=center><b>Source</b></td>
                <td width=30% align=center><b>Content</b></td>
                <td align=center><b>Date</b></td>
                <td align=center><b>Status</b></td>
                <td align=center><b>Action</b></td>
            </tr>
        </thead>
        <tbody>
            <?
            if ($total) {

                foreach ($db_list as $d) {

                    if ($d['is_resolved'] == 1) {
                        $change = "N";
                        $status = "Viewed";
                        $bold = "";
                        $back_color = "";
                    } else {
                        $change = "Y";
                        $status = "Pending";
                        $bold = "style='font-weight:bold;'";
                        $back_color = "style='background-color: #e6ecfa;'";
                    }
                    $dot = "";
                    $user = getDataByWhere("user", "*", array("user_id" => $d['user_id']));
                    echo "
    <tr $back_color>
        <td $bold>" . $d['feedback_id'] . "/" . $d['user_id'] . "</td>
        <td $bold>" . $user['first_name'] . " " . $user['last_name'] . "<br>" . $d['email'] . "<br>" . $user['mobile'] . "</td>
        <td $bold>"  . $d['course_name'] . "</td>
        <td $bold>" . ucfirst($d['platform']) . "</td>
        <td $bold>Feedback: " . html_entity_decode($d['feedback_text']) . "
        <td align=center $bold>" . showdate($d['submitted_at']) . "</td>
        <td $bold>" . $status . "</td>
        <td align=center $bold>
            <a   href='" . basename($_SERVER['PHP_SELF'], ".php") . "?id=" . $d['feedback_id'] . "&status=" . $change . "'>Change Status</a> | 
             
            <a href=\"javascript:if(confirm('Are you sure you wish to delete?')){window.location='" . basename($_SERVER['PHP_SELF'], ".php") . "?del_id=" . $d['feedback_id'] . "';}\">Delete</a>
        </td>
    </tr>";

                    $j++;
                }
            } else {
                echo "<font color=red>No Record found.</font>";
            }
            ?>
        </tbody>
    </table>
</form>

<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="sendEmailModalLabel">Compose Email</h5>
            </div>
            <div class="modal-body">
                <label for="subjectInput">Subject: </label>
                <input type="text" id="subjectInput" class="form-control" />
                <br>
                <label for="msgInput">Message: </label>
                <textarea id="msgInput" class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveOrderBtn">Send Email</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<? if ($total > 100) {
    echo "<ul class='pagination'>" . paging($total, "", "id=" . $_GET['id'] . "&status=" . $_GET['status']) . "</ul>";
} ?>

<script>
    $(document).ready(function() {
        $('.set-order-btn').click(function() {
            var eid = $(this).data('eid');
            $('#saveOrderBtn').data('eid', eid);
        });

        $('#saveOrderBtn').click(function() {
            var eid = $(this).data('eid');
            var subject = $('#subjectInput').val();
            var msg = $('#msgInput').val();

            // Perform an AJAX request
            $.ajax({
                type: 'POST',
                url: "<?= $configs['vpath'] . $configs['admin_dir']; ?>/ajax", // Replace with the actual URL of your server-side script
                data: "action=sendEmail&eid" + eid + "&subject=" + subject + "&msg=" + msg
            }).done(function(data) {
                // Display the response message
                $("#response").html(data.msg);

                // Close the modal/pop-up
                $('#sendEmailModal').modal('hide');

                // Refresh the page after a short delay (adjust the delay as needed)
                setTimeout(function() {
                    location.reload();
                });
            });
        });
    });
</script>



<!-- BEGIN FOOTER -->
<? include("includes/footer.php"); ?>