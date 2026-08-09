<?
include("../configs/autoload.php");
$data['courses']=getQuery("course","*",array("status"=>"Y"));


$data['city']=file("../city.txt");
$data['meta_title']="Sitemap";

echo template($data);

?>
