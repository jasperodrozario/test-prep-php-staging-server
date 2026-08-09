

<main>
    <div class="page-title-area bg-2 mt-90">
        <div class="container">
            <div class="page-title-content">
                <h2>Subscribe a Course</h2>

            </div>
        </div>
    </div>
    <section class="enroll-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="enroll-wrap">
                        <form class="courses-form" method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>" id="aform">

                            <input type='hidden' name='register' value='register'>

                            <h3>Subscribe Now</h3>
                            <?php
                            /*
                                // test internal veriables
                                echo '<pre>';
                                //var_dump($course);  
                                //print_r ($course);
                                //echo "<br>";
                                //echo "Selected Course ID: " . ($course['courseId']);
                                //echo $_SERVER['REQUEST_URI'];
                                echo '</pre>';
                                */
                            ?>
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
                                    <select name="course_id" size="1" id="course_id" required>
                                        <option value="">Select Course</option>

                                        <?php foreach ($testprep_course as $c): ?>
                                            <option value="<?php echo $c['courseId']; ?>">
                                                <?php
                                                echo $c['course_name'].' - '.$c['courseId'];
                                                ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <select name="subscription_package" id="subscription_package" size="1" required>
                                        <option value="">Select Subscription</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name='first_name' id="first_name" value="<?= $_POST['first_name']; ?>" placeholder="First Name" required>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name='last_name' id="last_name" value="<?= $_POST['last_name']; ?>" placeholder="Last Name" required>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" name='email' value="<?= $_POST['email']; ?>" id="email" placeholder="Email" autocomplete="username" required>
                                </div>

                                <div class="form-group password-container">
                                    <input type="password" class="form-control" name='password' value="<?= $_POST['password']; ?>" id="password" placeholder="Password" autocomplete="new-password" required>
                                    <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                                </div>

                                <div class="form-group password-container">
                                    <input type="password" class="form-control" name='re_password' value="<?= $_POST['re_password']; ?>" id="re_password" placeholder="Repeat Password" autocomplete="new-password" required>
                                    <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" id="mobile" maxlength=16 name='mobile' value="<?= $_POST['mobile']; ?>" placeholder="Valid Mobile Number" required>
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
                            <input type="hidden" name="square_source_id" id="square_source_id" value="">
                            <!-- Square Payment Section -->
                            <div class="form-group">
                                <div class="square-card-wrap">
                                    <label class="square-card-label">Card Details</label>

                                    <div class="supported-cards">
                                        <img src="https://cdn.jsdelivr.net/npm/payment-icons/min/flat/visa.svg" alt="Visa" width="40">
                                        <img src="https://cdn.jsdelivr.net/npm/payment-icons/min/flat/mastercard.svg" alt="Mastercard" width="40">
                                        <img src="https://cdn.jsdelivr.net/npm/payment-icons/min/flat/amex.svg" alt="American Express" width="40">
                                        <img src="https://cdn.jsdelivr.net/npm/payment-icons/min/flat/discover.svg" alt="Discover" width="40">
                                        <img src="https://cdn.jsdelivr.net/npm/payment-icons/min/flat/jcb.svg" alt="JCB" width="40">
                                        <img src="https://cdn.jsdelivr.net/npm/payment-icons/min/flat/unionpay.svg" alt="UnionPay" width="40">
                                    </div>

                                    <div class="card-brand-row">
                                        <span class="card-brand-label"></span>

                                    </div>

                                    <div id="card-container"></div>
                                    <div id="card-errors" class="square-card-error" style="display:none;"></div>
                                </div>
                            </div>

                            <div class="form-group" style="text-align: left;">
                                <label>
                                    <span style="color:red; font-size: 16px;">
                                        <input type="radio" id="termsCheckbox" name="termsCheckbox">
                                        <strong> I confirm that I have read and agree to the <a href="<?= $vpath . "info/terms-and-conditions" ?>" target="_blank" style="color:red;"><u>terms and conditions.</u></a></strong>
                                    </span>
                                </label>
                            </div>

                            <button type="button" class="btn btn-outline-light btn-gradient" onclick="submitRegistrationForm()">Submit</button>
                            <br>
                            <p id="error-message" style="color:red; display:<?= !empty($error) ? 'block' : 'none'; ?>; font-size:18px;">
                                <b><?= !empty($error) ? htmlspecialchars(reset($error), ENT_QUOTES, 'UTF-8') : ''; ?></b>
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
</main>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="<?= tmpl_subscribe_h($squareSdkUrl); ?>"></script>

