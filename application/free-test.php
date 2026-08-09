<?
include("../configs/autoload.php");
$alphabet=array("1"=>"A","2"=>"B","3"=>"C","4"=>"D");

function seo_url($x)
{
	$x=str_replace(" ","-",$x);
	$x=preg_replace('/[^a-z0-9_-]/i', '', $x);
	 
	if(!$x)
	{
		 $x="more-info";
	} 	 
	return strtolower($x);
    
}
function autonumber()
{
	for ($randomNumber = mt_rand(1, 9), $i = 1; $i < 10; $i++) 
	{
    	$randomNumber .= mt_rand(0, 9);
    	
    }
    return $randomNumber;
}
if(!$_SESSION['user_id'])
{
	$_SESSION['user_id']=autonumber();
} 

$meta_title=str_replace("{course}",ucwords(str_replace("-"," ",$cat_name)),$meta_title);
$meta_keys=str_replace("{course}",ucwords(str_replace("-"," ",$cat_name)),$meta_keys);
$meta_desc=str_replace("{course}",ucwords(str_replace("-"," ",$cat_name)),$meta_desc);

$categories=getQuery("category","*",array("parent_id"=>0,"cat_id !"=>70),"cat_name");

if(!$_GET['cat_id'])
{
	$_GET['cat_id']=57;
} 
$cat_name=getFldValueById("category","cat_name",array("cat_id"=>$_GET['cat_id']));

$total=10;

$questions=getQueryByJoin(array("questions_free as q","question_answer as qa"),"q.*,qa.*",array("q.cat_id"=>$_GET['cat_id'],"qa.free"=>1),array("qa.question_id"=>"q.id"),"rand()",$total);

$answers=array();

foreach($questions as $a)
{
	$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id'],"free"=>1),"id");
}

if($_GET['cat_name']=="score")
{
 
 $score=getTotalRow("free_exam_score",array("cat_id"=>$_GET['cat_id'],"correct"=>1,"user_id"=>$_SESSION['user_id']));
 
 
  
}
$data=[];
if($_GET['cat_name']=="myscore")
{
	
	$_SESSION['email']=getFldValueById("enquiry","email",array("user_id"=>$_SESSION['user_id']));
	
	$questions=[];$score=[];
	$cats=[];
	$my_answers=array();
	$answers=array();
	 
    $score =getTotalRow("free_exam_score",array("cat_id"=>$c['cat_id'],"correct"=>1,"user_id"=>$_SESSION['user_id']));

	
	$questions=getQueryByJoin(array("questions_free as q","question_answer as qa","free_exam_score as f"),"q.*,qa.*",array("q.cat_id"=>$_GET['cat_id'],"qa.free"=>1,"f.user_id"=>$_SESSION['user_id']),array("qa.question_id"=>"q.id","q.id"=>"f.question_id"));
	 
	foreach($questions  as $a)
	{
	 
		$answers[$a['question_id']]=getQuery("answers","*",array("question_id"=>$a['question_id'],"free"=>1),"id");
	 
	}

	$i=0;
	foreach($questions  as $q)
	{
		$i++;
		$my_answers[$q['question_id']]=getFldValueById("free_exam_score","answer_no",array("user_id"=>$_SESSION['user_id'],"question_id"=>$q['question_id']));
	  
	}

}
?>			
