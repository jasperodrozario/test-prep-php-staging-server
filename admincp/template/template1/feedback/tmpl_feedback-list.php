<table class='table table-striped'>
	    <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>ID</th><th width=60>Pic</th>
            <th width=25%>Feedback By</th>
              <th>Course</th>
          <th>Rating</th>
             <th>Date</th>  
             <th>Status</th>
            <th width=10% style="text-align: center;">Option</th>
           
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
	    	    if(!$d['pic']) 
						{
							$d['pic']="img/noimage.png";
						}
            $course_name="";
						if($d['course_id'])
						{
							$course_name=$courses_name[$d['course_id']];
						}
	    	 
					echo"<tr><td>" . $d['id'] ."</td><td width=60><img src='" . $configs['vpath']."image.php?image=" . $configs['vpath'].$d['pic'] ."&w=60&h=60' width=60 height=60></td><td>" . $d['name'] ."</td> 
		    	<td>" . $course_name ."</td><td>". $d['rating'] ."</td><td>" . showdate($d['date']) ."</td>
		    	<td>" . $status ."</td>
		    	<td align=center class='cursor-pointer'> 
           		<img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
       
		       <div class='dropdown-menu'>            

		            <a href='".$configs['admin_vpath'] ."feedback/add-feedback/" . $d['id']."' class='dropdown-item'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i> Edit</a> 		            

		            <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."feedback/feedback-list?del_id=" . $d['id']."';}\" class='dropdown-item'><i class='fa fa-trash-o fa-lg' aria-hidden='true' ></i> Delete</a>

		            
		       
		      </div>
          
            </td>

		    	
		    	</tr>";
			}
		}	
		else
		{
			echo"<tr align='center'><td colspan=5>No Feedback Found.</td></tr>";
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
 