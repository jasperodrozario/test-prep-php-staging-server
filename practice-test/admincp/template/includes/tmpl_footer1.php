
<!-- BEGIN FOOTER -->
<div><br>
</div>
<!-- BEGIN FOOTER -->
		<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div><!-- /.rightside --> 
<div style="clear:both"><br></div>
<footer>
	<div class="pull-left" style="text-align:center;">
		<span class="pull-left" width:100%;"><?php echo copyright('admin_footer');?></span>
	</div>
</footer>
</div><!-- /.wrapper -->
		
   
	<!-- END CONTENT -->
	 <script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/holder.js"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/js/core.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	
	<!-- flot chart -->
	<script src="<?=$configs['tpath'];?>assets/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/flot/jquery.flot.grow.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
	
	<!-- bootstrap slider -->
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap-slider/js/bootstrap-slider.js" type="text/javascript"></script>
	
	<!-- datepicker -->
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
	
	<!-- vectormap -->
	<script src="<?=$configs['tpath'];?>assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/jquery-jvectormap/jquery-jvectormap-europe-merc-en.js" type="text/javascript"></script>
	
	<!-- counter -->
	<script src="<?=$configs['tpath'];?>assets/plugins/jquery-countTo/jquery.countTo.js" type="text/javascript"></script>
	
	<!-- rickshaw -->
	<script src="<?=$configs['tpath'];?>assets/plugins/rickshaw/vendor/d3.v3.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/rickshaw/rickshaw.min.js" type="text/javascript"></script>
	
	<!-- maniac -->
	<script src="<?=$configs['tpath'];?>assets/js/maniac.js" type="text/javascript"></script>
    <script src="<?=$configs['tpath'];?>assets/js/jquery.nicescroll.min.js" type="text/javascript"></script>
   	<!-- dashboard -->
   	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
	<script type="text/javascript">
		maniac.loadchart();
		maniac.loadvectormap();
		maniac.loadbsslider();
		maniac.loadrickshaw();
		maniac.loadcounter();
		maniac.loadprogress();
		maniac.loaddaterangepicker();
	</script> 

