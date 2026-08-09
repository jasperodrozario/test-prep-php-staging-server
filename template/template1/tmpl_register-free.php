<main>

    <div class="page-title-area bg-2">
        <div class="container">
            <div class="page-title-content">
                <h2>REGISTER FOR HOMEWORK AND PRACTICE TEST</h2>
            </div>
        </div>
    </div>
    <section class="enroll-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="enroll-wrap">
                        <div id='loaderIcon'><img src='<?= $configs['vpath'] . "img/Spinner-2.gif"; ?>' style="display: none;text-align:center"></div>

                        <form class="courses-form" method='post' action='<?= $configs['vpath'] ?>register-free' id='aform'>

                            <input type='hidden' name='register' value='register'>

                            <h3>Register Now</h3>

                            <!-- start warning msg -->
                            <div id='response' style="display: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </symbol>
                                    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                    </symbol>
                                    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </symbol>
                                </svg>

                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                                        <use xlink:href="#exclamation-triangle-fill" />
                                    </svg>
                                    <div id='response_msg'>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <select name='test_id' size=1 required id='test_id'>
                                        <option value="">Select Course</option>
                                        <?php
                                        foreach ($courses_list as $c) {
                                            $selected = ($_GET['seo_url'] == strtolower(str_replace(' ', '-', $c['course_name'])) || $_POST['test_id'] == $c['course_id']) ? 'selected' : '';
                                            echo "<option value='" . $c["course_id"] . "' $selected>" . $c['course_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name='first_name' id="first_name" value="<?= $_POST['first_name']; ?>" placeholder="First Name" required>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name='last_name' id="last_name" value="<?= $_POST['last_name']; ?>" placeholder="Last Name" required>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" name='email' value="<?= $_POST['email']; ?>" id="email" placeholder="Email" required>
                                </div>

                                <div class="form-group password-container">
                                    <input type="password" class="form-control" name='password' value="<?= $_POST['password']; ?>" id="password" placeholder="Password" required>
                                    <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                                </div>

                                <div class="form-group password-container">
                                    <input type="password" class="form-control" name='re_password' value="<?= $_POST['re_password']; ?>" id="re_password" placeholder="Repeat Password" required>
                                    <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" id="mobile" maxlength=10 name='mobile' value="<?= $_POST['mobile']; ?>" placeholder="Valid Mobile Number" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <? if ($_GET['referral_id']) {
                                    $_POST['referral_id'] = $_GET['referral_id'];
                                } ?>

                                <input type="hidden" class="form-control" id="referral_id" name='referral_id' value="<? if ($_POST['referral_id']) {
                                    echo $_POST['referral_id'];
                                 } ?>" placeholder=" ">

                            </div>

                            <div class="form-group" style="text-align: left;">
                                <label>
                                    <span style="color:red; font-size: 16px;">
                                        <input type="radio" id="termsCheckbox" name="termsCheckbox">
                                        <strong> I confirm that I have read and agree to the <a href="<?= $configs['vpath'] . "info/terms-and-conditions" ?>" target="_blank" style="color:red;"><u>terms and conditions.</u></a></strong>
                                    </span>
                                </label>
                            </div>

                            <button type="button" class="btn btn-outline-light btn-gradient " onclick="showVerificationModal()">Submit</button>
                            <br>
                            <p id="error-message" style="color:red; display:none; font-size:14px;">
                                <b>Please accept terms to register.</b>
                            </p>

                        </form>
                    </div>
                </div>
                <div class="col-lg-7">

                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-tutor single-tutor-reg one">
                                <i class="flaticon-instructor-1"></i>
                                <h3>5* Reviews</h3>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-tutor single-tutor-reg two">
                                <i class="flaticon-instructor"></i>
                                <h3>High Success Rate</h3>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-tutor single-tutor-reg three">
                                <i class="flaticon-certificate-1"></i>
                                <h3>Unlimited Prediction Test</h3>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-tutor single-tutor-reg four">
                                <i class="flaticon-consulting"></i>
                                <h3>Progress Tracker </h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <div class='clearfix'></div>

    <!-- Add a hidden modal for verification code -->
    <div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter The Verification Code Send To Your Email</h5>
                    <button type="button" class="close" onclick="closeVerificationModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="verificationError" class="text-danger"></div>
                    <input type="text" class="form-control" id="verificationCodeInput" placeholder="Enter the code">
                    <div id="resendTimer" class="text-secondary"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="resendButton" onclick="resendCode()" disabled>Resend</button>
                    <button type="button" class="btn btn-warning" onclick="verifyCode()">Verify</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        $('#group_id').on('change', function() {
            console.log("jQuery is working!"); // Check if jQuery runs
            console.log($("#group_id").length);

            var groupId = $(this).val();
            alert("Selected Group ID: " + groupId); // Debug alert

            if (groupId !== "") {
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: "<?= $configs['vpath']; ?>ajax-home",
                    data: {
                        action: "get_course_option",
                        groupId: groupId
                    },
                    success: function(response) {
                        if (response.msg) {
                            $('#test_id').html(response.msg);
                        } else {
                            $('#test_id').html('<option value="">No courses available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX Error:", error);
                        alert('Failed to fetch courses. Please try again.');
                    }
                });
            } else {
                $('#test_id').html('<option value="">Select Course</option>');
            }
        });
    });
