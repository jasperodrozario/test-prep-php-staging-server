<?
include("../common/access.php");

include("../common/autoload.php");

header("Location: website-analytics");

$data['page_title']="Dashboard";
 
$data['breadcrumb']=array("Dashboard"=>""); 

$data["stats"]=array(
	"Total AR Questions"=>getTotalRow("questions",array("cat_id"=>57)),
	"Total MK Questions"=>getTotalRow("questions",array("cat_id"=>47)),
	"Total PC Questions"=>getTotalRow("questions",array("cat_id"=>19)),
	"Total WK Questions"=>getTotalRow("questions",array("cat_id"=>18)),
    "Total FT Questions"=>getTotalRow("questions",array("cat_id"=>70))
    );

$data['cat_ids']=array(0,57,47,19,18,70); 
   
$data['view_chart']=0;    

echo template($data);

?>
	