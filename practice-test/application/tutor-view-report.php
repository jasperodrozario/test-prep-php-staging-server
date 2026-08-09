<?php
include("../configs/autoload.php");
chklogin(str_replace("/practice-test/","",$_SERVER['REQUEST_URI']));
if($_SESSION['status']=="N")
{
	header("location:" . $configs['vpath'] ."renew-subscription");
	exit();
} 
 
$data['option_no']=5;

$_SESSION['test_id']=getFldValueById("subscription", "course_id", array("user_id"=>$_GET['student_id']));

if(!$_GET['cat_id'] && $_GET['result']!='flashcards')
{
    $data['categories'] = [];
	
    if (strpos($_GET['cat_name'], 'prediction') == false) {
        
        $category_list=getQuery("category","*",array("course_id"=>$_SESSION['test_id'], "online"=>"Y"),"category_order");
        foreach($category_list as $p){
            $data['categories'][]=["cat_id"=>$p['cat_id'], "category_name"=>$p['category_name']];
        }

    } else {

        $pred_test_list=getQuery("predic_test","*",array("course_id"=>$_SESSION['test_id'], "online"=>"Y"),"test_order");
        foreach($pred_test_list as $p){
            $data['categories'][]=["cat_id"=>$p['Predic_test_id'], "category_name"=>$p['test_name'], "pred_test"=>1];
        }

    }
}
if($_GET['cat_id'])
{
    if($_GET['result']=="prediction-test"){
        $data['db_value']=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id']));
    }else{
        if (strpos($_GET['cat_name'], 'prediction') == true) {
            $data['db_value']=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id']));
            $data['db_value']['category_name'] = $data['db_value']['test_name'];
            
        }
        else{
            $data['db_value']=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));
            $data['lesson']=getQuery("lesson","*",array("cat_id"=>$_GET['cat_id'],"online"=>"Y"));
            $data['lesson_total']=getTotalRow("lesson",array("cat_id"=>$_GET['cat_id'],"online"=>"Y"));
        }	
    }
}

