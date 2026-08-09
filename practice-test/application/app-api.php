<?
header('Content-Type: application/json');
include("configs/config.php"); 
include("configs/common.php"); 
include("configs/license.php"); 
include("configs/array.php");
include("smtpmail.php");


$option_no=4;
 
if($_POST['cat_id']==71)
{
	$option_no=0;
}	
if($_POST['cat_id']==87 || $_POST['cat_id']==80 || $_POST['cat_id']==88 || $_POST['cat_id']==90 || $_POST['cat_id']==79)
{ 
 
	$option_no=5;
}


$setting = getDataByWhere("setting","*",array("id"=>1));
 
$configs=array_merge($configs,$setting);

$response = array();
 
if($_POST['action'])
{
	switch($_POST['action'])
	{
		
		case "register":
		
			$n=getTotalRow("login",array("email"=>$_POST['email']));
   
		    if($n)
		    {
		   
		        $response["status"]=201;
				$response["isSuccess"]="false";
				$response["message"]="This email id already exits!";
			
		    }
		    else
		    {
		     	$activation_code=time();
		      
		        $user_id=insertTable("login",array("email"=>$_POST['email'],"paid"=>0,"status"=>"Y","password"=>$_POST['password'],"activation_status"=>"Y","otp"=>$activation_code,"reg_date"=>date("Y-m-d"),"reg_at"=>"app"));
		          
		        $r=insertTable("student",array("user_id"=>$user_id,"test_id"=>$_POST['test_id'],"first_name"=>$_POST['first_name'],"last_name"=>$_POST['last_name'],"mobile"=>$_POST['mobile']));

		        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Register"));

		        $body=html_entity_decode($mail['template']);
		        $subject=$mail['subject'];
		        $sendpassword=$_POST['password'];
		      
		        $mail_body=str_replace("{name}",$_POST['first_name'] ." ". $_POST['last_name'],$body);
		        $mail_body=str_replace("{activation_code}",$activation_code,$mail_body);
		        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
		        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
		        $mail_body=str_replace("{password}",$sendpassword,$mail_body);
		        $mail_body=html_entity_decode($mail_body);
		        
		        smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  
		       
      		    $response['status']=200;
		  		 
				$response["isSuccess"]=true;
				$authorizeToken=getToken(); 
				$response["userDetails"]=array(
					"userId"=>$user_id,
					"authorizationToken"=>$authorizeToken,
					"userName"=>$_POST['email'],"mobile"=>$_POST['mobile'],
					"name"=>$_POST['first_name'] ." ". $_POST['first_name']
					);
				
				updateTable("login",array("token"=>$authorizeToken),array("user_id"=>$user_id)); 
		        
		        $response["message"]="You are successfully registered with us. Please check your mail.";

		    } 

	    break;
	    
	    case "login":
	        //$exits=getTotalRow("login",array("email"=>$_POST['email'],"password"=>$_POST['password'],"reg_at"=>"app","status"=>'Y',"activation_status"=>'Y'));
           
		    $exits=getTotalRow("login",array("email"=>$_POST['email'],"password"=>$_POST['password'],"status"=>'Y',"activation_status"=>'Y',"reg_at"=>"app"));
            
            //echo getTotalRow("login",array("email"=>$_POST['email'],"password"=>$_POST['password'],"status"=>'Y',"activation_status"=>'Y'),1);
            
            if($exits)
			{
			    $token=getToken();
			    $d=getDataByJoin(array("login as l","student as s"),"l.*,s.first_name,s.last_name,s.mobile,s.test_id",array("l.email"=>$_POST['email'],"l.password"=>$_POST['password']),array("l.user_id"=>"s.user_id"));
			   	
			   	updateTable("login",array("token"=>$token,"last_login"=>date("Y-m-d")),array("user_id"=>$d["user_id"]));	       
		         	
				$response["status"]=200;
				$response["isSuccess"]=true;
				$response["message"]="Login Success";
				
				if($d['paid']==1)
				{
					$d['paid']=true;
				}	
				else
				{
					$d['paid']=false;
				}	
				$response["userDetails"]=array(
				"userId"=>$d["user_id"],
				"authorizationToken"=>$token,
				"userName"=>$d['email'],
				"name"=>$d['first_name'] ." ". $d['last_name'],
				"email"=>$d['email'],"mobile"=>$d['mobile'],"test_id"=>$d['test_id'],
				
				"isUserSubscribed"=>$d['paid']
				
				);
			} 
			else
			{
				
				$response["status"]=201;
				$response["isSuccess"]="false";
				$response["message"]="Login faild";

			}
		
		break;
		
		case "forgot-password":
		
			$exist=getTotalRow("login",array("email"=>$_POST['email']));
			
			if($exist)
			{
			    $d=getDataByWhere("login","*",array("email"=>$_POST['email']));
			 
		        $new_password=getPassword(8);

				updateTable("login",array("password"=>$new_password),array("user_id"=>$d['user_id']));
		      
		        $response['message']="A new password has beeen sent your email id.";
		        
		        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Forgot Password"));

		        $body=html_entity_decode($mail['template']);
		        $subject=$mail['subject'];
		        
		        $mail_body=str_replace("{name}",$name,$body);
		        
		        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
		        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
		        $mail_body=str_replace("{password}",$new_password,$mail_body);
		        $mail_body=html_entity_decode($mail_body);
                
                smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body); 
		  
			}
			else
			{
				$response['message']="Invalid Email Id.";
			}
		
		break;
		
		case "category":
		
		    $list=array();
		    
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		 
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
	            $user_id=$user['user_id'];
	            $paid_member=(int)$user['paid'];
	            $test_id=$user['test_id'];
	       
	        	$categories=getQueryByJoin(array("category as c","test_category as t"),"c.*",array("t.id"=>$test_id,"c.status"=>"Y","c.parent_id"=>0),array("c.cat_id"=>"t.cat_id"),"c.ord");
	        
			    foreach($categories as $c) 
	            {
					if(!$c['icon'])
	    			{
						$c['icon']="uploads/test.png";
					}
					 
				 
					$list[]=array("cat_id"=>$c['cat_id'],"cat_name"=>$c['cat_name'],"icon"=>$configs['vpath'].$c['icon']);
				
				}
			
			}
		    else
            {
				$response['error']="Token does not exist!";

			}
			$response['list']=$list;
		
		break;
		
		case "test-name":
	       
	        $lists=getQuery("test","*",array("status"=>"Y"),"ord");
	       
	        $list=array();
		    foreach($lists as $c) 
            {
				if(!$c['icon'])
    			{
					$c['icon']="uploads/test.png";
				}
			
				$list[]=array("id"=>$c['id'],"name"=>$c['name'],"icon"=>$configs['vpath'].$c['icon']);
			
			}
	
			$response['list']=$list;
		
		break;

		case "lessons":
		
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		 
		    if($exist)
		    {
		    
	            if($_POST['cat_id']==93)
	            {
	            	$lessons=getQuery("lesson","*",array("cat_id in "=>"(47,57)","status"=>"Y"),"ord");
	            
	            }
	            else
	            {
	            	$lessons=getQuery("lesson","*",array("cat_id"=>$_POST['cat_id'],"status"=>"Y"),"ord");
	    
	            }
 				$list=array();
 				foreach($lessons as $c) 
            	{
					if(!$c['icon'])
				    {
						$c['icon']=$configs['vpath']."uploads/test.png";
					}
					$list[]=array("lesson_id"=>$c['lesson_id'],"name"=>$c['name'],"icon"=>$c['icon'],"paid"=>(int)$c['paid'],"ord"=>(int)$c['ord']);
            
				}
				$response['list']=$list;
            }
            else
            {
				$response['error']="Token does not exist!";

			}
		break;
		
		case "all-test":
		
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		 
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
	            $user_id=$user['user_id'];
	            $paid_member=(int)$user['paid'];
	            $test_id=$user['test_id'];
	       
	        	$categories=getQueryByJoin(array("category as c","test_category as t"),"c.*",array("t.id"=>$test_id,"c.status"=>"Y","c.parent_id"=>0),array("c.cat_id"=>"t.cat_id"),"c.ord");
	        
	            
	            foreach($categories as $d)
	            {  
	                $test=array();
	               
	                if($d['cat_id']==93)
	                {
	                	$lessons=getQuery("lesson","*",array("cat_id in "=>"(57,47)","status"=>"Y"),"ord");
	                }	
					else
					{
						$lessons=getQuery("lesson","*",array("cat_id"=>$d['cat_id'],"status"=>"Y"),"ord");
					}	
 				    if($lessons)
 				    {
 				    	foreach($lessons as $c) 
		            	{
							if(!$c['icon'])
						    {
								$c['icon']=$configs['vpath']."uploads/test.png";
							}
							if($d['cat_id']==70)
							{
								$test[]=array("ord"=>$c['ord'],"cat_id"=>$d['cat_id'],"test_only"=>1,"lesson_id"=>$c['lesson_id'],"name"=>$c['name'],"icon"=>$c['icon'],"action"=>"final-test");
		       
							}
							else
							{
								$test[]=array("ord"=>$c['ord'],"cat_id"=>$d['cat_id'],"test_only"=>1,"lesson_id"=>$c['lesson_id'],"name"=>$c['name'],"icon"=>$c['icon'],"action"=>"practice-test");
		       	
							}
				     
						}
					   
 				    }
	 				else
	 				{
	 					$test[]=array("ord"=>$c['ord'],"cat_id"=>$d['cat_id'],"test_only"=>1,"lesson_id"=>0,"name"=>$c['name'],"icon"=>$c['icon'],"action"=>"final-test");
		       
	 				}	
					
					 $response[$d['cat_name']]=$test;
				}
			 
	            
            }
            else
            {
				$response['error']="Token does not exist!";

			}
		break;  
		
		case "lesson-view":
		
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
	            $user_id=$user['user_id'];
	            $paid_member=(int)$user['paid'];
	            $test_id=$user['test_id'];
	            
	            $lessons=getDataByWhere("lesson","*",array("lesson_id"=>$_POST['lesson_id']));
	            
	            $lessons['paid']=(int)$lessons['paid'];
	            $alert=0;
	            
	            if($lessons['paid']>0 && $configs['subscription_type']!="F")
	            {
	            	if(!$paid_member)
	                {
					   $alert=1;
						
					}
				}    
                if(!$alert)
                {
				
	                if($_POST['cat_id']==19)
	                {
						 $response['lesson']=array("cat_id"=>$lessons['cat_id'],"lesson_id"=>$lessons['lesson_id'],"status"=>$lessons['status']);
	    	       
					}
	                else
	                {
						$response['lesson']=array("cat_id"=>$lessons['cat_id'],"lesson_id"=>$lessons['lesson_id'],"overview"=>$configs['vpath'] ."lesson-overview/".$_POST['lesson_id'],"status"=>$lessons['status']);
	    	       
					 
		    	        $n=getTotalRow("file",array("file_type"=>"v","lesson_id"=>$_POST['lesson_id']));
		    	        
		    	        if($n)
		    	        {
							$videos=getQuery("file","*",array("file_type"=>"v","lesson_id"=>$_POST['lesson_id']),'pos');
		                    foreach($videos as $v)
		                    {
								
								$file=$v['file'];
								
								if(substr_count($file,"youtu.be"))
								{	
								    $e=end(explode("/",$file));
								}
								else
								{
									$e1=explode("?v=",$file);
									$e=$e1[1];
								}			
								$picture="http://img.youtube.com/vi/" .$e."/mqdefault.jpg";
			 					       
								$video[]=array("thumb"=>$picture,"title"=>$v['title'],"video"=>$v['file']);
								
							} 
						    
		    		        $response['videos']=$video;
		    		    }
	    		    }
	    		    
		    	    $exist=getTotalRow("contents",array("parent_id"=>$_POST['cat_id']));
		             
					if($_POST['cat_id']==57 || $_POST['cat_id']==47)
					{
						$response['homework_total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_POST['lesson_id'],"qtype"=>2,"cat_id"=>$_POST['cat_id']),array("qa.question_id"=>"q.id"));
			    	 	
			    	    $homework_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.explanation,qa.answer_no",array("q.lesson_id"=>$_POST['lesson_id'],"qtype"=>2,"cat_id"=>$_POST['cat_id']),array("qa.question_id"=>"q.id"),"q.id");
				    }
				    elseif($_POST['cat_id']==93)
				    {
				    	$response['homework_total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_POST['lesson_id'],"cat_id in "=>"(57,47)","qtype"=>2),array("qa.question_id"=>"q.id"));
			    	 	
			    	    $homework_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.explanation,qa.answer_no",array("q.lesson_id"=>$_POST['lesson_id'],"cat_id in "=>"(57,47)","qtype"=>2),array("qa.question_id"=>"q.id"),"q.id");
				   
				    }
				    elseif(in_array($_POST['cat_id'],$data['story_question']))
				    {
				    	$response['homework_total']=getTotalRowsByJoin(array("contents as c","questions as q"),array("q.cat_id"=>(int)$_POST['cat_id'],"q.lesson_id"=>(int)$_POST['lesson_id'],"q.status"=>"Y"),array("q.story_id"=>"c.id"));
			    	  
		    	    	$story_list=getQueryByJoin(array("contents as c","questions as q"),"q.story_id",array("q.cat_id"=>(int)$_POST['cat_id'],"q.lesson_id"=>(int)$_POST['lesson_id'],"q.status"=>"Y"),array("q.story_id"=>"c.id"),'q.ord','','','q.story_id');
		    	 	   
					    $answers=array();
					    $correct_answer=array();
					    $story=array();
					    $homework_list=[];
					    $list=[];$serial_no=0; 
					    foreach($story_list as $a)
					    {
					    	$story=getFldValueById("contents","contents",array("id"=>$a['story_id']));
					    	
					    	$homework_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_POST['lesson_id'],"q.cat_id"=>$_POST['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"),"q.ord",'','',"q.id");
					        $qlist=[];
					        foreach($homework_list as $d)
					        {
								$serial_no++;
								$answers=getQuery("answers","*",array("question_id"=>$d['question_id']),"id",$option_no);
							
								$j=0;$answer_list=array();
								
								foreach($answers as $c)
								{   $j++; 
									
									$ans=$data['alphabet'][$j];
									
									$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
									
								}
								
						          
						        $qlist[]=array("serial_no"=>$serial_no,"id"=>$d['question_id'],"cat_id"=>$d['cat_id'],"question"=>html_entity_decode($d['question']),"question_explanation"=>html_entity_decode($d['explanation']),"correct_answer"=>$data['alphabet'][$d['answer_no']],"answers"=>$answer_list);
							}
							 
							$list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))),"questions"=>$qlist);
					   
						}
						 
						$response['homework_list']=$list;
					 
				    }	
				    else
				    {
				    	if($_POST['cat_id']!=87 && $_POST['cat_id']!=90)
				    	{
				     	
				    		$response['homework_total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_POST['lesson_id'],"cat_id"=>$_POST['cat_id']),array("qa.question_id"=>"q.id"));
			    	 	
			    	   	    $homework_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.explanation,qa.answer_no",array("q.lesson_id"=>$_POST['lesson_id'],"cat_id"=>$_POST['cat_id']),array("qa.question_id"=>"q.id"),"q.id");
				        }
				    
				    
				    }	
	    	        if(!$response['homework_list'] && $_POST['cat_id']!=87 && $_POST['cat_id']!=90)
	    	        {
	    	        	
	    	            
					    $answers=array();
					    $correct_answer=array();
					    $question=array();
					    $serial_no=0;
					    foreach($homework_list as $a)
					    {
							$answers=getQuery("answers","*",array("question_id"=>$a['id']),"id",$option_no);
							$j=0;$answer_list=array();
							
							foreach($answers as $c)
							{   $j++; 
								
								$ans=$data['alphabet'][$j];
								
								$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
								
							}
							$serial_no++;
							
							$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode($a['question']),"question_explanation"=>html_entity_decode($a['explanation']),"correct_answer"=>$data['alphabet'][$a['answer_no']],"answers"=>$answer_list);
						}
						$response['homework_questions']=$question;
				    }
				    
				    
	    	       // deleteData("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
				    $story_limit=5;
				    if($_POST['cat_id']==70)
				    {
				    	 $response['practice_test_total']=30;
					
				    }
				    elseif($_POST['cat_id']==18 || $_POST['cat_id']==78)
				    {
				    	$response['practice_test_total']=25;
				    }
				    elseif($_POST['cat_id']==80 )
				    {
				    	$response['practice_test_total']=40;
				    	$story_limit=10;
				    	 
				    }
				    elseif($_POST['cat_id']==79)
				    {
				    	$response['practice_test_total']=30;
				    	$story_limit=6;
				    }		
	    	        else
	    	        {
	    	        	$response['practice_test_total']=20;
					
	    	        }	
	    	    
	    	        if($_POST['cat_id']==78 || $_POST['cat_id']==79 || $_POST['cat_id']==80)
				    {
				    	 
				    	$response['homework_total']=0;
				    	$response['homework_questions']=[];
				    }		
			        if(in_array($_POST['cat_id'],$data['story_question']))
				    {
				     	
				        $story_list=getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"status"=>"Y"),'story_id',$story_limit);
		    	 	    
					    $answers=array();
					    $correct_answer=array();
					    $story=array();
 
					    $list=[];$serial_no=0;
					    foreach($story_list as $a)
					    {
					    	$story=getFldValueById("contents","contents",array("id"=>$a['story_id']));
					    	
					    	$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_POST['lesson_id'],"q.cat_id"=>$_POST['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"),"q.ord");
					        
					        $qlist=[];
					        foreach($practice_test as $d)
					        {
								$serial_no++;
								$answers=getQuery("answers","*",array("question_id"=>$d['question_id']),"id",$option_no);
							    $j=0;$answer_list=array();
							
								foreach($answers as $c)
								{   $j++; 
									$ans=$data['alphabet'][$j];
									$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
									
								}
								 
								
						        $qlist[]=array("serial_no"=>$serial_no,"id"=>$d['question_id'],"cat_id"=>$d['cat_id'],"lesson_id"=>$d['lesson_id'],"question"=>html_entity_decode($d['question']),"answers"=>$answer_list);
					
							}
							
							$list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($story))))),"practice_test_questions"=>$qlist);
					
					   
						}
						 
						$response['cat_id']=$_POST['cat_id'];
				        $response['lesson_id']=$_POST['lesson_id']; 
						 
			  		    $response['practice_test_questions']=$list;
			  		    $response["status"]=200;
					    $response["isSuccess"]="true";
				    }    
			        elseif($_POST['cat_id']==57 || $_POST['cat_id']==47)
			        { 
						
						$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			           
					}
					elseif($_POST['cat_id']==93)
					{
						$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id in "=>"(57,47)","lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			         
					}	
					else
					{
						$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			      
					}
					if(!$response['practice_test_questions'])
					{
				 	
				    	$answers=array();
				    	$serial_no=0;
				    	$question=array();
				    	
				    	foreach($practice_test as $a)
				    	{
							
							$answers=getQuery("answers","*",array("question_id"=>$a['id']),"id",$option_no);
							$j=0;$answer_list=array();
							
							foreach($answers as $c)
							{   $j++; 
								$ans=$data['alphabet'][$j];
								$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
								
							}
							$serial_no++;
												
							$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$answer_list);
						}
					    $response['cat_id']=$_POST['cat_id'];
				        $response['lesson_id']=$_POST['lesson_id']; 
				  		$response['practice_test_questions']=$question;
				  		$response["status"]=200;
					    $response["isSuccess"]="true";
		    		}	
				}
				else
				{
					$response['message']="Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $". $configs['subscription_fees'] ." USD for ".$configs['validity']." days.";
				    $response["status"]=201;
				    $response["isSuccess"]="false";
				    $response["data"]=[];
				}
	        }
	        else
	        {
				$response['error']="Token does not exist!";
				$response["status"]=201;
				$response["isSuccess"]="false";

			}
		break;
		case "question-diagram":
	 	    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		            $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
	 			    $practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.explanation",array("q.cat_id"=>47,"q.lesson_id"=>201,"q.id in "=>"(2007,2008)"),array("qa.question_id"=>"q.id"),"rand()");
				
			    	$answers=array();
			    	$serial_no=0;
			    	$question=array();
			    	
			    	foreach($practice_test as $a)
			    	{
						
						$answers=getQuery("answers","*",array("question_id"=>$a['id']),"id");
						$j=0;$answer_list=array();
						
						foreach($answers as $c)
						{   $j++; 
							$ans=$data['alphabet'][$j];
							$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
							
						}
						$serial_no++;
											
						$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$answer_list);
					}
				
			  		$response['practice_test_questions']=$question;
 				 
		    }
	        else
	        {
				$response['error']="Token does not exist!";
				$response["status"]=201;
				$response["isSuccess"]="false";

			}
		
		break;
		case "knowledge-update": 
	 	    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
	            $user_id=$user['user_id'];
	            $paid_member=(int)$user['paid'];
	            $test_id=$user['test_id'];
	            
	            $lessons=getDataByWhere("lesson","*",array("lesson_id"=>225));
	            
	            $lessons['paid']=(int)$lessons['paid'];
	            $alert=0;
	            
	            if($lessons['paid']>0 && $configs['subscription_type']!="F")
	            {
	            	if(!$paid_member)
	                {
					   $alert=1;
						
					}
				}    
                if(!$alert)
                {
			        $total=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id"=>71,"q.status"=>"Y"),array("qa.question_id"=>"q.id"));
				   
				    if($total)
				    {
					    $questions=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.explanation",array("q.cat_id"=>71),array("qa.question_id"=>"q.id"),"rand()");
					
				    	$serial_no=0;
				    	$question=array();
						    	
				    	foreach($questions as $a)
				    	{
						
							$serial_no++;
												
							$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"question_explanation"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));
						}
					
				  		$response['questions']=$question;
				  		$response["status"]=200;
					    $response["isSuccess"]="true";
				    		
					}
					else
					{
						$response['message']="Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $". $configs['subscription_fees'] ." USD for ".$configs['validity']." days.";
						$response["status"]=201;
						$response["isSuccess"]="false";
						$response["data"]=[];
					}
				}
				else
				{
					$response['message']="Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $". $configs['subscription_fees'] ." USD for ".$configs['validity']." days.";
				    $response["status"]=201;
				    $response["isSuccess"]="false";
				    $response["data"]=[];
				}	
		    }
	        else
	        {
				$response['error']="Token does not exist!";
				$response["status"]=201;
				$response["isSuccess"]="false";

			}
		
		break;
		
		case "practice-test":
		 		    
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
	            $user_id=$user['user_id'];
	            $paid_member=(int)$user['paid'];
	            $test_id=$user['test_id'];
	            
	            $lessons=getDataByWhere("lesson","*",array("lesson_id"=>$_POST['lesson_id']));
	            
	            $lessons['paid']=(int)$lessons['paid'];
	            
	            $alert=0;
	            
	            if($lessons['paid']>0 && $configs['subscription_type']!="F")
	            {
	            	if(!$paid_member)
	                {
					   $alert=1;
						
					}
				}    
                if(!$alert)
                {
				    #story
				    $story_exist=getTotalRow("contents",array("parent_id"=>$_POST['cat_id']));
		           
					$term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	    		$new_term=(int)$term+1;
    	    
					deleteData("running_exam",array("cat_id"=>$_POST['cat_id'],"term"=>$new_term,"user_id"=>$user_id,"test_id"=>$test_id,"lesson_id"=>(int)$_POST['lesson_id']));
				
					$response['practice_test_total']=20;
				   
			        if($_POST['cat_id']==57 || $_POST['cat_id']==47)
			        { 
						$response['practice_test_total']=20;
		    			$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"q.lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			            
				    
				    }
				    elseif($_POST['cat_id']==93)
				    {
				    	 
				    	$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id in "=>"(47,57)","q.lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			     
				    }
				    elseif(in_array($_POST['cat_id'],$data['story_question']))
				    {
				     	
				     	if($_POST['cat_id']==80 || $_POST['cat_id']==79)
				     	{
				     		if($_POST['cat_id']==80){$response['practice_test_total']=40;}
				     		if($_POST['cat_id']==79){$response['practice_test_total']=30;}
				     			
				     		$story_list=getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"status"=>"Y"),'story_id');
		    	 	        $option_no="";
		    	 	        
				     	}
				     	else
				     	{
				     		$story_list=getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"status"=>"Y"),'story_id','5');
		    	 	    
				     	}	
				        
		    	 	    
					    $answers=array();
					    $correct_answer=array();
					    $story=array();
 
					    $list=[];$serial_no=0;
					    foreach($story_list as $a)
					    {
					    	$story=getFldValueById("contents","contents",array("id"=>$a['story_id']));
					    	
					    	$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_POST['lesson_id'],"q.cat_id"=>$_POST['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"));
					        
					        $qlist=[];
					        foreach($practice_test as $d)
					        {
								$serial_no++;
								$answers=getQuery("answers","*",array("question_id"=>$d['question_id']),"id",$option_no);
							    $j=0;$answer_list=array();
							
								foreach($answers as $c)
								{   $j++; 
									$ans=$data['alphabet'][$j];
									$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
									
								}
								 
								
						        $qlist[]=array("serial_no"=>$serial_no,"id"=>$d['question_id'],"cat_id"=>$d['cat_id'],"lesson_id"=>$d['lesson_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$d['question']))),"answers"=>$answer_list);
					
							}
							
							$list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))),"practice_test_questions"=>$qlist); 
					   
						}
						 
						$response['cat_id']=$_POST['cat_id'];
				        $response['lesson_id']=$_POST['lesson_id']; 
						$response['term']=$new_term;
			  		    $response['practice_test_questions']=$list;
				    }				        
				    else
			        {
						if($_POST['cat_id']==18 || $_POST['cat_id']==78){$response['practice_test_total']=25;}
							
					    $practice_test_total=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id"=>$_POST['cat_id'],"q.lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"));
			            
			            if($practice_test_total)
			            {
			               	$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"q.lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			     
			            }
			            else
			            {
			            	$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"q.lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			     
			         
			            }	
			            
					
					}
					if(!$response['practice_test_questions'])
					{
					 	$answers=array();
				    	$serial_no=0;
				    	$question=array();
				    	
				    	foreach($practice_test as $a)
				    	{
							
							$answers=getQuery("answers","*",array("question_id"=>$a['id']),"id",$option_no);
							$j=0;$answer_list=array();
							
							foreach($answers as $c)
							{   $j++; 
								$ans=$data['alphabet'][$j];
								$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
								
							}
							$serial_no++;
												
							$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$answer_list);
						}
					    $response['cat_id']=$_POST['cat_id'];
					    $response['lesson_id']=$_POST['lesson_id'];
					    $response['term']=$new_term;
				  		$response['practice_test_questions']=$question;
			  		}
	    	    }
				else
				{
					$response['message']="Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $". $configs['subscription_fees'] ." USD for ".$configs['validity']." days.";
				    $response["status"]=201;
				    $response["isSuccess"]="false";
				    $response["data"]=[];
				}
	        }
	        else
	        {
				$response['error']="Token does not exist!";

			}
		break; 
		
		case "afoqt":
		    $list=[];
		    foreach($data['AFOQT'] as $k=>$v)
		    {
		    	$t=0;
		    	foreach($data[$v] as $s=>$n)
		    	{
		    		$t=$t+$n;
		    	}	
		    	
		    	$cat_id=getFldValueById("category","cat_id",array("shortcode"=>$v));
		    	
		    	$list[]=["name"=>$v,"cat_id"=>$cat_id,"total"=>$t];
		    }
		    $response['list']=$list;
		break;
		
		case"final-test":
    	    
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
	            $user_id=$user['user_id'];
	            $paid_member=(int)$user['paid'];
	            $test_id=$user['test_id'];
	            
	            $lessons=getDataByWhere("lesson","*",array("lesson_id"=>$_POST['lesson_id']));
	            
	            $lessons['paid']=(int)$lessons['paid'];
	            $alert=0;
	            
	            if($lessons['paid']>0 && $configs['subscription_type']!="F")
	            {
	            	if(!$paid_member)
	                {
					   $alert=1;
						
					}
				} 
				$flag=0;   
                if(!$alert)
                {
			        $d=getDataByWhere("login","*",array("user_id"=>$user_id));
			        
			        $term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	    		$new_term=(int)$term+1;
    	    
					deleteData("running_exam",array("cat_id"=>$_POST['cat_id'],"term"=>$new_term,"user_id"=>$user_id,"test_id"=>$test_id,"lesson_id"=>(int)$_POST['lesson_id']));
				
			   
			    	//deleteData("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
				
	    	    	//$response['total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id"=>$_POST['cat_id'],"qtype"=>1,"lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"));
		            #story
		            $story_exist=getTotalRow("contents",array("parent_id"=>$_POST['cat_id']));
		            
		            if($story_exist)
				    {
				     	$story_list=getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"status"=>"Y","story_id >"=>0),'story_id','8');
		    	 	    
		    	 	    $answers=array();
					    $correct_answer=array();
					    $story=array();
					    $practice_test=[];
					    $list=[];$serial_no=0;
					    foreach($story_list as $a)
					    {
					    	$story=getFldValueById("contents","contents",array("id"=>$a['story_id']));
					    	
					    	$final_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_POST['lesson_id'],"q.cat_id"=>$_POST['cat_id'],"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"),"q.ord",'4','',"q.id");
					        $qlist=[];
					        foreach($final_test as $d)
					        {
								 
								$answers=getQuery("answers","*",array("question_id"=>$d['question_id']),"id",$option_no);
								$j=0;$answer_list=array();
								
								foreach($answers as $c)
								{   $j++; 
									$ans=$data['alphabet'][$j];
									$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
									
								}
								$serial_no++;
								
						        $qlist[]=array(array("serial_no"=>$serial_no,"id"=>$d['question_id'],"cat_id"=>$d['cat_id'],"lesson_id"=>$d['lesson_id'],"question"=>html_entity_decode($d['question']),"answers"=>$answer_list));
					
							}
							
							$list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))),$qlist);
					
						}
						$response['total']=28; 
						$response['cat_id']=$_POST['cat_id'];
				        $response['lesson_id']=$_POST['lesson_id']; 
						$response['term']=$new_term;
			  		    $response['final_test_questions']=$list;
			  		    $flag=1;
				    }				        
			        elseif($_POST['cat_id']==93)
			        {
			        	$questions=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id in "=>"(47,57)","lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"),"rand()",30);
				        
				        $total=getTotalRowsByByJoin(array("questions as q","question_answer as qa"),array("q.cat_id in "=>"(47,57)","lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"));
				        
			        }	
			        else
			        {
			        	$questions=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"),"rand()",30);
				   	    
				   	    $total=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"));
				   	      
			        }	
				    if($total)    
				    {    
				        if(!$flag)
				        {
				        	   	
					        $response['total']=30;
							 
					   		$answers=array();
					    	$serial_no=0;
					    	$question=array();
					    	
					    	foreach($questions as $a)
					    	{
								
								$answers=getQuery("answers","*",array("question_id"=>$a['id']),"id",4);
								$j=0;$answer_list=array();
								
								foreach($answers as $c)
								{   $j++; 
									$ans=$data['alphabet'][$j];
									$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
									
								}
								$serial_no++;
													
								$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$answer_list);
							}
							
							$response['cat_id']=$_POST['cat_id'];
					    	$response['lesson_id']=$_POST['lesson_id'];
					    	$response['term']=$new_term;
					  		$response['final_test_questions']=$question;
					  		 
				        }
		            }    
			        else
			        {
			        	$response['message']="No question is found!";
				    	$response["status"]=201;
				    	$response["isSuccess"]="false";
				    	$response["data"]=[];
			        	
			        }   
			  		
				}	
	    	    else
				{
					$response['message']="Its paid lesson.Upgrade your membership to get all paid lesson active by paying  $". $configs['subscription_fees'] ." USD for ".$configs['validity']." days.";
				    $response["status"]=201;
				    $response["isSuccess"]="false";
				    $response["data"]=[];
				}
    	    }
    	    else
	        {
				$response['error']="Token does not exist!";

			}
		break;
		case "prediction-test":
		
		    $correct="0";
		    
		    $shortcode=getFldValueById("category","shortcode",array("cat_id"=>$_POST['cat_id']));
		    
		    $at=$data[$shortcode];
		    
		   
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));

		        $user_id=$user['user_id'];
	            $test_id=$user['test_id'];
	            
	            $paid_member=(int)$user['paid'];
			    $correct="0";
	
			    $_POST['term']=getFldValueById("report","max(term)",array("cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"user_id"=>$user_id));
	    	 
				$answer=getDataByWhere("question_answer","*",array("question_id"=>$_POST['question_id']));
				if($answer['answer_no']==$_POST['answer_no']) 
				{
					$correct=1;
				}
			 
				deleteData("prediction_exam",array("question_id"=>$_POST['question_id'],"term"=>((int)$_POST['term']+1),"cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"test_id"=>$test_id,"user_id"=>$user_id));
			 
			    $id=insertTable("prediction_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"answer_no"=>$_POST['answer_no'],"test_id"=>$test_id,"user_id"=>$user_id,"date"=>date("Y-m-d"),"term"=>((int)$_POST['term']+1),"correct"=>(int)$correct));
				
			    $total_answer=getTotalRow("prediction_exam",array("term"=>((int)$_POST['term']+1),"test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id));
			
			    $pending=$_POST['total']-$total_answer;	
			 
				if($id)
				{
				   $response['message']="You have answered successfully.";
				
				}
				else
				{   
				   $response['message']="Error!";
				}
			 
				if($pending>0)
				{
					$response['total']="Still pending " . $pending . " questions to answer!.";
				
				}
				else
				{
					$response['message']="Congratulations! You have answered all questions successfully.You can view the score now.";
					
					$term=getFldValueById("report","max(term)",array("cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"user_id"=>$user_id));
	    	
			    	$cats_total=0;   
		            foreach($at as $key=>$total)
				    {
						
						$cat_id=getFldValueById("category","cat_id",array("shortcode"=>$key));
						
						$cat_score=0; 
						$correct=getTotalRow("prediction_exam",array("correct"=>1,"term"=>((int)$term+1),"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>$cat_id));
					    $wrong=getTotalRow("prediction_exam",array("correct"=>0,"term"=>((int)$term+1),"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>$cat_id));
					    
					    if($correct)
					    {
					    	 //$cat_score=getFldValueById("marks_master","point",array("qno"=>$correct,"cat_id"=>$cat_id));
					    	 
		                     $cat_score=($correct/$total)*100;
		             
					    }	
					   
					    $st+=$cat_score;
					   				 
		                $wrong=$total-$correct;
		                
		                $exam_time=getFldValueById("login","exam_time",array("user_id"=>$user_id));
				    
		                
						insertTable("report",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"lesson_id"=>(int)$cat_id,"total"=>$total,"correct"=>$correct,"wrong"=>$wrong,"score"=>$cat_score,"term"=>((int)$term+1),"date"=>date("Y-m-d"),"time"=>$exam_time));
		                
		                $cats_total++;
					}
 
				    $score=getFldValueById("score_master","score",array("total"=>round($st/$cats_total)));
				   
					insertTable("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"score"=>$score,"date"=>date("Y-m-d"),"term"=>((int)$term+1)));

				}	
		    }	
			else
			{
			 	$response['error']="Token does not exist!";
			}		
		   
		break;
		
		case "prediction-test-questions":
		    
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		       
	            $user_id=$user['user_id'];
	            $paid_member=(int)$user['paid'];
	          
	            $test_id=$user['test_id'];
	            $lessons['paid']=1;
	            $alert=0;
	            
	            if($lessons['paid']>0 && $configs['subscription_type']!="F")
	            {
	            	if(!$paid_member)
	                {
					   $alert=1;
						
					}
				}  
			    $alert=0;
                if(!$alert)
                {
                
	        		$term=getFldValueById("report","max(term)",array("cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"user_id"=>$user_id));
    	   			
    	   			deleteData("prediction_exam",array("user_id"=>$user_id,"test_id"=>$test_id,"term"=>((int)$term+1)));
			        
			        $shortcode=getFldValueById("category","shortcode",array("cat_id"=>$_POST['cat_id']));
		    
		    		$prediction=$data[$shortcode];
		     
		    		foreach($prediction as $key=>$limit)
		    		{
						
						$cat_id=getFldValueById("category","cat_id",array("shortcode"=>$key));
						 
						$val=getFldValueById("category","cat_name",array("shortcode"=>$key));
						
						
						if($cat_id==87 || $cat_id==80 || $cat_id==88 || $cat_id==90 || $cat_id==79)
						{
							$option_no=5;
						}
						else
						{
							$option_no=4;
						}	
						
						#story
		                
		                $lessons_text=[];
				
						if($cat_id==93)
						{
							$lessons_list=getQuery("lesson","lesson_id",array("cat_id in "=>"(47,57)","status"=>"Y"));
						
						}
						elseif($cat_id==80)
						{
							$lessons_list=getQuery("lesson","lesson_id",array("cat_id"=>$cat_id,"status"=>"Y"),"lesson_id",1);
						}	
						else
						{
							$lessons_list=getQuery("lesson","lesson_id",array("cat_id"=>$cat_id,"status"=>"Y"),"rand()");
						
						}	
						
						foreach($lessons_list as $l)
						{
							$lessons_text[]=$l['lesson_id'];
						}
						
						$lessons=implode(",",$lessons_text);
						
						
						$story_question=getTotalRow("questions",array("lesson_id in "=>"(".$lessons.")","cat_id"=>(int)$cat_id,"status"=>"Y","story_id >"=>0));
		    	 	       
		    	
						if($cat_id==93)
						{
							$question_test[$cat_id]=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id in "=>"(".$lessons.")","q.cat_id in "=>"(47,57)"),array("qa.question_id"=>"q.id"),"rand()",$limit);
					   	
						}
						elseif(in_array($cat_id,$data['story_question']))
						{
							$tot=0;$story_list_array=[];
			        
			                $story_list=[];
								
							if($cat_id==103 || $cat_id==102 || $cat_id==79 || $cat_id==80)
			        		{
			        			$lessons_id=$lessons_text[0];
			        			$story_list_array=getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$cat_id,"lesson_id"=>$lessons_id,"status"=>"Y","story_id >"=>0));
	    	 	 
			        		}
							elseif($cat_id==19)
							{
								$story_limit=5;
								if($_POST['cat_id']==105)
								{
									$story_limit=6;
								}
								$story_list_array=getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$cat_id,"status"=>"Y","story_id >"=>0),"rand()",$story_limit);
				    	 	   // echo getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$cat_id,"status"=>"Y","story_id >"=>0),"rand()",5,1);
						        
						    }
						    else
						    {   
						    
						        $story_list1=getQuery("questions","distinct(story_id) as story_id",array("cat_id"=>(int)$cat_id,"status"=>"Y","story_id >"=>0),"rand()");
				    	 	    
				    	 	    foreach($story_list1 as $d)
							    {
									
									$totn=getTotalRow("questions",array("story_id"=>$d['story_id']));
									
							        if(($tot+$totn)==$limit)
							        {
							        	$story_list_array[]=['story_id'=>$d['story_id']];
							        	break;
							        }
							        elseif(($tot+$totn)< $limit)
							        {
							        	$tot=$tot+$totn;
							        	$story_list_array[]=['story_id'=>$d['story_id']];
							        }
							          
								}
							}	
							
						    $story=array();
						    $question_test=[];
						    $list=[];$serial_no=0;
						   // $response['story_list'][$cat_id]=$story_list_array;
						    
						    foreach($story_list_array as $a)
						    {
						    	$story=getFldValueById("contents","contents",array("id"=>$a['story_id']));
						    	
						    	if($cat_id==19)
						    	{
						    		$question_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id in "=>"(".$lessons.")","q.cat_id"=>$cat_id,"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"),'','4');
						       
						    	}
						    	elseif($cat_id==88 || $cat_id==87)
						    	{
						    		$question_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id in "=>"(".$lessons.")","q.cat_id"=>$cat_id,"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"),'','5');
						       
						    	}	
						    	else
						    	{
						    		$question_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id in "=>"(".$lessons.")","q.cat_id"=>$cat_id,"q.story_id"=>$a['story_id']),array("qa.question_id"=>"q.id"));
						       
						    	}	
						    	 
						    	$question=array();
						     
								foreach($question_list as $a)
								{
									$answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
								 	 
									$j=0;$answer_list=array();
								
									foreach($answers as $c)
									{   $j++; 
										$ans=$data['alphabet'][$j];
										$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
										
									}
									$serial_no++;
								 				
									$question[]=array("serial_no"=>$serial_no,"id"=>$a['question_id'],"lesson_id"=>$a['lesson_id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$answer_list);
							    } 
						
						       $list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($story))))),"questions"=>$question);
						       
						       
						    }     
				  		    $test[$val]=array("lesson_name"=>$val,"total"=>$limit,"questions"=>$list);
				  		    
				  		   						
						}				  	 	
						else
						{
							$question_test[$cat_id]=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id in "=>"(".$lessons.")","q.cat_id"=>$cat_id),array("qa.question_id"=>"q.id"),"rand()",$limit);
					   
						}
						if(!in_array($cat_id,$data['story_question']))
						 
						{
						 
				            $answers=array();
					    	$serial_no=0;
					    	$question=array();
					    	$total=0;
							foreach($question_test[$cat_id] as $a)
							{
								$answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
								$j=0;$answer_list=array();
								
								foreach($answers as $c)
								{   $j++; 
									$ans=$data['alphabet'][$j];
									$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
									
								}
								$serial_no++;
													
								$question[]=array("serial_no"=>$serial_no,"id"=>$a['question_id'],"lesson_id"=>$a['lesson_id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$answer_list,"my_answer"=>$my_answer);
							   
							}
							
							$test[$val]=array("lesson_name"=>$val,"total"=>$limit,"questions"=>$question);
					    } 
					    
					}
					
					$response['test']=$test;
			    }		
	    	    else
				{
					$response['message']="Its paid lesson.Upgrade your membership to get all paid lessons active by paying  $". $configs['subscription_fees'] ." USD for ".$configs['validity']." days.";
					$response["status"]=201;
					$response["isSuccess"]="false";
					$response["data"]=[];
				}		
			}
			else
			{
				$response['error']="Token does not exist!";
			}		
				
		break;
		
		case "prediction":
		    
		   
            $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        /*$user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		       
		   
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];
		 
			    $score=array();
	            $st=0; 
            
                $shortcode=getFldValueById("category","shortcode",array("cat_id"=>$_POST['cat_id']));
                
		       
		    	$prediction=$data[$shortcode];
		    	 
		      	$term=getFldValueById("report","max(term)",array("cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"user_id"=>$user_id));
	    	   
	    	    $j=0;
            	foreach($prediction as $key=>$total)
		    	{
					$cat_id=getFldValueById("category","cat_id",array("shortcode"=>$key));
						 
					$val=getFldValueById("category","cat_name",array("shortcode"=>$key));
					
					$cat_score=0; 
					$correct=getTotalRow("prediction_exam",array("correct"=>1,"term"=>((int)$response['term']+1),"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>$cat_id));
			    	
			    	if($correct)
			    	{
			    	 	$cat_score=getFldValueById("marks_master","point",array("qno"=>$correct,"cat_id"=>$cat_id));

			    	}	
			   
			    	$st+=$cat_score;
					$score[]=array("name"=>$val,"score"=>$cat_score);
				
					 
	                $wrong=$total-$correct;
                
					insertTable("report",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"lesson_id"=>$cat_id,"total"=>$total,"correct"=>$correct,"wrong"=>$wrong,"score"=>$cat_score,"term"=>((int)$term+1),"date"=>date("Y-m-d"),"time"=>$exam_time));
					
					$j++;
 
				}*/
				
				
				$response['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
              
				
				$d=getDataByWhere("score_card","*",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id']));
			 
		    	$response['prediction_score']=$d['score'];
		    	
		    	$response['date']=$d['date'];
		    	
		    	$response['time']=$d['time'];
		    	
		    	$response['term']=$d['term'];
		    	
		    	
		    	/*$response['prediction_score']=getFldValueById("score_master","score",array("total"=>round($st/$j)));
		    
				$response['raw_score']=$st;
				
				$score=getFldValueById("score_master","score",array("total"=>round($st/$j)));
		    
		    	$exist=getTotalRow("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>((int)$term+1)));
			
				if(!$exist)
				{
					insertTable("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"score"=>$response['prediction_score'],"date"=>date("Y-m-d"),"time"=>$exam_time,"term"=>((int)$data['term']+1)));
				}*/
			 
			}
			else
			{
				$response['error']="Token does not exist!";
			}
		break;		
			
		case "test":
		
		    $term=0;
		  
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		       
		   
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];
		   
		   
		        $term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	        $new_term=(int)$term+1;
		       
			    $correct="0";
			   
				$answer_no=getFldValueById("question_answer","answer_no",array("question_id"=>$_POST['question_id']));
				
				//echo getFldValueById("question_answer","answer_no",array("question_id"=>$_POST['question_id']),1);
				
				if($answer_no==$_POST['answer_no']) 
				{
					$correct=1;
				}
				
				//deleteData("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>$_POST['lesson_id']));
				
				deleteData("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"test_id"=>$test_id,"term"=>$new_term,));
				 
				$id=insertTable("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"answer_no"=>$_POST['answer_no'],"user_id"=>$user_id,"test_id"=>$test_id,"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id'],"date"=>date("Y-m-d"),"correct"=>(int)$correct));
					
				$total_answer=getTotalRow("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"test_id"=>$test_id,"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id']));
				
				$pending=$_POST['total']-$total_answer;
				 
				$array['list']="";
				
				if($id)
				{
				    $response['message']="You have answered this question successfully.";
				
				}
				else
				{   
				    $response['message']="Error!";
				}
				
				$array['pending']="";
				
				if($pending>0)
				{
					$response['pending_total']="Still pending " . $pending . " questions to answer!.";
				
				}
				else
				{
					$response['message']="Congratulation ! You have successfully answered all questions. Check your score.";
					
					$response['cat_id']=$_POST['cat_id'];
					$response['lesson_id']=$_POST['lesson_id'];
					$response['term']=$new_term;
					$response['total']=$_POST['total'];
					
					//$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
					
					//$response['result_url']=$configs['vpath']."exam/" . $_POST['cat_id']."-" . seo_url($cat_name) ."/" . $_POST['lesson_id'] ."/result";
			        $total=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$new_term,"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
		   
			        $total_correct=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$new_term,"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>1));
			        $total_wrong=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$new_term,"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>0));
			     
			        $total_answer_given=$total_correct+$total_wrong;
			     
			        $total_answer_not_given=$total-$total_answer_given;
			    
			        $score=($total_correct/$total)*100;
			    
			     
			        $exam_time=getFldValueById("login","exam_time",array("user_id"=>$user_id));
			      
			      
			        $report_id=insertTable("report",array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>$new_term,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"total"=>$total,"correct"=>$total_correct,"wrong"=>$total_wrong,"score"=>$score,"date"=>date("Y-m-d"),"time"=>$exam_time));
			        updateTable("running_exam",array("report_id"=>$report_id),array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>$new_term,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"date"=>date("Y-m-d")));
			 
				
				}
			}
	        else
	        {
				$response['error']="Token does not exist!";

			}	
		break;
		
		
		case "results":
		   
		    
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		       
		   
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];		        
		        
		        $reports=getQuery("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id not in "=>"(75,76,86,92,101,102,103,104,105,106)"));
		          
	            $result=array();
	            
	            $array_chk=[];
	            
	            foreach($reports as $d)
	            {
					if(!in_array($d['cat_id'] . "-" . $d['lesson_id'],$array_chk))
					{
				 	
						$array_chk[]=$d['cat_id'] . "-" . $d['lesson_id'];
					
						
						$last_report=getQuery("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$d['cat_id'],"lesson_id"=>$d['lesson_id']),"date desc",1)->fetch_array();
			        
						$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$d['cat_id']));
						
					    $lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$d['lesson_id']));
					    
					    if($last_report['total']==($last_report['correct']+$last_report['wrong']))
					    {
					    	$result[]=array("category"=>$cat_name,"cat_id"=>$d['cat_id'],"lesson_id"=>$d['lesson_id'],"lesson_name"=>$lesson_name,"term"=>$last_report['term'],"date"=>showdate($last_report['date']),"time_taken"=>$last_report['time'],"correct"=>$last_report['correct'],"total_questions"=>$last_report['total'],"wrong"=>$last_report['wrong'],"score"=>$last_report['score']);
					    }
					}   
			  	}
			   	
			  	$response['result']=$result;
			  	
			  	$prediction=[];
			  
			  	$ptest=getQuery("score_card","*",array("user_id"=>$user_id),'cat_id','','','cat_id');
			  	
			  	foreach($ptest as $d)
			  	{
			  		 
			  		$last_rec=getQuery("score_card","*",array("user_id"=>$user_id,"cat_id"=>$d['cat_id']),"date desc","1")->fetch_array();
			  
			  		$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$d['cat_id']));
			  		
			  		$prediction[]=array("category"=>$cat_name,"cat_id"=>$d['cat_id'],"score"=>$last_rec['score'],"date"=>showdate($last_rec['date']),"time_taken"=>$last_rec['time'],"term"=>$last_rec['term']);
			  	}	
			  	
			    $response['prediction-test']=$prediction;
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";

			}	
			
		break;
		
		case "result":
		
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];
		        
		        if($_POST['term'])
		    	{
		    		$new_term=$_POST['term'];
		    	}
		    	else
		    	{
		    		$term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	       	    $new_term=(int)$term; 
		    	}	
		                 	  
				$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
				     
				$lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$_POST['lesson_id']));
				/* 
			    $exits=getTotalRow("report",array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>$new_term,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id']));
			   
			    if(!$exits)
			    {
			    	   
			       $total=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$new_term,"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
		   
			       $total_correct=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$new_term,"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>1));
			       $total_wrong=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$new_term,"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>0));
			    
			       $total_answer_given=$total_correct+$total_wrong;
			     
			       $total_answer_not_given=$total-$total_answer_given;
			    
			       $score=($total_correct/$total)*100;
			      
			       $exam_time=getFldValueById("login","exam_time",array("user_id"=>$user_id));
		 	      
			       $report_id=insertTable("report",array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>$new_term,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"total"=>$total,"correct"=>$total_correct,"wrong"=>$total_wrong,"score"=>$score,"date"=>date("Y-m-d"),"time"=>$exam_time));
			       updateTable("running_exam",array("report_id"=>$report_id),array("test_id"=>$test_id,"user_id"=>$test_id,"term"=>$new_term,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"date"=>date("Y-m-d")));
			      
			    }
			    */
		    
				$report=getDataByWhere("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id']));
				
				$response['report']=array("term"=>$new_term,"cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"lesson_id"=>$_POST['lesson_id'],"lesson_name"=>$lesson_name,"test_id"=>$test_id,"date"=>showdate($report['date']),"time_taken"=>$report['time'],"correct"=>$report['correct'],"total_questions"=>$report['total'],"wrong"=>$report['wrong'],"score"=>$report['score']);
				 
				$qlist=[]; $story=[];
		        $i=0;$list=[];
				$flag=0;$srno=0;
		 		 
		        #story
		        $story_exist=getTotalRow("contents",array("parent_id"=>$_POST['cat_id']));
		        
		        if(in_array($_POST['cat_id'],$data['story_question']))
			    {
			    	$story_list=getQueryByJoin(array("questions as q","running_exam as r"),"q.story_id",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>(int)$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id,"q.story_id >"=>0),array("q.id"=>"r.question_id"),'','','','q.story_id');
	                
	                foreach($story_list as $d)
	                {
	                 	$question_test[$d['story_id']]=getQueryByJoin(array("questions as q","question_answer as qa","running_exam as r"),"q.*,qa.*",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.story_id"=>$d['story_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("qa.question_id"=>"q.id","q.id"=>"r.question_id"),"r.id");
			            
			            $story=getFldValueById("contents","contents",array("id"=>$d['story_id']));
			            $qlist=[];
	                	foreach($question_test[$d['story_id']] as $a)
						{
						    $i++; 
						    $my_answer=getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'],"term"=>$new_term));
						   
						    if(!$my_answer)
							{
								$text="No Answer";
							}
							elseif($my_answer==$a['answer_no'])
							{
								$text='Correct';
							}
							else
							{
								$text='Wrong Answer';
							}
							
							$correct_answer=$data['alphabet'][$a['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
							
							$explanation=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation'])));
						 
					 	    $answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
						    
						    $ans=[];
						    
						    foreach($answers as $c)
						    {
								$ans[]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
							}
						     
						    $qlist[]=array("srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$ans,"myanswer"=>getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'])),"correct_answer"=>$correct_answer,"explanation"=>$explanation);
					    
					    } 
					    $list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))),"questions"=>$qlist);
					    
				    } 
				       
				 	$response['qlist']=$list; 
			    }
			    else	
			    {
			    	$question_test=getQueryByJoin(array("questions as q","question_answer as qa","running_exam as r"),"q.*,qa.*",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("qa.question_id"=>"q.id","q.id"=>"r.question_id"),"r.id");
			    }	
			    
		        if(!$response['qlist'])
		        {	
		            $i=0;
		            foreach($question_test as $a)
					{
					    $i++;
					    $my_answer=getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'],"term"=>$new_term));
					    if(!$my_answer)
						{
							$text="No Answer";
						}
						elseif($my_answer==$a['answer_no'])
						{
							$text='Correct';
						}
						else
						{
							$text='Wrong Answer';
						}
						
						$correct_answer=$data['alphabet'][$a['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
						$explanation=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation'])));
					 
				 	    $answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
					    
					    $ans=[];
					    
					    foreach($answers as $c)
					    {
							$ans[]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
						}
					     
					    $qlist[]=array("srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$ans,"myanswer"=>getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'])),"correct_answer"=>$correct_answer,"explanation"=>$explanation);
				    } 
				 
					$response['qlist']=$qlist;
				}		
				
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";
 
			}	
		break;
		
		case "progress-report":
		    
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.*",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		   
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];
		        
			    $report_data=[];$lesson_list=[];$report_total=[];
			   	
			     
			    switch($_POST['cat_id']) 
			    {
			        case "2000":
				  	
						##chart data
						$response['cat_name']="Overall Report";
									
						$category=getQueryByJoin(array("category as c","test_category as t"),"c.*",array("t.id"=>$test_id,"c.cat_id !"=>71),array("c.cat_id"=>"t.cat_id"),"c.ord");
		                //echo getQueryByJoin(array("category as c","test_category as t"),"c.*",array("t.id"=>$test_id,"c.cat_id !"=>71),array("c.cat_id"=>"t.cat_id"),"c.ord",'',1);
					    $k=0;
					    $score1=0;
					    foreach($category as $c)
					    {
					    	
				  		    $label=$c['cat_name'];
										  		   	
					    	$j=0;$score1=0;
					    	$scrores=getQuery("report","score",array("cat_id"=>$c['cat_id'],"user_id"=>$user_id),"id desc",5);
					    	
					    	foreach($scrores as $s)
					    	{
					    		$j++;
					    		$score1=$score1+$s['score'];
					    	}
					    	if(!is_nan(round($score1/$j,2)))
					        {
					    	   $score_data[]=array("label"=>$label,"score"=>round($score1/$j,2));
						    }  
					    }	
				        $response['chart_data']=$score_data;
				        
				    break;	
				 
				    case "47":
				 
					    $response['lesson_list']=getQuery("lesson","*",array("status"=>"Y","cat_id"=>$_POST['cat_id']));
				        
						foreach ($response['lesson_list'] as $l)
						{
							
							$total=getTotalRow("report",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>$l['lesson_id'],"user_id"=>$user_id));
							
							if($total>5)
							{
							    $limit=($total-5) . ",5";
							}
							else
							{
							    $limit=5;
							}		
						
							$report[$l['lesson_id']]=getQuery("report","*",array("user_id"=>$user_id,"lesson_id"=>$l['lesson_id']),'id',$limit);
							$report_total[$l['lesson_id']]=getTotalRow("report",array("user_id"=>$user_id,"lesson_id"=>$l['lesson_id']));
						}

						$response['report']=$report;  
												
						$response['report_total']=$report_total; 
						
						$response['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
						
						##chart data
						
						$total=getTotalRow("report",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>201,"user_id"=>$user_id));
						
						if($total>5)
						{
						    $limit=($total-5) . ",5";
						}
						else
						{
						    $limit=5;
						}		
				      
					    $row=getQuery("report","*",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>201,"user_id"=>$user_id),"id",$limit);
				        
						foreach($row as $d) 
						{
								
						    if(!is_nan($d['score']))
					        {
							   $score_data[]=array("label"=>showdate($d['date']),"score"=>$d['score']);
						    }	
						}
						
						$response['chart_data']=$score_data;	
		
				    break;
				    case "75":
				    case "76":
				    case "92":
				    case "86":
				     
						$afqt_report=[];
						
						$total_report=getTotalRow("report",array("cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"user_id"=>$user_id),'','term');
					    
					    if($total_report>5)
					    {
					    	$limit=($total_report-5) . ",5";
					    }
					    else
					    {
					    	$limit=5;
					    }		

						$afqt_term=getQuery("report","*",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id),'term',$limit,'','term');
		               
		                //echo  getQuery("report","*",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id),'term',$limit,'1','term');
		               
						foreach($afqt_term as $d) 
						{
							
							$total=getFldValueById("report","sum(total)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$d['term']));
							
							$total_correct=getFldValueById("report","sum(correct)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$d['term']));
							
							$total_score=getFldValueById("report","sum(score)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id !"=>94,"term"=>$d['term']));
							
							$total_wrong=$total-$total_correct;
							
							
							$score=getFldValueById("score_master","score",array("total"=>round($total_score/3)));
							
							
							$exist=getTotalRow("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$d['term']));
				
							if(!$exist)
							{
								insertTable("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"score"=>$score,"date"=>date("Y-m-d"),"term"=>$d['term']));
							}
				 
							$afqt_report[]=array("term"=>$d['term'],"cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"date"=>$d['date'],"correct"=>$total_correct,"total"=>$total,"wrong"=>$total_wrong,"score"=>$score);
							
						}	

						$response['report']=$afqt_report;
						
						$response['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
						
						##chart data
						
						$total=getTotalRow("score_card",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id));
		
						if($total>5)
						{
						    $limit=($total-5) . ",5";
						}
						else
						{
						    $limit=5;
						}		
				        
					    $row=getQuery("score_card","*",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id),"term",$limit);
				        
						foreach($row as $d) 
						{
						 	if(!is_nan($d['score']))
					        {
							   $score_data[]=array("label"=>showdate($d['date']),"score"=>$d['score']);
						    }	
						}
						
						$response['chart_data']=$score_data;	
							
					break; 
					  	
				    default:
				
					    if($_POST['cat_id']==93)
						{
							$lesson_list=getQuery("lesson","*",array("cat_id in "=>"(47,57)","status"=>"Y"),"lesson_id");
							$lesson_total=getTotalRow("lesson",array("cat_id in "=>"(47,57)","status"=>"Y"));
				
						}
						else
						{	
							$lesson_list=getQuery("lesson","*",array("cat_id"=>$_POST['cat_id'],"status"=>"Y"));
							$lesson_total=getTotalRow("lesson",array("cat_id"=>$_POST['cat_id'],"status"=>"Y"));
						}	
						$report=[];
						$response['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id'])); 
						if($lesson_total)
						{
						
							foreach ($lesson_list as $ls)
							{
								$report_list=getQuery("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>$ls['lesson_id']),"term desc",5);
								
							    $lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$ls['lesson_id']));
							    
							    foreach($report_list as $l)
								{ 	
							    
							       $report[]=array("date"=>showdate($l['date']),"total"=>$l['total'],"correct"=>$l['correct'],"wrong"=>$l['wrong'],"score"=>$l['score'],"term"=>$l['term']);
								 
								}	
						 
							}
							$response[$lesson_name]=$report; 
						}
						else
						{
							
							$report_list=getQuery("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id']),"term desc",5);
						
						
						    foreach($report_list as $l)
							{ 	
							    
							    $report[]=array("cat_name"=>$response['cat_name'],"date"=>showdate($l['date']),"total"=>$l['total'],"correct"=>$l['correct'],"wrong"=>$l['wrong'],"score"=>$l['score'],"term"=>$l['term']);
							
									 
							}	
							
						   $response['report']=$report;  
						
						}	
		 
			    }   

		    }
		    else
		    {
				$response['error']="Token does not exist!";
				$response["isSuccess"]=false;
			}
		
		break;
		
		case "view-prediction-result":
		
			$exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.*",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		   
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];
		 
			    $response['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id'])); 
			    
			    if(!$_POST['term'])
			    {
			    	$_POST['term']=getFldValueById("report","max(term)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id']));
				
			    }	
			    
			    $response['date']=getFldValueById("report","date",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
				
			    $response['term']=$_POST['term'];	
			    
			    $shortcode=getFldValueById("category","shortcode",array("cat_id"=>$_POST['cat_id']));
		    
		    	$prediction=$data[$shortcode];
			    
				$afqt_report=[];
				
				# lesson_id used as term
				$j=0;
				
				foreach($prediction as $key=>$total)
				{
					$cat_id=getFldValueById("category","cat_id",array("shortcode"=>$key));
						 
					$val=getFldValueById("category","cat_name",array("shortcode"=>$key));
					
					$score=getFldValueById("report","score",array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>$_POST['term'],"cat_id"=>$_POST['cat_id'],"lesson_id"=>$cat_id));
				    
				   // echo getFldValueById("report","score",array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>$_POST['term'],"cat_id"=>$_POST['cat_id'],"lesson_id"=>$cat_id),1);
				   
				    $afqt_report[$val]=$score;
				
				    $j++;
				}
					
				$total=getFldValueById("report","sum(total)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
						
				$total_correct=getFldValueById("report","sum(correct)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
				
				$total_score=getFldValueById("report","sum(score)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id !"=>94,"term"=>$_POST['term']));
				
				$total_wrong=$total-$total_correct;
							 
				$response['afqt_score']=getFldValueById("score_master","score",array("total"=>round($total_score/$j)));
				
				deleteData("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
				
				insertTable("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"score"=>$response['afqt_score'],"date"=>date("Y-m-d"),"term"=>$_POST['term']));
							
				$response['afqt_report']=$afqt_report;
				
			    foreach($prediction as $key=>$total)
				{
			        $cat_id=getFldValueById("category","cat_id",array("shortcode"=>$key));
						 
					$val=getFldValueById("category","cat_name",array("shortcode"=>$key));
					   
					$story_exist=getTotalRow("contents",array("parent_id"=>$cat_id));
			        
			        if(in_array($cat_id,$data['story_question']))			        
			        {	
						 
						$story_list=getQueryByJoin(array("prediction_exam as c","questions as q"),"q.story_id",array("q.story_id > "=>"0","c.lesson_id"=>$cat_id,"c.term"=>$_POST['term'],"c.user_id"=>$user_id,"c.test_id"=>$test_id),array("c.question_id"=>"q.id"),'q.story_id','5','','q.story_id');
	    	 	        $list=[];$i=0;
					    foreach($story_list as $s)
					    {
					    	$story=getFldValueById("contents","contents",array("id"=>$s['story_id']));
					    	
					    	$question_list=getQueryByJoin(array("questions as q","question_answer as qa","prediction_exam as r"),"q.*,qa.*",array("r.lesson_id"=>$cat_id,"r.cat_id"=>$_POST['cat_id'],"q.story_id"=>$s['story_id'],"r.term"=>$_POST['term'],"r.user_id"=>$user_id,"r.test_id"=>$test_id),array("qa.question_id"=>"q.id","q.id"=>"r.question_id"),"q.ord","4","","q.id");
					          
					    	$qlist=array();
					     
							 
							foreach($question_list as $a)
							{
								$my_answer=getFldValueById("prediction_exam","answer_no",array("cat_id"=>$_POST['cat_id'],"term"=>$_POST['term'],"user_id"=>$user_id,"test_id"=>$test_id,"question_id"=>$a['question_id']));
							    
							   
							    if(!$my_answer)
								{
									$text="No Answer";
								}
								elseif($my_answer==$a['answer_no'])
								{
									$text='Correct';
								}
								else
								{
									$text='Wrong Answer';
								}
								$correct_answer=$data['alphabet'][$a['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
							    $explanation=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation'])));
						 
								$answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
							 	
								$j=0;$ans=[];
							   
								foreach($answers as $c)
								{   $j++; 
									//$ans=$data['alphabet'][$j];
									$ans[]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
									
								}
								 
								$i++;
							    $qlist[]=array("srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$ans,"myanswer"=>$data['alphabet'][$my_answer],"correct_answer"=>$correct_answer,"explanation"=>$explanation);
								  
						     }  
					
					       $list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))),"questions"=>$qlist);
					       
					       
					    }     
			  		     
				  		$response[$val]=$list;
    
				    }
				    else
				    {	
				        $question_test=getQueryByJoin(array("questions as q","question_answer as qa","prediction_exam as r"),"q.*,qa.*",array("r.lesson_id"=>$cat_id,"r.user_id"=>$user_id,"r.test_id"=>$test_id,"r.term"=>$_POST['term']),array("qa.question_id"=>"q.id","q.id"=>"r.question_id"),'','','0','r.id');

				        $qlist=[];
				        $i=0;
						foreach($question_test as $a)
						{
						    $i++;
						    
						    $my_answer=getFldValueById("prediction_exam","answer_no",array("cat_id"=>$_POST['cat_id'],"term"=>$_POST['term'],"user_id"=>$user_id,"test_id"=>$test_id,"question_id"=>$a['question_id']));
						    if(!$my_answer)
							{
								$text="No Answer";
							}
							elseif($my_answer==$a['answer_no'])
							{
								$text='Correct';
							}
							else
							{
								$text='Wrong Answer';
							}
							
							$correct_answer=$data['alphabet'][$a['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
							$explanation=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation'])));
						 
					 	    $answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
						    
						    $ans=[];
						    
						    foreach($answers as $c)
						    {
								$ans[]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
							}
						     
						    $qlist[]=array("srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"answers"=>$ans,"myanswer"=>$data['alphabet'][$my_answer],"correct_answer"=>$correct_answer,"explanation"=>$explanation);
					    } 
                        $response[$val]=$qlist;
				    }
				 	
				 	
				 	
			    }  
		    }		  
		    else
		    {
				$response['error']="Token does not exist!";
				$response["isSuccess"]=false;
			}	  
        break;
        case "edit-profile":
        
           
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
	            
		  		$d=getDataByJoin(array("login as l","student as s"),"l.*,s.*",array("l.user_id"=>$user_id),array("s.user_id"=>"l.user_id"));

				$response["userDetails"]=$d; 
			    
		        $error="";
				if($_POST['email'])
				{
                    
					updateTable("student",$_POST,array("user_id"=>$user_id)); 
		            $response['message']="Your profile has been successfully updated.";
		            $response['status']=200;
		  		 
				    $response["isSuccess"]=true;
				}
				 
		    }
		    else
		    {
				$response['error']="Token does not exist!";
				$response["isSuccess"]=false;
			}
		break;
		
		 
        case "change-password":

         	
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
	           
		    
		        if($_POST['new_password']==$_POST['conf_password']) 
		        {
		            $r1=updateTable("login",array("password"=>$_POST['new_password']),array("user_id"=>$user_id));

		            $response['message']="Password has been successfully changed.";
                           
		            $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Change Password"));

		            $body=html_entity_decode($mail['template']);
		            $subject=$mail['subject'];
 
		              //$activation="<a href='" . $vpath . "activation/" . $activation_code . "' target='_new'>Activate</a>";
 
		            $mail_body=str_replace("{name}",$user['first_name'] ." ". $user['last_name'],$body);
		            //$mail_body=str_replace("{activation}",$activation,$mail_body);
		            $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
		            $mail_body=str_replace("{email}",$user['email'],$mail_body);
		            $mail_body=str_replace("{password}",$_POST['new_password'],$mail_body);
		            $mail_body=html_entity_decode($mail_body);
		                         
		            smtpmailer($d['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  
		              
		        }
		        
		        else
		        {
		          $response['message']="Confirm password does not match with new password!";
		        }  
		   
            }     
		    else
		    {
				$response['error']="Token does not exist!";
			}
        break;
        
        case "banner":
        
            $banner_array=array();
            $banner=getQuery("slider","*",array(),"ord",4);
            foreach($banner as $d)
            {
				 $banner_array[]=array("banner"=>$configs['vpath'].$d['slider'],"link"=>$d['link']);
			}
			$response['slider']=$banner_array;
            
        break;

        case "view-contact_us":
              $name=[];
              $test=getQuery("test","*",array("status"=>"Y"),"ord");
              foreach($test as $t)
              {
              	 $name[$t['id']]=$t['name'];
              }
              $response['test']=$name;
        break;
        case "contact_us":
         
            $_POST['date']=date("Y-m-d");
            $_POST['status']="N";
			insertTable("enquiry",$_POST);         
        	
        	$response['message']="We have received your information. We will contact you soon.";
         				
        	$mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Contact Us"));

        	$body=html_entity_decode($mail['template']);
       		
       		$subject=$mail['subject'];

            $mail_body=str_replace("{name}",$_POST['first_name'] ." ". $_POST['last_name'],$body);
            $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
            $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
            $mail_body=html_entity_decode($mail_body);
             
            smtpmailer($_POST['email'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);

			$subject="New Mail from " . $_POST['first_name'];
			
			$body="<html><body><table width=100%  style='background:#fafafa; width:650px;margin:0;' cellpadding='4' cellspacing='1' border='1' class='table'>";
	   
			unset($_POST['action'],$_POST['ip_address'],$_POST['viewed'],$_POST['status']);
	        $_POST['message']=nl2br($_POST['message']);
	        $_POST['date']=showdate(date("Y-m-d"));
	        
	        $response=array("Name"=>$_POST['first_name']." " .$_POST['last_name'],"Email"=>$_POST['email'],"Phone"=>$_POST['mobile'],"Message"=>$_POST['message'],"Date"=>$_POST['date']);
	        
	        foreach($response as $p=>$v)
	        {
				 $body.="<tr><td>".Ucwords($p) ."</td><td>" .$v ."</td></tr>";
			} 
			$body.="</table></html></body>"; 
		  
            smtpmailer($configs['support_mail'],$_POST['email'], $_POST['fname'],$subject, html_entity_decode($body));
       
        break;
        case "pay":
        
         	
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
               
	            $student=getDataByJoin(array("login as l","student as s"),"l.*,s.*",array("l.user_id"=>$user_id),array("s.user_id"=>"l.user_id"));
	            
	            $_POST["user_id"]=$user_id;
	            $_POST["name"]=$student['first_name'] ." " .$student['last_name'];
	            $_POST["email"]=$student['email'];
	            $_POST["pay_amount"]=$configs['subscription_fees']; 
               
	            $_POST['date']=date("Y-m-d");
	            $_POST['cancel_date']="0000-00-00";
	       
	            $_POST['cancel_subscription']=0;
	            insertTable("creditcard",$_POST); 
	           
	            $ch = curl_init('https://www.asvab-tutoring.com/online-study/mystrip/pay_confirm.php');
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
			    curl_setopt($ch, CURLOPT_POST, true);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
			   
			    $result =curl_exec($ch);
			    curl_close($ch);
			    $result=json_decode($result,TRUE);
                
                if($result['status']=="success")
                {
               	    $response['message']="You have successfully paid the subscription fees. Enjoy all test.";
               	     
                }
                else
                {
               	     $response['error']="Your payment got ".$result['status'].".";
                }	
          
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        
        break;
        
        case "payment-success-ios":
             
            
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
	            $exp_date = date("Y-m-d", strtotime("+" .$configs['validity'] ." day"));
	            
	            updateTable("login",array('paid'=>1,"exp_date"=>$exp_date),array("user_id"=>$user_id));
	            
	            insertTable("order",array('user_id'=>$user_id,"amount"=>$_POST['amount'],"date"=>date("Y-m-d"),"exp_date"=>$exp_date,"transaction_id"=>$_POST['transaction_id']));
	           
	            $response['message']="You have successfully paid the subscription fees. Enjoy all test.";
             
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        break;
        case "payment-success":
             
            
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
	            $exp_date = date("Y-m-d", strtotime("+" .$configs['validity'] ." day"));
	            
	            updateTable("login",array('paid'=>1,"exp_date"=>$exp_date),array("user_id"=>$user_id));
	            
	            insertTable("order",array('user_id'=>$user_id,"amount"=>$_POST['amount'],"date"=>date("Y-m-d"),"exp_date"=>$exp_date,"transaction_id"=>$_POST['transaction_id']));
	           
	            $response['message']="You have successfully paid the subscription fees. Enjoy all test.";
             
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        break;
        case "payment-failure":
        
            $exist=getTotalRow("token",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
        
              $response['message']="Your transaction has been failure. Please try again.";
          
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        break;
        case "savecarddetail":
         	
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
		        
	            $_POST['date']=date("Y-m-d");
	            $_POST['cancel_date']="0000-00-00";
	            $_POST['user_id']=$user_id;
	            $_POST['cancel_subscription']=0;
	            insertTable("creditcard",$_POST);
               
                $response['message']="Thanks for subscription. Your card information saved successfully.Your card will be charged after 3 days. It will not charge if you cencel the subscription before 3 days from the date of subscription.";
          
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        
        break;
        
        case "cancelsubscription":
         
         	$exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
               $user_id=getFldValueById("login","user_id",array("token"=>$_POST['authorizationToken']));
	           $_POST['user_id']=$user_id;
	           $_POST['cancel_subscription']=1;
	           $_POST['cancel_date']=date("Y-m-d");
	           //updateTable("creditcard",$_POST,array("user_id"=>$user_id));
               
               $response['message']="Your subscription has been canceled successfully.";
               $response['isUserSubscribed']=false;
               updateTable("login",array('paid'=>0),array("user_id"=>$user_id));
	           
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        
        break;
        case "terms-and-conditions":
        case "about-us":
        case "privacy-policy":
          
			$response['url']="https://www.asvab-tutoring.com/online-study/information/" .$_POST['action'];
			
        break;
        
 
    }
  
	echo json_encode($response);
	die(); 
 
}
 
    
?>