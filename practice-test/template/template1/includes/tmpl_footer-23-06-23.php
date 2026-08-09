<!-- Modal -->
<div class="modal fade" id="restrictModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Permision</h5>
        
      </div>
      <div class="modal-body">
        You don't have permisssion for this test!. <a href='<?=$configs['vpath']?>contact'>Contact Administrator</a>.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal-video" role="dialog">
    <div class="modal-dialog">
    <div class="modal-header">
        <button type="button" id="stop" class="close" data-dismiss="modal">&times;</button>    
        </div>
      <!-- Modal content-->
      <div class="modal-content">       
      </div>
    </div>
    </div>
<!--footer section-->
<div class="footer_wrapper">
      <div class="container">
            <div class="col-lg-12 col-sm-12">
			  <div class="ft-con">
              <div class="footer-menu">
                <ul>
                  <li><a href="<?=$configs['vpath']?>">Home</a></li>
                  <li><a href="<?=$configs['vpath']."info/about-us";?>">About Us</a></li>
                  <li><a href="<?=$configs['vpath']."contact";?>">Contact Us</a></li>
                  <li><a href="<?=$configs['vpath']."info/terms-of-use";?>">Terms of Use</a></li>
                  <li><a href="https://www.asvab-tutoring.com" target='_new' style='color:#fff'>www.asvab-tutoring.com</a></li>
                </ul>
              </div>
				<div class="social">
				  <ul>
					<li><a href="<?php echo $configs['facebook_url'];?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
					<li><a href="<?php echo $configs['twitter_url'];?>" target="_blank"><i class="fa fa-twitter" ></i></a></li>
					<li><a href="<?php echo $configs['googleplus_url'];?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
					<li><a href="<?php echo $configs['youtube_url'];?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
					</ul>
				</div>
				<div class="copyright"><p>&copy; <?php echo date("Y");?> <a href="http://www.asvab-tutoring.com" target='_new' style='color:#fff'>www.asvab-tutoring.com</a>. All rights reserved.</p></div>
              </div>
			</div>
            <div class="clearfix"></div>
        </div>
    </div>
    
<?
if($_SESSION['user_id'])
{?>
<div class="navbar">
  <a href="<?=$configs['vpath']?>report-error?cat_id=<?=$_GET['cat_id']."&lesson_id=" . $_GET['lesson_id'] ."&name=" . $_GET['name'];?>" target=_new>REPORT ERROR</a>
</div>
<?}?>


<script src="<?=$configs['template_path'];?>js/jquery-1.12.0.min.js"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?=$configs['template_path'];?>js/bootstrap.min.js"></script>
<!-- jQuery -->
<!--<script src="js/jquery-2.1.4.min.js"></script>-->
<!-- Bootstrap Core JavaScript -->
<!--<script src="js/bootstrap.min.js"></script>-->
<!-- SmartMenus jQuery plugin -->
  <script type="text/javascript" src="<?=$configs['template_path'];?>js/jquery_smartmenus.js"></script>
  <!-- SmartMenus jQuery Bootstrap Addon -->
  <script type="text/javascript" src="<?=$configs['template_path'];?>js/jquery_smartmenus_bootstrap.js"></script>
<!--portfolio-->
<script src="<?=$configs['template_path'];?>js/jquery.mixitup.min.js"></script>
<script src="js/main.js"></script> 
<!--auto slider-->
<script type="text/javascript" src="<?=$configs['template_path'];?>js/jquery.flexisel.js"></script>
<script src="<?=$configs['template_path'];?>js/custom.js"></script>
<script src="<?=$configs['template_path'];?>js/skdslider.js"></script>
<script src="<?=$configs['template_path'];?>js/bootstrap-datetimepicker.min.js"></script>
<script src="<?=$configs['template_path'];?>js/owl.carousel.min.js"></script>
<!--<script src="<?=$configs['template_path'];?>js/ajaxtabs.js"></script>-->
<script src="<?=$configs['template_path'];?>js/slick.js"></script>
<script src="<?=$configs['template_path'];?>js/script.js"></script>
<script src="<?=$configs['template_path'];?>js/jquery.nicescroll.min.js"></script>
<script src="<?=$configs['template_path'];?>js/swiper.min.js"></script>
<script src="<?=$configs['template_path'];?>js/popModal.js"></script>
<script src="<?=$configs['template_path'];?>js/js.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script   type="text/javascript"   src="//code.jquery.com/jquery-1.6.4.js"></script> -->


<script>
	
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
</script> 
 
<script type="text/javascript">
  $("#prediction-click").click(function(){
    $(".prediction-overly").show()
});
</script>
<!--<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>-->


<script>
  $(document).ready(function() {
    //$(".div-scroll").niceScroll({cursorborder:"",cursorcolor:"rgb(252, 184, 21)",boxzoom:true});
  });
