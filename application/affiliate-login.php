<?php
include("../configs/autoload.php");

$error=null;
$data['ref_discount']=10;
$data['meta_title']="Affiliate Login Page";

if($_POST)
{
    
    $login=file_get_contents("https://staging-test-prep.asvab-tutoring.com/validate.php?field=login&value=".$_POST['email'] . ",".$_POST['password']);
    
        
    if(trim(!$login))
    {
        $error="<h4 style='color:red'>Invalid login!</h4>";
    }	
    else
    {
        
        $_SESSION['user_id']=$array['user_id'];
        $_SESSION['name']=$array['name'];

        header("location:dashboard");
        exit;

    }    
   
     
}
 
echo template($data);

?>
