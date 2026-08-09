<?php 
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Referrer Earning : " . getName($_GET['user_id']) . "<br>" . getPaymentMethod($_GET['user_id']) ;

 
$data['breadcrumb']=array("Referrer Payout"=>"");

if(!$_GET['year'])
{
   $_GET['year']=date("Y");
}   

$month=date("m");

$month_array=[];

for($i=1;$i<=$month;$i++)
{
   if($i<10){$m="0".$i;}else{$m=$i;}

   $year_month=$_GET['year'] . "-". $m;

   $my=$m."-".$_GET['year'];
   
   $exist=getTotalRow("referrer_payout",["user_id"=>$_GET['user_id'],"month"=>$key]);
   
   if($exist){$status="Y";}else{$status="N";}
   
   $month_array[$my]=["earning"=>getFldValueById("referral","sum(referral_earning)",['referrer_id'=>$_GET['user_id'],"substring(date,1,7)"=>$year_month]),"student"=>getTotalRow("referral",['referrer_id'=>$_GET['user_id'],"substring(date,1,7)"=>$year_month]),"status"=>$status];


}   

$data['month_array']=$month_array;

echo template($data);
?>