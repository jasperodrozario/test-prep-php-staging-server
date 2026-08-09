<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

if($_GET['cat_id']) 
{
	$data['cat_name']= getFldValueById("category","cat_name",array("cat_id"=>$_GET['cat_id']));

}

$data['page_title']="Add Question in <u>".$data['cat_name']."</u>";
$data['breadcrumb']=array("Category List"=>"category_list",  
    "Add Question in <u>".$data['cat_name']."</u>"=>"");
$data['categories']= getQuery("category","*",array("parent_id"=>0),"cat_name");
            
if($_GET['page'])
{
	switch($_GET['page'])
	{
		 
	    case "add-question":
	        if($_GET['id'])
	        {
				$data['id']=$_GET['id'];
		    	$data['db_value']= getDataByJoin(array("questions_free as q","question_answer as qa"),"q.*,qa.answer_no,qa.explanation",array("q.id"=>$_GET['id']),array("qa.question_id"=>"q.id","qa.free"=>1),1);
               
                $data['answers']=getQuery("answers","*",array("question_id"=>$_GET['id'],"free"=>1),"id");
		        $data['total_answers']=getTotalRow("answers",array("question_id"=>$_GET['id'],"free"=>1));
		    }
		    else
		    {
				$data['db_value']['cat_id']=$_GET['cat_id'];
			}
 
            $data['total']=getTotalRow("questions_free",array("cat_id"=>$_GET['cat_id']));
	     	$data['alltotal']=getTotalRow("questions_free");
	  
		break;
		default:
		    
		    if($_GET['del_id'])
		    {
				$r=deleteData("questions_free",array("id"=>$_GET['del_id']));
			    $r=deleteData("answers",array("quesion_id"=>$_GET['del_id'],"free"=>1));
                $data['msg']=$configs['delete_msg'];
			}	
			
		   
            if($_GET['search'])
		    {
			    $data['db_list']= getQuery("questions_free","*",array("question like "=>"'%".$_GET['search'] ."%'"),'id');
	     	    $data['total']=getTotalRow("questions_free",array("question like "=>"'%".$_GET['search'] ."%'"));
	     	    $data['altotal']=getTotalRow("questions_free");
	     	}
	        elseif($_GET['cat_id'])
			{
				$data['db_list']= getQuery("questions_free","*",array("cat_id"=>$_GET['cat_id']),"id desc",$configs['limit']);
			    $data['total']=getTotalRow("questions_free",array("cat_id"=>$_GET['cat_id']));
	     	    $data['alltotal']=getTotalRow("questions_free");
			} 
			else
			{
			    $data['db_list']= getQuery("questions_free","*",array(),"id",$configs['limit']);
			    
			    $data['total']=getTotalRow("questions_free");
	     	    $data['alltotal']=getTotalRow("questions_free");
			}
		    $answers=array();
	    	
	    	foreach($data['db_list'] as $a)
	    	{
				$answers[$a['id']]=getQuery("answers","*",array("question_id"=>$a['id'],"free"=>1),"id");
			}
		
	  		$data['answers']=$answers;

    }
}


	        
$data['page_title']="Total questions free : ".$data['total']." <u>".$data['cat_name']."</u>";
$data['breadcrumb']=array("Category List"=>"category_list",  
"Total questions_free <u>".$data['cat_name']."</u>"=>"");

            
$data['option_no']=4;
 

echo template($data);
?>

 
