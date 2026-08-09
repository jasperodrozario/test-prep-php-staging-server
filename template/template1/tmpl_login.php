<main>

    <div class="page-title-area bg-8">
        <div class="container">
            <div class="page-title-content">
                <h2>Log In</h2>
            </div>
        </div>
    </div>

    <section class="user-area-style ptb-100">
        <div class="container">
            <div class="log-in-area">
                <div class="section-title">
                    <h2>Log In</h2>
                </div>
                <div class="contact-form-action">
                    <div id='login-response'>
                        <?php
                            // Check if there's a login error message
    //                        if (isset($msg) && !empty($msg)) {
    //                            echo '<div class="alert alert-danger">' . $msg . '</div>';
    //                        }
                        ?>
                    </div>
                    <form id="loginForm" method="post" enctype="multipart/form-data">
                        <input type='hidden' name='action' value='login'>
                        <input type='hidden' name='referer' value='<?=$_GET['referer'];?>'>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" required>
                                </div>
                            </div>
                            <!-- <div class="col-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" required>
                                </div>
                            </div> -->
                            <div class="col-12">
                                <div class="form-group position-relative">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" id="password" required>
                                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()" style="position: absolute; right: 15px; top: 55px; cursor: pointer;"></i>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="login-action">
                                    <button class="default-btn" type="submit" name="login_btn">
                                        Log In
                                    </button>
                                    <span class="forgot-login">
                                        <a href="<?=$configs['vpath']?>forgot-pass">Forgot your password?</a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12">
                                <p>Haven't an account? <a href="<?=$configs['vpath']?>subscribe">Subscribe or Register now.</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Include Font Awesome CDN in <head> if not already included -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script>
  function togglePassword() {
    const passwordField = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      passwordField.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }
</script>