<?php 
include("../common/access.php");
include("../common/autoload.php");

if($_GET['delete_id'])
{
	deleteData("login",array("user_id"=>$_GET['delete_id']));
	deleteData("guest",array("user_id"=>$_GET['delete_id']));
	$data['msg']=$configs['delete_msg']; 
}


switch($_GET['action'])
{
    case"add-guest":
           
        $data['page_title']="Add Affiliate Guest";
		$data['breadcrumb']=array("Affiliate Guest Member Management"=>"");
		$data['btn']="<a href='guest-list' class='btn btn-success'>Back To List</a>";
		$query=array("l.user_id"=>$_GET['user_id']);

		$data['db_value']=getDataByJoin(array("guest as e","login as l"),"e.*,l.*",$query,array("e.user_id"=>"l.user_id"));
        
		 
        /*if(isset($_POST))
		{
            
		   	if(!$_POST['password'])
		   	{
		  		unset($_POST['password']);
			}	
			
		   	$_POST['reg_date']=date("Y-m-d"); 
		   	
		   	if($_POST['user_id'])
		   	{	
		   	    updateTable("login",$_POST,array("user_id"=>$_POST['user_id']));
		   	    $r=updateTable("guest",$_POST,array("user_id"=>$_POST['user_id']));
		    }
		    else
		    {	
			    unset($_POST['user_id']);
				$exist=getTotalRow("login",array("email"=>$_POST['email']));
	    
	   			if($exist)
	   			{
	   				$data['msg']="<font color=red>Email Exists!</font>";
	   				$data['db_value']=$_POST;
	   			}
	   			else
	   			{
			        $_POST['user_id']=insertTable("login",$_POST);

			        echo insertTable("login",$_POST,'',1);
			        exit;
			        if($_POST['user_id'])
			        {
			        	$r=insertTable("guest",$_POST);
			        }	
			        
			         
		        }
			
		    }
		    if($r)
		    {	
		 
			 	$data['msg']=str_replace("{table}","Guest ",$configs['success_msg']);
				$data['msg'].="<script>setTimeout(\"location.href = '" . $configs['admin_vpath'] . "guest-list';\",2500);</script>";
			}
			else
			{	
			
			    $data['msg']=$configs['error_msg'];
				$data['db_value']=$_POST;
			   
	        }   
		} */   
 
    
    break;
 
    default:

        $data['page_title']="Affiliate Guest List";
		$query=array("l.user_type"=>"G");

		if($_GET['status'])
		{
			$query=array_merge($query,['l.status'=>$_GET['status']]);
		}	
        

		$data['total']=getTotalRowsByJoin(array("guest as e","login as l"),$query,array("e.user_id"=>"l.user_id"));
        
       
		
        if($data['total']) 
        {
        	$data['db_list']=getQueryByJoin(array("guest as e","login as l"),"l.*,e.*",$query,array("e.user_id"=>"l.user_id"),"e.user_id desc",$configs['limit']);
        }	
		 
		$data['breadcrumb']=array("Affilate Guest List"=>""); 
		$data['btn']="<a href='" . $configs['admin_vpath'] . "guest-list?action=add-guest' class='btn btn-success'>Add New Guest</a>"; 
		$data['page_title']="Total Affiliate Guest List [".$data['total']."]";
}
echo template($data);
?>