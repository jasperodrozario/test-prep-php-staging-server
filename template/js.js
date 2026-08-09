if(location.hostname=="localhost" || location.hostname=="192.168.1.14")
{
    var vpath = "http://"+location.hostname+"/eduon/";	
}
else
{
	var vpath = "https://"+location.hostname+"/";	
}

$(document).ready(function(){
    $('#contactForm').submit(function(){
     
        // show that something is loading
        $('#contact_response').html("<b>Loading response...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            dataType : "json",
            url: vpath+'ajax-home', 
            data: $(this).serialize()
        })
        .done(function(data){
            // alert(data);
            // show the response
            $('.list').html(data.list);
            $('#contact_response').html("");
			$('#contactForm').attr("style","display:none");
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    
    
});

 

 

// AJAX call for autocomplete 

$(document).ready(function(){


    $("#form").submit(function(event)
    {
     
        event.preventDefault();
        $('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
        $.ajax({
       
        type: "POST",
        dataType : "json", 
        url: vpath+"ajax-home",
        data: new FormData(this), 
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false   
        })
        .done(function(data)
        {
            
            //alert(data);
            
            $("#response").html(data.msg);
			 
			if(data.cartmsg)
            {
            	 $("#response1").html(data.cartmsg);
			} 
			if(data.emailmsg)
            {
            	 $("#response2").html(data.emailmsg);
			}       
            if(data.next)
            {
                
                window.location=data.next;
            } 
            if(data.list)
            {
                $(".list").html(data.list);
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
    



	$("#form-delete").submit(function(event)
	{
	 
	 	event.preventDefault();
	    $('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
	    $.ajax({
	   
	    type: "POST",
	 	 dataType : "json",
	    
	    url: vpath+"ajax-home",
	    data: new FormData(this),
	  
	    contentType: false,       // The content type used when sending data to the server.
		cache: false,             // To unable request pages to be cached
		processData:false   
	    })
	    .done(function(data)
	    {
			 
			$("#response").html(data.msg);   
			if(data.next)
		    {
				window.location=data.next;
			} 
			if(data.list)
		    {
				$(".list").html(data.list);
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


     $("#review_form").submit(function(event){
 
    event.preventDefault();

    $("#review_response").html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
    cache: false,             // To unable request pages to be cached
    processData:false   
    })
    .done(function(data){
         //alert(data);
         
        if(data.next)
        {
            window.location=data.next;
        } 
        else
        {           
          $("#review_response").html(data.msg);
        }
        if(data.list)
        {
            $(".list").html(data.list);
        } 
         
       $('html, body').animate({scrollTop:0}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
        
            
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
	

	
	$("#BookingFrm").submit(function(event){
 
 	event.preventDefault();

    $("#response").html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
		 //alert(data);
		 
        if(data.next)
	    {
			window.location=data.next;
		} 
	    else
        {			
		  $("#response").html(data.msg);
	    }
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	     
       $('html, body').animate({scrollTop:0}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
    	
    	    
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });


    $("#NewsletterFrm").submit(function(event){
 
    event.preventDefault();

    $("#news_response").html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
    cache: false,             // To unable request pages to be cached
    processData:false   
    })
    .done(function(data){
         //alert(data);
         
        if(data.msg)            
        {
              $("#news_response").html(data.msg);
        }
        if(data.list)
        {
            $("#NewsletterFrm").html(data.list);
        } 
         
   
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
        
            
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });


  $("#ContactFrm").submit(function(event){
 
 	event.preventDefault();

    $("#contact_susmessage").html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
		 //alert(data);
		 
        if(data.next)
	    {
			window.location=data.next;
		} 
	    else
        {			
		  $("#contact_susmessage").html(data.msg);
	    }
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	     
       $('html, body').animate({scrollTop:0}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
    	
    	    
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });

    $("#form_reg").submit(function(event){
 
 	event.preventDefault();

    $("#response_register").html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
		 //alert(data);
		 
        if(data.next)
	    {
			window.location=data.next;
		} 
	    else
        {			
		  $("#response_register").html(data.msg);
	    }
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	     
       $('html, body').animate({scrollTop:0}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
    	
    	    
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    
    
    $("#login_form").submit(function(event){
 
 	event.preventDefault();

    $("#login_response").html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
		 //alert(data);
		 
        if(data.next)
	    {
			window.location=data.next;
		} 
	    else
        {			
		  $("#login_response").html(data.msg);
	    }
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	     
       $('html, body').animate({scrollTop:0}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {    
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
     
    $("#ForgotFrm").submit(function(event){
 
 	event.preventDefault();

    $("#forgot_response").html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: new FormData(this),
  
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
		 //alert(data);
		 
        if(data.next)
	    {
			window.location=data.next;
		} 
	    else
        {			
		  $("#forgot_response").html(data.msg);
	    }
	    if(data.list)
	    {
			$(".list").html(data.list);
		} 
	     
       $('html, body').animate({scrollTop:0}, 1000);
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
       
        // just in case posting your form failed
        alert(textStatus );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });

    
    $("#submitok").click(function(event){
 
 	event.preventDefault();
 	var formData = new FormData($('#formc')[0]);
    $('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
   
    type: "POST",
    dataType : "json",
    
    url: vpath+"ajax-home",
    data: formData,
  
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){
      
	    if(data.msg=="loginok")
		{
			window.location=vpath+'dashboard';
		}
		else
        {			
		  $("#response3").html(data.msg);
	    }
	    if(data.list)
	    {
			$(".list").html(data.list);
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
    
    $(".email_quote").click(function(event){
 
 		event.preventDefault();
	 	if($('#email').val()=="")
	 	{
	 		alert("Enter valid email id!");
	 	}	
	 	else
	 	{	
		 	var formData = new FormData($('#form')[0]);
		    $('#response2').html("<img src='"+vpath+"img/loaderIcon.gif'>");
		    $.ajax({
		   
		    type: "POST",
		    dataType : "json",
		    
		    url: vpath+"ajax-home",
		    data: formData,
		  
		    contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false   
		    })
		    .done(function(data){
		      
			    		
				$("#response2").html(data.msg2);
			     
			    if(data.next)
	            {
			      window.location=data.next;
		        }  
		       $('html, body').animate({scrollTop: 0}, 1000);
		  
		    })
		    .fail(function(jqXHR, textStatus, errorThrown) 
		    {
		    	
		    	    
		        // just in case posting your form failed
		        alert(textStatus );
		             
		    });
	 
	    }
	        
	 
	  });
		
 });
 




$(document).ready(function()
{
    $("#state").change(function()
    {   
        var id=$(this).val();
        //var dataString = 'parent_id='+ id;
        $("#city_id").find('option').remove();
         
        $.ajax
        ({
        type: "POST",
        url: vpath+"ajax-home",
        data: "state_id="+id+"&get_city_option=1"
    
        })
        .done(function(html)
        {
            $("#city_id").html(html);
        }) 
    });
    
    $("#city").change(function()
    {
         
       var id=$(this).val();
        //var dataString = 'parent_id='+ id;
        $("#city_id").find('option').remove();
         
        $.ajax
        ({
        type: "POST",
        url: vpath+"ajax-home",
        data: "city_id="+id+"&get_area_option=1"
    
        })
        .done(function(html)
        {
             
            $("#area_id").html(html);
        }) 
    });
    
    

});
 
function deleteImage(x)
{
 	
 	$('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: vpath+"ajax-home",
    data: "del_id="+x+"&delete_picture=1"
  
    })
    .done(function(data)
    {
    
		$("#response").html(data.msg);
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




 function getCallBack(x)
 {
 	document.forms['callbak_form'].product_id.value=x;
 	$('#createcallBackModal').modal('show'); 
 }
 
 $(document).ready(function()
{
	$("#callbak_form").submit(function(event)
	{
 
 	    event.preventDefault();
		 
		var formData = new FormData(this);
		$.ajax
		({
		type: "POST",
		dataType : "json",
		url: vpath+"ajax-home",
		data: formData
    
    	})
		.done(function(html)
		{
			$("#callbak_response").html(""); 
			$(".otp").html(html.list);
		}) 
	});
	
	

});

function getValue(a)
{
	if (isNaN(a)) 
	{
      return 0;
    }
    else
    {
    	 return a;
    }	
}




 