<!-- Square related code -->
<script>
    const SQUARE_APP_ID = <?= json_encode($squareAppId); ?>;
    const SQUARE_LOCATION_ID = <?= json_encode($squareLocationId); ?>;

    let squarePayments = null;
    let squareCard = null;
    let squareReady = false;

    function normalizeCardBrand(brand) {
        const value = String(brand || '').trim();
        if (!value) return '-';

        const map = {
            VISA: 'VISA',
            MASTERCARD: 'MASTERCARD',
            AMERICAN_EXPRESS: 'AMERICAN EXPRESS',
            AMEX: 'AMERICAN EXPRESS',
            DISCOVER: 'DISCOVER',
            DINERS_CLUB: 'DINERS CLUB',
            JCB: 'JCB',
            UNIONPAY: 'UNIONPAY',
            CHINA_UNIONPAY: 'UNIONPAY',
            SQUARE_GIFT_CARD: 'SQUARE GIFT CARD'
        };

        const upper = value.toUpperCase();
        return map[upper] || upper.replace(/_/g, ' ');
    }

    function updateCardBrandDisplay(brand) {
        const el = document.getElementById('card-brand-name');
        if (el) {
            el.textContent = normalizeCardBrand(brand);
        }
    }

    function showCardError(message) {
        const el = document.getElementById('card-errors');
        if (el) {
            el.style.display = 'block';
            el.textContent = message || 'Card form error.';
        }
    }

    function hideCardError() {
        const el = document.getElementById('card-errors');
        if (el) {
            el.style.display = 'none';
            el.textContent = '';
        }
    }

    function extractCardBrandFromEvent(event) {
        if (!event) return '';
        if (event.cardBrand) return event.cardBrand;
        if (event.detail && event.detail.cardBrand) return event.detail.cardBrand;
        if (event.currentState && event.currentState.cardBrand) return event.currentState.cardBrand;
        return '';
    }

    function setSquareSourceId(token) {
        const hiddenField = document.getElementById('square_source_id');
        if (hiddenField) {
            hiddenField.value = token || '';
        }
    }

    async function initializeSquareCard() {
        const appId = String(SQUARE_APP_ID || '').trim();
        const locationId = String(SQUARE_LOCATION_ID || '').trim();

        updateCardBrandDisplay('');
        setSquareSourceId('');

        if (!appId || !locationId) {
            showCardError('Square payment is not configured.');
            return;
        }

        if (!window.Square || typeof window.Square.payments !== 'function') {
            showCardError('Square SDK not loaded.');
            return;
        }

        try {
            squarePayments = window.Square.payments(appId, locationId);

            squareCard = await squarePayments.card({
                style: {
                    input: {
                        fontSize: '16px'
                    }
                }
            });

            if (squareCard && typeof squareCard.addEventListener === 'function') {
                squareCard.addEventListener('cardBrandChanged', function(event) {
                    const brand = extractCardBrandFromEvent(event);
                    updateCardBrandDisplay(brand);
                });
            }

            await squareCard.attach('#card-container');

            squareReady = true;
            hideCardError();
        } catch (err) {
            squareReady = false;
            showCardError('Unable to load card fields.');
            console.error('Square initialize error:', err);
        }
    }

    async function tokenizeSquareCard() {
        if (!squareReady || !squareCard) {
            throw new Error('Card fields are not ready yet.');
        }

        hideCardError();
        setSquareSourceId('');

        const result = await squareCard.tokenize();

        if (result.status === 'OK' && result.token) {
            setSquareSourceId(result.token);
            console.log('square_source_id:', result.token);
            return result.token;
        }

        let msg = 'Unable to process card details.';
        if (result.errors && result.errors.length) {
            msg = result.errors.map(function(e) {
                return e.message;
            }).join(' ');
        }

        throw new Error(msg);
    }

    document.addEventListener('DOMContentLoaded', function() {
        initializeSquareCard();
    });
