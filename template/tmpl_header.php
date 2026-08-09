<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $meta_title; ?></title>
    <meta name="description" content="<?=$meta_desc;?>" />
    <meta name="keywords" content="<?=$meta_keys;?>" />
    <meta name="google" content="notranslate" />
    <meta name="google-site-verification" content="Otuy6-iKfDFdkzxQQIMmHONadEKmrxHm7qB-8KuTOpM" />
    <meta name="author" content="Asvab-tutoring.com">

    <link rel="icon" href="<?=$configs['vpath'].$configs['favicon'];?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/owl.theme.default.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/owl.carousel.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/magnific-popup.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/animate.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/boxicons.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/flaticon.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/meanmenu.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/nice-select.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/odometer.min.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/style.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/dark.css">

    <link rel="stylesheet" href="<?=$configs['template_path']?>assets/css/responsive.css">

    <link rel="stylesheet" href="<?=$configs['template_path'];?>css/font-awesome.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>

    <link rel="shortcut icon" type="image/png" href="<?=$configs['vpath'].$configs['favicon']?>">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YLQES0V4N9"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-YLQES0V4N9');
    </script>
</head>
<body> 

<div class="loader-wrapper">
    <div class="loader">
        <div class="dot-wrap">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

<div class="navbar-area navbar-area-two">
    <div class="mobile-nav">
        <a href="index.html" class="logo">
            <img src="<?=$configs['vpath'].$configs['logo']?>" class="main-logo" alt="<?=$configs['dotcom']?>">
            <img src="<?=$configs['vpath'].$configs['logo']?>" class="white-logo" alt="<?=$configs['dotcom']?>">  
        </a>
    </div>

    <div class="main-nav">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-md">
                <a class="navbar-brand" href="<?=$configs['vpath'];?>">
                    <img src="<?=$configs['vpath'].$configs['logo']?>" class="main-logo" alt="<?=$configs['dotcom']?>" style="width: 280px; height: auto;">
                    <img src="<?=$configs['vpath'].$configs['logo']?>" class="white-logo" alt="<?=$configs['dotcom']?>" style="width: 280px; height: auto;">
                </a>
                <div class="collapse navbar-collapse mean-menu">
                    <ul class="navbar-nav m-auto">
                    <?
                    if(!$_GET['referral_id'])
                    {
                    ?>
                      <li class="nav-item" style="margin-right: 20px;"><a href="<?=$configs['vpath']?>" class="nav-link active">HOME</a></li>

                    <?php
                        $course_group_list = getQuery("course_group", "*", array(), "ord");
                        ?>

                        <div class="dropdown">
                            <li id="coursesDropdown" class="nav-item nav-link highlight dropdown-toggle" style="margin-right: 20px; cursor: pointer;">COURSES</li>
                            <div class="dropdown-content">
                                <?php foreach ($course_group_list as $c): ?>
                                    <?php $num_of_courses = getTotalRow("course", array("group_id" => $c['group_id'], "is_online" => "Y")); ?>
                                    <?php if ($num_of_courses): ?>
                                        <div class="dropdown-option">
                                            <button class="dropdown-option-toggle"><?= $c['group_name'] ?><span class="toggle-icon"> ►</span></button>
                                            <ul class="dropdown-submenu">
                                                <?php
                                                $course_list = getQuery("course", "*", array("group_id" => $c['group_id'], "is_online" => "Y"), "ord");
                                                foreach ($course_list as $cr):
                                                ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?= $configs['vpath'] . $location; ?>course/<?= seo_url($cr['course_name']) ?>"><?= $cr['course_name'] ?></a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <li class="nav-item"><a href="<?=$configs['vpath']?>get-tutor" class="nav-link" style="margin-right: 20px;">1-on-1 Tutor</a></li>
                        <li class="nav-item"><a href="<?=$configs['vpath']?>blog" class="nav-link" style="margin-right: 20px;">BLOG</a></li>
                    <?}?>
                    </ul>

                    <div class="others-option">
                        <div class="option-item">
                            <i class="search-btn bx bx-search"></i>
                            <i class="close-btn bx bx-x"></i>
                        </div>
                    <?
                    if(!$_GET['referral_id'])
                    {
                      if($_SESSION['user_id'])
                      {
                         ?>
                         <div class="register" >
                             <ul class="navbar-nav m-auto">
                                 <li class="nav-item">
                                    <a href="#" class="nav-link highlight">
                                    <?=$_SESSION['name']?>
                                        <i class='bx bx-chevron-down'></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item">
                                            <a href="<?=$configs['vpath']?>edit-profile" class="nav-link">My Profile</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?=$configs['vpath']?>change-password" class="nav-link">Change Password</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?=$configs['vpath']?>affiliate-dashboard" class="nav-link">Affiliate Dashboard</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?=$configs['vpath']?>logout" class="nav-link">Logout</a>
                                        </li>
                                    </ul>
                                </li>
                                 <li>
                                     <a type="button" class="btn study-btn" href="<?=$configs['vpath']?>practice-test/exam/<?=$_SESSION['test_id']?>-<?=seo_url($_SESSION['test_name'])?>">
                                        Study
                                    </a>

                                 </li>
                            </ul>
                        </div>
                      <?
                      }
                      else
                      {?>
                      <div class="register" style="margin-right: 5px;"><a href="<?=$configs['vpath']?>register" class="default-btn" style="width: 120px;">Subscribe</a></div>

                      <div class="register"><a href="<?=$configs['vpath']?>login" class="default-btn" style="width: 120px;">Login</a></div>
                      <?}
                    }
                    ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="others-option-for-responsive">
        <div class="container">
            <div class="dot-menu">
                <div class="inner">
                    <div class="circle circle-one"></div>
                    <div class="circle circle-two"></div>
                    <div class="circle circle-three"></div>
                </div>
            </div>
            <div class="container">
                <div class="option-inner">
                    <div class="others-option justify-content-center d-flex align-items-center">
                        <div class="register">
                            <div class="register" ><a href="<?=$configs['vpath']?>register" class="default-btn">Subscribe</a></div>
                            <div class="register" ><a href="<?=$configs['vpath']?>login" class="default-btn">Login</a></div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>

