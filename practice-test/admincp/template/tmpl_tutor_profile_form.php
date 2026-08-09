<div id='<?php echo $action?>-response'></div>
<form id="<?php echo $action?>-form" method="post" enctype="multipart/form-data">
<input  type="hidden" name="user_id" value="<?php echo $_REQUEST['user_id'];?>">
<input  type="hidden" name="action" value="<?php echo $action?>">

<?
 
switch($action)
{
	case"add-tutor-login":
	?>
	
     
        <div class="row form-group">
            <div  class="col-lg-3 control-label" for="digits">Email </div>
            <div class="col-lg-9">
                <input class="form-control"  value="<?php if(isset($db_value['email'])){echo $db_value['email'];} ?>" name="email" type="text" placeholder="Email" required/>
            </div>
        </div>
        <?php if (!$db_value['user_id']) { ?>
        	<div class="row form-group">
                <div  class="col-lg-3 control-label" for="digits">Password </div>
                <div class="col-lg-9">
                    <input class="form-control"  name="password" type="password" placeholder="Password" required/>
                </div>
        	</div>
        <?php } ?>
         <?php if ($db_value['user_id']) { ?>
        	<div class="row form-group">
                <div  class="col-lg-3 control-label" for="digits">Password </div>
                <div class="col-lg-9">
                    <input class="form-control"    name="password" type="password"   />[keep empty if you dont want to change Password]
                </div>
        	</div>
        <?php } ?>
        <div class="row form-group">
            <div  class="col-lg-3 control-label" for="digits">Status </div>
            <div class="col-lg-9">
                <select name="status" id="status" class="form-control" size="1" required>
                        <option value="Y" <?php if(isset($db_value['status']) && $db_value['status']=='Y'){echo 'selected';} ?>>Active</option>
                        <option value="N" <?php if(isset($db_value['status']) && $db_value['status']=='N'){echo 'selected';} ?>>Inactive</option>
                </select>
            </div>
        </div>
         <div class="row form-group">
        	<div class="col-lg-3"></div>
            <div class="col-lg-9">
                <button type="submit" name="general" class="btn btn-primary"><i class="i-checkmark-circle-2" aria-hidden="true"></i> Save changes</button>
                <button type="reset"  class="btn btn-warning"><i class="i-close-3" aria-hidden="true"></i> Reset</button>

                <button type="button" onclick="history.back();" class="btn btn-warning"><i class="i-close-3" aria-hidden="true"></i> Cancel</button>

            </div>
           	
        </div><!-- End .form-group  -->
    <?
    break; 
    case "tutor-info":
    case "tutor-skill":
        
       
      	echo makeForm($form_fields);
    
    break;
    
	
    case "notes":
    ?>
    	<ul class="nav nav-tabs">
  			<li class="active"><a data-toggle="tab" href="#noteshome">Notes</a></li>
  			<li><a data-toggle="tab" href="#notesmenu1" class='total'>Notes List (<?=count($notes);?>)</a></li>
 
		</ul>
		<div class="tab-content">
		  <div id="noteshome" class="tab-pane fade in active">
	      <table  width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
	 
		  <tr><td valign="top" >Notes</td><td ><textarea  cols="50" rows="2" name="notes" class='form-control' Placeholder="Add Notes" required></textarea></td></tr>
		  <tr ><td></td><td height="25"  ><input type="submit" class="btn btn-success" name='submit' value='Submit'></td></tr>
		  </table>
	    </div>
		<div id="notesmenu1" class="tab-pane fade list">
		<?
		if(count($notes))
		{
			echo "<table class='table table-bordered table-striped'>";
	        foreach($notes as $d)
	        {
				echo "<tr><td width=20%>".showdate($d['date'])."</td><td>".html_entity_decode($d['notes']) ."</td></tr>";
			} 
	        echo "</table>";
		}    
        
        ?>
		</div>
		</div>
	  
      <?
	break;	 
    case "mail":
     
      ?>
      	<ul class="nav nav-tabs">
  			<li class="active"><a data-toggle="tab" href="#mailhome">Send Mail To Tutor </a></li>
  			<li><a data-toggle="tab" href="#mailmenu1" class='total'>Outbox (<?=count($mails);?>)</a></li>
 
		</ul>

		<div class="tab-content">
		  	<div id="mailhome" class="tab-pane fade in active">
		      <table  width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
		 
		      <tr><td valign="top" >Subject</td><td ><input type='text' name="subject" class='form-control' Placeholder="Add Subject" required></td></tr>
			
			  <tr><td valign="top" >Message</td><td ><textarea  cols="50" rows="6" name="message" class='form-control' Placeholder="Add Message" required></textarea></td></tr>
			  <tr ><td></td><td height="25"  ><input type="submit" class="btn btn-success" name='submit' value='Send'></td></tr>
			  </table>
			   	
        	</div>
			<div id="mailmenu1" class="tab-pane fade list">
		     	<?
				if(count($mails))
				{
					echo "<table class='table table-bordered table-striped'>";
			        foreach($mails as $d)
			        {
						echo "<tr><td width=20%>".showdate($d['date'])."</td><td width=20%>" . $d['subject'] ."</td><td>".html_entity_decode($d['message']) ."</td></tr>";
					} 
			        echo "</table>";
				}    
        
        		?>
			</div>
		</div>
    <?
    break;
    
	default:
        echo"<table class='table table-bordered table-striped>";
        echo"<thead><th>Id</th><th>First Name</th><th>Last Name</th><th>Mobile</th><th>City</th></thead><tbody>";
        foreach($db_list as $d)
        {
			$city=getFldValueById("city","name",array("id"=>$d['city_id']));
			echo"<tr><td>" .$d['user_id'] ."</td><td>" .$d['fname']."</td><td>".$d['lname']."</td><td>".$d['mobile']."</td><td>".$city."</td></tr>";
		}	
	    echo"</tbody></table>";

   
} 
?>
</form>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->

	
		
