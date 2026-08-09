<?
include('../configs/license.php');
include('../configs/common.php');
if($_REQUEST['id'])
{
	$db_value= getDataByWhere('questions','*',array('id'=>$_REQUEST['id']));
}
?>
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" onClick="window.parent.location.reload()"><i class="fa fa-times" aria-hidden="true"></i></button><h4 class="modal-title" id="myModalLabel">Questions Form </h4></div><div class="modal-body" ><div id="questions_response"></div>

<form method="post" id="questions"  enctype="multipart/form-data">
<input type='hidden' name="questions" value=1>
<div class='row form-group'>
<div class='col-md-3'><label class='form-control-label'><b>Subject</b></label></div><div class='col-md-9'> 
<?=getSelect('exam_category','id,name',$db_value['sub_cat_id'],'sub_cat_id');?></select></div></div>
<div class='row form-group'><div class='col-md-3'><label class='form-control-label'><b>Type</b></label></div><div class='col-md-9'><select class='form-control' name='type' id='type' REQUIRED>
<option value=''>Select --</option>
<?
 if($db_value['type']=='E'){echo "<option  value='E'  selected> E</option>";}else{echo"<option  value='E' > E</option>";}
?><?
 if($db_value['type']=='M'){echo "<option  value='M'  selected> M</option>";}else{echo"<option  value='M' > M</option>";}
?></select></div></div>
<div class='row form-group'>
					<div class='col-md-3'><label class='form-control-label'><b>No Options</b></label></div><div class='col-md-9'><select class='form-control' name='no_options' id='no_options' REQUIRED>
<option value=''>Select --</option>
<?
 if($db_value['no_options']=='2'){echo "<option  value='2'  selected> 2</option>";}else{echo"<option  value='2' > 2</option>";}
?><?
 if($db_value['no_options']=='3'){echo "<option  value='3'  selected> 3</option>";}else{echo"<option  value='3' > 3</option>";}
?><?
 if($db_value['no_options']=='4'){echo "<option  value='4'  selected> 4</option>";}else{echo"<option  value='4' > 4</option>";}
?><?
 if($db_value['no_options']=='5'){echo "<option  value='5'  selected> 5</option>";}else{echo"<option  value='5' > 5</option>";}
?><?
 if($db_value['no_options']=='6'){echo "<option  value='6'  selected> 6</option>";}else{echo"<option  value='6' > 6</option>";}
?></select></div></div>
<div class='row form-group'>
					<div class='col-md-3'><label class='form-control-label'><b>Questions</b></label></div><div class='col-md-9'><textarea  class='form-control' name='questions' id='questions' REQUIRED><?php echo html_entity_decode($db_value['questions']);?></textarea></div></div>
<div class='row form-group'>
					<div class='col-md-3'><label class='form-control-label'><b>Explanations</b></label></div><div class='col-md-9'><textarea  class='form-control' name='explanations' id='explanations' REQUIRED><?php echo html_entity_decode($db_value['explanations']);?></textarea></div></div>
<div class='row form-group'>
					<div class='col-md-3'><label class='form-control-label'><b>Status</b></label></div><div class='col-md-9'><input type='radio'  name='status' id='status' value='Y' REQUIRED <?if($db_value['status']=='Y'){echo'checked';}?>> Y &nbsp<input type='radio'  name='status' id='status' value='N' REQUIRED <?if($db_value['status']=='N'){echo'checked';}?>> N &nbsp</div></div>

<div class='row form-group'>
<div class='col-md-3'></div><div class='col-md-9'><input type='submit'  name='submit' value='Update' class='btn btn-success'> 
</div></div>
 
</form>
  