
<!DOCTYPE html>

<html xmlns:m="http://www.w3.org/1998/Math/MathML" lang="en"> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $configs['site_title']?></title>
    <meta http-equiv="Content-Security-Policy" content="block-all-mixed-content">
    <link rel="shortcut icon" href="<?=$configs['vpath'].$configs['favicon'];?>">
    <meta name="description" content="<?php echo $configs['meta_desc']?>">
    <meta name="kewords" content="<?php echo $configs['meta_keys']?>">
    <!-- Bootstrap -->
    <link href="<?=$configs['template_path'];?>css/bootstrap.min.css" rel="stylesheet">
    <!--font_css-->
    <link rel="stylesheet" href="<?=$configs['template_path'];?>css/font-awesome.min.css"/>
    <link href="<?=$configs['template_path'];?>fonts/webfontkit-Muli/stylesheet.css" rel="stylesheet"/>
    <!-- SmartMenus jQuery Bootstrap Addon CSS -->
    <link href="<?=$configs['template_path'];?>css/jquery_smartmenus_bootstrap.css" rel="stylesheet">
    <!--style_css-->
    <link href="<?=$configs['template_path'];?>css/style.css" rel="stylesheet"/>
     <link href="<?=$configs['template_path'];?>css/icofont.css" rel="stylesheet"/>
    
    <link href="<?=$configs['template_path'];?>css/custom.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/responsive.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/owl.carousel.min.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/modal-video.min.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/slick-theme.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/slick.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/mtq_core_style.css" rel="stylesheet"/>
    <link href="<?=$configs['template_path'];?>css/mtq_theme_style.css" rel="stylesheet"/>
	<link href="<?=$configs['template_path'];?>css/swiper.min.css" rel="stylesheet"/>
    <base href="<?=$configs['template_path'];?>">
<script src=""></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>   </head>
  <body>
   <div class="pageloader"></div>
	<!--header section-->
    <div class="header_wrapper">
        <div class="container">
            <div class="col-lg-3 col-sm-3 header-logo">
                <div class="nav_logo">
                    <a href="<?=$configs['vpath'];?>"><img src="<?=$configs['vpath'].$configs['logo'];?>" alt="<?=$configs['dotcom']?>" class="img-responsive"> Online Officer <br>Candidate Test Series</a>
                    
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 padding0">
                <div class="main_nav">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"><img src="<?=$configs['template_path'];?>images/menu-icon.png"></span>
  </button>
                    <div id="navbarSupportedContent" class="navbar-collapse collapse">
                        <!-- nav --> 
                        <div class="cut-nav">
                                <!-- new -->
                            <ul class="top-level-menu nav navbar-nav">
                           
                            
                           <?=getMenu("location_header");?>
                           
                            </ul>
                        </div>
           
                    </div>
                    
                   
                </div>
            </div>
			 <div class="col-lg-3 col-md-3 col-sm-3 res-header-right">
                <ul class="login-part">
                <?
                if($_SESSION['user_id'])
                {
                    
                    echo"<li class='dash-menu dropdown'><a href='" .$configs['vpath'] . "my-dashboard' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false' ><img src='".$configs['vpath'] ."img/dashboard.png' width=16 onClick=\"window.location='" .$configs['vpath'] . "my-dashboard';\"> My Dashboard</a>
					  <ul class='dropdown-menu'>
					     <li><a href='" .$configs['vpath']."my-dashboard'>Dashboad</a></li>
						
					    <li><a href='" .$configs['vpath']."edit-profile'>Edit Profile</a></li>
						<li><a href='" .$configs['vpath']."exam/" .$_SESSION['test_id'] ."-" .seo_url($_SESSION['test_name']) ."'>My Online Exam</a></li>
						<li><a href='" .$configs['vpath']."exam/progress-report'>My Progress Report</a></li>
						
						<li><a href='" .$configs['vpath']."change-password'>Change Password</a></li>
					 
						<li><a href='" .$configs['vpath']."logout'>Logout</a></li>
					  </ul>
					</li>";
                    
                     
                     
                }
                else
                {
                ?>         
                    <li class="login"><a  href="<?=$configs['vpath'];?>login"><span><img src="<?=$configs['template_path'];?>images/login.png"></span> Login</a></li>
                    <li class="res-reg"><a href="<?=$configs['vpath'];?>register"><span><img src="<?=$configs['template_path'];?>images/reg.png"></span> Register</a></li>
                <?
                }
                ?>

            </ul>
            <?if(!$_SESSION['user_id']){?>
                <!-- The Modal -->
            
            <div id="signModal" class="modal">
                <span class="close" data-dismiss="modal">&times;</span>
                <div class="modal-content" id="img01">
                  <div class="form-bg">
    
            <div class="col-md-12 padding0">
                <div class="tab" role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a id="log" href="#Section1" aria-controls="home" role="tab" data-toggle="tab">sign in</a></li>
                        
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content tabs">
                        <div role="tabpanel" class="tab-pane fade in active" id="Section1">
                            <div id='response' style='color:white'></div>
                            <form class="form-horizontal" id='loginForm' method="post">
                                <input type='hidden' name='action' value='login'>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email Id </label>
                                    <input type="email" class="form-control" name='email' id="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control" name='password' id="password"  required>
                                </div>
                                <div class="form-group mr20">
                                    <div class="main-checkbox">
                                        <input value="None" id="checkbox1" name="check" type="checkbox">
                                        <label for="checkbox1"></label>
                                    </div>
                                    <span class="text">Keep me Signed in</span>
                                    <div class="forgot-pass">
                                    <a id="forgot" href="#Section2" aria-controls="home" role="tab" data-toggle="tab">forgot password</a>
                                </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">Sign in</button>
                                </div>
                              
                                 <div class="form-group fb-login">
                                    
                                </div>
                            </form>
                        </div>
                        
                        <div role="tabpanel" class="tab-pane fade" id="Section2">
                            <div id="response_forgot" ></div>
                            <form class="form-horizontal" id="forgotForm">
                             <input type='hidden' name='action' value='forgot-password'>
                              
                                <div class="form-group">
                                    <label for="exampleInputEmail1">E-mail</label>
                                    <input class="form-control" id="email1" name='email1' type="email" required>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-default" id='login'>Submit</button>
                                </div>
                               
                            </form>
                        </div>
                       
                    </div>
                    <?}?>
                </div>
            </div>
            </div>
  
                </div>
                </div>
                      </div>
            <div class="clearfix"></div>      
        </div>           
    </div> 
    
