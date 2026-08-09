 <form method="post" action="">
 	<div class="row">

 	<div class='col-md-12'><input type='submit' class='btn btn-success pull-right' value='Approve all Checked'></div>
 </div>

		<table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
			<thead>
			<tr><td width=10><input type='checkbox' id='checkAll'></td><td width=5%><b>Id</b></td><td width=10%><b>Name</b></td><td><b>Email</b></td><td><b>Date</b></td><td><b>Status</b></td> <td align='center' width="10%"><b>Option</b></td></tr>	
			</thead>
			
			<tbody>
			    <?php
			    if(!$total){
			        echo "<tr><td colspan='7' align='center'>No Comments Found.</td></tr>";
			    }
				$j=0;
				foreach($db_list as $d)
				{
					  	 
					echo"<tr ><td><input type='checkbox' name='ids[]' value='".$d['id']."'></td><td>".$d['id']."</td><td>".$d['name']."</td><td>".$d['email']."</td><td>".showdate($d['date'])."</td><td>".$d['status']."</td>

						<td align=center class='cursor-pointer'> 
           <img src='" . $configs['admin_vpath'] ."img/dots.png'  data-toggle='dropdown' aria-haspopup='tru' aria-expanded='false'>
		   
		   <div class='dropdown-menu'>           

           

            <a  href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='".$configs['admin_vpath'] ."blog/comment-list?del_id=" . $d['id']."';}\" class='dropdown-item'><i class='fa fa-trash' aria-hidden='true' ></i> Delete</a>

       
		  </div>
          
            </td>

					</tr>";	 

		 
			    }	
			    ?>
			</tbody>
		</table>
		<div class='row'>
		<!-- <div class='col-md-6'><input type='submit' class='btn btn-success' value='Approve all Checked'></div> -->	
		 
		<div class='col-md-6'>
		<?
		if($total>20):

			echo "<ul class='pagination pull-right'>" . paging($total,"","enquiry-list") . "</ul>";

		endif;
		?>
		</div></div>
		</form>