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
	    		$exist=getTotalRow("admin",array("password"=>md5($_POST['password']),"username"=>$_POST['username'],"status"=>'Y'));
          
        		if($exist)
        		{
        			$d=getDataByWhere("admin","*",array("password"=>md5($_POST['password']),"username"=>$_POST['username'],"status"=>'Y'));
       
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
        
        case "services":
			
			switch($_POST['lang_id'])
			{
				case "1":
				    if(!$_POST['status']){$_POST['status']="Y";}
			  
					if(!$_POST['seo_url'] && $_POST['seo_url']!='#')
					{
						 $_POST['seo_url']=seo_url(strtolower($_POST['title']));
					} 
					$_POST['seo_url']=strtolower($_POST['seo_url']); 
					
					if($_POST['id'])
					{
						$r=updateTable("service",$_POST,array("id"=>$_POST['id']),array("picture","icon"));
				 
						$_POST['id']=$_POST['id'];
					}
				 
					else 
					{
						unset($_POST['id']);
						$r=insertTable("service",$_POST,array("picture","icon"));
						$_POST['id']=$r;
					} 
			 
				break;
				default:
				
					$_POST['json_value']=getJson($_POST,array("name","description","meta_keys","meta_desc","meta_title"));
					$_POST['table_name']="service";
					$_POST['fld_id']=$_POST['id'];
					deleteData("lang_data",array("table_name"=>$_POST['table_name'],"fld_id"=>$_POST['fld_id'],"lang_id"=>$_POST['lang_id']));
					insertTable("lang_data",$_POST);
			
			}	
			$error=mysqli_errno($configs['con']);
			if(!$error)
			{
 	  			$array['msg']=str_replace("{table}","services",$configs['success_msg']);
			}
			else
			{
    			$array['msg']=$configs['error_msg'];
		 
			}
		break; 

		case "category":		
	    	 
	    	$_POST['seo_url']=seo_url(strtolower($_POST['cat_name']));

	    	$_POST['date']=date('Y-m-d');

	    	$_POST['cur_date']=date('Y-m-d');

			if($_POST['cat_id'])
			{

				$r=updateTable("category",$_POST,array("cat_id"=>$_POST['cat_id']),array("icon","thumb"));

			}
			else
			{
				unset($_POST['cat_id']);
				$r=insertTable("category",$_POST,array("icon","thumb"));

			}
			 
			$error=mysqli_errno($configs['con']);
			if(!$error):
 	  			$array['msg']=str_replace("{table}","category",$configs['success_msg']);

 	  			$array['msg'].='<meta http-equiv="refresh" content="3;' .$configs['admin_vpath'].'category/category-list">';

			else:
    			$array['msg']=$configs['error_msg'];
		 
			endif;
		break; 
		case"site-setting":
		 
		    if($_POST['id'])
		    {
		    	 $r=updateTable("setting",$_POST,array("id"=>1),array("logo","favicon"));
		    }	
		    else
		    {
		    	 unset($_POST['id']);
		    	 $r=insertTable("setting",array_merge($_POST,array("vendor_id"=>$_SESSION['vendor_id'])),array("logo","favicon"));
		   
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
	    	 
			switch($_POST['lang_id'])
			{
				case "1":
				    
					
					if($_POST['id'])
					{
						$r=updateTable("banner",$_POST,array("id"=>$_POST['id']),array("banner"));
				 
						$_POST['id']=$_POST['id'];
					}
				 
					else 
					{
						unset($_POST['id']);
						$r=insertTable("banner",$_POST,array("banner"));
						$_POST['id']=$r;
					} 
			 
				break;
				default:
				    if($_FILES['banner']['file_name'])
				    {
						$ext=pathinfo($file_name, PATHINFO_EXTENSION);
						$new_file="uploads/banner/" . time() . ".".$ext;
						copy($_FILES['banner']['tmp_name'],$configs['apath'] . $new_file);
						$_POST['banner']=$new_file;
					}
					$_POST['json_value']=getJson($_POST,array("title","description","banner"));
					$_POST['table_name']="banner";
					$_POST['fld_id']=$_POST['id'];
					deleteData("lang_data",array("table_name"=>$_POST['table_name'],"fld_id"=>$_POST['fld_id'],"lang_id"=>$_POST['lang_id']));
					insertTable("lang_data",$_POST);
			
			}	
		    
			if(!mysqli_errno($configs['con']))
			{
		 
				$array['msg']=$configs['success_msg'];
				if($_POST['lang_id']==1)
				{
					$array['msg'].="<meta http-equiv=\"refresh\" content=\"2;URL='slider?id=" . $_POST['id'] . "&lang_id=2'\" />";
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
		                insertTable("gallery",array("file_type"=>"p","vendor_id"=>$_SESSION['vendor_id'],"date"=>$_POST['date'],"status"=>"Y","file"=>$targetFile));   
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
	  
	       		$r=updateTable("enquiry",$_POST,array("id"=>$_POST['id']));
			}
			
	         $array['msg']=str_replace("{table}","Enquiry",$configs['success_msg']);
	        
        break;
        
        case "add-page":

			 switch($_POST['lang_id'])
			{
				case "1":
				    if(!$_POST['status']){$_POST['status']="Y";}
			  
					if(!$_POST['seo_url'] && $_POST['seo_url']!='#')
					{
						 $_POST['seo_url']=seo_url(strtolower($_POST['title']));
					} 
					$_POST['seo_url']=strtolower($_POST['seo_url']); 
					
					if($_POST['id'])
					{
						$r=updateTable("contents",$_POST,array("id"=>$_POST['id']),array("img"));
				 
						$_POST['id']=$_POST['id'];
					}
				 
					else 
					{
						unset($_POST['id']);
						$r=insertTable("contents",$_POST,array("img"));
						$_POST['id']=$r;
					} 
			 
				break;
				default:
				
					$_POST['json_value']=getJson($_POST,array("title","contents","meta_keys","meta_desc","meta_title","short_contents"));
					$_POST['table_name']="contents";
					$_POST['fld_id']=$_POST['id'];
					deleteData("lang_data",array("table_name"=>$_POST['table_name'],"fld_id"=>$_POST['fld_id'],"lang_id"=>$_POST['lang_id']));
					insertTable("lang_data",$_POST);
			
			}	
		    
			if(!mysqli_errno($configs['con']))
			{
		 
				$array['msg']=$configs['success_msg'];
				if($_POST['lang_id']==1)
				{
					$array['msg'].="<meta http-equiv=\"refresh\" content=\"2;URL='page-add?id=" . $_POST['id'] . "&lang_id=2'\" />";
				}
				if($_POST['lang_id']==2)
				{
					$array['msg'].="<meta http-equiv=\"refresh\" content=\"2;URL='page-list'\" />";
				}
				
			}
			else
			{
				$array['msg']=$configs['error_msg'];
			}	
			   
 
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

				$r=updateTable("mailsetup",$_POST,array("id"=>$_POST['id']));

			}
			else
			{
				unset($_POST['id']);
				$r=insertTable("mailsetup",$_POST);

			}
			 
			$error=mysqli_errno($configs['con']);
			if(!$error):
 	  			$array['msg']=str_replace("{table}","mailsetup",$configs['success_msg']);
 	  			
 	  			$array['msg'].='<meta http-equiv="refresh" content="3;' .$configs['admin_vpath'].'mailsetup/mailsetup-list">';
			else:
    			$array['msg']=$configs['error_msg'];
		 
			endif;
		break; 
		
		case "add-campaign":
		
			if($_POST['id'])
			{

				$r=updateTable("campaign",$_POST,array("id"=>$_POST['id']));
				
		 
			}
			else
			{
				unset($_POST['id']);
				$_POST['id']=insertTable("campaign",$_POST);
				
			 
			}
			 
			 
			$target_dir = "uploads/"; // Upload directory
		    
		  	$imgs=[];
		    $i=0; 
		    foreach($_FILES['picture']['tmp_name'] as $key => $value) 
		    {
		        $i++;
		        $tempFile = $_FILES['picture']['tmp_name'][$key];
		        
		        $ext=pathinfo($_FILES['picture']['name'][$key], PATHINFO_EXTENSION);
		        
		        $targetFile =  $target_dir. time()."-".$i.".".$ext;
		       
		        if(move_uploaded_file($tempFile,$configs['apath'].$targetFile))
		        {
		           $imgs[]=$configs['vpath'].$targetFile;   
		        }
		        
		    }
		    
		    if(count($imgs))
		    {
		    	$its=implode(",",$imgs);
		    	updateTable("campaign",array("picture"=>$its),array("id"=>$_POST['id']));
		    }
		    
			$error=mysqli_errno($configs['con']);
			
			if(!$error)
			{	
 	  			$array['msg']=str_replace("{table}","campaign",$configs['success_msg']);
 	  			
 	  			$array['msg'].='<meta http-equiv="refresh" content="3;' .$configs['admin_vpath'].'newsletter/campaign-list">';
		    }
		    else
		    {	
    			$array['msg']=$configs['error_msg'];
		 
		    }
		
		break;
		case "email-add":
	    
            if($_POST['email'])
            {
            	$e=explode("\n",$_POST['email']);
            	for($i=0;$i<count($e);$i++)
            	{
            		$exist=getTotalRow("newsletter_email",array("email"=>trim($e[$i])));
			 		if(!$exist)	
			 		{
			 			$r=insertTable("newsletter_email",array("category"=>$_POST['category'],"campaign_id"=>0,"email"=>trim($e[$i])));
			 			$i++;
				    }
            		
            	}	
            	
            }
            	
            if($_FILES['csv']['tmp_name'])
            {
          
			 	$handle  = fopen($_FILES['csv']['tmp_name'], "r");
			 	$headers = fgetcsv($handle, 1000, ",");
			 	$i=0;
			 	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			 	{
			 		
			 		$_POST['facebook']=$data[0];
			 		$_POST['name']=$data[1]; 
			 		$_POST['mobile']=$data[2];
			 		$_POST['email']=trim($data[3]);
			 		$_POST['campaign_id']=0;
			 		
			 		$exist=getTotalRow("newsletter_email",array("email"=>trim($_POST['email'])));
			 		if(!$exist)	
			 		{
			 			$r=insertTable("newsletter_email",$_POST);
			 			$i++;
				    }
			 	   
			 	}
			 	
				fclose($handle);
			} 
			$error=mysqli_errno($configs['con']);
			if(!$error):
 	  			$array['msg']=$i . ' records added. <meta http-equiv="refresh" content="2; URL=\'' . $configs['admin_vpath'] .'newsletter/email-list\'" />' ;
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
		        $r=insertTable("adminmenu",$_POST);		         
		    }   
		    else
		    {
		        $r=updateTable("adminmenu",$_POST,array("id"=>$_POST['id']));		        
		        //echo updateTable("adminmenu",$_POST,array("id"=>$_POST['id']),'',1);
		    }
		    if($r)
		    {
				$array['msg']=str_replace("{table}","Menu ",$configs['success_msg']);
		    }
		    else
		    {
				$array['msg']=$configs['error_msg'];
		  
			}
        break;
		case "city": 
			if($_POST['id'])
			{

				$r=updateTable("city",$_POST,array("id"=>$_POST['id']));

			}
			else
			{
				unset($_POST['id']);
				$r=insertTable("city",$_POST);

			}
			 
			$error=mysqli_errno($configs['con']);
			if(!$error):
 	  			$array['msg']=str_replace("{table}","city",$configs['success_msg']);
			else:
    			$array['msg']=$configs['error_msg'];
		 
			endif;
		break;
		
		case "area": 
			if($_POST['id'])
			{

				$r=updateTable("area",$_POST,array("id"=>$_POST['id']));

			}
			else
			{
				unset($_POST['id']);
				$r=insertTable("area",$_POST);

			}
			 
			$error=mysqli_errno($configs['con']);
			
			if(!$error)
			{
 	  			$array['msg']=str_replace("{table}","area",$configs['success_msg']);
			}
		    else
			{
    			$array['msg']=$configs['error_msg'];
		 
			}

		break;
		case "feedback":
			
			switch($_POST['lang_id'])
			{
				case "1":
				 
					if($_POST['id'])
					{
						$r=updateTable("feedback",$_POST,array("id"=>$_POST['id']),array("pic"));
				 
						$_POST['id']=$_POST['id'];
					}
				 
					else 
					{
						unset($_POST['id']);
						$r=insertTable("feedback",$_POST,array("pic"));
						$_POST['id']=$r;
					} 
			 
				break;
				default:
				
					$_POST['json_value']=getJson($_POST,array("name","feedback","city"));
					$_POST['table_name']="feedback";
					$_POST['fld_id']=$_POST['id'];
					deleteData("lang_data",array("table_name"=>$_POST['table_name'],"fld_id"=>$_POST['fld_id'],"lang_id"=>$_POST['lang_id']));
					insertTable("lang_data",$_POST);
			
			}	
	    	 
	     
			 
			$error=mysqli_errno($configs['con']);
			if(!$error)
			{
 	  			$array['msg']=str_replace("{table}","feedback",$configs['success_msg']);
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

if($_POST['cat_id'] && $_POST['get_subcatgory_option'])
{
	
	$query = getQuery("category","*",array("parent_id"=>$_POST['cat_id']),'cat_name');
	
	echo"<option value=''>Select sub category</option>";
	
	foreach($query as $c)
    {
        echo"<option value='" .$c['cat_id'] ."'>" .$c['cat_name'] ."</option>";
    
    } 
	    
	 
}
 

if($_POST['city_id'] && $_POST['get_area_option'])
{
$query = getQuery("area","*",array("city_id"=>$_POST['city_id']),"name");
	
	echo"<option value=''>Select Area </option>";
	
	foreach($query as $c)
    {
        echo"<option value='" .$c['id'] ."'>" .$c['name'] ."</option>";
    
    } 
	    
	 
}


if($_POST['keyword'] && $_POST['get_city'])
{
	$stmt = getQuery("city","*",array("name like"=>"'%" . $_POST['keyword'] . "%'", "state_id"=>$_POST['state_id'],"status"=>'Y'),"name",5);
 
	if(count($stmt))
	{
		
		echo"<ul id='state-list'>";
		foreach($stmt as $row)
		{
			?>
			<li onClick="javascript:selectCity('<?php echo $row['id'];?>','<?php echo rtrim($row['name']); ?>');"><?php echo $row['name']; ?></li>
			<?php
	    }
	    echo"</ul>";
	}
}



if($_POST['setting_form']  && $_POST['delete'])
{

	$r=deleteData("setting",array("id"=>$_POST['id']));
	echo $configs['delete_msg'];

}
# member register=============
if($_POST['register'])
{
    unset($_POST['register']);
    unset($_POST['submit']);
	if($_POST['user_id'])
	{

       $r=updateTable("register",$_POST,array("user_id"=>$_POST['user_id']),array(""));
	}
	else
	{
	    $r=insertTable("register",$_POST,array(""));
	}
    if($r)
	{
      echo $configs['success_msg'];
    }
    else
	{
	    echo $configs['error_msg'];
	}
 
}

if($_POST['register_form']  && $_POST['delete'])
{

	$r=deleteData("register",array("user_id"=>$_POST['user_id']));
	echo $configs['delete_msg'];

}
  


if($_POST['delete_id'] && $_POST['banner_id']):
     $r=deleteData("adsense_city",array("id"=>$_POST['delete_id']));
     $list='<table id="table-1" width="100%" class="table table-bordered table-stripped" border="0" align="center" cellspacing="1" cellpadding="4"  >
		<thead>
		<tr><td valign=top><b>City Name</b></td><td valign=top><b>Country Name</b></td><td valign=top align=center><b>Action</b></td></tr>
		</thead>';

		$data=getQueryByJoin(array("adsense_city as n","city as c"),"n.id,c.*",array("n.banner_id"=>$_POST['banner_id']),array("n.city_id"=>"c.city_id"));

		foreach($data as $d)
		{
			$country_name=getFldNameById("country","name",array("id"=>$d['country_id']));
			$list.="<tr ><td >" . ucwords($d['city_name']) . "</a></td><td >" . ucwords($country_name) . "</td><td align=center> <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){getDeleteBanner(" . $d['id'] . ",".$_POST['banner_id'].");}\">Delete</a></td></tr>";

		}

		$list.='<tbody></tbody></table>';
		echo  $list;


endif;


if($_POST['delete_id'] && $_POST['article_id']):
     $r=deleteData("article_city",array("id"=>$_POST['delete_id']));
     $list='<table id="table-1" width="100%" class="table table-bordered table-stripped" border="0" align="center" cellspacing="1" cellpadding="4"  >
		<thead>
		<tr><td valign=top><b>City Name</b></td><td valign=top><b>Country Name</b></td><td valign=top align=center><b>Action</b></td></tr>
		</thead>';

		$data=getQueryByJoin(array("article_city as n","city as c"),"n.id,c.*",array("n.article_id"=>$_POST['article_id']),array("n.city_id"=>"c.city_id"));

		foreach($data as $d)
		{
			$country_name=getFldNameById("country","name",array("id"=>$d['country_id']));
			$list.="<tr ><td >" . ucwords($d['city_name']) . "</a></td><td >" . ucwords($country_name) . "</td><td align=center> <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){getDelete(" . $d['id'] . ",".$_POST['article_id'].");}\">Delete</a></td></tr>";

		}

		$list.='<tbody></tbody></table>';
		echo  $list;


endif;

if($_POST['banner_entry_form'])
{
	if($_POST['type']==2)
	{
		$_POST['type']=2;
		$files=array("banner");
		$query=array("size"=>$_POST['size'],
	    "date"=>date("Y-m-d"),
	    "status"=>$_POST['status'],
	    "link"=>$_POST['link'],
	    "type"=>$_POST['type'],
	    "start_date"=>$_POST['start_date'],
	    "finish_date"=>$_POST['finish_date']);
	}
    if($_POST['type']==1)
    {

    	$query=array("size"=>$_POST['size'],
	    "banner"=>htmlentities($_POST['adsence']),
	    "date"=>date("Y-m-d"),
	    "status"=>$_POST['status'],
	    "link"=>$_POST['link'],
	    "type"=>$_POST['type'],
	    "start_date"=>$_POST['start_date'],
	    "finish_date"=>$_POST['finish_date']);
	    $files=array();
	}
    if($_POST['id'])
    {
		$r=updateTable("adsense",$query,array("id"=>$_POST['id']),$files);
        $id=$r;
	}
    else
    {
		$r=insertTable("adsense",$query,$files,1);
	}

    if($r)
    {

	    $msg="<h5><img src='assets/img/on.png'> Inserted successful.</h5>";
    }
    else
    {
   	  $msg="<h5 style='color:red'>Update failure.</h5>";
    }
    echo $msg;
}



if($_GET['cat_name'])
{

	$searchTerm = $_GET['cat_name'];
	$data=array();

    //get matched data from skills table
    $query = getQuery("category","cat_name",array("cat_name rlike"=>$searchTerm),"cat_name");
    foreach($query as $row)
    {
        $data[] = $row['cat_name'];
    }

    //return json data
    echo json_encode($data);

    flush();
}
if($_GET['city_name'])
{

	$searchTerm = $_GET['city_name'];
	$data=array();

    //get matched data from skills table
    $query = getQuery("city","name",array("name rlike"=>$searchTerm),"city_name");
    foreach($query as $row)
    {
        $data[] = $row['name'];
    }

    //return json data
    echo json_encode($data);

    flush();
}
 

?>