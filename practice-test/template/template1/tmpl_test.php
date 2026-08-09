<!--courses section-->
  <div class="userprofile_wrapper">
      <div class="container">
        
        <div class="userprofile-body midle-conarea mr-t88">
        <div class="col-lg-3 col-md-3 col-sm-3 cust-side">
           
        <?
        include("../template/" .$configs['template'] ."/includes/tmpl_left_exam.php");
        ?>
        </div>
            
        <div class="col-lg-9 col-md-9 col-sm-12">
          	<!-- <div class="prediction-overly">
          	 <p>You do not have access in it. <a href='<?=$configs['vpath']?>contact'>Contact to your tutor</a></p>
          	</div> -->
        <div class="panel dashbord-panel with-nav-tabs panel-default border-none">
            <div id="myHeader" class="panel-heading">
            <?
            if($_GET['id'])
            {
                echo $db_value['name'];
            }
            ?>
                </div>
                <div class="panel-body div-scroll">
                <!-----Start dashbard box-->
			    <?  
            
					$p="";
					 
		            if($lesson_total)
		            {
						foreach($lesson as $c)
						{
							?><div class="col-sm-6 col-lg-4">
				                <div class="card desh-top-item bg-flat-color-5 download-box">
				                    <div class="card-body pb-0">
				                      <div class="col-md-12 text-center">
				                        <div class="icon-image">
				                        
											<a href='<?=$configs['vpath']."lesson/". $c['cat_id']."-".$c['lesson_id'] ."-" . seo_url($c['name']);?>'> <img class="img-responsive" src="<?=$configs['vpath']?>img/lesson.png" /></a>
										 
				                        </div>
				                        </div>
				                         
										 <p class="text-light"><a class='ocust-text' href='<?=$configs['vpath']."lesson/". $c['cat_id']."-".$c['lesson_id'] ."-" . seo_url($c['name']);?>'><?=$c['name'];?></a></p>            
										 
				                        
				                        </div>
				                
				                </div>
				            </div>
				            <?
						}
						
					}
					 
		 	
		           ?>
        </div>
           
            </div>
                </div>

            </div>
           
            </div>
            
           
            <div id="sclfooter"></div>   
      </div>    
            
    </div>
<?/*?>   
<script>
	function showGraph()
        {
            {
                $.post("<?=$configs['vpath']."chart-data/".$_GET['cat_id']."/".(int)$_GET['lesson_id'];?>",
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
                                label: '<?=$lesson_name;?> Score',
                                backgroundColor: '#49e2ff',
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: score
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata,
                        
                    });
                });
            }
        }
        
 
</script><?*/?>
<script>
function showDiv(x) 
{
  var x = document.getElementById("ansdiv_"+x);
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script> 

<style>
.ui-tabs .ui-tabs-panel {
    display: block;
    border-width: 0px;
    padding: 0em 0em!important; 
    background: none;
}	
.ui-tabs .ui-tabs-nav {
    margin: 0;
    padding: 0 0 0!important;
}
.midle-conarea ul {
    padding-left: 0px!important;  
    list-style-type: square!important;
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
    border-bottom-right-radius: 0px!important;  
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
    border-bottom-left-radius: 0px;
}
.ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
    border-top-right-radius: 0px!important;  
}
.ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
    border-top-left-radius: 0px!important;  
}

.ui-widget-header {
    border: 0px solid #dddddd!important;  
    background:#fff!important;  
    color: #333333;
    font-weight: bold;
}
.ui-widget.ui-widget-content {
    border: 0px solid #c5c5c5!important;
}

.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited, a.ui-button, a:link.ui-button, a:visited.ui-button, .ui-button {
    color: #454545;
    text-decoration: none;
    border-bottom: solid 1px silver!important;
}

.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
    border: 0px solid #003eff;
    background: #fcbd4f;
    font-weight: normal;
    color: #ffffff;
}
</style>
 	
 						