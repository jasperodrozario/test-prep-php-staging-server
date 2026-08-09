<div class="profile-toppart">
<div class="profile-img">
      

   <?
    $logo=getFldValueById("login","pic",array("user_id"=>$_SESSION['user_id']));
    if($logo)
    {
         ?>
        
         <img class="img-responsive" src="<?=$configs['vpath'].$logo?>" alt="<?=$_SESSION['name'];?>">
         </span>
        
    <?
       
    }
    else
    {
    ?>  
        
         <img class="img-responsive" src="<?=$configs['vpath']?>img/avatar.png" alt="<?=$_SESSION['name'];?>">
        
        
    <?
    }
    ?>
		
       
               
</div>
 <div class="profile-headtext">
          <h4>Hey <?=$_SESSION['name'];?></h4>
        </div>
        </div>


<div class="course-features-info dash-sidebar">
      <ul>
      <li class="lectures-feature"><a href="<?=$configs['vpath']?>my-dashboard"> <span class="label">Dashboard</span></a></li>
      <li class="quizzes-feature"> <a href="<?=$configs['vpath']?>edit-profile"><span class="label">My Account</span></a></li>
      
      
      <li class="duration-feature"><a href="<?=$configs['vpath']?>exam/<?=$_SESSION['test_id'] ."-" . seo_url($_SESSION['test_name']);?>"> <span class="label">My Online Exam</span></a></li>
      <li class="duration-feature"><a href="<?=$configs['vpath']?>exam/progress-report"> <span class="label">Progress Report</span></a></li>
     
      <li class="skill-feature"><a href="<?=$configs['vpath']?>change-password"><span class="label">Change Password</span></a></li>
      <!--<li class="language-feature"><a href="<?=$configs['vpath']?>pay"><span class="label">Payment</span></a></li>-->
      <li class="assessments-feature"><a href="<?=$configs['vpath']?>logout"><span class="label">Logout</span></a></li>
      </ul>
</div>