 
<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>My Online Exams</h1></div>
          </div>
</div>  

<!--courses section-->
  <div class="userprofile_wrapper">
      <div class="container">
            <div class="userprofile-body">
            <div class="col-lg-3 col-md-12">
            <?
                include("includes/tmpl_left_dashboard.php");
            ?>
            </div>
            
            <div class="col-lg-9 col-md-12">
                <div class="panel dashbord-panel with-nav-tabs panel-default">
                <div class="panel-heading">My Online Exams</div>
                <div class="panel-body">
                <?
                    
                     if($total_exams)
                     {
                         echo"<table class='table  table-striped'>
                         <thead><tr><td>Date</td><td >Exam Name</td><td>Duration [Minutes]</td><td >Tot. Questions</td><td >Correct Answers</td><td align=center>View Details</td></tr></thead><tbody>";
                         foreach($exams as $d)
                         {
                          
                           echo"<tr><td>" . showdate($d['date'])."</td><td>" . $d['set_name'] . "</td><td>" . $d['timer'] . " Miniutes</td><td>" .$d['total_questions'] . "</td><td>" .$d['correct'] . "</td><td align=center><a href='" . $configs['vpath'] ."exam/" . $d['set_id'] ."'>View Details</a></td></tr>";
                         }
                          echo"</tbody></table>";
                        echo"<center><h4><button type='button' class='btn btn-success' onClick=\"window.location.href='my-exam-select'\">Got for Next Exam</button> </center>";
                  
                    }
                    else
                    {
                        echo"<center><h4>You did not participate on any exam yet! <button type='button' class='btn btn-success' onClick=\"window.location.href='my-exam-select'\">Get Start</button> </center>";
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
