<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

$add_ques_in = "Question List";

$data['page_title'] = "Add Question in " . $add_ques_in;
$data['breadcrumb'] = array(
    "Question List" => "questions",
    "Add Question in " . $add_ques_in => ""
);

if (!$_GET['page']) {
    $_GET['page'] = 'add-question';
}

$data['msg']="";

if (isset($_POST['upload_btn'])) {
    
    if($_POST['table_name']=="question"){
    
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
                    
                     $field_names = array("question", "option_a", "option_b", "option_c", "option_d", "option_e", "answer", "explanation", "question_tag", "cat_id", "source", "story_id", "old_story_id", "chapter","topic");
    
                    foreach ($field_names as $index => $field_name) {
                        // Check if the field has a value and if so, add it to the insert array
                        if (!empty($row_data[$index + 1])) {
                            if ($field_name === "answer" || $field_name === "question_tag" || $field_name === "cat_id" || $field_name === "source" || $field_name === "story_id" || $field_name === "old_story_id" || $field_name === "chapter" || $field_name === "topic") {
                                $insert_data[$field_name] = $row_data[$index + 1];
                            } else {
                                $insert_data[$field_name] = '<p>' . htmlspecialchars($row_data[$index + 1]) . '</p>';
                            }
                        }
                    }
                    
                    $flag = insertTable("question", $insert_data);   
                }

                // Close CSV file
                fclose($handle);

                if($flag){
                    $data['msg']= "Questions have been uploaded successfully.";
                }

            } else {
                $data['msg']= "Error opening CSV file.";
            }
        } 
        header("location:questions");
    }
    else if($_POST['table_name']=="story"){
        
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
                    // Bind parameters and execute the statement for each row
                    $insert_data = array();
                    $field_names = array("story_title", "author", "story", "old_id");
                    
                        foreach ($field_names as $index => $field_name) {
                            // Check if the field has a value and if so, add it to the insert array
                            if (!empty($row_data[$index + 1])) {
                                if ($field_name === "story") {
                                    $insert_data[$field_name] = '<p>' . htmlspecialchars($row_data[$index + 1]) . '</p>';
                                } else {
                                    $insert_data[$field_name] = $row_data[$index + 1];
                                }
                            }
                        }
                    
                    $flag = insertTable("story", $insert_data);
                }

                // Close CSV file
                fclose($handle);

                if($flag){
                    $data['msg']= "Stories have been uploaded successfully.";
                }

            } else {
                $data['msg']= "Error opening CSV file.";
            }
        } 
        header("location:misc_list");
    }
    else if($_POST['table_name']=="study"){
        
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
                    // Bind parameters and execute the statement for each row
                    $insert_data = array();
                    $field_names = array("study_title", "content", "subject", "chapter", "Topic", "tag");
                    
                        foreach ($field_names as $index => $field_name) {
                            // Check if the field has a value and if so, add it to the insert array
                            if (!empty($row_data[$index + 1])) {
                                if ($field_name === "content") {
                                    $insert_data[$field_name] = '<p>' . htmlspecialchars($row_data[$index + 1]) . '</p>';
                                } else {
                                    $insert_data[$field_name] = $row_data[$index + 1];
                                }
                            }
                        }
                    
                    $flag = insertTable("study", $insert_data);
                }

                // Close CSV file
                fclose($handle);

                if($flag){
                    $data['msg']= "Studies have been uploaded successfully.";
                }

            } else {
                $data['msg']= "Error opening CSV file.";
            }
        } 
        header("location:study-list");
    }
}

echo template($data);
?>



