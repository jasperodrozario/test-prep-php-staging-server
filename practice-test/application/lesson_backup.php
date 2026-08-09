<?php
include("../configs/autoload.php");
chklogin();

$option_no=4;
 
if($_GET['cat_id']==71)
{
	$option_no=0;
}	 
if($_GET['cat_id']==87 || $_GET['cat_id']==80 || $_GET['cat_id']==88 || $_GET['cat_id']==90 || $_GET['cat_id']==79)
{	
	$option_no=5;
}

$query=array("cat_id"=>$_GET['cat_id']);
$jquery=[];

if(!$_GET['in'])
{
	if($_GET['cat_id']==78 || $_GET['cat_id']==87 ||  $_GET['cat_id']==79 || $_GET['cat_id']==88 || $_GET['cat_id']==90 || $_GET['cat_id']==80)
	{
		$_GET['in']="practice-test";
	}
	else
	{
		$_GET['in']="homework";
	}	
}

if($_GET['lesson_id'])
{
	$data['db_value']=getDataByWhere("lesson","*",array("lesson_id"=>$_GET['lesson_id']));
	
    $query=array_merge($query,array("lesson_id"=>$_GET['lesson_id']));

}	

$data['category']=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));

$data['cat_name']=$data['category']['cat_name'];

   				
switch($_GET['in'])
{
	case"video":

		$data['video_list']=getQuery("file","*",array("file_type"=>"v","lesson_id"=>$_GET['lesson_id']),'pos');

	break;

	case"homework": 
	
	 	$data['homework_total']=getTotalRow("homework_view",array("user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id']));
    	$_GET['home_limit_sr']=0;
    	if($data['homework_total'])
    	{
    	    $home_list=[];
    	    $homelist=getQuery("homework_view","*",array("user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id']));
    	    foreach($homelist as $d)
    	    {
    	        $home_list[]=$d['question_id'];
    	    }
    	    $data['home_list']=$home_list;
    	}	
    	    
    	if($_GET['cat_id'] && $_GET['lesson_id'])
        {
		
			if(in_array($_GET['cat_id'],$data['story_question']))	
			{   
				
			
			    $data['story_list']=getQuery("homework_story","story_id,ord",array("lesson_id"=>$_GET['lesson_id']),"",$configs['home_limit']);
				
				$data['story_total']=getTotalRow("homework_story",array("lesson_id"=>$_GET['lesson_id']));
			
				$_GET['home_limit_sr']=(int)getFldValueById("homework_story","sum(qno)",["lesson_id"=>$_GET['lesson_id'],"ord < "=>$_GET['home_limit']]);
                
                //echo (int)getFldValueById("homework_story","sum(qno)",["lesson_id"=>$_GET['lesson_id'],"ord < "=>$_GET['home_limit']],1);
                
                
                $exist_story_table=getTotalRow("homework_story",["lesson_id"=>$_GET['lesson_id']]);
                
            	
		    	if(!$exist_story_table)
		    	{
		    		$story_list=getQuery("questions","story_id",array("lesson_id"=>$_GET['lesson_id'],"cat_id"=>$_GET['cat_id'],"qtype"=>2),"","","","story_id");
				     
				    $h=0;
					foreach($story_list as $d)
					{
						$h++;
						$qno=getTotalRow("questions",array("lesson_id"=>$_GET['lesson_id'],"story_id"=>$d['story_id'],"qtype"=>2));
				   
						insertTable("homework_story",["story_id"=>$d['story_id'],"qno"=>$qno,"lesson_id"=>$_GET['lesson_id'],"ord"=>$h]);
					}
		    	}
			 	$data['total']=getTotalRow("questions",array("lesson_id"=>(int)$_GET['lesson_id'],"story_id >"=>0));
    	 	 
    	    	
    	 	    $data['story_list']=getQuery("homework_story","story_id,ord",array("lesson_id"=>$_GET['lesson_id']),"",$configs['home_limit']);
				
			    $answers=array();
			    $correct_answer=array();
			    $story=array();
			    $homework_list=[];
			    
			    foreach($data['story_list'] as $a)
			    {
			    	$story[$a['story_id']]=getFldValueById("contents","contents",array("id"=>$a['story_id']));
			    	
			    	$homework_list[$a['story_id']]=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.qtype"=>2,"q.cat_id"=>$_GET['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"),"q.ord",'','',"q.id");
			        
			        foreach($homework_list[$a['story_id']] as $d)
			        {
						$answers[$d['question_id']]=getQuery("answers","*",array("question_id"=>$d['question_id']),"id",$option_no);
				        $correct_answer[$d['question_id']]=getFldValueById("questions","answer",array("question_id"=>$d['question_id'],"answer_no"=>$d['answer_no']));
				
					}
			   
				}
				$data['story']=$story;
				$data['homework_list']=$homework_list;
			  	$data['answers']=$answers;
		  		$data['correct_answer']=$correct_answer; 
		  		
		  		$data['homework_paging']=homework_paging($data['story_total'], $_GET['cat_id'],$_GET['lesson_id'],$_GET['name']);
    	    	
			}
			else
			{
				if($_GET['cat_id']==93)
  	    	    {
				
					$data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
		 	       
		        	$data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()");
			        
			        if($data['total'])
		 	        {
		 	            $data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.status"=>"Y"),array("qa.question_id"=>"q.id"));
		 	       	 
		 	        }  
		 	        
		 	         if($data['total']>30){$data['total']=30;}
		 	         	
		 	        if($data['homework_total']==$data['total'])
			        {
			         	$data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord","1,1","","q.id");
              	    }	
		    	    elseif(!$_GET['home_limit'] && $data['homework_total'] && $data['homework_total']!=$data['total'])
		    	    {
		    	    	
		    	    	$data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",($data['homework_total']+1) . ",1","","q.id");
		    	    	   
                        $_GET['home_limit']=(getFldValueById("homework_view","max(ord)",["user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id']])+1);
                 
                    } 
		    	    else
		    	    {
		    	    	$data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",$configs['home_limit'],"","q.id");
		    	     
		    	    } 
		 	        
			    }
			    else
			    {
			    	$data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
		 	       
		             
			        if(!$data['total'])
		 	        {
		 	            $data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
		 	       
		 	        } 
		 	        
		 	        if($data['homework_total']==$data['total'])
			        {
			         	$data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord","1,1","","q.id");
              	         $flag=1;
              	    }	
		    	    elseif(!$_GET['home_limit'] && $data['homework_total'] && $data['homework_total']!=$data['total'])
		    	    {
		    	    	
		    	    	$data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",($data['homework_total']+1) . ",1","","q.id");
		    	    	   
                        $_GET['home_limit']=(getFldValueById("homework_view","max(ord)",["user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id']])+1);
                        $flag=2;
                    } 
		    	    else
		    	    {
		    	    	$data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",$configs['home_limit'],"","q.id");
		    	        $flag=3;
		    	    } 
			    	
			    }
			    
			    $data['homework_paging']=homework_paging($data['total'], $_GET['cat_id'],$_GET['lesson_id'],$_GET['name']);	    
	             
	    
			}		
		
		}
		 
  	    else
  	    {
  	    	 
  	    	if($_GET['cat_id']==93)
  	    	{
  	    	 
	  	    	$data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
	 	       
	           
		        if(!$data['total'])
	 	        {
	 	            $data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id in "=>"(47,57)","q.status"=>"Y"),array("qa.question_id"=>"q.id"));
	 	       
	 	        }
	 	        if($data['total']>30){$data['total']=30;}
	 	        if($data['homework_total']==$data['total'])
			    {
			         $data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord","1,1","","q.id");
              	}	
		    	elseif(!$_GET['home_limit'] && $data['homework_total'] && $data['homework_total']!=$data['total'])
		    	{
		    	    $data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",($data['homework_total']+1) . ",1","","q.id");
		    	    	   
                    $_GET['home_limit']=(getFldValueById("homework_view","max(ord)",["user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id']])+1);
                } 
		    	else
		    	{
		    	    $data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",$configs['home_limit'],"","q.id");
		    	     
		    	} 
		    }
		    else
		    {
		    	
		    	$data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
	 	       
	             
		        if(!$data['total'])
	 	        {
	 	          	$data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
	 	        
	 	        }
	 	        if($data['total']>30){$data['total']=30;}
	 	        	
	 	        if($data['homework_total']==$data['total'])
			    {
			         $data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord","1,1","","q.id");
              	}	
		    	elseif(!$_GET['home_limit'] && $data['homework_total'] && $data['homework_total']!=$data['total'])
		    	{
		    	    $data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",($data['homework_total']+1) . ",1","","q.id");
		    	    	   
                    $_GET['home_limit']=(getFldValueById("homework_view","max(ord)",["user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id']])+1);
                } 
		    	else
		    	{
		    	    $data['homework_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.qtype"=>2,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"q.ord",$configs['home_limit'],"","q.id");
		    	     
		    	} 
	 	        
		    	$answers=array();
			    $correct_answer=array();
			    foreach($data['homework_list'] as $a)
			    {
					$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id']),"id");
				    $correct_answer[$a['question_id']]=getFldValueById("questions","answer",array("question_id"=>$a['question_id'],"answer_no"=>$a['answer_no']));
				}
				
			  	$data['answers']=$answers;
		  		$data['correct_answer']=$correct_answer; 
		    	
		    }
		    	    
	        $data['homework_paging']=homework_paging($data['total'], $_GET['cat_id'],$_GET['lesson_id'],$_GET['name']);	
	        
	       
	    }
	    //echo "|".$flag;
	    
	    if(!in_array($_GET['cat_id'],$data['story_question']))
	    {
  
		    $answers=array();
		    $correct_answer=array();
		     
		    foreach($data['homework_list'] as $a)
		    {
				//echo getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no,1);

				$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
			   
			    $correct_answer[$a['question_id']]=getFldValueById("answers","answer",array("question_id"=>$a['question_id'],"answer_no"=>$a['answer_no']));
			}
			
		  	$data['answers']=$answers;
	  		$data['correct_answer']=$correct_answer; 
  		 } 
  		 
	    $data['pending']=$data['total']-$data['homework_total'];
	    $green=($data['homework_total']/$data['total'])*100;
	    $blue=($data['pending']/$data['total'])*100;
		$data['style']='';
		if($data['homework_total'] &&  $data['pending'])
		{
		   $data['msg']="<table class='table'><tr><td style='background-color:green;color:#fff' width='" . $green . "%'>".$data['homework_total']."</td><td style='background-color:yellow;color:#111' width='" . $blue . "%'>".$data['pending'] ."</td></tr></table>";
	   	}	
		if($data['homework_total']==$data['total'])
		{
			$data['msg']="<table class='table'><tr><td style='background-color:green;color:#fff' width='" . $green . "%'>All checked.</td></tr></table>";
	        $data['style']="style='color:green'";
	      
		}
		
		if(!$_GET['home_limit']){$_GET['home_limit']=1;} 
    	 
	break;

	case"practice-test":
	    if($_GET['action']=="freshtest")
        {
         		deleteData("running_exam",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"report_id"=>0));
		        deleteData("lastexam",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id']));
		 
        }	
	    
	    if($_GET['lesson_id'] && $_GET['cat_id'])
	    {
	    	$data['term']=getFldValueById("report","max(term)",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"test_id"=>$_SESSION['test_id']));
	    
	        deleteData("running_exam",array("cat_id"=>$_GET['cat_id'],"term"=>((int)$data['term']+1),"test_id"=>$_SESSION['test_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id']));
		 
	    }
	    else
	    {
	    	$data['term']=getFldValueById("report","max(term)",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"test_id"=>$_SESSION['test_id']));
	    
	        deleteData("running_exam",array("cat_id"=>$_GET['cat_id'],"term"=>((int)$data['term']+1),"user_id"=>$_SESSION['user_id'],"test_id"=>$_SESSION['test_id']));
		
		 }		
            
	    $data['total']=20;
	    
	    if($_GET['cat_id'] && $_GET['lesson_id'])
	    {	
		    if(in_array($_GET['cat_id'],$data['story_question']))	
		    {
		    	$story_total=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
				    
			    if($story_total)
			    {
				 	
				 	$story_total=5;  
				 	$qno=4;
				 	$order="rand()";
				
					if($_GET['cat_id']==80){$story_total="";$order=""; $data['total']=40;$qno="";}
		    		if($_GET['cat_id']==79){$story_total="";$order=""; $data['total']=30;$qno="";}
		    
		            /*if($_GET['cat_id']==80 || $_GET['cat_id']==79)
		            {
		            	
		            	$data['story_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.story_id",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"","","","q.story_id");
				        
				    }
		            else
		            {
		             	$data['story_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.story_id",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),$order,$story_total,"","q.story_id");
				   	
		            }*/
		            if($_GET['cat_id']==80 || $_GET['cat_id']==79)
		            {
			            if($_GET['action']=="continue")
						{
							
							$data['story_list']=getQuery("lastexam","q.story_id",array("lesson_id"=>$_GET['lesson_id'],"cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id']));
					  
						}
						else
						{
						   $data['story_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.story_id",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"","","","q.story_id");
				       
						}		
				    }
				    else
				    {
				    	if($_GET['action']=="continue")
						{
							
							$data['story_list']=getQuery("lastexam","q.story_id",array("lesson_id"=>$_GET['lesson_id'],"cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id']));
					  
						}
						else
						{
							$data['story_list']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.story_id",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),$order,$story_total,"","q.story_id");
				   	        //echo getQueryByJoin(array("questions as q","question_answer as qa"),"q.story_id",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),$order,$story_total,"1","q.story_id");
						}	
				    
				    }	
				    $answers=array();
				    $correct_answer=array();
				    $story=array();
				    $test_questions=[];
				    $total=0;
				    foreach($data['story_list'] as $a)
				    {
				    	$story[$a['story_id']]=getFldValueById("contents","contents",array("id"=>$a['story_id']));
				    	
				    	$queston_list_total=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"));
				    	
				    	if($_GET['action']=="continue")
						{
				    		$test_questions[$a['story_id']]=getQueryByJoin(array("questions as q","question_answer as qa","lastexam as l"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id","l.question_id"=>"q.id"),"q.ord",$qno,'',"q.id");
					    }
					    else
					    {
					    	$test_questions[$a['story_id']]=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"),"q.ord",$qno,'',"q.id");
					  
					    }	
				    	foreach($test_questions[$a['story_id']] as $d)
				        {
							 
							$answers[$d['question_id']]=getQuery("answers","*",array("question_id"=>$d['question_id']),"id",$option_no);
					        $correct_answer[$d['question_id']]=getFldValueById("questions","answer",array("question_id"=>$d['question_id'],"answer_no"=>$d['answer_no']));
					        $total++;
					        if($_GET['action']!="continue")
					        {
					        	insertTable("lastexam",["story_id"=>$a['story_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id'],"cat_id"=>$_GET['cat_id'],"question_id"=>$d['question_id']]);
				
					        }
						}
				   
					}
					$data['total']=$total;
					 
					
					$data['story']=$story;
					$data['practicetest']=$test_questions;
				  	$data['answers']=$answers;
			  		$data['correct_answer']=$correct_answer;
			  	 
			  	}
		    }
		    elseif($_GET['cat_id']==18 || $_GET['cat_id']==72 || $_GET['cat_id']==77 ||  $_GET['cat_id']==78 || $_GET['cat_id']==87 || $_GET['cat_id']==89 ||$_GET['cat_id']==90)
	        {
	            $data['total']=20;	
	            
	            $_GET['in']="practice-test";
	            	
	            if($_GET['cat_id']==18 || $_GET['cat_id']==90 || $_GET['cat_id']==77 || $_GET['cat_id']==78 ){$data['total']=25;}
	            
	            if($_GET['cat_id']==72 ){$data['total']=30;}
	            	
	            if($_GET['cat_id']==89 ){$data['total']=40;}
	            	
	            if($_GET['cat_id']==87){$data['total']=100;	}
	            	
	            if($_GET['action']=="continue")
				{	
	            
			       $data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa","lastexam as l"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id","l.question_id"=>"q.id"),"",$data['total']);
			    }
			    else
			    {
			    	$data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()",$data['total']);
			  
			    }	 
		    
		    
		    }
		    elseif($_GET['cat_id']==93)	
		    {
		    	if($_GET['action']=="continue")
				{
		    	   $data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa","lastexam as l"),"q.*,qa.*",array("q.qtype"=>1,"q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.status"=>"Y"),array("qa.question_id"=>"q.id","l.question_id"=>"q.id"),"",$data['total']);
			    }
			    else
			    {
			       $data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.qtype"=>1,"q.lesson_id"=>$_GET['lesson_id'],"q.cat_id in "=>"(47,57)","q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()",$data['total']);
			  
			    }	
		    }
		     
	        else
	        {
	            if($_GET['cat_id']==94){$data['total']=30;}
		      
		        $practicetest_total=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.qtype"=>1,"q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
			            
	            if($practicetest_total)
	            {
	               	if($_GET['action']=="continue")
				    {
	               	
	               	   $data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa","lastexam as l"),"q.*,qa.*",array("q.qtype"=>1,"q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id","l.question_id"=>"q.id"),"",$data['total']);
				    }
				    else
				    {
				    	$data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.qtype"=>1,"q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()",$data['total']);
				   
				    }	 
	            }
	            else
	            {
	            	
	            	if($_GET['action']=="continue")
				    {
	            	   $data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa","lastexam as l"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id","l.question_id"=>"q.id"),"",$data['total']);
				    }
				    else
				    {
				    	$data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()",$data['total']);
				    
				    }	
	            }	
			            
		      
		    }
		}
		else
		{
			if($_GET['action']=="continue")
			{
			    $data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa","lastexam as l"),"q.*,qa.*",array("q.cat_id"=>$_GET['cat_id'],"q.qtype"=>1,"q.status"=>"Y"),array("qa.question_id"=>"q.id","l.question_id"=>"q.id"),"",$data['total']);
		    }
		    else
		    {
		    	$data['practicetest']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.cat_id"=>$_GET['cat_id'],"q.qtype"=>1,"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()",$data['total']);
		  
		    }	
		}
		if(!in_array($_GET['cat_id'],$data['story_question']))	
		{
		 
			$answers=array();

			foreach($data['practicetest'] as $a)
			{
				$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
				if($_GET['action']!="continue")
				{
					insertTable("lastexam",["story_id"=>$a['story_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>$_GET['lesson_id'],"cat_id"=>$_GET['cat_id'],"question_id"=>$d['question_id']]);
				
				}
			}

			$data['answers']=$answers;
	    }
	     $data['alert']="";
    	    $data['last_exam']=[];
    	    
    	    $data["last_exam_total"]=getTotalRow("running_exam",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"report_id"=>0));
		 
    	    if($data["last_exam_total"] && $data["last_exam_total"] < $data['total'])
    	    {
    	    	$last_exam=getQuery("running_exam","*",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"report_id"=>0));
			       
			    $lastexam=[];
			    foreach($last_exam as $d)
			    {
			    	$lastexam[]=$d['question_id'];
			    }	
			    $data['last_exam']=$lastexam;
			  //  $data['alert']="<center><h4>You already answered " .$data["last_exam_total"] ." question(s) of this lesson. </h4><br><a href='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] ."-".$_GET['name'] . "/practice-test/continue' class='btn btn-success'>Continue?</a> or <a href='" . $configs['vpath'] . "lesson/" . $_GET['cat_id'] . "-" . $_GET['lesson_id'] ."-".$_GET['name'] . "/practice-test/freshtest' class='btn btn-warning'>Start Over?</a></center>";
    	    }
    	    else
    	    {
    	    	deleteData("running_exam",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"report_id"=>0));
		        deleteData("lastexam",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id']));
		 
    	    }	
    	    if($_GET['action'])
    	    {
    	    	switch($_GET['action'])
    	    	{
    	    		case "continue":
    	    		   $data['alert']="";
    	    		break;
    	    		case "freshtest":
    	    		  	deleteData("running_exam",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"report_id"=>0));
		                deleteData("lastexam",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id']));
		 
    	    		break;   
    	    	}
    	    	
    	    }	
	break;

	case"final-test":
    
        if($_GET['lesson_id'] && $_GET['cat_id'])
	    {
			$data['term']=getFldValueById("report","max(term)",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id'],"test_id"=>$_SESSION['test_id']));
    		deleteData("running_exam",array("cat_id"=>$_GET['cat_id'],"term"=>((int)$data['term']+1),"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id']));
	    }
	    else
	    {
	    	$data['term']=getFldValueById("report","max(term)",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"test_id"=>$_SESSION['test_id']));
    		deleteData("running_exam",array("cat_id"=>$_GET['cat_id'],"term"=>((int)$data['term']+1),"user_id"=>$_SESSION['user_id']));
	    
	    }		
		   
    	 
		$flag=0;
		
	    if($_GET['lesson_id'] && $_GET['cat_id'])
        {
            $data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>$_GET['lesson_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
	         
	    }
	    else
	    {
	    	$data['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
	         
	    }	
	
        if($data['total']>30)
        {
			$data['total']=30;
		} 
		
		 
				
		if($_GET['lesson_id'] && $_GET['cat_id'])
        {
 		$data['final_test']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_GET['lesson_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()",$data['total']);
	    }
	    else
	    {
	    	$data['final_test']=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.cat_id"=>$_GET['cat_id'],"q.status"=>"Y"),array("qa.question_id"=>"q.id"),"rand()",$data['total']);
	   
	    }	
	   
    	$answers=array();
    	
    	foreach($data['final_test'] as $a)
    	{
			$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
		}
	
  		$data['answers']=$answers;
  		 

    break;

}


$data['timestamp'] = time()+(60*60);
 
//$data['question_list']=getQuery("questions","*",array("cat_id"=>$data['db_value']['cat_id']),"id",15);

echo template($data);
?>