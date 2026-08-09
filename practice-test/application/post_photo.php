<?
include("configs/license.php");
include("configs/common.php");

if(isset($_POST)):
     
     if($_FILES['pic']['name'])
     {
        $ext=@end(explode(".",$_FILES['pic']['name']));
        $pic_name="uploads/" . $_SESSION['user_id'] . "." .$ext;
        copy($_FILES['pic']['tmp_name'],$apath.$pic_name);
        $r=updateTable("login",array("pic"=>$pic_name),array(),array("user_id"=>$_SESSION['user_id'])); 

     }   
     
             
   
    if($r)
    {
      
        
		echo"<p align=center><p></p><p></p><h4>Profile Picture has been successfully updated.</h4></p><p style='height:200px'></p>";
	}
    else
    {
		echo"<h4>Error</h4>";
	}
endif;
?>