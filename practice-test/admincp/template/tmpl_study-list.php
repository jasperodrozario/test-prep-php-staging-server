<div id='response'></div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        
        <form method="post" method='post' action='<?= basename($_SERVER['PHP_SELF'], ".php"); ?>' enctype="multipart/form-data">
             <div class='row'>
                <div class='col-md-4'>
                    <input type='number' name='id' placeholder='Enter Study Id' class='form-control'>
                </div>
                <div class='col-md-5'>
                    <input type='text' name='title'  id='ctitle' class='form-control' placeholder='Enter Study Title' autocomplete="off" >
                </div>
                <div class='col-md-3'>
                    <input type='submit'  name='submit' value='Search' class='btn btn-success'>
                </div>
            </div>
        </form>
        
        <div class='clearfix'><br></div>

        <table class='table table-bordered'>
			<thead style="background-color:#16A085">
                <th width=5%>ID</th>
                <th width=70%>Title</th>
                <th>Action</th>
            </thead>
            
            <tbody>
            <?
            if($total){
                foreach($db_list as $f){
                   echo"
					<tr>
						<td>" . $f['study_id'] ."</td>
                        <td ><a href='view-study?study_id=" . $f['study_id'] . "'>" . $f['study_title'] . "</a></td>
                        <td>
                            <i class='fa fa-edit'></i> 
							<a href='study_entryform?study_id=" .$f['study_id']. "&limit=" .$_GET['limit'] ."'>Edit</a> &nbsp;
							
							<i class='fa fa-trash'></i> 							
							<a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?del_id=" .$f['study_id'] ."&limit=" . $_GET['limit'] ."';}\" >Delete</a>
						</td>
					</tr>";
                    
                }
            }
            ?>
            </tbody>

        </table>

    </div>
</div>
