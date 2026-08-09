<form method='post' id='form' enctype="multipart/form-data">

       <table class='table table-striped'>
      <thead  >

         <tr >
            <th width=5%>Id</th>       
                       
          
            <th > Category Name</th>         

            <th width="10%" style="text-align: center;"> Status</th>

            <th width="15%" style="text-align: center;"> Added Date</th>
           
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
         
        echo"<tr><td>" . $d['cat_id'] ."</td>";    
       
        

        echo "<td>" . $d['cat_name'] ."</td>";        

          echo"<td align='center'>" . $d['status'] ."</td>";

          echo"<td align='center'>" . showdate($d['date']) ."</td>";         
         
            echo"<td align=center class='cursor-pointer'> 
           <img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
       
       <div class='dropdown-menu'>            

            <a href='".$configs['admin_vpath'] ."category/add-category/" . $d['cat_id']."' class='dropdown-item'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i> Edit</a>       

            <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."category/category-list?del_id=" . $d['cat_id']."';}\" class='dropdown-item'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i> Delete</a>

            
       
      </div>
          
            </td>";
         
          
          echo"</tr>";
      }
    } 
    else
    {
      echo"<tr><td colspan=6 align='center'>No Category Found.</td></tr>";
    }
      ?>
      </tbody>  


      </table>

    </form>
      <?
      
      if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","category-list");
      echo'</ul>';
    }
      
  

?>



 