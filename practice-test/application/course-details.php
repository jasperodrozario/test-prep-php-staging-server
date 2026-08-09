<?php 
include('includes/header.php');
$data=getDataByWhere("course","*","course_id=" .$_REQUEST['course_id']);

$category=getFldValueById("category","cat_name","cat_id=" .$data['cat_id']);

$data_subject=getQuery("course_subject","*",array("course_id"=>$_REQUEST['course_id']),"display_order");

$faq=getQuery("course_faq","*",array("course_id"=>$_REQUEST['course_id']));

$announcement=getFldValueById("course_annoucement","announcement",array("course_id"=>$_REQUEST['course_id']));

$related_course=getQuery("course","course_id,thumb_pic,name,cost,cat_id,duration",array("course_id!" =>$_REQUEST['course_id'],"cat_id"=>$data['cat_id']),"course_id desc","12");

$features=getDataByWhere("course","*",array("course_id"=>$_REQUEST['course_id']));

$categories=getQuery("category","*",array("status"=>"Y","parent_id"=>0));
?>   
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner course-detls-banner">
     <!-- <image class="img-responsive" src="<?=$vpath?>images/slider-03.jpg" alt="">-->
       <div class="banner-video">
       <div class="container">
       <div class="col-md-7">
         <div class="banner-text"><h1><?=$data['name']?></h1></div>
             
                <div class="course-features-info">

                    <!--<h3 class="title"> Course Features</h3>-->
                    <ul><li class="lectures-feature"> <i class="fa fa-files-o"></i> <span class="label">Classes</span> <span class="value"><?=$features['classes'];?></span></li>
                      <li class="quizzes-feature"> <i class="fa fa-puzzle-piece"></i> <span class="label">Exams</span> <span class="value"><?=$features['exams'];?></span></li>
                      <li class="duration-feature"> <i class="fa fa-clock-o"></i> <span class="label">Duration</span> <span class="value"><?php echo $features['duration']; ?> Hours</span></li>
                      <li class="skill-feature"> <i class="fa fa-level-up"></i> <span class="label">Skill level</span> <span class="value"><?php echo html_entity_decode($features['skill']); ?></span></li>
                      <li class="language-feature"> <i class="fa fa-language"></i> <span class="label">Language</span> <span class="value"><?php echo $features['language']; ?></span></li>
                      <li class="students-feature"> <i class="fa fa-users"></i> <span class="label">Students</span> <span class="value"><?=$features['students'];?></span></li>
                      <li class="assessments-feature"> <i class="fa fa-check-square-o"></i> <span class="label">Assessments</span> <span class="value"><?php echo $features['assessment']; ?></span></li></ul>
                  </div>
              </div>
          <div class="col-md-5">
         <div class="banner-addtofav">
                <a href="#" class="addtofav" title="Add To Favorites">Gift this course <i class="fa fa-heart-o" aria-hidden="true"></i></a>
              </div>
              
              
			  
				<div class="detail-img"> 	
                <video width="400" controls>
              <?
			  if($data['video'])
			  {?>
  <source src="<?=$vpath?><?=$data['video'];?>" type="video/mp4">
  <!--<source src="mov_bbb.ogg" type="video/ogg">
  Your browser does not support HTML5 video-->.
</video>	  
              <!--<iframe id="" src="<!--?=$vpath?><?=$data['video'];?>" frameborder="0" controls="controls" preload="none"></iframe>--><!--<img src="<?php echo $vpath; ?><?php /*?><?php echo $d['main_pic']; ?><?php */?>image.php?image=<?=$vpath.$data['main_pic']?>&width=775&height=517" class="img-responsive" alt="">-->
              <!--<div class="course-seats"> <i class="fa fa-user"></i> <span>50</span></div>-->
               <div class="cust-texbtn">Preview This Test </div></div>
			  <?}?>
              </div>
              
              </div>
      </div>
  </div>
