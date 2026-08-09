<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");


if (isset($_POST['submit'])) {
    
    $data['response'] = array();
    
    $all_user_list = getQuery("user", "*",array("user_id >"=>130));
    
    foreach ($all_user_list as $u){
        
        if($u['old_user_id']){

//            $d = getDataByWhereOfficersAsvab("student","*",array("user_id"=>$u['old_user_id']));
            
    //        $course_id = $d['test_id'];
    //        
    //        if($course_id==1){
    //            $course_id=21;
    //        }
    //        elseif($course_id==3){
    //            $course_id=24;
    //        }
    //        elseif($course_id==4){
    //            $course_id=25;
    //        }
    //        elseif($course_id==5){
    //            $course_id=26;
    //        }
    //        
    //        updateTable("user",array("first_name"=>$d['first_name'], "last_name"=>$d['last_name'], "mobile"=>$d['mobile']),array("old_user_id"=>$u['old_user_id']));
    //        
    //        $id = insertTable("subscription",array("user_id"=>$u['user_id'],"course_id"=>$course_id));

    //        $data['response'][]=$id;

            $data['response'][]=$d['id'];
//            $data['response'][]=$u['old_user_id'];
        }
    }
}else{
    $data['response'] = array(1,2,3);
}


echo template($data);
?>
