<?php 
include("../configs/autoload.php");
include("smtpmail.php");  
if(isset($_POST['submit']))
{
    $error="";


    $n=mysqli_num_rows(mysqli_query($con,"select * from ".$prev."login where email='".$_POST['email']."'"));
 
    if($n)
    {
   
         $error='<div class="alert alert-warning alert-dismissible fade in">
        <a href="#" class="close reg-close" data-dismiss="alert" aria-label="close">&times;</a>
        <h4>This email id already exits!. For login <a class="here" href="'.$vpath.'login">click here</a>.</h4> 
        </div>';
    }
    if(!$error)
    {
          
          $activation_code=time();
          $r=mysqli_query($con,"insert into ".$prev."login set
                    fname    =\"".$_POST['fname'] . "\",
                    lname    =\"".$_POST['lname'] . "\",
                    email   =\"".$_POST['email']."\",mobile   =\"".$_POST['mobile']."\",
                    status  =\"N\",password  =\"".md5($_POST['pass'])."\",activation_code=\"" . $activation_code . "\",activation_status=\"N\",reg_date=\"" .date("Y-m-d") . "\"");


          
           $msg='<div class="alert alert-warning alert-dismissible fade in">
          <a href="#" class="close reg-close" data-dismiss="alert" aria-label="close">&times;</a>
         <h4>You are successfully registerted in '.$dotcom.' For login <a class="here" href="'.$vpath.'login">click here</a>.</h4> 
        </div>';
        
        $mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Register"));

        $body=html_entity_decode($mail['template']);
        $subject=$mail['subject'];

          
       
          $activation_code="<a href='" . $vpath . "activation/" . $activation_code . "' target='_new'>Activate</a>";


        $mail_body=str_replace("{name}",$_POST['fname'] ." ". $_POST['lname'],$body);
        $mail_body=str_replace("{activation_code}",$activation_code,$mail_body);
        $mail_body=str_replace("{dotcom}",ucwords($dotcom),$mail_body);
        $mail_body=str_replace("{email}",$_POST['email'],$mail_body);
        $mail_body=str_replace("{password}",$_POST['pass'],$mail_body);
        $mail_body=html_entity_decode($mail_body);
     
             
        smtpmailer($_POST['email'],$setting['support_mail'], ucwords($dotcom),$subject, $mail_body);  
        smtpmailer($setting['admin_mail'],$setting['support_mail'], $dotcom,$subject, $mail_body);  

     }
  }
echo template($data);     
?>   
 