<main>

<div style="margin-top: 130px;"></div>

<div class="userprofile_wrapper">
    <div class="container">
        <div class="userprofile-body midle-conarea mr-t88">
            <div class="col-lg-12 col-md-12">
                <div class="panel dashbord-panel with-nav-tabs panel-default">
                    <div class="panel-heading">&nbsp; Change Password</div>
                    <div class="panel-body">
                        <?php
                        if ($msg) {
                            echo "<p align=center>" . $msg . "</p>";
                        }
                        ?>
                        <form class="form-horizontal" id="change_pass_form" method='post'>
                            <input type='hidden' name='action' value='change-password'>
                            <div class="form-group row">
                                <label for="old_password" class="col-md-3 col-form-label">Old Password</label>
                                <div class="col-sm-9 password-container">
                                    <input class="form-control" id="old_password" name='old_password' placeholder="Old Password" type="password" required>
                                    <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="new_password" class="col-md-3 col-form-label">New Password</label>
                                <div class="col-sm-9 password-container">
                                    <input class="form-control" id="new_password" name='new_password' placeholder="New Password" type="password" required>
                                    <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="conf_password" class="col-md-3 col-form-label">Confirm Password</label>
                                <div class="col-sm-9 password-container">
                                    <input class="form-control" name='conf_password' id="conf_password" placeholder="Confirm Password" type="password" required>
                                    <i class="fas fa-eye " onclick="togglePasswordVisibility(this)"></i>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class='col-md-3'></div>
                                <div class="col-sm-9">
                                    <button type="submit" name="SBMT" class="btn btn-warning pull-left" style="width:200px;">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<style>
    .password-container {
        position: relative;
    }

    .password-container i {
        position: absolute;
        top: 50%;
        right: 5%;
        transform: translateY(-50%);
        cursor: pointer;
    }

    #change_pass_response {
        display: none;
        padding: 10px;
        border-radius: 5px;
    }
</style>

<style>
    .userprofile_wrapper {
        margin: 20px auto;
    }

    .userprofile-body {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    button.btn {
        margin-top: 10px;
    }

    .error {
        display: block;
        margin-top: 5px;
        color: #ff0000;
    }

    .container {
        width: 100%;
        padding: 0 15px;
        margin: 0 auto;
    }

    .midle-conarea {
        margin: 0 auto;
    }

    .mr-t88 {
        margin-top: 88px;
    }

    .dashbord-panel {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 20px;
    }

    .dashbord-panel .panel-heading {
        background-color: #f5f5f5;
        padding: 10px;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .dashbord-panel .panel-body {
        padding: 20px;
    }

    .text-center {
        text-align: center;
    }

    .pull-left {
        float: left;
    }

    /* Add more styles as needed */


</style>


<script>
    function togglePasswordVisibility(eyeIcon) {
        const passwordInput = eyeIcon.previousElementSibling; // Get the input element before the clicked icon

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>
