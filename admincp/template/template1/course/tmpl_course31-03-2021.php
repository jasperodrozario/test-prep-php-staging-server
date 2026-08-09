<?include($configs['admin_template_include']."includes/tmpl_breadcrumb.php");?> 
<?
switch($action)
{
  case"add-category":
      include($configs['admin_template_include']."course/tmpl_add_category.php");
    break;


  case"add-course":
      include($configs['admin_template_include']."course/tmpl_add_course.php");
  break;

  case"add-lesson":
      include($configs['admin_template_include']."course/tmpl_add_lesson.php");
  break;


  case"add-chapter":
      include($configs['admin_template_include']."course/tmpl_add_chapter.php");
  break;


  case"view-review":
      include($configs['admin_template_include']."course/tmpl_view_review.php");
  break;

  case"add-question":
      include($configs['admin_template_include']."course/tmpl_add_question.php");
  break;

  case"add-video":
      include($configs['admin_template_include']."course/tmpl_add_video.php");
  break;

  case"add-studymat":
      include($configs['admin_template_include']."course/tmpl_add_studymat.php");
  break;

  case"add-subject":
      include($configs['admin_template_include']."course/tmpl_add_subject.php");
    break;

    case"category-list":
   
  ?>
      <table class='table table-striped'>
      <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>Id</th>
             <!--  <th>Icon</th> -->

            <th>Course Category Name</th>
              

             <th width=10% style="text-align: center;">Total Course</th>
           
            

            <th width=10% style="text-align: center;"> Status</th>

           
           
            <th width=20% style="text-align: center;">Action</th>
           
          </tr>
        </thead>
       
      <tbody>
      <?
      
        
        foreach($db_list as $d)
        {

          $total_course=getTotalRow("course",array("cat_id"=>$d['cat_id']));


        if($d['status']=='Y')
        {
           $d['status']="<img src='../img/on.png'>"; 
        }
        else
        {
          $d['status']="<img src='../img/off.png'>"; 
        }

        if($d['icon'])
           {
               $d['icon']="<img src='" . $configs['vpath'].$d['icon']."' width=48>"; 
           }


        ?>

          <tr>
        <td><?php echo $d['cat_id']?></td>
       <!--  <td ><?php echo $d['icon']?></td> -->
        <td ><?php echo $d['cat_name']?></td>
          <td align="center"><?=$total_course ?></td>
        <td align="center"><?php echo $d['status'];?></td>
        <td align="center">
        <?php

        echo "<u><a href='".$configs['admin_vpath'] ."course/add-category/" . encrypt($d['cat_id'])."' class=''>Edit</a></u> | ";
        
        echo "<u><a href='".$configs['admin_vpath'] ."course/course-list/" . encrypt($d['cat_id'])."' class=''>View Course</a></u> | ";
      
        
                
         
        echo "<u><a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."course/category-list?del_id=" . encrypt($d['cat_id'])."';}\" class=''>Delete</a></u>";

         
        ?>
        
      </td>
          </tr>

        <?    
        
      }


   
      ?>
      </tbody>  
      </table>
      <?

       if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","category-list");
      echo'</ul>';
    }


      
      
      
  break;



  case"course-list":
   
  ?>
      <table class='table table-striped'>
      <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>Id</th>
            <th idth=45%>Course Name</th>

           
            
            <?php
            if(!$_GET['cat_id'])
            {
              echo "<th width=20%>Category Name</th>";
            }
             ?>
             
            <th idth=15%>Total Chapter</th>


            <th idth=15%>Total Video</th>
            <th idth=15%>Total Studymat</th>
            
             <th idth=15%>Added By</th>
            <th width=10% style="text-align: center;"> Status</th>

           
           
            <th width=15% style="text-align: center;">Action</th>
           
          </tr>
        </thead>
       
      <tbody>
      <?
      
        
        foreach($db_list as $d)
        {

          $cat_name=getFldNameById("category","cat_name",array("cat_id"=>$d['cat_id']));


        if($d['status']=='Y')
        {
           $d['status']="<img src='".$configs['vpath']."img/on.png'>"; 
        }
        else
        {
          $d['status']="<img src='".$configs['vpath']."img/off.png'>"; 
        }

        $total_chapter=getTotalRow("chapter",array("course_id"=>$d['course_id']));
        $total_video=getTotalRow("file",array("file_type"=>"v","course_id"=>$d['course_id']));
        $total_studymat=getTotalRow("file",array("file_type"=>"p","course_id"=>$d['course_id']));
        ?>

          <tr>
        <td><?php echo $d['course_id']?></td>
        <td ><?php echo $d['name']?></td>

       

        <?php  if(!$_GET['cat_id'])
            { ?>
          <td><?=$cat_name ?></td> <? } ?>

           <td align="center"><?php echo $total_chapter?></td>
        <td align="center"><?php echo $total_video?></td>
        <td align="center"><?php echo $total_studymat?></td>
        <td align="center"><?php echo $d['added_by'];?></td>
        <td align="center"><?php echo $d['status'];?></td>
        <td align="center">
        <?php

        

         echo "<u><a href='".$configs['admin_vpath'] ."course/add-course?course_id=" . encrypt($d['course_id']) . "&cat_id=" . encrypt($d['cat_id'])."' class=''>Edit</a></u> | ";

         echo "<u><a href='".$configs['admin_vpath'] ."course/chapter-list?course_id=" . encrypt($d['course_id']) . "&cat_id=" . encrypt($d['cat_id'])."' class=''>View Chapter</a></u> | ";
        
         echo "<u><a href='".$configs['admin_vpath'] ."course/review-list?course_id=" . encrypt($d['course_id']) . "&cat_id=" . encrypt($d['cat_id'])."' class=''>Review</a></u> | ";       
                
         
        echo "<u><a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."course/course-list?del_id=" . encrypt($d['course_id'])."';}\" class=''>Delete</a></u>";

         
        ?>
        
      </td>
          </tr>

        <?    
        
      }


   
      ?>
      </tbody>  
      </table>
      <?

      if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","course-list");
      echo'</ul>';
    }

      
      
      
  break;


   case"chapter-list":
      ?>


        <table class='table table-striped'>
      <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>Id</th>
           
            <th>Name</th>

            <th style="text-align: center;" width="12%"> Display Order</th>

          
           
            <th width=15%>Action</th>
           
          </tr>
        </thead>
       
      <tbody>
      <?
      
      if($total)
      { 

        foreach($db_list as $d)
        {


        //$totalquestion=getTotalRow("questions",array("chapter_id"=>$d['chapter_id']));

        $totalvideo=getTotalRow("file",array("file_type"=>"v","chapter_id"=>$d['chapter_id']));
        $totalstudymat=getTotalRow("file",array("file_type"=>"p","chapter_id"=>$d['chapter_id']));

        echo"<tr><td>" . $d['chapter_id'] ."</td>
         
          <td>" . $d['name'] ."</td>
          <td align='center'>" . $d['ord'] ."</td>
         
          <td> <u><a href='".$configs['admin_vpath'] ."course/add-chapter?chapter_id=" . $d['chapter_id'] . "&course_id=" . $d['course_id']."' class=''>Edit</a></u> |
                    <u><a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."course/chapter-list?del_id=" . encrypt($d['chapter_id'])."';}\" class=''>Delete</a></u> | ";               

                   echo"<u><a href='".$configs['admin_vpath'] ."course/add-video?course_id=" . $d['course_id'] . "&chapter_id=" . $d['chapter_id']."&cat_id=" . $_GET['cat_id']."' class=''>Add Video</a> </u> | ";              

               

                   echo"<u><a href='".$configs['admin_vpath'] ."course/add-studymat?course_id=" . $d['course_id'] . "&chapter_id=" . $d['chapter_id']."&cat_id=" . $_GET['cat_id']."' class=''>Add Studymat</a> </u>";
                

                   
          echo"</td>
          </tr>";
      }
    } 
    else
    {
      echo"<tr align='center'><td colspan=4>No Chapter Found.</td></tr>";
    }
      ?>
      </tbody>  
      </table>
      <?
    break;

    case"review-list":
      ?>


        <table class='table table-striped'>
      <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>Id</th>
           
            <th>Date</th>

            <th>Review By</th>

           <th style="text-align: center;"> Status</th>
           
            <th width=20%>Action</th>
           
          </tr>
        </thead>
       
      <tbody>
      <?
      
      if($total)
      { 

        foreach($db_list as $d)
        {

        $user_type=getFldNameById("login","user_type",array("user_id"=>$d['user_id']));

        if($user_type=="student"){
          $user_type="student";

          $fname=getFldNameById("student","fname",array("user_id"=>$d['user_id']));
          $lname=getFldNameById("student","lname",array("user_id"=>$d['user_id']));
          $name=$fname." ".$lname;

        }
         else{

           $fname=getFldNameById("tutor","fname",array("user_id"=>$d['user_id']));
          $lname=getFldNameById("tutor","lname",array("user_id"=>$d['user_id']));
          $name=$fname." ".$lname;
         }
        if($d['status']=='Y')
        {
           $d['status']="<img src='../img/on.png'>"; 
        }
        else
        {
          $d['status']="<img src='../img/off.png'>"; 
        }
    

         
        echo"<tr><td>" . $d['id'] ."</td>
         
          <td>" . showdate($d['date']) ."</td>
          <td align=''>" . $name ."</td>

          <td align='center'>" . $d['status'] ."</td>



         
          <td> <a href='".$configs['admin_vpath'] ."course/view-review?course_id=" . $d['course_id'] . "&id=" . $d['id']."&user_id=" . $d['user_id']."' class=''>View</a> |
                    <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."course/review-list?del_id=" . encrypt($d['id'])."';}\" class=''>Delete</a>"; 


                    

                    


                   
          echo"</td>
          </tr>";
      }
    } 
    else
    {
      echo"<tr align='center'><td colspan=4>No Review Found.</td></tr>";
    }
      ?>
      </tbody>  
      </table>
      <?
    break;


}
?>
 