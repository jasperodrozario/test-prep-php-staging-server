<?php
include("../common/access.php");
include("../common/autoload.php");

$query=["user_type"=>"Tutor"];

if(!$_GET['action']){
    $_GET['action']="employee-list";
}

switch($_GET['action'])
{
	case "employee-list":
	
		if(isset($_POST['search_btn']) && $_POST['search'])
		{
 	        $query=array_merge($query,array("[or]"=>"(user_id like '%" .$_POST['search'] ."%' or email like '%" .$_POST['search'] ."%' or concat(first_name,' ',last_name) like '%" .$_POST['search'] ."%' or first_name like '%" .$_POST['search'] ."%' or last_name like '%" .$_POST['search']."%' or referral_code like '%" .$_POST['search']."%')"));
		}	
		$data['total']=getTotalRow("user",$query);
 
		if($_GET['del_tutor_id'])
		{
			deleteData("user",array("user_id"=>$_GET['del_tutor_id']));
			$data['msg']=$configs["delete_msg"];
		}
	
		$data['page_subtitle']="Total Tutor [".$data['total']."]";
		
		$data['breadcrumb']=array("Tutor List"=>"");
        
        $data['page_title']="Tutor List (".$data['total'].")";
        
        $path = str_replace("practice-test/", "", $configs['vpath'])."tutor-register";
		
        $data["btn"]="<input name='button' type='button' class='btn btn-success' onclick=\"javascript:window.location.href='".$path."'\" value='Add New' style='float:right' />";
		 
		$data['db_list']=getQuery("user","*",$query,"user_id",$configs['limit']);
	 		
    break;
        
        
    case "add-employee":
          
        $data['page_subtitle']="Add Employee";
		 
		$data['breadcrumb']=array("Employee Management"=>"tutor-list?action=employee-list","Add / Edit Employee"=>"");
		$data['btn']="<a href='tutor-list?action=employee-list' class='btn btn-success'>Back To List</a>";
		
        if(!$_GET['tutor_id']){
            $_GET['tutor_id'] = $_POST['tutor_id'];
        }
        
        if($_GET['tutor_id']){
            $data['db_value'] = getDataByWhere("user","*",array("user_id"=>$_GET['tutor_id']));
//            $data['msg'].="<script>setTimeout(\"location.href = 'tutor-list?action=employee-list';\",2500);</script>";
        }
        
        if(isset($_POST['submit']))
        {
            if($_GET['tutor_id']){
                if (isset($_POST['change_password'])) {
                    updateTable("user",array("first_name"=>$_POST["first_name"],"last_name"=>$_POST["last_name"],"password"=>md5($_POST['password']),"email"=>$_POST["email"],"mobile"=>$_POST["mobile"],"paypal_id"=>$_POST["paypal_id"],"activation_status"=>$_POST["activation_status"]), array("user_id"=>$_GET['tutor_id']));
                }else{
                    updateTable("user",array("first_name"=>$_POST["first_name"],"last_name"=>$_POST["last_name"],"email"=>$_POST["email"],"mobile"=>$_POST["mobile"],"paypal_id"=>$_POST["paypal_id"],"activation_status"=>$_POST["activation_status"]), array("user_id"=>$_GET['tutor_id']));
                }
                
                // updateTable("user",array("first_name"=>$_POST["first_name"],"last_name"=>$_POST["last_name"],"email"=>$_POST["email"],"mobile"=>$_POST["mobile"],"paypal_id"=>$_POST["paypal_id"],"activation_status"=>$_POST["activation_status"]), array("user_id"=>$_GET['tutor_id']));

                $data['db_value'] = getDataByWhere("user","*",array("user_id"=>$_GET['tutor_id']));
                $data['msg']=str_replace("{table}","Tutor ",$configs['success_msg']);
            }
            else{
                
                unset($_POST['tutor_id']);
                $_POST['reg_date']=date("Y-m-d");
                $_POST['user_type']="Tutor";
                $_POST['password']=md5($_POST['password']);

                $flag = true;
                $code = random_int(10000000, 99999999);;
                while($flag){
                    $code_exist = getFldValueById("user", "referral_code", array("referral_code"=>$code));

                    if((int)$code_exist){
                        $code = random_int(10000000, 99999999);
                    }
                    else{
                        $flag=false;
                    }
                }
                $_POST['referral_code']=$code;

                $exist=getTotalRow("user",array("email"=>$_POST['email']));

                if($exist)
                {
                    $data['msg']="<font color=red>User Email Exists!</font>";
                    $data['db_value']=$_POST;
                }
                else
                {
                    $r=insertTable("user",$_POST);
                }

              if($r){	
                    $data['msg']=str_replace("{table}","Tutor ",$configs['success_msg']);

    //				$data['msg'].="<script>setTimeout(\"location.href = 'tutor-list?action=employee-list';\",2500);</script>";
                }
                else
                {	
                    $data['msg']=$configs['error_msg'];
                    $data['db_value']=$_POST;
                }
            }
        }  
        
    break;
    
}	

$data['action']=$_GET['action'];

echo template($data); 
?>
 