</div>
    

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownContent = document.querySelector('.dropdown-content');

    // Toggle main dropdown
    dropdownToggle.addEventListener('click', function (event) {
        event.stopPropagation();
        dropdownContent.classList.toggle('show');
    });

    // Handle dropdown option clicks
    const dropdownOptions = document.querySelectorAll('.dropdown-option-toggle');
    dropdownOptions.forEach(option => {
        option.addEventListener('click', function (event) {
            event.stopPropagation();

            // Collapse all other dropdown submenus
            dropdownOptions.forEach(otherOption => {
                if (otherOption !== option) {
                    otherOption.nextElementSibling.classList.remove('show');
                }
            });

            // Toggle the clicked dropdown submenu
            const submenu = option.nextElementSibling;
            submenu.classList.toggle('show');
        });
    });

    // Close dropdowns when clicking outside
    window.addEventListener('click', function () {
        dropdownContent.classList.remove('show');
        document.querySelectorAll('.dropdown-submenu').forEach(submenu => {
            submenu.classList.remove('show');
        });
    });
});
</script>
    
    
<style>
/* Dropdown container */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown toggle button */
.dropdown-toggle {
    color: black;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

/* Toggle icon */
.toggle-icon {
    margin-left: 10px; /* Space between text and icon */
    font-size: 12px; /* Adjust icon size */
    transition: transform 0.3s ease; /* Smooth rotation animation */
}
    
/* Dropdown content */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 200px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

/* Dropdown options */
.dropdown-option {
    position: relative;
}

/* Dropdown option toggle button */
.dropdown-option-toggle {
    width: 100%;
    text-align: left;
    padding: 10px;
    background-color: #f1f1f1;
    border: none;
/*    border: 0.5px solid #ccc;*/
    cursor: pointer;
    font-size: 14px;
}

.dropdown-option-toggle:hover {
    background-color: #F1C40F;
}

/* Dropdown submenu */
.dropdown-submenu {
    display: none;
    position: absolute;
    left: 100%;
    top: 0;
    background-color: #f9f9f9;
    min-width: 200px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

/* Show dropdown content when active */
.dropdown-content.show {
    display: block;
}

/* Show submenu when active */
.dropdown-submenu.show {
    display: block;
}
</style>
    
<style>
    .study-btn {
        margin-left: 15px;
        margin-top: 5px;
        width: 120px;
        border-color: red;
        background-color: white;
        color: red;
        font-weight: bold;
        border-width: 2px;
    }

    .study-btn:hover {
        transform: scale(1.05);
        color: red;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .default-btn {
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 270px;
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 12px;
    }

    .dropdown-content a:hover {
        background-color: #F1C40F;
    }

    .dropdown-content.show {
        display: block;
    }

    .nested-dropdown-content {
        display: none;
    }

    .nested-dropdown:hover .nested-dropdown-content {
        display: block;
    }
</style>