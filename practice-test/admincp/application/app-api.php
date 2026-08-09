<?
header('Content-Type: application/json');
include("configs/config.php"); 
include("configs/common.php"); 
include("configs/license.php"); 
include("configs/array.php");
include("smtpmail.php");


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
		      
		        $user_id=insertTable("login",array("email"=>$_POST['email'],"final_exam"=>0,"prediction_test"=>0,"status"=>"N","password"=>md5($_POST['password']),"activation_status"=>"N","otp"=>$activation_code,"reg_date"=>date("Y-m-d"),"reg_at"=>"app"));
		      
		              
		        $r=insertTable("student",array("user_id"=>$user_id,"first_name"=>$_POST['first_name'],"last_name"=>$_POST['last_name'],"mobile"=>$_POST['mobile']));

		        
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
					"userName"=>$_POST['email'],
					"name"=>$_POST['first_name'] ." ". $_POST['first_name']
					);
				 
				deleteData("token",array("user_id"=>$user_id)); 
                
				insertTable("token",array("token"=>$authorizeToken,"date"=>date("Y-m-d"),"user_id"=>$user_id)); 
		        
		        $response["message"]="You are successfully registered with us. Please check your mail.";
		  		
		    
		    } 
		    
		    
	    break;
	    case "login":
	       
		    $exits=getTotalRow("login",array("email"=>$_POST['email'],"password"=>md5($_POST['password']),"status"=>'Y',"activation_status"=>'Y'));
            if($exits)
			{
			    $d=getDataByJoin(array("login as l","student as s"),"l.*,s.first_name,s.last_name",array("l.email"=>$_POST['email'],"l.password"=>md5($_POST['password'])),array("l.user_id"=>"s.user_id"));
			   		       
		        deleteData("token",array("user_id"=>$d["user_id"]));
	 			
	 			$token=getToken();
			    
	 			insertTable("token",array("token"=>$token,"user_id"=>$d["user_id"],"date"=>date("Y-m-d")));
	 			
				$response["status"]=200;
				$response["isSuccess"]=true;
				$response["message"]="Login Success";
				
				$response["userDetails"]=array(
				"userId"=>$d["user_id"],
				"authorizationToken"=>$token,
				"userName"=>$d['email'],
				"name"=>$d['first_name'] ." ". $d['last_name'],
				"email"=>$d['email']
				
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
			 
			if($exists)
			{
			    $d=getDataByWhere("login","*",array("email"=>$_POST['email1']));
			 
		        $new_password=getPassword(8);

				updateTable("login",array("password"=>md5($new_password)),array("user_id"=>$d['user_id']));
		      
		        $response['message']="A new password has beeen sent your email id";
		        
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
		 
		case "advance-search":
		
		    $search_array=array("t.exp_date >"=>date("Y-m-d"),"t.status"=>"Y");
            $param=array(); 
            if($_POST['type'])
            {
                $search_array=array_merge($search_array,array("t.cat_id"=>$_POST['type']));
                $param['type']=$_POST['type'];
            }  

            if($_POST['cat_id'])
            {
              $search_array=array_merge($search_array,array("t.sub_cat_id"=>$_POST['cat_id']));
              $param['sub_cat_id']=$_POST['cat_id'];
            }  

            if($_POST['country_id'])
            {
              $search_array=array_merge($search_array,array("l.country_id"=>$_POST['country_id']));
              $param['country_id']=$_POST['country_id'];
            }  

            if($_POST['search'])
            {
               $param['title rlike']=$_POST['search'];
               $search_array=array_merge($search_array,array("t.title rlike "=>$_POST['search'],"[or] tid rlike"=>$_POST['search'],"[or] tender_description rlike"=>$_POST['search']));
            }  
          
           
            $response['total']=getTotalRowsByJoin(array("tender as t","login as l"),$search_array,array("t.user_id"=>"l.user_id"));
            if(!$response['total'])
            {
				$response['message']="No tendor found!";
			}
			else
			{
				$response['message']=$response['total'] ." tendor found!";
			}
             
            $response['result']=getRowsByJoin(array("tender as t","login as l"),"t.*",$search_array,array("t.user_id"=>"l.user_id"),"t.tid desc");

		break;
		
		
		case "category":
	       
	        $categories=getQuery("category","*",array("parent_id"=>0,"status"=>"Y"),"ord");
	        
	        $list=array();
		    foreach($categories as $c) 
            {
				$list[]=array("cat_id"=>$c['cat_id'],"cat_name"=>$c['cat_name'],"icon"=>$configs['vpath'].$c['icon']);
			
			}
			$response['list']=$list;
		
		break;
		case "lessons":
		
		    $exist=getTotalRow("token",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		    
	            $lessons=getQuery("lesson","*",array("cat_id"=>$_POST['cat_id']),"lesson_id");
 				
 				$list=array();
 				foreach($lessons as $c) 
            	{
					if(!$c['icon'])
				    {
						$c['icon']=$configs['vpath']."uploads/test.png";
					}
					$list[]=array("lesson_id"=>$c['lesson_id'],"name"=>$c['name'],"icon"=>$c['icon']));
            
				}
				$response['list']=$list;
            }
            else
            {
				$response['error']="Token does not exist!";

			}
		break; 
		
		case "lesson-view":
		
		    $exist=getTotalRow("token",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user_id=getFldValueById("token","user_id",array("token"=>$_POST['authorizationToken']));
		        
	            $lessons=getDataByWhere("lesson","*",array("lesson_id"=>$_POST['lesson_id']));
 
                $response['lesson']=array("cat_id"=>$lessons['cat_id'],"lesson_id"=>$lessons['lesson_id'],"details"=>html_entity_decode($lessons['details']),"feature"=>html_entity_decode($lessons['feature']),"status"=>$lessons['status']);
    	        
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
    		    
	    	    if($_POST['cat_id']<47)
	    	    {
					$response['homework_total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_POST['lesson_id'],"cat_id"=>$_POST['cat_id']),array("qa.question_id"=>"q.id"));
		    	 	
		    	    $homework_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_POST['lesson_id'],"cat_id"=>$_POST['cat_id']),array("qa.question_id"=>"q.id"),"q.id");
			   
				}
				else
				{
					$response['homework_total']=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.lesson_id"=>(int)$_POST['lesson_id'],"cat_id"=>$_POST['cat_id'],"qtype"=>2),array("qa.question_id"=>"q.id"));
		    	 	
		    	    $homework_list=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.lesson_id"=>$_POST['lesson_id'],"cat_id"=>$_POST['cat_id'],"qtype"=>2),array("qa.question_id"=>"q.id"),"q.id");
			    }
    	 
			    $answers=array();
			    $correct_answer=array();
			    $question=array();
			    $serial_no=0;
			    foreach($homework_list as $a)
			    {
					$answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id");
					$j=0;$answer_list=array();
					
					foreach($answers as $c)
					{   $j++; 
						$ans=$data['alphabet'][$j];
						$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
						
					}
					$serial_no++;
					
					$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode($a['question']),"question_explanation"=>html_entity_decode($a['question_explanation']),
					
					"answers"=>$answer_list,
				    "correct_answer"=>$data['alphabet'][$a['answer_no']]);
				}
				$response['homework_questions']=$question;
			  
    	        deleteData("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
			
    	          
		        if($_POST['cat_id']==19)
		        {
		        	$response['practice_test_total']=11;
		        }
		        elseif($_POST['cat_id']==18)
		        {
		        	$response['practice_test_total']=30;
		        }
		        else
		        {
					$response['practice_test_total']=15;
				}
		        if($response['cat_id']<47)
	    	    {
					$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
		        }
		        else
		        {
					$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.cat_id"=>$_POST['cat_id'],"qtype"=>1,"lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
		     
				}
		    	$answers=array();
		    	$serial_no=0;
		    	$question=array();
		    	
		    	foreach($practice_test as $a)
		    	{
					
					$answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id");
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

			}
		break; 
		
		case"test":
		
		    $exist=getTotalRow("token",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user_id=getFldValueById("token","user_id",array("token"=>$_POST['authorizationToken']));
			    $correct="0";
			   
				$answer=getDataByWhere("question_answer","*",array("question_id"=>$_POST['question_id']));
				
				if($answer['answer_no']==$_POST['answer_no']) 
				{
					$correct=1;
				}
				
				deleteData("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
				 
				$id=insertTable("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"answer_no"=>$_POST['answer_no'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"date"=>date("Y-m-d"),"correct"=>(int)$correct));
					
				$total_answer=getTotalRow("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
				
				$pending=$_POST['total']-$total_answer;
				 
				$array['list']="";
				
				if($id)
				{
				    $response['message']="You have answered successfully.";
				
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
					$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
					$response['result_url']=$configs['vpath']."exam/" . $_POST['cat_id']."-" . seo_url($cat_name) ."/" . $_POST['lesson_id'] ."/result";
			
				}
			}
	        else
	        {
				$response['error']="Token does not exist!";

			}	
		break;
		case "result":
		   
		    if($_POST['cat_id']==70)
		    {
				 $total=20;
			}
		    elseif($_POST['cat_id']==19)
	        {
	        	$total=11;
	        }
	        elseif($_POST['cat_id']==18)
	        {
	        	$total=30;
	        }
	        else
	        {
				$total=15;
			}
		    
		    $response['total']=$total; 
		     
		    $response['lesson_name']=getFldValueById("lesson","name",array("lesson_id"=>$_POST['lesson_id']));
		    
		    $response['total_correct']=getTotalRow("running_exam",array("cat_id"=>$$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>1));
		    $response['total_wrong']=getTotalRow("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>0));
		    
		    $response['total_answer_given']=$response['total_correct']+$response['total_wrong'];
		     
		    $response['total_answer_not_given']=$response['total']-$response['total_answer_given'];
		    
		    
			$response['question_test']=getQueryByJoin(array("questions as q","question_answer as qa","running_exam as r"),"q.*,qa.*",array("q.lesson_id"=>(int)$_GET['lesson_id'],"q.cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id']),array("qa.question_id"=>"q.id","q.id"=>"r.question_id"),'','','','r.id');
            $answers=array();
            
            
			$my_answers=array();
	        $answers=array();
	    
			foreach($data['question_test'] as $a)
			{
			 
				$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id']),"id");
			 
			}
		    
			$data['answers']=$answers;
			$i=0;
			foreach($data['question_test'] as $q)
		    {
				$i++;
				$my_answers[$q['question_id']]=getFldValueById("running_exam","answer_no",array("user_id"=>$_SESSION['user_id'],"question_id"=>$q['question_id']));
			    
			}
			
			$data['my_answers']=$my_answers;
			
			deleteData("exam_result",array("user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_GET['lesson_id']));
			
			insertTable("exam_result",array("user_id"=>$_SESSION['user_id'],"cat_id"=>$_GET['cat_id'],"total"=>$data['total'],"marked_answer"=>count($my_answers),"lesson_id"=>(int)$_GET['lesson_id'],"not_answered"=>$not_answered,"correct"=>$data['total_correct'],"wrong"=>$data['total_wrong'],"score"=>$data['score'],"date"=>date("Y-m-d")));
	  		
		break;
		case "feedback-post":
		
		    $exist=getTotalRow("token",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		
			    $user_id=getFldValueById("token","user_id",array("token"=>$_POST['authorizationToken'])); 
			    
			    $response=getDataByWhere("tender","*",array("tid"=>$_POST['tid'],"user_id"=>$user_id));
				  

				$response1=getDataByWhere("bid_select","*",array("tid"=>$_POST['tid'],"accept"=>"Y"));
                if($response1['user_id'])
                {
					
				
					$supplier=getFldNameById("supplier","company_name",array("user_id"=>$response1['user_id']));

					$sql_feedback = insertTable("feedback",
			        array("sender_id"=>$user_id, 
			              "recever_id"=>$response1['user_id'],
			              "tid"=>$_POST['tid'],
			             
			              "remark"=>$_POST['remark'],
			              "date"=>date("Y-m-d")
			            )
			          );

			        $name=getName($user_id);
			        $to=getFldNameById("login","email",array("user_id"=>$response1['user_id']));
			           
			        $template=getDataByWhere("mailsetup","*",array("mail_type"=>"Feedback"));

			        $subject=$template['subject'];
			        $message=html_entity_decode($template['template']);

			        $body=str_replace("{tid}",$_POST['tid'],$template);
			        $body=str_replace("{name}",$name,$template);
			        $body=str_replace("{title}",$response['title'],$template);
			        $body=str_replace("{dotcom}",$dotcom,$template);
			               
			        $response['message']="Your feedback has been successfully submitted.";
			        smtpmailer($to, $setting['support_mail'], $dotcom, $subject, $body);
			 		getNotify($response1['user_id'],htmlentities("A feedback you have received from '" . $name ."' for  tender : '" . $response['title'] . "'"));
                }
                else
                {
					$response['error']="Buyer has not selected any supplier for this tendor yet!";
				}
				
			}
			else
			{
				 $response['error']="Token does not exist!";
			      
			}	
				

		break;
		
		
		
		
		
        
		break;
		
       
       

        case "view-profile":
        
            $exist=getTotalRow("token",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {

	            $response['user_id']=getFldNameById("token","user_id",array("token"=>$_POST['authorizationToken']));
	            $response['user_type']=getFldNameById("login","user_type",array("user_id"=>$response['user_id']));

		        if($response['user_type']=="buyer")
		        {	
				    $response['data']=getDataByJoin(array("buyer as b","login as l"),"b.*,l.email,l.reg_date",array("l.user_id"=>$response['user_id']),array("b.user_id"=>"l.user_id"));
				    $table="buyer";
				}
			    else
			    {	
				    $response['data']=getDataByJoin(array("supplier as b","login as l"),"b.*,l.email,l.reg_date",array("l.user_id"=>$response['user_id']),array("b.user_id"=>"l.user_id"));
				    $table="supplier";
				}  

				if($response['user_type']=="supplier")
	            {
	            	$ct=getRowsByJoin(array("category as c","supplier_cat as s"),"c.cat_name",array("s.user_id"=>$response['user_id']),array("c.cat_id"=>"s.cat_id"),"c.cat_name");
	                $txt=array();
	                foreach($ct as $cat)
	                {
	                   $txt[]=$cat['cat_name']; 
	                } 
	                $response['supplier_category']=implode(",",$txt);
	            }
            }
            else
            {
				$response['error']="Token does not exist!";
			}
        break;

        case "edit-profile":
        
            $exist=getTotalRow("token",array("token"=>$_GET['authorizationToken']));
		
		    if($exist)
		    {

	            $response['user_id']=getFldNameById("token","user_id",array("token"=>$_POST['authorizationToken']));
	            
		        $response=array();$error="";
				if($response['user_id'])
				{

					updateTable("student",$_POST,array("user_id"=>$user_id)); 
		            $response['message']="Your profile successfully updated";
				}
				 
		    }
		    else
		    {
				$response['error']="Token does not exist!";
			}
		break;
		
		case "update-profile":  
		 	    
			$response['user_id']=getFldNameById("token","user_id",array("token"=>$_POST['authorizationToken']));
            $response['user_type']=getFldNameById("login","user_type",array("user_id"=>$response['user_id']));

	        $query=array("fname"=>$_POST['fname'],"lname"=>$_POST['lname'], 
			"work_title"=>$_POST['work_title'], "company_name"=>$_POST['company_name'],"address"=>$_POST['address'],"company_desc"=>htmlentities($_POST['company_desc']),"country_id"=>(int)$_POST['country_id'],"website"=>$_POST['website'],"phone"=>$_POST['phone']);

			 
		    $r=updateTable($response['user_type'],$query,array("user_id"=>$response['user_id']),array("logo"));

            if(!mysqli_errno($configs['con']))
            {
            	$response['status']="You have successfully updated your profile.";
            }	
            else
            {
            	$response['status']="error";
            }	
		
        break;
         
        case "change-password":

         	$response['user_id']=getFldNameById("token","user_id",array("token"=>$_POST['authorizationToken']));
            $response['user_type']=getFldNameById("login","user_type",array("user_id"=>$response['user_id']));
 
		 
		    $exit=getTotalRow("login",array("password"=>md5($_POST['old_password']),"user_id"=>$response['user_id']));

		    if($exit)
		    {

		        $response=getDataByWhere("login","*",array("user_id"=>$response['user_id']));

		        if($_POST['new_password']==$_POST['conf_password']) 
		        {
		            $r1=updateTable("login",array("password"=>md5($_POST['new_password'])),array("user_id"=>$response['user_id']));

		            $response['message']="Password has been successfully changed.";
                    
		            
		                    
		            $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Change Password"));

		            $body=html_entity_decode($mail['template']);
		            $subject=$mail['subject'];

		              
		           
		              //$activation="<a href='" . $vpath . "activation/" . $activation_code . "' target='_new'>Activate</a>";


		            $mail_body=str_replace("{name}",$_SESSION['name'] ." ". $_POST['lname'],$body);
		            //$mail_body=str_replace("{activation}",$activation,$mail_body);
		            $mail_body=str_replace("{dotcom}",ucwords($dotcom),$mail_body);
		            $mail_body=str_replace("{email}",$_SESSION['email'],$mail_body);
		            $mail_body=str_replace("{password}",$_POST['new_password'],$mail_body);
		            $mail_body=html_entity_decode($mail_body);
		                         
		            smtpmailer($d['email'],$setting['support_mail'], ucwords($dotcom),$subject, $mail_body);  
		            //smtpmailer($setting['admin_mail'],$_POST['email'], $_POST['fname'],$subject, $mail_body);
		             
		        }
		        
		        else
		        {
		          $response['message']="Confirm password does not match with new password!";
		        }  
		      
		    } 
		    else
		    {

		      $response['message']="Wrong old password!!";
		    }  

        break;

        case "buyer-dashboard":


        	$response['user_id']=getFldNameById("token","user_id",array("token"=>$_POST['authorizationToken']));
            $response['user_type']=getFldNameById("login","user_type",array("user_id"=>$response['user_id']));


		    if($response['user_type']=="buyer")
		    {	
		        $response['profile_data']=getDataByJoin(array("buyer as b","login as l"),"b.*,l.email",array("l.user_id"=>$response['user_id']),array("l.user_id"=>"b.user_id"));
		        $table="buyer";
		    }
		    else
		    {	
		        $response['profile_data']=getDataByJoin(array("supplier as b","login as l"),"b.*,l.email",array("l.user_id"=>$response['user_id']),array("l.user_id"=>"b.user_id"));
		        $table="supplier";
		    }


            $response['total_tender']=getTotalRow("tender",array("user_id"=>$response['user_id']));
			$response['total_tender_open']=getTotalRow("tender",array("user_id"=>$response['user_id'],"status"=>"Y"));
			$response['total_tender_close']=getTotalRow("tender",array("user_id"=>$response['user_id'],"status"=>"C"));

        break;

        case "search-result":

        	$search_array=array("t.exp_date >"=>date("Y-m-d"),"t.status"=>"Y");
            $param=array(); 
            if($_POST['type'])
            {
                 $search_array=array_merge($search_array,array("t.cat_id"=>$_POST['type']));
                $param['type']=$_POST['type'];
            }  

            if($_POST['cat_id'])
            {
                $search_array=array_merge($search_array,array("t.sub_cat_id"=>$_POST['cat_id']));
                $param['sub_cat_id']=$_POST['cat_id'];
            }  
            if($_POST['country_id'])
            {
                $search_array=array_merge($search_array,array("l.country_id"=>$_POST['country_id']));
                $param['country_id']=$_POST['country_id'];
            }  
            if($_POST['search'])
            {
                $param['title rlike']=$_POST['search'];
                $search_array=array_merge($search_array,array("t.title rlike "=>$_POST['search'],"tid rlike"=>$_POST['search'],"tender_description rlike"=>$_POST['search']));
            }    
            $perpage=10;

            if(!$_POST['limit']){$_POST['limit']=1;}

            $limit=(($_REQUEST['limit']-1)*$perpage).",".$perpage;

            if($_POST['cat_id'])
            {
                $search_array=array_merge($search_array,array("t.sub_cat_id"=>$_POST['cat_id']));
            } 

            $response['total']=getTotalRowsByJoin(array("tender as t","login as l"),$search_array,array("t.user_id"=>"l.user_id"));

            $response['result']=getRowsByJoin(array("tender as t","login as l"),"t.*",$search_array,array("t.user_id"=>"l.user_id"),"t.tid desc");

            $k=0;

            if(!$response['total'])
            {

                $response['message']="No Tendor found!";

            }
            $param_data="";

             
         
        break;
        case "edit_tendor":
         
			updateTable("tender",$_POST,array("tid"=>$_POST['tid']));

            $response['message']="Tendor details successfully updated.";
		   
        break;
        
        
        case "contact_us":
         
            $_POST['date']=date("Y-m-d");
            
			insertTable("enquiry",$_POST);         
        	
        	$response['message']="We have received your information. We will contact you soon.";
         				
        	$mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Contact Us"));

        	$body=html_entity_decode($mail['template']);
       		
       		$subject=$mail['subject'];

          
            $mail_body=str_replace("{name}",$_POST['name'] ." ". $_POST['lname'],$body);
            $mail_body=str_replace("{dotcom}",ucwords($dotcom),$mail_body);
            $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
 
            $mail_body=html_entity_decode($mail_body);
                         
            smtpmailer($_POST['email'],$setting['support_mail'], ucwords($dotcom),$subject, $mail_body);

			$subject="New Mail from " . $_POST['name'];
			
			unset($_POST['g-recaptcha-response']);
			
			$mail_body=implode("<br>",$_POST);
			
            smtpmailer($setting['support_mail'],$_POST['email'], $_POST['fname'],$subject, $mail_body);
                 
		   
        break;
        case "tender-tips":
        
            $r=getQuery("faq","*",array("cat_id"=>27),"ord");
            
            $tips=array();
            foreach($r as $d)
            {
				$tips[]=array("question"=>$d['question'],"answer"=>$d['answer']);
			} 
            $response['tips']=$tips;
                                 
        break;
        case "chat":
        
            $response['user_id']=getFldNameById("token","user_id",array("token"=>$_POST['authorizationToken']));
           
           
	        if(isset($_POST['message']))
			{
				$udata=getDataByWhere("login","*",array("user_id"=>$response['user_id']));
				
				$r=insertTable("message",array("message"=>$_POST['message'],"receiver"=>$_POST['receiver'],"sender"=>$response['user_id'],"tid"=>$_POST['tid'],"msg_date"=>date("Y-m-d H:i")),array("attachment"));

			    if($r)
			    {
			        $response['message']="Your message has been  sent succesfully.";
			       
			        $template=getDataByWhere("mailsetup","*",array("mail_type"=>"Message Board"));

			        $subject=$template['subject'];
			        $message=html_entity_decode($template['template']);
					
					$name=getName($_POST['receiver']);
			        
					$body=str_replace("{name}",$name,$message);
			        $body=str_replace("{tid}",$_POST['tid'],$body);
			        $body=str_replace("{title}",$response['title'],$body);
			        $body=str_replace("{message}",$_POST['message'],$body);
			        $body=str_replace("{dotcom}",$dotcom,$body);

			        smtpmailer($udata['email'], $setting['support_mail'], $dotcom, $subject, $body);
					getNotify($udata['user_id'],htmlentities("A new message has been submitted by a supplier on your tendor.<br><a href='" . $vpath . "/message/" . $_POST['tid'] ."/" .seo_url($response['title'])."'>View message for Ref#".$_POST['tid'].".</a>"));

			    }
			    else
			    {
			    	$response['message']="Error!";
			    }	
					 
			} 
			        
	    break;
 
    }
  
	echo json_encode($response);
	die(); 
 
}
if($_GET['action'])
{
	switch($_GET['action'])
	{
		case "terms-and-conditions":
        case "about-us":
        case "privacy-policy":
        
            $response=getDataByWhere("contents","contents,cont_title",array("seo_url"=>$_GET['action']));

			$response['content']=html_entity_decode($response["contents"]);

            $response['title']=$response["cont_title"];

        break;
        case "latest-tendors":
	       
		    $response['latest']=getRowsByJoin(array("tender as t","category as c"),"t.*,c.cat_name",array("t.exp_date >"=>date("Y-m-d"),"t.status"=>"Y"),array("c.cat_id"=>"t.sub_cat_id"),"t.tid desc",10);

		break;
		
		case "notification":
		    
		    $exist=getTotalRow("token",array("token"=>$_GET['authorizationToken']));
		
		    if($exist)
		    {
				$response['user_id']=getFldNameById("token","user_id",array("token"=>$_GET['authorizationToken']));

		    	$response['total']=getTotalRow("notification",array("user_id"=>$response['user_id']));
		
	        	$response['notification']=getQuery("notification","*",array("user_id"=>$response['user_id']),"id desc");
                
			}
			else
			{
				$response['error']="Token does not exist!";
			}
		     
		break;
    }
    echo json_encode($response); 
    die();
}
    
?>