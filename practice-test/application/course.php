<?php 
include 'includes/header.php';
$total=getTotalRow("course",array("cat_id"=>$_REQUEST['cat_id']));

if($total)
{
    $courses=getQuery("course","*",array("cat_id"=>$_REQUEST['cat_id']),"course_id desc");
}  
else
{
    $courses=getQuery("course","*","","course_id desc","12");
}  

$category=getFldValueById("category","cat_name",array("cat_id"=>$_REQUEST['cat_id']));
?>  

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Courses of <?=$category;?></h1></div>
          </div>
</div>  

<!--courses section-->
  <div class="courses_wrapper">
      <div class="container">
        
          <div class="courses_wrap">
          <?
          if(!$total)
          {
            echo "<p style='text-aligh:center'><h3 style='text-align:center !important'>No course has been uploaded yet under $category !</h3></p>";

           echo"<hr><div style='float-left;padding-bottom:20px'><h2>You can check our other courses</h2></div>";

      
          }  
         

          foreach($courses as $d)
          {
              $category=getFldValueById("category","cat_name",array("cat_id"=>$d['cat_id']));
                
              ?>
              <div class="col-sm-4 single-courses-box cust-course-box">
                   
              <div class="single-courses">
             
                <figure>
                  <div class="figure-img"><img src="<?php echo $configs['vpath'];?>image.php?src=<?php echo $configs['vpath'].$d['thumb_pic'];?>&w=350&h=197" alt="<?php echo $d['name']; ?>" class="img-responsive">
                  </div>
                  <figcaption>
                    <div><a href="<?=$configs['vpath'];?>course-info/<?php echo $d['course_id']. "/" . seo_url($d['name']); ?>" class="read_more-btn">Apply Now</a></div>
                  </figcaption>
                </figure>
                <div class="courses-content-box">
                    <div class="courses-content">
                      <h3><a href="<?=$configs['vpath'];?>course-info/<?php echo $d['course_id']. "/" . seo_url($d['name']); ?>"> <?php echo $d['name']; ?></a></h3>
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
                        <li><a href="#"><i class="fa fa-heart"></i> 35</a></li>
                        <li><span><?=$setting['currency']?><?php echo $d['cost']; ?></span></li>
                      </ul>
                    </div>
                </div>
 
            </div>

            </div>
          <?php 
          } ?>
  
         </div>
 
      </div>         
    </div>    

<!--footer section-->
<?php 
include ('includes/footer.php');
?>
