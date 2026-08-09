<table class='table table-striped'>
	    <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>ID</th>
            <th>Feedback By</th>
          
             <th>Date</th>
             <th>Status</th>
            <th width=10%>Action</th>
           
          </tr>
        </thead>

      <!--   <td>" . substr(html_entity_decode(html_entity_decode($d['fd_desc'])),0,60) ."</td> -->
	     
	    <tbody>
	    <?
	    
	    if($total)
	    {  
	    	foreach($db_list as $d)
	    	{
	    	    if($d['status']=="Y"){$status="Active";}else{$status="Offline";}
	    	 
				echo"<tr><td>" . $d['id'] ."</td>
				<td>" . $d['name'] ."</td>
		    
		    
		    	<td>" . showdate($d['date']) ."</td>
		    	<td>" . $status ."</td>
		    	<td> <a href='".$configs['admin_vpath'] ."feedback/add-feedback/" . encrypt($d['id'])."' class='btn btn-success btn-xs'><i class='fa fa-pencil-square fa-lg' aria-hidden='true' ></i></a>
                    <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."feedback/feedback-list?del_id=" . encrypt($d['id'])."';}\" class='btn btn-danger btn-xs'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i></a>
		    	</td>
		    	</tr>";
			}
		}	
		else
		{
			echo"<tr><td colspan=3>No Feedback Found.</td></tr>";
		}
	    ?>
	    </tbody>	
	    </table>
	    <?
	    
	    

	     if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","feedback-list");
      echo'</ul>';
    }


	    
	

?>
 