<?php
switch($_SERVER['HTTP_HOST'])
{
	case "localhost":
	case "192.168.1.14":
		include($_SERVER['DOCUMENT_ROOT']."/eduon/configs/config.php");
	break;	 
    default:
        include($_SERVER['DOCUMENT_ROOT']."/configs/config.php");
         
}

include($configs['apath']."configs/license.php");
include($configs['apath']."configs/common.php");
include($configs['apath']."configs/array.php");

if(!$_GET['limit']){$_GET['limit']=1;}
$configs['limit']=(($_GET['limit']-1)*50).",50";
	 
if(!$_SESSION['order_id'])
{
	$_SESSION['order_id']=GetOrderId();
}

// if(basename($_SERVER['PHP_SELF'],".php")=="index" or basename($_SERVER['PHP_SELF'],".php")=="")
// {
// 	$configs['includes']=array("header"=>array("includes/tmpl_header","includes/tmpl_slider"),"footer"=>array("includes/tmpl_footer"));
	               
// }
// else
// {
// 	$configs['includes']=array("header"=>array("includes/tmpl_header"),"footer"=>array("includes/tmpl_footer"));
	
	
// }

// ✅ if isapp=1 → no header/footer
if (isset($_GET['isapp']) && $_GET['isapp'] == 1) {
    if(basename($_SERVER['PHP_SELF'],".php")=="index" 
       || basename($_SERVER['PHP_SELF'],".php")=="") {
        $configs['includes'] = array(
            "header"=>array("includes/tmpl_header-app","includes/tmpl_slider"),
            "footer"=>array("includes/tmpl_footer-app")
        );
    } else {
        $configs['includes'] = array(
            "header"=>array("includes/tmpl_header-app"),
            "footer"=>array("includes/tmpl_footer-app")
        );
    }
} else {
    if(basename($_SERVER['PHP_SELF'],".php")=="index" 
       || basename($_SERVER['PHP_SELF'],".php")=="") {
        $configs['includes'] = array(
            "header"=>array("includes/tmpl_header","includes/tmpl_slider"),
            "footer"=>array("includes/tmpl_footer")
        );
    } else {
        $configs['includes'] = array(
            "header"=>array("includes/tmpl_header"),
            "footer"=>array("includes/tmpl_footer")
        );
    }
}


$data['files']=array("tmpl_" .basename($_SERVER['PHP_SELF'],".php"));	
 
$data['template_path']=$configs['template_path'];

$setting = getDataByWhere("ocs_setting","*",array("id"=>1));
 
$data['meta_title']=$setting['meta_title'];
$data['meta_keys']=$setting['meta_keys'];
$data['meta_desc']=$setting['meta_desc'];
 
unset($setting['meta_title'],$setting['meta_keys'],$setting['meta_desc']);
        
$data['configs']=array_merge($configs,$setting);
 
$data['footer_about']=getDataByWhere("ocs_contents","contents",array("seo_url"=>"about-us"));

$footer_about = strip_tags(html_entity_decode(html_entity_decode(html_entity_decode($data['footer_about']['contents']))));

if(strlen($footer_about) > 215) 
{
                
	$data['footer_contents'] = substr($footer_about, 0, 215). ' <a href="'.$configs["vpath"].'info/about-us" class="read-more footer-read">Read More...</a>';
} 
else 
{
	$data['footer_contents'] = $footer_about;
} 
if($setting['maintenance_status']>0)
{
	
	header("location:" . $configs['vpath'] . "maintenance");

}	

if($_GET['seo_url']=="logout")
{

   echo "<script>setTimeout(\"location.href = '".$configs['vpath'] ."';\",6000);</script>";

}

if($_GET['location'])
{
    $data['location']=$_GET['location'] . "/";
}   
 
?>