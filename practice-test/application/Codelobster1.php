	$msg=""; 
	   
	$exit=getTotalRow("running_exam",array("set_id"=>$_POST['set_id']));
	if(!$exit)
	{
		$id=insertTable("running_exam",array("set_id"=>$_POST['set_id'],"user_id"=>$_SESSION['user_id'],"start_time"=>$_POST['start_time']));

	}
	$exit_answer=getTotalRow("exam_temp",array("question_id"=>$_POST['question_id']));
	$srno=($_POST['srno']+1); 
	if($_POST['save_next'])
	{
		
	    if(!$_POST['answer_id'])
	    {
	    	$msg="<font color=red>You have not selected any answer!</font>";
			
		}
		else
		{
			
			if($exit_answer)
			{
			    insertTable("exam_temp",array("question_id"=>$_POST['question_id'],"course_id"=>$_POST['course_id'],"answer_id"=>$_POST['answer_id'],"user_id"=>$_SESSION['user_id']));
			}
			else
			{
				updateTable("exam_temp",array("course_id"=>$_POST['course_id'],"answer_id"=>$_POST['answer_id']),array("question_id"=>$_POST['question_id'],"user_id"=>$_SESSION['user_id']));
			
			}
			$test.="<input type='hidden' name='srno' value='" .($_POST['srno']+1) ."'>";
	    }
	}
	
	$test="";
	
	$db_values=getQueryByJoin(array("questions as q","questions_set_questions as s"),"q.*",array("s.set_id"=>$_POST['set_id'],"q.id >"=>$_POST['question_id']),array("s.question_id"=>"q.id"),"q.id",1);
    
    if($_POST['back'])
    {
    	$srno=($_POST['srno']-1); 
		$db_values=getQueryByJoin(array("questions as q","questions_set_questions as s"),"q.*",array("s.set_id"=>$_POST['set_id'],"q.id <"=>$_POST['question_id']),array("s.question_id"=>"q.id"),"q.id",1);
        $test.="<input type='hidden' name='srno' value='" .($_POST['srno']-1) ."'>";
   
	}
    
	$test.="<table  class='table table-bordered table-striped'>";
	if(count($db_values))
	{
		foreach($db_values as $d)
		{
			$test.="<input type='hidden' name='question_id' value='" . $d['id']."'>"; 
	    
			$test.="<tr ><td width=20>".$srno."</td><td>".html_entity_decode($d['questions']);
			
			$ans=getQuery("answers","*",array("question_id"=>$d['id']));
			$test.="<ul style='padding-left:20px;list-style-type: lower-alpha'>";
			foreach($ans as $v)
			{
				$test.="<li><input type='radio' name='" . $d['question_id'] ."ans' value='" . $v['id']."' > " . html_entity_decode($v['answer']) . "</li>"; 
			}
			$test.="</ul>";
			$test.="</td></tr> ";
		     
        }
        $test.="</table>";
  	    $test.="<div style='padding-left:20px;padding-bottom:10px'><input type='button' class='btn btn-info' id='back' name='back' onClick=\"goNext('" .$d['id'] ."','0','1');\" value='< Back'> <input type='submit' class='btn btn-success' id='submit' name='save_next' value='Save & Next'> <input type='button' onClick=\"goNext('" .$d['id'] ."','1','0');\" class='btn btn-info' id='next' name='next' value='Next'></div>";
	}
	else
	{
		$total_questions=getTotalRow("questions_set_questions",array("set_id"=>$_POST['set_id']));
		$total_answer=getTotalRow("exam_temp",array("set_id"=>$_POST['set_id'],"user_id"=>$_SESSION['user_id']));
		if($total_questions==$total_answer)
		{
			$test.="<tr ><td>You have answerd all.</td></tr>";
			$test.="</table>";
  		    $test.="<div style='padding-left:20px;padding-bottom:10px'><input type='button' onClick=\"goNext('" .$d['id'] ."','0','1');\" class='btn btn-info' id='back' name='back' value=' <Back'> <input type='submit' class='btn btn-success' id='submit' name='save_next' value='Finish'></div>";
	
		}
		else
		{
			$test.="<tr ><td>".($total_questions-$total_answer) ." You have not answerd yet.</td></tr>";
		    $test.="</table>";
  		    $test.="<div style='padding-left:20px;padding-bottom:10px'><input type='submit' class='btn btn-success' id='submit' name='save_next' value='Finish'> <input type='button' class='btn btn-info' id='next' name='next' value='Next' onClick=\"goNext('" .$d['id'] ."','0','1');\"></div>";

		}
			
	}