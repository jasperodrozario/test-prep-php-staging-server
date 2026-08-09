<?php

include("../configs/autoload.php");
chklogin(str_replace("/practice-test/","",$_SERVER['REQUEST_URI']));
if($_SESSION['status']=="N")
{
	header("location:" . $configs['vpath'] ."renew-subscription");
	exit();
} 

require '../dompdf/vendor/autoload.php';  // Include the dompdf library
require_once ('../jpgraph/src/jpgraph.php');
require_once ('../jpgraph/src/jpgraph_bar.php');

use Dompdf\Dompdf;

function generateBarChart($chart_data, $y_title = 'Score', $title='Progress Chart') {
    // Extracting data for the graph
    $labels = [];
    $scores = [];
    $flag = false;

    foreach ($chart_data as $data) {
        if ($data['score']) {
            $labels[] = $data['index'];
            $scores[] = $data['score'];
            $flag = true;
        }
    }

    if ($flag) {
        // Create the graph in memory
        $graph = new Graph(600, 300);
        $graph->SetScale('textlin');

        // Set margin
        $graph->SetMargin(40, 30, 20, 40);

        // Set labels on X-axis
        $graph->xaxis->SetTickLabels($labels);

        // Create the bar plot
        $barplot = new BarPlot($scores);
        $barplot->SetFillColor('lightblue');
        $graph->Add($barplot);

        // Set Y-axis range with a custom max value (up to 100)
        $graph->yaxis->scale->SetAutoMin(0);  // Minimum value (0)
        $graph->yaxis->scale->SetAutoMax(100);  // Maximum value (100)

        // Add title
        $graph->title->Set($title);
        $graph->xaxis->title->Set('');
        $graph->yaxis->title->Set($y_title);

        // Capture the image output in memory (output buffering)
        ob_start();
        $graph->Stroke(_IMG_HANDLER);
        $image = $graph->img->Stream(); // Stream the image directly to the buffer
        $imageData = ob_get_contents();
        ob_end_clean();

        // Convert image data to Base64
        return base64_encode($imageData);
    }

    return null; // Return null if no chart data
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create a new instance of Dompdf
    $dompdf = new Dompdf();
    
    // Enable remote images
    $dompdf->set_option('isRemoteEnabled', true);
    
    if($_POST['cat_id']){
        $cat_id = $_POST['cat_id'];
    }
    else{
        $cat_id = 2000;
    }
    
    if($_POST['student_id']){
        
        $user_id=(int)$_POST['student_id'];
        $test_id=(int)getFldValueById("subscription","course_id",array("user_id"=>$_POST['student_id']));
        $test_name=getFldValueById("course","course_name",array("course_id"=>$test_id));
        $email=getFldValueById("user","email",array("user_id"=>$_POST['student_id']));
        $fname=getFldValueById("user","first_name",array("user_id"=>$_POST['student_id']));
        $lname=getFldValueById("user","last_name",array("user_id"=>$_POST['student_id']));
        $name=$fname." ".$lname;
    
    }else{
        
        $user_id=(int)$_SESSION['user_id'];
        $test_id=(int)$_SESSION['test_id'];
        $test_name=$_SESSION['test_name'];
        $email=$_SESSION['email'];
        $name=$_SESSION['name'];
        
    }

    // Fetch user results from the database
    
    $report = array();
    $chart_data = array();
    $pred_chart_data = array();
    
//    $flag = "";
    
    switch ($cat_id){
        
        case 2000:
            
            $chart_title = "Overall Progress Chart";

            $cat_list=getQuery("category","*",array("course_id"=>$test_id),"category_order");

            $result_list = array();
            
            foreach($cat_list as $d)
            {
                $cat_lesson_total=getTotalRow("lesson",array("course_id"=>$test_id, "cat_id"=>$d['cat_id'],"online"=>"Y"));
                
                if($cat_lesson_total){
                    $cat_lessons=getQuery("lesson","*",array("course_id"=>$test_id, "cat_id"=>$d['cat_id'],"online"=>"Y"),"lesson_order");
                }
                
                $total_cat_score = 0;
                $cnt_total_lesson = 0;
                
                foreach ($cat_lessons as $lesson)
                {
                    $result_index = $d['category_name']." ". $lesson['Lesson_name'];
                    $report_total=getTotalRow("result",array("user_id"=>$user_id,"cat_id"=>$d['cat_id'],"lesson_id"=>$lesson['lesson_id'],"score!"=>-1));
                    
                    if($report_total){
                        $reports=getQuery("result","*",array("user_id"=>$user_id,"cat_id"=>$d['cat_id'],"lesson_id"=>$lesson['lesson_id'],"score!"=>-1),"result_id desc",5);

                        $result_list[$result_index] = array();
                        
                        foreach($reports as $r){
                            $result_list[$result_index][] = array("Date"=> showdate($r['result_date']), "Total Question"=> $r['total_questions'], "Correct"=>$r['correct_answers'], "Wrong"=>($r['total_questions']-$r['correct_answers']), "Score"=>(int)$r['score']);
                            
                            $total_cat_score+=(int)$r['score'];
                            $cnt_total_lesson+=1;
                        }
                    }
                }
                
                $chart_data[] = array("index"=>$d['shortcode'], "score"=>(int)($total_cat_score/$cnt_total_lesson));
            }
            
            $y_title = "Average Score";
        
            break;
        
        default:
            
            $result_list = array();
            
            $cat=getDataByWhere("category","*",array("cat_id"=>$cat_id));
            
            $pred_cat=getDataByWhere("predic_test","*",array("Predic_test_id"=>$cat_id));
            
            $data['pred_cat_list'] = getQuery("predic_test","*",array("course_id"=>$test_id, "online"=>'Y'),"test_order");
            
            if($pred_cat){
                $pred_code = $pred_cat['shortcode'];
                $chart_title = $pred_code . " Progress Chart";
                $y_title = "Score";
                
                $pred_cat_list = getQuery("prediction_test_category","*",array("pred_test_id"=>$cat_id),"","","","id");
                
                $total=0;
                $total_cat=0;
                foreach($pred_cat_list as $p){
                    $total=$total+$p['number_of_questions'];
                    $total_cat++;
                }
                $total_results=getTotalRow("result",array("cat_id"=>$cat_id,"Predic_test_id"=>$cat_id,"user_id"=>$user_id));

                if($total_results>5)
                {
                    $limit=($total_results-5) . ",5";
                }
                else
                {
                    $limit=5;
                }
                $results=getQuery("result","*",array("cat_id"=>$cat_id,"Predic_test_id"=>$cat_id,"user_id"=>$user_id,"score!"=>-1),"result_id",$limit);
                
                foreach($results as $d) 
                {
                    $total_wrong=$d['total_questions']-$d['correct_answers'];

                    $score = (int)$d['score'];
                    
                    if($score)
                    {
                        $result_list[$pred_code." Prediction Test"][]=array("cat_id"=>$cat_id,"test_id"=>$test_id,"Date"=>showdate($d['result_date']),"Correct"=>$d['correct_answers'],"Total Question"=>$d['total_questions'],"Wrong"=>$total_wrong,"Score"=>$score);

                        $chart_data[] = array("index"=>showdate($d['result_date']), "score"=>(int)$score);
                    }
                }  
            }
            elseif($cat){
                
                $cat_code = $cat['shortcode'];
                $chart_title = $cat_code . " Progress Chart";
                $y_title = "Average Score";
                
                $result_list = array();
                
                $cat_lesson_total=getTotalRow("lesson",array("course_id"=>$test_id, "cat_id"=>$cat['cat_id'],"online"=>"Y"));
                
                if($cat_lesson_total){
                    $cat_lessons=getQuery("lesson","*",array("course_id"=>$test_id, "cat_id"=>$cat['cat_id'],"online"=>"Y"),"lesson_order");
                }
                
                foreach ($cat_lessons as $lesson)
                {
                    $total_lesson_score = 0;
                    $cnt_total_lesson = 0;
                    
                    $result_index = $cat['category_name']." ". $lesson['Lesson_name'];
                    $report_total=getTotalRow("result",array("user_id"=>$user_id,"cat_id"=>$cat['cat_id'],"lesson_id"=>$lesson['lesson_id'],"score!"=>-1));
                    
                    if($report_total){
                        $reports=getQuery("result","*",array("user_id"=>$user_id,"cat_id"=>$cat['cat_id'],"lesson_id"=>$lesson['lesson_id'],"score!"=>-1),"result_id desc",5);

                        $result_list[$result_index] = array();
                        
                        foreach($reports as $r){
                            $result_list[$result_index][] = array("Date"=> showdate($r['result_date']), "Total Question"=> $r['total_questions'], "Correct"=>$r['correct_answers'], "Wrong"=>($r['total_questions']-$r['correct_answers']), "Score"=>(int)$r['score']);
                            
                            $total_lesson_score+=(int)$r['score'];
                            $cnt_total_lesson+=1;
                        }
                        
                        $chart_data[] = array("index"=>$lesson['Lesson_name'], "score"=>round($total_lesson_score/$cnt_total_lesson));
                    }
                }
            }   
    }
    
    // Generate the progress report HTML format
    $logo_path = "https://staging-test-prep.asvab-tutoring.com/images/test-prep-logo.png";
    $app_store_path = "https://officers.asvab-tutoring.com/template/template1/images/app_store.png";
    $play_store_path = "https://officers.asvab-tutoring.com/template/template1/images/play_store.png";
    
    $html = '
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            
            <style>
                @page {
                    margin: 50px 25px;
                }
                body {
                    margin-top: 0px;
                    margin-bottom: 50px;
                }
                
                .result-table td {
                    text-align: center;
                }
                
                .footer {
                    position: fixed;
                    left: 0px;
                    bottom: 0px;
                    right: 0px;
                    height: 30px; /* Reduced height */
                    font-size: 12px;
                    color: #666;
                    padding: 10px 25px;
                }

                .footer-content {
                    display: flex;
                    justify-content: space-between; /* Align items across the available space */
                    align-items: center; /* Align vertically in the center */
                }

                .footer .qr-code {
                    display: inline-block;
                    margin-right: 10px; /* Space between QR codes */
                    margin-top: 5px;
                }

                .footer .links {
                    text-align: right;
                }

                .footer .pagenum:before {
                    content: counter(page);
                }
            </style>
      
        <div class="footer">
            <div class="footer-content">
                
                <table width="100%" cellspacing="0" cellpadding="5">
                    <td align="left">
                        <!-- Left side: QR Codes -->
                        <div class="qr-codes">
                            <img src="'.$app_store_path.'" alt="App Store QR" width="50" height="50" class="qr-code">
                            <img src="'.$play_store_path.'" alt="Play Store QR" width="50" height="50" class="qr-code">
                            [Scan to download the app]
                        </div>
                    </td>
                    
                    <td align="right">
                        <!-- Right side: Links -->
                        <div class="links">
                            <a href="https://www.asvab-tutoring.com" target="_blank">www.asvab-tutoring.com</a><br>
                            <a href="https://staging-test-prep.asvab-tutoring.com/" target="_blank">staging-test-prep.asvab-tutoring.com/</a>
                        </div>
                    </td>
                </table>
                
                <!-- Center: Page Number -->
                <div class="pagenum">
                    Page <span class="pagenum"></span>
                </div>
            </div>
        </div>
    
        <table width="100%" cellspacing="0" cellpadding="5">
            <tr>
                <td align="left">
                    <h2 style="padding: 0; margin: 0px 0;">ASVAB Tutoring</h2>
                    <h2 style="padding: 0; margin: 0px 0;">Test Prep Online Test Series</h2>
                    <h2 style="padding: 0; margin: 0px 0;">Progress Report</h2>
                </td>
                <td align="right">
                    <img src="'.$logo_path.'" alt="ASVAB Tutoring" width="100" height="100">
                </td>
            </tr>
            <tr>
                <td align="left">
                    <h3 style="padding: 0; margin: 0px 0;">User Information:</h3>
                    Name: ' . $name . '<br>
                    Email: ' . $email . '<br>
                    Course: ' . $test_name . '
                </td>
            </tr>
        </table><br>';
        
        // HTML with the embedded base64 image
        if (!empty($chart_data)){
            $base64Image = generateBarChart($chart_data, $y_title, $chart_title);
            if($base64Image){
                $html .= '
                    <img src="data:image/png;base64,' . $base64Image . '" alt="Bar Chart" style="width:100%; height:auto;">
                ';
            }
        }
    
        foreach ($result_list as $result_index => $results) {
           
           $html.= '<table class="result-table" border="1" cellspacing="0" cellpadding="5" width="100%">';
           $html .= '<tr>
                        <th colspan="5" style="background-color:#6EACDA;">' . $result_index . '</th>
                    </tr>';
               
           $html .= '<tr>
                        <th>Date</th>
                        <th>Total Question</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th>Score</th>
                    </tr>';

            // Traverse each result entry within this index
            foreach ($results as $result) {
                $html .= '<tr>';
                $html .= '<td>' . $result['Date'] . '</td>';
                $html .= '<td>' . $result['Total Question'] . '</td>';
                $html .= '<td>' . $result['Correct'] . '</td>';
                $html .= '<td>' . $result['Wrong'] . '</td>';
                $html .= '<td>' . $result['Score'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table><br>';
        }
        
        foreach ($pred_report as $result_index => $results) {
            
           $html.= '<table class="result-table" border="1" cellspacing="0" cellpadding="5" width="100%">';
           $html .= '<tr>
                        <th colspan="5" style="background-color:#6EACDA;">' . $result_index . '</th>
                    </tr>';
               
           $html .= '<tr>
                        <th>Date</th>
                        <th>Total Question</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th>Prediction Score</th>
                    </tr>';

            // Traverse each result entry within this index
            foreach ($results as $result) {
                $html .= '<tr>';
                $html .= '<td>' . $result['Date'] . '</td>';
                $html .= '<td>' . $result['Total Question'] . '</td>';
                $html .= '<td>' . $result['Correct'] . '</td>';
                $html .= '<td>' . $result['Wrong'] . '</td>';
                $html .= '<td>' . $result['Score'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table><br>';
        }
    

    // Load the HTML content into Dompdf
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the PDF (convert HTML to PDF)
    $dompdf->render();

    // Send the generated PDF to the browser for download
    $file_name = $name . "-" . $test_name . "-report.pdf";
    $dompdf->stream($file_name, array("Attachment" => true));
}
?>
