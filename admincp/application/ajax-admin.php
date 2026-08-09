<?php
include("../common/autoload.php");
$array=array(); 
 
#Update Setting Form==================================  
 
$array=array();
 
 

if($_POST['action'])
{
	$array=array();
	switch($_POST['action'])
	{
	    
        case"admin-login":
        
         	if($_SESSION["captcha_code"]!=$_POST['captchatext'])
         	{
         		$array['msg']="<font color=#FF0000>Error in Security code.</font>";
 
         	} 
         	else
         	{
         		$flag="";
	    		$exist=getTotalRow("ocs_admin",array("password"=>md5($_POST['password']),"username"=>$_POST['username'],"status"=>'Y'));
          
        		if($exist)
        		{
        			$d=getDataByWhere("ocs_admin","*",array("password"=>md5($_POST['password']),"username"=>$_POST['username'],"status"=>'Y'));
       
				    $_SESSION['admin_id'] 	= $d['admin_id'];
			   
				    $_SESSION['username'] 	= $d['username'];
				    $_SESSION['email'] 	    = $d['email'];
				    $_SESSION['reg_date'] 	= $d['reg_date'];
				    $_SESSION['type'] 	    = $d['type'];
			 
		    
		   			$array['next']=$configs['admin_vpath']."account/dashboard";
        		}
        		else
        		{
        			 $array['msg']="<font color=red size=2>Login Failure!!</font><br>";
        		}	
      
         	}	
	 
        break;
        
        case "blog":
		
       
			if($_POST['id'])
			{

				$r=updateTable("ocs_blog",$_POST,array("id"=>$_POST['id']),array("pic"));

			}
			else
			{
				unset($_POST['id']);
				$r=insertTable("ocs_blog",$_POST,array("pic"));

			}
			 
			$error=mysqli_errno($configs['con']);
			if(!$error):
 	  			$array['msg']=str_replace("{table}","Blog",$configs['success_msg']);

 	  			$array['msg'].='<meta http-equiv="refresh" content="3;' .$configs['admin_vpath'].'blog/blog-list">';

			else:
    			$array['msg']=$configs['error_msg'];
		 
			endif;
		break;

        case "faq":
        
	        if($_POST['id']) 
	        {
				$r=updateTable("ocs_faq",$_POST,array("id"=>$_POST['id']));
			} 
			else 
			{
				unset($_POST['id']);
				$r=insertTable("ocs_faq",$_POST);
			}

			$error=mysqli_errno($configs['con']);

			if (!$error)
			{	
				$array['msg']=str_replace("{table}","faq",$configs['success_msg']);
				$array['msg'].="<meta http-equiv='refresh' content=\"3;URL='".$configs['admin_vpath'] . "faq/faq-list'\" />";
		    }
			else
			{
				$array['msg']=$configs['error_msg'];
		    }
			 
		break; 
        
        case "add-course":
        
	        if ($_POST['course_id']) 
	        {

				$r=updateTable("ocs_course",$_POST,array("course_id"=>$_POST['course_id']),array("picture","thumb"));

			} else 
			{
				unset($_POST['course_id']);
				$r=insertTable("ocs_course",$_POST,array("picture","thumb"));

			}

			$error=mysqli_errno($configs['con']);

			if (!$error):
				$array['msg']=str_replace("{table}","course",$configs['success_msg']);
				$array['msg'].="<meta http-equiv='refresh' content=\"3;URL='course-list'\" />";
			else:
				$array['msg']=$configs['error_msg'];

			endif;
		break; 
        case "enquiry":
			if($_POST['id'])
			{
	  
	       		$r=updateTable("ocs_enquiry",$_POST,array("id"=>$_POST['id']));
			}
			
	        $array['msg']=$configs['success_msg'];
	        
        break;
		case "category":		
	    	 
	    	$_POST['seo_url']=seo_url(strtolower($_POST['cat_name']));

	    	$_POST['date']=date('Y-m-d');

	    	$_POST['cur_date']=date('Y-m-d');

			if($_POST['cat_id'])
			{

				$r=updateTable("ocs_category",$_POST,array("cat_id"=>$_POST['cat_id']),array("icon","thumb"));

			}
			else
			{
				unset($_POST['cat_id']);
				$r=insertTable("ocs_category",$_POST,array("icon","thumb"));

			}
			 
			$error=mysqli_errno($configs['con']);
			if(!$error)
			{	
 	  			$array['msg']=str_replace("{table}","category",$configs['success_msg']);

 	  			$array['msg'].='<meta http-equiv="refresh" content="3;' .$configs['admin_vpath'].'category/category-list">';

		    }
		    else
		    {	
    			$array['msg']=$configs['error_msg'];
		 
		    }
		break; 
		case"site-setting":
		 
		    if($_POST['id'])
		    {
		    	 $r=updateTable("ocs_setting",$_POST,array("id"=>1),array("logo","favicon"));
		    	
		    }	
		    else
		    {
		    	 unset($_POST['id']);
		    	 $r=insertTable("ocs_setting",$_POST,array("logo","favicon"));
		   
		    }	
		   
		     
		    if($r)
		    {

		      
		      $array['msg']=str_replace("{table}","Confiration",$configs['success_msg']);

		    }
		    else
		    {

		      $array['msg']=$configs['error_msg'];

		    }	  
	
 
		break;
		case "slider":		
	    	 
			 
					
			if($_POST['id'])
			{
				$r=updateTable("ocs_slider",$_POST,array("id"=>$_POST['id']),array("background"));
				 
				$_POST['id']=$_POST['id'];
			}
				 
			else 
			{
				unset($_POST['id']);
				$r=insertTable("ocs_slider",$_POST,array("background"));
				$_POST['id']=$r;
			} 
			 
				 
			
			 	
		    
			if(!mysqli_errno($configs['con']))
			{
		 
				$array['msg']=$configs['success_msg'];
				if($_POST['lang_id']==1)
				{
					$array['msg'].="<meta http-equiv=\"refresh\" content=\"2;URL='add-slider?id=" . $_POST['id'] . "&lang_id=2'\" />";
				}
				if($_POST['lang_id']==2)
				{
					$array['msg'].="<meta http-equiv=\"refresh\" content=\"2;URL='slider-list'\" />";
				}
				
			}
			else
			{
				$array['msg']=$configs['error_msg'];
			}	
			   
			 
		break;

	 
		
		case "add-gallery":		
	    	   	
            $_POST['date']=date("Y-m-d");
            $_POST['file_type']="p"; 
            $i=1;
		    $target_dir = "uploads/gallery/"; // Upload directory
		    $allowed_image_extension = array(
                    "png",
                    "jpg",
                    "jpeg"
                );
            
			foreach($_FILES['file']['tmp_name'] as $key => $value) 
		    {
		        $tempFile = $_FILES['file']['tmp_name'][$key];
		        
		        $ext=pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
		        
		         // Get Image Dimension
                $fileinfo = @getimagesize($tempFile);
                $width = $fileinfo[0];
                $height = $fileinfo[1];
                
                if(in_array($ext, $allowed_image_extension))
                {
		        
		            $targetFile =  $target_dir. time()."-".$i.".".$ext;
		       
		            if(move_uploaded_file($tempFile,$configs['apath'].$targetFile))
		            {
		                insertTable("ocs_gallery",array("file_type"=>"p","vendor_id"=>$_SESSION['vendor_id'],"date"=>$_POST['date'],"status"=>"Y","file"=>$targetFile));   
		            }
		            $i++;
                }
		    }
			 
			$error=mysqli_errno($configs['con']);
			if(!$error)
			{	
 	  			$array['msg']=str_replace("{table}","gallery",$configs['success_msg']);
 	  			
 	  			$array['msg'].='<meta http-equiv="refresh" content="3;' .$configs['admin_vpath'].'gallery/gallery-list">';
 	  			 
		    }
		    else
		    {	
    			$array['msg']=$configs['error_msg'];
		 
		    }
		break;
 
        
        case "enquiry":
        
			if($_POST['id'])
			{
	  
	       		$r=updateTable("ocs_enquiry",$_POST,array("id"=>$_POST['id']));
			}
			
	         $array['msg']=str_replace("{table}","Enquiry",$configs['success_msg']);
	        
        break;
        
      case "add-page":

		    if(!$_POST['status']){$_POST['status']="Y";}
	 
		    $_POST['contents']=htmlentities(htmlentities($_POST['contents']));
		    
		    if(!$_POST['seo_url'] && $_POST['seo_url']!='#')
		    {
			 	$_POST['seo_url']=seo_url(strtolower($_POST['title']));
			} 
			$_POST['seo_url']=strtolower($_POST['seo_url']); 
       
			if($_POST['id'])
			{
 
				$r=updateTable("contents",$_POST,array("id"=>$_POST['id']),array("img"));
			 
			}
			else
			{
				unset($_POST['id']);
				
				$r=insertTable("contents",$_POST,array("img"));
				
			 
			}
			 
			$error=mysqli_errno($configs['con']);
			if(!$error):
 	  			$array['msg']=str_replace("{table}","ocs_contents",$configs['success_msg']);
			
			    $array['msg'].='<meta http-equiv="refresh" content="2,' .$configs['admin_vpath'].'content/page-list">';
 
			else:
    			$array['msg']=$configs['error_msg'];
		 
			endif;
		break;
		
       
        case "state_list":
		    
		    $stmt =getQuery("state","*",array("country_id"=>$_POST['country_id'],"status"=>'Y'),"name");
            $options="";
			if(count($stmt))
			{
				
				$options.="<option value=''>Select Sate</option>";
				foreach($stmt as $row)
				{
					$options.="<option value='" .$row['id'] ."'>" .$row['name'] ."</option>";
					 
			    }
			     
			}
			$array['options']=$options;
        break;
					
		case "city_list":
		    
		    $stmt =getQuery("city","*",array("state_id"=>$_POST['state_id'],"status"=>'Y'),"name");
            $options="";
			if(count($stmt))
			{
				
				$options.="<option value=''>Select City</option>";
				foreach($stmt as $row)
				{
					$options.="<option value='" .$row['id'] ."'>" .$row['name'] ."</option>";
					 
			    }
			     
			}
			$array['options']=$options;
        break; 
        case"grant":
	    	 
			deleteData("admin_grant",array("admin_id"=>$_POST['admin_id']));
			
			for($i=0; $i<count($_POST['progid']); $i++):
				   
			  	$prog=explode(",",$_POST['progid'][$i]);
				$prog_id = $prog[0];
			   	$proghead_id=$prog[1];
			   
			   	if($prog_id && $proghead_id)
			   	{
			   	  
					insertTable("admin_grant",array("admin_id"=>$_POST['admin_id'],"prog_id"=>$prog_id,"proghead_id"=>$proghead_id));
				    //echo insertTable("admin_grant",array("admin_id"=>$_POST['admin_id'],"prog_id"=>$prog_id,"proghead_id"=>$proghead_id),'',1);
				}
			endfor;
			 
			$array['msg']=str_replace("{table}","Grant",$configs['success_msg']);
			 
	    break;
	     
	    case "change-password":	


	   	 	$n=getTotalRow("login",array("user_id"=>$_POST['user_id'],"password"=>md5($_POST['old_password'])));

	   	 	if(!$n)
			{
				 $array['msg']="<font color=red>Old password is not correct!</font>";
			}


			if($_POST['new_password']!=$_POST['conf_password'])
		    {
		        $array['msg']="<font color=red>News password & confirm password  does not match!</font>";
		    }
		    else
		    {
		  
				$r=updateTable("login",array("password"=>md5($_POST['new_password'])),array("user_id"=>$_POST['user_id']));

			 	if(!mysqli_errno($configs['con']))
			    {
			        $array['msg']="Your password has been changed successfully!";
			         
			    }   
			    else
			    {
			        $array['msg']="<font color=red>Error!</font>";
			    }
			}
 
	    break ;   
 
	    case "mailsetup":
       
			if($_POST['id'])
			{

				$r=updateTable("ocs_mailsetup",$_POST,array("id"=>$_POST['id']));

			}
			else
			{
				unset($_POST['id']);
				$r=insertTable("ocs_mailsetup",$_POST);

			}
			 
			$error=mysqli_errno($configs['con']);
			if(!$error):
 	  			$array['msg']=str_replace("{table}","mailsetup",$configs['success_msg']);
 	  			
 	  			$array['msg'].='<meta http-equiv="refresh" content="3;' .$configs['admin_vpath'].'mailsetup/mailsetup-list">';
			else:
    			$array['msg']=$configs['error_msg'];
		 
			endif;
		break; 
		
		 
		
		case "menu":
		    $_POST['ord']=(int)$_POST['ord'];
		    $_POST['parent_id']=(int)$_POST['parent_id'];
		    
		    if(!$_POST['id'])
		    {
		    	unset($_POST['id']);
		        $r=insertTable("ocs_adminmenu",$_POST);		         
		    }   
		    else
		    {
		        $r=updateTable("ocs_adminmenu",$_POST,array("id"=>$_POST['id']));		        
		        //echo updateTable("ocs_adminmenu",$_POST,array("id"=>$_POST['id']),'',1);
		    }
		    if($r)
		    {
				$array['msg']=str_replace("{table}","Menu ",$configs['success_msg']);
				$array['msg'].="<meta http-equiv=\"refresh\" content=\"2;URL='list'\" />";
				
		    }
		    else
		    {
				$array['msg']=$configs['error_msg'];
		  
			}
        break;
		 
		
		 
		case "feedback":
			
			 
		 
			if($_POST['id'])
			{
				$r=updateTable("ocs_feedback",$_POST,array("id"=>$_POST['id']),array("pic"));
		 
				$_POST['id']=$_POST['id'];
			}
		 
			else 
			{
				unset($_POST['id']);
				$r=insertTable("ocs_feedback",$_POST,array("pic"));
				$_POST['id']=$r;
			} 
	 
				 
	     
			 
			$error=mysqli_errno($configs['con']);
			if(!$error)
			{
 	  			$array['msg']=str_replace("{table}","feedback",$configs['success_msg']);
 	  			$array['msg'].="<meta http-equiv=\"refresh\" content=\"2;URL='" . $configs['admin_vpath']. "feedback/feedback-list'\" />";
			}
			else
			{
    			$array['msg']=$configs['error_msg'];
		 
			}

		break;
		 
	 
	}
	//print_r($array);
	echo json_encode($array);

    flush();

}



if($_POST['setting_form']  && $_POST['delete'])
{

	$r=deleteData("setting",array("id"=>$_POST['id']));
	echo $configs['delete_msg'];

}



?>