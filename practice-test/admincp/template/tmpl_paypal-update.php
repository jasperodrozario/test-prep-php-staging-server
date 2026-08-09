<div id='response'></div>
<form method="post" id='form11' enctype="multipart/form-data">
<input type='hidden' name='action' value='paypal-update'>
<input type="hidden" name="user_id" value="<?=$_GET['user_id']?>">
<h3>Update Paypal Info</h3>
<table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
<tr><td align="left" width=20%>User Id</td><td> <?php echo $db_value['user_id'];?> </td></tr>  
<tr><td align="left" width=20%>Name</td><td> <?php echo $db_value['first_name']." ".$db_value['last_name'];?> </td></tr> 
<tr><td align="left" width=20%>Paypal Id</td><td><input type="text" class='form-control' name='paypal_id' value="<?php echo $db_value['paypal_id'];?>"  required></td></tr> 
<tr><td></td><td ><input type='submit'  name='SBMT_REG' value='Update' class='btn btn-success'></td></tr>
</table>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  
<script>
	$("#form11").submit(function(event){
 	 event.preventDefault();
    $('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
   dataType : "json",	
    type: "POST",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: new FormData(this),
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){

        
        $("#response").html(data.msg);
       // $('.modal-content').empty();  
    
      
    })
});  
</script>