if($_GET['cat_id']==4444){
    $data['prediction']=getQuery("predic_test","*",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"));
    $data['prediction_total']=getTotalRow("predic_test",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"));
}

switch($_GET['result'])
{
		case "result":
	      
            $exits=getTotalRow("result",array("user_id"=>$_GET['student_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>(int)$_GET['lesson_id'], "score"=>-1));
            
            $result_id = $_GET['result_id'];
        
            $data['date']=date('Y-m-d H:i:s');
		    
            $data['lesson_name']=getFldValueById("lesson","Lesson_name",array("lesson_id"=>$_GET['lesson_id']));
			    
            $data['total']=getTotalRow("exam",array("user_id"=>$_GET['student_id'], "cat_id"=>$_GET['cat_id'],"lesson_id"=>(int)$_GET['lesson_id'],"result_id"=>$result_id));
                
            $data['total_correct']=getTotalRow("exam",array("user_id"=>$_GET['student_id'], "cat_id"=>$_GET['cat_id'],"lesson_id"=>(int)$_GET['lesson_id'],"result_id"=>$result_id, "iscorrect"=>1));
                
            $data['total_wrong']=getTotalRow("exam",array("user_id"=>$_GET['student_id'], "cat_id"=>$_GET['cat_id'],"lesson_id"=>(int)$_GET['lesson_id'],"result_id"=>$result_id, "iscorrect"=>0));
			    
            $data['total_answer_given']=$data['total_correct']+$data['total_wrong'];
			     
            $data['total_answer_not_given']=$data['total']-$data['total_answer_given'];
			    
            $data['score']=round(($data['total_correct']/ $data['total'])*100);
		   
		    if($exits){
		    	
                updateTable("result",array("correct_answers"=>$data['total_correct'], "score"=>$data['score'],"result_date"=>date('Y-m-d H:i:s')),array("user_id"=>$_GET['student_id'], "cat_id"=>$_GET['cat_id'], "lesson_id"=>(int)$_GET['lesson_id'],"result_id"=>$result_id));
                updateTable("exam",array("is_result"=>1),array("user_id"=>$_GET['student_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>(int)$_GET['lesson_id'], "result_id"=>$result_id));
		    }    
		    $data['db_value']=getDataByWhere("result","*",array("user_id"=>$_GET['student_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>(int)$_GET['lesson_id'], "result_id"=>$result_id));        
		    $data['db_value']['lesson_name']=getFldValueById("lesson","Lesson_name",array("lesson_id"=>$data['db_value']['lesson_id']));
            $data['db_value']['cat_name']=getFldValueById("category","category_name",array("cat_id"=>$_GET['cat_id']));
            
            $has_pt_questions = getTotalRow("lesson_questions",array("cat_id"=>$_GET['cat_id'],"lesson_id"=>(int)$_GET['lesson_id'], "pt"=>1, "course_id"=>$_SESSION['test_id']));
            
//            if($has_pt_questions){
//                $pt_hw = 'pt';
//            }
//            else{
//                $pt_hw = 'HW';
//            }
        
			$question_test=[];	
			$story=[];
		    if(in_array($_GET['cat_id'],$data['story_question']))
		    {
                $data['story_list']=getQueryByJoin(array("question as q","exam as e"),"q.story_id",array("e.lesson_id"=>$_GET['lesson_id'],"e.cat_id"=>$_GET['cat_id'],"e.user_id"=>$_GET['student_id'],"e.result_id"=>$result_id),array("e.qid"=>"q.qid"),"e.qid","","","q.story_id");
	            
	            foreach($data['story_list'] as $a)
				{
                    $story[$a['story_id']]=getFldValueById("story","story",array("story_id"=>$a['story_id']));
				    	
					$question_test[$a['story_id']]=getQueryByJoin(array("question as q","lesson_questions as l","exam as e"),"q.*,l.*,e.*",array("l.lesson_id"=>$_GET['lesson_id'],"l.cat_id"=>$_GET['cat_id'],"l.course_id"=>$_SESSION['test_id'],"e.user_id"=>$_GET['student_id'],"e.result_id"=>$result_id,"q.story_id"=>$a['story_id']),array("q.qid"=>"l.qid","l.qid"=>"e.qid"),"e.id",'','',"q.qid");
		        }
                $data['story']=$story; 
				$data['question_test']=$question_test;
		    }
		    else
		    {
		    	$data['question_test']=getQueryByJoin(array("question as q","lesson_questions as l","exam as e"),"q.*,l.*,e.*",array("l.lesson_id"=>$_GET['lesson_id'],"l.cat_id"=>$_GET['cat_id'],"l.course_id"=>$_SESSION['test_id'],"e.result_id"=>$result_id), array("q.qid"=>"l.qid","l.qid"=>"e.qid"),'l.order_no','','','l.qid');
			}
	  		
		break;
		
		case "view":
		 
		    $data['category']=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id'])); 
            $data['date']=getFldValueById("result","result_date",array("result_id"=>$_GET['result_id']));
            $pred_set_id=getFldValueById("result","pred_set_id",array("result_id"=>$_GET['result_id']));
			$num_category=(int)getTotalRow("prediction_test_category",array("pred_test_id"=>$_GET['cat_id']));
            $pred_cat_list = getQuery("prediction_test_category","*",array("pred_test_id"=>$_GET['cat_id']),"","","","id");

            if($_SESSION['test_id']==27 && $_GET['cat_id']==23){ // Full ASVAB Score
                $data['full_asvab_result_list'] = getQuery("result","*",array("user_id"=>$_GET['student_id'],"Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$_GET['cat_id'], "pred_name!"=>"Full ASVAB", "parent_id"=>$_GET['result_id']),"result_id desc");
            }
        
            $pred_test_report=[];
			$pred_cat_name=[];
			$total_array=[];
			$i=0;
			foreach($pred_cat_list as $p)
			{
                if(!$i && !$_GET['lesson_id']){
                    $_GET['lesson_id']=$p['cat_id']; 
                }
                $i++;
				$cat=getDataByWhere("category","*",array("cat_id"=>$p['cat_id']));
                $cat_id = $p['cat_id'];
				$cat_name=$cat['category_name'];
                $total_array[$cat['cat_id']]=$p['number_of_questions'];
                $pred_cat_name[]=["cat_id"=>$cat_id,"cat_name"=>$cat_name];

                $tmp_score = getQuery("result","*",array("user_id"=>$_GET['student_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id, "pred_set_id"=>$pred_set_id, "score!"=>-1, "parent_id"=>$_GET['result_id']), "result_id desc", 1);
                foreach($tmp_score as $t){
                    $pred_test_report[$cat_name] = $t;
                }
            }
			$data['pred_cat_name']=$pred_cat_name;
			$data['pred_test_report']=$pred_test_report;
             $total=getFldValueById("result","total_questions",array("user_id"=>$_GET['student_id'],"Predic_test_id"=>$_GET['cat_id'], "result_id"=>$_GET['result_id'], "pred_set_id"=>$pred_set_id));
			$total_correct=getFldValueById("result","correct_answers",array("user_id"=>$_GET['student_id'],"Predic_test_id"=>$_GET['cat_id'], "result_id"=>$_GET['result_id'], "pred_set_id"=>$pred_set_id));
			$data['pred_score']=getFldValueById("result","score",array("user_id"=>$_GET['student_id'],"Predic_test_id"=>$_GET['cat_id'], "result_id"=>$_GET['result_id'], "pred_set_id"=>$pred_set_id));
			
			$total_wrong=$total-$total_correct;
			
            $my_answers=array();
	        $answers=array();
            $story=array();
            $questions=[];
            $story_list=[];
		       
			$cat_id=$_GET['lesson_id'];
		
			$data['cat_name']=getFldValueByid("category","category_name",array("cat_id"=>$cat_id));
		
			$data['cat_id']=$cat_id;

			$data['option_no']=5;
			
			if(in_array($cat_id, $data['story_question']))
			{
                $cat_result_id = getFldValueByid("result","result_id",array("parent_id"=>$_GET['result_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id));	

                $story_list[$cat_id]=getQueryByJoin(array("question as q","exam as e"),"q.story_id",array("e.cat_id"=>(int)$cat_id,"e.Predic_test_id"=>$_GET['cat_id'],"e.user_id"=>$_GET['student_id'], "e.pred_set_id"=>$pred_set_id, "e.result_id"=>$cat_result_id),array("e.qid"=>"q.qid"),"e.id","","","q.story_id");
				
				foreach($story_list[$cat_id] as $a)
				{
                    $story[$a['story_id']]=getFldValueById("story","story",array("story_id"=>$a['story_id']));
					
					$data['question_test'][$a['story_id']]=getQueryByJoin(array("question as q","predic_questions as p"), "q.*,p.*", array("p.cat_id"=>(int)$cat_id, "p.predic_test_id"=>$_GET['cat_id'], "p.course_id"=>$_SESSION['test_id'], "q.story_id"=>$a['story_id']), array("q.qid"=>"p.qid"), 'q.qid', '', '', "q.qid");
					
					foreach($data['question_test'][$a['story_id']] as $d)
					{
                        $user_ans=(int)getFldValueById("exam","user_ans",array("cat_id"=>(int)$cat_id,"Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_GET['student_id'], "pred_set_id"=>$pred_set_id, "qid"=>$d['qid']));
                                
                        if($user_ans){
                            $my_answers[$d['qid']] = $user_ans;
                        }
					}
				}
			}
			else
			{	
                $cat_result_id = getFldValueByid("result","result_id",array("parent_id"=>$_GET['result_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id));	

                $questions[$cat_id]=getQueryByJoin(array("question as q", "exam as e"), "q.*, e.*", array("e.cat_id"=>$cat_id,"e.user_id"=>$_GET['student_id'],"e.Predic_test_id"=>$_GET['cat_id'], "e.pred_set_id"=>$pred_set_id), array("e.qid"=>"q.qid"), "e.id", $total_array[$cat_id]);
                
				foreach($questions[$cat_id] as $d)
				{
                    $my_answers[$d['qid']]=$d['user_ans'];
				}
			}	
		    
		    $data['story_list']=$story_list;
		    $data['questions']=$questions;
	        $data['story']=$story;
			$data['my_answers']=$my_answers; 
			
		break;
	 
		case "score-chart":
		
		   $data['lesson_name']=getFldValueById("lesson","Lesson_name",array("lesson_id"=>$_GET['lesson_id']));
		   
		break; 
		 
		case "progress-report":
		    
            $_SESSION['test_id']=getFldValueById("subscription","course_id",array("user_id"=>$_GET['student_id']));
        
		    $result=[];
            $lesson_list=[];
            $result_total=[];
		    
            if(!$_GET['cat_id'])
		    {
		    	$_GET['cat_id']=2000;
		    }	
		     
		    switch($_GET['cat_id']) 
		    {
		        case "2000":
			 
					$_GET['cat_id']="2000";
				
					$cats=getQuery("category","*",array("course_id"=>$_SESSION['test_id']),"category_order");
                    $data['category_list']=$cats;
				    
				    foreach($cats as $cat)
				    {
				        $report[]=getQuery("result","*",array("user_id"=>$_GET['student_id'],"cat_id"=>$cat['cat_id']),"result_id desc",5);
						 
		            }
                    
                    $data['pred_cat_list'] = getQuery("predic_test","*",array("course_id"=>$_SESSION['test_id'], "online"=>'Y'),"test_order");
                    
					$data['report']=$report;  
					
					$data['report_total']=$report_total; 
	  
					$data['cat_name']="Overall Report";
			    
			    break;	
                    
                default:
                    $cat=getDataByWhere("category","*",array("cat_id"=>$_GET['cat_id']));
                    $pred_cat=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id']));
                    $data['pred_cat_list'] = getQuery("predic_test","*",array("course_id"=>$_SESSION['test_id'], "online"=>'Y'),"test_order");
                    
                    if($pred_cat){
                        $cat=getDataByWhere("predic_test","*",array("Predic_test_id"=>$_GET['cat_id']));
                        $data['cat_name']=$cat['test_name'];
                        $pred_cat_list = getQuery("prediction_test_category","*",array("pred_test_id"=>$_GET['cat_id']),"","","","id");

                        $pred_cats=[];
                        $total=0;
                        $total_cat=0;

                        if($_SESSION['test_id'] == 27 && $_GET['cat_id']==23){
                            $total_results=getTotalRow("result",array("cat_id"=>$_GET['cat_id'],"Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_GET['student_id'], "score!"=>-1, "pred_name"=>"Full ASVAB"));
                        }
                        else{
                            foreach($pred_cat_list as $p){
                                $category=getDataByWhere("category","*",array("cat_id"=>$p['cat_id']));
                                $pred_cats[]=[$category['cat_id']=>$category['cat_name']];
                                $total=$total+$p['number_of_questions'];
                                $total_cat++;
                            }
                            $data['pred_cats']=$pred_cats;
                            $total_results=getTotalRow("result",array("cat_id"=>$_GET['cat_id'],"Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_GET['student_id']));
                        }

                        if($total_results>5)
                        {
                            $limit=($total_results-5) . ",5";
                        }
                        else
                        {
                            $limit=5;
                        }

                        if($_SESSION['test_id'] == 27 && $_GET['cat_id']==23){
                            $data['result']=getQuery("result","*",array("cat_id"=>$_GET['cat_id'],"Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_GET['student_id'],"score!"=>-1,"pred_name"=>"Full ASVAB"),"result_id",$limit);
                        }else{
                            $data['result']=getQuery("result","*",array("cat_id"=>$_GET['cat_id'],"Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_GET['student_id'],"score!"=>-1),"result_id",$limit);
                        }

                        $data['pred_result_total']=$total_results;
                    } 

                    else if($cat){

                        $data['lesson_list']=getQuery("lesson","*",array("course_id"=>$_SESSION['test_id'], "cat_id"=>$_GET['cat_id'],"online"=>"Y"),"lesson_order");
                        $data['lesson_total']=getTotalRow("lesson",array("course_id"=>$_SESSION['test_id'],"cat_id"=>$_GET['cat_id'],"online"=>"Y"));
                        
                        if($data['lesson_total'])
                        {
                            foreach ($data['lesson_list'] as $l)
                            {
                                $result[$l['lesson_id']]=getQuery("result","*",array("user_id"=>$_GET['student_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>$l['lesson_id'],"score!"=>-1),"result_id desc",5);
                                $result_total[$l['lesson_id']]=getTotalRow("result",array("user_id"=>$_GET['student_id'],"cat_id"=>$_GET['cat_id'],"lesson_id"=>$l['lesson_id']));
                            }
                        }

                        $data['result']=$result;  

                        $data['result_total']=$result_total; 

                        $data['cat_name']=getFldValueById("category","cat_name",array("cat_id"=>$_GET['cat_id']));
                    }
		    } 
		 
		break;
        
		case "flashcards": 
		    
		    $data['total']=getTotalRowsByJoin(array("question as q","lesson_questions as l"),array("l.cat_id"=>$_GET['flashcard_id'], "l.course_id"=>$_SESSION['test_id']),array("q.qid"=>"l.qid"));
			if($data['total'])
			{
				$data['questions']=getQueryByJoin(array("question as q","lesson_questions as l"),"q.*",array("l.cat_id"=>$_GET['flashcard_id'], "l.course_id"=>$_SESSION['test_id']),array("q.qid"=>"l.qid"),"rand()");
		    }
	 
		break;
		 
	    case "prediction-result":
			$total_questions = 0;
            $total_correct_ans = 0;
            $total_cat_score = 0;
            $num_category=(int)getTotalRow("prediction_test_category",array("pred_test_id"=>$_GET['cat_id']));
            $pred_cat_list = getQuery("prediction_test_category","*",array("pred_test_id"=>$_GET['cat_id']),"","","","id");
            
            foreach($pred_cat_list as $p){
                $cat=getDataByWhere("category","*",array("cat_id"=>$p['cat_id']));
                $cat_id = $p['cat_id'];
                $cat_score=0; 
				$num_correct_ans=getTotalRow("exam",array("iscorrect"=>1,"user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$_GET['cat_id'],"cat_id"=>$cat_id, "is_result"=>0));
                
                if($num_correct_ans)
			    {
			    	$correct_ans_percentage=round((1/$p['number_of_questions'])*$num_correct_ans*100);
			    	$cat_score=round(20+$correct_ans_percentage*0.55);
			    }
                $total_cat_score += $cat_score;
                $num_wrong_ans=$p['number_of_questions']-$num_correct_ans;
                $total_questions += $p['number_of_questions'];
                $total_correct_ans += $num_correct_ans;
                
                $pred_set_id = (int)getFldValueById("result","pred_set_id",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$_GET['cat_id'],"cat_id"=>$cat_id,"score"=>-1));
                
                $result_id = getFldValueById("result","result_id",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$_GET['cat_id'],"cat_id"=>$cat_id,"score"=>-1));
                
                updateTable("result",["score"=>$cat_score, "correct_answers"=>$num_correct_ans],['user_id'=>$_SESSION['user_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id, "result_id"=>$result_id]);
            }
            $data['pred_cat_list']=$pred_cat_list;
        
			$reslt_id =  insertTable("result",array("user_id"=>$_SESSION['user_id'],"Predic_test_id"=>$_GET['cat_id'],"cat_id"=>$_GET['cat_id'],"score"=>round($total_cat_score/$num_category),"result_date"=>date("Y-m-d"), "pred_set_id"=>$pred_set_id, "total_questions"=>$total_questions, "correct_answers"=>$total_correct_ans));
            
            updateTable("exam",['is_result'=>1],['user_id'=>$_SESSION['user_id'],"Predic_test_id"=>$_GET['cat_id'],"pred_set_id"=>$pred_set_id]);
		    
			header("location:".$configs['vpath']."exam/view/" . $_GET['cat_id'] ."-". seo_url($_GET['cat_name']) ."/0/".$reslt_id);
			exit;
		break;
		
		case"prediction-test":
        
            if($_GET['action']=="freshtest")
			{
                $result_id=(int)getFldValueById("exam","result_id",array("Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"is_result"=>0));
                 deleteData("exam",array("Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"is_result"=>0));
                 deleteData("result",array("Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'],"result_id"=>$result_id));
			}
            
			$question_test=[];
			$question_list=[];
            $prediction_cat=[];
			$story=array();
		  	$total=0;
            $i=0;
            $completed_total=0;
            $flag=1;
            
            $pred_cat_list = getQuery("prediction_test_category","*",array("pred_test_id"=>$_GET['cat_id']),"","","","id");
        
            foreach($pred_cat_list as $p){
                $total+=$p['number_of_questions'];  
				$cat=getDataByWhere("category","*",array("cat_id"=>$p['cat_id']));
                
                if(!$i && !$_GET['child_cat_id']){
                    $_GET['child_cat_id']=$cat['cat_id']; 
                    $flag=0;
                }
                
                $completed=(int)getTotalRow("exam",['Predic_test_id'=>$_GET['cat_id'],'user_id'=>$_SESSION['user_id'],'cat_id'=>$cat['cat_id'],'user_ans>'=>0, 'is_result'=>0]);
                
                if($completed>$p['number_of_questions']){
                    $completed=$p['number_of_questions'];
                }
                $completed_total+=$completed;
                
//                if(in_array($cat['cat_id'],$data['story_question']))
//                {
//                    $p['number_of_questions'] = 24;
//                }
                
                $prediction_cat[$cat['cat_id']]=["cat_name"=>$cat['category_name'],"shortcode"=>$cat["shortcode"],"cat_id"=>$cat['cat_id'],"total"=>$p['number_of_questions'],"completed"=>$completed];
				
                $i++;
            }
            
			$data['completed_total']=$completed_total;
            $data['total']=$total;
		    $data['prediction_cat']=$prediction_cat;
			
            $data['alert']="";
			if($completed_total==$total)
			{
//                $cat_name = getFldValueById("predic_test", "test_name", array("Predic_test_id"=>$_GET['cat_id'], "course_id"=>$_SESSION['test_id']));
                
				$data['alert_total']="<a  class=\"btn btn-success text-center\" href=\"" . $configs['vpath']."exam/prediction-result/".$_GET['cat_id'] ."-". seo_url($_GET['cat_name']) ."\">Submit to View Prediction Result</a>";
			}
			  
			if($completed_total && $completed_total<$total && !$flag && $_GET['action']!='continue')
			{
				$data['alert']="<center><h4>You already answered " .$data["completed_total"] ." question(s) from this Prediction Test. Still pending ".($total-$completed_total) . " questions.</h4><br><a href='" . $configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" . $_GET['cat_name']."/continue' class='btn btn-success'>Continue ?</a> or <a href='" . $configs['vpath'] . "exam/prediction-test/" . $_GET['cat_id'] . "-" . $_GET['cat_name'] . "/freshtest' class='btn btn-warning'>Start Over?</a></center>";
				 
			}
			else
			{		 
				$data['db_child_category']=getDataByWhere("category","*",["cat_id"=>$_GET['child_cat_id']]);
	            $cat_id=$_GET['child_cat_id'];  
				$limit=$prediction_cat[$_GET['child_cat_id']]["total"];
				$lessons_text=[];
				$set_id=0;
				if($cat_id==-87 || $cat_id==-80)
				{
					$data['option_no']=4;
				}
				else
				{
					$data['option_no']=5;
				}
                
				if(in_array($cat_id,$data['story_question']))
				{
                    if(in_array($cat_id, array(84,63,65))){
                        $limit=(int)$limit/5;
                    }else{
                        $limit=(int)$limit/4;
                    }
					
					$story_cat_limit=5;					
					$tot=0;
                    $story_list_array=[];
					$story_list=[];
				    
                    $exist_prev_question = getTotalRow("exam",array("cat_id"=>$cat_id,"user_id"=>$_SESSION['user_id'],"Predic_test_id"=>(int)$_GET['cat_id'],"is_result"=>0));
                    
					if((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed']>0) || $exist_prev_question)
					{
                        $story_list_array=getQueryByJoin(array("question as q","exam as e"),"q.story_id",array("e.cat_id"=>(int)$cat_id,"e.Predic_test_id"=>$_GET['cat_id'],"e.user_id"=>$_SESSION['user_id'], "e.is_result"=>0),array("e.qid"=>"q.qid"),"e.id","","","q.story_id");
					}
					else
					{
                        $attempted_set_rows = getQuery("exam","*",array("user_id"=>$_SESSION['user_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id, "is_result"=>1), "", "", "", "pred_set_id");
                        $attempted_set_list = array();
                        foreach($attempted_set_rows as $r){
                            $attempted_set_list[] = $r['pred_set_id'];
                        }
                        
                        $set_rows = getQuery("predic_questions","*",array("course_id"=>$_SESSION['test_id'], "predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id), "", "", "", "set_id");
                        $set_list = array();
                        foreach($set_rows as $r){
                            if(!in_array($r['set_id'], $attempted_set_list)){
                                $set_list[] = $r['set_id'];    
                            }
                        }
                        if(empty($set_list)){
                            $set_id = $attempted_set_list[array_rand($attempted_set_list)];
                        }
                        else{
                            $set_id = $set_list[array_rand($set_list)];
                        }
                        
                        if($set_id){
                            $story_list_array=getQueryByJoin(array("question as q", "predic_questions as p"),"q.story_id",array("p.cat_id"=>(int)$cat_id,"p.set_id"=>$set_id,"q.story_id >"=>0,"p.predic_test_id"=>$_GET['cat_id'],"p.course_id"=>$_SESSION['test_id']),array("q.qid"=>"p.qid"),"","","","q.story_id");
                        }
                        else{
                            $story_total = (int)$limit;
                            $story_list_array=getQueryByJoin(array("question as q","lesson_questions as l"),"q.story_id",array("l.cat_id"=>(int)$cat_id,"l.course_id"=>$_SESSION['test_id']),array("q.qid"=>"l.qid"),"rand()","","","q.story_id");
                            
//                            $story_list_array=getQueryByJoin(array("question as q", "predic_questions as p"),"q.story_id",array("p.cat_id"=>(int)$cat_id,"p.set_id"=>$set_id,"q.story_id >"=>0,"p.predic_test_id"=>$_GET['cat_id'],"p.course_id"=>$_SESSION['test_id']),array("q.qid"=>"p.qid"),"","","","q.story_id");    
                        }
					}

//					$data['story_list'][$cat_id]=$story_list_array;
                    $data['story_list'][$cat_id]=[];
                    
 					$result_id=(int)getFldValueById("exam","result_id",array("cat_id"=>$cat_id,"user_id"=>$_SESSION['user_id'],"Predic_test_id"=>(int)$_GET['cat_id'],"is_result"=>0));
            
                    $result_exist = getFldValueById("result","result_id",array("user_id"=>$_SESSION['user_id'],"cat_id"=>$cat_id,"Predic_test_id"=>(int)$_GET['cat_id'], "result_id"=>$result_id));
                    
//                    echo "result: " . $result_exist;
                    
                    if(!$set_id){
                        $set_id=0;
                    }
                    
                    if(!$result_exist){
                        $result_id = insertTable("result",["user_id"=>$_SESSION['user_id'], "cat_id"=>$cat_id, "Predic_test_id"=>(int)$_GET['cat_id'], "total_questions"=>$prediction_cat[$cat_id]['total'], "result_date"=>date('Y-m-d H:i:s'),"score"=>-1, "pred_set_id"=>$set_id]);
                    }
                    
                    $total_story_ques=$prediction_cat[$_GET['child_cat_id']]["total"];
                    
					foreach($story_list_array as $a)
					{
                        if($total_story_ques<=0){
                            break;
                        }
//                        echo "<h2>" . $a['story_id'] . "</h2>";
                        $data['story_list'][$cat_id][]=$a;
                        
                        $story[$a['story_id']]=getFldValueById("story","story",array("story_id"=>$a['story_id']));
                        
                        if($set_id){
                            $question_list[$a['story_id']]=getQueryByJoin(array("question as q","predic_questions as p"),"q.*,p.*",array("p.cat_id"=>(int)$cat_id,"p.predic_test_id"=>$_GET['cat_id'],"p.course_id"=>$_SESSION['test_id'],"q.story_id"=>$a['story_id']),array("q.qid"=>"p.qid"),'q.qid','','',"q.qid");
                        }else{
//                            echo " story id: ". $a['story_id'];
                            $question_list[$a['story_id']]=getQueryByJoin(array("question as q","lesson_questions as l"),"q.*,l.*",array("l.cat_id"=>(int)$cat_id,"l.course_id"=>$_SESSION['test_id'],"q.story_id"=>$a['story_id']),array("q.qid"=>"l.qid"),"q.qid",$total_story_ques,'',"l.qid");
                            
                            $n_question = getTotalRowsByJoin(array("question as q","lesson_questions as l"),array("l.cat_id"=>(int)$cat_id,"l.course_id"=>$_SESSION['test_id'],"q.story_id"=>$a['story_id']),array("q.qid"=>"l.qid"));
                            
                            $total_story_ques-=$n_question;
                        }
                        
						foreach($question_list[$a['story_id']] as $d)
						{
							if(!$prediction_cat[$cat_id]['completed'] && !$exist_prev_question)
                            { 
                                insertTable("exam",["user_id"=>$_SESSION['user_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id, "result_id"=>$result_id, "qid"=>$d['qid'], "exam_date"=>date('Y-m-d H:i:s'),"is_result"=>0, "pred_set_id"=>$set_id]);
                            }
                            
							if((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed']>0) || $exist_prev_question)
							{
								$user_ans=(int)getFldValueById("exam","user_ans",array("cat_id"=>(int)$cat_id,"Predic_test_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id'], "is_result"=>0, "qid"=>$d['qid']));
                                
                                if($user_ans){
                                    $user_answers[$d['qid']] = $user_ans;
                                }
			 			
							}
		
						}
						
					}
				    $data['user_answers'] = $user_answers;
					$data['story']=$story;
					$data['question_list']=$question_list;
					
				}				  	 	
				else
				{
                    $exist_prev_question = getTotalRow("exam",array("cat_id"=>$cat_id,"user_id"=>$_SESSION['user_id'],"Predic_test_id"=>(int)$_GET['cat_id'],"is_result"=>0));
                    
					if((isset($prediction_cat[$cat_id]['completed']) && $prediction_cat[$cat_id]['completed']>0) || $exist_prev_question)
					{
//                        $set_id = (int)getFldValueById("exam", "pred_set_id", array("cat_id"=>$cat_id, "user_id"=>$_SESSION['user_id'], "Predic_test_id"=>(int)$_GET['cat_id'], "is_result"=>0));
						
                        $question_test[$cat_id]=getQueryByJoin(array("question as q", "exam as e"),"q.*, e.*",array("e.cat_id"=>$cat_id,"e.user_id"=>$_SESSION['user_id'],"e.Predic_test_id"=>$_GET['cat_id'],"e.is_result"=>0),array("e.qid"=>"q.qid"),"e.id",$limit);
					}
					else
					{
                        $attempted_set_rows = getQuery("exam","*",array("user_id"=>$_SESSION['user_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id, "is_result"=>1), "", "", "", "pred_set_id");
                        $attempted_set_list = array();
                        foreach($attempted_set_rows as $r){
                            $attempted_set_list[] = $r['pred_set_id'];
                        }
                        
                        $set_rows = getQuery("predic_questions","*",array("course_id"=>$_SESSION['test_id'], "predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id), "", "", "", "set_id");
                        $set_list = array();
                        foreach($set_rows as $r){
                            if(!in_array($r['set_id'], $attempted_set_list)){
                                $set_list[] = $r['set_id'];    
                            }
                        }
                        if(empty($set_list)){
                            $set_id = $attempted_set_list[array_rand($attempted_set_list)];
                        }
                        else{
                            $set_id = $set_list[array_rand($set_list)];
                        }
                        
                        if($set_id){
                            $question_test[$cat_id]=getQueryByJoin(array("predic_questions as p", "question as q"),"q.*,p.*",array("p.set_id"=>$set_id, "p.course_id"=>$_SESSION['test_id'], "p.predic_test_id"=>$_GET['cat_id'], "p.cat_id"=>$cat_id), array("p.qid"=>"q.qid"),"rand()",$limit);
                        }
                        else{
                            $question_test[$cat_id]=getQueryByJoin(array("question as q","lesson_questions as l"),"q.*,l.*",array("l.cat_id"=>(int)$cat_id,"l.course_id"=>$_SESSION['test_id']),array("q.qid"=>"l.qid"),"rand()",$limit,"","q.qid");
                                
//                            getQueryByJoin(array("predic_questions as p", "question as q"),"q.*,p.*",array("p.set_id"=>$set_id, "p.course_id"=>$_SESSION['test_id'], "p.predic_test_id"=>$_GET['cat_id'], "p.cat_id"=>$cat_id), array("p.qid"=>"q.qid"),"rand()",$limit);
                        }
					}
				}
				if(!in_array($cat_id,$data['story_question']))	
				{
                    if(!$set_id){
                        $set_id=0;
                    }
                    
					$result_id=(int)getFldValueById("exam","result_id",array("cat_id"=>$cat_id,"user_id"=>$_SESSION['user_id'],"Predic_test_id"=>(int)$_GET['cat_id'],"is_result"=>0));
            
                    $result_exist = getFldValueById("result","result_id",array("user_id"=>$_SESSION['user_id'],"cat_id"=>$cat_id,"Predic_test_id"=>(int)$_GET['cat_id'], "result_id"=>$result_id));
            
                    if(!$result_exist){
                        $result_id = insertTable("result",["user_id"=>$_SESSION['user_id'], "cat_id"=>$cat_id, "Predic_test_id"=>(int)$_GET['cat_id'], "total_questions"=>$prediction_cat[$cat_id]['total'], "result_date"=>date('Y-m-d H:i:s'),"score"=>-1, "pred_set_id"=>$set_id]);
                    }
                    
                    $exist_prev_ques = getTotalRow("exam",array("cat_id"=>$cat_id,"user_id"=>$_SESSION['user_id'],"Predic_test_id"=>(int)$_GET['cat_id'],"is_result"=>0));
                    
				 	foreach($question_test[$cat_id] as $d)
					{	
						if(!$prediction_cat[$cat_id]['completed'] && !$exist_prev_ques)
						{
//                            echo "insert code: ".$prediction_cat[$cat_id]['completed'];
                            
                            insertTable("exam",["user_id"=>$_SESSION['user_id'], "Predic_test_id"=>$_GET['cat_id'], "cat_id"=>$cat_id, "result_id"=>$result_id, "qid"=>$d['qid'], "exam_date"=>date('Y-m-d H:i:s'),"is_result"=>0, "pred_set_id"=>$set_id]);
						}
					}
					$data['question_test']=$question_test;
				}
			}
			
		break;
	}

	
echo template($data);
?>