<?php 
include 'includes/header.php';
$total=getTotalRow("course");
$cond=""; $category="";
if($_REQUEST['cat_id'])
{
	$cond=" where status='Y' and cat_id=" .$_REQUEST['cat_id'];
	$category=" Under " .getFldValueById("category","cat_id=".$_REQUEST['cat_id']);
}
$data=getQuery("course","course_id,thumb_pic,name,cost,cat_id,duration",$cond,"limit 20");
?>  

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Courses .<?=$category;?> </h1></div>
          </div>
</div>  

<!--courses section-->
  <div class="courses_wrapper">
      <div class="container">
        
          <div class="courses_wrap">
          <?
          for($i=0;$i<count($data);$i++)
          {
              $category=getFldValueById("category","cat_name","cat_id=" . $data['cat_id'][$i]);
                
              ?>
              <div class="col-sm-4 single-courses-box">
                   
              <div class="single-courses">
             
                <figure>
                  <div class="figure-img"><img src="image.php?image=<?php echo $vpath; ?><?php echo $data['thumb_pic'][$i]; ?>&width=350&height=197&cropratio=2:1" alt="" class="img-responsive">
                  </div>
                  <figcaption>
                    <div><a href="<?=$vpath;?>course/<?php echo $data['course_id'][$i]. "/" . seo_url($data['name'][$i]); ?>" class="read_more-btn">Apply Now</a></div>
                  </figcaption>
                </figure>
                <div class="courses-content-box">
                    <div class="courses-content">
                      <h3><a href="<?=$vpath;?>course/<?php echo $data['course_id'][$i]. "/" . seo_url($data['name'][$i]); ?>"> <?php echo $data['name'][$i]; ?></a></h3>
                      <div class="rank-icons">
                        <ul class="list-unstyled">
                          <li><i class="fa fa-star"></i></li>
                          <li><i class="fa fa-star"></i></li>
                          <li><i class="fa fa-star"></i></li>
                          <li><i class="fa fa-star"></i></li>
                          <li><i class="fa fa-star"></i></li>                 
                        </ul>
                        <span>20 Reviews</span>
                      </div>  
                      
                    </div>
                    <div class="courses-content-bottom">
                      <ul class="list-unstyled">
                        <li><i class="fa fa-user"></i> 80</li>
                        <li><i class="fa fa-heart"></i> 35</li>
                        <li><span><?=$setting['currency']?><?php echo $data['cost'][$i]; ?></span></li>
                      </ul>
                    </div>
                </div>

            </div>

            </div>
          <?php 
          } ?>
  
         </div>

         <div class="col-md-12">
            <div class="view_more_btn">
              <a href="#" class="view-more-item">view all courses</a>
            </div>
          </div>
      </div>         
    </div>    

<!--footer section-->
<?php 
include ('includes/footer.php');
?>
