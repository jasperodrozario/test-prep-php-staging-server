<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo $meta_title; ?></title>
    <meta name="description" content="<?= $meta_desc; ?>" />
    <meta name="keywords" content="<?= $meta_keys; ?>" />
    <meta name="google" content="notranslate" />
    <meta name="google-site-verification" content="Otuy6-iKfDFdkzxQQIMmHONadEKmrxHm7qB-8KuTOpM" />
    <meta name="author" content="Asvab-tutoring.com">

    <link rel="icon" href="<?= $configs['vpath'] . $configs['favicon']; ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/owl.theme.default.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/owl.carousel.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/magnific-popup.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/animate.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/boxicons.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/flaticon.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/meanmenu.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/nice-select.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/odometer.min.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/style.css?v=<?= filemtime($configs['template_apath'] . 'assets/css/style.css') ?>">


    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/dark.css">

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/responsive.css">

    <link rel="stylesheet" href="<?= $configs['template_path']; ?>assets/css/font-awesome.min.css" />

    <link rel="stylesheet" href="<?= $configs['template_path'] ?>assets/css/style-2.css?v=<?= filemtime($configs['template_apath'] . 'assets/css/style-2.css') ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>

    <link rel="shortcut icon" type="image/png" href="<?= $configs['vpath'] . $configs['favicon'] ?>">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YLQES0V4N9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-YLQES0V4N9');
    </script>
</head>