<script type="text/javascript">
 
function setActiveTab(tab,id,table) 
{
 	$("#home").html("");
 	$('a[href="#' + tab + '"]').trigger('click'); 
 	$.ajax
		({
		type: "POST",
		url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
		data: "id="+id+"&form="+table 

		})
		.done(function(html)
		{
			 
			$("#home").html(html);
		}) ;
 	
   
} 

$(document).ready(function()
{
	$("#country_id").change(function()
	{
		 
		var id=$(this).val();
		//var dataString = 'parent_id='+ id;
		$("#state_id").find('option').remove();
		 
		$.ajax
		({
		type: "POST",
		dataType : "json",
		url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
		data: "country_id="+id+"&action=get_state_option" 
    
    	})
		.done(function(html)
		{
			 
			$("#state_id").html(html.list);
		}) 
	});

   $("#state_id").change(function()
	{
		 
		var id=$(this).val();
		//var dataString = 'parent_id='+ id;
		$("#city_id").find('option').remove();
		 
		$.ajax
		({
		type: "POST",
	 
        dataType : "json",
		url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
		data: "state_id="+id+"&action=get_city_option" 
    
    	})
		.done(function(html)
		{
			 
			$("#city_id").html(html.list);
		}) 
	});

 

$("#<?php echo $action?>-form").submit(function(event){
 
 	event.preventDefault();
    
 	//var formData = new FormData($('#<?=$info?>-form')[0]);
    $('#<?php echo $action?>-response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
    	
     	
        $("#<?php echo $action?>-response").html(data.msg);
	    if(data.list)
	    {
			$(".list").html(data.list);
		}
		if(data.next)
	    {
			window.location=data.next;
		}  
	    if(data.total)
	    {
			$(".total").html(data.total);
		} 
        $('html, body').animate({scrollTop: 0}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
    	
    	    
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
     
 });
 function deleteImage(x)
 {
 	
 	$('#<?=$info?>-response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: "del_id="+x+"&user_id="+<?=(int)$_REQUEST['user_id']?>+"&delete_picture=1"
  
    })
    .done(function(data)
    {
    
		$("#<?=$info?>-response").html(data.msg);
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	    if(data.total)
	    {
			$(".total").html(data.total);
		} 
    })
    .fail(function() 
    {
        // just in case posting your form failed
        alert( "Posting failed." );
    });
 
 }

function deleteVideo(x)
 {
 	
 	$('#<?=$info?>-response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: "del_id="+x+"&user_id="+<?=(int)$_REQUEST['user_id']?>+"&delete_video=1"
  
    })
    .done(function(data)
    {
    
		$("#<?=$info?>-response").html(data.msg);
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	    if(data.total)
	    {
			$(".total").html(data.total);
		} 
    })
    .fail(function() 
    {
        // just in case posting your form failed
        alert( "Posting failed." );
    });
 
 }
function getDelete(table,auto_id,id)
{
 	$('#<?=$action?>-response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: "college_id=<?=$_REQUEST['college_id'];?>&table="+table+"&auto_id="+auto_id+"&del_id="+id
  
    })
    .done(function(data)
    {
    
		$("#<?=$action?>-response").html(data.msg);
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	    if(data.total)
	    {
			$(".total").html(data.total);
		} 
    })
    .fail(function() 
    {
        // just in case posting your form failed
        alert( "Posting failed." );
    });
 
 }

</script>

 
