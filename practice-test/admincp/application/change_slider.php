<?
include("../common/access.php");
include("../common/autoload.php");


$data['page_title']="Change Slider";
$data['breadcrumb']=array("Change Slider"=>"");
$data['btn']="<a href='change_slider?id=0' class='btn btn-success'>Add Banner</a>";

$img=array("png","jpg");

if($_REQUEST['delete']):

	$sql2=deleteData("slider",array("id"=>$_REQUEST['delete'])); 

endif;

if($_POST['submit']):

	if($_POST['id']):
	    $sql1=updateTable("slider",$_POST,array("id"=>$_POST['id']),array("slider"));
		 
	else:
		$sql1=insertTable("slider",$_POST,array("slider"));
		
	endif;
	if(!mysqli_errno($configs['con']))
	{
		
		$data['msg']=$configs['success_msg'];
    
    }
    else
    {
	    $data['msg']=$configs['error_msg'];
	}
endif;

if($_REQUEST['id'])
{
	$data['db_value']=getDataByWhere("slider","*",array("id"=>$_REQUEST['id']));

}
$data['sliders']=getQuery("slider");
 
echo  template($data);
 
?>