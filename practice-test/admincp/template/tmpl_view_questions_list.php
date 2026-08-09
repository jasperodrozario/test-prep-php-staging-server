<hr style='margin-top:0'>

<div id='response'></div>
 
<form method="post" id='form'>
<input type='hidden' name='set_id' value='<?=$_GET['set_id'];?>'>
<input type='hidden' name='set_update' value='1'>
<table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
	<thead>
	<tr><td><b>Id</b></td><td><b>Questions</b></td><td><b>Subject</b></td><td><b>Chapter</b></td><td><b>No Options</b></td><td><b>Status</b></td> <td align='center' width=12%><b>Option</b></td></tr>	
	</thead>
	
	<tbody>
	    <?php
		$j=0;
		foreach($db_values as $d)
		{
			
			$chapter=getFldValueById("chapter","name",array("id"=>$d['chapter_id']));
 
			$subject=getFldValueById("course_subject","subject",array("sub_id"=>$d['sub_id']));

			$course_name=getFldValueById("course","name",array("course_id"=>$d['course_id']));

  	 
			echo"<tr ><td>".$d['id']."</td><td>".html_entity_decode($d['questions'])."</td><td>".$subject."</td><td>".$chapter."</td><td>".$d['no_options']."</td><td>".$d['status']."</td><td align='enter'><i class='fa fa-pencil-square-o'> <a  href='questions_form.php?id=".$d['id']."&chapter_id=" . $d['chapter_id'] ."&sub_id=" . $d['sub_id']."&course_id=" . $d['course_id']."' >Edit</a> | <i class='fa fa-times'></i> <a  href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location.href='view_questions_list.php?del_id=".$d['id']."&chapter_id=" . $d['chapter_id'] ."&sub_id=" . $d['sub_id']."&course_id=" . $d['course_id']."'}\">Delete</a></td></tr>";	    
	    }	
	    ?>
	</tbody>
</table>
</form>
<?
if($total>20):

echo paging(20,$total,'$param');

endif;
?>

<!-- Modal HTML -->
<div id="myModalEdit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content" style='width:600px'>
            <!-- Content will be loaded here from "remote.php" file -->
        </div>
    </div>
</div>

<script>
$(document).on("hidden.bs.modal", function (e) {
    $(e.target).removeData("bs.modal").find(".modal-content").empty();
});
</script>

<style>
.load-more {
    margin: 15px 25px;
    cursor: pointer;
    padding: 10px 0;
    text-align: center;
    font-weight:bold;
}	
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>


// AJAX call for autocomplete 


function getFld(x)
{
	
 	$.ajax({
    type: "POST",
    url: "ajax.php",
    data: "nos="+x+"&answer_fld=1"
   
    })
    .done(function(data){
        
       $("#ansfld").html(data);
    })
    .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
    });

}
function toggle(x) 
{
	  var y = document.getElementById(x);

	 
	  if (y.style.display === "none") {
	    y.style.display = "block";
	  } else {
	    y.style.display = "none";
	  }
}
function getOptions(x) {
  var y = document.getElementById("options");
  if (x== "mcq") {
    y.style.display = "block";
  } else {
    y.style.display = "none";
  }
}
</script>
 
<script>

// AJAX call for autocomplete 
$(document).ready(function(){

$("#update").click(function(){
  event.preventDefault();
  
  

  
    $('#response').html("loading....");
    var formData = new FormData($('#form')[0]);
    $.ajax({
    type: "POST",
    url: "ajax.php",
    data: formData,
    contentType: false,       // The content type used when sending data to the server.
  cache: false,             // To unable request pages to be cached
  processData:false   
    })
    .done(function(data){
       

       $("#response").html(data);
  
    })
    .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
 });
</script>