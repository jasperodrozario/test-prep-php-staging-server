<form method='post' id='form' enctype="multipart/form-data">

       <table class='table table-striped'>
      <thead  >

         <tr >
            <th width=5%>Id</th>

            <td  align="left"><b>Question</b></td>

            <td  align=center><b>Course</b></td>
         
            <td width="10%"  align=center><b>Order</b></td>
            <th width="5%" style="text-align: center;"> Status</th>
           
            <th width=10% style="text-align: center;">Option</th>
           
          </tr>
        </thead>


       
        <tbody>
      <?
      
      if($total)
      {  
        foreach($db_list as $d)
        {

          if($d['status']=='Y')
        {
           $d['status']="<img src='../img/on.png'>"; 
        }
        else
        {
          $d['status']="<img src='../img/off.png'>"; 
        }
 
        $course_name=getFldnameById("ocs_course","name",array("course_id"=>$d['course_id']));
         
        echo"<tr><td>" . $d['id'] ."</td><td>" . $d['question'] ."</td><td>" . $course_name ."</td><td align='center'>" . $d['ord'] ."</td>          
          <td align='center'>" . $d['status'] ."</td>

          <td align=center class='cursor-pointer'> 
           <img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
       
           <div class='dropdown-menu'>                  

                <a href='".$configs['admin_vpath'] ."faq/add-faq/" .$d['id']."' class='dropdown-item'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i> Edit</a>

                <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."faq/faq-list?del_id=" . $d['id']."';}\" class='dropdown-item'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i> Delete</a>
          
          </div>
          
            </td>

          
          </tr>";
      }
    } 
    else
    {
      echo"<tr><td colspan=7 align='center'>No Faq Found.</td></tr>";
    }
      ?>
      </tbody>  


      </table>

    </form>
      <?
      
      if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","faq-list");
      echo'</ul>';
    }
      
  

?>



 