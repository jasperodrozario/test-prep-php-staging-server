<? 
include("../common/access.php");
include("../common/autoload.php");
include('../smtpmail.php');
$data['page_title']="Add | Edit Modaretor";

$data['breadcrumb']=array("Moderator Management"=>"site_moderators.php"," Add | Edit Modaretor"=>"");
$data['btn']="<input type='button' value='Back to List' class='btn btn-success' onclick='javascript:history.back()'>";

 
if($_REQUEST['admin_id']):
    $data['db_value']=getDataByWhere("admin","*", array("admin_id"=>$_REQUEST['admin_id']));
    $data['admin_id']=$_REQUEST['admin_id'];
endif;
if($_POST['submit']):
    unset($_POST['submit']);
     
		
	if(!$_POST['admin_id']):
	    unset($_POST['admin_id']);
	    $r=insertTable("admin",$_POST,array("photo"));
	   
		$data['admin_id']=$r;

	else:
	    unset($_POST['password']);
	    $r=updateTable("admin",$_POST,array("photo"),array("admin_id"=>$_POST['admin_id']),array("photo"));

	    if($_POST['status']=='Y'){

	    	echo "Mail sent ";
/*
	    	$mail=getDataByWhere("mailsetup","*",array("mail_type"=>"Contact Us"));
           
            $body=html_entity_decode($mail['template']);
            $subject=$mail['subject'];
            $mail_body=str_replace("{name}",$d['fname'],$body);
            
            $mail_body=str_replace("{dotcom}",ucwords($dotcom),$mail_body);
            $mail_body=str_replace("{email}",$d['email'],$mail_body);
            $mail_body=str_replace("{password}",$password,$mail_body);
            $mail_body=html_entity_decode($mail_body);
                         
            smtpmailer($d['email'],$setting['support_mail'], ucwords($dotcom),$subject, $mail_body);  
            smtpmailer($setting['support_mail'],$d['email'], $d['fname'],$subject, $mail_body);
*/


	    	
	    }

	endif;	 
	 
	if($_POST['password']):
	   $r=updateTable("admin",array("password"=>md5($_POST['password'])),array("admin_id"=>$_POST['admin_id']));
 
	endif;
 
	
    if($r):
        $data['msg']="<img src='assets/img/on.png' width=20  align='absmiddle'><b>Update Successfull.</b>";
	else:
		$data['msg']="<img src='assets/img/off.png' width=20 align='absmiddle'><b>Duplicate User Name </b>";
	endif;
endif;
if($_POST['DELT']):
	deleteData("admin",array("admin_id"=>$_POST['admin_id']));
	$data['msg']=$configs['delete_msg']; 
endif;

echo template($data);
?> 
