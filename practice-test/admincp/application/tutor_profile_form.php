<?
include_once("../common/access.php");
include_once("../common/autoload.php");
include("../../../configs/array.php");
 
unset($configs['includes'],$data['menus'],$data['menu_open_id']);


if(!$_REQUEST['action'])
{
	$_REQUEST['action']="add-tutor-login";
}

$data['action']=$_REQUEST['action'];

switch($_REQUEST['action'])
{
	case "tutor-info":
	   
	    
	    $countries=getQuery("country");
	    $array=array();
	    foreach($countries as $c)
	    {
			$array[$c['id']]=$c['name'];
		}
		$data['countries']=$array;
		$data['db_value']=getDataByWhere("tutor","*",array("user_id"=>$_REQUEST['user_id']));
		
		
		 $data['form_fields']=Array
		(
	       
	    Array('name' => 'fname','type' => 'text','value' => $data['db_value']['fname'],'required' => ''),
	    Array('name' => 'lname','type' => 'text','value' => $data['db_value']['lname'],'required' => 'required'),
	    Array('name' => 'mobile','type' => 'text','value' => $data['db_value']['mobile'],'required' => 'required'),
	    Array('name' => 'country_id','type' => 'select','value' => $data['db_value']['country_id'],'required' => 'required','option'=>$data['countries']),
	     
	    Array('name' => 'city_name','type' => 'text','value' => $data['db_value']['city_name'],'required' => 'required'),
	    Array('name' => 'about','type' => 'textarea','value' => $data['db_value']['about'],'required' => 'required'),
	   
	    Array('name' => 'photo','type' => 'file','accept'=>'.png, .jpg, .jpeg','value' => $data['db_value']['photo'],'required' => ''),
	    
	    Array('name' => 'attachment','type' => 'file','accept'=>'.doc, .pdf','value' => $data['db_value']['attachment'],'required' => ''), 
	    Array('name' => 'submit','type' => 'submit','value' => "Save")  
		);	
		
	break;
	case "tutor-skill":
	
        $skills=getQuery("name_master","id,name",array("type"=>"skill"));
	    $array=array();
	    foreach($skills as $c)
	    {
			$array[$c['id']]=$c['name'];
		}
		$data['skills']=$array;
		
		if($_REQUEST['id'])
		{
			$data['db_value']=getDataByWhere("tutor_skill","*",array("user_id"=>$_REQUEST['user_id']));
	    }
	   
	    
	    $data['form_fields']=Array
		(
	    
	     
	    Array('label' => 'Skill','name' => 'skill','type' => 'checkbox','value' => $data['db_value']['skill'],'required' => '',"option"=>$data['skills']),
	    Array('name' => 'submit','type' => 'submit','value' => "Save")  
		);	
    break;
    case "students":
 
	
		$data['db_list']=getQueryByJoin(array("student as s","tutor_student as ts"),"s.*",array("ts.tutor_id"=>$_REQUEST['user_id']),array("s.user_id"=>"ts.student_id"),"s.fname");
   
    break;
    case "reviews":
 
		$data['db_list']=getList("tutor_reviews",array(array("student","first_name,last_name,email,mobile","user_id=student_id"),"rating","review"),$action,array("user_id"=>$_REQUEST['user_id']));
    
    break;
    case "enquires":
 
		$data['db_list']=getList("enquiry_list",array(array("student","first_name,last_name,email,mobile","student_id"),"enquiry_for","enquiry_desc"),$action,array("user_id"=>$_REQUEST['user_id']));
    
    break;
 	
} 
echo template($data);  
?>


