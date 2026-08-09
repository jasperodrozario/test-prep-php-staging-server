<?
if(strpos($_SERVER['PHP_SELF'],"index.php"))
{
	include("configs/config.php");
	include("configs/license.php");
	include("configs/common.php");
	include("configs/array.php");
}
else
{
	include("../configs/config.php");
	include("../configs/license.php");
	include("../configs/common.php");
	include("../configs/array.php");
}
 


if(basename($_SERVER['PHP_SELF'],".php")=="index" or basename($_SERVER['PHP_SELF'],".php")=="")
{
	$configs['includes']=array("header"=>array("includes/tmpl_header"),"footer"=>array("includes/tmpl_footer"));
                
}
else
{
	$configs['includes']=array("header"=>array("includes/tmpl_header"),"footer"=>array("includes/tmpl_footer"));
	
}

$data['files']=array("tmpl_" .basename($_SERVER['PHP_SELF'],".php"));	
 
$data['template_path']=$configs['template_path'];

 

$setting = getDataByWhere("setting","*",array("id"=>1));
 
$data['configs']=array_merge($configs,$setting);
 
if($_GET['seo_url'])
{
    $meta_url=$configs['vpath'] . "info/" .$_GET['seo_url'];
    $meta_img=$configs['vpath'] .$setting['logo'];
}

  
  
$configs['meta_title']=$datas['meta_title'];
$configs['meta_keys']=$datas['meta_keys'];
$configs['meta_desc']=$datas['meta_desc'];
 
$data['about']=getFldValueById("contents","contents",array("seo_url"=>"about-company"));

 

if($_SESSION['test_id'])
{
	$data['cat_value']=getDataByWhere("course","*",array("course_id"=>$_SESSION['test_id']));
	$data['cat_list']=getQuery("category","*",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"),"category_order");
	
    $cat_lesson_total=[];
	$cat_lesson=[];
	
	foreach($data['cat_list'] as $d)
	{
		 
//	    if($d['cat_id']==93)
//	    {
//	    	 $cat_lesson_total[$d['cat_id']]=getTotalRow("lesson",array("cat_id in "=>"(57,47)","status"=>"Y"));
//	    	 $cat_lesson[$d['cat_id']]=getQuery("lesson","*",array("cat_id in "=>"(57,47)","status"=>"Y"),"ord");
//	    }
//	    else
//	    {
            $cat_lesson_total[$d['cat_id']]=getTotalRow("lesson",array("cat_id"=>$d['cat_id'],"online"=>"Y"));
	   	    $cat_lesson[$d['cat_id']]=getQuery("lesson","*",array("cat_id"=>$d['cat_id'],"online"=>"Y"),"lesson_order");
	  
//	    }	
	   	
	}
	$data['cat_lesson_total']=$cat_lesson_total;
	$data['cat_lesson']=$cat_lesson;
	
    $data['num_pred_test'] = getTotalRow("predic_test",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"));
    
   if($data['num_pred_test']==1){
       $data['pred_test_list']=getDataByWhere("predic_test","*",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"));
   }
   else{
        $data['pred_test_list']=getQuery("predic_test","*",array("course_id"=>$_SESSION['test_id'],"online"=>"Y"),"test_order");
   }
    
//	$afqot_sub=[];
	
//	foreach($data['AFOQT'] as $d=>$shortcode)
//	{
//		$cat=getDataByWhere("category","*",array("shortcode"=>$shortcode));
//		$afqot_sub[$cat['cat_id']]=$shortcode;
//	}	
	
//	$data['afqot_sub']=$afqot_sub;
}

if($setting['maintenance_status']>0):
  //header("location:" . $configs['vpath'] . "maintenance");
endif;
 

?>