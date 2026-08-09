<?
include_once("../common/access.php");
include_once("../common/autoload.php");

unset($configs['includes'],$data['menus'],$data['menu_open_id']);

if(!$_GET['info'])
{
	$_GET['info']="login";
}

$data['info']=$_GET['info'];

switch($_GET['info'])
{
	case "login":
	case "grant":
		$data['db_value']=getDataByWhere("user","*",array("user_id"=>$_GET['user_id']));
    break;
    
	case "info":
	 
		$data['db_value']=getDataByJoin(array("user as u","subscription as s"),"u.*,s.*",array("u.user_id"=>$_GET['user_id']),array("s.user_id"=>"u.user_id"));
		
		$data['test_names']=getQuery("course","*",array(),"course_name");
		$data['state_list']=getQuery("state","*",array("country_id"=>$configs['country_id']),"name");

		 
		if($data['db_value']['state_id'])
		{
			$data['city_list']=getQuery("city","*",array("state_id"=>$data['db_value']['state_id']),"name");	
		}
	break;
	 
    case "applied":
    
        $data['db_list']=getQueryByJoin(array("exam_result as x","category as c"),"c.cat_name,x.*",array("x.user_id"=>$_GET['user_id']),array("x.cat_id"=>"c.cat_id"),"x.date");
		
 
		//$data['db_list']=getQueryByJoin(array("running_exam as r","category as c"),"c.*",array("r.user_id"=>$_GET['user_id']),array("r.set_id"=>"c.cat_id"));
   
    break;
  
    case "enquires":
 
		$data['db_list']=getList("enquiry",array(array("student","first_name,last_name","user_id=student_id"),array("login","email,mobile","user_id=student_id"),"subject","message"),$action,array("student_id"=>$_GET['user_id']));
    
    break;
    case "notes":
		$data['notes']=getQuery("notes","*",array("user_id"=>$_REQUEST['user_id']),"date desc");
    break;
    case "mails":
		$data['mails']=getQuery("email","*",array("user_id"=>$_REQUEST['user_id']),"date desc");
    break;
 	 
} 

$data['countries']=getQuery("country");
echo template($data);  
?>