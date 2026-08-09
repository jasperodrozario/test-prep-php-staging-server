<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta content="<?=$configs['dotcom']?>" name="description"/>
	<meta content="MMe" name="author"/>
    
<!--    <link rel="shortcut icon" type="image/png" href="<?="https:staging-test-prep.asvab-tutoring.com/".$configs['favicon']?>">-->
	
	<title><?php echo ucwords($configs['dotcom']);?> - Dashboard</title>
	
		<!-- BEGIN CORE FRAMEWORK -->
	<link href="<?=$configs['tpath'];?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<!-- END CORE FRAMEWORK -->
	
	<!-- BEGIN PLUGIN STYLES -->
	<link href="<?=$configs['tpath'];?>assets/plugins/animate/animate.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/bootstrap-slider/css/slider.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/rickshaw/rickshaw.min.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
	<!-- END PLUGIN STYLES -->
	
	<!-- BEGIN THEME STYLES -->
	<link href="<?=$configs['tpath'];?>assets/css/material.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/style.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/plugins.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/helpers.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/responsive.css" rel="stylesheet" />
    <link href="<?=$configs['tpath'];?>assets/css/imgareaselect-default.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?=$configs['tpath'];?>assets/css/jquery.awesome-cropper.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	 <!-- END THEME STYLES -->
    
    <!-- Bootstrap from your setup -->
    <script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Additional Plugins -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    
 	<script>function popup(pageURL, title, w, h) {
            var left = (screen.width - w) / 2;
            var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
            var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        } 
    </script>


    <style>
        /* Sidebar toggle button styling */
        .navbar-toggle-sidebar {
            display: none;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 24px;
            padding: 13px 15px;
            float: left;
            outline: none;
            cursor: pointer;
            position: relative;
            z-index: 10005;
        }

        /* Mobile-specific menu styles */
        @media screen and (max-width: 992px) {
            .navbar-toggle-sidebar {
                display: block;
            }
            
            header .logo {
                padding-left: 10px !important;
                width: auto !important;
                white-space: nowrap !important;
                display: inline-block !important;
                float: left !important;
            }

            header {
                z-index: 10002 !important;
                position: relative;
            }

            /* Prevent navlinks from wrapping on mobile to keep header height consistent */
            .admin-rightmenu h5 a {
                font-size: 11px !important;
                margin: 0 2px !important;
            }

            /* Sidebar becomes scrollable */
            .leftside {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 280px !important;
                height: 100% !important;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 10001;
                overflow-y: auto !important;  /* SCROLLING WORKS!!!!! */
                -webkit-overflow-scrolling: touch;
                padding-top: 64px !important; /* Push menu below header */
                padding-bottom: 50px !important; /* Extra bottom scroll space */
            }
            
            body.show-sidebar .leftside {
                transform: translateX(0);
            }
            
            /* Override dropdown positioning - MAKE THEM STATIC */
            .leftside .nav-sidebar .nav-dropdown ul {
                position: static !important;  /* KILL ABSOLUTE POSITIONING */
                width: 100% !important;
                margin: 0 !important;
            }
            
            /* Submenu links indented */
            .leftside .nav-sidebar .nav-dropdown ul li a {
                padding-left: 45px !important;
                color: #ffffff !important;
                opacity: 0.9 !important;
            }
            
            /* Overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 10000;
            }
            body.show-sidebar .sidebar-overlay {
                display: block;
            }
            body.show-sidebar {
                overflow: hidden;
            }
        }
        @media screen and (max-width: 643px) {
            .leftside {

                top: 48px !important; /* Start below header (60px height + 4px border) */

            }
        }
        @media screen and (max-width: 343px) {
            .leftside {

                top: 70px !important; /* Start below header (60px height + 4px border) */

            }
        }
    </style>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->

