<?php
include("../configs/autoload.php");

$data['meta_title']="Affiliate Dashboard";

chklogin();

$data['payment_info'] = getFldValueById("user", "payment_info", ['user_id' => $_SESSION['user_id']]);

switch($_GET['action'])
{
    case "profile":
        
        $data['db_value']=getDataByWhere("user","*",['user_id'=>$_SESSION['user_id']]);
        
    break;
    case "referral-student":
     
        $data['total']=getTotalRow("user",array("referer_id"=>$_SESSION['user_id']));
        $data['db_list']=getQuery("user","*",array("referer_id"=>$_SESSION['user_id']),"user_id desc",$configs['limit']);
      
    break;

    case "earning":

        $data['total_paid']=getFldValueById("referrer_payout","sum(amount)",['referrer_id'=>$_SESSION['user_id']]);
        
        $data['total_due']=$data['total_paid']-getFldValueById("referrer_payout","sum(amount)",['referrer_id'=>$_SESSION['user_id']]);

      
    break;
    case"faq":
        $data['faq_list']=getQuery("ocs_faq","*",["course_id"=>1000],"ord");
        
    break;    
    case"payment":
 
        $data['total']=getTotalRow("referrer_payout",["user_id"=>$_SESSION['user_id']]);

        $data['db_list']=getQuery("referrer_payout","*",["user_id"=>$_SESSION['user_id']]);
   
                
    break;  
    default:
        $data['db_value']=getDataByWhere("user","*",['user_id'=>$_SESSION['user_id']]);
        
        $data['total_student']=getTotalRow("user",['referer_id'=>$_SESSION['user_id']]);
        
        $data['total_earning']=getFldValueById("referral_earnings","sum(amount)", array('user_id'=>$_SESSION['user_id']));
	
        $data['total_paid']=getFldValueById("referrer_payout","sum(amount)",['user_id'=>$_SESSION['user_id']]);
        
        $data['total_due']= $data['total_earning'] - $data['total_paid'];

}

echo template($data);
?>





 


 





 


 