<script>
$(document).ready(function() {
  $( function() {
    $( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $( "#event_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
 } );  
</script>
 

<!--<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>-->
    
 <!-- Modal HTML -->
<div id="myModalEdit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content" style='width:500px'>
            <!-- Content will be loaded here from "remote.php" file -->
        </div>
    </div>
</div>
</body>
</html> 
    
<script>
$(".story").hide();
$("#cat_id").change(function()
{
	 
	var id=$(this).val();
	
	if(id==71)
	{
	  $(".story").show();
    }
  
		 
});
  $( function() {
    $( "#tabs" ).tabs({
     
      beforeLoad: function( event, ui ) 
      {
        $('#response').html("");
         
        
        ui.jqXHR.fail(function() 
        {
          $('.ui-tabs-panel').html("");
          ui.panel.html("Loading....");
        });
      }
    });
  } );
 

function selectState(id,value) {
$("#state_id").val(id);
$("#state_name").val(value);
$("#state-suggesstion-box").hide();
}
function selectCity(id,value) {
$("#city_id").val(id);
$("#city_name").val(value);
$("#city-suggesstion-box").hide();
}

function selectLocality(id,value) {
$("#locality_id").val(id);
$("#locality_name").val(value);
$("#locality-suggesstion-box").hide();	
}
</script>  
<script>
function goBack() {
  window.history.back();
}

// AJAX call for autocomplete 
$(document).ready(function()
{

   
    
	$("#form").submit(function(event){
 
 	event.preventDefault();
 	CKEDITOR.instances['question'].updateElement();
    <?
 	if($option_no==4)
 	{
 	
 		?>
 		CKEDITOR.instances['answer1'].updateElement();
 		CKEDITOR.instances['answer2'].updateElement();
 		CKEDITOR.instances['answer4'].updateElement();
 		CKEDITOR.instances['answer3'].updateElement();
 		<?
 	}	
 	?>
 	CKEDITOR.instances['explanation'].updateElement();
  
 	  	
 	 
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

        //alert(data);
        
        $("#response").html(data.msg);
           
      // $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  

        if(data.list)
	    {
			$(".list").html(data.list);
		} 
		if(data.url)
	    {
			window.location=data.url;
		} 
		if(data.total)
	    {
			$(".total").html(data.total);
		} 
      
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
            // just in case posting your form failed
           alert(textStatus);
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    // AJAX call for autocomplete 
    
    
    $("#lesson_form").submit(function(event){
 
 	event.preventDefault();
    <?
    if($_GET['page']=="lesson")
    {
    ?>	
     CKEDITOR.instances['details'].updateElement();
    <?
	}
	?>
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

        //alert(data);
        
        $("#response").html(data.msg);
           
      // $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  

        if(data.list)
	    {
			$(".list").html(data.list);
		} 
		if(data.url)
	    {
			window.location=data.url;
		} 
		if(data.total)
	    {
			$(".total").html(data.total);
		} 
      
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
            // just in case posting your form failed
           alert(textStatus);
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    
    $("#lesson_video_form").submit(function(event){
 
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

        //alert(data);
        
        $("#response").html(data.msg);
           
      // $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  

        if(data.list)
	    {
			$(".list").html(data.list);
		} 
		if(data.url)
	    {
			window.location=data.url;
		} 
		if(data.total)
	    {
			$(".total").html(data.total);
		} 
      
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
            // just in case posting your form failed
           alert(textStatus);
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    
    $("#form2").submit(function(event){
 
    event.preventDefault();
    $('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
  
    type: "POST",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: new FormData(this),
    contentType: false,       // The content type used when sending data to the server.
    cache: false,             // To unable request pages to be cached
    processData:false   
    })
    .done(function(data){
        //alert(data);
         
        $("#response").html(data);
   
      
       $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
         
            // just in case posting your form failed
           alert(textStatus);
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });   
 $("#save").click(function(event){
 
 	event.preventDefault();
    $('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: new FormData($('#form')[0]),
 
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
       
       $("#response").html(data);
      
       //$('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  
    })
    .fail(function() 
    {
         
            // just in case posting your form failed
           alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });   
 });
 
 $("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

$('#myModalEdit').on('hidden', function() {
    $(this).data('modal').$element.removeData();
});

function deleteFile(action,id,x)
{
 	$('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: "action="+action+"&id="+id+"&lesson_id=<?=$_REQUEST['lesson_id'];?>"
   
    })
    .done(function(data)
    {
        //alert(data);
        $("#response").html(data.msg);
        $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  
	    if(data.list)
	    {
			$("#"+x).html(data.list);
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
 
function editFile(action,id,x)
{
 	$('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: "action="+action+"&id="+id+"&lesson_id=<?=$_REQUEST['lesson_id'];?>"
   
    })
    .done(function(data)
    {
        //alert(data);
        $("#response").html(data.msg);
        $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  
	    if(data.list)
	    {
			$("#"+x).html(data.list);
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

 
 
<link rel="stylesheet" href="../summernote/summernote-lite.css">  
<script type="text/javascript" src="../summernote/summernote-lite.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script>
<script src="../summernote/summernote-math.js"></script>
<script>
      $('.description').summernote({height:75,
       
        
        toolbar: [
        // [groupName, [list of button]]
        ['style', ['bold', 'italic', 'underline']],
       ['color', ['color']],
        ['fontsize', ['fontsize']],
        
    ['para', ['ul', 'ol', 'paragraph']],
    
    ['table', ['table']],
         ['insert', ['link', 'picture','math', 'hr','codeview']],
      ],
     
      });
       
      
 
// AJAX call for autocomplete 


function getFld(x)
{
	
 	$.ajax({
    type: "POST",
    url: "ajax",
    data: "nos="+x+"&answer_fld=1"
   
    })
    .done(function(data){
       // alert(data);
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