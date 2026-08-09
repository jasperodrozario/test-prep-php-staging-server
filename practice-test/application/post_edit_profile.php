<?
include("configs/license.php");
include("configs/common.php");

if(isset($_POST)):
     
    $_POST=array("fname"=>$_POST['fname'],"lname"=>$_POST['lname'],"l_date"=>date("Y-m-d"),"email"=>$_POST['email'],"mobile"=>$_POST['mobile']);
    
     $r=updateTable("login",$_POST,array(),array("user_id"=>$_SESSION['user_id'])); 
             
   
    if($r)
    {
      
        
		echo"<p align=center><p></p><p></p><h4>Profile has been successfully updated.</h4></p><p style='height:200px'></p>";
	}
    else
    {
		echo"<h4>Error</h4>";
	}
endif;
?>