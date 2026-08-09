<?
include("../common/access.php");
include("../common/autoload.php");

/*below*/
if(isset($_POST['submit']))
{
    //$query=array("cat_name"=>$_POST['cat_name'],"status"=>$_POST['status']);
    unset($_POST['submit'],$_POST['questions'],$_POST['delete']);

   	if($_POST['id']):
   	    $r= updateTable("questions",$_POST,array("id"=>$_POST['id']));
        $data['id']=$_POST['id'];
	else:
	    unset($_POST['id']);
	    $r=insertTable("questions",$_POST);
		$data['id']=$r;
		if($_POST['set_id'])
		{
			updateSet($_POST['set_id'],$data['id']);
		}	
	endif;
	if($r):
 	
	 	$data['msg']=$configs['success_msg'];
	endif;
}   
if(isset($_POST['delete'])):
    $r=deleteData("questions",array("id"=>$_POST['id']));
 
	if($r):
		 if($_POST['set_id'])
		 {
		 	removeSet($_POST['set_id'],$_POST['id']);
	     }
	     
	     $data['msg']=$configs['delete_msg'];
	     
	endif;
endif;

if($_REQUEST['id'])
{
   $data['id']=$_REQUEST['id'];
   $data['db_value']= getDataByWhere("questions","*",array("id"=>$_REQUEST['id']));

    $answers=getQuery("answers","*",array("question_id"=>$_REQUEST['id']),"id");
    $i=0;$answer_html="";
    foreach($answers as $ans)
	{	
    
        if($ans['is_correct']=="Y"){$checked="checked";}else{$checked="";}
	    $answer_html.="<div class='row form-group'>
		<div class='col-md-3'><label class='form-control-label'>Answer " . ($i+1) ."</label></div>
		<div class='col-md-9'><textarea  class='form-control answer' name='answer[]' REQUIRED>" . html_entity_decode($ans['answer']) ."</textarea>
        Mark This Correct</b> <input type='radio' name='is_correct' " .$checked ." value='" .($i+1) ."'>

        Need Explanation ? <input type='checkbox' onClick=\"toggle('expl" .($i+1) ."');\">
		</div></div>
	 
		<div class='row form-group' id='expl" .($i+1) ."' style='display:none'>
		<div class='col-md-3'><label class='form-control-label'></label></div>
		<div class='col-md-9'><textarea placeholder=\"Explanation of Answer " .($i+1) ."\" class='form-control' name='explanation[]' ></textarea>
		</div></div>";
		 
		$i++;
	}	
    $data['answers']=$answer_html;
}




$data['page_title']="Questions Form ";

$data['breadcrumb']=array("Question Form Management"=>"questions_list?cat_id=".$_REQUEST['cat_id'],"Add | Edit Question"=>"");
$data['btn']="<button id='submit' class='btn btn-success'>Update</button> <a href='javascript:history.back()' class='btn btn-info'>Back To List</a>";
 
echo template($data);  
?>
