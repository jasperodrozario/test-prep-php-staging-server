<?php 
include 'includes/header.php';
?>
<!--banner section-->
<?php 
include 'includes/slider.php';
?>
<!--welcome section-->
<div class="welcome_wrapper">
  <div class="container">
          <div class="welcome_left">
              <h1>How we can help </h1>
                <?
                $d=getDataByWhere("contents","contents","cont_title='How we can help'");
                
                echo html_entity_decode($d['contents']);
                ?>
                
                </div>
          <div class="welcome_center">
              <div class="img_top">
                  <img src="images/Icon/pic_2.png" alt="images" class="img-responsive">
                </div>
                <div class="img_bottom">
                  <img src="images/Icon/pic_1.png" alt="images" class="img-responsive">
                </div>
                <div class="clearfix"></div>
            </div>
          <div class="welcome_right">
              <div class="content_top">
                  <img src="images/Icon/welcome.jpg" alt="images" class="img-responsive">
                </div>
                <div class="content_bottom">
                  <h2>Why Us</h2>
                  <?
               	 	$d=getDataByWhere("contents","contents","cont_title='Why Us'");
                
                	echo html_entity_decode($d['contents']);
                	?>
                   </div>
                <div class="clearfix"></div>
            </div>
        <div class="clearfix"></div>
    </div>
</div>
  
<div class="course">
 <div class="container">
    <div class="row">
    <h1 class="sec-heading">OUR POPULAR COURSES</h1>
        <div class="col-md-12">
            <div id="news-slider" class="owl-carousel">
                <?
                $data=getQuery("course","course_id,thumb_pic,name,details,cost,cat_id,duration","featured='Y'","course_id desc","12");
 
          		for($i=0;$i<count($data);$i++)
          		{
                ?>
	                <div class="post-slide">
                    <div class="news-slider-con">
	                    <div class="post-img">
	                        <img src="image.php?image=<?=$vpath.$data[$i]['thumb_pic']?>&width=360&height=270&cropratio=2:1" alt="<?=$data[$i]['name']?>" class="img-responsive">
	                        <div class="over-layer">
	                            <ul class="post-link">
	                                <li><a href="<?=$vpath?>view_video.php?id=<?php echo $data[$i]['course_id'];?>" class="fa fa-video-camera" data-toggle="modal" data-target="#myModal-video" title="course demo video"></a></li>
	                                <li><a href="<?=$vpath;?>course/<?php echo $data[$i]['course_id']. "/" . seo_url($data[$i]['name']); ?>" class="fa fa-link"></a></li>
                                    
	                            </ul>
	                        </div>
	                    </div>
	                    <div class="post-review">
	                        <h3 class="post-title"><a href="<?=$vpath;?>course/<?php echo $data[$i]['course_id']. "/" . seo_url($data[$i]['name']); ?>"><?=$data[$i]['name']?></a></h3>
	                        
	                       <div class="post-info">
	                          <ul class="review-stars">
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star-half-o" aria-hidden="true"></i></li>
	                          </ul>
	                          <span class="course-rating-total"> 2 Reviews</span>
	                       </div>
	                        
	                        <p class="post-description">
	                            <?=substr(strip_tags(html_entity_decode($data[$i]['details'])),0,170);?>...
	                        </p>
	                        <a href="<?=$vpath;?>course/<?php echo $data[$i]['course_id']. "/" . seo_url($data[$i]['name']); ?>" class="read-more">read more</a>
	                    </div>
                        </div>
                        <div class="news-slider-con">
                         <div class="post-img">
	                        <img src="image.php?image=<?=$vpath.$data[$i]['thumb_pic']?>&width=360&height=270&cropratio=2:1" alt="<?=$data[$i]['name']?>" class="img-responsive">
	                        <div class="over-layer">
	                            <ul class="post-link">
	                                <li><a href="<?=$vpath?>view_video.php?id=<?php echo $data[$i]['course_id'];?>" class="fa fa-video-camera" title="course demo video" ></a></li>
	                                <li><a href="<?=$vpath;?>course/<?php echo $data[$i]['course_id']. "/" . seo_url($data[$i]['name']); ?>" class="fa fa-link"></a></li>
	                            </ul>
	                        </div>
	                    </div>
	                    <div class="post-review">
	                        <h3 class="post-title"><a href="<?=$vpath;?>course/<?php echo $data[$i]['course_id']. "/" . seo_url($data[$i]['name']); ?>"><?=$data[$i]['name']?></a></h3>
	                        
	                       <div class="post-info">
	                          <ul class="review-stars">
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
	                            <li><i class="fa fa-star-half-o" aria-hidden="true"></i></li>
	                          </ul>
	                          <span class="course-rating-total"> 2 Reviews</span>
	                       </div>
	                        
	                        <p class="post-description">
	                            <?=substr(strip_tags(html_entity_decode($data[$i]['details'])),0,170);?>...
	                        </p>
	                        <a href="<?=$vpath;?>course/<?php echo $data[$i]['course_id']. "/" . seo_url($data[$i]['name']); ?>" class="read-more">read more</a>
	                    </div>
	                </div>
                    </div>
                   
                    
                <?
                }
                ?>
                
            </div>
        </div>
    </div>
