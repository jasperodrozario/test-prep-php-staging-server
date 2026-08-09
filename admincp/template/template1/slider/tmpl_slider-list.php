<form method='post' id='form' enctype="multipart/form-data"> 

    <table class='table table-striped'>
      <thead  >

          <tr >
            <th width=5%>Id</th> 

            <th width=12%>Slider Image</th>           
          
            <th >Title</th>



            <th width="15%" style="text-align: center;"> Display Order</th>
           
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

         if($d['background'])
        {
               $d['background']="<img src='" . $configs['vpath'].$d['background']."' width=30 height=35>"; 
        }
        else{
          $d['background']="<img src='" . $configs['vpath']."uploads/no-image.jpg' width=30 height=35>";
        }

        

         
        echo"<tr><td>" . $d['id'] ."</td><td>".$d['background']."</td><td>" . $d['title'] ."</td>          
          <td align='center'>" . $d['ord'] ."</td>

           <td align=center class='cursor-pointer'> 
           <img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
       
       <div class='dropdown-menu'>       

            <a href='".$configs['admin_vpath'] ."slider/add-slider?id=" . encrypt($d['id'])."' class='dropdown-item'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i> Edit</a> 

            <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."slider/slider-list?del_id=" . encrypt($d['id'])."';}\" class='dropdown-item'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i> Delete</a>

            
       
      </div>
          
            </td>


          
          </tr>";
      }
    } 
    else
    {
      echo"<tr><td colspan=5 align=center>No Slider Found.</td></tr>";
    }
      ?>
      </tbody>  


      </table>

    </form>
      <?
      
      if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","report-list");
      echo'</ul>';
    }
      
      
      
  
?>


<script>

$("#ckbCheckAll").click(function () {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});
</script>
 