</div>  
 
  <div class="courses_wrapper">
      <div class="container">
        
            <div class="course-body">
            <div class="col-md-12 course-body-top">
                
            </div>
            <div class="col-lg-8 col-md-12"><!-- images/gallery/courses-dtls-image1.jpg -->
              
                
                <div class="col-md-12 padding0">
                  <div class="course-meta">
                    <!--<div class="author"><div class="image"> <a class="rs-author" href="#"><img src="images/author-img.jpeg" alt="image"> </a></div><div class="author-name"> <a class="rs-author" href="#"><?=$data['author'];?></a><p>Professior</p></div></div>-->
                    
                    <div class="categories"> <span class="cat-links"><p>Categorires:</p><a href="#" rel="tag"><?=ucwords($category)?></a></span></div>
                    <div class="client-rating">
                     <span class="course-rating-total"> 0 </span> Review
                     <div class="review-stars-rated">
                        <ul>
                          <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                          <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                          <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                          <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                          <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                        </ul>
                     </div>
                    </div>

                    
                    <div class="course-payment"><button class="button button-purchase-course" onClick="javascript:window.location.href='<?=$vpath?>payment/<?=$_REQUEST['course_id'];?>/<?=seo_url($data['name']);?>'"> Buy This Course </button></div>
                     <div class="course-payment add"><button class="button button-add-cart"> Add to Cart</button></div>

                  </div>
                </div>
                <div class="col-md-12 course-fee padding0">
                <div class="course-seats price"><div class="course-price"><h2>Course Fees</h2> <span class="price"><?=$setting['currency'];?><?=$data['cost'];?></span></div></div>
                </div>
                <div class="col-md-12 padding0 course-summary">

            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab1default" data-toggle="tab">Overview</a></li>
                      <li><a href="#tab2default" data-toggle="tab">Course Content</a></li>
                      <?if($_SESSION['user_id']){?><li><a href="#tab3default" data-toggle="tab">Q&A</a></li><? }?>
                     
                      <?if($_SESSION['user_id']){?><li><a href="#tab5default" data-toggle="tab">Announcement</a></li><?}?>
                      <?if($_SESSION['user_id']){?><li><a  href="#tab6default" title="<?=$data['name']?>" data-toggle="tab">Bookmark</a></li><?}?>
                       <!-- <li><?if($_SESSION['user_id']){?><a  href="#tab6default" title="<?=$data['name']?>" data-toggle="tab">Bookmark</a><?}else{?><span data-balloon="Please log in to Bookmark on this course!" data-balloon-pos="down"><a>Bookmark</a></span><?}?></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                          <div class="course-description" id="learn-press-course-description">
                                <h4 class="desc-title">What I will Learn</h4>
                                <?php echo html_entity_decode($data['feature']); ?> 

                                <h4 class="desc-title">Requirements</h4>
                                <?php echo html_entity_decode($data['requirement']); ?> 
                                 <h4 class="desc-title">Description</h4>
                                 <?php echo html_entity_decode($data['details']); ?> 
                                 <h4 class="desc-title">Target Audience</h4>  
                                 <?php echo html_entity_decode($data['skill']); ?> 
                          </div>
                          
                        </div>

                        <div class="tab-pane fade" id="tab2default">

                            <div class="col-md-12 padding0 course-conten">
                              <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <?
                                 
                                 if(count($data_subject))
                                 {

                                    for($i=0;$i<count($data_subject);$i++)
                                    {
  								  	                 
                                       ?>
    								  	                 <div class="panel panel-default">
    	                                      <div class="panel-heading" role="tab" id="heading<?=$i?>">
    	                                          <h4 class="panel-title">
    	                                              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i?>" aria-expanded="false" aria-controls="collapse<?=$i?>">
    	                                                  <?=($i+1)?>.<?=ucwords($data_subject[$i]['subject']);?>
    	                                              </a>
    	                                          </h4>
    	                                      </div>

  	                                      <div id="collapse<?=$i?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?=$i?>">
  	                                             <div class="panel-body">
                                                 
                                                  <?
                                                  echo"<table class='table'>";
                                                  $k=0;
                                                  $files=getQuery("course_subject_material","*",array("sub_id"=>$data_subject[$i]['sub_id']));
                                                  foreach($files as $file)
                                                  {
                                                    
                                                      echo"<tr><td>" . ($k+1)."</td><td>" . $file['name']."</td><td>";

                                                      if($file['type']=='video')
                                                      {
                                                          echo"<a title='View File' href='play.php?file=" .$file['file'] . "' data-toggle=\"modal\"  data-target=\"#modalFile\" ><img src='img/play.png' width=20></a>";
                                                      }
                                                      else
                                                      {
                                                          echo"<a title='View File' href='" .$vpath.$file['file'] . "' target=_new><img src='img/play.png' width=20></a>";
                                                      }    

                                                      
                                                      echo"</td><td>" .$file['duration']." Hrs.</td></tr>";
                                                      $k++;
                                                  }    
                                                   echo"</table>";
                                                  ?> 
                                              
                                                 </div>
  	                                      </div>
                                    	</div>
                                    	<?
  								                 }
                                 } 
                                 else
                                 {
                                  echo" No data updated yet!.";
                                 }
                                 ?>
                              </div>
                          </div>
 
                        </div>



                        <div class="tab-pane fade" id="tab3default">

                        <?php 

                       
                        if(count($faq))
                        {
                            $k=0;
                            foreach($faq as $val)
                            {

                                ?>
                                <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    <h4 class="panel-title">
                                      <a data-toggle="collapse in" data-parent="#accordion" href="#collapse<?=$k?>"><?php echo ucfirst($val['question']); ?></a>
                                    </h4>
                                  </div>
                                  <div id="collapse<?=$k?>" class="panel-collapse collapse in">
                                    <div class="panel-body"><?php echo $val['answer']; ?></div>
                                  </div>
                                </div>
                                
                              </div>
                              <? 
                              $k++;
                            }  

                        }  
                        else
                        {
                          echo" No data updated yet!.";
                        }  
                        ?>  
                          
 

                            
                        </div>
                       
                         <div class="tab-pane fade" id="tab5default">


                          <?php 
                           if($announcement)
                           {
                             echo $announcement;
                           }
                           else
                           {
                            echo"No announcement yet!";
                           } 
                          
                           
                          ?>
                        </div>
                        <div class="tab-pane fade" id="tab6default">
                          <form method='post' name='bookmarkForm' id='bookmarkForm'>
                           <input type='hidden' name='course_id' value='<?=$_REQUEST['course_id'];?>'>
                           <input type='hidden' name='user_id' value='<?=$_SESSION['user_id'];?>'>
                           <div class='col-md-10'><input type='text' name='bookmark' class='form-control' id='bookmark' placeholder='Enter your notes about this course for future use'></div><div class='col-md-2'>
                           <button  type='submit' class='btn btn-info btn-large'>Submit</button>
                          </div>
                           </form>
                           <div id='response'>
                             
                           <?
                             $notes=getQuery("course_user_bookmark","*",array("user_id"=>$_SESSION['user_id'],"course_id"=>$_REQUEST['course_id']));
                            if(count($notes))
                            {
                              echo"<div class='col-md-12' style='margin-top:20px'><table class='table table-borderd' >";
                        
                              foreach($notes as $note)
                              {
                                 echo"<tr><td width=20%>" . showdate($note['add_date']) . "</td><td style='text-align:left'>".$note['bookmark']."</td></tr>";
                              }    
                              echo"</table></div>";
                            } 
                            
                            ?>

                           </div> 

                         

                        </div>

                        
                    </div>
                </div>
            </div>
   
                </div>
                <?if(count($related_course))
                {?>
                    <div class="col-md-12 padding0 related-post">
                    <h2> Related Course</h2>
                    <div class="row">
                    <div id="news-slider1" class="owl-carousel">
                <?
  				
       			  $active="";
          
	            for($i=0;$i<count($related_course);$i++)
	            {
					     if(!$i){$active="active";}
				 
           			?>
                  <div class="single-courses-box">
                  
		          <div class="single-courses pop" data-container="body" data-toggle="popover" data-placement="right" data-content="Optional parameter: Skip if this was not requested<br>                                    A placement group is a logical grouping of instances within a single Availability Zone. Using placement groups enables applications to get the full-bisection bandwidth and low-latency network performance required for tightly coupled, node-to-node communication typical of HPC applications.<br><a class=&quot;favbtn&quot; href=&quot;#&quot; target=&quot#&quot;>Add To Favourites</a><a class=&quot;wishlist&quot; title=&quot;Add to Wishlist&quot; href=&quot;#&quot;><i class=&quot;fa fa-heart-o&quot; aria-hidden=&quot;true&quot;></i></a>">
		          <figure>
		            <div class="figure-img"><img src="<?=$vpath?>image.php?image=<?php echo $vpath; ?><?php echo $related_course[$i]['thumb_pic']; ?>&width=350&height=197&cropratio=2:1" alt="" class="img-responsive"></div>
		            <figcaption>
		              <div><a href="<?=$vpath;?>course-info/<?php echo $related_course[$i]['course_id']. "/" . seo_url($related_course[$i]['name']); ?>" class="read_more-btn">Apply Now</a></div>
		            </figcaption>
		          </figure>
		          <div class="courses-content-box">
		            <div class="courses-content">
		              <h3><a href="<?=$vpath;?>course-info/<?php echo $related_course[$i]['course_id']. "/" . seo_url($related_course[$i]['name']); ?>"><?php echo $related_course[$i]['name']; ?></a></h3>
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
		                <!--<li><i class="fa fa-user"></i> 80</li>-->
		                <li><div class="" ><i class="fa fa-heart"></i> 35</div></li>
		                <li><span><?=$setting['currency'];?><?php echo $related_course[$i]['cost'];?></span></li>
		              </ul>
		            </div>
                     
		          </div>
		        </div>
		      </div>
		    <?

    		}
		
    		?>  

            
                </div>
                </div>
            </div>
    <?}?>
            </div>
            <div class="col-lg-4 col-md-12 coursedtls-sitebar">
                <!--<div class="course_category course-detls">
                   <h2>Courses Name</h2>
                   
                   <ul>
                     
                       <li> <a href="#">Lorem Ipsum is simply dummy text</a> </li>
                     <li> <a href="#">Lorem Ipsum is simply dummy text</a> </li>
<li> <a href="#">Lorem Ipsum is simply dummy text</a> </li>
                   </ul>
                  </div> -->         
             	
                  
                   
                  <?include("includes/apply_form.php")?>  
               
                  <div class="course_category">
                   <h2>Courses Categories</h2>
                   
                   <ul>
                     <?
                     foreach($categories as $category)
                     {
                      ?>
                       <li> <a href="<?=$vpath?>course/<?=$category['cat_id']?>/<?=seo_url($category['cat_name'])?>"><i class="fa fa-angle-right" aria-hidden="true"></i> <?php echo $category['cat_name'];?></a> <span class="courses-cat-amount">(0)</span></li>
                      <?
                     } 
              
                    ?>

                   </ul>
                  </div>
                    
              
            </div>
            </div>
            
      </div>         
    </div>    

 <div class="modal fade video-new" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:600px;">
        <div class="modal-content" >
           <!--modal body-->
  
        </div>
    </div>
</div>   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/balloon-css/0.5.0/balloon.min.css">
<!--footer section-->
<?php 
include 'includes/footer.php';
?> 