<?php 
include("../configs/autoload.php");

chklogin(str_replace("/practice-test/","",$_SERVER['REQUEST_URI']));

$param=[];
$query=array("u.user_type"=>"Student");

$data['test']=getQuery("course","*",array(),"course_name");
$test_name=[];

$student_total=[];

foreach($data['test'] as $t)
{
	$student_total[$t['course_id']]=getTotalRowsByJoin(array("user as u","subscription as s"),array_merge($query,array("s.course_id"=>$t['course_id'])),array("u.user_id"=>"s.user_id"));
    
    $test_name[$t['course_id']]=getFldValueById("course","course_name",array("course_id"=>$t['course_id']));
}	
$data['test_name']=$test_name;
$data['student_total']=$student_total;
 
if($_GET['key'])
{
	$query=array_merge($query,array("[or]"=>"(u.mobile like '%" .$_GET['key'] ."%' or u.email like '%" .$_GET['key'] ."%' or concat(u.first_name,' ',u.last_name) like '%" .$_GET['key'] ."%')"));
	
}

if($_GET['reg_at']){
    // $query=array_merge($query,array("u.reg_at like '%"=>$_GET['reg_at']."%'"));

    if($_GET['reg_at']=="app"){
        // $query=array_merge($query,array("c.app"=>1));
        $query=array_merge($query,array("u.reg_at like '%"=>"app%'"));
    }else if($_GET['reg_at']=="subscribe"){
        // $query=array_merge($query,array("c.subscribe"=>1));
        $query=array_merge($query,array("u.reg_at like '%"=>"web%'"));
        $query=array_merge($query,array("u.tutor_sub"=>"Y"));
    }else if($_GET['reg_at']=="tutoring"){
        // $query=array_merge($query,array("c.tutoring"=>1));
        $query=array_merge($query,array("u.reg_at like '%"=>"web%'"));
        $query=array_merge($query,array("u.tutor_sub"=>"N"));
    }
    $param[]="reg_at=".$_GET['reg_at'];
}

if($_GET['payment_status'])
{
    if($_GET['payment_status']=="paid"){
        $query=array_merge($query,array("u.activation_status"=>"Y"));
    }else if($_GET['payment_status']=="unpaid"){
        $query=array_merge($query,array("u.activation_status"=>"N"));
    }else if($_GET['payment_status']=="all"){
        // $query=array_merge($query,array("c.tutoring"=>1));
    }
	
	$param[]="payment_status=".$_GET['payment_status'];
}

if($_GET['test_id'])
{
	$query=array_merge($query,array("s.course_id"=>$_GET['test_id']));
	
	$param[]="test_id=".$_GET['test_id'];
}

if($_POST['status'])
{
    if($_POST['status']=='T'){
        $query=array_merge($query,['u.activation_status'=>'Y', "s.amount"=>0]);
    }
    else{
        $query=array_merge($query,['u.activation_status'=>$_POST['status']]);
    }
    
    $param[]="status=".$_POST['status'];
}

$data['param']=implode("&",$param);

$data['all_total']=getTotalRowsByJoin(array("user as u", "course as c","subscription as s"),$query,array("u.user_id"=>"s.user_id", "s.course_id"=>"c.course_id"));

$data['total_active']=getTotalRowsByJoin(array("user as u", "course as c", "subscription as s"),array_merge($query,['u.activation_status'=>'Y', "s.amount!"=>0]),array("u.user_id"=>"s.user_id", "s.course_id"=>"c.course_id"));

$data['total_inactive']=getTotalRowsByJoin(array("user as u", "course as c", "subscription as s"),array_merge($query,['u.activation_status'=>'N']),array("u.user_id"=>"s.user_id", "s.course_id"=>"c.course_id"));
	
$data['total_free_active']=getTotalRowsByJoin(array("user as u", "course as c", "subscription as s"),array_merge($query,['u.activation_status'=>'Y', 's.amount'=>0]),array("u.user_id"=>"s.user_id", "s.course_id"=>"c.course_id"));


$data['db_list']=getQueryByJoin(array("user as u", "course as c","subscription as s"),"u.*,s.*",$query,array("u.user_id"=>"s.user_id", "s.course_id"=>"c.course_id"),"u.user_id desc",$configs['limit']);

$data['total']=getTotalRowsByJoin(array("user as u", "course as c","subscription as s"),$query,array("u.user_id"=>"s.user_id", "s.course_id"=>"c.course_id"));

$data['breadcrumb']=array("Student List"=>""); 
$data['page_title']="Student List [".$data['total']."]";

echo template($data);
?>