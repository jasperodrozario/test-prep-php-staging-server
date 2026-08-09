<?php
include("../configs/autoload.php");

chklogin();

// $data['meta_title']="Dashboard";

$data['total']=getTotalRow('orders',array("user_id"=>$_SESSION['user_id']));

$data['db_list']= getQuery("orders","*",array("user_id"=>$_SESSION['user_id']),"ord_date desc",5);


$query=["vendor_id"=>$configs['vendor_id'],"status"=>"Y"];
                   


$data['db_menu']=getQuery("menu","*",$query,"rand()",8);

$menu_cate=[];

foreach ($data['db_menu'] as $d) 
{
	$menu_cate[$d['cat_id']]=getFldNameById("category","cat_name",array("cat_id"=>$d['cat_id']));
}

$data['menu_cate']=$menu_cate;


 	

echo template($data);

?>





 


 





 


 
