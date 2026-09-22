<?
header('Content-Type: application/json');
include("configs/config.php"); 
include("configs/common.php"); 
include("configs/license.php"); 
include("configs/array.php");
include("smtpmail.php");


$afoqt_pred_score_array = [
    0=>0, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1,
    11 => 2, 12 => 2, 13 => 2, 14 => 2, 15 => 3, 16 => 3, 17 => 3, 18 => 4, 19 => 4, 20 => 5,
    21 => 5, 22 => 6, 23 => 7, 24 => 7, 25 => 8, 26 => 9, 27 => 10, 28 => 11, 29 => 12, 30 => 13,
    31 => 15, 32 => 16, 33 => 17, 34 => 19, 35 => 20, 36 => 22, 37 => 24, 38 => 25, 39 => 27, 40 => 29,
    41 => 31, 42 => 33, 43 => 35, 44 => 37, 45 => 37, 46 => 38, 47 => 38, 48 => 39, 49 => 39, 50 => 40,
    51 => 41, 52 => 41, 53 => 42, 54 => 42, 55 => 43, 56 => 43, 57 => 45, 58 => 45, 59 => 46, 60 => 46,
    61 => 48, 62 => 48, 63 => 49, 64 => 49, 65 => 50, 66 => 51, 67 => 52, 68 => 53, 69 => 54, 70 => 55,
    71 => 56, 72 => 57, 73 => 58, 74 => 59, 75 => 60, 76 => 61, 77 => 62, 78 => 63, 79 => 64, 80 => 65,
    81 => 66, 82 => 67, 83 => 68, 84 => 69, 85 => 70, 86 => 72, 87 => 74, 88 => 76, 89 => 78, 90 => 80,
    91 => 83, 92 => 85, 93 => 88, 94 => 90, 95 => 93, 96 => 95, 97 => 97, 98 => 98, 99 => 99, 100 => 99
];

$pred_score_array = [
    0=>0, 1 => 20, 2 => 20, 3 => 20, 4 => 20, 5 => 20, 6 => 20, 7 => 20, 8 => 20, 9 => 20, 10 => 20,
    11 => 20, 12 => 20, 13 => 20, 14 => 20, 15 => 20, 16 => 20, 17 => 20, 18 => 20, 19 => 20, 20 => 20,
    21 => 20, 22 => 20, 23 => 20, 24 => 20, 25 => 20, 26 => 21, 27 => 22, 28 => 22, 29 => 23, 30 => 24,
    31 => 25, 32 => 26, 33 => 26, 34 => 27, 35 => 28, 36 => 29, 37 => 30, 38 => 30, 39 => 31, 40 => 32,
    41 => 33, 42 => 34, 43 => 34, 44 => 35, 45 => 36, 46 => 37, 47 => 38, 48 => 38, 49 => 39, 50 => 40,
    51 => 41, 52 => 42, 53 => 42, 54 => 43, 55 => 44, 56 => 45, 57 => 46, 58 => 46, 59 => 47, 60 => 48,
    61 => 49, 62 => 50, 63 => 50, 64 => 51, 65 => 52, 66 => 53, 67 => 54, 68 => 54, 69 => 55, 70 => 56,
    71 => 57, 72 => 58, 73 => 58, 74 => 59, 75 => 60, 76 => 61, 77 => 62, 78 => 62, 79 => 63, 80 => 64,
    81 => 65, 82 => 66, 83 => 66, 84 => 67, 85 => 68, 86 => 69, 87 => 70, 88 => 70, 89 => 71, 90 => 72,
    91 => 73, 92 => 74, 93 => 74, 94 => 75, 95 => 76, 96 => 77, 97 => 78, 98 => 78, 99 => 79, 100 => 80
];

//start: normal distribution calculation functions

function erf($x) {
    // Constants for approximation
    $a1 =  0.254829592;
    $a2 = -0.284496736;
    $a3 =  1.421413741;
    $a4 = -1.453152027;
    $a5 =  1.061405429;
    $p  =  0.3275911;

    // Save the sign of x
    $sign = $x < 0 ? -1 : 1;
    $x = abs($x);

    // Approximation formula
    $t = 1.0 / (1.0 + $p * $x);
    $y = 1.0 - ((((($a5 * $t + $a4) * $t) + $a3) * $t + $a2) * $t + $a1) * $t * exp(-$x * $x);

    return $sign * $y;
}

function normDist($x, $mean, $stdDev) {
    return 0.5 * (1 + erf(($x - $mean) / ($stdDev * sqrt(2))));
}

//end: normal distribution calculation functions


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
		      
		        $user_id=insertTable("login",array("email"=>$_POST['email'],"paid"=>0,"status"=>"Y","user_type"=>"S","password"=>$_POST['password'],"activation_status"=>"Y","otp"=>$activation_code,"reg_date"=>date("Y-m-d"),"last_login"=>date("Y-m-d H:i:s"),"reg_at"=>"app"));
		        if($user_id) 
		        {	 
			        $r=insertTable("student",array("user_id"=>$user_id,"test_id"=>(int)$_POST['test_id'],"first_name"=>$_POST['first_name'],"last_name"=>$_POST['last_name'],"mobile"=>$_POST['mobile'],"country_id"=>(int)$_POST['country_id'],"state_id"=>(int)$_POST['state_id'], "city_id"=>(int)$_POST['city_id'],"zip_code"=>(int)$_POST['zip_code']));

			        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Register app"));

			        $body=html_entity_decode($mail['template']);
			        $subject=$mail['subject'];
//			        $subject="Course Registration Confirmation";
			        $sendpassword=$_POST['password'];
			      
			        $mail_body=str_replace("{name}",$_POST['first_name'] ." ". $_POST['last_name'],$body);
			        $mail_body=str_replace("{activation_code}",$activation_code,$mail_body);
			        $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
			        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
			        $mail_body=str_replace("{password}",$sendpassword,$mail_body);
			        $mail_body=html_entity_decode($mail_body);
                    
			        smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body); 
                    
