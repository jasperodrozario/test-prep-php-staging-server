<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="DigiWEB 360">
    <meta name="author" content="MME">
    <link rel="icon" href="<?=$configs['admin_template_path'];?>images/favicon.ico">

    <title>DigiWEB360 - Dashboard </title>
  
	<!-- Bootstrap v4.0.0-beta -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>assets/vendor_components/bootstrap/dist/css/bootstrap.css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>assets/vendor_components/font-awesome/css/font-awesome.css">

	<!-- Ionicons -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>assets/vendor_components/Ionicons/css/ionicons.css">

	<!-- jvectormap -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>assets/vendor_components/jvectormap/jquery-jvectormap.css">

	<!-- Theme style -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>css/master_style.css">

	<!-- Maximum Admin Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>css/skins/_all-skins.css">

 
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?=$configs['admin_vpath'];?>account/dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">CP<!-- <img src='<?=$configs['vpath'].$configs['favicon']?>' width=45> --></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo"><b>DigiWEB360 CP</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
	
      <div class="navbar-custom-menu">
        
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=$configs['admin_template_path'];?>images/user2-160x160.jpg" class="user-image rounded-circle" alt="User Image">
              <span class="d-none d-sm-inline-block">Admin</span>
            </a>
            <ul class="dropdown-menu scale-up">
              <!-- User image -->
              <li class="user-header">
                
                <p>
                  Admin
                  <small>Today :  <?=showdate(date("Y-m-d"));?></small>
                </p>
              </li>
              
              <!-- Menu Footer-->
              <div class="user-footer">
                 
                <div class="text-center">
                  <a href="<?=$configs['admin_vpath']?>account/logout" class="btn btn-danger btn-flat">Sign out</a>
                </div>
              </div>
            </ul>
          </li>
          <!-- Messages: style can be found in dropdown.less-->

      
         
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel
      <div class="user-panel">
        <div class="image">
          <img src="<?=$configs['admin_template_path'];?>images/user2-160x160.jpg" class="rounded-circle" alt="User Image">
        </div>
        <div class="info">
          <p>Admin</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div> -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
       <!-- <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?=$configs['admin_vpath'];?>dashboard"><i class="fa fa-circle-o"></i> Dashboard</a></li>
             
          </ul>
        </li>-->
        <?
                
        $i=0;
        if(!$menu_open_id)
        {
          $menu_open_id=165;
        }
        foreach($menus as $rw ) 
        {
           
            $main_name = stripslashes($rw['name']);
          
            $class="";
            if($rw['id']==$menu_open_id)
            {
              $class="active menu-open"; 
            }
          
            if(!empty($rw['url']))
            {
               $admin_url = $rw['url'];
            }  
            else
            {
               $admin_url = "#";
            }  
            if(!empty($rw['icon']))
            {
               //$icon = "<img src='" . $configs['admin_vpath'] .$rw['icon'] ."'>";
              $icon  = "<i class='".$rw['icon']." fa-lg'></i>";
            }  
            else
            {
               $icon  = "<i class='fa fa-th fa-lg'></i>";
            }  
 
            if($sub_menu[$rw['id']]) 
            {
               
                echo '<li class="treeview '.$class.'"><a href="javascript:void(0);">' .$icon .'<span>'. $main_name .'</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span></a>';
           
                echo '<ul class="treeview-menu">';
                foreach($sub_menu[$rw['id']] as $d) 
                {
                    $sub_name = stripslashes($d['name']);
                  	if(!empty($d['url'])):
                    	$admin_url2 = $d['url'];
                  	else:
                    	$admin_url2 = "#";
                  	endif;
                  	echo '<li><a href="'.$configs['admin_vpath'].$admin_url2.'"><i class="fa fa-circle-o"></i>'.$sub_name.'</a></li>';
                }
          
                echo "</ul></li>";
            }
            else
            {
            
              echo '<li  ><i class="fa fa-circle-o text-red"><a href="'.$configs['admin_vpath'].$admin_url.'"><span>'.$main_name.'</span></a></li>';
             
            }
            $i++;
        }
        ?>
        
      </ul>
    </section>
    <!-- /.sidebar -->
    <div class="sidebar-footer">
		<!-- item-->
		<a href="<?=$configs['admin_vpath']?>account/dashboard" class="link" data-toggle="tooltip" title="" data-original-title="Settings"><i class="fa fa-cog fa-spin"></i></a>
		<!-- item
		<a href="#" class="link" data-toggle="tooltip" title="" data-original-title="Email"><i class="fa fa-envelope"></i></a>
		<!-- item-->
		<a href="<?=$configs['admin_vpath']?>account/logout" class="link" data-toggle="tooltip" title="" data-original-title="Logout"><i class="fa fa-power-off"></i></a>
	</div>
  </aside>