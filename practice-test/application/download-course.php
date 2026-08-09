<?php 
include 'includes/header.php';
chklogin();
$data=getDataByWhere("course","*",array("course_id"=>$_REQUEST['course_id']));
?>   

<!--banner section-->
<div class="banner_wrapper">
  <!--carousel section-->
  <div class="inner-banner">
      <image class="img-responsive" src="images/slider-03.jpg" alt="">
      <div class="banner-text"><h1>Download Courses</h1></div>
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
               <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
             <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />

                  <div class="panel dashbord-panel with-nav-tabs panel-default">
                  <div class="panel-heading">My Course Enrolled > <?=$data['name']?></div>
                  <div class="panel-body">


						<div id="pettabs" class="indentmenu">
                        <ul>
                        <li><a href="<?=$vpath?>course-data.php?content=overview&course_id=<?=$_REQUEST['course_id'];?>" rel="#iframe" >Course Overview</a></li>
                        <!--<li><a href="<?=$vpath?>course-data.php?content=subjects&course_id=<?=$_REQUEST['course_id'];?>" rel="#iframe">Subjects</a></li>-->
                        <li><a href="<?=$vpath?>course-data.php?content=download&course_id=<?=$_REQUEST['course_id'];?>" rel="#flowerdivcontainer">Syllabus</a></li>
                        <li><a href="<?=$vpath?>course-data.php?content=faq&course_id=<?=$_REQUEST['course_id'];?>" rel="#iframe">Q&A</a></li>
                        
                        </ul>
                     
                        </div>
                        
                        <div id="petsdivcontainer" style="border-top:1px solid gray; width:100%; height: auto; margin-bottom:1em">
                        
                        </div>
                   
					

                    
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