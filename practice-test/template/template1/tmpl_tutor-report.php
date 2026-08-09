<p></p>
<script src="<?=$configs['template_path'];?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$configs['template_path'];?>js/Chart.min.js"></script> 

<style type="text/css">	
#chart-container {
    width: 660PX;
    height: auto;
}
</style>
<? 
 

if($_GET['cat_id']==2000)
{
	$cat_name="Overall";
	?>
	 
		<h3>Overall Progress Chart</h3><br> 
	    <div id="chart-container">
		<canvas id="graphCanvas" style='height:300px'></canvas>
	</div>
	<script>
	        $(document).ready(function () {
	            showGraph();
	        });

    </script>
 
 <?
 
}
else
{
    $category_name = getFldValueById("category","category_name",array("cat_id"=>$_GET['cat_id']));
    $pred_test_name = getFldValueById("","test_name",array("Predic_test_id"=>$_GET['cat_id']));
    if($category_name){
        $cat_name = $category_name;
    }
    else if($pred_test_name){
        $cat_name = $pred_test_name;
    }
    ?>

	<h3><?=$cat_name?> Progress Chart </h3><br> 
	    <div id="chart-container">
		<canvas id="graphCanvas" style='height:300px'></canvas>
	</div>
	<script>
    $(document).ready(function () {
        showGraph();
    });

    </script>
    <p><br></p>
    
	<?
	if($lesson_total)
	{
		foreach($lesson_list as $c)
		{
		    if($result_total[$c['lesson_id']])
			{
				echo"<p></p><div class='row'><div class='col-md-12'><h4>" . $db_value['category_name'] .">".$c['Lesson_name'] ." Score </h4></div></div>";
				echo'<br><div class="table-responsive"><table class="table table-bordered table-striped">';
				echo"<thead><th>Date</th><th>Total Question</th><th>Correct</th><th>Wrong</th><th>Score(%)</th><th width=20% style='text-align:right'>Option</th></thead>";
				echo"<tbody>";
				$i=0;
				foreach ($result[$c['lesson_id']] as $l) {
					$i++;
					echo"<tr><td>".showdate($l['result_date'])."</td><td>" . $l['total_questions']."</td><td>".$l['correct_answers']."</td><td>".($l['total_questions']-$l['correct_answers'])."</td>
					<td>".round($l['score'])."</td><td><a href='" . $configs['vpath'] ."tutor-view-report/".$_GET['student_id']."/result/" . $c['cat_id']."-".seo_url($db_value['category_name']) ."/" .$l['lesson_id'] ."/".$l['result_id']."' class='btn btn-info pull-right tab'>View</a></td></tr>";
					
				}
				echo"</tbody></table></div><br>";
			}	
			
		}
	}
	else if($pred_result_total)
	{	
		echo'<br><div class="table-responsive"><table class="table table-bordered table-striped">';
		echo"<thead><th>Date</th><th>Total Question</th><th>Correct</th><th>Wrong</th><th>Prediction Score(%)</th><th style='text-align:center'>View</th></thead>";
		echo"<tbody>";
			
		foreach($result as $c)
		{
			 
			 echo"<tr><td>".showdate($c['result_date'])."</td><td>" . $c['total_questions']."</td><td>".$c['correct_answers']."</td><td>".($c['total_questions']-$c['correct_answers'])."</td><td>".round($c['score'])."</td><td style='text-align:center'><a href='" . $configs['vpath'] ."tutor-view-report/".$_GET['student_id']."/view/".$_GET['cat_id']."-".seo_url($_GET['cat_name']) ."/0/".$c['result_id']."' class='btn btn-info'>View</a></td></tr>";
			 
		}
		 
		echo"</tbody></table></div><br>";
	}	
	
}
?>

<script>
function showGraph()
{
    {
        $.post("<?=$configs['vpath'];?>tutor-chart-data/<?=$_GET['student_id']?>/<?=$_GET['cat_id']?>/0",
        function (score_data)
        {
            console.log(score_data);
            var date = [];
            var score = [];

            for (var i in score_data) {
                date.push(score_data[i].date);
                score.push(score_data[i].score);
            }
 
            var chartdata = {
                labels: date,
                datasets: [
                    {
                        
                        label: 'Avarage Score',
                        backgroundColor: '#49e2ff',
                        borderColor: '#ffaa00',
                        hoverBackgroundColor: '#ffaa00',
                        hoverBorderColor: '#666666',
                        data: score
                    }
                ]
            };

            var graphTarget = $("#graphCanvas");

            var barGraph = new Chart(graphTarget, {
                type: 'bar',
                data: chartdata,
                options: {
				    responsive: false,
				    scales: {
				     
				      yAxes: [{
				        ticks: {
				          min: 0,
				          max:100,
				          padding: 20,
				          stepSize: 10,
				        }
				      }]
				    }
				  }, 
                
            });
        });
    }
}
 
</script>
