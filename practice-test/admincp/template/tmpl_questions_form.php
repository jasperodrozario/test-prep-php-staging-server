 
<div id="form-response"></div>


<form method="post" id="form"  enctype="multipart/form-data">
<input type='hidden' name="questions_form" value=1>
<input type='hidden' name="id" value="<?=$id;?>">
 
<div class='row form-group'>
	<div class='col-md-3'><label class='form-control-label'>Question Tags</label></div><div class='col-md-9'><select class='form-control' name='type' id='type' REQUIRED onchange="getOptions(this.value);">
<option value=''>Select --</option>
<?
 if($db_value['type']=='exp'){echo "<option  value='exp'  selected> Explnation</option>";}else{echo"<option  value='exp' >Explnation</option>";}
?><?
 if($db_value['type']=='mcq'){echo "<option  value='mcq'  selected>Multichoice Questions</option>";}else{echo"<option  value='mcq' >Multichoice Questions</option>";}
?></select></div></div>
<div class='row form-group' id='options' style='display:none'>
		<div class='col-md-3'><label class='form-control-label'>No Options</label></div><div class='col-md-9'><select class='form-control' name='no_options' id='no_options' REQUIRED onChange="getFld(this.value);">
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
		<div class='col-md-3'><label class='form-control-label'>Questions</label></div><div class='col-md-9'><textarea   class='form-control description' name='questions' id='questions'><?=html_entity_decode($db_value['questions']);?></textarea>

         Need Explanation ? <input type='checkbox' name='check' onClick="toggle('expl');">
		</div></div>
		<div class='row form-group' id='expl' style='display: none'>
		<div class='col-md-3'><label class='form-control-label'></label></div><div class='col-md-9'><textarea  class='form-control' name='explanations' id='explanations'    placeholder="Explanations" ><?php echo html_entity_decode($db_value['explanations']);?></textarea></div></div>

		<div id='ansfld'>
		<?=$answers;?>	

		</div>

		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Status</label></div><div class='col-md-9'><input type='radio'  name='status' id='status' value='A' REQUIRED <?if($db_value['status']=='A'){echo'checked';}?>> Yes &nbsp<input type='radio'  name='status' id='status' value='S' REQUIRED <?if($db_value['status']=='S'){echo'checked';}?>> No &nbsp</div></div>
		 
		</form>
		  
 
 

