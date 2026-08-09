<div id='response'></div>
<?

if($_REQUEST['page']!="all-question-list")
{

?>	 
 <ul class="nav nav-tabs">
	<li <?if($_REQUEST['page']=="add-question"){echo"class='active'";}?>><a  href="?page=add-question&cat_id=<?=$_REQUEST['cat_id']?>" class='total'>Add Question</a></li>
	<li <?if($_REQUEST['page']=="question-list"){echo"class='active'";}?>><a  href="?page=question-list&cat_id=<?=$_REQUEST['cat_id']?>" class='total'>Question List (<?=$total;?>)</a></li>
	
	</ul>
 <?}?>   
	<div class="tab-content">
	<div   class="tab-pane fade in active">
	<?
	switch($_REQUEST['page'])
	{
		case "add-question": 
	 	?>   
	 	<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
		<h5><b>Add Question</b></h5>
		<p><br></p>
		<div id="form-response"></div>

		<form method="post" id="form"  enctype="multipart/form-data">
		<input type='hidden' name="action" value="add-bulk-question">
		<input type='hidden' name="id" value="<?=$_REQUEST['id'];?>">
	 
		<input type='hidden' name="limit" value="<?=$_REQUEST['limit'];?>">
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Subject</label></div>
		<div class='col-md-9'><?
		echo getDropDown($categories,"cat_id","cat_name",$db_value['cat_id'],"required");
		?></div></div>
		
        <div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Grade</label></div><div class='col-md-9'>
		<select name='grade' class='form-control' size=1 required>
		<option value=''>Select Grade</option>
		<?
		for($y=1;$y<=10;$y++)
		{
			if($y==$db_value['grade'])
			{
				echo"<option value='" . $y ."' selected>".$y ."</option>";
			}
			else
			{
				echo"<option value='" . $y ."'>".$y ."</option>";
			
			}
		}
		?>
		</select>
		</div></div>
		
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Year</label></div><div class='col-md-9'>
		<select name='year' class='form-control' size=1 required>
		<option value=''>Select Year</option>
		<?
		for($y=2010;$y<=date("Y");$y++)
		{
			if($y==$db_value['year'])
			{
				echo"<option value='" . $y ."' selected>".$y ."</option>";
			}
			else
			{
				echo"<option value='" . $y ."'>".$y ."</option>";
			
			}
		}
		?>
		</select>	
		</div></div>
		
		
		
		<div class='row form-group story' >
		<div class='col-md-3'><label class='form-control-label'>Story Title</label></div><div class='col-md-9'>
		<select name='story_id' id='story_id' class='form-control' size=1 >
		<option value=''>Select Story</option>
		<?
		foreach($story_list as $s)
		{
		    if($s['id']==$db_value['story_id'])
		    {
		   		echo"<option value='".$s['id'] ."' selected>" .$s['title'] ."</option>";
		    }
		    else
		    {
				echo"<option value='".$s['id'] ."'>" .$s['title'] ."</option>";
		  
			}
		   	
		}
		?> 
		</select>	
		</div></div>
		
		
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Question</label></div><div class='col-md-9'><textarea   class='form-control' name='question' id='question'   ><?=html_entity_decode($db_value['question']);?></textarea>

        </div></div>
        <?
        
        if($total_answers_bulk)
        {
        	$i=0; 
		    foreach($answers_bulk as $ans)
			{	
		        
		        echo"<div class='row form-group'>
				<div class='col-md-3'><label class='form-control-label'>Answer " . ($i+1) ."</label></div>
				<div class='col-md-9'><textarea id='answer".($i+1)."' class='form-control ' name='answer".($i+1)."' REQUIRED>" . html_entity_decode($ans['answer']) ."</textarea>
		        </div></div>";
				 
				$i++;
			}
		}		
		else
		{       
				$i=0;$answer_html="";
			    for($i=0;$i<$option_no;$i++)
				{	
			    
			        echo"<div class='row form-group'>
					<div class='col-md-3'><label class='form-control-label'>Answer " . ($i+1) ."</label></div>
					<div class='col-md-9'><textarea  id='answer".($i+1)."' class='form-control' name='answer".($i+1)."' REQUIRED>" . html_entity_decode($ans['answer']) ."</textarea>
			        </div></div>";
					 
					 
				}	
		}
        ?>
        <div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Correct Answer  </label></div>
		<div class='col-md-9'><select name='answer_no' class='form-control'>
		<option value=''>Select Answer</option>
		<?
		for($i=1;$i<=$option_no;$i++)
		{
			if($db_value['answer_no']==$i)
			{
				echo"<option value='" . $i ."' selected>Answer ". $i ."</option>";	
		    }
		    else
		    {
				echo"<option value='" . $i ."' >Answer ". $i ."</option>";	
		   
			}
		
		}
		?>
		</select></div></div>
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Clarification [optional]</label></div>
		<div class='col-md-9'><textarea placeholder="Explanation" class='form-control' name='explanation' id='explanation'><?=html_entity_decode($db_value['explanation']);?></textarea>
		</div></div>
		<div class='row form-group'>
		<?if(!$db_value['status'])
		{
			$db_value['status']="Y";
		}
		?>
		<div class='col-md-3'><label class='form-control-label'>Status</label></div><div class='col-md-9'><input type='radio'  name='status' id='status' value='Y' REQUIRED <?if($db_value['status']=='Y'){echo'checked';}?>> Yes &nbsp<input type='radio'  name='status' id='status' value='N' REQUIRED <?if($db_value['status']=='N'){echo'checked';}?>> No &nbsp</div></div>
		
		<div class='row form-group'>
		<div class='col-md-3'></div><div class='col-md-9'><input type="submit" class="btn btn-success" name='submit' value='Save'></div></div>
		
		</form>
		<?
		break;
		
		default:
		    ?>
		    <form method="post" action='<?=basename($_SERVER['PHP_SELF'],".php");?>'  enctype="multipart/form-data">
			<input type='hidden' name="action" value="add-question">
			<input type='hidden' name="id" value="<?=$_REQUEST['id'];?>">
			<input type='hidden' name="all" value="<?=$_REQUEST['all'];?>">
			<input type='hidden' name="limit" value="<?=$_REQUEST['limit'];?>">
			<table class='table table-bordered'>
			<tr><td><input type='text' class='form-control' name='search' placeholder='Enter Search Key'></td><td width=20%><input type='submit' value='Search' name='ok' class='btn btn-success'></td></tr>
			</table>
			</table></form>
			<br>
			<table class='table table-bordered'>
			<thead><th width=5%>Q.No</th><th width=25%>Question</th><th>Category</th><th>Year</th><th>Grade</th><th width=100>Action</th></thead>
			<tbody>
			<?
			$i=0;
			foreach($db_list as $f)
			{
				$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$f['cat_id']));
				$answer_no=getFldValueById("question_answer","answer_no",array("question_id"=>$f['id']));
	            
				$i++;
				echo"<tr style='background:#f5f5f5'><td>" . $f['id'] ."</td><td>" . html_entity_decode(str_replace("&#92;","\\",$f['question']))."</td><td>" . $cat_name."</td><td>" . $f['year']."</td><td><span class='badge'>" . $f['grade']."</span></td><td><i class='fa fa-edit'></i> <a href='?page=add-question&cat_id=" .$f['cat_id']."&id=" .$f['id'] ."&limit=" . $_GET['limit'] ."'>Edit</a><br><i class='fa fa-trash'></i> <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?page=question-list&cat_id=" . $f['cat_id']."&lesson_id=" . $f['lesson_id'] ."&del_id=" .$f['id'] ."&all=1&limit=" . $_GET['limit'] ."';}\" >Delete</a> </td></tr>";
 
				$j=0;
				foreach($answers[$f['id']] as $a)
				{   $j++; 
				    $ans=$alphabet[$j];
				    if($answer_no==$j)
				    {
						 $ans='<span class="badge badge-success">'.$alphabet[$j].'</a>';
					}
					echo"<tr><td width=5>" . $ans."</td><td class='text-align:left' colspan=4>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
				}
			
			}
			?>
			</tbody>
			</table>
		    <?
		    if($total>20)
		    {
				echo"<ul class='pagination'>" .paging($total,20,"&cat_id=" . $_GET['cat_id'] ."&lesson_id=" .$_GET['lesson_id']) . "</ul>";
			}
		break;
	}	
	?>
	</div>	    
	 
</div>	
<script>
<?
if($db_value['cat_id']==73)
{
	echo'$(".story").show();';
}
else
{
	echo'$(".story").hide();';
}
?>


$("#cat_id").change(function()
{
	 
	var id=$(this).val();
	 
	if(id==73)
	{
		 
		$(".story").show();
	} 
});
	
</script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>
 	
 <script>
    CKEDITOR.replace('question', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 120,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
	CKEDITOR.replace('answer1', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('answer2', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('answer3', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('answer4', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
     CKEDITOR.replace('answer5', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
      CKEDITOR.replace('explanation', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
      document.getElementById('ie8-warning').className = 'tip alert';
    }
  </script> 