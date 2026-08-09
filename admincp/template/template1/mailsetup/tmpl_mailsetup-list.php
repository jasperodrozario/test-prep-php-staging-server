<table class='table table-striped'>
      <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>Id</th>
            <th>Subject</th>
           
            <th width=10% style="text-align: center;">Option</th>
           
          </tr>
        </thead>
       
      <tbody>
      <?
      
      if($total)
      {  
        foreach($db_list as $d)
        {
         
        echo"<tr><td>" . $d['id'] ."</td>
          <td>" . $d['subject'] ."</td>
          <td align=center class='cursor-pointer'> 
           <img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
       
           <div class='dropdown-menu'>               

                <a href='".$configs['admin_vpath'] ."mailsetup/add-mailsetup/" . encrypt($d['id'])."' class='dropdown-item'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i> Edit</a>                

                <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."mailsetup/mailsetup-list?del_id=" . encrypt($d['id'])."';}\" class='dropdown-item'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i> Delete</a>
                
           
          </div>
          
            </td>

          
          </tr>";
      }
    } 
    else
    {
      echo"<tr><td colspan=3>No Member Found.</td></tr>";
    }
      ?>
      </tbody>  
      </table>
      <?
      
     
    
    if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","mailsetup-list");
      echo'</ul>';
    }

      
 


?>
 