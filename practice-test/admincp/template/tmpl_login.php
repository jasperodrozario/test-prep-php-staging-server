<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<title><?php echo $configs['dotcom'];?> - Login</title>
	<!-- BEGIN CORE FRAMEWORK -->
	<link href="<?=$configs['tpath'];?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<!-- END CORE FRAMEWORK -->
	
	<!-- BEGIN PLUGIN STYLES -->
	<link href="<?=$configs['tpath'];?>assets/plugins/animate/animate.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/bootstrapValidator/bootstrapValidator.min.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
	<!-- END PLUGIN STYLES -->
	<style>
	.auth-page{
		background-image: url(images/background.jpg);
	}	
    </style>
 
	<!-- BEGIN THEME STYLES -->
	<link href="<?=$configs['tpath'];?>assets/css/material.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/style.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/plugins.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/helpers.css" rel="stylesheet" />
	<link href="<?=$configs['tpath'];?>assets/css/responsive.css" rel="stylesheet" />
	<!-- END THEME STYLES -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="auth-page height-auto Login-page">
 
	<!-- BEGIN CONTENT -->
	<div class="wrapper animated fadeInDown text-center" >
     
	<?php if(isset($msg)){echo "<p>".$msg."</p>";}?>
		<div class="panel midle-conpart overflow-hidden margin-bottom0" style='margin-top:100px'>
		   
			<form id="checkform" method="post">
				<div class="alert text-center sub-title no-radius no-margin">Sign in to  <?=Ucwords($configs['dotcom']);?> dashboard </div>
				<div class="box-body padding-md">
				
					<div class="form-group">
						<input type="text" name="username" class="form-control input-lg" placeholder="Username" required/>
					</div>
					
					<div class="form-group">
						<input type="password" name="password" class="form-control input-lg" placeholder="Password" required/>
					</div>   
					<div class="form-group">
                      <img src='<?=$configs['vpath'].$configs['admin_dir'];?>/captcha_code.php' height=40>
    				
					<input type="text" name="captchatext" id="captcha-form" class="form-control input-lg" placeholder="Security Code" required/>
					 
					</div> 
                    <div class="form-group margin-top-20 text-right"><a href="forgot-pass.php#"> Forgot Password? </a></div>
                       
					
					<!--<div class="form-group margin-top-20">
						<input type="checkbox" class="js-switch" id="checkbox" checked /><label for="checkbox" class="font-size-12 normal margin-left-10">Remember Me</label>
					</div>    -->   
					
					<button type="submit" name='submit' class="btn btn-dark bg-light-green-500 padding-10 btn-block color-white"><i class="ion-log-in"></i> Sign in</button>  
				    
				</div>
			</form>
			<!--<div class="panel-footer padding-md no-margin no-border bg-light-blue-500 text-center color-white">&copy; <?php //echo date("Y");?> <?php //echo Ucwords($dotcom);?>.</div>-->
		</div>
	</div>
	<!-- END CONTENT -->
		
	<!-- BEGIN JAVASCRIPTS -->
	
	<!-- BEGIN CORE PLUGINS -->
	<script src="<?=$configs['tpath'];?>assets/plugins/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrap/js/holder.js"></script>
	<script src="<?=$configs['tpath'];?>assets/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?=$configs['tpath'];?>assets/js/core.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	
	<!-- bootstrap validator -->
	<script src="<?=$configs['tpath'];?>assets/plugins/bootstrapValidator/bootstrapValidator.min.js" type="text/javascript"></script>
	
	<!-- switchery -->
	<script src="<?=$configs['tpath'];?>assets/plugins/switchery/switchery.min.js" type="text/javascript"></script>
	
	<!-- maniac -->
	<script src="<?=$configs['tpath'];?>assets/js/maniac.js" type="text/javascript"></script>

	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>