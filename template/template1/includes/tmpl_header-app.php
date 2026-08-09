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

    <link rel="stylesheet" href="<?= $configs['template_path']; ?>css/font-awesome.min.css" />

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