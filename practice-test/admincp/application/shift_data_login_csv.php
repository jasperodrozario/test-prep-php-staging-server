<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$add_ques_in = "Question List";

$data['page_title'] = "Add Data In Database From The CSV File";
$data['breadcrumb'] = array(
    "Add Data into Database" => "",
);

$data['msg']="";

if (isset($_POST['upload_btn'])) {
    
    $flag = false;

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
                
                $field_names = array("old_user_id", "email", "password", "user_type", "reg_date", "activation_status", "reg_at");
                    
                $table_name = "user";
    
                foreach ($field_names as $index => $field_name) {
                    // Check if the field has a value and if so, add it to the insert array
                    if (!empty($row_data[$index])) {
//                            if ($field_name === "answer" || $field_name === "question_tag" || $field_name === "cat_id" || $field_name === "source" || $field_name === "story_id" || $field_name === "old_story_id") {
//                                $insert_data[$field_name] = $row_data[$index + 1];
//                            } else {
//                                $insert_data[$field_name] = '<p>' . htmlspecialchars($row_data[$index + 1]) . '</p>';
//                            }
                        if($row_data[$index]=='S'){
                            $row_data[$index] = 'Student';
                        }
                        
                        $insert_data[$field_name] = $row_data[$index];
                    }
                }
                    
                $flag = insertTable($table_name, $insert_data); 
                    
//                if($flag){
//                    echo "okay <br>";
//                }else{
//                    echo "not okay <br>";
//                }
            }

            // Close CSV file
            fclose($handle);

            if($flag){
                $data['msg']= "Data have been uploaded in ".$table_name." table successfully.";
            }
            else{
                $data['msg']= "Failed to upload data. Please try again.";
            }

        } else {
            $data['msg']= "Error opening CSV file.";
        }
    }
}

echo template($data);
?>



