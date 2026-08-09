<?php
include("configs/config.php");
include("configs/common.php");
include("configs/license.php");

$courses_list=getQuery("course","*",array("status"=>"Y"));

#city=================================
$xml="<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>"."\n";
$xml.="<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">"."\n";

foreach($courses_list as $d)
{
	$xml.="
	<url>
	 	<loc>".$configs['vpath'] ."course/" .$d['seo_url'] ."</loc>
	 	<changefreq>Daily</changefreq>
<priority>1.0</priority>
	</url>";
}
$xml.="\n";

$lines=file("city.txt");

 
foreach($courses_list as $d)
{
	for($i=0;$i<count($lines);$i++)
	{
	    
	   		$xml.="<url>"."\n";
			$xml.="<loc>".$configs['vpath'].seo_url($lines[$i]) ."/course/"  .seo_url($d['seo_url'])."</loc>"."\n";
			$xml.="<changefreq>Daily</changefreq>"."\n";
			$xml.="<priority>1.0</priority>"."\n";
			$xml.="</url>"."\n";
			
		
	}
}
$xml.="</urlset>";

$f=fopen("xml/sitemap.xml","w+");
fputs($f,$xml);
fclose($f);
echo"<p><a href='".$configs['vpath'] ."xml/sitemap.xml'>".$configs['vpath']."xml/sitemap.xml</a></p>";
 
?>