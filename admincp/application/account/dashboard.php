<?
include("../../common/access.php");

include("../../common/autoload.php");

$data['page_title']="Dashboard";
 
$data['breadcrumb']=array("Dashboard"=>""); 
 
$query=[];



#update clients status

$data["stats"]=array(
	"Total Courses"=>array("total"=>getTotalRow("ocs_course"),"icon"=>"product.png","url"=>$configs['admin_vpath']."course/course-list"), 
      "Total Student Registers"=>array("total"=>getTotalRow("student"),"icon"=>"product.png","url"=>$configs['admin_vpath']."course/course-list"),  
    
        "Total Amount Transactions"=>array("total"=>getFldValueById("ocs_transactions","sum(amount)",array("payment_status"=>"success")),"icon"=>"product.png","url"=>$configs['admin_vpath']."course/course-list"), 
         "Total Number of  Transactions"=>array("total"=>getTotalRow("ocs_transactions",array("payment_status"=>"success")),"icon"=>"product.png","url"=>$configs['admin_vpath']."course/course-list")  
    ); 
    
     

echo template($data);

?>
	