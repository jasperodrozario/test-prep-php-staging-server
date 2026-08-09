<?php
$data['db_list']=getQuery("questions","*",array("cat_id"=>70,"qtype"=>3));

	     	  
foreach($data['db_list'] as $a)
{
	$answers=getQuery("answers","*",array("question_id"=>$a['id']),"id");
	
	$question_answer=getDataByWhere("question_answer","*",array("question_id"=>$a['id']));
	
	unset($a['id']);
	$a['qtype']=2;
	$question_id=insertTable("questions",$a);
	
	foreach($answers as $d)
	{
	   $r=insertTable("answers",array("question_id"=>$question_id,"answer"=>$d['answer'],"answer_no"=>$d['answer_no']));
	
	}   
	      	 
  	insertTable("question_answer",array("question_id"=>$question_id,"explanation"=>$question_answer['explanation'],"answer_no"=>$question_answer['answer_no']));
       
	
}
 

?>