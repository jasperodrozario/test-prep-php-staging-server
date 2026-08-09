<?php 
include 'includes/header.php';
chklogin();
?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Courses Enrolled</h1></div>
          </div>
</div>  

<!--courses section-->
  <div class="userprofile_wrapper">
      <div class="container">
        
            <div class="userprofile-body">
            <div class="col-lg-3 col-md-12">
            
                 
                <?
                include("includes/left_dashboard.php");
                ?>
               
            </div>
            
            
            <div class="col-lg-9 col-md-12">
            
              
                  <div class="panel dashbord-panel with-nav-tabs panel-default">
                  <div class="panel-heading">My Course Enrolled</div>
                  <div class="panel-body"><?
                     $courses=getQueryByJoin(array("course_user as u","course as c"),"c.*,u.add_date",array("u.user_id"=>$_SESSION['user_id']),array("u.course_id"=>"c.course_id"),"u.add_date desc");
                     
                     if(count($courses))
                     {
                         echo"<table class='table  table-striped'>
                         <thead><tr><td colspan=2 width=50%>Course Name</td><td>Duration [Hrs]</td><td>Purchased On</td><td align=center>Syllabus</td></tr></thead><tbody>";
                         foreach($courses as $course)
                         {
                           $cat_name=getFldValueById("category","cat_name",array("cat_id"=>$course['cat_id']));

                          echo"<tr><td><img src='" . $vpath."image.php?image=" .$vpath.$course["thumb_pic"]."&width=100&height=100&cropratio=1:1'></td><td><h4>" . $course['name'] . "</h4><br>Category : " . $cat_name."</td><td><img src='img/time.png' width=30>" .$course['duration'] . "</td><td> ".showdate($course['add_date']) ."</td><td align=center><a href='download-course/" . $course['course_id'] . "/". seo_url($course['name']) ."'><img src='img/more.png' width=30></a></td></tr>";
                         }
                          echo"</tbody></table>";
                     }
                    else
                    {
                        echo"<p align=center><h4>No course purchased yet!</p>";
                    }
                    ?>




                    
                  </div>
            
             
               </div>
          </div>
            </div>
            </div>
            
            </div>
            
      </div>         
    </div>    

               

<!--footer section-->
<?php 
include 'includes/footer.php';
?> 