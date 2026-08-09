<?php
include("../../common/access.php");
include("../../common/autoload.php");
 
$data['page_title']="Site Setting";
$data['page_subtitle']="Site Configuration";
$data['breadcrumb']=array("Site Setting"=>"");
 

$data['db_value']=getDataByWhere("ocs_setting","*",array("id"=>1));

 
echo template($data);
 