</div>
</div>
<!--auto slider section-->
<div class="slider_section">
  <div class="container">
    <h1 class="sec-heading">Upcoming Information</h1>
      <ul id="flexiselDemo3">
            <li>
              <div class="why_box">
                      <div class="box_top">
                          <div class="top_left">
                              <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="top_right">
                              <h6>Nam aliquam pretium feugiat Duis sem est viverra eu interdum ac suscipit nec </h6>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="box_bottom">
                          <p>Duis sem est, viverra eu interdum ac, suscipit nec mauris. Suspendisse commodo tempor sagittis! In justo est, sollicitudin eu scelerisque pretium, placerat eget elit. Praesent faucibus rutrum odio at rhoncus. </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
            </li>
            <li>
              <div class="why_box">
                      <div class="box_top">
                          <div class="top_left">
                              <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="top_right">
                              <h6>Nam aliquam pretium feugiat Duis sem est viverra eu interdum ac suscipit nec </h6>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="box_bottom">
                          <p>Duis sem est, viverra eu interdum ac, suscipit nec mauris. Suspendisse commodo tempor sagittis! In justo est, sollicitudin eu scelerisque pretium, placerat eget elit. Praesent faucibus rutrum odio at rhoncus. </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
            </li>
            <li>
              <div class="why_box">
                      <div class="box_top">
                          <div class="top_left">
                              <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="top_right">
                              <h6>Nam aliquam pretium feugiat Duis sem est viverra eu interdum ac suscipit nec </h6>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="box_bottom">
                          <p>Duis sem est, viverra eu interdum ac, suscipit nec mauris. Suspendisse commodo tempor sagittis! In justo est, sollicitudin eu scelerisque pretium, placerat eget elit. Praesent faucibus rutrum odio at rhoncus. </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
            </li>
            <li>
            <div class="why_box">
                      <div class="box_top">
                          <div class="top_left">
                              <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="top_right">
                              <h6>Nam aliquam pretium feugiat Duis sem est viverra eu interdum ac suscipit nec </h6>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="box_bottom">
                          <p>Duis sem est, viverra eu interdum ac, suscipit nec mauris. Suspendisse commodo tempor sagittis! In justo est, sollicitudin eu scelerisque pretium, placerat eget elit. Praesent faucibus rutrum odio at rhoncus. </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
            </li>                                                 
        </ul><!--flexiselDemo3--> 
    </div>
</div>

<!------video modal------->

<div class="modal fade" id="myModal-video" role="dialog">
    <div class="modal-dialog">
    <div class="modal-header">
          <button type="button" id="stop" class="close" data-dismiss="modal">&times;</button>
          
        </div>
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body raja">
        <!--- load video from external url---->
        </div>
        
      </div>
      </div>
      </div>

<?php 
include 'includes/footer.php';
?>