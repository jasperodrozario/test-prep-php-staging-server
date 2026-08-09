<?
require($configs['admin_template_include']."includes/tmpl_breadcrumb.php");


if(file_exists($configs['admin_template_include'].$module."/tmpl_" .$_GET['action'].".php"))
{
	require($configs['admin_template_include'].$module."/tmpl_" .$_GET['action'].".php");

}
else
{
	echo $configs['admin_template_include'].$module."/tmpl_" .$_GET['action'].".php not found!";
	exit;
}
?>