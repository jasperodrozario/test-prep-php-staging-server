<?
include("configs/license.php");
include("configs/common.php");
if(isset($_POST)):
    $course_name=getFldValueById("course","name","course_id=" . $_POST['course_id']);
    $_POST=array_merge($_POST,array("date"=>date("Y-m-d"),"message"=>$course_name,"ip_address"=>$_SERVER['REMOTE_ADDR']));

   
    $r=insertTable("enquiry",$_POST); 
    
	/*$r=mysqli_query($con,"insert into ".$prev."enquiry set
	name	=\"".$_POST['name']."\",phone	=\"".$_POST['phone']."\",
    email	=\"".$_POST['email']."\",date	=\"".date("Y-m-d")."\",course_id=\"".$_POST['course_id']."\",ip_address=\"" . $_SERVER['REMOTE_ADDR'] . "\",
    message =\"".$_POST['description']."\"");*/
            
    //mail($_POST['email'],"New message from " .$_POST['name'],"Name : " .$_POST['name'] ."\n\nEmail : " . $_POST['email'] ."\nPhone : " . $_POST['mobile'] ."\nMessage : " .$course_name,"Form:" .$_POST['email'] ."\r\nReply-to:" .$_POST['email'] . "\r\nCc:" .$setting['admin_contact']);
    if($r)
    {
		echo"<h4>Your message has been successfully submitted.<br><br>We will contact you soon.<br><br> Thanks<br><br>" . ucwords($dotcom) ."</h4>";
	}
    else
    {
		echo"<h4>Error</h4>";
	}
endif;
?>