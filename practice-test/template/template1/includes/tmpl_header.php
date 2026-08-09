<!DOCTYPE html>

<html xmlns:m="http://www.w3.org/1998/Math/MathML" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $configs['site_title'] ?></title>
    <meta http-equiv="Content-Security-Policy" content="block-all-mixed-content">
    <link rel="shortcut icon" href="<?= $configs['vpath'] . $configs['favicon']; ?>">
    <meta name="description" content="<?= $configs['meta_desc'] ?>">
    <meta name="keywords" content="<?= $configs['meta_keys'] ?>">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/font-awesome.min.css"><!-- keep ONE FA; remove CDN v6.2 to avoid conflicts -->
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/jquery_smartmenus_bootstrap.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/slick.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/slick-theme.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/swiper.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"><!-- jQuery UI CSS in head -->

    <!-- Fonts -->
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>fonts/webfontkit-Muli/stylesheet.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/icofont.css">

    <!-- Site CSS (order: base → theme → overrides → responsive) -->
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/style.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/mtq_core_style.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/mtq_theme_style.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/custom.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/responsive.css">
    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/modal-video.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/style-2.css?v=<?= filemtime($configs['template_abspath'] . 'css/style-2.css'); ?>">

    <!-- 
        MathJax (OK in head) -->
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>


</head>

<body>
    <div class="pageloader"></div>
    <a href="#" id="scroll" style="display: none;"><span></span></a>
    <!--header section-->

    <div class="header_wrapper">
        <div class="container">
            <div class="col-lg-3 col-md-3 col-sm-3 header-logo">
                <div class="nav_logo">

                    <a href="<?= str_replace("/practice-test", "", $configs['vpath']); ?>">
                        <div class="logo-container">
                            <img src="<?= $configs['vpath'] . "template/template1/images/test_prep_logo.png"; ?>" alt="<?= $configs['dotcom'] ?>" class="img-responsive" style="width: 70px; height: auto; margin-bottom: 10px">
                            <span class="logo-text">
                                <span class="line1"><?php echo $_SESSION['test_name']; ?> TEST PREP </span><br>
                                <span class="line2">Online Test Series</span>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Header nevigation menu start -->
            <div class="col-lg-9 col-md-9 col-sm-9 padding0">
                <div class="main_nav">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"><img src="<?= $configs['template_path']; ?>images/menu-icon.png"></span>
                    </button>
                    <div id="navbarSupportedContent" class="navbar-collapse collapse">
                        <!-- nav -->
                        <div class="cut-nav">
                            <!-- new -->
                            <ul class="top-level-menu nav navbar-nav">
                                <?
                                if ($_SESSION['user_type'] == "Tutor") {
                                ?>
                                    <li class="home1"><a href="<?= $configs['vpath']; ?>report-student-list">Home</a></li>
                                    <li><a href="<?= $configs['vpath']; ?>report-student-list">Student List</a></li>
                                    <li><a href="<?= $configs['vpath']; ?>get-student-report">Progress Report</a></li>
                                <?
                                } else {
                                ?>
                                    <?= getMenu("location_header");

                                    $has_1_on_1_tutoring_content = getTotalRow("asvab_tutor_package", array("code_name" => $_SESSION['test_name'], "is_online" => "Y", "is_group_tutoring" => 0));

                                    $course_name = getFldValueById("course", "course_name", array("course_id" => $_SESSION['test_id']));

                                    if ($has_1_on_1_tutoring_content) { ?>

                                        <li><a href=<? echo "https://www.asvab-tutoring.com/package-details/1-on-1-tutoring/" . $course_name ?> target='_new'>Get a Tutor</a></li>

                                    <? }
                                    if($_SESSION['has_multi_courses']>1){
                                        $password = getFldValueById("user", "password", array("user_id" => $_SESSION['user_id']));
                                    ?>
                                        <li>
                                            <a href="javascript:void(0)" data-toggle="dropdown" id="user_profile">
                                                Switch Course
                                                <b class='caret'></b>
                                            </a>
                                            <ul class="second-level-menu">
                                                <?php

                                                $multi_courses = getQueryByJoin(array("user as u", "subscription as s"), "u.*,s.course_id", array("u.email" => $_SESSION['email']), array("u.user_id" => "s.user_id"));

                                                foreach ($multi_courses as $course) {
                                                    $course_name = getFldValueById("course", "course_name", array("course_id" => $course['course_id']));
                                                    ?>
                                                    <li><a href="<?= $configs['vpath'] ?>login/<?= $_SESSION['email']?>/<?= $password ?>/<?= $course['course_id'] ?>"><?= $course_name ?></a></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    <? }
                                    ?>
                                <? } ?>
                                <?php if (!empty($_SESSION['user_id'])) { ?>
                                    <?php if ($_SESSION['user_type'] === "Tutor") { ?>
                                        <li>
                                            <a href="<?= $configs['vpath'] ?>my-dashboard" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cog" aria-hidden="true"></i> My Dashboard
                                                <b class='caret'></b></a>
                                            <ul class="second-level-menu">
                                                <li><a href="<?= $configs['vpath'] ?>get-student-report">View Progress Report</a></li>
                                                <li><a href="<?= $configs['vpath'] ?>logout">Logout</a></li>
                                            </ul>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="javascript:void(0)" data-toggle="dropdown" id="user_profile">
                                                <i class="fa fa-cog" aria-hidden="true"></i> <?= $_SESSION['name'] ?>
                                                <b class='caret'></b></a>
                                            <ul class="second-level-menu">
                                                <li><a href="<?= str_replace("/practice-test", "", $configs['vpath']) ?>affiliate-dashboard">Affiliate Dashboard</a></li>
                                                <li><a href="<?= str_replace("/practice-test", "", $configs['vpath']) ?>edit-profile">Edit Profile</a></li>

                                                <li>
                                                    <a href="<?= str_replace("/practice-test", "", $configs['vpath']) ?>subscription-profile" class="dropdown-item">Subscription</a>
                                                </li>

                                                <li><a href="<?= $configs['vpath'] ?>exam/progress-report">My Progress Report</a></li>
                                                <li><a href="<?= str_replace("/practice-test", "", $configs['vpath']) ?>change-password">Change Password</a></li>
                                                <li><a href="<?= $configs['vpath'] ?>logout">Logout</a></li>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                <?php } else { ?>
                                    <li class="login">
                                        <a href="<?= str_replace("/practice-test", "", $configs['vpath']) ?>login">
                                            <span><img src="<?= $configs['template_path'] ?>images/login.png" alt="Login" style="height:2em; vertical-align:middle;"></span> Login
                                        </a>
                                    </li>
                                    <li class="res-reg">
                                        <a href="<?= str_replace("/practice-test", "", $configs['vpath']) ?>register">
                                            <span><img src="<?= $configs['template_path'] ?>images/reg.png" alt="Register" style="height:2em; vertical-align:middle;"></span> Subscribe
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Header nevigation menu end -->

            <div class="clearfix"></div>
        </div>
    </div>

    <style>

    </style>