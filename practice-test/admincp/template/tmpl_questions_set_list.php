<hr style='margin-top:0'>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table  width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
	<thead>
	<tr><td><b>Id</b></td><td width=30%><b>Set Name</b></td><td ><b>Course</b></td><td><b>Questions</b></td><td><b>Timer</b></td><td><b>Status</b></td> <td align='center'><b>Option</b></td></tr>	
	</thead>
	
	<tbody>
	    <?php
		$j=0;
		foreach($db_values as $d)
		{
			$course_name=getFldValueByid("course","name",array("course_id"=>$d['course_id']));  
            $total_questions=getTotalRow("questions_set_questions",array("set_id"=>$d['id']));  

			echo"<tr ><td>".$d['id']."</td><td>".$d['set_name']."</td><td>".$course_name."</td><td>".$total_questions."</td><td>".$d['timer']." Minutes</td><td>".$d['status']."</td><td align='enter'><i class='fa fa-pencil-square-o'> <a  href='modal_questions_set_form.php?id=".$d['id']."' data-toggle='modal' data-target='#myModalEdit'>Edit</a> | <a  href='questions_list.php?set_id=".$d['id']."' >Add Questions</a> | <a  href='view_questions_list.php?set_id=".$d['id']."' >View Questions</a> | <a  href='modal_view_exam.php?id=".$d['id']."' data-toggle='modal' data-target='#myModalEdit'>View Exam</a> | <i class='fa fa-times'></i> <a  href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location.href='questions_set_list.php?del_id=".$d['id']."'}\">Delete</a></td></tr>";	    
	    }	
	    ?>
	</tbody>
</table>
<?
if($total>20):

echo paging(20,$total,'$param');

endif;
?>


   
<script>
//jQuery.noConflict(); 
//$('#myModaEdit').modal('show'); 

 

 $('#myModalEdit').on('hidden.bs.modal', function () {
 	$("#modal-content").html("");
 location.reload();
})

</script>

<!-- Modal HTML -->
<div id="myModalEdit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content" id='modal-content'>
            <!-- Content will be loaded here from "remote.php" file -->
        </div>
    </div>
</div>

<style>
.load-more {
    margin: 15px 25px;
    cursor: pointer;
    padding: 10px 0;
    text-align: center;
    font-weight:bold;
}	
</style>
