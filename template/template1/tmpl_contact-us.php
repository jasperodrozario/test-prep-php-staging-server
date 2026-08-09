<main>
<div class="page-title-area bg-5 ">
    <div class="container">
        <div class="page-title-content">
            <h2>Contact Us</h2>

        </div>
    </div>
</div>


<section class="contact-info-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="single-contact-info">
                    <i class="flaticon-call"></i>
                    <h3>Call us</h3>
                    <a href="tel:+17182006396">+1 (718)-200-6396</a>

                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="single-contact-info">
                    <i class="flaticon-pin"></i>
                    <h3>Our location</h3>
                    <a href="#">United States</a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 offset-sm-3 offset-lg-0">
                <div class="single-contact-info">
                    <i class="flaticon-email"></i>
                    <h3>Email</h3><a href="mailto:info@asvab-tutoring.com">info@asvab-tutoring.com</span></a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="main-contact-area pb-100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="contact-wrap contact-pages mb-0">
                    <div class="contact-form">
                        <div class="section-title">
                            <h2>Drop us a message for any query</h2>
                            <p>For more information about our courses, get in touch <br> with +1 (718)-200-6396</p>
                        </div>
                        <div id='contact_response'></div>
                        <form class="form-contact" id='cform' method="POST">
                            <input type='hidden' name='action' value='contact'>
                            <div class="row">

                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" required data-error="Please enter your First Name *" requied value=<?= $_SESSION['first_name']; ?>>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" required data-error="Please enter your Last Name *" requied value=<?= $_SESSION['last_name']; ?>>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="email" id="email" class="form-control" required data-error="Please enter your email *" requied value=<?= $_SESSION['email']; ?>>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" required data-error="Please enter your contact number *" requied value=<?= $_SESSION['mobile']; ?>>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Course</label>
                                        <select name='test_id' class='form-control' size=1 required="">
                                            <option value=''>Select Course</option>
                                            <?
                                            $courses = getQuery("course", "*", array("is_online" => "Y"));
                                            if ($_SESSION['test_id']) {
                                                $disable = "disabled";
                                            } else {
                                                $disable = "";
                                            }
                                            foreach ($courses as $course) {
                                                if ($_SESSION['test_id'] == $course['course_id']) {
                                                    echo "<option value='" . $course['course_id'] . "' selected>" . $course['course_name'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $course['course_id'] . "' " . $disable . ">" . $course['course_name'] . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <br>
                                        <label>Subject</label>
                                        <input type="text" name="subject" id="subject" class="form-control" required data-error="Please enter your subject *" requied>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea name="message" class="form-control" id="message" cols="30" rows="5" required data-error="Write your message *" required></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <button type="submit" class="default-btn btn-two">
                                        Send Message
                                    </button>
                                    <div id="msgSubmit" class="h3 text-center hidden"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $("#cform").submit(function(event) {

        event.preventDefault();
        $('#contact_response').html("<img src='<?= $configs['vpath'] ?>img/loaderIcon.gif'>");
        $.ajax({

                type: "POST",
                dataType: "json",
                url: "<?= $configs['vpath'] ?>ajax-home",
                data: new FormData(this),
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false
            })
            .done(function(data) {

                //alert(data);
                $("#contact_response").html(data.msg);


                $('#cform').attr("style", "display:none");


            })
            .fail(function(jqXHR, textStatus, errorThrown) {

                // just in case posting your form failed
                alert(textStatus);

            });

        // to prevent refreshing the whole page page
        return false;

    });
</script>