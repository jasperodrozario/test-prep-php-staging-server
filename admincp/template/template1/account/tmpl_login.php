<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="NfinBank">
    <meta name="author" content="MMeTech">
    <link rel="icon" href="<?=$configs['admin_template_path'];?>images/favicon.ico">

    <title><?php echo $configs['dotcom'];?> - Login</title>
  
	<!-- Bootstrap v4.0.0-beta -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>assets/vendor_components/bootstrap/dist/css/bootstrap.min.css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>assets/vendor_components/font-awesome/css/font-awesome.min.css">

	<!-- Ionicons -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>assets/vendor_components/Ionicons/css/ionicons.min.css">

	<!-- Theme style -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>css/master_style.css">

	<!-- maximum_admin Skins. Choose a skin from the css/skins
	   folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?=$configs['admin_template_path'];?>css/skins/_all-skins.css">	

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="<?=$configs['admin_vpath'];?>"><?=ucwords($configs['domain']);?></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
 <form class="form-element" id="form" method="post">
 <input type='hidden' name='action' value='admin-login'>
  <div id=response> </div>
       <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="Username" autocomplete="off" required>
        <span class="ion ion-email form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
        <span class="ion ion-locked form-control-feedback"></span>
      </div>
       <div class="form-group has-feedback">
          <img src='<?=$configs['admin_vpath'];?>/captcha_code.php' height=40>
            
          <input type="text" class="form-control" name='captchatext' placeholder="Security Code" required autocomplete="off">
          <span class="ion ion-locked form-control-feedback"></span>
      </div>
     
      <div class="form-group has-feedback">
          <button type="submit" class="btn btn-info btn-block btn-flat margin-top-10" style='width:100%'>SIGN IN</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

   <!-- <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-social-icon btn-circle btn-facebook"><i class="fa fa-facebook"></i></a>
      <a href="#" class="btn btn-social-icon btn-circle btn-google"><i class="fa fa-google-plus"></i></a>
    </div>-->
    <!-- /.social-auth-links -->

   <!-- <div class="margin-top-30 text-center">
    	<p>Don't have an account? <a href="register.html" class="text-info m-l-5">Sign Up</a></p>
    </div>-->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->


	<!-- jQuery 3 -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/jquery/dist/jquery.min.js"></script>
	
	<!-- popper -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/popper/dist/popper.min.js"></script>
	
	<!-- Bootstrap v4.0.0-beta -->
	<script src="<?=$configs['admin_template_path'];?>assets/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="<?=$configs['admin_vpath']?>template/js.js"></script>
</body>
</html>