</script>

<script>
    var resendTimerSeconds = 10; // 1 minute
    var resendTimerInterval;
    var new_code = generateNewCode();
    //    function showVerificationModal() {
    //        $('#verificationModal').modal('show');
    //        updateResendTimerDisplay(); // Initialize the timer display
    //        startResendTimer();
    //    }

    function showVerificationModal() {

        // Make an AJAX request to update the server-side session variable
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?= $configs['vpath']; ?>ajax-home",
            data: {
                action: 'checkUserRegInput',
                course: $('#test_id').val(),
                fname: $('#first_name').val(),
                lname: $('#last_name').val(),
                email: $('#email').val(),
                mobile: $('#mobile').val(),
                password: $('#password').val(),
                re_password: $('#re_password').val(),
                var_code: new_code
            }
        }).done(function(data) {
            // Display the response message
            //            console.log('Server response:', data);
            if (data.flag == true && !$('#termsCheckbox').is(':checked')) {
                $('#response').show();
                $("#response_msg").html('Please accept the <a href="https://staging-test-prep.asvab-tutoring.com/info/terms-and-conditions" target="_blank" style="text-decoration: underline;">Terms and Conditions</a>.');
                $('#error-message').show();
                $('html, body').animate({ scrollTop: 0 }, 'fast'); // Scroll to top
            } 
            else if (data.flag == true) {
                // $('#verificationModal').modal('show');
                // updateResendTimerDisplay();
                // startResendTimer();
                $('#error-message').hide();
                $('#aform').submit();
            } 
            else {
                $('#response').show();
                $("#response_msg").html(data.msg);
                $('#error-message').hide();
                $('html, body').animate({ scrollTop: 0 }, 'fast'); // Scroll to top
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // Log any errors to the console
            alert("Posting failed.");
            console.error("AJAX Error: " + textStatus, errorThrown);
            console.log(jqXHR.responseText); // Log the server response
        });
    }

    function closeVerificationModal() {
        stopResendTimer();
        $('#verificationModal').modal('hide');
    }

    function resendCode() {
        var newVerificationCode = generateNewCode();
        //        console.log('New verification code:', newVerificationCode);

        // Update the timer and disable the Resend button for 2 minutes
        resendTimerSeconds = 3;
        $('#resendButton').attr('disabled', true);
        startResendTimer();

        // Make an AJAX request to update the server-side session variable
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?= $configs['vpath']; ?>ajax-home",
            data: "action=resendVarEmail&email=" + $('#email').val() + "&new_code=" + newVerificationCode
        }).done(function(data) {
            // Display the response message
            //            console.log('Server response:', data);
            $("#verificationError").html(data.msg);
            new_code = newVerificationCode;

            // Refresh the page after a short delay (adjust the delay as needed)
            // setTimeout(function() {
            //     location.reload();
            // }, 2000); // Example: Refresh after 2 seconds
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // Log any errors to the console
            alert("Posting failed.");
            console.error("AJAX Error: " + textStatus, errorThrown);
            console.log(jqXHR.responseText); // Log the server response
        });
    }

    function verifyCode() {
        var userEnteredCode = $('#verificationCodeInput').val();

        if (userEnteredCode === new_code) {
            // Code is correct, close the modal and execute the remaining code or submit the form
            $('#verificationModal').modal('hide');
            //            $('#verificationError').html('Hurray!!');
            $('#aform').submit();
        } else {
            // Display an error message inside the modal
            $('#verificationError').html('Incorrect verification code! Please try again.');
        }
    }

    function startResendTimer() {
        resendTimerInterval = setInterval(function() {
            updateResendTimer();
            if (resendTimerSeconds <= 0) {
                stopResendTimer();
                $('#resendButton').attr('disabled', false);
            }
        }, 1000);
    }

    function stopResendTimer() {
        clearInterval(resendTimerInterval);
    }

    function updateResendTimer() {
        var minutes = Math.floor(resendTimerSeconds / 60);
        var seconds = resendTimerSeconds % 60;
        $('#resendTimer').text('Resend in ' + minutes + 'm ' + seconds + 's');
        resendTimerSeconds--;
    }

    function updateResendTimerDisplay() {
        var minutes = Math.floor(resendTimerSeconds / 60);
        var seconds = resendTimerSeconds % 60;
        $('#resendTimer').text('Resend in ' + minutes + 'm ' + seconds + 's');
    }

    function generateNewCode() {
        // Generate a random 4-digit code
        return Math.floor(1000 + Math.random() * 9000).toString();
    }
</script>

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