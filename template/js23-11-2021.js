if(location.hostname=="localhost" || location.hostname=="192.168.1.14")
{
    var vpath = "http://"+location.hostname+"/papiadams/";	
}
else
{
	var vpath = "https://"+location.hostname+"/";	
}

function add2cart(x)
{
 
	
	$('#btn_'+x).html("<img src='"+vpath+"img/loaderIcon.gif'>");
	var qty=$("#qty_"+x).val();
    $.ajax({
    type: "POST",
 	dataType : "json",    
    url: vpath+"ajax-home",
    data: "action=addtocart&menu_id="+x+"&qty="+qty
  
    })
    .done(function(data)
    {
		//alert(data);
		$('#btn_'+x).html('<i class="fa fa-shopping-bag" aria-hidden="true"></i>'); 
		$('#cart-list').html(data.list);  
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
    	        // just in case posting your form failed
        alert(textStatus );
             
    });

}

function updatecart(x,y)
{

	$('#cart-list').html("<img src='"+vpath+"img/loaderIcon.gif'>");
	var qty=y;
    $.ajax({
    type: "POST",
 	dataType : "json",    
    url: vpath+"ajax-home",
    data: "action=updatecart&menu_id="+x+"&qty="+qty
  
    })
    .done(function(data)
    {
		//alert(data);
		$('#cart-list').html(data.list);  
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
    	        // just in case posting your form failed
        alert(textStatus );
             
    });

}

$(document).ready(function() {
	
    $("#apply").click(function(){
    	$('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
        if($('#couponcode').val()=="")
        {
        	$("#response").html('<font color=red>Enter coupon code!');
        }
        //alert("action=check-coupon&coupon_code="+$('#couponcode').val()+"&amount="+$('#amount').val()+"&shipping_charge="+$('#shipping_charge').val()+"&total_amount="+$('#total_amount').val());
        if($('#couponcode').val())	
        {
        	$.ajax({
		    type: "POST",
		 	dataType : "json",    
		    url: vpath+"ajax-home",
		    data: "action=check-coupon&coupon_code="+$('#couponcode').val()+"&amount="+$('#amount').val()+"&total_amount="+$('#total_amount').val(),	  
		    })
		    .done(function(data)
		    {
				$("#response").html(data.msg);
				
				if(data.list)
				{
					$("#cart-total").html(data.list); 
				} 
		    })
		    .fail(function(jqXHR, textStatus, errorThrown) 
		    {
		    	// just in case posting your form failed
		        alert(textStatus);
		             
		    });
        }	
        
    }); 
});

function Shipping(x)
{
	if(x=="shipping")
	{
		 
		$("#postcode").css('visibility', 'visible');
		$("#post_code").attr('required', 'required');
	 
	}
	else
	{
		$("#postcode").css('visibility', 'hidden');
		$("#post_code").removeAttr('required');
	 
	}	
	 
}

// AJAX call for autocomplete 
jQuery(document).ready(function()
{ 
  $("#product_name").keyup(function(){
    $.ajax({
    type: "POST",
    dataType : "json",
    url: vpath+'ajax-home',    
    data:'action=get_name&product_name='+$(this).val(),    
    beforeSend: function(){
      $("#product_name").css("background","#FFF url("+vpath+"img/loaderIcon.gif) no-repeat 165px");
    },
    success: function(data)
    {
      //alert(data);
      $("#suggesstion-box").show();
      $("#suggesstion-box").html(data.list);
      $("#product_name").css("background","#FFF");
    }
    });
  });
 
}); 

function setName(id,value,url) {
	
	$("#product_id").val(id);
	$("#product_name").val(value);
	$("#suggesstion-box").hide();
	window.location=url;
	 
}


function paymentMode(x)
{
	if(x=="card")
	{
		 
		 
		$("#name").attr('required', 'required');
		$("#card_type").attr('required', 'required');
	    $("#cardno").attr('required', 'required');
	    $("#month").attr('required', 'required');
	    $("#year").attr('required', 'required');
	    $("#cvc").attr('required', 'required');
	    
	}
	else
	{
		$("#name").removeAttr('required');
		$("#card_type").removeAttr('required');
	    $("#cardno").removeAttr('required');
	    $("#month").removeAttr('required');
	    $("#year").removeAttr('required');
	    $("#cvc").removeAttr('required');
	 
	}	
	 
}
 

 
  
function getDeleteCart(x)
{
	$('#response').html("<img src='"+vpath+"img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
 	dataType : "json",    
    url: vpath+"ajax-home",
    data: "action=get-delete_cart&id="+x
  
    })
    .done(function(data)
    {
		//alert(data);
		$("#response").html(data.msg); 
		window.location.reload();
  
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
    	        // just in case posting your form failed
        alert(textStatus );
             
    });
}

 

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




 