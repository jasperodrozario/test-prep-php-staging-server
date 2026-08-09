<?php
header('Content-Type: application/json');
include("../configs/config.php");
include("../configs/license.php");
include("../configs/common.php");

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
switch($_GET['cat_id'])
{
	case "2000":
	    
	    $category=getQueryByJoin(array("category as c","test_category as t"),"c.*",array("t.id"=>$_SESSION['test_id'],"c.cat_id !"=>71),array("c.cat_id"=>"t.cat_id"),"c.ord");
		
	    $k=0;
	    $score1=0;
	    foreach($category as $c)
	    {
	    	
  		    $label=initials(substr($c['cat_name'],0,-2),substr($c['cat_name'],-2,2));
			$label=initials($c['cat_name']);
						  		   	
	    	$j=0;$score1=0;
	    	$scrores=getQuery("report","score",array("cat_id"=>$c['cat_id'],"user_id"=>$_SESSION['user_id']),"id desc",5);
	    	foreach($scrores as $s)
	    	{
	    		$j++;
	    		$score1=$score1+$s['score'];
	    	}
	    	if(!is_nan(round($score1/$j,2)))
	        {
	    	  $score_data[]=array("date"=>$label,"score"=>round($score1/$j,2));
		    }  
	    }	
	    
		break;
		case "1000":
		
		    $total=getTotalRow("report",array("cat_id"=>1000,"user_id"=>$_SESSION['user_id']),'','term');
		    if($total>5)
		    {
		    	$limit=($total-5) . ",5";
		    }
		    else
		    {
		    	$limit=5;
		    }		

			$data['afqt_term']=getQuery("report","term",array("cat_id"=>1000,"user_id"=>$_SESSION['user_id']),'id',$limit,'','term');

			foreach($data['afqt_term'] as $d) 
			{
				$date=getFldValueById("report","date",array("user_id"=>$_SESSION['user_id'],"cat_id"=>1000,"term"=>$d['term']));
				
				$total_correct=getFldValueById("report","sum(correct)",array("user_id"=>$_SESSION['user_id'],"cat_id"=>1000,"term"=>$d['term']));
				$total_score=getFldValueById("report","sum(score)",array("user_id"=>$_SESSION['user_id'],"cat_id"=>1000,"term"=>$d['term']));
				
				$total_wrong=59-$total_correct;
				
				if($total_score>270)
			    {
					$score=99;
				}
				else
				{
					$score=getFldValueById("score_master","score",array("total"=>$total_score));
				
				}
				if(!is_nan($score))
	            {
					$score_data[]=array("term"=>$d['term'],"date"=>showdate($date),"correct"=>$total_correct,"total"=>59,"wrong"=>$total_wrong,"raw_score"=>$total_score,"score"=>$score);
				
			    }	
	       }
	       
	break;
	case "47":
	
		$total=getTotalRow("report",array("cat_id"=>47,"lesson_id"=>201,"user_id"=>$_SESSION['user_id']));
		if($total>5)
		{
		    $limit=($total-5) . ",5";
		}
		else
		{
		    $limit=5;
		}		
       
       
		   
	    $row=getQuery("report","*",array("cat_id"=>$_GET['cat_id'],"lesson_id"=>201,"user_id"=>$_SESSION['user_id']),"id",$limit);
        
		foreach($row as $d) 
		{
				
			//$score=getFldValueById("report","score",array("user_id"=>$_SESSION['user_id'],"cat_id"=>$_GET['cat_id'],"term"=>$d['term']));
			
			if(!is_nan($d['score']))
	        {
			   $score_data[]=array("date"=>showdate($d['date']),"score"=>$d['score']);
		    }	
		}	
	 
	break;
	case "76":
	case "75":
		$total=getTotalRow("score_card",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id']));
		
		if($total>5)
		{
		    $limit=($total-5) . ",5";
		}
		else
		{
		    $limit=5;
		}		
        
	    $row=getQuery("score_card","*",array("cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id']),"term",$limit);
        
		foreach($row as $d) 
		{
				
			//$score=getFldValueById("report","score",array("user_id"=>$_SESSION['user_id'],"cat_id"=>$_GET['cat_id'],"term"=>$d['term']));
			
			if(!is_nan($d['score']))
	        {
			   $score_data[]=array("date"=>showdate($d['date']),"score"=>$d['score']);
		    }	
		}	
	 
	break;
	default:
	
	    if($_GET['cat_id']==93)
		{
			$lesson_list=getQuery("lesson","*",array("cat_id in "=>"(47,57)","status"=>"Y"),"ord");
			$lesson_total=getTotalRow("lesson",array("cat_id in "=>"(47,57)","status"=>"Y"));
		
		}
		else
		{	
			$lesson_list=getQuery("lesson","*",array("cat_id"=>$_GET['cat_id'],"status"=>"Y"),"ord");
			$lesson_total=getTotalRow("lesson",array("cat_id"=>$_GET['cat_id'],"status"=>"Y"));
		}	
				
	 
	  	if($lesson_total)
	  	{
	  		 
	  	   		   	 
		    foreach($lesson_list as $d)
		    {
		    	$d['name']=str_replace("MS","Math Skill",$d['name']);
		    	$d['name']=str_replace("RC","Reading Comprehension",$d['name']);
		    	$d['name']=str_replace("RC","Mechanical Comprehension",$d['name']);
		    	
		    	$total=getTotalRow("report",array("test_id"=>$_SESSION['test_id'],"lesson_id"=>$d['lesson_id'],"user_id"=>$_SESSION['user_id']));
				if($total)
				{
				
					if($total>5)
					{
					    $limit=($total-5) . ",5";
					}
					else
					{
					    $limit=5;
					}		
		       
		       
			    	$scrores=getQuery("report","*",array("user_id"=>$_SESSION['user_id'],"lesson_id"=>$d['lesson_id']),"id",$limit);
			    	
			  
			    	$j=0;$score1=0;
			    	
			    	foreach($scrores as $s)
			    	{
			    		$j++;
			    		$score1=$score1+$s['score'];
			    	}
			        if(!is_nan(round($score1/$j,2)))
			        {
			        	$score_data[]=array("date"=>initials($d['name']),"score"=>round($score1/$j,2));
			        }	
			    }
		    	
		    }	
	    }
	    else
	    {
	    	 	
	    	$total=getTotalRow("report",array("test_id"=>$_SESSION['test_id'],"cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id']),'',"term");
			
			if($total)
			{
				
				if($total>5)
				{
				    $limit=($total-5) . ",5";
				}
				else
				{
				    $limit=5;
				}		
	        
		    	$scrores=getQuery("report","*",array("test_id"=>$_SESSION['test_id'],"cat_id"=>$_GET['cat_id'],"user_id"=>$_SESSION['user_id']),"term",$limit,"","term");
		    	 
		    	$score_data=[];
		    	
		    	foreach($scrores as $d) 
				{
						
					$total=getFldValueById("report","sum(total)",array("test_id"=>$_SESSION['test_id'],"user_id"=>$_SESSION['user_id'],"cat_id"=>$_GET['cat_id'],"term"=>$d['term']));
					
					$total_correct=getFldValueById("report","sum(correct)",array("test_id"=>$_SESSION['test_id'],"user_id"=>$_SESSION['user_id'],"cat_id"=>$_GET['cat_id'],"term"=>$d['term']));
					
					$total_score=getFldValueById("report","sum(score)",array("test_id"=>$_SESSION['test_id'],"user_id"=>$_SESSION['user_id'],"cat_id"=>$_GET['cat_id'],"lesson_id !"=>94,"term"=>$d['term']));
					 
					$score=getFldValueById("score_master","score",array("total"=>($total_score/3)));
					 
					$score_data[]=array("date"=>showdate($d['date']),"score"=>round($score,2));
			    }
		    }    	
		
		}	
 
 
}

echo json_encode($score_data);
?>