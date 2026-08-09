<?

include("../configs/autoload.php");


$error_msg="";
if(isset($_POST['activation_code'])){
    

 


  $check=getDataByWhere("login","*",array("email"=>$_POST['email'],"password"=>md5($_POST['password'])));
 
  

  if(!empty($check))
  {

   updateTable("login",array("status"=>"Y","activation_status"=>"Y"),array("email"=>$_POST['email']),"",1);
   
  
   


    $d=getDataByWhere("login","*",array("email"=>$_POST['email'],"status"=>"Y","activation_status"=>"Y"));

    

        $_SESSION['user_id']        = $d["user_id"];
        $_SESSION['email']          = $d['email'];
        $_SESSION['name']           = $d['fname'] ." ". $d['lname'];
        $_SESSION['last_login']     = $d['last_login'];  
        $_SESSION['reg_date']       = $d['reg_date']; 

      
       header("location:" . $configs['vpath'] . "my-dashboard");
     }
     else{

      $data['wrong_msg']='Invalid Email/Password';
     }


}
echo template($data);
?>



