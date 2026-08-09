<form method='post' id='form' enctype="multipart/form-data">

       <table class='table table-striped'>
      <thead  >

         <tr >
            <th width=5%>Id</th>

            <td  align="left"><b>Question</b></td>

            <td width="10%"  align=center><b>Category</b></td>
         
            <td width="10%"  align=center><b>Order</b></td>
            <th width="5%" style="text-align: center;"> Status</th>
           
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


       
        $cat_name=getFldnameById("faq_category","cat_name",array("cat_id"=>$d['cat_id']));
         
        echo"<tr><td>" . $d['id'] ."</td><td>" . $d['question'] ."</td><td>" . $cat_name ."</td><td align='center'>" . $d['ord'] ."</td>          
          <td align='center'>" . $d['status'] ."</td>

          <td> <a href='".$configs['admin_vpath'] ."faq/add-faq/" . encrypt($d['id'])."' class='btn btn-success btn-xs'><i class='fa fa-pencil-square fa-lg' aria-hidden='true' ></i></a> 
                    <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."faq/faq-list?del_id=" . encrypt($d['id'])."';}\" class='btn btn-danger btn-xs'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i></a>
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



 