<div id="tabs">
<ul>
    <li ><a href='#tabs-1'>Student Login</a></li> 
  	<li ><a href='student_profile_form?info=info&user_id=<?=$_GET['user_id']?>'>Info</a></li> 
    <!--<li ><a href='student_profile_form?info=grant&user_id=<?=$_GET['user_id']?>'>Grant</a></li>
    <li ><a href='student_profile_form?info=applied&user_id=<?=$_GET['user_id']?>'>Exam History</a></li>--.>

     <!--<li ><a href='student_profile_form?info=enquires&user_id=<?=$_GET['user_id']?>'>Enquires</a></li>
   <li ><a href='student_profile_form?info=notes&user_id=<?=$_GET['user_id']?>'>Notes</a></li>
    <li ><a href='student_profile_form?info=mail&user_id=<?=$_GET['user_id']?>'>Send Email</a></li>-->

</ul>
  
 <hr>
 <div id="tabs-1" style='margin-top:20px'>
 
  <?
    
   include("tmpl_student_profile_form.php");
   
  
  ?> 

</div></div>



