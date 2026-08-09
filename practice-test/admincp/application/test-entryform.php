<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Add  Test Name";
$data['breadcrumb']=array("Test Name"=>"mock-test","Add Test Name"=>"");

if($_POST['SBMT'])
{
     if($_POST['id']):
   	    $r=updateTable("test",$_POST,array("id"=>$_POST['id']),array("icon"));
	       
		$id=$_POST['id'];
	else:
	    unset($_POST['id']);
	    $id=insertTable("test",$_POST,array("icon"));
	
	endif;
	if($id):
		deleteData("test_category",array("id"=>$id));
	
	    for($i=0;$i<count($_POST['cat_ids']);$i++)
	    {
	    	if($_POST['cat_ids'][$i])
	    	insertTable("test_category",array("id"=>$id,"cat_id"=>$_POST['cat_ids'][$i]));
	    }
		
	 	$data['msg']=$configs['success_msg'] ."<meta http-equiv='refresh' content=\"15;URL='mock-test'\" />";
	 	header("location:mock-test");
    	
	endif;
 
}	
$data['categories']=getQuery("category","*",array("status"=>"Y","parent_id"=>0),"cat_name");

if($_GET['id'])
{
	
    $data['db_value']=getDataByWhere("test","*",array("id"=>$_GET['id']));
    
    $q=getQuery("test_category","cat_id",array("id"=>$_GET['id']));
    
    $clist=[];
    
    foreach($q as $d)
    {
    	$clist[]=$d['cat_id'];
    }
    
    $data['clist']=$clist;	
	
} 
  
echo template($data);
?>

 