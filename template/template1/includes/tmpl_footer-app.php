<?
if (!$_GET['referral_id']) { ?>
    <!-- <footer class="footer-top-area pt-50 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="footer-widget">
                        <h3>Find Us</h3>
                        <ul class="address">
                            <li>
                                <i class="bx bxs-message"></i>
                                <a href="<?= $configs['vpath'] ?>contact-us">Contact Us</a>
                            </li>
                            <li class="location">
                                <i class="bx bxs-location-plus"></i>
                                United States
                            </li>
                            <li>
                                <i class="bx bxs-envelope"></i>
                                <a href="mailto:info@asvab-tutoring.com">info@asvab-tutoring.com</span></a>
                            </li>
                            <li>
                                <i class="bx bxs-phone-call"></i>
                                <a href="tel:+17182006396">+1 (718)-200-6396</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="footer-widget">
                        <h3>About Us</h3>
                        <ul class="link">
                            <li><a href="<?= $configs['vpath'] ?>about">About us</a></li>
                            <li><a href="<?= $configs['vpath'] ?>info/terms-and-conditions">Terms & Conditions</a></li>
                            <li><a href="<?= $configs['vpath'] ?>info/privacy-policy">Privacy Policy</a></li>
                            <li><a href="<?= $configs['vpath'] ?>sitemap">Sitemap</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="footer-widget">
                        <h3>Affiliate</h3>
                        <ul class="link">
                            <?php
                            //            if(!$_SESSION['user_id']){
                            ?>
                            <li>
                                <a href="<?= $configs['vpath'] ?>affiliate-login">Affiliate Login</a>
                            </li>
                            <?
                            //            }
                            ?>
                            <li>
                                <a href="<?= $configs['vpath'] ?>become-an-affiliate">Become an Affiliate</a>
                            </li>
                            <li> <a href="<?= $configs['vpath'] ?>tutor-register">Tutor Register</a> </li>

                        </ul>
                    </div>
                </div>


                <div class="col-lg-3 col-sm-6">
                    <div class="footer-widget">
                        <h3>Follow Us</h3>
                        <ul class="link">
                            <li>
                                <a href="https://www.facebook.com/asvabtutoring" target=_new><img src='<?= $configs['vpath'] ?>img/facebook.png' width=45></a>

                                <a href="https://twitter.com/asvabtutoring18" target=_new><img src='<?= $configs['vpath'] ?>img/twitter.png' width=38></a>
                                <a href="https://www.instagram.com/asvabtutoring/" target=_new><img src='<?= $configs['vpath'] ?>img/instagram.png' width=38></a>

                                <a href="https://www.youtube.com/channel/UCCVP6UtL3O2-gQJa9KwRMXw" target=_new><img src='<?= $configs['vpath'] ?>img/youtube.png' width=38></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer> -->
<? } ?>

<footer class="footer-bottom-area">
    <div class="container">
        <div class="copyright-wrap">
            <p>
                Copyright <i class="bx bx-copyright"></i><?= date("Y") ?> <?= $configs['dotcom'] ?>.</p>
        </div>
    </div>
</footer>


<div class="go-top">
    <i class='bx bx-chevrons-up'></i>
    <i class='bx bx-chevrons-up'></i>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

<script src="<?= $configs['template_path'] ?>assets/js/bootstrap.bundle.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/meanmenu.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/owl.carousel.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/wow.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/nice-select.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/magnific-popup.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/jarallax.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/appear.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/odometer.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/form-validator.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/contact-form-script.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/ajaxchimp.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/custom.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="<?= $configs['template_path'] ?>assets/js/jquery.min.js"></script>

<script src="<?= $configs['vpath'] ?>template/js.js"></script>


</body>

</html>