</script>

<script type="text/javascript">
 !function ($) {
    
    // Le left-menu sign
    /* for older jquery version
    $('#left ul.nav li.parent > a > span.sign').click(function () {
        $(this).find('i:first').toggleClass("icon-minus");
    }); */
    
    $(document).on("click","#left ul.nav li.parent > a > span.sign", function(){          
        $(this).find('i:first').toggleClass("icon-minus");      
    }); 
    
    // Open Le current menu
    $("#left ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus");
    $("#left ul.nav li.current").parents('ul.children').addClass("in");

}(window.jQuery);
</script>
<script type="text/javascript">
$('.recent-updates-slider').slick({
  dots: false,
  slidesToShow: 4,
  slidesToScroll: 1,
  touchMove: false,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});
  
</script>
<script type="text/javascript">
$('.new-qs-slider').slick({
dots: false,
infinite: false,
speed: 300,
slidesToShow: 1,
 navigation:true,
  prevArrow: $('.slick-prev'),
   nextArrow: $('.slick-next'),
adaptiveHeight: true,
slidesToScroll: 1
});
</script>
<script>
$('.view-ans').click(function(){
$('.slick-list').css("height","auto");
});
</script>
<script type="text/javascript">
  $(".slick-arrow").click(function(){
  $(".mtq_explanation").hide('slow');
});
</script>

<script type="text/javascript">
  $(".slick-arrow").click(function(){
  $(".mtq_explanation").css("display","none");
});
</script>
<script type="text/javascript">
  $(".close-btn").click(function(){
  $(".mtq_explanation").css("display","none");
});
</script>

<script>
  $(document).ready(function(){
        $('#demo1').skdslider({
          slideSelector: '.slide',
          delay:5000,
          animationSpeed:2000,
          showNextPrev:true,
          showPlayButton:true,
          autoSlide:true,
          animationType:'fading'
        });
    });
</script>
<script>
 $(document).ready(function() {
    $("#news-slider").owlCarousel({
        items : 4,
        itemsDesktop:[1199,3],
        itemsDesktopSmall:[980,2],
        itemsTablet:[650,1],
        pagination:false,
        navigation:true,
        navigationText:["",""]
    });
});
</script>
<script>
 $(document).ready(function() {
    $("#news-slider1").owlCarousel({
        items : 3,
        itemsDesktop:[1199,3],
        itemsDesktopSmall:[980,2],
        itemsTablet:[650,1],
        pagination:false,
        navigation:true,
        navigationText:["",""]
    });
});
</script>


 <script type="text/javascript">
function addNow() {
  nowDate = moment().tz("Europe/London").format('YYYY-MM-DD');
  nowTime = moment().tz("Europe/London").format('HH:mm:ss');
  document.getElementById('registration-date').value = nowDate;
  document.getElementById('registration-time').value = nowTime;
  set = setTimeout(function () { addNow(); }, 1000);
}

</script>
<script>
 $(document).ready(function () {
    $("#forgot").click(function () {
        $("#forgot").fadeOut(function () {
            $("#log").text(($("#log").text() == 'sign in') ? 'forgot your password?' : 'sign in').fadeIn(100);
        })
    })
});

 
</script>



<!-- retype password-->
<script>
    var allowsubmit = false;
    $(function(){
      //on keypress 
      $('#confpass').keyup(function(e){
        //get values 
        var pass = $('#pass').val();
        var confpass = $(this).val();
        
        //check the strings
        if(pass == confpass){
          //if both are same remove the error and allow to submit
          $('.error').text('Matching');
          allowsubmit = true;
        }else{
          //if not matching show error and not allow to submit
          $('.error').text('Password not matching');
          allowsubmit = false;
        }
      });
      
      //jquery form submit
      $('#form').submit(function(){
      
        var pass = $('#pass').val();
        var confpass = $('#confpass').val();

        //just to make sure once again during submit
        //if both are true then only allow submit
        if(pass == confpass){
          allowsubmit = true;
        }
        if(allowsubmit){
          return true;
        }else{
          return false;
        }
      });



    });
  </script>
 <script>
  jQuery(function($) {
  
  $('#bookmark-this').click(function(e) {
    var bookmarkURL = window.location.href;
    var bookmarkTitle = document.title;

    if ('addToHomescreen' in window && addToHomescreen.isCompatible) {
      // Mobile browsers
      addToHomescreen({ autostart: false, startDelay: 0 }).show(true);
    } else if (window.sidebar && window.sidebar.addPanel) {
      // Firefox <=22
      window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
    } else if ((window.sidebar && /Firefox/i.test(navigator.userAgent)) || (window.opera && window.print)) {
      // Firefox 23+ and Opera <=14
      $(this).attr({
        href: bookmarkURL,
        title: bookmarkTitle,
        rel: 'sidebar'
      }).off(e);
      return true;
    } else if (window.external && ('AddFavorite' in window.external)) {
      // IE Favorites
      window.external.AddFavorite(bookmarkURL, bookmarkTitle);
    } else {
      // Other browsers (mainly WebKit & Blink - Safari, Chrome, Opera 15+)
      alert('Press ' + (/Mac/i.test(navigator.platform) ? 'Cmd' : 'Ctrl') + '+D to bookmark this page.');
    }

    return false;
  });
  
});

