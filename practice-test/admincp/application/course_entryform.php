<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add Course";
$data['breadcrumb']=array("Course List"=>"course_list","Add Course"=>"");

$data['btn']="<a href='#' class='set-pred-set-btn btn btn-success' data-toggle='modal' data-target='#setPredSetModal'>Create Course Group</a>";
 
if($_POST['SBMT_REG']):
    
    if(isset($_POST['is_online'])){
        $_POST['is_online'] = 'Y';
    }else{
        $_POST['is_online'] = 'N';
    }

	$_POST['subscribe'] = isset($_POST['subscribe']) ? 1 : 0;
	$_POST['app'] = isset($_POST['app']) ? 1 : 0;
	$_POST['web'] = isset($_POST['web']) ? 1 : 0;

   	if($_POST['course_id']):
   	    $r=updateTable("course",$_POST,array("course_id"=>$_POST['course_id']));
	       
		$course_id=$_POST['course_id'];
	else:
	    unset($_POST['course_id']);
	    $course_id=insertTable("course",$_POST);
	    
		
	endif;
	if(!mysqli_errno($configs['con'])):
	 	$data['msg']=$configs['success_msg'];
	 	header("location:course_list");
    	
	endif;

endif;


if($_REQUEST['course_id'])
{
	
    $data['db_value']=getDataByWhere("course","*",array("course_id"=>$_REQUEST['course_id']));
	
} 
 
 

echo template($data);
?>

 