<main>
<br><br><br>
<div class="form-container">
    <h2>Tutor Register Form </h2>
    <div id="tutor-info-response"></div><br>
    <form id="tutor-info-form" method="post" enctype="multipart/form-data">
        
        <input type="hidden" name="action" value="tutor-info">

        <input type='hidden' name='register' value='register'>

        <div class="row form-group">
            <label class="col-lg-3 control-label">Email<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" id="email" name="email" type="email" placeholder="Email" required>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Password<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" name="password" id="password" type="password" placeholder="Password" required>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Repeat Password<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" name="re_password" id="re_password" type="password" placeholder="Repeat Password" required>
            </div>
        </div>

        <!-- Additional fields for tutor information -->
        <div class="row form-group">
            <label class="col-lg-3 control-label">First Name<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" name="fname" type="text" placeholder="First Name" required>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Last Name<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" name="lname" type="text" placeholder="Last Name" required>
                </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Country<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <select class="form-control" name="country_id" id="country_id" required>
                    <option value="">Select Country</option>
                    <?php foreach ($countries as $id => $name) { ?>
                        <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">State</label>
            <div class="col-lg-9">
                <input class="form-control" name="state_id" type="text" placeholder="State">
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">City<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" name="city_name" type="text" placeholder="City" required>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Phone<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" name="mobile" type="text" placeholder="Maximum 10 digits" required>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Address<span style="color: red;">*</span></label>
            <div class="col-lg-9">
                <input class="form-control" name="address" type="text" placeholder="Address" required>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">About</label>
            <div class="col-lg-9">
                <textarea class="form-control" name="about" placeholder="About"></textarea>
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Photo</label>
            <div class="col-lg-9">
                <input class="form-control" name="photo" type="file" accept=".png, .jpg, .jpeg">
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-3 control-label">Attachment</label>
            <div class="col-lg-9">
                <input class="form-control" name="attachment" type="file" accept=".doc, .pdf">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-lg-3"></div>
            <div class="col-lg-9">
                <!-- <button type="submit" name="submit_btn" class="btn btn-primary">Save changes</button> -->
                <!-- <button type="button" class="btn btn-primary" onclick="showVerificationModal()">Submit</button> -->
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                <button type="button" onclick="history.back();" class="btn btn-warning">Cancel</button>
            </div>
        </div>
    </form>
</div>

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
<!--               <button type="button" class="btn btn-secondary" onclick="closeVerificationModal()">Close</button>-->
               <button type="button" class="btn btn-warning" id="resendButton" onclick="resendCode()" disabled>Resend</button>
               <button type="button" class="btn btn-warning" onclick="verifyCode()">Verify</button>
           </div>
       </div>
   </div>
</div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    var resendTimerSeconds = 60; // 1 minute
    var resendTimerInterval;
    var new_code = generateNewCode();
    
    function showVerificationModal() {
        
        // Make an AJAX request to update the server-side session variable
        $.ajax({
            type: 'POST',
            dataType : "json",
            url: "<?=$configs['vpath'];?>ajax-home",
            data: {
                action: 'checkUserRegInput',
                email: $('#email').val(),
                mobile: $('#mobile').val(),
                password: $('#password').val(),
                re_password: $('#re_password').val(),
                var_code: new_code
            }
        }).done(function(data) {
            // Display the response message
//            console.log('Server response:', data);
            if(data.flag == true){
               $('#verificationModal').modal('show');
                updateResendTimerDisplay();
                startResendTimer();
                $('#error-message').hide();
            }
            else{
                // $('#response').show();
                $("#tutor-info-response").html(data.msg);
                // $('#error-message').hide();
                
                // Scroll to response
                $('html, body').animate({
                    scrollTop: $(".form-container").offset().top
                }, 800);
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
        resendTimerSeconds = 60;
        $('#resendButton').attr('disabled', true);
        startResendTimer();

        // Make an AJAX request to update the server-side session variable
        $.ajax({
            type: 'POST',
            dataType : "json",
            url: "<?=$configs['vpath'];?>ajax-home",
            data: "action=resendVarEmail&email="+$('#email').val()+"&new_code=" + newVerificationCode
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
            $('#tutor-info-form').submit();
        } else {
            // Display an error message inside the modal
            $('#verificationError').html('Incorrect verification code! Please try again.');
        }
    }

    function startResendTimer() {
        resendTimerInterval = setInterval(function () {
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#tutor-info-form').submit(function (event) {
            event.preventDefault();
            $('#tutor-info-response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?=$configs['vpath'];?>/ajax-home',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false
            }).done(function (response) {
                $('#tutor-info-response').html(response.msg);
                if (response.next) {
                    window.location = response.next;
                }
                $('html, body').animate({scrollTop: 0}, 1000);
            }).fail(function () {
                alert('Failed to submit form.');
            });
        });
    });
</script>


<style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .form-container {
            margin: 50px auto;
            max-width: 800px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 5px;
        }
        .btn-warning {
            border-radius: 5px;
        }

        #tutor-info-response {
            padding-left: 5px;
            background-color: #f8d7da;
            color: #721c24;
        }
</style>
