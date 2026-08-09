<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");


$data['page_title'] = "Add Data In Database From The CSV File";
$data['breadcrumb'] = array(
    "Add Data into Database" => "",
);

$data['msg']="";

if (isset($_POST['update_subs_btn'])){
    
    $flag = false;
    
    $user_list = getQuery("user", "*");
    
    $user_id_list = array();
    
    foreach ($user_list as $u){
        $user_id_list[$u['old_user_id']] = $u['user_id'];
    }
    
    // Check if a file is uploaded
    if ($_FILES['csv_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $csv_file_path = $_FILES['csv_file']['tmp_name'];

        // Read CSV file
        if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
            // Assuming the first row contains column headers, we skip it
            fgetcsv($handle);

            // Prepare SQL statement for inserting data
            while (($row_data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                
                $field_names = array("user_id", "first_name", "last_name", "mobile", "test_id");
                
                if($user_id_list[(int)$row_data[0]]){
                    
                    $row_data[4] = (int)$row_data[4];
                    
                    if($row_data[4]==1){
                        $course_id=21;
                    }
                    elseif($row_data[4]==3){
                        $course_id=24;
                    }
                    elseif($row_data[4]==4){
                        $course_id=25;
                    }
                    elseif($row_data[4]==5){
                        $course_id=26;
                    }
                    
                    if($user_id_list[(int)$row_data[0]]){
                        
                        $cnt = (int)getTotalRow("subscription", array("user_id"=>$user_id_list[(int)$row_data[0]], "course_id"=>$course_id));
                        
                        if(!$cnt){
                            $flag = insertTable("subscription", array("user_id"=>$user_id_list[(int)$row_data[0]], "course_id"=>$course_id));
                        }
                    }
                } 
            }

            // Close CSV file
            fclose($handle);

            if($flag){
                $data['msgg']= "Data have been uploaded in Subscription table successfully.";
            }
            else{
                $data['msgg']= "Failed to upload data. Please try again.";
            }

        } else {
            $data['msgg']= "Error opening CSV file.";
        }
    }
    
}


if (isset($_POST['upload_btn'])) {
    
    $flag = false;
    
//    $data['checking']  = array();
    
    $user_list = getQuery("user", "*");
    
    $user_id_list = array();
    
    foreach ($user_list as $u){
        $user_id_list[$u['old_user_id']] = $u['user_id'];
    }

    // Check if a file is uploaded
    if ($_FILES['csv_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $csv_file_path = $_FILES['csv_file']['tmp_name'];

        // Read CSV file
        if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
            // Assuming the first row contains column headers, we skip it
            fgetcsv($handle);

            // Prepare SQL statement for inserting data
            while (($row_data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                $insert_data = array();
                // Bind parameters and execute the statement for each row
                
                $field_names = array("user_id", "amount", "currency", "date", "test_id", "txn_id", "subscription");
                    
                $table_name = "subscription";
                
                if($row_data[6]!="1 month"){
                    $row_data[6] .= "s";
                }
                
                if($user_id_list[(int)$row_data[0]]){
                    
                    $package = getDataByWhere("package", "*", array("duration"=>$row_data[6]));
                        
                    $cnt = (int)getTotalRow("subscription", array("user_id"=>$user_id_list[(int)$row_data[0]]));
                        
                    if($cnt){
                        
                        // Extract the number of months from $row_data[6]
                        $months = (int) filter_var($row_data[6], FILTER_SANITIZE_NUMBER_INT);

                        // Calculate the expiration date by adding the months to the subscription date
                        $subscription_date = $row_data[3];
                        $expire_date = date('Y-m-d', strtotime("+$months months", strtotime($subscription_date)));

                        $flag = updateTable("subscription", array("package_id"=>$package['package_id'], "amount"=>$row_data[1], "currency"=>$row_data[2], "subscription_date"=>$row_data[3], "expire_date"=>$expire_date, "txn_id"=>$row_data[5], "subscription"=>$row_data[6]), array("user_id"=>$user_id_list[(int)$row_data[0]]));
                    }
                    
                }
                
            }

            // Close CSV file
            fclose($handle);

            if($flag){
                $data['msgg']= "Data have been uploaded in ".$table_name." table successfully.";
            }
            else{
                $data['msgg']= "Failed to upload data. Please try again.";
            }

        } else {
            $data['msgg']= "Error opening CSV file.";
        }
    }
}

echo template($data);
?>



