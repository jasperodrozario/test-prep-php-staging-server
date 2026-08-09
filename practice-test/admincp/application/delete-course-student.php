<?php 
include("../common/access.php");
include("../common/autoload.php");

$data['breadcrumb']=array("Student List"=>"student-list", "Delete Students by Course"=>""); 
$data['page_title']="Delete Students by Course";

$data['test']=getQuery("course","*",array(),"course_name");
$test_name=[];

$student_total=[];

foreach($data['test'] as $t)
{
	$student_total[$t['course_id']]=getTotalRowsByJoin(array("user as u","subscription as s"),array("s.course_id"=>$t['course_id']),array("u.user_id"=>"s.user_id"));
    
    $test_name[$t['course_id']]=getFldValueById("course","course_name",array("course_id"=>$t['course_id']));
}	
$data['test_name']=$test_name;
$data['student_total']=$student_total;


$data['platform']=getQuery("user","DISTINCT(reg_at)",array(),"reg_at");


if(isset($_POST['delete_course_students']))
{
	$query = array();

    $del_accounts = getQueryByJoin(array("user as u","subscription as s"),"u.*", array("s.course_id"=>(int)$_POST['test_id']), array("u.user_id"=>"s.user_id"));
	
	// getQuery("subscription","*",array("course_id"=>(int)$_POST['test_id']));
	$cnt = 0;

    foreach ($del_accounts as $id)
    {
		if($id['user_id'])
		{
			deleteData("user",array("user_id"=>$id['user_id']));
			deleteData("login",array("user_id"=>$id['user_id']));
            deleteData("subscription",array("user_id"=>$id['user_id']));
			
			$cnt+=1;
		}
	}
	$data['message'] = "Number of deleted students: ".$cnt;
}

echo template($data);
?>