$('#stop').on('click', function() {
    //$('#popup-youtube-player').stopVideo();
$('#popup-youtube-player')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');    
});
	</script>
  <script>
   $(function() {
  $('#heading0 a').addClass('collapsed');
  $('#collapse0').addClass('in');
});
  </script>
  <script>
    $(document).ready(function () {
$('.dropdown').hover(function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
    }, function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
    });
});
</script>
 
<script>
  $(".pop").popover({ trigger: "manual" , html: true, animation:false})
    .on("mouseenter", function () {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 300);
});

function getAnswer(x,y)
{
	
	$("#response_"+x).html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
	var time=$("#time").val();
    $.ajax({
    type: "POST",
    dataType : "json",
    url: vpath+"ajax-home",
    data: "action=test&id=<?=$_GET['id'];?>&total=<?=(int)$total;?>&term=<?=(int)$term;?>&cat_id=<?=(int)$_GET['cat_id'];?>&lesson_id=<?=(int)$_GET['lesson_id'];?>&question_id="+x+"&answer_no="+y+"&time="+time
   
    })
    .done(function(data)
    {
        //alert(data);
        $("#response_"+x).html(data.msg);
	    $("#total").html(data.total);
		 
	    
    })
    .fail(function() 
    {
    	 
        // just in case posting your form failed
        alert("Posting failed.");
    });
 
}


function getFinalAnswer(x,y,z)
{
	var time=$("#time").val();
	$("#response_"+x).html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath']?>ajax-home",
    data: "action=prediction-test&total=<?=(int)$total;?>&term=<?=(int)$term;?>&cat_id=<?=$_GET['cat_id']?>&lesson_id="+y+"&question_id="+x+"&answer_no="+z+"&time="+time
   
    })
    .done(function(data)
    {
        //alert(data);
        $("#response_"+x).html(data.msg);
	    $("#total").html(data.total);
		  $("#"+y+"total").html(data.ctotal);		 
	    
    })
    .fail(function() 
    {
    	 
        // just in case posting your form failed
        alert("Posting failed.");
    });
 
}
 
  
function finish()
{
 	 
 	$('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath']?>ajax_home.php",
    data: "set_no=<?=$_GET['set_no']?>&take_exam=1&finish=1"
   
    })
    .done(function(data)
    {
        //alert(data);
        $("#response").html("");
	    $("#list").html(data.list);
		 
	    
    })
    .fail(function() 
    {
    	 
        // just in case posting your form failed
        alert("Posting failed.");
    });
 
 }
 


</script>
 
 
<script type="text/javascript">
/*   $('.flip-slider').slick({
  dots: false,
  infinite: false,
  speed: 300,
  swipeToSlide: true,
  slidesToShow: 1,
  slidesToScroll: 1

}); */
/* $('.flip-slider').owlCarousel({
    loop:true,
    margin:10,
    navigation:true,
	center: true,
	touchDrag: true,
	 items:1
 
}) */
</script>
<script>
    var swiper = new Swiper('.swiper-container', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  </script>
<script>  
  $(window).load(function(){    
   $(function () {
    $("#big-image item:eq(0)").nextAll().hide();
    $(".small-images td").click(function (e) {
        var $this = $(this),
            index = $this.index();
        $(".small-images item").removeClass('selected');
        $this.addClass('selected');
        $("#big-image item").eq(index).show().siblings().hide();
    });
    $('#next').click(function(){ 
        var $selected = $('.selected');
        
        var index = $selected.next('item').index();
        if(index == -1){
            index = 0;
        }
         
        var $curr = $(".small-images item").eq(index);
        $(".small-images item").removeClass('selected');
        $curr.addClass('selected');
        $("#big-image item").eq(index).show().siblings().hide();
    });
    
   
    
    $('#prev').click(function(){ 
        var $selected = $('.selected');
        var index = $selected.prev('item').index();
        var $curr = $(".small-images item").eq(index);
        $(".small-images item").removeClass('selected');
        $curr.addClass('selected');
        $("#big-image item").eq(index).show().siblings().hide();
    });
});
});
function selectIt(x)
{
    
    var index = x-1;
    if(index == -1){
            index = 0;
        } 
    $("#big-image item").eq(index).show().siblings().hide();
   
}

function getReport(x)
{
    
   $('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath']?>ajax-home",
    data: "set_no=<?=$_GET['set_no']?>&lesson_id=<?=$_GET['lesson_id']?>&cat_id=<?=$_GET['cat_id']?>&action=report&total="+x
   
    })
    .done(function(data)
    {
        //alert(data);
        $("#response").html("");
	    $("#form").html(data.list);
		 
	    
    })
    .fail(function() 
    {
    	 
        // just in case posting your form failed
        alert("Posting failed.");
    });
 
} 
function getExam()
{
    
   $('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
    type: "POST",
    dataType : "json",
    url: "<?=$configs['vpath']?>ajax-home",
    data: "set_no=<?=$_GET['set_no']?>&cat_id=<?=$_GET['cat_id']?>&action=get-exam"
   
    })
    .done(function(data)
    {
        //alert(data);
        $("#response").html("");
	    $("#form").html(data.list);
		 
	    
    })
    .fail(function() 
    {
    	 
        // just in case posting your form failed
        alert("Posting failed.");
    });
 
}    
</script>  
 <script>
