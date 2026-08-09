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
	$_GET['cat_id'] = "2000";
}
$score_data=[];
switch($_GET['cat_id'])
{
	case "2000":
	    
        $cats=getQuery("category","*",array("course_id"=>$_SESSION['test_id']),"category_order");
        
        foreach($cats as $cat)
        {   
            $date="";
            $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"cat_id"=>$cat['cat_id'],"lesson_id>"=>0,"score!"=>-1),"result_id desc",5);
            
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

            if($_SESSION['test_id'] == 27 || $_SESSION['test_id'] == 40){
                if($cat['Predic_test_id']==23){
                    $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$cat['Predic_test_id'],"cat_id"=>$cat['Predic_test_id'],"score!"=>-1, "pred_name"=>"Full ASVAB"),"result_id desc",5);
                }else{
                    $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$cat['Predic_test_id'],"cat_id"=>$cat['Predic_test_id'],"score!"=>-1),"result_id desc",5);
                }
            }
            else if($_SESSION['test_id'] == 24){
                $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$cat['Predic_test_id'],"cat_id"=>$cat['Predic_test_id'],"score!"=>-1, "pred_name"=>"AFOQT Prediction test"),"result_id desc",5);
            }
            else{
                $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$cat['Predic_test_id'],"cat_id"=>$cat['Predic_test_id'],"score!"=>-1),"result_id desc",5);
            }

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

        $prediction_ids = array(15,16,17,18,19,20);
        
        if ($pred_cat && (strpos($_GET['cat_name'], 'prediction') == true || in_array($_GET['cat_id'], $prediction_ids))) {
            if(($_SESSION['test_id'] == 27 && $_GET['cat_id'] == 23) || ($_SESSION['test_id'] == 40 && $_GET['cat_id'] == 39)){
                $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$pred_cat['Predic_test_id'],"cat_id"=>$pred_cat['Predic_test_id'],"score!"=>-1, "pred_name"=>"Full ASVAB"),"result_id desc",5);
            }
            else if($_SESSION['test_id'] == 24 && $_GET['cat_id'] == 40){
                $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$pred_cat['Predic_test_id'],"cat_id"=>$pred_cat['Predic_test_id'],"score!"=>-1, "pred_name"=>"AFOQT Prediction test"),"result_id desc",5);
            }
            else{
                $scores=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$pred_cat['Predic_test_id'],"cat_id"=>$pred_cat['Predic_test_id'],"score!"=>-1),"result_id desc",5);
            }

            foreach($scores as $s)
	    	{
                if($s['score'])
	            {
	    		    $score_data[]=array("date"=>$s['result_date'],"score"=>round($s['score']), "result_id"=>$s['result_id']);
	    	    }
            }
        }
        else{
           
            $lesson_list=getQuery("lesson","*",array("course_id"=>$_SESSION['test_id'], "cat_id"=>$_GET['cat_id'],"online"=>"Y"),"lesson_order");
            $lesson_total=getTotalRow("lesson",array("course_id"=>$_SESSION['test_id'],"cat_id"=>$_GET['cat_id'],"online"=>"Y"));
                     
            if($lesson_total)
            {
                foreach ($lesson_list as $l)
                {
                   $sum_score = 0; 
                   $cnt_exam = 0;
                    $result=getQuery("result","*",array("user_id"=>$_SESSION['user_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>$l['lesson_id'],"score!"=>-1));
                    foreach ($result as $r)
                    {
                        $sum_score += (int)$r['score'];
                        $cnt_exam++;
                    }   
                    
                    if($cnt_exam){
                        // $score_data[]=array("date"=>$l['Lesson_name'],"score"=>round($sum_score/$n_exam));
                        if($l['shortcode']){
                            $label = $l['shortcode'];
                        }else{
                            $label = $l['Lesson_name'];
                        }
                        if($sum_score)
	                    {
                            $score_data[]=array("date"=>$label, "score"=>round($sum_score/$cnt_exam));
                        }
                    }
                }
            }
        }
 
}

echo json_encode($score_data);
?>