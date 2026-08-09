var vpath = "http://"+location.hostname+"/printmate/admincp/"; 	
 
function getList(x)
{
 
    $('#list').html("Loading response...");
    $.ajax({
    	dataType : "json",
        type: 'POST',
        url: vpath+'ajax-admin', 
        data: "client_id="+x+"&action=assignment"
    })
    .done(function(data){
    	   
        $('#list').html(data.list);
     
    })
    .fail(function() {
        alert( "Posting failed." );
    });
    	
}   
// AJAX call for autocomplete 
jQuery(document).ready(function()
{ 
  $("#company_name").keyup(function(){
  	
  
    $.ajax({
    type: "POST",
    dataType : "json",
    url: vpath+'ajax-admin',
    
    data:'action=get_company&pay_type=credit&company_name='+$(this).val(),
    
    beforeSend: function(){
      $("#company_name").css("background","#FFF url("+vpath+"img/loaderIcon.gif) no-repeat 165px");
    },
    success: function(data)
    {
      
      $("#suggesstion-box").show();
      $("#suggesstion-box").html(data.list);
      $("#company_name").css("background","#FFF");
    }
    });
  });
  
 
}); 

function setName(id,value) {
	
	$("#client_id").val(id);
	$("#company_name").val(value);
	$("#suggesstion-box").hide();
	getList(id);
	
}

function setManifestName(id,value,city) {
	
	$("#pod_id").val(id);
	$("#courier_no").val(value);
	$("#city").val(city);
	$("#suggesstion-box").hide();
	
}
// AJAX call for autocomplete 
jQuery(document).ready(function()
{
  
 
  $("#courier_no1").keyup(function(){
  	
    $.ajax({
    type: "POST",
  
    dataType : "json",
    url: vpath+'ajax-admin',
    
    data:'action=get_courier_no&courier_no='+$(this).val()+'&origin_office_id='+$("#origin_office_id").val()+'&dest_office_id='+$("#dest_office_id").val(),
    
    beforeSend: function(){
      $("#courier_no").css("background","#FFF url("+vpath+"img/loaderIcon.gif) no-repeat 165px");
      
    },
    success: function(data)
    {
      //alert(data);
      $("#response").html(data.msg);
      $("#suggesstion-box").show();
      $("#suggesstion-box").html(data.list);
      $("#courier_no").css("background","#FFF");
    }
    });
  });
  
  
  $("#origin_office_id").change(function(){
  	
    $.ajax({
    type: "POST",
  
    dataType : "json",
    url: vpath+'ajax-admin',
    
    data:'action=get_manifest&origin_office_id='+$(this).val(),
    
    beforeSend: function(){
      $("#manifest").css("background","#FFF url("+vpath+"img/loaderIcon.gif) no-repeat 165px");
      
    },
    success: function(data)
    {
      //alert(data);
      $("#response").html(data.msg);
     
      $("#manifest").html(data.list);
      $("#manifest").css("background","#FFF");
      
    }
    });
  });
  
  $("#delivery_man_id").change(function(){
  	
    $.ajax({
    type: "POST",
  
    dataType : "json",
    url: vpath+'ajax-admin',
    
    data:'action=get_drslist&delivery_man_id='+$(this).val(),
    
    beforeSend: function(){
      $("#list").css("background","#FFF url("+vpath+"img/loaderIcon.gif) no-repeat 165px");
      
    },
    success: function(data)
    {
      //alert(data);
      $("#response").html(data.msg);
     
      $("#list").html(data.list);
      $("#list").css("background","#FFF");
      
    }
    });
  });
  
 
}); 


$(document).ready(function() {
  $( function() {
    $( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $( "#date2" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $( "#invoice_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $( "#paid_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
 } );
 
 
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
    	
    	//alert(data);
    	 
        if(data.message)
        {
       	  $("#response").html(data.message);
		}
		if(data.msg)
		{
			$("#response").html(data.msg);
		 
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
   
    
    $("#origin_state_id").change(function()
    {
         
        var id=$(this).val();
          
        $.ajax
        ({
        	type: "POST",
        	dataType : "json",	
        	url: vpath+"ajax-admin",
        	data: "action=city_list&state_id="+id
    
        })
        .done(function(html)
        {
            //alert(html)
            
            $("#origin_city_id").html(html.options);
        }) 
    });
    
  
    $("#to_state_id").change(function()
    {
         
        var id=$(this).val();
          
        $.ajax
        ({
        	type: "POST",
        	dataType : "json",	
        	url: vpath+"ajax-admin",
        	data: "action=city_list&state_id="+id
    
        })
        .done(function(html)
        {
            //alert(html)
            
            $("#to_city_id").html(html.options);
        }) 
    });
    
   
    $("#file").change(function()
    { 
        var formData = new FormData($('#form')[0]);
      
        formData.append('action', 'pod-list');  
        $.ajax
        ({
        	type: "POST",
        	dataType : "json",	
        	url: vpath+"ajax-admin",
        	data: formData,
        	contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false   
    
        })
        .done(function(html)
        {
        	//alert(html);
            
            $("#list").html(html.list);
            $("#total").html(html.total);
        }) 
    });
    
});

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



