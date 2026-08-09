<?
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");


$data['page_title']="Incomplte Question List";
$data['breadcrumb']=array("Category List"=>"category_list",  
    "Add Question in <u>".$data['cat_name']."</u>"=>"");
 
$data['categories']= getQuery("category","*",array("parent_id"=>0),"cat_name");

if(!$_GET['page'])
{
	$_GET['page']='question-list';
}

$data['option_no']=4;
 
if($_GET['cat_id']==71)
{
	$data['option_no']=0;
}	 
if($_GET['cat_id']==87 || $_GET['cat_id']==80 || $_GET['cat_id']==88 || $_GET['cat_id']==90 || $_GET['cat_id']==79)
{ 
	$data['option_no']=5;
}
if($_POST['Delete'])
{
	 
	$j=0;
	for($i=0;$i<count($_POST['quid']);$i++)
	{
		if($_POST['quid'][$i])
		{
			deleteData("questions",array("id"=>$_POST['quid'][$i]));
			deleteData("answers",array("quesion_id"=>$_POST['quid'][$i]));
			deleteData("question_answer",array("quesion_id"=>$_POST['quid'][$i]));
			$j++;
		}

	}

	$data['msg']="<font color=red>" .$j .  " questions deleted!</font>";
}
 
               
if($_GET['page'])
{
	switch($_GET['page'])
	{
		case "question_list":
		    
		    if($_GET['del_id'])
		    {
				$r=deleteData("questions",array("id"=>$_GET['del_id']));
			    $r=deleteData("answers",array("quesion_id"=>$_GET['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
		    
			$data['db_list']= getQuery("questions","*",array("[or]"=>"cat_id is null or lesson_id is null"),"id desc",$configs['limit']);
	        
			$data['total']=getTotalRow("questions",array("[or]"=>"cat_id is null or lesson_id is null"));
				
			$answers=array();
	    	
	    	foreach($data['db_list'] as $a)
	    	{
				$answers[$a['id']]=getQuery("answers","*",array("question_id"=>$a['id']),"id",$data['option_no']);
			}
		
	  		$data['answers']=$answers;
	       
	    break;
	    case "add-question":

	        if($_GET['id'])
	        {
				$data['id']=$_GET['id'];
		    	$data['db_value']= getDataByJoin(array("questions as q","question_answer as qa"),"q.*,qa.answer_no,qa.explanation",array("q.id"=>$_GET['id']),array("qa.question_id"=>"q.id"));
                
		    	$data['answers']=getQuery("answers","*",array("question_id"=>$_GET['id']),"id",$data['option_no']);
		        $data['total_answers']=getTotalRow("answers",array("question_id"=>$_GET['id']));
		    }
		    else
		    {
				$data['db_value']['cat_id']=$_GET['cat_id'];
			}
	        $data['lesson_list']=getQuery("lesson","*",array("cat_id"=>$data['db_value']['cat_id']),"name");
            
            if($_GET['cat_id']==71)
	        {
	        	$data['total']=getTotalRow("questions",array("cat_id"=>$_GET['cat_id']));
	
	        }
	        else
	        {
	        	$data['total']=getTotalRow("questions",array("cat_id"=>$_GET['cat_id'],"lesson_id"=>$_GET['lesson_id']));
	
	        }		
            //$data['total']=getTotalRow("questions",array("[or]"=>"(platform=0 or platform=" .$_GET['platform'].")","cat_id"=>$_GET['cat_id'],"lesson_id"=>$_GET['lesson_id']));
	     	if($_GET['cat_id']==19 || $_GET['cat_id']==88 || $_GET['cat_id']==80 || $_GET['cat_id']==79 ||  $_GET['cat_id']==90)
	        {
	        	$data['story_list']=getQuery("contents","*",array("parent_id"=>$_GET['cat_id']));
	
	        }
		break;
		default:
		
		    if($_GET['del_id'])
		    {
				$r=deleteData("questions",array("id"=>$_GET['del_id']));
			    $r=deleteData("answers",array("quesion_id"=>$_GET['del_id']));
                $data['msg']=$configs['delete_msg'];
			}	
			
		    $lesson_list="248,267,275,215,255,227,243,244,190,259,203,204,205,206,210,260,261,262,263,264,187,188,189,201,228,193,192,191,198,197,174,185,199,184,200,211,242,221,214,212,213,219,229,239,240,216,241,222,223,291,265,266,281,282,274,276,272,273,268,269,271,292,293,294,295,252,253,256,270,257,258,287,288,289,290,250,251,279,283,236,237,286,202,207,208,209,217";
            
			$data['db_list']= getQuery("questions","*",array("cat_id !"=>71,"lesson_id not in "=>"(". $lesson_list . ")"),"id desc",$configs['limit']);
	        
			$data['total']=getTotalRow("questions",array("cat_id !"=>71,"lesson_id not in "=>"(". $lesson_list . ")"));
			
			//echo getTotalRow("questions",array("cat_id !"=>71,"lesson_id not in "=>"(". $lesson_list . ")"),1);
		   
		    $answers=array();
	    	
	    	foreach($data['db_list'] as $a)
	    	{
				$answers[$a['id']]=getQuery("answers","*",array("question_id"=>$a['id']),"id",$data['option_no']);
			}
		
	  		$data['answers']=$answers;
	       
	    break;
       	 
    }
}
         
$data['page_title']="Total Incomplte Questions : ".$data['total'];
$data['breadcrumb']=array("Category List"=>"category_list",  
"Total Questions <u>".$data['cat_name']."</u>"=>"");

 
echo template($data);
?>

 
