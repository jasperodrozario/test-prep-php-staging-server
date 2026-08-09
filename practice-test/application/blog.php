<?php 
include 'includes/header.php';



?>    

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Blog</h1></div>
          </div>
</div> 

<!--blog section-->
<div class="blog_wrapper">
      <div class="container">
          <div class="blog_wrap">
        <div class="col-lg-9 col-sm-8">



                
                  <div class="post">

                    <?php

                    $j=0;
                    $blog =mysqli_query($con,"select * from " . $prev. "bloly_message order by cnt DESC  limit 6");
                  
                    while($d=@mysqli_fetch_array($blog))
                    {

                    ?>
                   
                        <h2><a href="post.php"><?php echo $d['header']; ?></a></h2>
                        <p class="name">By John Slim</p>
                        <hr>
                        <p class="date-comments">
                            <span><i class="fa fa-calendar-o"></i> <?php echo $d['t']; ?><!-- June 20, 2013 --></span>
                            <span><i class="fa fa-comment-o"></i> 8 Comments</span>
                        </p>
                        <div class="image">
                            <a href="post.php">
                                <img alt="Example blog post alt" class="img-responsive" src="<?php echo $vpath; ?><?php echo $d['blog_photo']; ?>">
                            </a>
                        </div>
                        <p><?php echo html_entity_decode($d['body']); ?></p>
                        <a href="post.php"><button class="btn btn-primary pull-right" type="button">Continue reading<i class="fa fa-chevron-right"></i></button> </a>   


                    <?php } ?>



                    </div>


                   
                  
                  
                   
                   
                  
                    <ul class="pager">
                        <li class="previous"><a href="#">← Older</a>
                        </li>
                        <li class="next"><a href="#">Newer →</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-4">
                    <div class="categories">
                      <h3>Categories</h3>
                        <ul>
                          <li><a href="#">Dining room design</a></li>
                            <li><a href="#">Modern Interior Design</a></li>
                            <li><a href="#">Rest room design</a></li>
                            <li><a href="#">Kitchen interia design</a></li>
                            <li><a href="#">Bed room design</a></li>
                        </ul>
                    </div>
                    <div class="recent_post">
                      <h3>Recent Posts</h3>
                        <div class="post_box">
                            <h6>Hivamus at magnate </h6>
                            <img src="images/blog/p1.jpg" alt="follow" class="img-responsive">   
                            <p class="tags">
                                <span><i class="fa fa-calendar-o"></i> June 20, 2013</span>
                                <span><i class="fa fa-comment-o"></i> 8 Comments</span>
                            </p>                   
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.</p>
                      </div>
                        <div class="post_box">
                            <h6>Hivamus at magnate </h6>
                            <img src="images/blog/p2.jpg" alt="follow" class="img-responsive">   
                            <p class="tags">
                                <span><i class="fa fa-calendar-o"></i> June 20, 2013</span>
                                <span><i class="fa fa-comment-o"></i> 8 Comments</span>
                            </p>                   
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.</p>
                      </div>
                        <div class="post_box">
                            <h6>Hivamus at magnate </h6>
                            <img src="images/blog/p3.jpg" alt="follow" class="img-responsive">   
                            <p class="tags">
                                <span><i class="fa fa-calendar-o"></i> June 20, 2013</span>
                                <span><i class="fa fa-comment-o"></i> 8 Comments</span>
                            </p>                   
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.</p>
                      </div>
                        <div class="post_box">
                            <h6>Hivamus at magnate </h6>
                            <img src="images/blog/p4.jpg" alt="follow" class="img-responsive">   
                            <p class="tags">
                                <span><i class="fa fa-calendar-o"></i> June 20, 2013</span>
                                <span><i class="fa fa-comment-o"></i> 8 Comments</span>
                            </p>                   
                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.</p>
                      </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="comment">
                      <h3>Recent Comment</h3>
            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>                      
                    </div>
                    <div class="clearfix"></div>                    
                </div>                      
                <div class="clearfix"></div>
            </div>
        </div>
    </div>      


<?php 
include 'includes/footer.php';
?> 