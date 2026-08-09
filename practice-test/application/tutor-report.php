<?php
include("../configs/autoload.php");
chklogin(str_replace("/practice-test/","",$_SERVER['REQUEST_URI']));
 		    
$report=[];$lesson_list=[];$report_total=[];

if(!$_GET['cat_id'])
{
	$_GET['cat_id']="2000";
}	

if($_GET['cat_id']==2000)
{
	
}
else{
    
//    $cat=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));
//    $pred_cat=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id']));
//    
//    if($cat)
//    {
//        $data['db_value']=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));
//
//        $data['lesson_list']=getQuery("lesson","*",array("cat_id"=>$_GET['cat_id'],"online"=>"Y"));
//        $data['lesson_total']=getTotalRow("lesson",array("cat_id"=>$_GET['cat_id'],"online"=>"Y"));
//        
//        if($data['lesson_total'])
//        {
//            $data['test'] = "hello world";
//            foreach ($data['lesson_list'] as $l)
//            {
////                $data['test'] = "hello world";
//                $report[$l['lesson_id']]=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"lesson_id"=>$l['lesson_id'], "cat_id"=>$_GET['cat_id']),"result_id desc",5);
//                $report_total[$l['lesson_id']]=getTotalRow("result",array("user_id"=>$_SESSION['user_id'],"lesson_id"=>$l['lesson_id'], "cat_id"=>$_GET['cat_id']));
//            }
//        }
//
//        $data['report']=$report;  
//        $data['report_total']=$report_total; 
//        $data['cat_name']=$data['db_value']['cat_name'] ;	
//        $label=$data['cat_name'];
//    }		

}
//else
//{	
//
//	$data['afqt']=array("4444"=>"Arithmetic Reasoning","216"=>"Math Knowledge","214"=>"Word Knowledge","221"=>"Paragraph Comprehension");
//
//	$afqt_report=[];
//
//	$data['afqt_term']=getQuery("report","*",array("cat_id"=>1000,"user_id"=>$_SESSION['user_id']),'term desc',5,"","term");
//
//	foreach($data['afqt_term'] as $d) 
//	{
//		
//		
//		$total_correct=getFldValueById("report","sum(correct)",array("user_id"=>$_SESSION['user_id'],"cat_id"=>1000,"term"=>$d['term']));
//		$total_score=getFldValueById("report","sum(score)",array("user_id"=>$_SESSION['user_id'],"cat_id"=>1000,"term"=>$d['term']));
//		
//		$total_wrong=59-$total_correct;
//		
//		if($total_score>270)
//	    {
//			$score=99;
//		}
//		else
//		{
//			$score=getFldValueById("score_master","score",array("total"=>$total_score));
//		
//		}
//		$afqt_report[]=array("term"=>$d['term'],"date"=>$d['date'],"correct"=>$total_correct,"total"=>59,"wrong"=>$total_wrong,"raw_score"=>$total_score,"score"=>$score);
//		
//	}	
//
//	$data['afqt_report']=$afqt_report;
// 
//}
 
unset($configs['includes']);	
echo template($data);
?>