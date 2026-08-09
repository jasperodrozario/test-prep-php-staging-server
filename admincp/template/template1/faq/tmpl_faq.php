<?
include($configs['admin_template_include']."includes/tmpl_breadcrumb.php");

if(file_exists($configs['admin_template_include']."faq/tmpl_" .$_GET['action'].".php")) 
{
  include($configs['admin_template_include']."faq/tmpl_" .$_GET['action'].".php");
  
   
} 
else
{
  echo $configs['admin_template_include']."faq/tmpl_" .$_GET['action'].".php not found!";
  exit;
}
?>
