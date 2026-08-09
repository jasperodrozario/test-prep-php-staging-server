<?php 
include("../configs/autoload.php");
//include("smtpmail.php");  

include("../smtpmail.php");

$data['test_names']=getQuery("test","*",array("status"=>"Y"),"name");

if(isset($_POST['submit']))
{

    $error="";$data['error']="";
    $data['db_value']=$_POST; 
    $activation_code=time();
    
    /*if($n)
    {
   
        $error='<div class="alert alert-warning alert-dismissible fade in">
        <a href="#" class="close reg-close" data-dismiss="alert" aria-label="close">&times;</a>
        <h4>This email id already exits!. For login <a class="here" href="#" data-toggle="modal" data-target="#myModal">click here</a>.</h4> 
        </div>';

    }*/
    if($_POST['pass']!=$_POST['confpass'])
    {
		$error="<h4 style='color:red'>Password & confirm password does not match!</h4>";

	}
   
    $exist=getTotalRow("login",array("email"=>$_POST['email'],"reg_at"=>"web"));
    
   
    $exist2=getTotalRow("student",array("mobile"=>$_POST['mobile']));
        
    if($exist)
    {
        $error="<h4 style='color:red'>Email Id is Exists! Please try with different Email Id.</h4>";
    }	
   // if($exist2)
   // {
   //     $error="<h4 style='color:red'>Mobile No is  Exists! Please try with different Mobile No.</h4>";
    //}
    if(!$error)	
    {	
        $_POST['user_id']=insertTable("login",array("email"=>$_POST['email'],"reg_at"=>"web","status"=>"N","password"=>$_POST['pass'],"activation_status"=>"N","reg_date"=>date("Y-m-d")));
        
        if($_POST['user_id'])
        {
        	
	        	
	        $data['user_id']=$_POST['user_id'];
	      
	              
	        $r=insertTable("student",array("test_id"=>$_POST['test_id'],"user_id"=>$_POST['user_id'],"first_name"=>$_POST['first_name'],"last_name"=>$_POST['last_name'],"mobile"=>$_POST['mobile'],"country_id"=>(int)$_POST['country_id'],"state_id"=>(int)$_POST['state_id'], "city_id"=>(int)$_POST['city_id'],"zip_code"=>(int)$_POST['zip_code']));

	        $data['msg']='<h4>You have successfully registerted in '.$configs['dotcom'].'. Your account will be activate by  webmaster with in 24 hours.</h4>';

	        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Register"));
	        
	       
	        $body=html_entity_decode($mail['template']);
	        $subject=$mail['subject'];
	        $sendpassword=$_POST['pass'];
	       
	      //  $activation_code="<a href='" . $configs['vpath'] . "activation/" . $activation_code . "' target='_new'>Activate</a>";

	        $mail_body=str_replace("{name}",$_POST['first_name'] ." ". $_POST['last_name'],$body);
	       // $mail_body=str_replace("{activation_code}",$activation_code,$mail_body);
	        $mail_body=str_replace("{dotcom}",ucwords($dotcom),$mail_body);
	        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
	        $mail_body=str_replace("{password}",$sendpassword,$mail_body);
	        $mail_body=html_entity_decode($mail_body);
	        
	        smtpmailer($_POST['email'],$setting['support_mail'], ucwords($dotcom),$subject, $mail_body);  
        }
        else
        {
        	$data['error']=$configs['error_msg'];
        }	
     }
     else
     {
     	$data['error']=$error;
     }	
  }
 
echo template($data);     
?>   
 