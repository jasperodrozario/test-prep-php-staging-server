<?
if(strpos($_SERVER['PHP_SELF'],"index.php"))
{
	include("../configs/config.php");
	include("../configs/license.php");
	include("../configs/common.php");
}
else
{
	include("../../configs/config.php");
	include("../../configs/license.php");
	include("../../configs/common.php");
}


#files-----------------
$configs['includes']=array("header"=>array("includes/tmpl_header"),"footer"=>array("includes/tmpl_footer"));

$data['files']=array("tmpl_" .basename($_SERVER['PHP_SELF'],".php"));
 
$configs['template']="template";
$data['admin']=1;
#end----------------------
 
$setting = getDataByWhere("setting","*",array("id"=>1));

$data['configs']=array_merge($configs,array($setting));

if($_SESSION['type']=="S"):
	$data['menus']=getQuery("adminmenu","*",array("status"=>'Y',"parent_id"=>'0'),"ord");
else:
	$data['menus']=getQueryByJoin(array("adminmenu as am","admin_grant ag"),"am.*",array("ag.admin_id"=>$_SESSION['admin_id'],"am.status"=>'Y'),array("am.id"=>"ag.proghead_id"),"am.ord");
endif;

$sub_menu=array();

foreach($data['menus'] as $d)
{
	if($_SESSION['type']=="S"):
		$sub_menu[$d['id']]=getQuery("adminmenu","*",array("status"=>'Y',"parent_id"=>$d['id']),"ord");
	else:
		$sub_menu[$d['id']]=getQueryByJoin(array("adminmenu as am","admin_grant ag"),"am.*",array("ag.admin_id"=>$_SESSION['admin_id'],"am.status"=>'Y',"am.parent_id"=>$d['id']),array("am.id"=>"ag.proghead_id"),"am.ord");
	endif;
	 
}

$data['sub_menu']=$sub_menu;

$scriptname=basename($_SERVER["REQUEST_URI"],".php");

$scriptname=reset(explode("&",$scriptname));

$data['menu_open_id']=getFldValueById("adminmenu","parent_id",array("url"=>$scriptname));

if(!$data['menu_open_id'])
{
	$data['menu_open_id']=getFldValueById("adminmenu","id",array("url"=>$scriptname));
}

 

if(!$_GET['limit']){$_GET['limit']=1;}
$data['limit']=(($_GET['limit']-1)*50) .",50";


$data['captcha']=$_SESSION['captcha'];
?>