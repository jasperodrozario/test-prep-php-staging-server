<div id="response"></div>

<form method='post' action="<?=basename($_SERVER['PHP_SELF'],".php");?>?reg_at=<?=$_REQUEST['reg_at'];?>">
<input type='hidden' name='reg_at'  value='<?=$_REQUEST['reg_at'];?>'>
<div class='row'><div class='col-md-8'><input type='text' class='form-control' name='key' placeholder='Search By ID / Email Id / Phone' autocomplete="off"></div><div class='col-md-2'><input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'></div>
<div class='col-md-2'>
	
</div>

</div>
</form>
<br>		 
<form method="post" action="<?=basename($_SERVER['PHP_SELF'],".php");?>">
 
<table id="table-1" width="100%"  border="0" align="center" cellspacing="1" cellpadding="4" class='table table-stripe table-bordered'>
<thead>
<tr >
    <th width=3% height=22><b>ID</b></th>
    <th width=20%><b>Sent By</b></th>
    <th><b>Course</b></th>
    <th><b>Type</b></th>
    <th><b>Content</b></th>
    <th><b>Date</b></th>
    <th><b>Status</b></th>
    <th><b>Action</b></th>
</tr>
</thead>
<tbody>
<?
if($total)
{

 foreach($db_list as $d) 
 {
     $course_name = getFldValueById("course","course_name",array("course_id"=>(int)$d['course_id']));
  
	if($d['status']=="Y"){
        $change="N";
        $status = "Viewed";
        $bold="";
        $back_color = "";
    }else{
        $change="Y";
        $status = "Pending";
        $bold = "style='font-weight:bold;'";
        $back_color = "style='background-color: #e6ecfa;'";
    }
	$dot="";
    echo "
    <tr $back_color>
        <td $bold>" . $d['msg_id'] ."</td>
        <td $bold>" . $d['first_name'] . " " . $d['last_name'] . "<br>" . $d['email'] . "<br>". $d['phone'] . "</td>
        <td $bold>" . $course_name . "</td>
        <td $bold>" . $d['tutoring_type'] . "</td>
        <td $bold>".substr(strip_tags(html_entity_decode($d['message'])), 0, 60)."
        <td align=center $bold>" . showdate($d['created_at']) . "</td>
        <td $bold>" . $status . "</td>
        <td align=center $bold>
            <a   href='".basename($_SERVER['PHP_SELF'],".php") ."?id=" . $d['msg_id'] . "&status=" . $change."'>Change Status</a> | 
            <a href=\"javascript:if(confirm('Are you sure you wish to delete?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?del_id=" . $d['msg_id'] . "';}\">Delete</a>
        </td>
    </tr>";     
     
	$j++;
 }
	
}
else
{
	echo"<font color=red>No Record found.</font>";
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

<?if($total>100){echo"<ul class='pagination'>" . paging($total,"","id=".$_GET['id']."&status=".$_GET['status']) . "</ul>";}?>

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
            url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax", // Replace with the actual URL of your server-side script
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
<?include("includes/footer.php");?>