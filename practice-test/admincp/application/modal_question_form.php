<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");
$data['cat_name']= getFldValueById("category","cat_name",array("cat_id"=>$_GET['cat_id']));

$data['page_title']="Add Question in <u>".$data['cat_name']."</u>";
$data['breadcrumb']=array("Category List"=>"category_list",  
    "Add Question in <u>".$data['cat_name']."</u>"=>"");
 
$data['categories']= getQuery("category","*",array("parent_id"=>0),"cat_name");



if(!$_REQUEST['page'])
{
	$_REQUEST['page']='all-question-list';

}
               
if($_REQUEST['page'])
{
	switch($_REQUEST['page'])
	{
		case "question-list":
		    
		    
		     
		    if($_REQUEST['del_id'])
		    {
				$r=deleteData("questions",array("id"=>$_REQUEST['del_id']));
			    $r=deleteData("answers",array("quesion_id"=>$_REQUEST['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
		    
		    if($_REQUEST['search'])
		    {
			    $data['db_list']= getQuery("questions","*",array("question like "=>"'%".$_REQUEST['search'] ."%'"));
	     	    $data['total']=getTotalRow("questions",array("question like "=>"'%".$_REQUEST['search'] ."%'"));
			}
			else
			{
				if($_REQUEST['lesson_id'])
				{
					$data['db_list']= getQuery("questions","*",array("cat_id"=>$_REQUEST['cat_id'],"lesson_id"=>$_REQUEST['lesson_id']),"id desc",$configs['limit']);
	                $data['total']=getTotalRow("questions",array("cat_id"=>$_REQUEST['cat_id'],"lesson_id"=>$_REQUEST['lesson_id']));
			
				}
				else
				{
					$data['db_list']= getQuery("questions","*",array("cat_id"=>$_REQUEST['cat_id']),"id desc",$configs['limit']);
	                $data['total']=getTotalRow("questions",array("cat_id"=>$_REQUEST['cat_id']));
			
				}
		
			}
			$answers=array();
	    	
	    	foreach($data['db_list'] as $a)
	    	{
				$answers[$a['id']]=getQuery("answers","*",array("question_id"=>$a['id']));
			}
		
	  		$data['answers']=$answers;
	       
	    break;

	    case "add-question":
	        if($_REQUEST['id'])
	        {
				$data['id']=$_REQUEST['id'];
		    	$data['db_value']= getDataByJoin(array("questions as q","question_answer as qa"),"q.*,qa.answer_no,qa.explanation",array("q.id"=>$_REQUEST['id']),array("qa.question_id"=>"q.id"));
                
		    	$data['answers']=getQuery("answers","*",array("question_id"=>$_REQUEST['id']),"id");
		        $data['total_answers']=getTotalRow("answers",array("question_id"=>$_REQUEST['id']));
		    }
		    else
		    {
				$data['db_value']['cat_id']=$_GET['cat_id'];
			}
	        $data['lesson_list']=getQuery("lesson","*",array("cat_id"=>$data['db_value']['cat_id']),"name");
            $data['total']=getTotalRow("questions",array("cat_id"=>$_REQUEST['cat_id'],"lesson_id"=>$_REQUEST['lesson_id']));
	     	
		break;
		default:
		
		    if($_REQUEST['del_id'])
		    {
				$r=deleteData("questions",array("id"=>$_REQUEST['del_id']));
			    $r=deleteData("answers",array("quesion_id"=>$_REQUEST['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
			
		   
             if($_REQUEST['search'])
		    {
			    $data['db_list']= getQuery("questions","*",array("question like "=>"'%".$_REQUEST['search'] ."%'"),'id');
	     	    $data['total']=getTotalRow("questions",array("question like "=>"'%".$_REQUEST['search'] ."%'"));
	     	}
			elseif($_REQUEST['cat_id'])
			{
				$data['db_list']= getQuery("questions","*",array("cat_id"=>$_REQUEST['cat_id']),"id desc",$configs['limit']);
			    $data['total']=getTotalRow("questions",array("cat_id"=>$_REQUEST['cat_id']));
	     	
			} 
			else
			{
			    $data['db_list']= getQuery("questions","*",array(),"id",$configs['limit']);
			    $data['total']=getTotalRow("questions");
	     	
			}
		    $answers=array();
	    	
	    	foreach($data['db_list'] as $a)
	    	{
				$answers[$a['id']]=getQuery("answers","*",array("question_id"=>$a['id']));
			}
		
	  		$data['answers']=$answers;
	       
	    break;
       	 
    }
}

 

            
$data['option_no']=4;
 
 
echo template($data);
?>

 
