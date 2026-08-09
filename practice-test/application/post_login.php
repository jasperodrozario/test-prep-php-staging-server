<?
include("configs/config.php"); 
include("configs/common.php");
 
if(isset($_POST['email']) && isset($_POST['password']))
{
 	$uname=$_POST['email'];
	$password=$_POST['password'];
	$d=getDataByWhere("login","*",array("email"=>$uname,"password"=>md5($password),"status"=>'Y',"activation_status"=>'Y'));
	 
	if(count($d))
	{
	    
		$_SESSION['user_id']      = $d["user_id"];
		$_SESSION['email']        = $d['email'];
		$_SESSION['name']         = $d['fname'] . " " . $d['lname'];
		$_SESSION['user_type']	  = $d['user_type'];
		$_SESSION['reg_date']	  = $d['reg_date']; 
		$_SESSION['l_date']	      = $d['l_date'];  
        #update last login
        
		updateTable("login",array("l_login"=>date("Y-m-d")),array("user_id"=>$d['user_id']));
    	#Get Business logo
   		
	}
	else
	{
	    echo"ERROR";
	}
}
?>