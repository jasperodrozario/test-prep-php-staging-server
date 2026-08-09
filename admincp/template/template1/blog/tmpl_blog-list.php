<form method='post' id='form' enctype="multipart/form-data">

       <table class='table table-striped'>
      <thead  >

         <tr >
            <th width=5%>Id</th>

            <th width=5%>Pic</th> 

            <th > Name</th>          
          
            <th width=15%>Author</th>
         <th width=15% style="text-align: center;">Total Comments</th> 
            <th width="10%" style="text-align: center;"> Status</th>
           
            <th width=10%>Action</th>
           
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

        $total_comments=getTotalRow("ocs_blog_review",array("blog_id"=>$d['id']));


        //if($d['pic']){$d['pic']="<img src='".$configs['vpath']."image.php?image=" . $configs['vpath'].$d['pic']."&w=60&h=60'>";}else{$d['pic']="<img src='".$configs['vpath']."image.php?image=" . $configs['vpath']."img/no-image.png&width=60&height=60'>";} 

        if($d['pic']){$d['pic']="<img src='". $configs['vpath'].$d['pic']."' width=60 height=60>";}else{$d['pic']="<img src='". $configs['vpath']."img/no-image.png' width=60 height=60>";} 
        

         
        echo"<tr><td>" . $d['id'] ."</td><td>".$d['pic']."</td><td>" . $d['name'] ."</td><td>" . $d['author'] ."</td> 

        <td align='center'><a href='".$configs['admin_vpath'] ."blog/comment-list?blog_id=" . encrypt($d['id'])."' class='dropdown-item'>" . (int)$total_comments ." </a> </td>

          <td align='center'>" . $d['status'] ."</td>

          <td align=center class='cursor-pointer'> 
           <img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
       
       <div class='dropdown-menu'>       

            <a href='".$configs['admin_vpath'] ."blog/add-blog?id=" . encrypt($d['id'])."' class='dropdown-item'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i> Edit</a> 

            <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."blog/blog-list?del_id=" . encrypt($d['id'])."';}\" class='dropdown-item'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i> Delete</a>

            
       
      </div>
          
            </td>

          
          </tr>";
      }
    } 
    else
    {
      echo"<tr><td colspan=3>No Blog Found.</td></tr>";
    }
      ?>
      </tbody>  


      </table>

    </form>
      <?
      
      if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","blog-list");
      echo'</ul>';
    }
      
  

?>



 