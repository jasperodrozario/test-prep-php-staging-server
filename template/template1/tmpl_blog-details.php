<main>

    <div class="page-title-area bg-5 ">
        <div class="container">
            <div class="page-title-content">
                <h2><?= $db_value['name'] ?></h2>
                <ul>
                    <li>
                        <a href="<?= $configs['vpath'] ?>">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="<?= $configs['vpath'] ?>blog">
                            Blog
                        </a>
                    </li>

                    
                </ul>
            </div>
        </div>
    </div>


    <div class="single-blog-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="single-blog-content">
                        <div class="blog-top-content">
                            <!-- <img src="<?= $template_path ?>assets/img/single-blog/single-blog-img.jpg" alt="Image"> -->

                            <img src="<?= $configs['vpath'] ?>image.php?image=<?= $configs['vpath'] . $db_value['pic'] ?>&w=810&h=500" alt="<?= $db_value['name'] ?>">




                            <!-- <span class="research">Research</span> -->
                            <h3><?= $db_value['name'] ?></h3>
                            <ul class="post-details">
                                <li>
                                    By <a href="#"><?= $db_value['author'] ?></a>
                                </li>
                                <li>
                                    Posted on <?= showdate($db_value['date']) ?>
                                </li>
                                <li>
                                    <a href="#"><?= (int)$total_blog_review ?> Comments</a>
                                </li>
                            </ul>
                            <?= str_replace("<p", "<p align='justify' ", html_entity_decode($db_value['description'])); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $("#review_form").submit(function(event) {

        event.preventDefault();
        $('#review_response').html("<img src='<?= $configs['vpath'] ?>img/loaderIcon.gif'>");
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
                $("#review_response").html(data.msg);


                $('#review_form').attr("style", "display:none");


            })
            .fail(function(jqXHR, textStatus, errorThrown) {

                // just in case posting your form failed
                alert(textStatus);

            });

        // to prevent refreshing the whole page page
        return false;

    });
</script>