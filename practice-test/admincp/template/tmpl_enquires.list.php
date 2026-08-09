<div id="response"></div>

<form method='post' action="<?=basename($_SERVER['PHP_SELF'],".php");?>?reg_at=<?=$_REQUEST['reg_at'];?>">
<input type='hidden' name='reg_at'  value='<?=$_REQUEST['reg_at'];?>'>
<div class='row'><div class='col-md-8'><input type='text' class='form-control' name='key' placeholder='Search By ID / Email Id / Phone' autocomplete="off"></div><div class='col-md-2'><input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'></div>
<div class='col-md-2'>
<!--
<select name='test_id' class='form-control' onchange='window.location=this.value'>
<option value='<?=$configs['admin_path'];?>enquires.list'>All Enquiries</option>
<?
foreach($test as $t)
{
   if($t['id']==$_GET['test_id'])
   {
   	  echo"<option value='".$configs['admin_path']."enquires.list?reg_at=".$_GET['reg_at']."&test_id=".$t['id']."' selected>".$t['name'] ." [" . $student_total[$t['id']]."]</option>"; 	

   }
   else
   {
   	   echo"<option value='".$configs['admin_path']."enquires.list?reg_at=".$_GET['reg_at']."&test_id=".$t['id']."'>".$t['name'] ." [" . $student_total[$t['id']]."]</option>"; 	

   }	
}	
?>	
</select>	
-->
	
</div>

</div>
</form>
<br>		 
<form method="post" action="<?=basename($_SERVER['PHP_SELF'],".php");?>">
 
<table id="table-1" width="100%"  border="0" align="center" cellspacing="1" cellpadding="4" class='table table-stripe table-bordered'>
<thead>
<tr >
    <td width=7% height=22><b>ID/User ID</b></td>
    <td width=20%><b>Sent By</b></td>
    <td><b>Course Name</b></td>
    <td><b>Source</b></td>
    <td width=35%><b>Content</b></td>
    <td align=center><b>Date</b></td>
    <td align=center><b>Status</b></td>
    <td  align=center><b>Action</b></td>
</tr>
</thead>
<tbody>
<?
if($total)
{

 foreach($db_list as $d) 
 {
  
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
    
    $reg_at = "-";
    if($d['reg_at']){
        $reg_at = $d['reg_at'];
    }
    else{
        $reg_at = getFldValueById("user", "reg_at", array("user_id" => $d['user_id']));
    }

    echo "
    <tr $back_color>
        <td $bold>" . $d['id'] . "/".$d['user_id']."</td>
        <td $bold>" . $d['first_name'] . " " . $d['last_name'] . "<br>" . $d['email'] . "<br>". $d['mobile'] . "</td>
        <td $bold>"  . $test_name[$d['test_id']]. "</td>
        <td $bold>" . $reg_at . "</td>
        <td $bold><strong>Subject: ".html_entity_decode($d['subject'])."</strong><br>Message: ".html_entity_decode($d['message'])."
        <td align=center $bold>" . showdate($d['date']) . "</td>
        <td $bold>" . $status . "</td>
        <td align=center $bold>
            <a   href='".basename($_SERVER['PHP_SELF'],".php") ."?id=" . $d['id'] . "&status=" . $change."'>Change Status</a> | 
            <a   href='send-email-form?id=" . $d['id'] . "&email=" . $d['email']."&name=".$d['first_name'] . " " . $d['last_name']."'>Send Email</a> |
             
            <a href=\"javascript:if(confirm('Are you sure you wish to delete?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?del_id=" . $d['id'] . "';}\">Delete</a>
        </td>
    </tr>";     
     
	$j++;
     
//     <a   href='#' class='set-order-btn' data-eid=".$d['id']." data-toggle='modal' data-target='#sendEmailModal'>Send Email</a> |
     
     
//	echo"<tr  ><td >" . $d['id'] . "</td><td>"  . substr(strip_tags(html_entity_decode($d['message'])),0,60). "..</td><td>"  . $d['name']. "</td><td>"  . $test_name[$d['test_id']]. "</td><td >" . $d['email'] . "</td>
//	<td >" . $d['mobile'] . "</td><td align=center>" . showdate($d['date']) ."</td><td align=center><a   href='enquiry.entry?id=" . $d['id'] . "'>View</a> | <a   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?del_id=" . $d['id'] . "';}\">Delete</a></td></tr>";
   
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