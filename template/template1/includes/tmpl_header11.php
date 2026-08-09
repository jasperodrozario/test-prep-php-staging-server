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