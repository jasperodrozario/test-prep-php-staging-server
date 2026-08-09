<form method='post' id='form' enctype="multipart/form-data">

       <table class='table table-striped'>
      <thead  >

         <tr >
            <th width=5%>Id</th>

            <?php
            if($_GET['parent_id'])
            {
              echo "<th width=5%>Icon</th>";
            }
             ?>

                       
          
            <th > Name</th>

            <?php
            if(!$_GET['parent_id'])
            {
              echo "<th width='16%' style='text-align: center;'> Total Sub category</th>";
            }
             ?>
            

           

            <th width="10%" style="text-align: center;"> Status</th>
           
            <th width=22%>Action</th>
           
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

        $total_sub=getTotalRow("category",array("parent_id"=>$d['cat_id']));
        

         if($d['icon'])
        {
               $d['icon']="<img src='" . $configs['vpath'].$d['icon']."' width=30 height=35>"; 
        }
        else{
          $d['icon']="<img src='" . $configs['vpath']."uploads/no-image.jpg' width=30 height=35>";
        }

        if(!$_GET['parent_id'])
        {
          if($total_sub)
          {
            $addsub="<a href='".$configs['admin_vpath'] ."category/category-list?parent_id=" . $d['cat_id']."' class='btn btn-success btn-sm'><i class='fa fa-eye fa-lg' aria-hidden='true' ></i></a>";
          }
          else{
            $addsub="<a href='".$configs['admin_vpath'] ."category/add-category?parent_id=" . $d['cat_id']."' class='btn btn-primary btn-sm'>Add Sub </a>";
          }
          
        }


         
        echo"<tr><td>" . $d['cat_id'] ."</td>";

        if($_GET['parent_id'])
        {          
          echo"<td>".$d['icon']."</td>";
        }

        echo "<td>" . $d['cat_name'] ."</td>";

        if(!$_GET['parent_id'])
        {          
          echo"<td align='center'><a href='".$configs['admin_vpath'] ."category/category-list?parent_id=" . $d['cat_id']."'>" . $total_sub ."</a></td>";
        }

          echo"<td align='center'>" . $d['status'] ."</td>";

          if($_GET['parent_id'])
          {
            echo"<td> <a href='".$configs['admin_vpath'] ."category/add-category?cat_id=" . $d['cat_id']."&parent_id=".$_GET['parent_id']."' class='btn btn-success btn-sm'><i class='fa fa-pencil-square fa-lg' aria-hidden='true' ></i></a> ".$addsub."  
                    <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."category/category-list?del_id=" . $d['cat_id']."';}\" class='btn btn-danger btn-sm'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i></a>
          </td>";
          }
          else{
            echo"<td> <a href='".$configs['admin_vpath'] ."category/add-category/" . $d['cat_id']."' class='btn btn-success btn-sm'><i class='fa fa-pencil-square fa-lg' aria-hidden='true' ></i></a> ".$addsub."  
                    <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."category/category-list?del_id=" . $d['cat_id']."';}\" class='btn btn-danger btn-sm'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i></a>
          </td>";
          }
          
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



 