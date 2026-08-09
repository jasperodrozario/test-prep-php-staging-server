<?
switch($_SERVER['HTTP_HOST'])
{
	case "localhost":
	
		require_once($_SERVER['DOCUMENT_ROOT']."/digiweb/configs/config.php");
		 
		$x=explode("/",str_replace("/digiweb","",$_SERVER['REQUEST_URI']));
		
	break;
	default:  
	    
		require_once($_SERVER['DOCUMENT_ROOT']."/configs/config.php");
		$x=explode("/",$_SERVER['REQUEST_URI']);
}		
require_once($configs['apath']."configs/license.php");
require_once($configs['apath']."configs/common.php");
require_once($configs['apath']."configs/array.php");

 
$data['admin']=1;
 

$data['route']=count($x);
if(count($x)>0)
{
	$data['script_dir']=$x[2];
}
 
if(!isset($_SESSION['admin_id']) && !strpos($_SERVER['REQUEST_URI'],"login"))
{
	
	header("location:".$configs['admin_vpath']."login");
	exit();
}
if(basename($_SERVER['PHP_SELF'],".php")=="index" or basename($_SERVER['PHP_SELF'],".php")=="")
{
	$configs['includes']=array("header"=>array("includes/tmpl_header"),"footer"=>array("includes/tmpl_footer"));
                   
}
else
{
	$configs['includes']=array("header"=>array("includes/tmpl_header"),"footer"=>array("includes/tmpl_footer"));
	
}

$e=explode("?",$_SERVER['REQUEST_URI']);
$e1=explode("&",$e[1]);
if(count($e1))
{
	for($i=0;$i<count($e1);$i++)
	{
		$c=explode("=",$e1[$i]);
		
		$_GET[$c[0]]=$c[1];
	}
}
else
{
	$c=explode("=",$e1);
		
	$_GET[$c[0]]=$c[1];
	
}	

if(!$_GET['limit']){$_GET['limit']=1;}
$configs['limit']=(($_GET['limit']-1)*20).",20";

$data['files']=array("tmpl_" .basename($_SERVER['PHP_SELF'],".php"));	
 
$data['template_path']=$configs['template_path'];

$setting = getDataByWhere("setting","*",array("id"=>1));

unset($setting['id']);

$data['configs']=array_merge($configs,$setting);

#end----------------------

 $data['metro_city']=getQuery("city","*",array("status"=>'Y'),"name");
 
 $data['areas']=getQuery("area","*",array("status"=>"Y"),"name");

if(isset($_SESSION['admin_id']))
{

	if($_SESSION['type']=="S"):
		$data['menus']=getQuery("adminmenu","*",array("status"=>'Y',"parent_id"=>'0'),"ord");
     else:
		$data['menus']=getQueryByJoin(array("adminmenu as am","admin_grant ag"),"am.*",array("ag.admin_id"=>$_SESSION['admin_id'],"am.status"=>'Y'),array("am.id"=>"ag.proghead_id"),"am.ord","","","ag.proghead_id");
 	
	endif;
	  
    $sub_menu=array();

	foreach($data['menus'] as $d)
	{
		if($_SESSION['type']=="S"):
			$sub_menu[$d['id']]=getQuery("adminmenu","*",array("status"=>'Y',"parent_id"=>$d['id']),"ord");
			
		else:
			$sub_menu[$d['id']]=getQueryByJoin(array("adminmenu as am","admin_grant ag"),"am.*",array("ag.admin_id"=>$_SESSION['admin_id'],"am.status"=>'Y',"am.parent_id"=>$d['id']),array("am.id"=>"ag.prog_id"),"am.ord");
	 	endif;
		 
	}
   
	$data['sub_menu']=$sub_menu;

    $file=pathinfo($_SERVER['PHP_SELF']);
	
	$scriptname=basename($file['basename'],".php"). "/".$_GET['action'];
	
	$data['scriptname']=$scriptname;
	
	$data['menu_open_id']=getFldValueById("adminmenu","parent_id",array("url"=>$scriptname));

	if(!$data['menu_open_id'])
	{
		$data['menu_open_id']=getFldValueById("adminmenu","id",array("url"=>$scriptname));
	}

}

echo "||".$scriptname;
 
$data['captcha']=$_SESSION['captcha'];

?>