<?php
header('Content-Type: application/json');
include("../configs/config.php");
include("../configs/license.php");
include("../configs/common.php");
include("../configs/array.php");

function initials($str,$no="") {
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret.$no;
}
 
if(!$_GET['cat_id'])
{
	$_GET['cat_id']="2000";
}
$score_data=[];

$_SESSION['test_id']=getFldValueById("subscription","course_id",array("user_id"=>$_GET['student_id']));

switch($_GET['cat_id'])
{
	case "2000":
	    
        $cats=getQuery("category","*",array("course_id"=>$_SESSION['test_id']),"category_order");
        
        foreach($cats as $cat)
        {   
            $date="";
            $scores=getQuery("result","*",array("user_id"=>$_GET['student_id'],"cat_id"=>$cat['cat_id'],"lesson_id>"=>0,"score!"=>-1),"result_id desc",5);
            
            $j=0;
            $score=0;
            foreach($scores as $s)
	    	{
	    		$j++;
	    		$score=$score+$s['score'];
	    	}
	    	if(!is_nan(round($score/$j)))
	        {
                if(!$cat['shortcode']){
                    $cat['shortcode'] = $cat['category_name'];
                }
	    	  $score_data[]=array("date"=>$cat['shortcode'],"score"=>round($score/$j));
		    }  
        }
        
        $pred_cats=getQuery("predic_test","*",array("course_id"=>$_SESSION['test_id']),"test_order");
        
        foreach($pred_cats as $cat)
        {   
            $date="";
            $scores=getQuery("result","*",array("user_id"=>$_GET['student_id'],"Predic_test_id"=>$cat['Predic_test_id'],"cat_id"=>$cat['Predic_test_id'],"score!"=>-1),"result_id desc",5);
            
            $j=0;
            $score=0;
            foreach($scores as $s)
	    	{
	    		$j++;
	    		$score=$score+$s['score'];
	    	}
	    	if(!is_nan(round($score/$j)))
	        {
                if(!$cat['shortcode']){
                    $cat['shortcode'] = $cat['test_name'];
                }
	    	  $score_data[]=array("date"=>$cat['shortcode'],"score"=>round($score/$j));
		    }  
        }
        
        break;
        
    default:
        
        $cat=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));
        $pred_cat=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id']));
        
        if($pred_cat){
            $scores=getQuery("result","*",array("user_id"=>$_GET['student_id'],"Predic_test_id"=>$pred_cat['Predic_test_id'],"cat_id"=>$pred_cat['Predic_test_id'],"score!"=>-1),"result_id desc",5);

            foreach($scores as $s)
	    	{
	    		$score_data[]=array("date"=>$s['result_date'],"score"=>round($s['score']));
	    	}
        }
        else if($cat){
           
            $lesson_list=getQuery("lesson","*",array("course_id"=>$_SESSION['test_id'], "cat_id"=>$_GET['cat_id'],"online"=>"Y"),"lesson_order");
            $lesson_total=getTotalRow("lesson",array("course_id"=>$_SESSION['test_id'],"cat_id"=>$_GET['cat_id'],"online"=>"Y"));
                     
            if($lesson_total)
            {
                foreach ($lesson_list as $l)
                {
                   $sum_score = 0; 
                    $result=getQuery("result","*",array("user_id"=>$_GET['student_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>$l['lesson_id'],"score!"=>-1));
                    foreach ($result as $r)
                    {
                        $sum_score += $r['score'];
                    }   
                    $n_exam=getTotalRow("result",array("user_id"=>$_GET['student_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>$l['lesson_id'],"score!"=>-1));
                    
                    if($n_exam){
                        $score_data[]=array("date"=>$l['Lesson_name'],"score"=>round($sum_score/$n_exam));
                    }
                }
            }
        }
 
}

echo json_encode($score_data);
?>