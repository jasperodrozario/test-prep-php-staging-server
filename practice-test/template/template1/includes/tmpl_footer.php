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
      <div>
        <div class="ft-con">
          <div class="footer-menu">
            <ul>
              <li><a href="<?=$configs['vpath']?>">Home</a></li>
              <li><a href="<?=str_replace("/practice-test","",$configs['vpath'])."about";?>">About Us</a></li>
              <li><a href="<?=str_replace("/practice-test","",$configs['vpath'])."contact-us";?>">Contact Us</a></li>
              <li><a href="<?=$configs['vpath']."info/terms-of-use";?>">Terms of Use</a></li>            
            </ul>
             <div class="copyright"><p>&copy; <?php echo date("Y");?> <a href="http://www.asvab-tutoring.com" target='_new' style='color:#fff'>www.asvab-tutoring.com</a> All rights reserved.</p></div> 
          </div>
          <div class="social">
            <ul>
              <li><a href="<?php echo $configs['facebook'];?>" target="_blank"><i class="fa fa-facebook fa-lg"></i></a></li>
              <li><a href="<?php echo $configs['x_url'];?>" target="_blank"><i class="fa fa-twitter fa-lg"></i></a></li>
              <li><a href="<?php echo $configs['youtube'];?>" target="_blank"><i class="fa fa-youtube fa-lg"></i></a></li>
              <li><a href="<?php echo $configs['instagram'];?>" target="_blank"><i class="fa fa-instagram fa-lg"></i></a></li>
            </ul>
          </div>
        </div>
				
      </div>
    </div>

  </div>
        
</div>
    
<?
if($_SESSION['user_id'])
{
    if($_GET['result']=="prediction-test"){
        $_GET['in']="prediction-test";
        $_GET['lesson_id'] = $_GET['child_cat_id'];
    }
?>
    <div class="navbar">
      <a href="<?=$configs['vpath']?>report-error?cat_id=<?=$_GET['cat_id']."&lesson_id=" . $_GET['lesson_id'] ."&name=" . $_GET['name']."&in=".$_GET['in']."&hw_ord=".$_GET['home_limit']."&url=".$configs['vpath']."".str_replace("/practice-test/","",$_SERVER['REQUEST_URI']);?>" target=_new>REPORT ERROR</a>
    </div>
<?
}
?>
<style>

.footer_wrapper {
    width: 100%;
    padding: 0px 0px;
    margin: 0px;
    float: left;
    background: #7a7773;
    position: relative;
}

.copyright p {
    color: #fff4e6;
    margin-top: 5px;
    margin-bottom: 10px;
    margin-left: 0px;
    text-align: left;
    font-size: 12px;
    
}

.copyright {

    text-align: left;
}

.ft-con {
  display: flex;
  justify-content: space-between; /* Place items at left and right */
  align-items: center; /* Vertically center the items */
  width: 100%;
}

.footer-menu {
  /* Aligns the menu to the left */
  display: flex;
  
  flex-direction: column;
}

.social {
  /* Aligns social icons to the right */
  display: flex;
  align-items: center; /* Vertically center the social icons */
}

.social ul {
  display: flex;
  padding-top: 15px;
  padding-bottom: 15px;
  margin: 0;
}

.social li {
  margin-left: 10px; /* Space between social icons */
}

.footer-menu ul {
    text-align: left;
    padding: 5px 0 0px;
}

.footer-menu ul li a {
    padding: 5px 10px 0 0;
    margin: 0 0 0 0;
    margin-left: 0px;
    font-family: 'Roboto', sans-serif;
    color: #fff;
    font-weight: 500;
    letter-spacing: 0.8px;
    font-size: 14px;
}
</style>


<script src="<?=$configs['template_path'];?>js/jquery-1.12.0.min.js"></script>
<script src="<?=$configs['template_path'];?>js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?=$configs['template_path'];?>js/jquery_smartmenus.js"></script>
  <script type="text/javascript" src="<?=$configs['template_path'];?>js/jquery_smartmenus_bootstrap.js"></script>