$(document).ready(function(){
  $(".responsive-menu-click").click(function(){
    $(".course-side-menu").slideToggle();
  });
});
</script>
<script type="text/javascript">
 $(window).load(function() {
  $(".pageloader").fadeOut("slow");
});
</script>
<script type="text/javascript">
function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var footer_top = $("#sclfooter").offset().top;
    var div_top = $('#sticky-anchor').offset().top;
    var div_height = $("#left-mob-menu").height();
    
    var padding = 20;  // tweak here or get from margins etc
    
    if (window_top + div_height > footer_top - padding)
        $('#left-mob-menu').css({top: (window_top + div_height - footer_top + padding) * -1})
    else if (window_top > div_top) {
        $('#left-mob-menu').addClass('stick');
        $('#left-mob-menu').css({top: 100})
    } else {
        $('#left-mob-menu').removeClass('stick');
    }
}

$(function () {
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});
</script>
 <script type="text/javascript">
function checkOffset() {
    if($('#social-float').offset().top + $('#social-float').height() 
                                           >= $('#sclfooter').offset().top - 10)
        $('#social-float').css({"position": "absolute", "bottom": "0", "left": "0"});
    if($(document).scrollTop() + window.innerHeight < $('#sclfooter').offset().top)
        $('#social-float').css({"position": "fixed", "bottom": "initial", "left": "260px"}); // restore when you scroll up

    //$('#social-float').text($(document).scrollTop() + window.innerHeight);
}
$(document).scroll(function() {
    checkOffset();
});
 </script>
 <script type="text/javascript">
 $(".dash-mob-menu").click(function(){
  $("#left-mob-menu").toggle();
});
 </script>
 <script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>
<script>
  $(document).ready(function () {
    $(".flip-box").hover(
      function () {
        $(this).addClass("box-hover");
      },
      function () {
        $(this).removeClass("box-hover");
      }
    );
  });
</script>

<!--<script>

var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {
  dropdownContent.style.display = "none";
  } else {
  dropdownContent.style.display = "block";
  }
  });
}
</script>-->

<script>
  $.sidebarMenu = function(menu) {
  var animationSpeed = 300;
  
  $(menu).on('click', 'li a', function(e) {
    var $this = $(this);
    var checkElement = $this.next();

    if (checkElement.is('.treeview-menu') && checkElement.is(':visible')) {
      checkElement.slideUp(animationSpeed, function() {
        checkElement.removeClass('menu-open');
      });
      checkElement.parent("li").removeClass("active");
    }

    //If the menu is not visible
    else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
      //Get the parent menu
      var parent = $this.parents('ul').first();
      //Close all open menus within the parent
      var ul = parent.find('ul:visible').slideUp(animationSpeed);
      //Remove the menu-open class from the parent
      ul.removeClass('menu-open');
      //Get the parent li
      var parent_li = $this.parent("li");

      //Open the target menu and add the menu-open class
      checkElement.slideDown(animationSpeed, function() {
        //Add the class active to the parent li
        checkElement.addClass('menu-open');
        parent.find('li.active').removeClass('active');
        parent_li.addClass('active');
      });
    }
    //if this isn't a link, prevent the page from being redirected
    if (checkElement.is('.treeview-menu')) {
      e.preventDefault();
    }
  });
}

$.sidebarMenu($('.sidebar-menu'));

$(document).ready(function(){ 
    $(window).scroll(function(){ 
        if ($(this).scrollTop() > 100) { 
            $('#scroll').fadeIn(); 
        } else { 
            $('#scroll').fadeOut(); 
        } 
    }); 
    $('#scroll').click(function(){ 
        $("html, body").animate({ scrollTop: 0 }, 600); 
        return false; 
    }); 
});

</script>
  </body>


</html>