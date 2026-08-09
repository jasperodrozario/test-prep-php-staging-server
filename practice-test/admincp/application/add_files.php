<?
include("../configs/license.php");
include("../configs/common.php");
if($_REQUEST['id'])
{
	$data=getDataByWhere("course_subject_material","*",array("id"=>$_REQUEST['id']));
}
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&times;</button>
    <h4 class="modal-title">Add & Edit  Files</h4>
</div>
<div class="modal-body"><div id='response'></div>
   			<form class="form-horizontal" id='filesForm' method="post"  enctype='multipart/form-data'>
                    <input type="hidden" name="course_id" id="course_id" value="<?=$_REQUEST['course_id']?>" >
                    <input type="hidden" name="sub_id" id="sub_id" value="<?=$_REQUEST['sub_id'];?>" >
                     <input type="hidden" name="id" id="id" value="<?=$_REQUEST['id'];?>" >
                     
                    <div class="form-group">
                        <input type="text" name='name' id="name" value="<?=$data['name'];?>" class="form-control" placeholder="Enter Name" required>
                         
                    </div>
                    <div class="form-group help">
                        <input type="file"  name="file" id="file" class="form-control"  placeholder="Add Files">[PDF & MP4 Files]
                       
                      
                    </div>
                     <div class="form-group help">
                        <input type="text"  name="duration" id="duration" value="<?=$data['duration'];?>" class="form-control"  placeholder="Add Duration Hrs."> [in number hrs.]
                       
                      
                    </div>
                     <div class="form-group help">
                        <input type="text"  name="ord" id="ord" value="<?=$data['ord'];?>" class="form-control"  placeholder="Add Display Order">
                       
                      
                    </div>
                    <div class="form-group">
                        <div class="main-checkbox">
                            <input type="checkbox" value="1" id="checkbox1" id='paid' <?if($data['name']==1){ echo"checked";}?> name="paid">
                            <label for="checkbox1"> </label> <span class="text">Paid</span><button type="submit" class="btn btn-success" style='float:right;' >Upload</button>
                        </div>
                       
					
                    </div>
                </form>

                
</div>
<!--<div class="modal-footer">
   
    <!--<button type="button" class="btn btn-primary">Save changes</button>--> <!--<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>->
</div>-->


<script>
$(document).ready(function(){
    $('#filesForm').submit(function(e){
  
     e.preventDefault();
        // show that something is loading
        $('#response').html("<b>Loading response...</b>");

        //var file_data = $("#file").prop("files")[0];   // Getting the properties of file from file field
		var form_data = new FormData(this);                  // Creating object of FormData class
		//form_data.append("file", file_data)              // Appending parameter named file with properties of file_field to form_data
		//form_data.append("user_id", 123)   
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: 'upload_files.php', 
            contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false, 
            //data: $(this).serialize()
            data:form_data
        })
        .done(function(data){
            // alert(data);
            // show the response
            $('#response').html(data);
           // $('#loading').hide();
			//$("#message").html(data);
       
             
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