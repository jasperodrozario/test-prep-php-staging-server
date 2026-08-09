<?php 
include 'configs/autoload.php'; 
 
$data['how_can_help']=getFldValueById("contents","contents",array("cont_title"=>'How we can help'));
$data['why_us']=getFldValueById("contents","contents",array("cont_title"=>'Why Us'));
$data['course_study_video']=getDataByWhere("course_help_videos","*",array("is_online"=>"Y", "course_id"=>$_SESSION['test_id']));
$data['total_course_videos']=getTotalRow("course_help_videos", array("is_online"=>"Y", "course_id"=>$_SESSION['test_id']));

$data['test']=[];

if(!$_SESSION['user_id']){
    header("location:https://staging-test-prep.asvab-tutoring.com");
}

if($_SESSION['test_id']){
    $data['cat_list']=getQuery("category","*",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"),"category_order");
    foreach($data['cat_list'] as $c){
        $data['test'][]=["id"=>$c['cat_id'], "name"=>$c['category_name']];
    }

//    $data['predic_list']=getQuery("predic_test","*",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"),"test_order");
//    foreach($data['predic_list'] as $p){
//        $data['test'][]=["id"=>$p['Predic_test_id'], "name"=>$p['test_name'], "pred_test"=>1];
//    }
}
else{
    $data['course_list']=getQuery("course","*",array(),"ord");
    foreach($data['course_list'] as $c){
        $data['test'][]=["id"=>$c['course_id'], "name"=>$c['course_name'], "course"=>1];
    }
}

// $data['faq']=getQuery("faq","*",array("status"=>"Y"),"","");

$data['feedback']=getQuery("feedback","*",array("admin_chk"=>"Y"),"","");

$data['home_content']=getDataByWhere("contents","*",array("id"=>44));



echo template($data);
                                 
?> 