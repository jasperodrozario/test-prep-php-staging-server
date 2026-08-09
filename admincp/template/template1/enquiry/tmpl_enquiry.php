<?
if(!$modal){include($configs['admin_template_include']."includes/tmpl_breadcrumb.php");}?> 

<?
switch($action)
{
	case"add-enquiry":
	case"view-enquiry":
	    include($configs['admin_template_include']."enquiry/tmpl_add_enquiry.php");
    break;
    case"enquiry-list":

	?>
	    <form method="post" action="">
		<table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
			<thead>
			<tr><td width=10><input type='checkbox' id='checkAll'></td><td width=5%><b>Id</b></td><td width=10%><b>Contact Details</b></td><td><b>Subject</b></td><td><b>Phone</b></td><td><b>Date</b></td><td><b>Status</b></td> <td align='center' width="10%"><b>Option</b></td></tr>	
			</thead>
			
			<tbody>
			    <?php
			    if(!$total){
			        echo "<tr><td colspan='7' align='center'>No Enquiry Found.</td></tr>";
			    }
				$j=0;
				foreach($db_list as $d)
				{
					  	 
					echo"<tr ><td><input type='checkbox' name='ids[]' value='".$d['id']."'></td><td>".$d['id']."</td><td>".$d['name']."<br>".$d['email']."</td><td>".$d['subject']."</td><td>".$d['mobile']."</td><td>".showdate($d['date'])."</td><td>".$d['status']."</td>

						<td align=center class='cursor-pointer'> 
           <img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
		   
		   <div class='dropdown-menu'>           

            <a  href='view-enquiry?id=".$d['id']."' data-toggle='modal' data-target='#myModalEdit' class='dropdown-item'><i class='fa fa-eye' aria-hidden='true' ></i> View</a> 

            <a  href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='".$configs['admin_vpath'] ."enquiry/enquiry-list?del_id=" . $d['id']."';}\" class='dropdown-item'><i class='fa fa-trash' aria-hidden='true' ></i> Delete</a>

       
		  </div>
          
            </td>

					</tr>";	 

		 
			    }	
			    ?>
			</tbody>
		</table>
		<div class='row'>
		<div class='col-md-6'><input type='submit' class='btn btn-danger' value='Delete all Checked'></div>	
		 
		<div class='col-md-6'>
		<?
		if($total>20):

			echo "<ul class='pagination pull-right'>" . paging($total,"","enquiry-list") . "</ul>";

		endif;
		?>
		</div></div>
		</form>
	 
        <?
	break;
 
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div id="myModalEdit" class="modal fade">
    <div class="modal-dialog">
    <div class="modal-header enquiry-header">
			<h4 class="modal-title">Enquiry</h4>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
						   
						  
	</div>
					 
        <div class="modal-content">
            <!-- Content will be loaded here from "remote.php" file -->
        </div>
    </div>
</div>
<script>
 $(document).ready(function() {

    $('#enquiry_form').submit(function(){
        //event.preventDefault();
        $('#enquiry_response').html("Loading response...");
        $.ajax({
            type: 'POST',
			dataType : "json",	
            url: '<?=$configs['admin_vpath']?>ajax-admin', 
            data: $(this).serialize()
        })
        .done(function(data){
           
            $('#enquiry_response').html(data.msg);
              
             
        })
        .fail(function() {
            alert( "Posting failed." );
        });
        return false;
    });
    
    
});
</script>
