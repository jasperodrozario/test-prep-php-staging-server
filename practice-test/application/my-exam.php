<?php 
include("../configs/autoload.php");
chklogin();
$data['total_exams']=getTotalRowsByJoin(array("running_exam as r","questions_set as q"),array("r.user_id"=>$_SESSION['user_id']),array("r.set_id"=>"q.id"));
                     
$data['exams']=getQueryByJoin(array("running_exam as r","questions_set as q"),"r.*,q.set_name,q.timer",array("r.user_id"=>$_SESSION['user_id']),array("r.set_id"=>"q.id"),"r.start_time desc");
                    
echo template($data);
?>  