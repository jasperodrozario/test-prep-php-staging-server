<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");


$data['page_title']="Add Question";
$data['breadcrumb']=array("Add Question"=>"");
 
$data['categories']= getQuery("category","*",array("parent_id"=>0,"status"=>"N"),"cat_name");
 

if(!$_REQUEST['page'])
{
	$_REQUEST['page']='add-question';

}
               
if($_REQUEST['page'])
{
	switch($_REQUEST['page'])
	{
		case "question-list":
		    
		    if($_REQUEST['del_id'])
		    {
				$r=deleteData("questions_bulk",array("id"=>$_REQUEST['del_id']));
			    $r=deleteData("answers_bulk",array("quesion_id"=>$_REQUEST['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
		    
		    if($_REQUEST['search'])
		    {
			    $data['db_list']= getQuery("questions","*",array("question like "=>"'%".$_REQUEST['search'] ."%'"));
	     	    $data['total']=getTotalRow("questions",array("question like "=>"'%".$_REQUEST['search'] ."%'"));
			}
			else
			{
				$query=array("cat_id"=>$_REQUEST['cat_id']);
				
				if($_REQUEST['lesson_id'])
				{
					$query=array_merge($query,array("lesson_id"=>$_REQUEST['lesson_id']));
				}
				if($_REQUEST['grade'])
				{
					$query=array_merge($query,array("grade"=>$_REQUEST['grade']));
				}
					
				$data['db_list']= getQuery("questions_bulk","*",$query,"id desc",$configs['limit']);
	            $data['total']=getTotalRow("questions_bulk",$query);
			
			}	
			$answers_bulk=array();
	    	
	    	foreach($data['db_list'] as $a)
	    	{
				$answers_bulk[$a['id']]=getQuery("answers_bulk","*",array("question_id"=>$a['id']));
			}
		
	  		$data['answers_bulk']=$answers_bulk;
	       
	    break;

	    case "add-question":
	        if($_REQUEST['id'])
	        {
				$data['id']=$_REQUEST['id'];
		    	$data['db_value']= getDataByJoin(array("questions_bulk as q","question_answer_bulk as qa"),"q.*,qa.answer_no,qa.explanation",array("q.id"=>$_REQUEST['id']),array("qa.question_id"=>"q.id"));
                
		    	$data['answers_bulk']=getQuery("answers_bulk","*",array("question_id"=>$_REQUEST['id']),"id");
		        $data['total_answers_bulk']=getTotalRow("answers_bulk",array("question_id"=>$_REQUEST['id']));
		    }
		    $query=array("cat_id"=>$_REQUEST['cat_id']);
			
		    $data['total']=getTotalRow("questions_bulk",$query);
		    $data['story_list']=getQuery("contents","*",array("status"=>"S"),"title");
	     	
		break;
		default:
		
		    if($_REQUEST['del_id'])
		    {
				$r=deleteData("questions_bulk",array("id"=>$_REQUEST['del_id']));
			    $r=deleteData("answers_bulk",array("quesion_id"=>$_REQUEST['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
			
		   
             if($_REQUEST['search'])
		    {
			    $data['db_list']= getQuery("questions_bulk","*",array("question like "=>"'%".$_REQUEST['search'] ."%'"),'id');
	     	    $data['total']=getTotalRow("questions_bulk",array("question like "=>"'%".$_REQUEST['search'] ."%'"));
	     	}
	     	
			else
			{
			    $data['db_list']= getQuery("questions_bulk","*",array(),"id",$configs['limit']);
			    $data['total']=getTotalRow("questions_bulk");
	     	
			}
		    $answers_bulk=array();
	    	
	    	foreach($data['db_list'] as $a)
	    	{
				$answers_bulk[$a['id']]=getQuery("answers_bulk","*",array("question_id"=>$a['id']));
			}
		
	  		$data['answers_bulk']=$answers_bulk;
	       
	    break;
       	 
    }
}


	        
$data['page_title']="Total Questions : ".$data['total']." <u>".$data['cat_name']."</u>";
$data['breadcrumb']=array("Category List"=>"category_list",  
"Total questions_bulk <u>".$data['cat_name']."</u>"=>"");

            
$data['option_no']=5;
 

echo template($data);
?>

 