<body>

    <?php
    if (isset($_GET['referral_id'])) {
        $referral_id = $_GET['referral_id'];
        $ref_type = $_GET['ref_type'];

        // store for 24 hours
        setcookie("referral_id", $referral_id, time() + (24 * 60 * 60), "/");
        setcookie("ref_type", $ref_type, time() + (24 * 60 * 60), "/");
    }
    ?>

    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="<?= $configs['vpath']; ?>">
                <!-- Emblem -->
                <img src="<?= $configs['vpath'] ?>img/logo.png" 
                    alt="<?= $configs['dotcom'] ?>" 
                    style="height: 50px; width: auto;">

                <!-- Text beside logo -->
                <div class="ms-2">
                    <span class="brand">TEST-PREP</span><br>
                    <span class="brand-description">Online Test Series</span>
                </div>

                
            </a>

            <!-- Hamburger toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#mainNavbar" aria-controls="mainNavbar" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav items -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a href="<?= $configs['vpath'] ?>" class="nav-link active">HOME</a>
                        </li>

                        <!-- COURSES (1st level only, all courses) -->
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            COURSES
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <?php
                            $course_group_list = getQuery("course_group", "*", [], "ord");
                            foreach ($course_group_list as $g) {
                            $course_list = getQuery("course", "*",
                                ["group_id"=>$g['group_id'],"is_online"=>"Y","subscribe"=>1], "ord");
                            if ($course_list) {
                                foreach ($course_list as $cr) { ?>
                                <li>
                                    <a class="dropdown-item"
                                    href="<?= $configs['vpath'] ?>course/<?= seo_url($cr['course_name']) ?>">
                                    <?= $cr['course_name'] ?>
                                    </a>
                                </li>
                            <?php } } } ?>
                        </ul>
                        </li>

                        <!-- 1-on-1 Tutor dropdown -->
                        <?php 
                        // $all_courses = getQuery("course", "*", ["is_online" => "Y", "tutoring" => 1], "course_name"); 
                        $all_courses = getQuery("course", "*", ["is_online" => "Y", "subscribe" => 1], "course_name"); 
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="tutorDropdown" 
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                1-ON-1 TUTOR
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="tutorDropdown">
                                <?php foreach ($all_courses as $course) { ?>
                                    <li>
                                        <a class="dropdown-item" 
                                           href="<?= $configs['vpath'] ?>get-tutor?course_id=<?= $course['course_id'] ?>">
                                           <?= $course['course_name']; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="<?= $configs['vpath'] ?>blog" class="nav-link">BLOG</a>
                        </li>
                    
                </ul>

                <!-- Right side (desktop view) -->
                <div class="d-none d-md-flex">
                        <?php if ($_SESSION['user_id']) { ?>
                            <!-- Logged-in user dropdown -->
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle highlight" href="#" id="userDropdown"
                                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= htmlspecialchars($_SESSION['name']) ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <?php if ($_SESSION['user_type'] != "Referrer") { ?>
                                            <li><a href="<?= $configs['vpath'] ?>edit-profile" class="dropdown-item">My Profile</a></li>
                                            <li><a href="<?= $configs['vpath'] ?>subscription-profile" class="dropdown-item">Subscription</a></li>
                                            <li><a href="<?= $configs['vpath'] ?>change-password" class="dropdown-item">Change Password</a></li>
                                        <?php } ?>
                                        <li><a href="<?= $configs['vpath'] ?>affiliate-dashboard" class="dropdown-item">Affiliate Dashboard</a></li>
                                        <li><a href="<?= $configs['vpath'] ?>logout" class="dropdown-item">Logout</a></li>
                                    </ul>
                                </li>
                                <?
                                if($_SESSION['test_id'] && $_SESSION['test_name']){
                                ?>
                                    <li class="nav-item">
                                        <a class="btn btn-success ms-2" 
                                        href="<?= $configs['vpath'] ?>practice-test/exam/<?= $_SESSION['test_id'] ?>-<?= seo_url($_SESSION['test_name']) ?>">
                                            Study
                                        </a>
                                    </li>
                                <?
                                }
                                ?>
                            </ul>
                        <?php } else { 
                            $button = ($_GET['account']=="free") ? "Register" : "Subscribe";
                            $page_name = ($_GET['account']=="free") ? "register-free" : "subscribe";
                        ?>
                            <a href="<?= $configs['vpath'].$page_name ?>" class="btn btn-warning me-2"><?= $button ?></a>
                            <a href="<?= $configs['vpath'] ?>login" class="btn btn-primary">Login</a>
                        <?php } ?>
                </div>
                
                <!-- Right side (mobile: inside hamburger) -->
                <div class="d-md-none mt-3">
                        <?php if ($_SESSION['user_id']) { ?>
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle highlight" href="#" id="userDropdownMobile"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= htmlspecialchars($_SESSION['name']) ?>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="userDropdownMobile">

                                        <?php if ($_SESSION['user_type'] != "Referrer") { ?>
                                            <li><a href="<?= $configs['vpath'] ?>edit-profile" class="dropdown-item">My Profile</a></li>
                                            <li><a href="<?= $configs['vpath'] ?>subscription-profile" class="dropdown-item">Subscription</a></li>
                                            <li><a href="<?= $configs['vpath'] ?>change-password" class="dropdown-item">Change Password</a></li>
                                        <?php } ?>
                                        
                                        <li><a href="<?= $configs['vpath'] ?>affiliate-dashboard" class="dropdown-item">Affiliate Dashboard</a></li>
                                        <li><a href="<?= $configs['vpath'] ?>logout" class="dropdown-item">Logout</a></li>
                                    </ul>
                                </li>
                                <?
                                if($_SESSION['test_id'] && $_SESSION['test_name']){
                                ?>
                                <li class="nav-item mt-2">
                                    <a class="btn btn-success w-100"
                                    href="<?= $configs['vpath'] ?>practice-test/exam/<?= $_SESSION['test_id'] ?>-<?= seo_url($_SESSION['test_name']) ?>">
                                        Study
                                    </a>
                                </li>
                                <?
                                }
                                ?>
                            </ul>
                        <?php } else { 
                            $button = ($_GET['account']=="free") ? "Register" : "Subscribe";
                            $page_name = ($_GET['account']=="free") ? "register-free" : "subscribe";
                        ?>
                            <a href="<?= $configs['vpath'].$page_name ?>" class="btn btn-warning w-100 mb-2"><?= $button ?></a>
                            <a href="<?= $configs['vpath'] ?>login" class="btn btn-primary w-100">Login</a>
                        <?php } ?>
                </div>
            </div>


        </div>
    </nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Toggle 2nd-level submenus
  document.querySelectorAll('.submenu-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();

      const submenu = this.nextElementSibling;
      if (!submenu) return;

      // Close siblings
      const parentMenu = this.closest('.dropdown-menu');
      parentMenu.querySelectorAll('.dropdown-menu.show').forEach(function (m) {
        if (m !== submenu) m.classList.remove('show');
      });
      parentMenu.querySelectorAll('.submenu-toggle[aria-expanded="true"]').forEach(function (t) {
        if (t !== toggle) t.setAttribute('aria-expanded', 'false');
      });

      // Toggle this submenu
      const isOpen = submenu.classList.toggle('show');
      this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  });

  // When the top-level dropdown closes, close any open submenus
  document.querySelectorAll('.dropdown').forEach(function (dd) {
    dd.addEventListener('hide.bs.dropdown', function () {
      this.querySelectorAll('.dropdown-menu.show').forEach(function (m) { m.classList.remove('show'); });
      this.querySelectorAll('.submenu-toggle[aria-expanded="true"]').forEach(function (t) { t.setAttribute('aria-expanded','false'); });
    });
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const dropdowns = document.querySelectorAll('.dropdown');

  dropdowns.forEach(dropdown => {
    const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
    const menu = dropdown.querySelector('.dropdown-menu');

    // Initial setup
    menu.style.overflow = 'hidden';
    menu.style.transition = 'max-height 0.5s ease';

    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      const isOpen = dropdown.classList.contains('show');

      if (isOpen) {
        // Collapse
        menu.style.maxHeight = '0';
        menu.style.opacity = '0';
        setTimeout(() => {
          menu.style.display = 'none';
        }, 500); // Match transition duration
        dropdown.classList.remove('show');
      } else {
        // Expand
        menu.style.display = 'block';
        menu.style.maxHeight = menu.scrollHeight + 'px';
        menu.style.opacity = '1';
        dropdown.classList.add('show');
      }
    });

    // Optional: Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
      if (!dropdown.contains(e.target)) {
        menu.style.maxHeight = '0';
        menu.style.opacity = '0';
        setTimeout(() => {
          menu.style.display = 'none';
        }, 500);
        dropdown.classList.remove('show');
      }
    });
  });
});
</script>




    <style>
        /* Dropdown container */
        
    </style>