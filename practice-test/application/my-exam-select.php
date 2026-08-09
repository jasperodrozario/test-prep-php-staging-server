<?php 
include 'includes/header.php';
chklogin();
?>   
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
                include("includes/left_dashboard.php");
            ?>
               
            </div>
            
            <div class="col-lg-9 col-md-12">
                  <div class="panel dashbord-panel with-nav-tabs panel-default">
                  <div class="panel-heading">Select Exam</div>
                   
                  <table  width="100%" class="table table-bordered table-striped" border="0">
					<thead>
					<tr><td><b>Id</b></td><td width=30%><b>Name</b></td><td><b>Questions</b></td><td><b>Timer</b></td><td align='center'><b>Option</b></td></tr>	
					</thead>
					
					<tbody>
					    <?php
					    $db_values=getQuery("questions_set","*",array("status"=>"A"));
						$j=0;
						foreach($db_values as $d)
						{
							$exit=getTotalRow("running_exam",array("user_id"=>$_SESSION['user_id'],"set_id"=>$d['id']));
						    if($exit)
						    {
								$status="Done | <a href='" . $configs['vpath'] ."exam/" . $d['id'] ."'>View</a>";
							}
							else
							{
								$status="<a href='" . $configs['vpath'] . "exam/" . $d['id'] . "'>Take Exam</a>";
							}
						    $total_questions=getTotalRow("questions_set_questions",array("set_id"=>$d['id']));

							echo"<tr ><td>".$d['id']."</td><td>".$d['set_name']."</td><td>".$total_questions."</td><td>".$d['timer']." Minutes</td><td align=center>".$status."</td></td></tr>";	    
					    }	
					    ?>
					</tbody>
				</table>
				
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