<?php
include("../common/access.php");
include("../common/autoload.php");
 
$data['page_title']="Site Setting";

$data['breadcrumb']=array("Site Setting"=>"");
 
$data['id']=1;


if($_POST)
{
   
    $r=updateTable("setting",$_POST,array("id"=>1),array("logo","favicon"));
     
    if($r)
    {
 
      $data['msg']=$configs['success_msg'];

    }
    else
    {

      $data['msg']=$configs['success_error'];

    }	  
	
}


 

$data['db_value']=getDataByWhere("setting","*",array("id"=>1));

 

echo template($data);
?>
 
 