<!--portfolio-->
<script src="<?=$configs['template_path'];?>js/jquery.mixitup.min.js"></script>
<script src="<?=$configs['template_path'];?>js/main.js"></script> 
<!--auto slider-->
<script type="text/javascript" src="<?=$configs['template_path'];?>js/jquery.flexisel.js"></script>
<script src="<?=$configs['template_path'];?>js/custom.js"></script>
<script src="<?=$configs['template_path'];?>js/skdslider.js"></script>
<script src="<?=$configs['template_path'];?>js/bootstrap-datetimepicker.min.js"></script>
<script src="<?=$configs['template_path'];?>js/owl.carousel.min.js"></script>
<script src="<?=$configs['template_path'];?>js/slick.js"></script>
<script src="<?=$configs['template_path'];?>js/script.js"></script>
<script src="<?=$configs['template_path'];?>js/jquery.nicescroll.min.js"></script>
<script src="<?=$configs['template_path'];?>js/swiper.min.js"></script>
<script src="<?=$configs['template_path'];?>js/popModal.js"></script>
<script src="<?=$configs['template_path'];?>js/js.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

 

 
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

 !function ($) {
    $(document).on("click","#left ul.nav li.parent > a > span.sign", function(){          
        $(this).find('i:first').toggleClass("icon-minus");      
    }); 
    $("#left ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus");
    $("#left ul.nav li.current").parents('ul.children').addClass("in");

}(window.jQuery);
//=====================================================
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
//=====================================================
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
//=====================================================
$('.view-ans').click(function(){
$('.slick-list').css("height","auto");
});
//=====================================================
  $(".slick-arrow").click(function(){
  $(".mtq_explanation").hide('slow');
});
//=====================================================
  $(".slick-arrow").click(function(){
  $(".mtq_explanation").css("display","none");
});
//=====================================================
  $(".close-btn").click(function(){
  $(".mtq_explanation").css("display","none");
});
//=====================================================
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
//=====================================================
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
//=====================================================
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
//=====================================================
function addNow() {
  nowDate = moment().tz("Europe/London").format('YYYY-MM-DD');
  nowTime = moment().tz("Europe/London").format('HH:mm:ss');
  document.getElementById('registration-date').value = nowDate;
  document.getElementById('registration-time').value = nowTime;
  set = setTimeout(function () { addNow(); }, 1000);
}

//=====================================================
 $(document).ready(function () {
    $("#forgot").click(function () {
        $("#forgot").fadeOut(function () {
            $("#log").text(($("#log").text() == 'sign in') ? 'forgot your password?' : 'sign in').fadeIn(100);
        })
    })
});

 
//=====================================================
/* retype password */

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
//=====================================================
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

//=====================================================
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