<script>
    $(document).ready(function() {

        //    $("#test_id").change(function()
        //    {
        //
        //        var id=$(this).val();
        //        console.log('New verification code: Im here ',id);
        //        //var dataString = 'parent_id='+ id;
        //        $("#subscription").find('option').remove();
        //
        //        $.ajax
        //        ({
        //            type: "POST",
        //            dataType : "json",
        //            url: "<?= $configs['vpath'] ?>/ajax-home",
        //            data: "course_id="+id+"&action=get_subcription_option" 
        //        })
        //        .done(function(html)
        //        {
        //            console.log('New verification code: Im here');
        //            $("#subscription").html(html.list);
        //        }) 
        //    });


        $('#loginForm').submit(function() {

            // show that something is loading
            $('#login-response').html("<b>Loading response...</b>");

            $.ajax({
                    dataType: "json",
                    type: 'POST',
                    url: '<?= $configs['vpath'] ?>ajax-home',
                    data: $(this).serialize()
                })
                .done(function(data) {

                    $('#login-response').html(data.msg);

                    $('#list').html(data.list);

                    if (data.url) {
                        window.location = data.url;

                    }


                })
                .fail(function() {

                    // just in case posting your form failed
                    alert("Posting failed.");

                });

            // to prevent refreshing the whole page page
            return false;

        });

        $('#forgotForm11').submit(function() {

            $('#responseforgot').html("<b>Loading response...</b>");


            $.ajax({
                    dataType: "json",
                    type: 'POST',
                    url: vpath + 'ajax-home',
                    data: $(this).serialize()
                })
                .done(function(data) {


                    if (data.error) {
                        $('#responseforgot').html("");
                        $('#response-error').html(data.error);
                        $('#response-success').html("");
                    }
                    if (data.success) {
                        $('#response-success').show();
                        $('#response-success').html(data.success);
                        $('#response-error').html("");
                        $('#responseforgot').html("");
                        $('#forgotForm11').hide();
                    }
                    if (data.url) {
                        window.location = data.url;

                    }


                })
                .fail(function() {

                    // just in case posting your form failed
                    alert("Posting failed.");

                });

            // to prevent refreshing the whole page page
            return false;

        });

    });
</script>

<script>
    $(document).ready(function() {
        $('#form').submit(function(event) {
            event.preventDefault();
            $('#response').html("<b>Loading response...</b>");

            $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: '<?= $configs['vpath'] ?>ajax-home',
                    data: new FormData(this),
                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false
                })
                .done(function(data) {
                    //alert(data);
                    $('#response').html(data.msg);
                    $('#pending').html(data.pending);
                    $('#list').html(data.list);

                    if (data.isreset) {
                        $('#form')[0].reset();
                    }
                    if (data.error) {
                        $('#response-error').html(data.error);
                        $('#response-success').html("");
                    }
                    if (data.success) {
                        $('#response-success').html(data.success);
                        $('#response-error').html("");

                        $('#form').hide();
                    }
                    if (data.url) {
                        window.location = data.url;

                    }

                })
                .fail(function() {

                    // just in case posting your form failed
                    alert("Posting failed.");

                });

            // to prevent refreshing the whole page page
            return false;

        });

        $('#form_tutor').submit(function(event) {
            event.preventDefault();
            $('#response').html("<b>Loading response...</b>");

            $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: '<?= $configs['vpath'] ?>ajax-home',
                    data: new FormData(this),
                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false
                })
                .done(function(data) {
                    //alert(data);
                    $('#response').html(data.msg);
                    $('#pending').html(data.pending);
                    $('#list').html(data.list);

                    if (data.isreset) {
                        $('#form_tutor')[0].reset();
                    }
                    if (data.error) {
                        $('#response-error').html(data.error);
                        $('#response-success').html("");
                    }
                    if (data.success) {
                        $('#response-success').html(data.success);
                        $('#response-error').html("");

                        $('#form_tutor').hide();
                    }
                    if (data.url) {
                        window.location = data.url;

                    }

                })
                .fail(function() {

                    // just in case posting your form failed
                    alert("Posting failed.");

                });

            // to prevent refreshing the whole page page
            return false;

        });

    });
</script>

<script>
    $("#state_id").change(function() {

        var id = $(this).val();
        //var dataString = 'parent_id='+ id;
        $("#city_id").find('option').remove();

        $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?= $configs['vpath'] ?>ajax-home',
                data: "state_id=" + id + "&action=get_city_option"

            })
            .done(function(html) {
                $("#city_id").html(html.list);
            })
    });
</script>