<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload()" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
<h4 class="modal-title" id="myModalLabel"><?php echo $page_title?></h4>
</div>
<div class="modal-body" style='width:100%'>

<div id='response'></div>


	<?
	switch($_REQUEST['page'])
	{
		case "add-question": 
	 	?>   
	 	<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
		<h5><b>Add Question</b></h5>
		<p><br></p>
		<div id="form-response"></div>

		<form method="post" id="edit_form"  enctype="multipart/form-data">
		<input type='hidden' name="action" value="add-question">
		<input type='hidden' name="id" value="<?=$_REQUEST['id'];?>">
		<input type='hidden' name="all" value="<?=$_REQUEST['all'];?>">
		<input type='hidden' name="limit" value="<?=$_REQUEST['limit'];?>">
        <div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Question For</label></div><div class='col-md-9'><input type='radio'  name='qtype' value='1' REQUIRED <?if($db_value['qtype']=='1'){echo'checked';}?>> Practice Test &nbsp<input type='radio'  name='qtype'  value='2' REQUIRED <?if($db_value['qtype']=='2'){echo'checked';}?>> Homework <input type='radio'  name='qtype'  value='3' REQUIRED <?if($db_value['qtype']=='3'){echo'checked';}?>> Final Test</div></div>
		
		<div class='row form-group' id='options'>
			<div class='col-md-3'><label class='form-control-label'>Category</label></div><div class='col-md-9'>
			
			<?
			echo getDropDown($categories,"cat_id","cat_name",$db_value['cat_id'],"required");
			?>	
				
			</div>
		</div>
		
		<div class='row form-group' id='options'>
		<div class='col-md-3'><label class='form-control-label'>Lesson</label></div><div class='col-md-9'>
		<select class='form-control' name='lesson_id' id='lesson_id'>
		<option value=''>Select --</option>
		<?
		 	
		foreach($lesson_list as $l)
		{
			if($db_value['lesson_id']==$l['lesson_id'])
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
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Question</label></div><div class='col-md-9'><textarea   class='form-control' name='question' id='question'   ><?=html_entity_decode($db_value['question']);?></textarea>

        </div></div>
        <?
        
        if($total_answers)
        {
        	$i=0; 
		    foreach($answers as $ans)
			{	
		        
		        echo"<div class='row form-group'>
				<div class='col-md-3'><label class='form-control-label'>Answer " . ($i+1) ."</label></div>
				<div class='col-md-9'><textarea id='answer".($i+1)."' class='form-control' name='answer".($i+1)."' REQUIRED>" . html_entity_decode($ans['answer']) ."</textarea>
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
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Clarification [optional]</label></div>
		<div class='col-md-9'><textarea placeholder="Explanation" class='form-control' name='explanation' id='explanation'><?=html_entity_decode($db_value['explanation']);?></textarea>
		</div></div>
		<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Status</label></div><div class='col-md-9'><input type='radio'  name='status' id='status' value='Y' REQUIRED <?if($db_value['status']=='Y'){echo'checked';}?>> Yes &nbsp<input type='radio'  name='status' id='status' value='N' REQUIRED <?if($db_value['status']=='N'){echo'checked';}?>> No &nbsp</div></div>
		
		<div class='row form-group'>
		<div class='col-md-3'></div><div class='col-md-9'><input type="submit" class="btn btn-success" name='submit' value='Save'></div></div>
		
		</form>
		<?
		break;
		
		default:
		    ?>
			<table class='table table-bordered'>
			<thead><th width=5%>Q.No</th><th width=25%>Question</th><th>Category</th><th>Lesson</th><th>Q. Type</th><th>Status</th><th width=100>Action</th></thead>
			<tbody>
			<?
			$i=0;
			foreach($db_list as $f)
			{
				$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$f['cat_id']));
				$lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$f['lesson_id']));
				$answer_no=getFldValueById("question_answer","answer_no",array("question_id"=>$f['id']));
	            
				$i++;
				echo"<tr style='background:#f5f5f5'><td>" . $f['id'] ."</td><td>" . html_entity_decode(str_replace("&#92;","\\",$f['question']))."</td><td>" . $cat_name."</td><td>" . $lesson_name."</td><td><span class='badge'>" . $qtype[$f['qtype']]."</span></td><td>" . $f['status']."</td><td><i class='fa fa-edit'></i> <a  href='modal_question_form?page=add-question&cat_id=" .$f['cat_id']."&lesson_id=" . $f['lesson_id'] ."&id=" .$f['id'] ."&all=1&limit=" . $_GET['limit'] ."' data-toggle='modal' data-target='#myModalEdit'>Edit</a><br><i class='fa fa-trash'></i> <a href='#' onClick=\"if(confirm('Are you sure to delete?')){window.location='?page=question-list&cat_id=" . $f['cat_id']."&del_id=" .$f['id'] ."&all=1&limit=" . $_GET['limit'] ."';}\" >Delete</a></td></tr>";
 
				$j=0;
				foreach($answers[$f['id']] as $a)
				{   $j++; 
				    $ans=$alphabet[$j];
				    if($answer_no==$j)
				    {
						 $ans='<span class="badge badge-success">'.$alphabet[$j].'</a>';
					}
					echo"<tr><td width=5>" . $ans."</td><td class='text-align:left' colspan=5>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
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
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML' async></script>
 	
 <script>
    CKEDITOR.replace('question', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 120
    });
	CKEDITOR.replace('answer1', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80
    });
    CKEDITOR.replace('answer2', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80
    });
    CKEDITOR.replace('answer3', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80
    });
    CKEDITOR.replace('answer4', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80
    });
      CKEDITOR.replace('explanation', {
      extraPlugins: 'mathjax',
      mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
      height: 80
    });
    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
      document.getElementById('ie8-warning').className = 'tip alert';
    }
  </script> 
  
  <script>


// AJAX call for autocomplete 
$(document).ready(function()
{

   
    
	$("#edit_form").submit(function(event){
 
 	event.preventDefault();
 	CKEDITOR.instances['question'].updateElement();
 	CKEDITOR.instances['answer1'].updateElement();
 	CKEDITOR.instances['answer2'].updateElement();
 	CKEDITOR.instances['answer4'].updateElement();
 	CKEDITOR.instances['answer3'].updateElement();
 	CKEDITOR.instances['explanation'].updateElement();
  
 	  	
 	 
    $('#response').html("<img src='<?=$configs['vpath'];?>img/loaderIcon.gif'>");
    $.ajax({
   // dataType : "json",	
    type: "POST",
    url: "<?=$configs['vpath'].$configs['admin_dir'];?>/ajax",
    data: new FormData(this),
    contentType: false,       // The content type used when sending data to the server.
	cache: false,             // To unable request pages to be cached
	processData:false   
    })
    .done(function(data){

        alert(data);
        
        $("#response").html(data.msg);
           
      // $('html, body').animate({scrollTop: $("#response").offset().top}, 1000);
  

        if(data.list)
	    {
			$(".list").html(data.list);
		} 
		if(data.url)
	    {
			window.location=data.url;
		} 
		if(data.total)
	    {
			$(".total").html(data.total);
		} 
      
    })
    .fail(function(jqXHR, textStatus, errorThrown) 
    {
            // just in case posting your form failed
           alert(textStatus);
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
}); 


</script>    