</script>

<script>
    // jQuery code to handle course selection and load subscriptions
    $(document).ready(function() {
        $('#course_id').on('change', function() {
            var courseId = $(this).val();
            console.log('Selected course ID:', courseId);
            var subscriptionDropdown = $('#subscription_package');

            subscriptionDropdown.html('<option value="">Select Subscription</option>');

            if (courseId !== "") {
                $.ajax({
                    url: "<?= $vpath; ?>ajax-home",
                    type: "POST",
                    dataType: "json",
                    data: {
                        action: 'get_subscriptions_testprep',
                        course_id: courseId
                    },
                    success: function(response) {

                        if (response && response.msg) {
                            subscriptionDropdown.html('<option value="">Select Subscription</option>' + response.msg);
                            subscriptionDropdown.html('<option value="">Select Subscription</option>' + response.msg);

                            subscriptionDropdown.next('.nice-select').remove();
                            subscriptionDropdown.niceSelect();

                        }
                    },

                });
            }
        });
    });
</script>

<script>
    // Function to toggle password visibility
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

<script>
    // Self-invoking function to handle form validation and submission
    (function() {
        let isSubmitting = false;

        const form = document.getElementById('aform');
        const submitBtn = document.querySelector('button[onclick="submitRegistrationForm()"]');

        const fields = {
            course_id: document.getElementById('course_id'),
            subscription_package: document.getElementById('subscription_package'),
            first_name: document.getElementById('first_name'),
            last_name: document.getElementById('last_name'),
            email: document.getElementById('email'),
            password: document.getElementById('password'),
            re_password: document.getElementById('re_password'),
            mobile: document.getElementById('mobile'),
            termsCheckbox: document.getElementById('termsCheckbox')
        };

        function trimValue(el) {
            return el ? String(el.value || '').trim() : '';
        }

        function escapeHtml(str) {
            return String(str || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function getWrapper(field) {
            return field.closest('.form-group') || field.parentNode;
        }

        function createErrorElement(field) {
            const error = document.createElement('div');
            error.className = 'field-error-msg';
            error.style.color = 'red';
            error.style.fontSize = '13px';
            error.style.marginTop = '5px';
            error.style.textAlign = 'left';
            error.style.display = 'none';

            getWrapper(field).appendChild(error);
            return error;
        }

        function getErrorElement(field) {
            const wrapper = getWrapper(field);
            let error = wrapper.querySelector('.field-error-msg');

            if (!error) {
                error = createErrorElement(field);
            }

            return error;
        }

        function showFieldError(field, message) {
            if (!field) return;

            const error = getErrorElement(field);
            error.innerHTML = message;
            error.style.display = 'block';

            if (field.type !== 'checkbox' && field.type !== 'radio') {
                field.style.borderColor = 'red';
            }
        }

        function clearFieldError(field) {
            if (!field) return;

            const wrapper = getWrapper(field);
            const error = wrapper.querySelector('.field-error-msg');

            if (error) {
                error.innerHTML = '';
                error.style.display = 'none';
            }

            if (field.type !== 'checkbox' && field.type !== 'radio') {
                field.style.borderColor = '';
            }
        }

        function clearAllErrors() {
            Object.keys(fields).forEach(function(key) {
                clearFieldError(fields[key]);
            });

            $('#response').hide();
            $('#response_msg').html('');
            hideCardError();
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);
        }

        function sanitizePhoneInput(value) {
            value = String(value || '').trim();

            // Keep only digits and plus
            value = value.replace(/[^\d+]/g, '');

            // Keep only one plus and only at the beginning
            if (value.indexOf('+') > 0) {
                value = value.replace(/\+/g, '');
            } else if ((value.match(/\+/g) || []).length > 1) {
                value = '+' + value.replace(/\+/g, '');
            }

            // Limit total digits to 15
            if (value.charAt(0) === '+') {
                value = '+' + value.substring(1).replace(/\D/g, '').slice(0, 15);
            } else {
                value = value.replace(/\D/g, '').slice(0, 15);
            }

            return value;
        }

        function normalizePhone(value) {
            return sanitizePhoneInput(value);
        }

        function isValidMobile(mobile) {
            const normalized = normalizePhone(mobile);
            return /^\+?[1-9]\d{9,14}$/.test(normalized);
        }

        function validateSingleField(field) {
            clearFieldError(field);
            const value = trimValue(field);

            if (field === fields.course_id) {
                if (value === '') showFieldError(field, 'Please select a course.');
            } else if (field === fields.subscription_package) {
                if (value === '') showFieldError(field, 'Please select a subscription.');
            } else if (field === fields.first_name) {
                if (value === '') showFieldError(field, 'First name is required.');
                else if (value.length < 2) showFieldError(field, 'First name must be at least 2 characters.');
            } else if (field === fields.last_name) {
                if (value === '') showFieldError(field, 'Last name is required.');
                else if (value.length < 2) showFieldError(field, 'Last name must be at least 2 characters.');
            } else if (field === fields.email) {
                if (value === '') showFieldError(field, 'Email is required.');
                else if (!isValidEmail(value)) showFieldError(field, 'Please enter a valid email address.');
            } else if (field === fields.password) {
                if (value === '') showFieldError(field, 'Password is required.');
                else if (value.length < 6) showFieldError(field, 'Password must be at least 6 characters.');
            } else if (field === fields.re_password) {
                if (value === '') showFieldError(field, 'Please repeat your password.');
                else if (value !== trimValue(fields.password)) showFieldError(field, 'Passwords do not match.');
            } else if (field === fields.mobile) {
                if (value === '') {
                    showFieldError(field, 'Mobile number is required.');
                } else if (!isValidMobile(value)) {
                    showFieldError(field, 'Please enter a valid mobile number.');
                }
            } else if (field === fields.termsCheckbox) {
                if (!field.checked) showFieldError(field, 'Please accept the terms and conditions.');
            }
        }

        function validateForm() {
            clearAllErrors();
            let isValid = true;

            const testId = trimValue(fields.course_id);
            const subscriptionPackage = trimValue(fields.subscription_package);
            const firstName = trimValue(fields.first_name);
            const lastName = trimValue(fields.last_name);
            const email = trimValue(fields.email);
            const password = trimValue(fields.password);
            const rePassword = trimValue(fields.re_password);
            const mobile = trimValue(fields.mobile);
            const termsChecked = fields.termsCheckbox.checked;

            if (testId === '') {
                showFieldError(fields.course_id, 'Please select a course.');
                isValid = false;
            }

            if (subscriptionPackage === '') {
                showFieldError(fields.subscription_package, 'Please select a subscription.');
                isValid = false;
            }

            if (firstName === '') {
                showFieldError(fields.first_name, 'First name is required.');
                isValid = false;
            } else if (firstName.length < 2) {
                showFieldError(fields.first_name, 'First name must be at least 2 characters.');
                isValid = false;
            }

            if (lastName === '') {
                showFieldError(fields.last_name, 'Last name is required.');
                isValid = false;
            } else if (lastName.length < 2) {
                showFieldError(fields.last_name, 'Last name must be at least 2 characters.');
                isValid = false;
            }

            if (email === '') {
                showFieldError(fields.email, 'Email is required.');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showFieldError(fields.email, 'Please enter a valid email address.');
                isValid = false;
            }

            if (password === '') {
                showFieldError(fields.password, 'Password is required.');
                isValid = false;
            } else if (password.length < 6) {
                showFieldError(fields.password, 'Password must be at least 6 characters.');
                isValid = false;
            }

            if (rePassword === '') {
                showFieldError(fields.re_password, 'Please repeat your password.');
                isValid = false;
            } else if (password !== rePassword) {
                showFieldError(fields.re_password, 'Passwords do not match.');
                isValid = false;
            }

            if (mobile === '') {
                showFieldError(fields.mobile, 'Mobile number is required.');
                isValid = false;
            } else if (!isValidMobile(mobile)) {
                showFieldError(fields.mobile, 'Please enter a valid mobile number.');
                isValid = false;
            }

            if (!termsChecked) {
                showFieldError(fields.termsCheckbox, 'Please accept the terms and conditions.');
                isValid = false;
            }

            return isValid;
        }

        function setSubmittingState(state) {
            isSubmitting = state;

            if (submitBtn) {
                submitBtn.disabled = state;
                submitBtn.innerText = state ? 'Please wait...' : 'Submit';
            }
        }

        function bindLiveValidation() {
            Object.keys(fields).forEach(function(key) {
                const field = fields[key];
                if (!field) return;

                const eventName = (field.tagName === 'SELECT' || field.type === 'checkbox') ? 'change' : 'input';

                field.addEventListener(eventName, function() {
                    if (field === fields.mobile) {
                        field.value = sanitizePhoneInput(field.value);
                    }
                    clearFieldError(field);
                });

                if (field.tagName !== 'SELECT' && field.type !== 'checkbox') {
                    field.addEventListener('blur', function() {
                        validateSingleField(field);
                    });
                }
            });
        }

        window.submitRegistrationForm = async function() {
            if (isSubmitting) return;

            const formIsValid = validateForm();
            if (!formIsValid) return;

            setSubmittingState(true);

            try {
                const token = await tokenizeSquareCard();
                console.log('square_source_id tokenized:', token);
                console.log('square_source_id field:', document.getElementById('square_source_id').value);
            } catch (err) {
                showCardError(err && err.message ? err.message : 'Unable to process card details.');
                setSubmittingState(false);
                return;
            }

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "<?= $vpath; ?>ajax-home",
                data: {
                    action: 'checkUserExist',
                    email: trimValue(fields.email)
                }
            }).done(function(data) {
                clearFieldError(fields.email);
                $('#response').hide();
                $('#response_msg').html('');

                if (data && data.flag === true) {
                    form.submit();
                    return;
                }

                if (data && data.flag === false) {
                    if (data.msg) {
                        showFieldError(fields.email, escapeHtml(data.msg));
                    } else {
                        showFieldError(fields.email, 'This email already exists.');
                    }
                    return;
                }

                showFieldError(fields.email, 'Unable to verify email right now.');
            }).fail(function() {
                clearFieldError(fields.email);
                showFieldError(fields.email, 'Unable to verify email right now.');
            }).always(function() {
                setSubmittingState(false);
            });
        };

        if (fields.mobile) {
            fields.mobile.addEventListener('keypress', function(e) {
                const charCode = e.which ? e.which : e.keyCode;
                const charStr = String.fromCharCode(charCode);

                // Allow control keys
                if (
                    charCode === 8 || // backspace
                    charCode === 9 || // tab
                    charCode === 13 || // enter
                    charCode === 27 || // escape
                    charCode === 46 // delete
                ) {
                    return;
                }

                // Allow + only at the beginning and only once
                if (charStr === '+') {
                    if (this.selectionStart !== 0 || this.value.indexOf('+') !== -1) {
                        e.preventDefault();
                    }
                    return;
                }

                // Allow digits only otherwise
                if (!/[0-9]/.test(charStr)) {
                    e.preventDefault();
                }
            });

            fields.mobile.addEventListener('paste', function() {
                const input = this;
                setTimeout(function() {
                    input.value = sanitizePhoneInput(input.value);
                    validateSingleField(input);
                }, 0);
            });
        }

        bindLiveValidation();
    })();
</script>