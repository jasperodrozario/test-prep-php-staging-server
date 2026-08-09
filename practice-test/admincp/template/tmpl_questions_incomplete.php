<div id='response'></div>
<?

if($_GET['page']!="all-question-list")
{

?>	 
 <ul class="nav nav-tabs">
<?
    if($_GET['cat_id']==71)
    {
    	?>
    		<li <?if($_GET['page']=="add-question"){echo"class='active'";}?>><a  href="?page=add-question&cat_id=<?=$_GET['cat_id'];?>" class='total'>Add Question</a></li>
	        <li <?if($_GET['page']=="question-list" || $_GET['page']=="all-question-list"){echo"class='active'";}?>><a  href="?page=question-list&cat_id=<?=$_GET['cat_id']?>" class='total'>Question List (<?=$total;?>)</a></li>

    	<?
    }
    else
    {
    	?>
    		<li <?if($_GET['page']=="add-question"){echo"class='active'";}?>><a  href="?page=add-question&cat_id=<?=$_GET['cat_id'];?>&lesson_id=<?=$_GET['lesson_id']?>" class='total'>Add Question</a></li>
	        <li <?if($_GET['page']=="question-list" || $_GET['page']=="all-question-list"){echo"class='active'";}?>><a  href="?page=question-list&cat_id=<?=$_GET['cat_id']?>&lesson_id=<?=$_GET['lesson_id'];?>" class='total'>Question List (<?=$total;?>)</a></li>

    	<?
    }
 }?>  
 </ul> 
	<div class="tab-content" style='overflow-x: auto;'>
	<div   class="tab-pane fade in active" >
	<?
	switch($_GET['page'])
	{
		case "add-question": 
	 	?>   
	 	<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
		<h5><b>Add Question</b></h5>
		<p><br></p>
		<div id="form-response"></div>

		<form method="post" id="form"  enctype="multipart/form-data">
		
		<input type='hidden' name="action" value="add-question">
		<input type='hidden' name="id" value="<?=$_GET['id'];?>">
		<input type='hidden' name="all" value="<?=$_GET['all'];?>">
		<input type='hidden' name="limit" value="<?=$_GET['limit'];?>">
         
         <?
        if($_GET['cat_id']!=71)
        {?>
        	
        	<div class='row form-group'>
			<div class='col-md-3'><label class='form-control-label'>Question For</label></div><div class='col-md-9'><input type='radio'  name='qtype' value='1' REQUIRED <?if($db_value['qtype']=='1'){echo'checked';}?>> Practice Test &nbsp;<input type='radio'  name='qtype'  value='2' REQUIRED <?if($db_value['qtype']=='2'){echo'checked';}?>> Homework <!--<input type='radio'  name='qtype'  value='3' REQUIRED <?if($db_value['qtype']=='3'){echo'checked';}?>> Final Test--></div></div>
		<?
        }
        ?>
        <div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Device</label></div><div class='col-md-9'><input type='radio'  name='platform' value='1' REQUIRED <?if($db_value['platform']=='1'){echo'checked';}?>> All &nbsp;<input type='radio'  name='platform'  value='2' REQUIRED <?if($db_value['platform']=='2'){echo'checked';}?>> Web &nbsp;<input type='radio'  name='platform'  value='3' REQUIRED <?if($db_value['platform']=='3'){echo'checked';}?>> App</div></div>
	
         
		<div class='row form-group' id='options'>
		<div class='col-md-3'><label class='form-control-label'>Category</label></div><div class='col-md-9'>
		
		<?
		echo getDropDown($categories,"cat_id","cat_name",$db_value['cat_id'],"required");
		?>	
			
		</div></div>
		<?
        if(($_GET['cat_id']!=71)  || $_GET['lesson_id'])
        {
         
	    ?>
			<div class='row form-group' id='options'>
			<div class='col-md-3'><label class='form-control-label'>Lesson</label></div> 
			<div class='col-md-9'><select class='form-control' name='lesson_id' id='lesson_id'>
			<option value=''>Select --</option>
			<?
			 	
			foreach($lesson_list as $l)
			{
				if($db_value['lesson_id']==$l['lesson_id'] || $l['lesson_id']==$_GET['lesson_id'])
				{
					echo"<option value='" . $l['lesson_id'] ."' selected>" .$l['name'] ."</option>";	
			    }
			    else
			    {
					echo"<option value='" . $l['lesson_id'] ."' >" .$l['name'] ."</option>";	
			   
				}
			
			}
			?>	
				
			</select></div></div>
			<?
			
		}
	 
	    if($_GET['cat_id']==19 || $_GET['cat_id']==88 || $_GET['cat_id']==79 || $_GET['cat_id']==80)
	    {        
	            
	        	?>
	        		<div class='row form-group'>
					<div class='col-md-3'><label class='form-control-label'>
					<?
					if($_GET['cat_id']==19){echo"Comprehension";}
					if($_GET['cat_id']==88){echo"Hidden Figures";}
					if($_GET['cat_id']==80){echo"Table Reading";}	
					if($_GET['cat_id']==79){echo"Block Counting";}
					 
					?></label></div>
					<div class='col-md-9'> 
                     <select name='story_id' class='form-control' size=1 required>
                     	<option value=''>Select 
                     	<?
						if($_GET['cat_id']==19){echo"Comprehension";}
						if($_GET['cat_id']==88){echo"Hidden Figures";}
						if($_GET['cat_id']==80){echo"Table Reading";}	
						if($_GET['cat_id']==79){echo"Block Counting";}
						 
						?>
                     	</option>
                     	<?
                     	 foreach($story_list as $s)
                     	 {
                     	 	if($db_value['story_id']==$s['id'])
                     	 	{
                     	 		echo"<option value='" . $s['id'] ."' selected>" . $s['title'] ."</option>";
                     	 	}
                     	 	else
                     	 	{
                     	 		echo"<option value='" . $s['id'] ."'>" . $s['title'] ."</option>";
                     	 	}
                     	 	
                     	 }
                     	?>
                     </select>
			        </div></div>
	        	<?
	    }
		?>
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Question</label></div><div class='col-md-9'><textarea   class='form-control' name='question' id='question'   ><?=html_entity_decode($db_value['question']);?></textarea>

	    </div></div>
	       
	        
	    <?	
	    if($_GET['cat_id']!=71)
	    { 
		    if($total_answers)
		    {
		        	$i=0; 
				    foreach($answers as $ans)
					{	
				        
				        echo"<div class='row form-group'>
						<div class='col-md-3'><label class='form-control-label'>Answer " . ($i+1) ."</label></div>
						<div class='col-md-9'><textarea id='answer".($i+1)."' class='form-control ' name='answer[]' REQUIRED>" . html_entity_decode($ans['answer']) ."</textarea>
				        </div></div>";
						 
						$i++;
						if($option_no==$i)break;
					}
				}		
				else
				{       
						$i=0;$answer_html="";
					    for($i=1;$i<=$option_no;$i++)
						{	
					    
					        echo"<div class='row form-group'>
							<div class='col-md-3'><label class='form-control-label'>Answer " . $i."</label></div>
							<div class='col-md-9'><textarea  id='answer".$i."' class='form-control' name='answer[]' REQUIRED>" . html_entity_decode($ans['answer']) ."</textarea>
					        </div></div>";
							 
							 
						}	
				}
		        ?>
		        <div class='row form-group'>
				<div class='col-md-3'><label class='form-control-label'>Correct Answer  </label></div>
				<div class='col-md-9'><select name='answer_no' class='form-control' required>
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
			<?
        }
        ?>	
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Clarification [optional]</label></div>
		<div class='col-md-9'><textarea placeholder="Explanation" class='form-control' name='explanation' id='explanation'><?=html_entity_decode(html_entity_decode($db_value['explanation']));?></textarea>
		</div></div>
			<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Question Order</label></div><div class='col-md-9'><input type="number"  name='ord'   value='<?=$db_value['ord']?>' class='form-control'></div></div>
		
		<div class='row form-group'>
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
			<input type='hidden' name="id" value="<?=$_GET['id'];?>">
			<input type='hidden' name="all" value="<?=$_GET['all'];?>">
			<input type='hidden' name="limit" value="<?=$_GET['limit'];?>">
			<input type='hidden' name="platform" value="<?=$_GET['platform'];?>">
			
			<table class='table table-bordered'>
			<tr><td><input type='text' class='form-control' name='search' placeholder='Enter Search Key'></td><td width=20%><input type='submit' value='Search' name='ok' class='btn btn-success'></td></tr>
			</table>
			</table></form>
			<br>
			<form method="post" action='<?=basename($_SERVER['PHP_SELF'],".php");?>'  enctype="multipart/form-data">
			
			<table class='table table-bordered' width=100%>
			<?
			if($_REQUEST['cat_id']==71)
			{
				?>
					<thead><th width=5%><input type='checkbox' name='all' id='checkAll'></th><th width=5%>Q.No/Ord</th><th width=25%>Question</th><th>Explanation</th><th>Category</th><th>Status</th><th width=100>Action</th></thead>
		
				<?
			}
			else
			{
				?>
					<thead><th width=5%><input type='checkbox' name='all' id='checkAll'></th><th width=5%>Q.No/Ord</th><th width=500>Question</th><th width=15%>Category</th><th width=15%>Lesson</th><th width=10%>Q. Type</th><th width=10%>Platform</th><th width=10%>Status</th><th width=10%>Action</th></thead>
		
				<?
			}				
			?>	
			<tbody>
			<?
			$i=0;
			foreach($db_list as $f)
			{
				$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$f['cat_id']));
				$lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$f['lesson_id']));
				$answer_no=getFldValueById("question_answer","answer_no",array("question_id"=>$f['id']));
	            
				$i++;
				if($_REQUEST['cat_id']==71)
			    {
			    	$explanation=getFldValueById("question_answer","explanation",array("question_id"=>$f['id']));
			    	echo"<tr style='background:#f5f5f5'><td><input type='checkbox' class='checkSingle' name='quid[]' value='" . $f['id'] . "'></td><td>" . $f['id'] ."/" .  $f['ord']."</td><td>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['question'])))."</td><td>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$explanation)))."</td><td>" . $cat_name."</td><td>" . $f['status']."</td><td><i class='fa fa-edit'></i> <a href='?page=add-question&cat_id=" .$f['cat_id']."&lesson_id=" . $f['lesson_id'] ."&id=" .$f['id'] ."&all=1&limit=" . $_GET['limit'] ."'>Edit</a><br><i class='fa fa-trash'></i> <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?page=question-list&cat_id=" . $f['cat_id']."&lesson_id=" . $f['lesson_id'] ."&del_id=" .$f['id'] ."&all=1&limit=" . $_GET['limit'] ."';}\" >Delete</a></td></tr>";
 
			    }
				else
				{
					echo"<tr style='background:#f5f5f5'><td><input type='checkbox' class='checkSingle' name='quid[]' value='" . $f['id'] . "'></td><td>" . $f['id']  ."/" .  $f['ord']."</td><td>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['question'])))."</td><td>" . $cat_name."</td><td>" . $lesson_name."</td><td><span class='badge'>" . $qtype[$f['qtype']]."</span></td><td>" . ucwords($platform[(int)$f['platform']])."</td><td>" . $f['status']."</td><td><i class='fa fa-edit'></i> <a href='?page=add-question&cat_id=" .$f['cat_id']."&lesson_id=" . $f['lesson_id'] ."&id=" .$f['id'] ."&all=1&limit=" . $_GET['limit'] ."'>Edit</a><br><i class='fa fa-trash'></i> <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?page=question-list&cat_id=" . $f['cat_id']."&lesson_id=" . $f['lesson_id'] ."&del_id=" .$f['id'] ."&all=1&limit=" . $_GET['limit'] ."';}\" >Delete</a></td></tr>";
 
					$j=0;
					foreach($answers[$f['id']] as $a)
					{   $j++; 
						$ans=$alphabet[$j];
						if($answer_no==$j)
						{
							 $ans='<span class="badge badge-success">'.$alphabet[$j].'</a>';
						}
						echo"<tr><td>".$a['lesson_id'] . "</td><td width=5>" . $ans."</td><td class='text-align:left' colspan=6>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
					}
			    }
			}
			?>
			</tbody>
			</table>
			<div><input type='submit' class='btn btn-danger' value='Delete All Checked Questions' name='Delete'></div>
		</form>
		    <?
		    if($total>20)
		    {
				echo"<ul class='pagination'>" .paging($total,20,"cat_id=" . $_GET['cat_id'] ."&lesson_id=" .$_GET['lesson_id']) . "</ul>";
			}
		break;
		 
	}	
	?>
	</div>	    
	 
</div>	
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
    
    <?
    if($_GET['cat_id']==87 || $_GET['cat_id']==88 || $_GET['cat_id']==80 || $_GET['cat_id']==79 || $_GET['cat_id']==90)
    {
     ?>
      CKEDITOR.replace('answer5', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80,
      filebrowserUploadUrl: 'application/ck_upload.php',
      filebrowserUploadMethod: 'form'
    });
     <?	
    }
    ?>	
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
    <script>
	$('#checkAll').on('change', function(){
    if($(this).attr('checked')){
        $('input[type=checkbox]').attr('checked',true);
    }
    else{
        $('input[type=checkbox]').removeAttr('checked');
    }
});
  </script> 