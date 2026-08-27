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
		    header("location:" . $configs['admin_path']."dashboard");
		    exit();
	    else:
		    // Not an admin — check the employee roster too. Employees get full
		    // page access for now (same as a super admin) by reusing the
		    // existing admin_id/type gate, via a negative admin_id that can
		    // never collide with a real admin row. This is a stopgap: pages
		    // that treat admin_id as a real admin row (e.g. change password)
		    // won't behave correctly for an employee session yet.
		    $emp = getDataByWhere("employee", "*", array("password" => md5($_POST['password']), "username" => $_POST['username'], "status" => 'active'));

		    if (count($emp)):
			    $_SESSION['employee_id']  = $emp['id'];
			    $_SESSION['admin_id']     = -$emp['id'];
			    $_SESSION['username']     = $emp['username'];
			    $_SESSION['email']        = $emp['email'];
			    $_SESSION['type']         = 'S';
			    header("location:" . $configs['admin_path']."dashboard");
			    exit();
		    else:
 	            $data['msg']="<font color=red size=2>Incorrect username or password. Please try again.</font><br>";
		    endif;
	    endif;
    endif;
endif;

unset($configs['includes']);

echo template($data);
?>

