<table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
	<thead>
		<tr>
		  <td><b>ID</b></td>
		  <td><b>Name</b></td><td align=center><b>Status</b></td><td align="center" with=25%><b>Option</b></td>
		</tr>
		</thead>
		<tbody>
	    <?php
		$j=0;
		foreach($db_list as $d)
		{
		  	?>
		  	<tr>
		  	<td><?php echo $d['id']?></td>
		  	<td ><?php echo $d['name']?></td> 
		  	<td align="center"><?php echo $d['status'];?></td>
		  	<td align="center">
		  	<?php
				echo"<a  href='test-entryform?id=" . $d['id'] . "'><u>Edit</u></a> | ";
			 
				
    			echo"<a  href=\"javascript:if (confirm('Are you sure you want to delete `" . $d['name'] . "`?')){window.location.href='test-list?del_id=" . $d['id'] . "'}\"><u>Delete</u></a>";
    		?>
				
			</td>
	       	</tr> 
	    <?   
        }	
        ?>
		</tbody>
        </table>
