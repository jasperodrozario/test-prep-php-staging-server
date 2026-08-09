<?
include("../common/access.php");

include("../common/autoload.php");

$data['page_title']="Dashboard";
 
$data['breadcrumb']=array("Dashboard"=>""); 

$query=[];


$data["stats"]=array(
    "Products"=>array("total"=>getTotalRow("products"),"icon"=>"product.png","url"=>$configs['admin_vpath']."product/product-list"),
      
        
    "Member"=>array("total"=>getTotalRow("member",array("status"=>"Y")),"icon"=>"blog.png","url"=>$configs['admin_vpath']."member/member-list"),
    "Enquiries"=>array("total"=>getTotalRow("enquiry"),"icon"=>"search.png","url"=>$configs['admin_vpath']."enquiry/enquiry-list"), 
    
    "Feedback"=>array("total"=>getTotalRow("feedback"),"icon"=>"feedback.png","url"=>$configs['admin_vpath']."feedback/feedback-list")    
       
    ); 



$data["stats_url"]=array(
    "Products"=>"product/product-list",
    "Member"=>"member/member-list",
    "Enquiries"=>"enquiry/enquiry-list",
       
    "Feedback"=>"blog/blog-list"
    );

   
$data['view_chart']=0;    

echo template($data);

?>
	