<?
include("../common/autoload.php");

if($_POST['password'] && $_POST['username'] && $_POST['captchatext']):
 
    if($_SESSION["captcha_code"]!=$_POST['captchatext']):
		$data['msg']="<font color=#FF0000>Error in Security code.</font>";
 
	else:
	    $flag="";
	    $d=getDataByWhere("admin","*",array("password"=>md5($_POST['password']),"username"=>$_POST['username'],"status"=>'Y'));

	    if(count($d)):
		    $_SESSION['admin_id'] 	= $d['admin_id'];
		    $_SESSION['username'] 	= $d['username'];
		    $_SESSION['email'] 	    = $d['email'];
		    $_SESSION['reg_date'] 	= $d['reg_date'];
		    $_SESSION['type'] 	    = $d['type'];
		    header("location:" . $configs['vpath'].$configs['admin_dir']."/dashboard");

	    else:
 	        $data['msg']="<font color=red size=2>Login Failure!!</font><br>";
	    endif;
    endif;
endif;



unset($configs['includes']);

echo template($data);
?>

