<?php 
include("../common/access.php");
include("../common/autoload.php");

$data['page_title']="Referrer List";

$referrer_list = getQuery("referral_earnings","*",array(),"","","","user_id");
$total=0;
$ref_list = [];
foreach($referrer_list as $r){
    $total+=1;
    $ref_list[]=$r['user_id'];
}

$param = [];

$query=["referral_code >"=>0];

if($_POST['user_id'])
{
   $query=array_merge($query,["user_id"=>$_POST['user_id']]);
}
if($_POST['code'])
{
  $query=array_merge($query,["referral_code"=>$_POST['code']]);
}

if(isset($_POST['search'])){
    $query=array_merge($query,array("[or]"=>"(user_id like '%" .$_POST['search_input'] ."%' or email like '%" .$_POST['search_input'] ."%' or concat(first_name,' ',last_name) like '%" .$_POST['search_input'] ."%' or first_name like '%" .$_POST['search_input'] ."%' or last_name like '%" .$_POST['search_input']."%' or referral_code like '%" .$_POST['search_input']."%')"));
}

$query=array_merge($query, array("user_id in ("=> implode(', ', $ref_list).")"));

$data['total']=$total;
 
$data['db_list']=getQuery("user","*",$query,"user_id desc",$configs['limit']);
 
$val_total=[];$val_earning=[];$val_due=[];	 

foreach($data['db_list'] as $d)
{
  $val_total[$d['user_id']]=getTotalRow("referral_earnings",['user_id'=>$d['user_id']]);
  $val_earning[$d['user_id']]=getFldValueById("referral_earnings","sum(amount)",['user_id'=>$d['user_id']]);
  $val_due[$d['user_id']]=$val_earning[$d['user_id']]-getFldValueById("referrer_payout","sum(amount)",['user_id'=>$d['user_id']]);
}		 

$data['val_total']=$val_total;
$data['val_earning']=$val_earning;
$data['val_due']=$val_due;

$data['breadcrumb'] = array("Referrer List" => "", " > Total Referrer: " . $data['total'] => "");

$data['page_title']="Total Referrer List [".$data['total']."]";

 

echo template($data);
?>