<body class="fixed-leftside fixed-header">
	<!-- BEGIN HEADER -->
	<header style="background:#8f1d2e;border-bottom:solid 4px #a5a4a0;">
		<button type="button" class="navbar-toggle-sidebar" id="sidebarToggle">
			<i class="fa fa-bars"></i>
		</button>
		<a href="<?=$configs['admin_path'];?>" class="logo">TEST PREP ADMIN</a>
	
		<nav class="navbar navbar-static-top">

            <div class="navbar-right admin-rightmenu" style='padding-top:8px'>
				
				<h5><a class='color-grey'><i class="fa fa-user"></i> Welcome <?php echo Ucwords($_SESSION['username']);?></a>
                 <a href='<?php echo str_replace("practice-test/","",$configs['vpath'])?>admincp' class='color-grey'><i class="fa fa-user"></i> Blog Admin</a>
				 <a href='<?php echo $configs['vpath']?>'  class='color-grey'><i class="fa fa-desktop"></i> Website </a>  
				 <a href='logout' class='color-grey'><i class="fa fa-sign-out"></i> Logout</a> 
                </h5>
                 
			</div>
        </nav>
    </header>

	<!-- END HEADER -->
	<div class="wrapper">
		<div class="leftside">
			<div class="sidebar">				
				<ul class="nav-sidebar">
				<?php
				$i=0;
				if(!$menu_open_id)
                {
					$menu_open_id=165;
				}
				foreach($menus as $rw ) 
				{
					 
					$main_name = stripslashes($rw['name'] );
					
					$class="";
					if($rw['id']==$menu_open_id)
					{
						$class="open"; 
					}
					
					if(!empty($rw['url'])):
						$admin_url = $rw['url'];
					else:
						$admin_url = "#";
					endif;
					
					 
					if(count($sub_menu[$rw['id']])) 
					{
						echo '<li class="nav-dropdown ' . $class . '">
						<a href="'.$admin_url.'"><i class="ion-navicon-round"></i>'.$main_name.'
					    <i class="ion-chevron-right pull-right"></i></a>';
						echo "<ul>";
						foreach($sub_menu[$rw['id']] as $d) 
						{
						    $sub_name = stripslashes($d['name']);
							if(!empty($d['url'])):
								$admin_url2 = $d['url'];
							else:
								$admin_url2 = "#";
							endif;
							echo '<li><a href="'.$admin_url2.'">'.$sub_name.'</a></li>';
						}
					
						echo "</ul></li>";
					}
					else
					{
						$style='';
					    if(!$i)
					    {
						  $style="style='background:#b4abaf;padding-top:0px;padding-bottom:0px'";	
						}
						echo '<li ' . $style .' class="' . $class . '"><a href="'.$admin_url.'"><i class="ion-navicon-round"></i>'.$main_name.'</a></li>';
					   
					}
					$i++;
				}
				?>
                    </ul>					
			</div>			
        </div>	

	<!-- BEGIN RIGHTSIDE -->
        <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head bg-grey-100">
				<h1 class="page-title"><?php echo breadcrum($breadcrumb);?></h1>
				<div class='pull-right' style='padding-right:30px'><?=$btn?></div>
			</div>
			<!-- END PAGE HEADING -->
             
			
			<div class="container-fluid">
			 <div class="panel-body no-padding-top bg-white">
			 
		
            <div class='clearfix'></div>  
            <?if($msg)
			{
				echo"<p style='padding-top:10px;'>".$msg."</p>";
			}?>
			
			<div class="table-responsive">
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<!-- <script>
jQuery(document).ready(function($) {
    function toggleSidebar() {
        $('body').toggleClass('show-sidebar');
        var icon = $('#sidebarToggle i');
        if($('body').hasClass('show-sidebar')) {
            icon.removeClass('fa-bars').addClass('fa-times');
        } else {
            icon.removeClass('fa-times').addClass('fa-bars');
        }
    }

    $('#sidebarToggle, #sidebarOverlay').on('click', function() {
        toggleSidebar();
    });

    $(window).on('resize', function() {
        if($(window).width() > 992) {
            $('body').removeClass('show-sidebar');
            $('#sidebarToggle i').removeClass('fa-times').addClass('fa-bars');
        }
    });
});
</script> -->

<script>
    jQuery(document).ready(function($) {
        var $window = $(window);
        var $body = $('body');
        var $sidebar = $('.leftside');
        var $toggleBtn = $('#sidebarToggle');
        var $overlay = $('#sidebarOverlay');
        
        
        // Toggle sidebar visibility
        function toggleSidebar() {
            $body.toggleClass('show-sidebar');
            var icon = $toggleBtn.find('i');
            if($body.hasClass('show-sidebar')) {
                icon.removeClass('fa-bars').addClass('fa-times');
            } else {
                icon.removeClass('fa-times').addClass('fa-bars');
            }
        }
        
        // Event listeners
        $toggleBtn.on('click', toggleSidebar);
        $overlay.on('click', function() { 
            $body.removeClass('show-sidebar');
            $toggleBtn.find('i').removeClass('fa-times').addClass('fa-bars');
        });
        $(document).on('keyup', function(e) {
            if(e.key === 'Escape' && $body.hasClass('show-sidebar') && $window.width() <= 992) {
                $body.removeClass('show-sidebar');
                $toggleBtn.find('i').removeClass('fa-times').addClass('fa-bars');
            }
        });
        
        $window.on('resize', function() {
            if($window.width() > 992) {
                $body.removeClass('show-sidebar');
                $toggleBtn.find('i').removeClass('fa-times').addClass('fa-bars');
            }
        });
    });
</script>