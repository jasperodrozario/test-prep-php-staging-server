<?php
include("../configs/autoload.php");

// $data['meta_title']="Course Details";

$exist=getTotalRow("ocs_course",array("seo_url"=>$_GET['seo_url']));

$course_name = strtoupper(ucwords(str_replace("-", " ", $_GET['seo_url'])));
$course_id = (int)getFldValueById("course","course_id",array("course_name"=>$course_name));
$data['course_id'] = $course_id;
$ft_cat_list=getQuery("category","*",array("course_id"=>$course_id, "free_test"=>1),"category_order");

$data['ft_cat_list'] = array();
$data['ft_ques_list'] = array();
foreach($ft_cat_list as $i){

	if(!$i['num_free_test_ques']){
		$i['num_free_test_ques'] = 10;
	}

	$data['ft_cat_list'][] = $i['category_name'];

	$data['ft_ques_list'][$i['category_name']] = getQueryByJoin(
		array("question as q", "free_test_questions as l"),
		"q.*, l.*",
		array("l.course_id" => $course_id, "l.cat_id" => $i['cat_id']),
		array("q.qid" => "l.qid"),
		"",
		$i['num_free_test_ques']
	);
}

if(!$exist)
{
	header("location:".$configs['vpath']);
	exit;
}

$data['db_value']=getDataByWhere("ocs_course","*",array("seo_url"=>$_GET['seo_url']));

$data['similar_courses']=getQuery("ocs_course","*",array("course_id !"=>$data['db_value']['course_id'], "status"=>"Y"));

$data['number_of_courses']=getTotalRow("ocs_course",array("course_id !"=>$data['db_value']['course_id'], "status"=>"Y"));

$course_id = getFldValueById("course","course_id",array("course_name"=>strtoupper(str_replace('-', ' ', $_GET['seo_url'])), "is_online"=>"Y"));

$data['num_prac_ques']=getTotalRow("lesson_questions",array("course_id"=>$course_id));

$data['num_lessons']=getTotalRow("lesson",array("course_id"=>$course_id, "online"=>"Y"));

$data['num_flash']=getFldValueById("category","num_of_ques_pt",array("course_id"=>$course_id, "online"=>"Y", "shortcode"=>"FC"));

$exist=getTotalRow("ocs_faq",array("course_id"=>$data['db_value']['course_id']));

if($exist)
{
	$data['total_faq']=$exist;
	$data['faqs']=getQuery("ocs_faq","*",array("course_id"=>$data['db_value']['course_id']),"ord"); 
}

$data['feedback']=getQuery("ocs_feedback","*",array("course_id"=>$data['db_value']['course_id'],"status"=>"Y"));

$rating_sum = (float)getFldValueById("ocs_feedback","sum(rating)",array("course_id"=>$data['db_value']['course_id'],"status"=>"Y"));
$total_review=(int)getTotalRow("ocs_feedback",array("course_id"=>$data['db_value']['course_id'],"status"=>"Y"));

if ($total_review > 0) {
    $data['avrg_rating'] = round($rating_sum / $total_review, 2);
} else {
    $data['avrg_rating'] = 0;          // or null, as you prefer
}
$data['total_review'] = $total_review;

if($_GET['location'])
{
	$meta_title=str_replace("{location}"," in ".ucwords(str_replace("-"," ",$_GET['location'])),$data['db_value']['meta_title']);
	$meta_keys=str_replace("{location}"," in ".ucwords(str_replace("-"," ",$_GET['location'])),$data['db_value']['meta_keys']);
	$meta_desc=str_replace("{location}"," in ".ucwords(str_replace("-"," ",$_GET['location'])),$data['db_value']['meta_desc']);
	$location=$_GET['location'] ."/";
}
else
{
	$meta_title=str_replace("{location}","",$data['db_value']['meta_title']);
	$meta_keys=str_replace("{location}","",$data['db_value']['meta_keys']);
	$meta_desc=str_replace("{location}","",$data['db_value']['meta_desc']);
	$location="";
}

$data['meta_title']=str_replace("{course}",ucwords(str_replace("-"," ",$_GET['seo_url'])),$meta_title);
$data['meta_keys']=str_replace("{course}",ucwords(str_replace("-"," ",$_GET['seo_url'])),$meta_keys);
$data['meta_desc']=str_replace("{course}",ucwords(str_replace("-"," ",$_GET['seo_url'])),$meta_desc);

$course_name = strtoupper(ucwords(str_replace("-", " ", $_GET['seo_url'])));
$course_id = getFldValueById("course","course_id",array("course_name"=>$course_name));
$data['course_name'] = getFldValueById("course", "course_name", array("course_id" => $course_id));
$data['cat_list'] = getQuery("category","*",array("course_id"=>$course_id, "online"=>"Y"),"cat_id");

if($_GET['cat_id']){
    $data['test_questions']=getQueryByJoin(array("question as q","lesson_questions as l"),"q.*,l.*",array("l.cat_id"=>$_GET['cat_id'],"l.course_id"=>$course_id),array("q.qid"=>"l.qid"),"rand()",15);
}

echo template($data);
?>