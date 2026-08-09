<?
include("configs/license.php");
include("configs/common.php");
if(isset($_POST)):
 
    $_POST=array("user_id"=>$_POST['user_id'],"course_id"=>$_POST['course_id'],"add_date"=>date("Y-m-d"),"bookmark"=>$_POST['bookmark']);

   
    $r=insertTable("course_user_bookmark",$_POST); 
    
 
    if($r)
    {
		echo"<div class='col-md-12' style='margin-top:20px'><table class='table table-bordered' style='margin-top:20px'><tr><td colspan=2><h4>Notes Added...</h4></td></tr>";
        $notes=getQuery("course_user_bookmark","*",array("user_id"=>$_POST['user_id'],"course_id"=>$_POST['course_id']));
        
        foreach($notes as $note)
        {
           echo"<tr><td width=20%>" . showdate($note['add_date']) . "</td><td style='text-align:left'>".$note['bookmark']."</td></tr>";
        }    
        echo"</table></div>";
	}
    else
    {
		echo"<h4>Error</h4>";
	}
endif;
?>