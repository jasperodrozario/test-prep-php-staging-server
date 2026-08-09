<?
include("../common/access.php");
include("../common/autoload.php");
if($_REQUEST['id'])
{
	$db_value= getDataByWhere('questions_set','*',array('id'=>$_REQUEST['id']));

}
$courses= getQuery('course','*',array(),'name');

$subjects= getQuery('exam_category','*',array('parent_id'=>0),"name");
?>
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload()" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button><h4 class="modal-title" id="myModalLabel">Questions Set Form </h4></div><div class="modal-body" >
<div id="questions_set_response"></div>

<form method="post" id="questions_set" enctype="multipart/form-data">
<input type='hidden' name="questions_set" value=1>
<input type='hidden'  name='id' value='<?php echo $db_value['id'];?>' >
<div class='row form-group'><div class='col-md-3'><label class='form-control-label'>Set Name</label></div><div class='col-md-9'><input type='text' class='form-control' name='set_name' value='<?php echo $db_value['set_name'];?>' id='set_name' REQUIRED></div></div>
<div class='row form-group'><div class='col-md-3'><label class='form-control-label'>Courses</label></div><div class='col-md-9'>
<select name='course_id' class='form-control' size=1 required>
<option value=''>Select Course</option>
<?
foreach($courses as $c)	
{
   if($db_value['course_id']==$c['course_id'])
   {
   	   echo"<option value='" .$c['course_id'] . "' selected>" . $c['name'] ."</option>";	
   }
   else
   {
   		 echo"<option value='" .$c['course_id'] . "' >" . $c['name'] ."</option>";	
  
   }
	
}
?>
</select>

</div></div>

<div class='row form-group'><div class='col-md-3'><label class='form-control-label'>Timer [Minutes]</label></div><div class='col-md-9'><input type='text' class='form-control' name='timer' value='<?php echo $db_value['timer'];?>' id='timer' REQUIRED></div></div>
<div class='row form-group'><div class='col-md-3'><label class='form-control-label'>Instruction</label></div><div class='col-md-9'><textarea  class='form-control' name='instruction' id='instruction' REQUIRED><?php echo html_entity_decode($db_value['instruction']);?></textarea></div></div>
<div class='row form-group'><div class='col-md-3'><label class='form-control-label'>Status</label></div><div class='col-md-9'><input type='radio'  name='status' id='status' value='A' REQUIRED <?if($db_value['status']=='A'){echo'checked';}?>> Yes &nbsp<input type='radio'  name='status' id='status' value='S' REQUIRED <?if($db_value['status']=='S'){echo'checked';}?>> No &nbsp</div></div>
<div class='row form-group'><div class='col-md-3'></div><div class='col-md-9'><input type='submit'  name='submit' value='Update' class='btn btn-success'>
</div></div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
// AJAX call for autocomplete 
$(document).ready(function(){

$("#questions_set").submit(function(event){
 	event.preventDefault();
    $('#questions_set_response').html("<img src='<?=$configs['vpath'];?>img/ajax-loader.gif'>");
    $.ajax({
    type: "POST",
    url: "ajax.php",
    data: new FormData(this),
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
       
       $("#questions_set_response").html(data);
  
        
	
    })
    .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
 });
</script></div></div> 
