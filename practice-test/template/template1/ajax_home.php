<?
include("../configs/config.php"); 
include_once("../configs/license.php"); 
include_once("../configs/common.php");
if($_SERVER['HTTP_HOST']!="localhost")
{
 include("../smtpmail.php");
}
$array=array(); 


if($_POST['action'])
{   
	switch($_POST['action'])
	{
		case"login":
		
			 $exits=getTotalRow("user",array("email"=>$_POST['email'],"password"=>$_POST['password']));
         
			if($exits)
			{
//			    $d=getDataByJoin(array("subscription as s","user as u", "login as l"),"s.course_id,s.expire_date,u.*,l.last_login",array("u.email"=>$_POST['email'],"u.password"=>$_POST['password']),array("u.user_id" => "s.user_id", "u.user_id" => "l.user_id"));
                
                $d=getDataByJoin(array("user as u", "login as l"),"u.*,l.last_login",array("u.email"=>$_POST['email'],"u.password"=>$_POST['password']),array( "u.user_id" => "l.user_id"));
			    
                $dd = getQuery("subscription","*",array("user_id"=>$d["user_id"]));
                
			    $_SESSION['user_id']      = $d["user_id"];
                $_SESSION['status']       = $d["activation_status"];
                $_SESSION['email']        = $d['email'];
                $_SESSION['name']         = $d['first_name'] . " " . $d['last_name'];
                $_SESSION['user_type']	  = $d['user_type'];
                $_SESSION['reg_date']	  = $d['reg_date']; 
                $_SESSION['last_login']	  = $d['last_login'];
                $_SESSION['test_id']	  = $dd['course_id'];
                $_SESSION['checking']	  = $_POST['password'];
				if($d['course_id'])
                {
                    $_SESSION['test_name'] = getFldValueById("course","course_name",array("course_id"=>$d['course_id']));
                }

                if($dd['expire_date']<date('Y-m-d'))
                {
                    updateTable("user",["activation_status"=>"N"],["user_id"=>$d["user_id"]]);
                    $_SESSION['status'] = "N";
//                    $_SESSION['checking'] = "con: ".  date('Y-m-d');
                }
				
		        #update last login
		        
				updateTable("login",array("last_login"=>date("Y-m-d H:i:s")),array("user_id"=>$d['user_id']));
		     
		   		$array['msg']="";
		   		
		   		if($_POST['referer'])
		   		{
					$array['url']=$configs['vpath'].$_POST['referer'];
		   		}
				else
				{
					$array['url']=$configs['vpath']."my-dashboard";
				}
		    }
			else
			{
			    $array['msg']=str_replace("Error!!.","Error!!. Invalid Email and Password.",$configs['error_msg']);
			}
			 
		break;
		case"test":
		
		    $correct="0";
            $answer=getDataByWhere("question","*",array("qid"=>$_POST['question_id']));
			
			if($answer['answer']==$_POST['answer_no']) 
			{
				$correct=1;
			}
			//deleteData("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$_SESSION['user_id'],"set_no"=>$_POST['set_no'],"lesson_id"=>(int)$_POST['lesson_id'],"unique_id"=>$_SESSION['unique_id'],"date"=>date("Y-m-d")));
			
//			deleteData("running_exam",array("test_id"=>$_SESSION['test_id'],"question_id"=>$_POST['question_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_POST['lesson_id'],"term"=>$new_term,"report_id"=>0));
			 
			$id=updateTable("exam",array("user_ans"=>(int)$_POST['answer_no'],"iscorrect"=>(int)$correct),array("qid"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_POST['lesson_id'],"is_result"=>0));
		
		   // echo insertTable("running_exam",array("test_id"=>$_SESSION['test_id'],"question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"answer_no"=>(int)$_POST['answer_no'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_POST['lesson_id'],"date"=>date("Y-m-d"),"term"=>$new_term,"correct"=>(int)$correct,"report_id"=>0),'',1);
			
			$total_answer=getTotalRow("exam", array("cat_id"=>$_POST['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_POST['lesson_id'],"user_ans >"=>0,"is_result"=>0));
			 
			$pending=$_POST['total']-$total_answer;
			 
			//$array['list']=html_entity_decode($answer['explanation']);
			
			$array['list']="";
			
			if($id)
			{
			    $array['msg']="<span class='badge-success'>You have answered successfully.</span>";
			
			}
			else
			{   
			  $array['msg']="<span class='badge'>Error!</span>";
			}
			
			$array['pending']="";
			
			if($pending>0)
			{
				$array['total']="<font color=red>Still pending " . $pending . " questions to answer!</font>";
			
			}
			else
			{
//				updateTable("login",array("exam_time"=>(int)$_POST['time']),array("user_id"=>$_SESSION['user_id']));
				
				$category_name=getFldValueById("category","category_name",array("cat_id"=>$_POST['cat_id']));
                
                $result_id = getFldValueById("exam","result_id",array("qid"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$_SESSION['user_id'],"lesson_id"=>(int)$_POST['lesson_id'],"is_result"=>0));
                
				$array['total']="<div class=\"btn btn-success text-center\" onClick=\"javascript:window.location='" . $configs['vpath']."exam/result/" . $_POST['cat_id']."-" . seo_url($category_name) ."/" . $_POST['lesson_id'] ."/". $result_id . "';\">Submit</div>";
		
			}
		   
		break;
		case "freetest":
		     
		    $correct="0";
		    //$array['msg']="<div class='mtq_stamp mtq_wrong_stamp'>Wrong</div>";
			
			$answer=getDataByWhere("question_answer","*",array("question_id"=>$_POST['question_id'],"free"=>1));
			
			if($answer['answer_no']==$_POST['answer_no']) 
			{
				$correct=1;
			}
			//deleteData("running_exam",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$_SESSION['user_id'],"set_no"=>$_POST['set_no'],"lesson_id"=>(int)$_POST['lesson_id'],"unique_id"=>$_SESSION['unique_id'],"date"=>date("Y-m-d")));
			
			deleteData("free_exam_score",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"user_id"=>$_POST['user_id']));
			 
			$id=insertTable("free_exam_score",array("question_id"=>$_POST['question_id'],"cat_id"=>$_POST['cat_id'],"answer_no"=>$_POST['answer_no'],"user_id"=>$_POST['user_id'],"date"=>date("Y-m-d"),"correct"=>(int)$correct));
				
			$total_answer=getTotalRow("free_exam_score",array("cat_id"=>$_POST['cat_id'],"user_id"=>$_POST['user_id']));
			
			$pending=10-$total_answer;
			 
			//$array['list']=html_entity_decode($answer['explanation']);
			
			$array['list']="";
			if($id)
			{
			   $array['msg']="<span class='badge-success'>You have answered successfully.</span>";
			
			}
			else
			{   
			  $array['msg']="<span class='badge'>Error!</span>";
			}
			 
			$array['pending']="";
			
			if($pending>0)
			{
				$array['total']="<font color=red>Still pending " . $pending . " questions to answer!</font>";
			
			}
			else
			{
				$array['total']="<div class=\"btn btn-success text-center\" onClick=\"javascript:window.location='https://www.asvab-tutoring.com/online-courses/freetest/score-".$_POST['cat_id']."';\">View Score</div>";
		
			}
		  
		break;
		case "freetest_reg":
		    $_POST['date']=date("Y-m-d");
		    $_POST['status']="N";
		    $_POST['subject']="freetest";
		    $id=insertTable("enquiry",$_POST);
		    $array['url']="https://www.asvab-tutoring.com/online-courses/freetest/myscore-".$_POST['cat_id'];
		break;
		
		case"prediction-test":
		
		    $correct="0";
		    
			$answer=getDataByWhere("question","*",array("qid"=>$_POST['question_id']));
			if($answer['answer']==$_POST['answer_no']) 
			{
				$correct=1;
			}
			 
			$id=updateTable("exam", array("exam_date"=>date("Y-m-d"),"user_ans"=>$_POST['answer_no'], "iscorrect"=>$correct),array("qid"=>$_POST['question_id'],"Predic_test_id"=>$_POST['cat_id'],"cat_id"=>(int)$_POST['lesson_id'],"user_id"=>$_SESSION['user_id'], "is_result"=>0));
            
			$total_answer=getTotalRow("exam",array("user_ans>"=>0,"Predic_test_id"=>$_POST['cat_id'],"user_id"=>$_SESSION['user_id'], "is_result"=>0));
			
			$pending=$_POST['total']-$total_answer;		 
			 
			$array['list']="";
			if($id)
			{
			   $array['msg']="<span class='badge-success'>You have answered successfully.</span>";
			
			}
			else
			{   
			  $array['msg']="<span class='badge'>Error!</span>";
			}
			
			$array['pending']="";

//			$array['ctotal']=getTotalRow("prediction_exam",array("term"=>((int)$_POST['term']+1),"test_id"=>$_SESSION['test_id'],"cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"user_id"=>$_SESSION['user_id']));
			
			if($pending>0)
			{
				$array['total']="<font color=red>Still pending " . $pending . " questions to answer!</font>";
			
			}
			else
			{
				$cat_name=getFldValueById("predic_test","test_name",array("Predic_test_id"=>$_POST['cat_id']));
			 	
			 	$array['total']="<div class=\"btn btn-success text-center\" onClick=\"javascript:window.location='" . $configs['vpath']."exam/prediction-result/".$_POST['cat_id'] ."-". seo_url($cat_name) ."';\">Submit</div>";
		
			}
		   
		break;
		
		case"report":		
		    $list='<div id="mtq_shaded_item_msg-1" class="mtq_shaded_item_msg">Shaded items are complete.</div>
			<table id="mtq_question_list_container-1" class="mtq_question_list_container">
			<tr>';
			if(!$_POST['set_no'])
			{
				$_POST['set_no']=1;
			}
			$total=$_POST['total'];
            $questions=getQuery("questions","id",array("set_no"=>$_POST['set_no'],"cat_id"=>$_POST['cat_id']));
            $answers=array();
            $i=0;
            foreach($questions as $q)
            {
				$i++;
				$yes=getTotalRow("running_exam",array("user_id"=>$_SESSION['user_id'],"question_id"=>$q['id'],"set_no"=>$_POST['set_no']));
			    if($yes)
			    {
					$answers[]=$i;
				}
			}
            
			for($i=1;$i<=$total;$i++)
			{
				$background="";
				if(in_array($i,$answers))
				{
					$background="style='background-color:green';";
				}
				$list.="<td  " .$background ." class='mtq_list_item' onclick='selectIt(" . $i .");'>" . $i ."</td>";
			    if(!($i%5))
			    {
					$list.="</tr><tr>";
				}
			}
			
			$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id'])); 
			
			$list.="</table><div class='clearfix'><br></div>";
			 
			if($_POST['lesson_id'])
			{
				$name=getFldValueById("lesson","name",array("id"=>$_POST['lesson_id']));
				$list.="<div class=\"btn btn-info text-center\" onClick=\"javascript:window.location='" . $configs['vpath']."lesson/" . $_POST['cat_id']."-" . $_POST['lesson_id']."-".seo_url($name) ."/practice-test';\">Return</div>";
			}
			else
			{
				$list.="<div class=\"btn btn-info text-center\" onClick=\"javascript:window.location='" . $configs['vpath']."exam/" . $_POST['cat_id']."-" . seo_url($cat_name) ."/set-" . $_POST['set_no'] ."';\">Return</div>";
			}
			
			$list.="<div class=\"btn btn-success text-center\" onClick=\"javascript:window.location='" . $configs['vpath']."exam/" . $_POST['cat_id']."-" . seo_url($cat_name) ."/set-" . $_POST['set_no'] ."/".$_SESSION['unique_id']."/result';\">Submit</div>
			
			</div>";
			
			$array['list']=$list;
		 
		break;
		case "get-exam":
		
		    if(!$_POST['set_no'])
			{
				$_POST['set_no']=1;
			}
		    $list='<div id="big-image">';
			$i=0;
			$total=getTotalRowsByJoin(array("questions as q","question_answer as qa"),array("q.set_no"=>$_POST['set_no'],"q.cat_id"=>$_POST['cat_id'],"q.homework"=>0),array("qa.question_id"=>"q.id"));
            $question_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*,qa.*",array("q.set_no"=>$_POST['set_no'],"q.cat_id"=>$_POST['cat_id'],"q.homework"=>0),array("qa.question_id"=>"q.id"));
            $answers=array();
    
			foreach($question_test as $a)
			{
				if($_POST['cat_id']==18)
				{
					$answers[$a['question_id']]=getFldValueById("answers","answer",array("question_id"=>$a['question_id'],"answer_no"=>$a['answer_no']));

				}
				else
				{
					$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id']));
			    }
			}
		   
			foreach($question_test as $f)
         	{
				$i++;
				$list.='<item>
				<table class="table table-borderless">
				<tr><td ><span class="badge">'.$i.'</span></td><td colspan=2>'.html_entity_decode($f['question'])."</td></tr>";
				$j=0;
				foreach($answers[$f['question_id']] as $a)
				{   $j++; 
					$list.="<tr><td width=5>" . $alphabet[$j]."</td><td width=5><input type='radio' name='answer' id='" . $i."' onClick=\"getAnswer('" . $f['question_id']."','" . $j ."');\" ></td><td class='text-align:left'>" . html_entity_decode($a['answer']) ."</td></tr>";
				}
				$list.="</table>";
				$list.="</item>" ;
			}
			$list.='</div>
         	<div class="small-images" style="display: none">';
         	for($i=1;$i<=$total;$i++)
			{
				$class='';
				if($i==1){$class='class="selected"';}
					
				$list.="<item " . $class . ">" . $i ."</item>";
				     
			}
			$list.="</div>
			   
			   <div class='row'>
			   	<div class='col-md-2'>
			   		<div id=\"prev\"   class='prev browse left mtq_css_back_button mtq_listrow_button pull-left'>&#8592;</div>
			 		
			   	</div>
			   	<div class='col-md-8 text-center'>
			   		<center>There are " .$total ." questions to complete.</center>
			   		<div class=\"btn btn-info\" onClick=\"javascript:getReport();\">List </div>
					</div>
			   	<div class='col-md-2'>
			   		<div id=\"next\"   class='next browse right mtq_css_next_button mtq_listrow_button pull-right'>&#8594;</div>
					</div>
			   </div> 
			   <div id='list'></div>";
             
            $array['list']=$list;
			 
		break;
		case"edit-profile":
		    $_POST['country_id']=(int)$_POST['country_id'];
		    $_POST['state_id']=(int)$_POST['state_id'];
		    $_POST['city_id']=(int)$_POST['city_id'];
		    $_POST['zip_code']=(int)$_POST['zip_code'];
		
		    updateTable("user",$_POST,array("user_id"=>$_SESSION['user_id'])); 
		    $array['msg']=$configs['success_msg'];
		break;
		
		case"contact":
		    
		    $_POST['date']=date("Y-m-d");
            $_POST['status']="N";
            $_POST['user_id']=(int)$_POST['user_id'];
            $_POST['test_id']=(int)$_POST['test_id'];
            
			insertTable("enquiry",$_POST);
			 
			$array['msg']="<h4>You have successfully submitted enquiry.</h4>";
			
			$mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Contact Us"));

	        $body=html_entity_decode($mail['template']);
	        $subject=$mail['subject'];

	        $mail_body=str_replace("{name}",$_POST['fname'] ." ". $_POST['lname'],$body);
	        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
	        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
	        $mail_body=html_entity_decode($mail_body);
	        $mailbody="<table width=100%>";
	        foreach($_POST as $p=>$v)
	        {
				 $mailbody.="<tr><td>".Ucwords($p) ."</td><td>" .$v ."</td></tr>";
			} 
			$mailbody.="<tr><td>Source</td><td>Officers Web</td></tr>";
			$mailbody="</table>";   
			         
	       smtpmailer($_POST['email'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  
	       smtpmailer($configs['admin_mail'],$_POST['email'], $_POST['name'],"New Contact from " . $_POST['first_name'], $mailbody);
	       
        break;
        case "report-error":            
             
	     	if($_POST['cat_id'])
	     	{
	     		$exist_cat=getTotalRow("category",['cat_id'=>$_POST['cat_id']]);
	     	
	     	}
	     	if($_POST['lesson_id'])
	     	{
	     		$exist_lesson=getTotalRow("lesson",['lesson_id'=>$_POST['lesson_id']]);
	     	
	     	}		
	     	if($exist_cat)
	     	{
	     		$cat_name=getFldValueById("category","cat_name",['cat_id'=>$_POST['cat_id']]);
	     
	     	}
	     	if($exist_lesson)
	     	{
	     		$lesson_name=getFldValueById("lesson","name",['lesson_id'=>$_POST['lesson_id']]);
	     
	     	}
			$error="<b>Error Details</b><hr>Category: " . $cat_name . "<br>Lesson: " . $lesson_name . "<br>Error:".$_POST['message'];

			insertTable("report_error",["user_id"=>$_SESSION["user_id"],"date"=>date("Y-m-d"),"error"=>$error,"status"=>"Open"]);

	        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Report Error"));

	        $body=html_entity_decode($mail['template']);
	        $subject=str_replace("{name}",$_POST['name'],$mail['subject']);
	        
	        $mail_body=str_replace("{grade}",$grade,$body);
	        
	        $mail_body=str_replace("{lesson_name}",$_POST['lesson_name'],$body);
	        $mail_body=str_replace("{lesson_id}",$_POST['lesson_id'],$body);
	        $mail_body=str_replace("{cat_name}",$cat_name,$body);
	        $mail_body=str_replace("{cat_id}",$_POST['cat_id'],$body);
	        
	        $mail_body=str_replace("{date}",showdate("Y-m-d"),$mail_body);
	        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
	        
	        
	        $mail_body=str_replace("{error_url}",$_POST['error_url'],$mail_body);
	        $mail_body=str_replace("{message}",$error,$mail_body);
	        
	        $mail_body="<html><body>" .html_entity_decode($mail_body) . "<br>Source : Officers WEB</body></html>";
            if($_SERVER['HTTP_HOST']!="localhost")
            {
			    smtpmailer($setting['admin_mail'],$_POST['email'], ucwords($configs['dotcom']),$subject, $mail_body);
		
			}
			$array['success']="<h3>Thanks for report this issue.Our team will do the needful.</h3>";
		break;	
        case "forgot-password":
        
		    $exists=getTotalRow("login",array("email"=>$_POST['email']));
			
			if($exists)
			{
			    $d=getDataByWhere("login","*",array("email"=>$_POST['email']));
			 
				$devider=date("Ymd");
		        $password_link=$configs['vpath']."create-password/" . ($d['user_id']*$devider);
		        
				//updateTable("login",array("password"=>md5($new_password)),array("user_id"=>$d['user_id']));
		      
		        
		        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Forgot Password"));

		        $body=html_entity_decode($mail['template']);
		        $subject=$mail['subject'];
		        
		        $mail_body=str_replace("{name}",$name,$body);
		        
		        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
		        $mail_body=str_replace("{email}",$_POST['email1'],$mail_body);
		        $mail_body=str_replace("{password_link}",$password_link,$mail_body);
		        $mail_body=html_entity_decode($mail_body);
                if($_SERVER['HTTP_HOST']!="localhost")
                {
				    smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);
				 
				}
		        $array['success']="Please check your email to reset password.<br><br>"; 	
			}
			else
			{
				$array['error']="<font color=red>Invalid Email Id!</font>";
				 
			}
			 
		break;	
		case "create-password":
        
		    if($_POST['password']!=$_POST['conf_password'])
		    {
		    	$array['error']="<font color=red><img src='" . $configs['vpath'] ."img/off.png'> Password and confirm password does not match!</font>";
		    	$array['success']='';
		    }
			else 
			{
			    $devider=date("Ymd");
				$user_id=($_POST['user_id']/$devider);
			    
			    $exist=getTotalRow("login",array("user_id"=>$user_id));
			    if($exist)
			    {
			 
				    updateTable("login",array("password"=>$_POST['password']),array("user_id"=>$user_id));		      
		            $array['success']="<img src='" . $configs['vpath'] ."img/on.png'> Your new password has been updated.<br>"; 
		         
				} 	
			    else
				{
					$array['error']="<font color=red><img src='" . $configs['vpath'] ."img/off.png'> Invalid  Id.</font>";
					$array['success']='';
				}
		    } 
		break;
		case"contact":
		
		 	if($_SESSION["captcha_code"]!=$_POST['captchatext'])
		 	{
		 		$array['msg']="<font color=#FF0000>Error in Security code.</font>";
	
		 	} 
			else
			{	
				$_POST['date']=date("Y-m-d");
				$_POST['status']="Y";
				insertTable("enquiry",$_POST);
				$array['msg']="";
				$array['list']="<h4>You have successfully submitted enquiry.</h4>";
				
				$mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Contact Us"));

		        $body=html_entity_decode($mail['template']);
		        $subject=$mail['subject'];

		        $mail_body=str_replace("{name}",$_POST['fname'] ." ". $_POST['lname'],$body);
		        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
		        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
		        $mail_body=html_entity_decode($mail_body);
		        $mailbody="<table width=100%>";
		        foreach($_POST as $p=>$v)
		        {
					 $mailbody.="<tr><td>".Ucwords($p) ."</td><td>" .$v ."</td></tr>";
				}
				$mailbody.="<tr><td>Source</td><td>Officers Web</td></tr>";
				$mailbody="</table>"; 
				  
			    if($_SERVER['HTTP_HOST']!="localhost")
                {    
	       			// smtpmailer($_POST['email'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);  
	       			smtpmailer($configs['admin_mail'],$_POST['email'], $_POST['name'],"New Contact from " . $_POST['first_name'], $mailbody);
			    }
		    }
        break;	
		
		case"view-homework":
		
		    deleteData("homework_view",array("user_id"=>$_SESSION['user_id'],"question_id"=>$_POST['question_id'],"lesson_id"=>(int)$_POST['lesson_id'],"course_id"=>$_SESSION['test_id']));
		     
		    insertTable("homework_view",array("user_id"=>$_SESSION['user_id'],"ord"=>(int)$_POST['ord'],"question_id"=>$_POST['question_id'],"story_id"=>(int)$_POST['story_id'],"lesson_id"=>(int)$_POST['lesson_id'],"date"=>date("Y-m-d"),"course_id"=>$_SESSION['test_id']));
			$total=getTotalRow("homework_view",array("user_id"=>$_SESSION['user_id'],"lesson_id"=>$_POST['lesson_id'],"course_id"=>$_SESSION['test_id']));
			  
		    $pending=$_POST['total']-$total;

		    $green=($total/$_POST['total'])*100;
			$blue=0;
			if($pending)
			{
				$blue=($pending/$_POST['total'])*100;
			}
		    
		    
			if((int)$pending)
			{
			   $array['total']="<table class='table'><tr><td style='background-color:green;color:#fff' width='" . $green . "%'>".$total."</td><td style='background-color:yellow;color:#111' width='" . $blue . "%'>".$pending ."</td></tr></table>";
		   	
			}	
			else
			{
				$array['total']="<table class='table'><tr><td style='background-color:green;color:#fff' width='" . $green . "%'>All checked.</td></tr></table>";
		   
			}	
			
		break; 		 
		
	}
	//print_r($array);
	echo json_encode($array);	
	flush();	 
}


if($_POST['state_id'] && $_POST['get_city_option'])
{
	$citys=getQuery("city","*",array("state_id"=>$_POST['state_id']),"name");
    echo"<option value=''>Select City</option</option>";
    foreach($citys as $c)
    {
		echo"<option value='" . $c['id'] ."'>" .$c['name'] ."</option</option>";
	}    
}

?>