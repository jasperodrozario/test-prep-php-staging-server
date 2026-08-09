if(location.hostname=="localhost" || location.hostname=="192.168.1.14")
{
	var vpath = "http://"+location.hostname+"/eduon/admincp/"; 	
 
}	
else
{
	var vpath = "https://"+location.hostname+"/admincp/"; 	
}

 
 
 
 
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
 

 
function goBack() {
  window.history.back();
}
 
 

// AJAX call for autocomplete 
$(document).ready(function(){

   $("#form2").submit(function(event){
 
 	event.preventDefault();
    $('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
    dataType : "json",	
    type: "POST",
    url: vpath+"ajax-admin",
    data: new FormData(this),
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
    	
       // alert(data);
        $("#response").html(data.msg);
        $("#name").val("");
        $("#id").val("");
        if(data.list)
	    {
			$(".list").html(data.list);
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
   
$("#form").submit(function(event){
 
    event.preventDefault();
    $('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",	
    url: vpath+"ajax-admin",
    data: new FormData(this),
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
    
    	// alert(data);
      // $("#response").html(data);
        if(data.message)
        {
       	  $("#response").html(data.message);
		}
		if(data.msg)
		{
			$("#response").html(data.msg);
		 
		}
	    if(data.next)
        {
       	  	window.location=data.next;
		}
		if(data.list)
        {
       	  	$("#list").html(data.list);
		}
        $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  
    })
    .fail(function() 
    {
         
            // just in case posting your form failed
           alert( "Posting failed." );
             
    });
 
        // to prevent refreshing the whole page page
        return false;
 
  });
    $("#form-content").submit(function(event){

//CKEDITOR.instances['contents'].updateElement();
    //CKEDITOR.instances['details'].updateElement();
   // CKEDITOR.instances['curriculam'].updateElement();
    if(CKEDITOR.instances)
    {

    	for ( instance in CKEDITOR.instances ) {
        	CKEDITOR.instances[instance].updateElement();
   	 }
    }
    event.preventDefault();
    $('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",	
    url: vpath+"ajax-admin",
    data: new FormData(this),
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
    
    	// alert(data);
      // $("#response").html(data);
        if(data.message)
        {
       	  $("#response").html(data.message);
		}
		if(data.msg)
		{
			$("#response").html(data.msg);
		 
		}
	    if(data.next)
        {
       	  	window.location=data.next;
		}
		if(data.list)
        {
       	  	$("#list").html(data.list);
		}
        $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  
    })
    .fail(function() 
    {
         
            // just in case posting your form failed
           alert( "Posting failed." );
             
    });
 
        // to prevent refreshing the whole page page
        return false;
 
  });
    
 $("#save").click(function(event){
 
 	event.preventDefault();
    $('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
     url: vpath+"ajax-admin",
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
})


 

 $(document).ready(function()
{
    $("#cat_id").change(function()
    {
         
        var id=$(this).val();
        //var dataString = 'parent_id='+ id;
        $("#subject").find('option').remove();
         
        $.ajax
        ({
        type: "POST",
        url: vpath+"ajax-admin",
        data: "cat_id="+id+"&get_subject_option=1"
    
        })
        .done(function(html)
        {
             
            $("#subject").html(html);
        }) 
    });
    
    

});



