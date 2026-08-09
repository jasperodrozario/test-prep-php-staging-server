<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Add Prediction Test";
$data['breadcrumb'] = array("Prediction Test List" => "prediction-test", " Add Prediction Test" => "");

$data['course'] = getQuery("course", "*", "", "course_name");

if ($_POST['SBMT_REG']) {

    // Check if Predic_test_id is set in the form data
    if ($_POST['Predic_test_id']) {
        $predUpdateData = array(
            'course_id' => (int)$_POST['course_id'],
            'test_name' => $_POST['test_name'],
            'free' => $_POST['free'],
            'test_order' => $_POST['test_order'],
            'online' => $_POST['online'],
            'shortcode' => $_POST['shortcode']
        );
        $updateCondition = array('Predic_test_id' => $_POST['Predic_test_id']);
        $p_test_id = updateTable("predic_test", $predUpdateData, $updateCondition);

        if ($p_test_id) {
            $data['msg'] = $configs['success_msg'];
            header("location: prediction-test");
        } else {
            $data['msg'] = "Error updating prediction test information.";
        }
    } else {
        $predInsertData = array(
            'course_id' => (int)$_POST['course_id'],
            'test_name' => $_POST['test_name'],
            'free' => $_POST['free'],
            'test_order' => $_POST['test_order'],
            'online' => $_POST['online'],
            'shortcode' => $_POST['shortcode']
        );
        $p_test_id = insertTable("predic_test", $predInsertData);

        if ($p_test_id) {
            $data['msg'] = $configs['success_msg'];
            header("location: prediction-test");
        } else {
            $data['msg'] = "Error inserting prediction test information.";
        }
    }
}

if ($_REQUEST['Predic_test_id']) {
    $data['db_value'] = getDataByWhere("predic_test", "*", array("Predic_test_id" => $_REQUEST['Predic_test_id']));
    $data['total_cat'] = getTotalRow("prediction_test_category", array("pred_test_id" => $_REQUEST['Predic_test_id']));
}

echo template($data);