//=====================================================
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
	    //$("#total").html(data.total);
		  $("#"+y+"total").html(data.ctotal);
      $("#total-answered").html(data.total_answer);
      $("#pred-total").html(data.total);


	    
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
 


    var swiper = new Swiper('.swiper-container', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
//=====================================================
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
    if(index == -1)
    {
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
    data: "set_no=<?=(int)$_GET['set_no']?>&lesson_id=<?=(int)$_GET['lesson_id']?>&cat_id=(int)<?=$_GET['cat_id']?>&action=report&total="+x
   
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


//=====================================================
$(document).ready(function(){
  $(".responsive-menu-click").click(function(){
    $(".course-side-menu").slideToggle();
  });
});
//=====================================================
 $(window).load(function() {
  $(".pageloader").fadeOut("slow");
});
//=====================================================
/*
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

//=====================================================
function checkOffset() {
    if($('#social-float').offset().top + $('#social-float').height() 
                                           >= $('#sclfooter').offset().top - 10)
        $('#social-float').css({"position": "absolute", "bottom": "0", "left": "0"});
    if($(document).scrollTop() + window.innerHeight < $('#sclfooter').offset().top)
        $('#social-float').css({"position": "fixed", "bottom": "initial", "left": "260px"}); // restore when you scroll up

}
$(document).scroll(function() {
    checkOffset();
});
*/
//=====================================================
 $(".dash-mob-menu").click(function(){
  $("#left-mob-menu").toggle();
});
//=====================================================
/*
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
  */

//=====================================================
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

//=====================================================
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

$.sidebarMenu($('.sidebar-menu'))

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

$(document).ready(function() {
    $(".lesson-controls").each(function() {
        if ($.trim($(this).html()) === "") {
            $(this).css("margin-top", "0px");
        }
    });
});
// Optional: close tooltip if clicking outside
document.addEventListener('click', function (e) {
    if (!e.target.closest('.afoqt-subscore th[data-tooltip]')) {
        document.querySelectorAll('.afoqt-subscore th.show-tooltip')
            .forEach(el => el.classList.remove('show-tooltip'));
    }
});

// Close the mobile/tablet nav when tapping outside of it.
$(document).on('click touchstart', function (e) {
    var $nav = $('#navbarSupportedContent');
    if (!$nav.hasClass('in')) {
        return;
    }
    if ($(e.target).closest('#navbarSupportedContent, .navbar-toggler').length === 0) {
        $nav.collapse('hide');
    }
});

</script>


<script>

/*
// Images will fit if the image size exceeds the container size.
$(document).ready(function() {
  // Wrap images if not already inside img-que-container
  $(".div-scroll img").each(function() {
    if (!$(this).parent().is("div.img-que-container")) {
      $(this).wrap('<div class="img-que-container"></div>');
    }
  });
});

  $(document).ready(function() {
  // Check for overflow after page fully loaded
  window.addEventListener('load', function() {
    document.querySelectorAll('.img-que-container').forEach(container => {
      const img = container.querySelector('img');

  function adjustImage() {
    const containerWidth = container.clientWidth;
    const naturalWidth = img.naturalWidth;

    if (containerWidth < naturalWidth) {
      // Container is smaller than the image’s natural size → shrink to fit
      container.classList.add('force-fit');
    } else {
      // Container is bigger or equal → remove shrink rule and let natural size apply
      container.classList.remove('force-fit');
    }
  }

      adjustImage(); // run once
      window.addEventListener('resize', adjustImage);
    });
  });
});
*/
$(document).ready(function () {
  function wrapAndAdjustImages() {
    $(".div-scroll img").each(function () {
      const $img = $(this);
      const $container = $img.parent(".img-que-container");

      // Wrap if not already wrapped
      if ($container.length === 0) {
        $img.wrap('<div class="img-que-container"></div>');
      }
    });

    // Adjust all wrapped images
    $(".img-que-container").each(function () {
      const container = this;
      const img = container.querySelector("img");

      if (!img.complete) {
        img.onload = () => adjustImage(container, img);
      } else {
        adjustImage(container, img);
      }
    });
  }

  function adjustImage(container, img) {
    const containerWidth = container.clientWidth;
    const naturalWidth = img.naturalWidth;

    if (containerWidth < naturalWidth) {
      container.classList.add("force-fit");
    } else {
      container.classList.remove("force-fit");
    }
  }

  // Debounce utility
  function debounce(fn, delay) {
    let timer;
    return function () {
      clearTimeout(timer);
      timer = setTimeout(fn, delay);
    };
  }

  // Initial run
  wrapAndAdjustImages();

  // Resize with debounce
  window.addEventListener("resize", debounce(wrapAndAdjustImages, 150));

  // Optional: re-run if new images are added dynamically
  const observer = new MutationObserver(() => wrapAndAdjustImages());
  observer.observe(document.querySelector(".div-scroll"), {
    childList: true,
    subtree: true,
  });
});

</script>
<style>
    @media screen and (min-width: 320px) and (max-width: 500px) {
  
    
    .div-scroll p {
        font-size: 16px !important;
        font-weight: 500 !important
    }
  }
  @media screen and (max-width: 768px) {
  
    
    .div-scroll p {
        font-size: 16px !important;
        font-weight: 500 !important
    }
  }
</style>

  </body>

</html>
