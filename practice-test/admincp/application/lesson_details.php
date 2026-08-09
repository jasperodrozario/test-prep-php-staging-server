<?php
include("../common/access.php");
include("../common/autoload.php");
include("../../configs/array.php");

// ---- Get QID from POST (form submit) or GET (page view)
If (isset($_POST['add_to_lesson'])) {
    $qid = isset($_POST['qid']) ? (int)$_POST['qid'] : 0;
    $data['qid'] ="post ". $qid;
} else {
    $qid = isset($_GET['qid']) ? (int)$_GET['qid'] : 0;
    $data['qid'] ="get ". $qid;
}


$data['page_title'] = "Lesson Info for QID: " . $qid;
$data['breadcrumb'] = array(
    "Lesson List for Q.ID ".$qid => "",
    
);
 
// ---- Handle Add to Lesson submit
if (isset($_POST['add_to_lesson'])) {

    $course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
    $cat_id    = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;
    $lesson_id = isset($_POST['lesson_id']) ? (int)$_POST['lesson_id'] : 0;
    $order_no  = isset($_POST['order_no']) ? (int)$_POST['order_no'] : "";
    $hw_pt     = isset($_POST['hw_pt']) ? trim($_POST['hw_pt']) : "HW";


    // Validate required fields
    if ($qid <= 0 || $course_id <= 0 || $cat_id <= 0 || $lesson_id <= 0) {
        $data['error'] = "Please select Course, Category, and Lesson.";
    } else {

        // HW/PT flags
        $HW = 0;
        $pt = 0;
        if (strtoupper($hw_pt) === "PT") {
            $pt = 1;  $HW = 0;
        } else {
            $HW = 1;  $pt = 0;
        }
   

        // Optional: prevent duplicate mapping (same qid+course+cat+lesson)
        $dup = getQuery(
            "lesson_questions",
            "id",
            array(
                "qid" => $qid,
                "course_id" => $course_id,
                "cat_id" => $cat_id,
                "lesson_id" => $lesson_id
            ),
            "id DESC",
            "1"
        );

        if ($dup && $dup->num_rows > 0) {
            $data['error'] = "This question is already exists to the selected lesson.";
        } else {

            // Insert
            $insert = array(
                "qid" => $qid,
                "course_id" => $course_id,
                "cat_id" => $cat_id,
                "lesson_id" => $lesson_id,
                "order_no" => $order_no,
                "pt" => $pt,
                "HW" => $HW
            );

            $new_id = insertTable("lesson_questions", $insert);

            if ($new_id) {
                // Redirect to avoid re-submit on refresh
                header("Location: " . $configs['vpath'] . $configs['admin_dir'] . "/lesson_details?qid=" . $qid);
                exit;
            } else {
                $data['error'] = "Failed to save lesson mapping. Please try again.";
                header("Location: " . $configs['vpath'] . $configs['admin_dir'] . "/lesson_details?qid=" . $qid);
                exit;
            }
        }
    }
}

$courseRes = getQuery(
    "course",
    "course_id, course_name",
    array(),
    "course_id"
);

if ($courseRes && $courseRes->num_rows > 0) {
    while ($c = $courseRes->fetch_assoc()) {
        $data['course'][] = $c;   // ['course_id' => ..., 'course_name' => ...]
    }
}

if ($_GET['del_id']) {
    $flag = deleteData("lesson_questions", array("id" => $_GET['del_id']));

    if($flag){
        $data['msg'] = $configs['delete_msg'];
    } else{
        $data['msg'] = $configs['error_msg'];
    }
}

if (!$qid) {
    $data['error'] = "Missing Question ID (qid)";
} else {
    $data['qid'] = $qid;
    $data['lessons'] = []; // renamed for clarity

    $results = getQuery("lesson_questions", "*", array("qid" => $qid), "id ASC");

    if ($results && $results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {

            $row['course_name'] = getFldValueById("course", "course_name", ["course_id" => $row['course_id']]);
            $row['category_name'] = getFldValueById("category", "category_name", ["cat_id" => $row['cat_id']]);
            $row['lesson_name'] = getFldValueById("lesson", "Lesson_name", ["lesson_id" => $row['lesson_id']]);

            $data['lessons'][] = $row;
        }
    }
}


// Pass $data to template system

echo template($data);
?>