//                    $email_body = '
//                    <html>
//                    <body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
//                        <p>Hi '.$_POST['first_name'] .' '. $_POST['last_name'].',</p>
//                        <p>Thank you for your registration.</p>
//                        <p><strong>Login URL:</strong> <a href="'.ucwords($configs['dotcom']).'" style="color: #0073e6; text-decoration: none;">'.ucwords($configs['dotcom']).'</a></p>
//                        <p><strong>Your Login ID:</strong> '.$_POST['email'].'</p>
//                        <p><strong>Your Password:</strong> '.$sendpassword.'</p>
//                        <p>Thanks,</p>
//                        <p>The ASVAB Tutoring Team</p>
//
//                        <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; text-align: center;">
//                            <p style="color: #ff6600; font-weight: bold;">🌟 Unlock Your Potential with ASVAB Tutoring! 🌟</p>
//                            <p><strong>Exclusive Tutoring Services Just for You!</strong></p>
//                            <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
//                                <li>✅ <strong>One-on-One Sessions:</strong> Personalized coaching tailored to your needs.</li>
//                                <li>✅ <strong>Comprehensive Study Materials:</strong> Access the best resources and guides.</li>
//                                <li>✅ <strong>Expert Tutors:</strong> Learn from the best in the field.</li>
//                            </ul>
//                            <p style="color: #ff6600; font-weight: bold;">🎓 Achieve Your Goals with Us!</p>
//                            <p><a href="https://www.asvab-tutoring.com/" style="display: inline-block; padding: 10px 20px; margin-top: 10px; font-size: 16px; color: #fff; background-color: #0073e6; text-decoration: none; border-radius: 5px;">Explore Our Tutoring Facilities Now!</a></p>
//                        </div>
//
//                        <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; text-align: center;">
//                            <p style="color: #ff6600; font-weight: bold;">📚 Expand Your Knowledge with Our Additional Courses! 📚</p>
//                            <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
//                                <li>🔹 <strong>ASVAB Preparation:</strong> Get the best scores with our expert guidance.</li>
//                                <li>🔹 <strong>ASTB Preparation:</strong> Comprehensive resources and tutoring to excel.</li>
//                                <li>🔹 <strong>OAR Preparation:</strong> Customized programs to help you succeed.</li>
//                                <li>🔹 <strong>SIFT Preparation:</strong> Get the best scores with our expert guidance.</li>
//                                <li>🔹 <strong>AFOQT Preparation:</strong> Customized programs to help you succeed.</li>
//                            </ul>
//                            <p><a href="https://ocs.asvab-tutoring.com/" style="display: inline-block; padding: 10px 20px; margin-top: 10px; font-size: 16px; color: #fff; background-color: #0073e6; text-decoration: none; border-radius: 5px;">Discover All Our Courses Here!</a></p>
//                        </div>
//
//                        <p style="text-align: center;">If you have any questions or need further assistance, feel free to reach out to our team.</p>
//                    </body>
//                    </html>
//                    ';
//
//                    $email_body=html_entity_decode(html_entity_decode(html_entity_decode($email_body)));
//                    smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $email_body);

	      		    $response['status']=200;
			  		 
					$response["isSuccess"]=true;
					$authorizeToken=getToken(); 
					$response["userDetails"]=array(
						"userId"=>$user_id,
						"authorizationToken"=>$authorizeToken,
						"userName"=>$_POST['email'],"mobile"=>$_POST['mobile'],
						"name"=>$_POST['first_name'] ." ". $_POST['last_name']
						);
					
					updateTable("login",array("token"=>$authorizeToken),array("user_id"=>$user_id)); 
			        
			        $response["message"]="You are successfully registered with us. Please check your mail.";
			    }
			    else
			    {
			    	$response["message"]="Some Error! Try Later.";
			    }	
			
		    } 

	    break;
	    
	    case "login":
	         
		    $exits=getTotalRow("login",array("email"=>$_POST['email'],"password"=>$_POST['password'],"status"=>'Y',"activation_status"=>'Y',"reg_at"=>"app"));
            
             
            if($exits)
			{
			    $token=getToken();
			    
			    $d=getDataByJoin(array("login as l","student as s"),"l.*,s.first_name,s.last_name,s.mobile,s.test_id",array("l.email"=>$_POST['email'],"l.password"=>$_POST['password'],"l.reg_at"=>"app"),array("l.user_id"=>"s.user_id"));
			   	
			   	updateTable("login",array("token"=>$token,"last_login"=>date("Y-m-d")),array("user_id"=>$d["user_id"]));	       
		         	
				$response["status"]=200;
				$response["isSuccess"]=true;
				$response["message"]="Login Success";
				
				if($d['paid'] && $d['exp_date']<date("Y-m-d"))
				{
					//updateTable("login",array("paid"=>0),array("user_id"=>$d["user_id"]));	       
		            //$d['paid']=0;
				}	
				
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
				$response["message"]="Invalid email address or password.";

			}
		
		break;

		case "delete-account":
	         
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		 
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
	            $user_id=$user['user_id'];
	          
			   	
			   	updateTable("login",array("token"=>$token,"status"=>'D',"last_login"=>date("Y-m-d")),array("user_id"=>$user_id));	       
		         	
				$response["status"]=200;
				$response["isSuccess"]=true;
				$response["message"]="Account Deleted Successfully";

				$mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Delete Account"));

		        $body=html_entity_decode($mail['template']);
		        $subject=$mail['subject'];
		        
		        $mail_body=str_replace("{name}",$name,$body);
		        
		    
		        $mail_body=html_entity_decode($mail_body);
                if($_SERVER['HTTP_HOST']!="localhost")
                {
				    smtpmailer($_POST['email'],$setting['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);
				  
				} 
				
				 
			} 
			else
			{
				
				$response["status"]=201;
				$response["isSuccess"]="false";
				$response["message"]="Login faild";

			}
		
		break;
		
		case "forgot-password":
		
			$exist=getTotalRow("login",array("email"=>$_POST['email'], "reg_at"=>"app"));
			
			if($exist)
			{
			    $d=getDataByWhere("login","*",array("email"=>$_POST['email'], "reg_at"=>"app"));
			 
				$devider=date("Ymd");
		        $password_link=$configs['vpath']."create-password/" . ($d['user_id']*$devider);
		         
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
				    
				    $response['message']="Please check your email to reset password."; 
				} 
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
		        
                $response['reg_date'] = $user['reg_date'];
                
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
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		        
	            
		         $user_id=$user['user_id'];
	             $paid_member=(int)$user['paid'];
		    
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
					if($paid_member)
					{
						$c['paid']=0;
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
	            
	            $term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	    	
    	    	$new_term=(int)$term+1;
				   
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
				
				    $story_limit=5;
				    if($_POST['cat_id']==70)
				    {
				    	 $response['practice_test_total']=20;
					
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
						$response['cat_id']=$_POST['cat_id'];
				        $response['lesson_id']=$_POST['lesson_id']; 
			  		    $response["status"]=200;
					    $response["isSuccess"]="true";
				    }    
			        elseif($_POST['cat_id']==57 || $_POST['cat_id']==47)
			        { 
						
//						$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			           
					}
					elseif($_POST['cat_id']==93)
					{
//						$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id in "=>"(57,47)","lesson_id"=>$_POST['lesson_id'],"qtype"=>1),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			         
					}	
					else
					{
//						$practice_test=getQueryByJoin(array("questions as q","question_answer as qa"),"q.*",array("q.cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id']),array("qa.question_id"=>"q.id"),"rand()",$response['practice_test_total']);
			      
					}
					if(!$response['practice_test_questions'])
					{
				 	
//				    	$answers=array();
//				    	$serial_no=0;
//				    	$question=array();
//				    	
//				    	foreach($practice_test as $a)
//				    	{
//							
//							$answers=getQuery("answers","*",array("question_id"=>$a['id']),"id",$option_no);
//							$j=0;$answer_list=array();
//							
//							foreach($answers as $c)
//							{   $j++; 
//								$ans=$data['alphabet'][$j];
//								$answer_list[]=array($j=>$ans,"id"=>$c['id'],"answer"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer']))));
//								
//							}
//							$serial_no++;
//												
//							$question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"cat_id"=>$a['cat_id'],"question"=>html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question'])))),"answers"=>$answer_list);
//						}
					    $response['cat_id']=$_POST['cat_id'];
				        $response['lesson_id']=$_POST['lesson_id']; 
//				  		$response['practice_test_questions']=$question;
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
                    $story_exist=getTotalRow("contents",array("parent_id"=>$_POST['cat_id']));
		           $term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	    		$new_term=(int)$term+1;
                    
                   $is_freshtest = (int)getTotalRow("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"test_id"=>$test_id,"term"=>$new_term,"report_id"=>0,"lesson_id"=>(int)$_POST['lesson_id'], "answer_no>"=>0));
                    
                    if($is_freshtest == 0){
                        deleteData("running_exam",['user_id'=>$user_id,"lesson_id"=>$_POST['lesson_id'], "cat_id"=>$_POST['cat_id'], "term"=>$new_term]);
//                        $response['okay'] = "here i'm 2";
                    }
                    else{
                        if($_POST['cat_id']!=93){
                            $check_new_data = getTotalRowsByJoin(array("question as q","running_exam as r"), array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"));

                            if($check_new_data == 0){
                                $is_freshtest = 0;
//                                $response['okay'] = "here i'm";
                                deleteData("running_exam",['user_id'=>$user_id,"lesson_id"=>$_POST['lesson_id'], "cat_id"=>$_POST['cat_id'], "term"=>$new_term]);
                            }
                        }
                    }
                    
                    $total_answer = (int)getTotalRow("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"test_id"=>$test_id,"term"=>$new_term,"report_id"=>0,"lesson_id"=>(int)$_POST['lesson_id'], "answer_no>"=>0));
                    
                    if($total_answer){
                        $response["total_answered"]= $total_answer;
                    }
                    
					$response['practice_test_total']=20;
				   
			        if($_POST['cat_id']==57 || $_POST['cat_id']==47)
			        { 
                        if($is_freshtest > 0){
                            $practice_test=getQueryByJoin(array("question as q","running_exam as r"), "q.*, r.id as rid, r.answer_no", array("r.user_id"=>$user_id, "r.lesson_id"=>$_POST['lesson_id'], "r.term"=>$new_term, "r.cat_id"=>$_POST['cat_id'],"q.qtype"=>1),array("r.question_id"=>"q.id"), "r.id", $response['practice_test_total']);
                        }
                        else{
                            $practice_test=getQuery("question","*",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"qtype"=>1), "rand()",$response['practice_test_total']);
                        }
				    }
                    elseif($_POST['cat_id']==93)
				    {
                        if($is_freshtest > 0){
                            $practice_test=getQueryByJoin(array("question as q","running_exam as r"), "q.*, r.id as rid, r.answer_no", array("r.user_id"=>$user_id, "r.lesson_id"=>$_POST['lesson_id'], "r.term"=>$new_term, "r.cat_id"=>$_POST['cat_id'],"q.qtype"=>1),array("r.question_id"=>"q.id"), "r.id", $response['practice_test_total']);
                        }
                        else{
                            $practice_test=getQuery("question","*",array("cat_id in "=>"(47,57)","lesson_id"=>$_POST['lesson_id'],"qtype"=>1), "rand()",$response['practice_test_total']);
                        }
				    }
                    elseif(in_array($_POST['cat_id'],$data['story_question']))
				    {
				     	
				     	if($_POST['cat_id']==80 || $_POST['cat_id']==79)
				     	{
				     		if($_POST['cat_id']==80){
                                $response['practice_test_total']=40; 
                                $q_limit=20;
                            }
                            else{
                                $q_limit=5;
                            }
                            
				     		if($_POST['cat_id']==79){$response['practice_test_total']=30;}
				     			
				     		if($is_freshtest > 0){
                                $story_list=getQueryByJoin(array("question as q","running_exam as r"),"distinct(q.story_id) as story_id",array("r.user_id"=>$user_id, "r.cat_id"=> $_POST['cat_id'], "r.lesson_id"=>$_POST['lesson_id'], "r.term"=>$new_term, "q.story_id!"=>"0"),array("r.question_id"=>"q.id"),"r.id",$response['practice_test_total']);
                            }
                            else{
				            
                                $story_list=getQuery("question","distinct(story_id) as story_id",array("cat_id"=>(int)$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id']),'story_id');
                            }
				     	}
				     	else
				     	{
                            if($_POST['cat_id']==88){
                                $story_limit = 4;
                            }
                            else{
                                $story_limit = 5;
                            }
                            
                            if($is_freshtest > 0){
                                $story_list=getQueryByJoin(array("question as q","running_exam as r"),"distinct(q.story_id) as story_id",array("r.user_id"=>$user_id, "r.cat_id"=> $_POST['cat_id'], "r.lesson_id"=>$_POST['lesson_id'], "r.term"=>$new_term, "q.story_id!"=>"0"),array("r.question_id"=>"q.id"),"r.id",$story_limit);
                            }
                            else{
				            
                                $story_list=getQuery("question","distinct(story_id) as story_id",array("cat_id"=>(int)$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id']),'story_id',$story_limit);
                            }
				     	}	 
					    $answers=array();
					    $correct_answer=array();
					    $story=array();
                        $practice_test=[];
					    $list=[];$serial_no=0;
                        $qlist=[];
					    foreach($story_list as $a)
					    {
					    	$story=getFldValueById("contents","contents",array("id"=>$a['story_id']));
                            
                            if($is_freshtest > 0){
                                $practice_test_tmp=getQueryByJoin(array("question as q","running_exam as r"), "q.*, r.id as rid, r.answer_no", array("r.user_id"=>$user_id, "r.lesson_id"=>$_POST['lesson_id'], "r.term"=>$new_term, "r.cat_id"=>$_POST['cat_id'],"q.story_id"=>$a['story_id']),array("r.question_id"=>"q.id"), "r.id", $q_limit);
                            }
                            else{
                                $practice_test_tmp=getQuery("question","*",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"story_id"=>$a['story_id']), "id", $q_limit);
                            }
                            
                            foreach($practice_test_tmp as $p)
                            {
                                $serial_no++;

    //                            ********* insert new questions into running exam *******
                                $rid=0;
                                if($is_freshtest == 0){
                                    $rid = insertTable("running_exam",["ord"=>(int)$serial_no, 'cat_id'=>$_POST['cat_id'],'lesson_id'=>$_POST['lesson_id'],'question_id'=>$p['id'],'user_id'=>$user_id, 'term'=>$new_term, 'report_id'=>0, 'date'=>date('Y-m-d'), 'test_id'=> $test_id]);
                                }
                                 if($rid){
                                     $p['rid'] = $rid;
                                 }
    //                           ************** end ***********
                                $qlist[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))), "serial_no"=>$serial_no,"id"=>$p['id'],"rid"=>$p['rid'],"cat_id"=>$_POST['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$p['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$p['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$p['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$p['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$p['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$p['option_e']))),"answer"=>$p['answer'], "my_answer"=>$p['answer_no']);
                            }
                            
//							$list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))),"practice_test_questions"=>$qlist); 
					   
						}
						 
						$response['cat_id']=$_POST['cat_id'];
				        $response['lesson_id']=$_POST['lesson_id']; 
						$response['term']=$new_term;
			  		    $response['practice_test_questions']=$qlist;
				    }				        
				    else
			        {
						if($_POST['cat_id']==18 || $_POST['cat_id']==78){$response['practice_test_total']=25;}
				        $practice_test_total=getTotalRow("questions",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"qtype"=>1));
			            
			            if($practice_test_total>=$response['practice_test_total'])
			            {
                            if($is_freshtest > 0){
                                $practice_test=getQueryByJoin(array("question as q","running_exam as r"), "q.*, r.id as rid, r.answer_no", array("r.user_id"=>$user_id, "r.lesson_id"=>$_POST['lesson_id'], "r.term"=>$new_term, "r.cat_id"=>$_POST['cat_id'],"q.qtype"=>1),array("r.question_id"=>"q.id"), "r.id", $response['practice_test_total']);
                            }
                            else{
                                $practice_test=getQuery("question","*",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id'],"qtype"=>1), "rand()", $response['practice_test_total']);
                            }
			            }
			            else
			            {
			            	if($is_freshtest > 0){
                                $practice_test=getQueryByJoin(array("question as q","running_exam as r"), "q.*, r.id as rid, r.answer_no", array("r.user_id"=>$user_id, "r.lesson_id"=>$_POST['lesson_id'], "r.term"=>$new_term, "r.cat_id"=>$_POST['cat_id']),array("r.question_id"=>"q.id"), "r.id", $response['practice_test_total']);
                            }
                            else{
                                $practice_test=getQuery("question","*",array("cat_id"=>$_POST['cat_id'],"lesson_id"=>$_POST['lesson_id']), "rand()", $response['practice_test_total']);
                            }
			            }
					}
                    
					if(!$response['practice_test_questions'])
					{
                        $serial_no=0;
				    	$question=array();
                        
                        foreach($practice_test as $a)
				    	{
                            $serial_no++;
							
//                          ********* insert new questions into running exam *******
                            $rid=0;
                            if($is_freshtest == 0){
                                if($a['story_id']==null){
                                    $a['story_id']=0;
                                }

                                $rid = insertTable("running_exam",["ord"=>(int)$serial_no, 'cat_id'=>$_POST['cat_id'],'lesson_id'=>$_POST['lesson_id'],'question_id'=>$a['id'],'user_id'=>$user_id, 'term'=>$new_term, 'report_id'=>0, 'date'=>date('Y-m-d'), 'test_id'=> $test_id]);
                            }
                             if($rid){
                                 $a['rid'] = $rid;
                             }
//                           ************** end ***********
                            $question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"rid"=>$a['rid'],"cat_id"=>$_POST['cat_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_e']))),"answer"=>$a['answer'], "my_answer"=>$a['answer_no']);
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
	             
				if(!$_POST['term']){
                    $_POST['term']=getFldValueById("prediction_exam","term",array("id"=>$_POST['pid']));
                }
                
				if($_POST['answer']==$_POST['answer_no']) 
				{
					$correct=1;
				}
			   
               $exist=getTotalRow("prediction_exam",array("id"=>$_POST['pid']));
                
				if($exist)
				{
					$id = updateTable("prediction_exam",["answer_no"=>$_POST['answer_no'],"correct"=>(int)$correct],array("id"=>$_POST['pid']));
				}
				 $total_answer=getTotalRow("prediction_exam",array("term"=>$_POST['term'],"test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id, "answer_no>"=>0, "report_id"=>0));
			
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
                    
			    	$response['cat_id']=$_POST['cat_id'];
                    $response['term']=$_POST['term'];
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
                    $ord = 0;
	        		$term=getFldValueById("report","max(term)",array("cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"user_id"=>$user_id));
    	   			
                    if($_POST['test_type']=="freshtest")
				    {               
                        $is_freshtest = 0;
                        
                        deleteData("prediction_exam",['user_id'=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>((int)$term+1),"test_id"=>$test_id,"report_id"=>0]);
                    }
                    else{
                        if($_POST['subject_id']){
                            $is_freshtest = (int)getTotalRow("prediction_exam", array("user_id"=>$user_id, "cat_id"=>$_POST['cat_id'], "lesson_id"=>$_POST['subject_id'],"term"=>((int)$term+1),"test_id"=>$test_id, "report_id"=>0));
                            
//                            if($is_freshtest==0){
//                                deleteData("prediction_exam",['user_id'=>$user_id,"cat_id"=>$_POST['cat_id'], "lesson_id"=>$_POST['subject_id'],"term"=>((int)$term+1),"test_id"=>$test_id,"report_id"=>0]);
//                            }
                        }
                        else{
                            $is_freshtest = (int)getTotalRow("prediction_exam", array("user_id"=>$user_id, "cat_id"=>$_POST['cat_id'],"term"=>((int)$term+1),"test_id"=>$test_id, "report_id"=>0));
//                            if($is_freshtest==0){
//                                deleteData("prediction_exam",['user_id'=>$user_id,"cat_id"=>$_POST['cat_id'], "term"=>((int)$term+1),"test_id"=>$test_id,"report_id"=>0]);
//                            }
                        }
                    }
                    
//                    $response["check_freshtest"] = $is_freshtest;
                    
                    $response["completed_total"]= (int)getTotalRow("prediction_exam", array("user_id"=>$user_id, "cat_id"=>$_POST['cat_id'], "term"=>((int)$term+1),"test_id"=>$test_id, "report_id"=>0, "answer_no>"=>0));
                    
                    $response['continue_msg']= "You have already answered ".$response['completed_total']." question(s) from this Prediction Test. Do you want to continue?";
                    
			        $shortcode=getFldValueById("category","shortcode",array("cat_id"=>$_POST['cat_id']));
		            $prediction=$data[$shortcode];
		            $total_question = 0;
                    
		    		$flag = 1;
                    foreach($prediction as $key=>$limit)
		    		{
                        $total_question += $limit;
                        $subject_info = getDataByWhere("category","*",array("shortcode"=>$key));
                        
                        $cat_id = (int)$subject_info['cat_id'];
                        $val = $subject_info['cat_name'];
						$test[$val]=array("subject_name"=>$val,"subject_id"=>$cat_id,"cat_id"=>$_POST['cat_id'],"total"=>$limit);
                        
                        if(!$_POST['subject_id'] && $flag){
                            $_POST['subject_id'] = $cat_id;
                            $flag=0;
                        }
                        if($_POST['subject_id'] == $cat_id){
                            $subject_name = $val;
                            $sub_total = $limit;
                        }
                        
                    }
                    $response['total_question'] = $total_question;
                    $response['pred_subject']=$test;
				    
                    $sub_id = (int)$_POST['subject_id'];
                    
                    if($sub_id==87 || $sub_id==80 || $sub_id==88 || $sub_id==90 || $sub_id==79)
				    {
						$option_no=5;
				    }
				    else
				    {
				        $option_no=4;
				    }	
						
						#story
                    $lessons_text=[];
				
				    if($sub_id==93)
				    {
				        $lessons_list=getQuery("lesson","lesson_id",array("cat_id in "=>"(47,57)","status"=>"Y"));
						
				    }
				    elseif($sub_id==80)
				    {
                        $lessons_list=getQuery("lesson","lesson_id",array("cat_id"=>$sub_id,"status"=>"Y"),"lesson_id",1);
				    }	
				    else
				    {
				        $lessons_list=getQuery("lesson","lesson_id",array("cat_id"=>$sub_id,"status"=>"Y"),"rand()");
						
				    }	
						
				    foreach($lessons_list as $l)
				    {
				        $lessons_text[]=$l['lesson_id'];
				    }
						
				    $lessons=implode(",",$lessons_text);
						
					$story_question=getTotalRow("questions",array("lesson_id in "=>"(".$lessons.")","cat_id"=>(int)$sub_id,"status"=>"Y","story_id >"=>0));
		    	 	       
                    if($sub_id==93)
				    {
                        if($is_freshtest > 0){
                            $question_test=getQueryByJoin(array("question as q","prediction_exam as p"), "q.*, p.id as pid, p.answer_no", array("p.user_id"=>$user_id, "p.lesson_id"=>$sub_id, "p.term"=>$term+1, "p.cat_id"=>$_POST['cat_id'],"p.test_id"=>$test_id, "p.report_id"=>0),array("p.question_id"=>"q.id"), "p.id", $test[$subject_name]['total']);
                        }
                        else{
                            $question_test=getQuery("question","*",array("cat_id in "=>"(47,57)","lesson_id in "=>"(".$lessons.")"), "rand()",$test[$subject_name]['total']);
                        }
				    }
				    elseif(in_array($sub_id,$data['story_question']))
				    {
				        $tot=0;$story_list_array=[];
			        
			            $story_list=[];
								
				        if($sub_id==103 || $sub_id==102 || $sub_id==79 || $sub_id==80)
			        	{
			        	    $lessons_id=$lessons_text[0];
                                
			        	    if($is_freshtest > 0){
                                $story_list_array=getQueryByJoin(array("question as q","prediction_exam as p"),"distinct(q.story_id) as story_id",array("p.user_id"=>$user_id, "p.cat_id"=> (int)$_POST['cat_id'], "p.lesson_id"=>$sub_id,"p.test_id"=>$test_id, "p.term"=>((int)$term+1), "q.story_id!"=>"0"),array("p.question_id"=>"q.id"),"p.id");
                            }
                            else{
                                $story_list_array=getQuery("question","distinct(story_id) as story_id",array("cat_id"=>(int)$sub_id,"lesson_id"=>$lessons_id,"story_id >"=>0));
                            }
	    	 	 
			        	}
				        elseif($sub_id==19)
				        {
				            $story_limit=5;
				            if($_POST['cat_id']==105)
				            {
								$story_limit=6;
				            }
                                
				            if($is_freshtest > 0){
								$story_list_array=getQueryByJoin(array("question as q","prediction_exam as p"),"distinct(q.story_id) as story_id",array("p.user_id"=>$user_id, "p.cat_id"=> (int)$_POST['cat_id'], "p.lesson_id"=>$sub_id,"p.test_id"=>$test_id, "p.term"=>((int)$term+1), "q.story_id!"=>"0"),array("p.question_id"=>"q.id"),"p.id",$story_limit);
                            }
                            else{
                                $story_list_array=getQuery("question","distinct(story_id) as story_id",array("cat_id"=>(int)$sub_id,"story_id >"=>0),"rand()",$story_limit);
                            } 
						  }
						  else
						  {
                              if($is_freshtest > 0){
                                  $story_list1=getQueryByJoin(array("question as q","prediction_exam as p"),"distinct(q.story_id) as story_id",array("p.user_id"=>$user_id, "p.cat_id"=> (int)$_POST['cat_id'], "p.lesson_id"=>$sub_id,"p.test_id"=>$test_id, "p.term"=>((int)$term+1), "q.story_id!"=>"0"),array("p.question_id"=>"q.id"),"p.id");
                               }
                               else{
                                    $story_list1=getQuery("question","distinct(story_id) as story_id",array("cat_id"=>(int)$sub_id,"story_id >"=>0),"rand()");
                                }
				    	 	    
				    	 	    foreach($story_list1 as $d)
							    {
									$totn=getTotalRow("question",array("story_id"=>$d['story_id']));
									
							        if(($tot+$totn)==$test[$subject_name]['total'])
							        {
							        	$story_list_array[]=['story_id'=>$d['story_id']];
							        	break;
							        }
							        elseif(($tot+$totn)< $test[$subject_name]['total'])
							        {
							        	$tot=$tot+$totn;
							        	$story_list_array[]=['story_id'=>$d['story_id']];
							        }
							          
								}
							}	
							
						    $story=array();
						    $question_test=[];
						    $list=[];
						    $question=array();
                            $serial_no=0;
						    foreach($story_list_array as $a)
						    {
						    	$story=getFldValueById("contents","contents",array("id"=>$a['story_id']));
						    	
						    	if($sub_id==19)
						    	{
                                    if($is_freshtest > 0){
                                        $question_list=getQueryByJoin(array("question as q","prediction_exam as p"), "q.*, p.id as pid, p.answer_no", array("p.user_id"=>$user_id, "p.lesson_id"=>$sub_id, "p.term"=>$term+1, "p.cat_id"=>$_POST['cat_id'],"p.test_id"=>$test_id,"q.story_id"=>$a['story_id']),array("p.question_id"=>"q.id"), "p.id", 4);
                                    }
                                    else{
                                        $question_list=getQuery("question","*",array("cat_id"=>$sub_id,"lesson_id in "=>"(".$lessons.")","story_id"=>$a['story_id']), "id", 4);
                                    }
						    	}
						    	elseif($sub_id==88 || $sub_id==87)
						    	{
                                    if($is_freshtest > 0){
                                        $question_list=getQueryByJoin(array("question as q","prediction_exam as p"), "q.*, p.id as pid, p.answer_no", array("p.user_id"=>$user_id, "p.lesson_id"=>$sub_id, "p.term"=>$term+1, "p.cat_id"=>$_POST['cat_id'],"p.test_id"=>$test_id,"q.story_id"=>$a['story_id']),array("p.question_id"=>"q.id"), "p.id", '5');
                                    }
                                    else{
                                        $question_list=getQuery("question","*",array("cat_id"=>$sub_id,"lesson_id in "=>"(".$lessons.")","story_id"=>$a['story_id']), "id", "5");
                                    }
						    	}	
						    	else
						    	{
                                    if($is_freshtest > 0){
                                        $question_list=getQueryByJoin(array("question as q","prediction_exam as p"), "q.*, p.id as pid, p.answer_no", array("p.user_id"=>$user_id, "p.lesson_id"=>$sub_id, "p.term"=>$term+1, "p.cat_id"=>$_POST['cat_id'],"p.test_id"=>$test_id,"q.story_id"=>$a['story_id']),array("p.question_id"=>"q.id"), "p.id");
                                    }
                                    else{
                                        $question_list=getQuery("question","*",array("cat_id"=>$sub_id,"lesson_id in "=>"(".$lessons.")","story_id"=>$a['story_id']), "id");
                                    }
						    	}	
						    	
								foreach($question_list as $a)
								{
                                    $serial_no++;
                                    
//                              ********* insert new questions into running exam *******
                                    $rid=0;
                                    if($is_freshtest == 0){
                                        $pid = insertTable("prediction_exam",[ 'cat_id'=>$_POST['cat_id'],'lesson_id'=>$sub_id,'question_id'=>$a['id'],'user_id'=>$user_id, 'term'=>$term+1, 'report_id'=>0, 'date'=>date('Y-m-d'), 'test_id'=> $test_id]);
                                    }
                                    if($pid){
                                         $a['pid'] = $pid;
                                    }
//                               ************** end ***********
                                    $question[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))), "serial_no"=>$serial_no,"id"=>$a['id'],"pid"=>$a['pid'],"cat_id"=>$_POST['cat_id'],"subject_id"=>$sub_id,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_e']))),"answer"=>$a['answer'], "my_answer"=>$a['answer_no']);
                                } 
						
//						       $list[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode(html_entity_decode($story))))),"questions"=>$question);
						        
						    }     
                            $questions_list=array("cat_id"=>$_POST['cat_id'],"subject_id"=>$sub_id,"subject_name"=>$subject_name,"total"=>$sub_total,"term"=>$term+1,"questions"=>$question);
				  		}				  	 	
						else
						{
                            if($is_freshtest > 0){
                                $question_test=getQueryByJoin(array("question as q","prediction_exam as p"), "q.*, p.id as pid, p.answer_no", array("p.user_id"=>$user_id, "p.lesson_id"=>$sub_id, "p.term"=>$term+1, "p.cat_id"=>$_POST['cat_id'],"p.test_id"=>$test_id, "p.report_id"=>0),array("p.question_id"=>"q.id"), "p.id",$test[$subject_name]['total']);
                            }
                            else{
                                $question_test=getQuery("question","*",array("cat_id"=>$sub_id,"lesson_id in "=>"(".$lessons.")"), "rand()",$test[$subject_name]['total']);
                            }
						}
                        
						if(!in_array($sub_id,$data['story_question'])) 
						{  
					    	$serial_no=0;
					    	$question=array();
					    	$total=0;
                            
							foreach($question_test as $a)
							{
                                $serial_no++;
//                              ********* insert new questions into running exam *******
                                $rid=0;
                                if($is_freshtest == 0){
                                    if($a['story_id']==null){
                                        $a['story_id']=0;
                                    }

                                    $pid = insertTable("prediction_exam",['cat_id'=>$_POST['cat_id'],'lesson_id'=>$sub_id,'question_id'=>$a['id'],'user_id'=>$user_id, 'term'=>$term+1, 'report_id'=>0, 'date'=>date('Y-m-d'), 'test_id'=> $test_id]);
                                }
                                if($pid){
                                    $a['pid'] = $pid;
                                }
//                               ************** end ***********
                                $question[]=array("serial_no"=>$serial_no,"id"=>$a['id'],"pid"=>$a['pid'],"cat_id"=>$_POST['cat_id'],"subject_id"=>$_POST['subject_id'],"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_e']))),"answer"=>$a['answer'], "my_answer"=>$a['answer_no']);
                            }
                            $questions_list=array("cat_id"=>$_POST['cat_id'],"subject_id"=>$sub_id,"subject_name"=>$subject_name,"total"=>$sub_total,"term"=>$term+1,"questions"=>$question);
					    } 
					
                        $response['test']=$questions_list;
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
		       
			    $correct="0";
			   
				if($_POST['answer']==$_POST['answer_no']) 
				{
					$correct=1;
				}
                
				$exist=getTotalRow("running_exam",array("id"=>$_POST['rid']));
                
				if($exist)
				{
					$id = updateTable("running_exam",["answer_no"=>$_POST['answer_no'],"correct"=>(int)$correct],array("id"=>$_POST['rid']));
				}
    	         
                $term = $_POST['term'];
                $total_answer=getTotalRow("running_exam",array("cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"test_id"=>$test_id,"term"=>$term,"report_id"=>0,"lesson_id"=>(int)$_POST['lesson_id'], "answer_no>"=>0));
				
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
				   // $array['pending']="";
				}
				else
				{
					$response['message']="Congratulation ! You have successfully answered all questions. Check your score.";
					
					$response['cat_id']=$_POST['cat_id'];
					$response['lesson_id']=$_POST['lesson_id'];
					$response['term']=$term;
					$response['total']=$_POST['total'];
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
					    	$result[]=array("category"=>$cat_name,"cat_id"=>$d['cat_id'],"lesson_id"=>$d['lesson_id'],"lesson_name"=>$lesson_name,"term"=>$last_report['term'],"date"=>showdate($last_report['date']),"time_taken"=>(int)$last_report['time'],"correct"=>$last_report['correct'],"total_questions"=>$last_report['total'],"wrong"=>$last_report['wrong'],"score"=>$last_report['score']);
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
			  		
			  		$prediction[]=array("category"=>$cat_name,"cat_id"=>$d['cat_id'],"score"=>$last_rec['score'],"date"=>showdate($last_rec['date']),"time_taken"=>(int)$last_rec['time'],"term"=>$last_rec['term']);
			  	}	
			  	//if($prediction)
			  	//{
			  		$response['prediction-test']=$prediction;
			  	//}
			    
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";

			}	
			
		break;

		case "results-using-user-id":
		    
		    $exist=(int)$_POST['user_id'];

		    if($exist)
		    {
		        // $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		       
		        $user_id=(int)$_POST['user_id'];
		        $test_id=(int)getFldValueById("student","test_id",array("user_id"=>$user_id));	        
		        
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
					    	$result[]=array("category"=>$cat_name,"cat_id"=>$d['cat_id'],"lesson_id"=>$d['lesson_id'],"lesson_name"=>$lesson_name,"term"=>$last_report['term'],"date"=>showdate($last_report['date']),"time_taken"=>(int)$last_report['time'],"correct"=>$last_report['correct'],"total_questions"=>$last_report['total'],"wrong"=>$last_report['wrong'],"score"=>$last_report['score']);
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
			  		
			  		$prediction[]=array("category"=>$cat_name,"cat_id"=>$d['cat_id'],"score"=>$last_rec['score'],"date"=>showdate($last_rec['date']),"time_taken"=>(int)$last_rec['time'],"term"=>$last_rec['term']);
			  	}	
			  	//if($prediction)
			  	//{
			  		$response['prediction-test']=$prediction;
			  	//}
			    
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";

			}	
			
		break;
		
        case "practice-test-submission":
            
            $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];
		        
                $total=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$_POST['term'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
                $total_correct=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$_POST['term'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>1));
			     $total_wrong=getTotalRow("running_exam",array("test_id"=>$test_id,"term"=>$_POST['term'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id'],"correct"=>0));
			     
			     $total_answer_given=$total_correct+$total_wrong;
			     
			     $total_answer_not_given=$total-$total_answer_given;
			    
			     $score=($total_correct/$total)*100;
			    
			     $exam_time=getFldValueById("login","exam_time",array("user_id"=>$user_id));
			      
			     $report_id=insertTable("report",array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>(int)$_POST['term'],"cat_id"=>(int)$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'],"total"=>(int)$total,"correct"=>(int)$total_correct,"wrong"=>(int)$total_wrong,"score"=>(int)$score,"date"=>date("Y-m-d"),"time"=>(int)$exam_time));
                    
			     updateTable("running_exam",array("report_id"=>(int)$report_id),array("test_id"=>$test_id,"user_id"=>$user_id,"term"=>$_POST['term'],"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id']));
                
                
		        if($_POST['term'])
		    	{
		    		$new_term=$_POST['term'];
		    	}
		    	else
		    	{
		    		$term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	       	   // echo getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']),1);
					$new_term=(int)$term; 
		    	}	
		                 	  
				$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
				     
				$lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$_POST['lesson_id']));
				$report=getDataByWhere("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id']));
				$response['report']=array("term"=>$new_term,"cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"lesson_id"=>$_POST['lesson_id'],"lesson_name"=>$lesson_name,"test_id"=>$test_id,"date"=>showdate($report['date']),"time_taken"=>(int)$report['time'],"correct"=>$report['correct'],"total_questions"=>$report['total'],"wrong"=>$report['wrong'],"score"=>$report['score']);
				
                deleteData("lastexam",['user_id'=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$_POST['lesson_id'], "grade"=>$test_id,"term"=>$new_term]);
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";
 
			}	
		break;
        
        case "prediction-test-submission":
            
            $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByJoin(array("login as l","student as s"),"l.*,s.test_id",array("l.token"=>$_POST['authorizationToken']),array("l.user_id"=>"s.user_id"));
		        
		        $user_id=$user['user_id'];
		        $test_id=$user['test_id'];
		        
                $shortcode=getFldValueById("category","shortcode",array("cat_id"=>$_POST['cat_id']));
		        $at=$data[$shortcode];
                $cats_total=0;
                $st=0;
                $total_ques = 0;
                $total_correct = 0;
                
		        foreach($at as $key=>$total)
				{
				    $cat_id=getFldValueById("category","cat_id",array("shortcode"=>$key));
						
				    $category_score=0;
                    
                    $correct=(int)getTotalRow("prediction_exam",array("correct"=>1,"term"=>$_POST['term'],"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>$cat_id));
					   
                    if($_POST['cat_id']>=101 && $_POST['cat_id']<=106){
                        $category_score_per =round(($correct/$total)*100);
						$category_score = $afoqt_pred_score_array[$category_score_per];
                    }
                    else{
                        
                        $category_score_per = round(($correct/$total)*100);
                        
                        if($category_score_per<25){
                            $category_score = 20;
                        }
                        elseif($category_score_per>100){
                            $category_score = 80;
                        }
                        else{
                            $category_score = $pred_score_array[$category_score_per];
                        }
                    }
                    
				    	
				     $st+=$category_score;
				     $total_ques+=$total;
                     $total_correct+=$correct;
		             $wrong=$total-$correct;
		                
		             $exam_time= date('H:i:s');
                    
				     $report_id =  insertTable("report",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"test_id"=>$test_id,"lesson_id"=>(int)$cat_id,"total"=>(int)$total,"correct"=>(int)$correct,"wrong"=>(int)$wrong,"score"=>$category_score,"term"=>(int)$_POST['term'],"date"=>date("Y-m-d")));
		                
                      updateTable("prediction_exam",["report_id"=>$report_id],['user_id'=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id"=>(int)$cat_id,"term"=>$_POST['term'],"test_id"=>$test_id]);
                        
		              $cats_total++;
				}
                
                if($_GET['cat_id']>=101 && $_GET['cat_id']<=106){
					$score = round(($total_correct/$total_ques)*100);
					// $score = $data['afoqt_scores'][$score];  
					$score = $afoqt_pred_score_array[$score]; 
				}
				else{
					// $score = round($total_score/$cats_total);
					$score = round(($total_correct/$total_ques)*100);
					$score = $pred_score_array[$score]; 
				}
                
//                $score=getFldValueById("prediction_score","score",array("total"=>round($st/$cats_total),"test_id"=>$test_id));
		  
				insertTable("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"score"=>(int)$score,"date"=>date("Y-m-d"),"term"=>$_POST['term'],"test_id"=>$test_id));
                
		        $response['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id'])); 
			    
			    if(!$_POST['term'])
			    {
                    $_POST['term']=getFldValueById("score_card","max(term)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id']));
				
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
				   
				    $afqt_report[$val]=$score;
				
				    $j++;
				}
					
				$total=getFldValueById("report","sum(total)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
                $total_correct=getFldValueById("report","sum(correct)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
				
				$total_wrong=$total-$total_correct;
				$response['afqt_score']=getFldValueById("score_card","score",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
                    
				$response['afqt_report']=$afqt_report;
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
    	       	   // echo getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']),1);
					$new_term=(int)$term; 
		    	}	
		                 	  
				$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
				     
				$lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$_POST['lesson_id']));
				$report=getDataByWhere("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id']));
				$response['report']=array("term"=>$new_term,"cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"lesson_id"=>$_POST['lesson_id'],"lesson_name"=>$lesson_name,"test_id"=>$test_id,"date"=>showdate($report['date']),"time_taken"=>(int)$report['time'],"correct"=>$report['correct'],"total_questions"=>$report['total'],"wrong"=>$report['wrong'],"score"=>$report['score']);
				
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";
 
			}	
		break;

		case "result-using-user-id":
		
		    $exist=(int)$_POST['user_id'];
		
		    if($exist)
		    {
		        $user_id=(int)$_POST['user_id'];
		        $test_id=(int)getFldValueById("student","test_id",array("user_id"=>$user_id));	        
		        
		        
		        if($_POST['term'])
		    	{
		    		$new_term=$_POST['term'];
		    	}
		    	else
		    	{
		    		$term=getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']));
    	       	   // echo getFldValueById("report","max(term)",array("test_id"=>$test_id,"cat_id"=>$_POST['cat_id'],"user_id"=>$user_id,"lesson_id"=>(int)$_POST['lesson_id']),1);
					$new_term=(int)$term; 
		    	}	
		                 	  
				$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id']));
				     
				$lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$_POST['lesson_id']));
				$report=getDataByWhere("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id']));
				$response['report']=array("term"=>$new_term,"cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"lesson_id"=>$_POST['lesson_id'],"lesson_name"=>$lesson_name,"test_id"=>$test_id,"date"=>showdate($report['date']),"time_taken"=>(int)$report['time'],"correct"=>$report['correct'],"total_questions"=>$report['total'],"wrong"=>$report['wrong'],"score"=>$report['score']);
				
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";
 
			}	
		break;
		
        case "review-test": //after submiting practice test, view the questions on clicking "review test" button
		
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
				$report=getDataByWhere("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id']));
				$response['report']=array("term"=>$new_term,"cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"lesson_id"=>$_POST['lesson_id'],"lesson_name"=>$lesson_name,"test_id"=>$test_id,"date"=>showdate($report['date']),"time_taken"=>(int)$report['time'],"correct"=>$report['correct'],"total_questions"=>$report['total'],"wrong"=>$report['wrong'],"score"=>$report['score']);
				 
				$qlist=[]; $story=[];
		        $i=0;$list=[];
				$flag=0;$srno=0;
		 		 
		        #story
		        $story_exist=getTotalRow("contents",array("parent_id"=>$_POST['cat_id']));
		        
		        if(in_array($_POST['cat_id'],$data['story_question']))
			    {
                    $check_new_student = getTotalRowsByJoin(array("question as q","running_exam as r"), array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id,"q.story_id >"=>0),array("q.id"=>"r.question_id"));
                    
                    if($check_new_student){
                      
                        $story_list=getQueryByJoin(array("question as q","running_exam as r"),"q.story_id",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>(int)$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id,"q.story_id >"=>0),array("q.id"=>"r.question_id"),'','','','q.story_id');
                        $qlist=[];
                        foreach($story_list as $d)
                        {
                            $question_test[$d['story_id']]=getQueryByJoin(array("question as q","running_exam as r"),"q.*,r.answer_no",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.story_id"=>$d['story_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.ord");

                            $story=getFldValueById("contents","contents",array("id"=>$d['story_id']));
                            foreach($question_test[$d['story_id']] as $a)
                            {
                                $i++; 

                                if(!$a['answer_no'])
                                {
                                    $text="No Answer";
                                }
                                elseif($a['answer_no']==$a['answer'])
                                {
                                    $text='Correct';
                                }
                                else
                                {
                                    $text='Wrong Answer';
                                }

                                $correct_answer=$data['alphabet'][$a['answer']]." | Your answer is ".  $data['alphabet'][$a['answer_no']]." | " . $text;
                                $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));

                                $qlist[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))), "srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_e']))),"correct_answer"=>$correct_answer, "myanswer"=>$a['answer_no'],"explanation"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));
                            } 
                        } 
                    }else{
                        if($_POST['cat_id']==80 || $_POST['cat_id']==79){
                            $option_no=5;
                        }
                        else{
                            $option_no=4;
                        }
                        
                        $story_list=getQueryByJoin(array("questions as q","running_exam as r"),"q.story_id",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>(int)$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id,"q.story_id >"=>0),array("q.id"=>"r.question_id"),'','','','q.story_id');
	                    
                        $qlist=[];
                        foreach($story_list as $d)
                        {
                            $question_test[$d['story_id']]=getQueryByJoin(array("questions as q","running_exam as r"),"q.*,r.question_id",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.story_id"=>$d['story_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.question_id");

                            $story=getFldValueById("contents","contents",array("id"=>$d['story_id']));
                            
                            foreach($question_test[$d['story_id']] as $a)
                            {
                                $tmp_array = array();
                                $i++;
                                $qa=getDataByWhere("question_answer","*",["question_id"=>$a['question_id']]);
                                $my_answer=getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'],"term"=>$new_term));
                                if(!$my_answer)
                                {
                                    $text="No Answer";
                                }
                                elseif($my_answer==$qa['answer_no'])
                                {
                                    $text='Correct';
                                }
                                else
                                {
                                    $text='Wrong Answer';
                                }

                                $correct_answer=$data['alphabet'][$qa['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
                                 $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$qa['explanation']))));

                                $answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);

                                $srl = 1;
                                foreach($answers as $c)
                                {
                                   $option = "option_" . chr(96 + $srl); $tmp_array[$option]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
                                    $srl++;
                                }

                                $tmp_array["srno"]=$i; $tmp_array["question"]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question'])));
                                $tmp_array["correct_answer"]=$correct_answer;
                                $tmp_array["myanswer"]=$my_answer;
                                $tmp_array["explanation"]=$explanation;
                                $tmp_array["story"]=str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story))));
                                $qlist[]=$tmp_array;   
                            }
                        } 
                    }
                    
				 	$response['qlist']=$qlist; 
			    }
			    else	
			    {
                    $check_new_student = getTotalRowsByJoin(array("question as q","running_exam as r"), array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"));
                    
                    if($_POST['cat_id']==93){
                        $check_new_student = 1;
                    }
                    
                    if(!$check_new_student){
                        $question_test=getQueryByJoin(array("questions as q","running_exam as r"),"q.*,r.question_id,r.answer_no",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.ord");
                    }
                    else{
                        $question_test=getQueryByJoin(array("question as q","running_exam as r"),"q.*,r.question_id,r.answer_no",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.ord");
                    }
			    }	
			    
		        if(!$response['qlist'])
		        {	
		            $i=0;
                    if($_POST['cat_id']==80 || $_POST['cat_id']==79){
                        $option_no=5;
                    }
                    else{
                        $option_no=4;
                    }
                    
                    if(!$check_new_student){
                        foreach($question_test as $a)
                        {
                            $tmp_array = array();
                            $i++;
                            $qa=getDataByWhere("question_answer","*",["question_id"=>$a['question_id']]);
                            $my_answer=getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'],"term"=>$new_term));
                            if(!$my_answer)
                            {
                                $text="No Answer";
                            }
                            elseif($my_answer==$qa['answer_no'])
                            {
                                $text='Correct';
                            }
                            else
                            {
                                $text='Wrong Answer';
                            }

                            $correct_answer=$data['alphabet'][$qa['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
                             $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$qa['explanation']))));

                            $answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
                            
                            $srl = 1;
                            foreach($answers as $c)
                            {
                               $option = "option_" . chr(96 + $srl); $tmp_array[$option]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
                                $srl++;
                            }
                            
                            $tmp_array["srno"]=$i; $tmp_array["question"]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question'])));
                            $tmp_array["correct_answer"]=$correct_answer;
                            $tmp_array["myanswer"]=$my_answer;
                            $tmp_array["explanation"]=$explanation;
                            
                            $qlist[]=$tmp_array;                              
                        }
                    }
                    
                    else{
                        foreach($question_test as $a)
                        {
                            $i++;
                             $my_answer= $a['answer_no'];
                            if(!$my_answer)
                            {
                                $text="No Answer";
                            }
                            elseif($my_answer==$a['answer'])
                            {
                                $text='Correct';
                            }
                            else
                            {
                                $text='Wrong Answer';
                            }

                            $correct_answer=$data['alphabet'][$a['answer']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
                            $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));
                            $qlist[]=array("srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_e']))),"correct_answer"=>$correct_answer, "myanswer"=>$a['answer_no'],"explanation"=>$explanation);
                        }
                    }
				 
					$response['qlist']=$qlist;
				}		
				
			}  	
		  	else
	        {
				$response['error']="Token does not exist!";
 
			}	
		break;

		case "review-test-using-user-id": //after submiting practice test, view the questions on clicking "review test" button
		
		    $exist=(int)$_POST['user_id'];
		
		    if($exist)
		    {
		        $user_id=(int)$_POST['user_id'];
		        $test_id=(int)getFldValueById("student","test_id",array("user_id"=>$user_id));	   
		        
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
				$report=getDataByWhere("report","*",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$new_term,"lesson_id"=>(int)$_POST['lesson_id']));
				$response['report']=array("term"=>$new_term,"cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"lesson_id"=>$_POST['lesson_id'],"lesson_name"=>$lesson_name,"test_id"=>$test_id,"date"=>showdate($report['date']),"time_taken"=>(int)$report['time'],"correct"=>$report['correct'],"total_questions"=>$report['total'],"wrong"=>$report['wrong'],"score"=>$report['score']);
				 
				$qlist=[]; $story=[];
		        $i=0;$list=[];
				$flag=0;$srno=0;
		 		 
		        #story
		        $story_exist=getTotalRow("contents",array("parent_id"=>$_POST['cat_id']));
		        
		        if(in_array($_POST['cat_id'],$data['story_question']))
			    {
                    $check_new_student = getTotalRowsByJoin(array("question as q","running_exam as r"), array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id,"q.story_id >"=>0),array("q.id"=>"r.question_id"));
                    
                    if($check_new_student){
                      
                        $story_list=getQueryByJoin(array("question as q","running_exam as r"),"q.story_id",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>(int)$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id,"q.story_id >"=>0),array("q.id"=>"r.question_id"),'','','','q.story_id');
                        $qlist=[];
                        foreach($story_list as $d)
                        {
                            $question_test[$d['story_id']]=getQueryByJoin(array("question as q","running_exam as r"),"q.*,r.answer_no",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.story_id"=>$d['story_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.ord");

                            $story=getFldValueById("contents","contents",array("id"=>$d['story_id']));
                            foreach($question_test[$d['story_id']] as $a)
                            {
                                $i++; 

                                if(!$a['answer_no'])
                                {
                                    $text="No Answer";
                                }
                                elseif($a['answer_no']==$a['answer'])
                                {
                                    $text='Correct';
                                }
                                else
                                {
                                    $text='Wrong Answer';
                                }

                                $correct_answer=$data['alphabet'][$a['answer']]." | Your answer is ".  $data['alphabet'][$a['answer_no']]." | " . $text;
                                $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));

                                $qlist[]=array("story"=>str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story)))), "srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_e']))),"correct_answer"=>$correct_answer, "myanswer"=>$a['answer_no'],"explanation"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));
                            } 
                        } 
                    }else{
                        if($_POST['cat_id']==80 || $_POST['cat_id']==79){
                            $option_no=5;
                        }
                        else{
                            $option_no=4;
                        }
                        
                        $story_list=getQueryByJoin(array("questions as q","running_exam as r"),"q.story_id",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>(int)$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id,"q.story_id >"=>0),array("q.id"=>"r.question_id"),'','','','q.story_id');
	                    
                        $qlist=[];
                        foreach($story_list as $d)
                        {
                            $question_test[$d['story_id']]=getQueryByJoin(array("questions as q","running_exam as r"),"q.*,r.question_id",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.story_id"=>$d['story_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.question_id");

                            $story=getFldValueById("contents","contents",array("id"=>$d['story_id']));
                            
                            foreach($question_test[$d['story_id']] as $a)
                            {
                                $tmp_array = array();
                                $i++;
                                $qa=getDataByWhere("question_answer","*",["question_id"=>$a['question_id']]);
                                $my_answer=getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'],"term"=>$new_term));
                                if(!$my_answer)
                                {
                                    $text="No Answer";
                                }
                                elseif($my_answer==$qa['answer_no'])
                                {
                                    $text='Correct';
                                }
                                else
                                {
                                    $text='Wrong Answer';
                                }

                                $correct_answer=$data['alphabet'][$qa['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
                                 $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$qa['explanation']))));

                                $answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);

                                $srl = 1;
                                foreach($answers as $c)
                                {
                                   $option = "option_" . chr(96 + $srl); $tmp_array[$option]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
                                    $srl++;
                                }

                                $tmp_array["srno"]=$i; $tmp_array["question"]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question'])));
                                $tmp_array["correct_answer"]=$correct_answer;
                                $tmp_array["myanswer"]=$my_answer;
                                $tmp_array["explanation"]=$explanation;
                                $tmp_array["story"]=str_replace('"/uploads/','"https://officers.asvab-tutoring.com/uploads/',html_entity_decode(html_entity_decode(html_entity_decode($story))));
                                $qlist[]=$tmp_array;   
                            }
                        } 
                    }
                    
				 	$response['qlist']=$qlist; 
			    }
			    else	
			    {
                    $check_new_student = getTotalRowsByJoin(array("question as q","running_exam as r"), array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"));
                    
                    if($_POST['cat_id']==93){
                        $check_new_student = 1;
                    }
                    
                    if(!$check_new_student){
                        $question_test=getQueryByJoin(array("questions as q","running_exam as r"),"q.*,r.question_id,r.answer_no",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"q.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.ord");
                    }
                    else{
                        $question_test=getQueryByJoin(array("question as q","running_exam as r"),"q.*,r.question_id,r.answer_no",array("r.lesson_id"=>(int)$_POST['lesson_id'],"r.cat_id"=>$_POST['cat_id'],"r.user_id"=>$user_id,"r.term"=>$new_term,"r.test_id"=>$test_id),array("q.id"=>"r.question_id"),"r.ord");
                    }
			    }	
			    
		        if(!$response['qlist'])
		        {	
		            $i=0;
                    if($_POST['cat_id']==80 || $_POST['cat_id']==79){
                        $option_no=5;
                    }
                    else{
                        $option_no=4;
                    }
                    
                    if(!$check_new_student){
                        foreach($question_test as $a)
                        {
                            $tmp_array = array();
                            $i++;
                            $qa=getDataByWhere("question_answer","*",["question_id"=>$a['question_id']]);
                            $my_answer=getFldValueById("running_exam","answer_no",array("user_id"=>$user_id,"question_id"=>$a['question_id'],"term"=>$new_term));
                            if(!$my_answer)
                            {
                                $text="No Answer";
                            }
                            elseif($my_answer==$qa['answer_no'])
                            {
                                $text='Correct';
                            }
                            else
                            {
                                $text='Wrong Answer';
                            }

                            $correct_answer=$data['alphabet'][$qa['answer_no']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
                             $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$qa['explanation']))));

                            $answers=getQuery("answers","*",array("question_id"=>$a['question_id']),"id",$option_no);
                            
                            $srl = 1;
                            foreach($answers as $c)
                            {
                               $option = "option_" . chr(96 + $srl); $tmp_array[$option]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$c['answer'])));
                                $srl++;
                            }
                            
                            $tmp_array["srno"]=$i; $tmp_array["question"]=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question'])));
                            $tmp_array["correct_answer"]=$correct_answer;
                            $tmp_array["myanswer"]=$my_answer;
                            $tmp_array["explanation"]=$explanation;
                            
                            $qlist[]=$tmp_array;                              
                        }
                    }
                    
                    else{
                        foreach($question_test as $a)
                        {
                            $i++;
                             $my_answer= $a['answer_no'];
                            if(!$my_answer)
                            {
                                $text="No Answer";
                            }
                            elseif($my_answer==$a['answer'])
                            {
                                $text='Correct';
                            }
                            else
                            {
                                $text='Wrong Answer';
                            }

                            $correct_answer=$data['alphabet'][$a['answer']]." | Your answer is ".  $data['alphabet'][$my_answer]." | " . $text;
                            $explanation=html_entity_decode(html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['explanation']))));
                            $qlist[]=array("srno"=>$i,"question"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['question']))),"option_a"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_a']))),"option_b"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_b']))),"option_c"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_c']))),"option_d"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_d']))),"option_e"=>html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['option_e']))),"correct_answer"=>$correct_answer, "myanswer"=>$a['answer_no'],"explanation"=>$explanation);
                        }
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
					    	if(!is_nan(round($score1/$j)))
					        {
					    	   $score_data[]=array("label"=>$label,"score"=>round($score1/$j));
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
							$total_no=getTottalRow("report",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$d['term']));
							
							$total_correct=getFldValueById("report","sum(correct)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$d['term']));
							
							$total_score=getFldValueById("report","sum(score)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"lesson_id !"=>94,"term"=>$d['term']));
							
							$total_wrong=$total-$total_correct;
							
							//$score=getFldValueById("point_score","score",array("total"=>$correct,"cat_id"=>$_POST['cat_id'],"test_id"=>$test_id));
					
							$score=getFldValueById("prediction_score","score",array("test_id"=>$test_id,"total"=>round($total_score/$total_no)));
							
							
							$exist=getTotalRow("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$d['term']));
				
							if(!$exist)
							{
								insertTable("score_card",array("user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"score"=>$score,"date"=>date("Y-m-d"),"term"=>$d['term'],"time"=>(int)$_POST['time']));
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
                    $_POST['term']=getFldValueById("score_card","max(term)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id']));
				
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
				   
				    $afqt_report[$val]=$score;
				
				    $j++;
				}
					
				$total=getFldValueById("report","sum(total)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
                $total_correct=getFldValueById("report","sum(correct)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
				
				$total_wrong=$total-$total_correct;
				$response['afqt_score']=getFldValueById("score_card","score",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
                    
				$response['afqt_report']=$afqt_report;
		    }		  
		    else
		    {
				$response['error']="Token does not exist!";
				$response["isSuccess"]=false;
			}	  
        break;

		case "view-prediction-result-using-user-id":
		
			$exist=(int)$_POST['user_id'];
		
		    if($exist)
		    {
		        $user_id=(int)$_POST['user_id'];
		        $test_id=(int)getFldValueById("student","test_id",array("user_id"=>$user_id));	
		 
			    $response['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_POST['cat_id'])); 
			    
			    if(!$_POST['term'])
			    {
                    $_POST['term']=getFldValueById("score_card","max(term)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id']));
				
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
				   
				    $afqt_report[$val]=$score;
				
				    $j++;
				}
					
				$total=getFldValueById("report","sum(total)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
                $total_correct=getFldValueById("report","sum(correct)",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
				
				$total_wrong=$total-$total_correct;
				$response['afqt_score']=getFldValueById("score_card","score",array("test_id"=>$test_id,"user_id"=>$user_id,"cat_id"=>$_POST['cat_id'],"term"=>$_POST['term']));
                    
				$response['afqt_report']=$afqt_report;
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
					
					$_POST['country_id']=(int)$_POST['country_id'];
					$_POST['state_id']=(int)$_POST['state_id'];
					$_POST['city_id']=(int)$_POST['city_id'];
					$_POST['zip_code']=(int)$_POST['zip_code'];
				      
					$u = updateTable("student",$_POST,array("user_id"=>$user_id)); 
					
                    if($u){
                        $response['message']="Your profile has been successfully updated.";
                        $response['status']=200;
                        $response["isSuccess"]=true;
                    }
		            
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
            
            if($_POST['authorizationToken']){
                
                $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));

                if($exist)
                {
                    $user=getFldValueById("login","user_id",array("token"=>$_POST['authorizationToken']));
                    $student=getFldValueById("student","test_id",array("user_id"=>$user));
                    $_POST['user_id']=$user;
                    $_POST['test_id']=$student;
                    $test_name = getFldValueById("test","name",array("id"=>$_POST['test_id']));
                }
            }
            
            $_POST['date']=date("Y-m-d");
            $_POST['status']="N";
            
            if(!$test_name){
                $test_name = "Officer's";
            }
            
            $_POST['source'] = $test_name . ' app';
			
//            insertTable("enquiry",$_POST);
            
            insertTable("enquiry", array('first_name'=>$_POST['first_name'], 'last_name'=>$_POST['last_name'], 'mobile'=>$_POST['mobile'], 'subject'=>$_POST['subject'],'message'=>$_POST['message'],'date'=>$_POST['date'],'status'=>$_POST['status'],'email'=>$_POST['email'],'source'=>$_POST['source'], 'user_id'=>(int)$_POST['user_id']));
            
            $response['course_name'] = $test_name;
            $response['user_id'] = $_POST['user_id'];
            $response['test_id'] = $_POST['test_id'];
            
            
//            insertTable_asvab("enquiry",array('user_id'=>$_POST['user_id'], 'first_name'=>$_POST['first_name'], 'last_name'=>$_POST['last_name'], 'mobile'=>$_POST['mobile'], 'subject'=>$_POST['subject'],'message'=>$_POST['message'],'date'=>date('Y-m-d H:i:s'),'status'=>$_POST['status'],'email'=>$_POST['email'],'source'=>$test_name['name'] . ' app'));
        	
        	$response['message']="We have received your information. We will contact you soon.";
         				
        	$mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Contact Us"));

        	$body=html_entity_decode($mail['template']);
       		
       		$subject=$mail['subject'];

            $mail_body=str_replace("{name}",$_POST['first_name'] ." ". $_POST['last_name'],$body);
            $mail_body=str_replace("{dotcom}",ucwords($configs['dotcom']),$mail_body);
            $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
            $mail_body=html_entity_decode($mail_body);
            
            smtpmailer($_POST['email'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $mail_body);
            
            
//            $subject = "ASVAB 1-on-1 Tutoring Offer";
//            $email_body = '<html>
//            <body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; color: #333;">
//                <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 8px;">
//                    <p>Hi '.$_POST['first_name'].' '.$_POST['last_name'].',</p>
//                    <p>This is <strong>Deb from ASVAB Tutoring</strong>. We received your tutoring request.</p>
//                    <p><strong>Follow the steps to get started:</strong></p>
//                    <ol style="padding-left: 20px;">
//                        <li style="margin-bottom: 10px;">Download Skype from the <a href="https://apps.apple.com/app/skype/id304878510" style="color: #0073e6; text-decoration: none;">App Store</a> or <a href="https://play.google.com/store/apps/details?id=com.skype.raider" style="color: #0073e6; text-decoration: none;">Google Play</a>.</li>
//                        <li style="margin-bottom: 10px;">Create a Skype account.</li>
//                        <li style="margin-bottom: 10px;">Add us on Skype: <strong>debbrata1</strong> for a free evaluation.</li>
//                    </ol>
//                    <p><strong>Our Reviews:</strong></p>
//                    <p>
//                        <a href="https://www.facebook.com/asvabtutoring" style="color: #0073e6; text-decoration: none;">Facebook</a> | 
//                        <a href="https://g.co/kgs/71MtXgq" style="color: #0073e6; text-decoration: none;">Google</a>
//                    </p>
//                </div>
//                
//                <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px; text-align: center;">
//                    <p style="color: #ff6600; font-weight: bold;">📚 Expand Your Knowledge with Our Additional Courses! 📚</p>
//                    <ul style="list-style-type: none; padding: 0; text-align: left; display: inline-block;">
//                        <li>🔹 <strong>ASVAB Preparation:</strong> Get the best scores with our expert guidance.</li>
//                        <li>🔹 <strong>ASTB Preparation:</strong> Comprehensive resources and tutoring to excel.</li>
//                        <li>🔹 <strong>OAR Preparation:</strong> Customized programs to help you succeed.</li>
//                        <li>🔹 <strong>SIFT Preparation:</strong> Get the best scores with our expert guidance.</li>
//                        <li>🔹 <strong>AFOQT Preparation:</strong> Customized programs to help you succeed.</li>
//                    </ul>
//                    <p><a href="https://ocs.asvab-tutoring.com/" style="display: inline-block; padding: 10px 20px; margin-top: 10px; font-size: 16px; color: #fff; background-color: #0073e6; text-decoration: none; border-radius: 5px;">Discover All Our Courses Here!</a></p>
//                </div>
//
//                <p style="text-align: center;">If you have any questions or need further assistance, feel free to reach out to our team.</p>
//                
//            </body>
//            </html>';
//            $email_body=html_entity_decode(html_entity_decode(html_entity_decode($email_body)));
//            smtpmailer($_POST['email'],$configs['support_mail'], ucwords($configs['dotcom']),$subject, $email_body);

            
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
			$body.="<tr><td>Source</td><td>Officers App</td></tr>";
            $body.="<tr><td>Course Name</td><td>".$test_name."</td></tr>";
			$body.="</table></html></body>"; 
		  
            smtpmailer($configs['support_mail'],$_POST['email'], $_POST['first_name'],$subject, html_entity_decode($body));
       
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
//	            $exp_date = date("Y-m-d", strtotime("+" .$configs['validity'] ." day"));
	            $exp_date = date("Y-m-d", strtotime("+30 day"));
	            updateTable("login",array('paid'=>1,"exp_date"=>$exp_date),array("user_id"=>$user_id));
	            
	            $_POST['course_id']=(int)$_POST['course_id'];
	            
	            insertTable("order",array('user_id'=>$user_id,"amount"=>$_POST['amount'],"date"=>date("Y-m-d"),"exp_date"=>$exp_date,"transaction_id"=>$_POST['transaction_id']));
	           
	            $response['message']="You have successfully paid the subscription fees. Enjoy all test.";
             
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        break;
        case "payment-success":
             
            $_POST['course_id']=(int)$_POST['course_id'];
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
//	            $exp_date = date("Y-m-d", strtotime("+" .$configs['validity'] ." day"));
	            $exp_date = date("Y-m-d", strtotime("+30 day"));
	            updateTable("login",array('paid'=>1,"exp_date"=>$exp_date),array("user_id"=>$user_id));
	            
	            insertTable("order",array('user_id'=>$user_id,"amount"=>$_POST['amount'],"date"=>date("Y-m-d"),"exp_date"=>$exp_date,"transaction_id"=>$_POST['transaction_id']));
	           
	            $response['message']="You have successfully paid the subscription fees. Enjoy all test.";
             
            }
            else
            {
				  
				$response['error']="Token does not exist!";
			 
			}
        break;
        
        case "payment-success-android":
             
            $_POST['course_id']=(int)$_POST['course_id'];
		    $exist=getTotalRow("login",array("token"=>$_POST['authorizationToken']));
		
		    if($exist)
		    {
		        $user=getDataByWhere("login","*",array("token"=>$_POST['authorizationToken']));
		   
		        $user_id=$user['user_id'];
//	            $exp_date = date("Y-m-d", strtotime("+" .$configs['validity'] ." day"));
	            $exp_date = date("Y-m-d", strtotime("+30 day"));
	            
//				$has_transid=getTotalRow("order",array("transaction_id"=>$_POST['transaction_id']));

//				if(!$has_transid){
					$flag = updateTable("login",array('paid'=>1,"exp_date"=>$exp_date),array("user_id"=>$user_id));

//					$flag = insertTable("order",array('user_id'=>$user_id,"amount"=>$_POST['amount'],"date"=>date("Y-m-d"),"exp_date"=>$exp_date,"transaction_id"=>$_POST['transaction_id']));
	            
					if($flag){
						$response['message']="You have successfully paid the subscription fees. Enjoy all test.";
					}else{
						$response['message'] = "Payment was unsuccessful. Please check your payment details or try again later. If the issue persists, contact support.";
					}
//				}else{
//					$response['message']="The transaction ID already exists.";
//				}
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
        case "about-us":
			$response['url']="https://www.asvab-tutoring.com/about-us";
			break;
			
		case "terms-and-conditions":
			$response['url']="https://www.asvab-tutoring.com/terms-of-service";
			break;

        case "privacy-policy":
			$response['url']="https://www.asvab-tutoring.com/privacy-policy";
        	break;
        
 
    }
  
	echo json_encode($response);
	die(); 
 
}
 
    
?>