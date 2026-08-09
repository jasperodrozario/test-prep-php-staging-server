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
                  if($_GET['result'])
                  {
                  	if($_GET['result']=='final-test')
                  	{
						echo"Final Test";
					}
					elseif($_GET['result']=='prediction-test')
                  	{
						echo"AFQT Prediction Test";
					}
					else
					{
						echo"<a href='" . $configs['vpath'] ."exam/results'>Results</a> > ";
					}
                  
                  }
                  if($_GET['cat_id'])
                  {
                  	echo "<a href='" . $configs['vpath'] ."exam/" . $_GET['cat_id'] ."-" . seo_url($db_value['cat_name']) ."'>" .$db_value['cat_name'] ."</a> ";
                  	if($_GET['homework'])
                  	{
						echo "> Homework ";
                    }
					if($_GET['practice-test'])
					{
						echo " > Practice Test";
                    }
                  }
                  
                  if($_GET['set_no'])
                  {
                  	  " >  Set No " .$_GET['set_no'];
                    
                  }
                  ?>
                  </div>
                  <div class="panel-body div-scroll">
                  <!-----Start dashbard box-->
			       
            <?
            switch($_GET['result'])
            {
				case"result":
				    $score=($total_correct/$total)*100;
				    echo"<h4>Result for "  .$db_value['cat_name'] . "</h4>"; 
				    
				    echo'<br><table class="table table-bordered table-striped">';
				    echo"<tr><td>Subject</td><td colspan=3>".$db_value['cat_name'] ."</td></tr>";
				    echo"<tr><td>Lesson</td><td>".$lesson_name ."</td><td>Total Questions</td><td>".$total ."</td></tr>";
				    echo"<tr><td>Total Answerd</td><td>" . $total_answer_given ."</td><td>Total Correct Answer</td><td>".$total_correct ."</td></tr>
				    <tr><td>Wrong Answer</td><td>" . $total_wrong ."</td><td>% Score</td><td>". round($score,2)  ."</td></tr>";
				    echo"</table><br>";
				    echo"<h4>Question & Answer with clarification for your test on  "  .$db_value['cat_name'] . "</h4>";
				    echo"<hr>"; 
				    
				    echo'<table class="table table-borderless">';
				    $i=0 ;
				    foreach($question_test as $q)
				    {
					    $i++;
						echo'<tr><td width=5><span class="badge ">'.$i.'</span></td><td colspan=2>'.html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$q['question'])))."</td></tr>";
						$j=0;
						foreach($answers[$q['question_id']] as $a)
						{   $j++; 
							$ans=$alphabet[$j];
							if($my_answers[$q['question_id']]==$j)
							{
								$ans='<span class="badge badge-primary">'.$alphabet[$j].'</span>';
							}
							echo"<tr><td></td><td width=5>" . $ans."</td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
						}
						if(!$my_answers[$q['question_id']])
						{
							$text="<span class='badge_no'>No Answer</span>";
						}
						elseif($my_answers[$q['question_id']]==$q['answer_no'])
						{
							$text='<span class="badge badge-success">Correct</span>';
						}
						else
						{
							$text='<span class="badge badge-wrong">Wrong Answer</span>';
						}
						echo"<tr><td width=5></td><td class='text-align:left' colspan=2><b>Correct answer is  " . $alphabet[$q['answer_no']]." | Your answer is ".  $alphabet[$my_answers[$q['question_id']]]." | " . $text."</b></td></tr>";
						if($q['explanation']){echo"<tr><td width=5></td><td class='text-align:left' colspan=2 style='color:blue'>Explanation: " . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$q['explanation'])))."</td></tr>";}
						echo"<tr><td colspan=3><hr style='margin:0;'></td></tr>";
					}	
					echo"</table>";
				
				break;
				
				case"results":
				
				    echo"<h4>Results</h4>"; 
				    echo'<br><table class="table table-bordered table-striped">';
				    echo"<thead><th>Date</th><th>Subject</th><th>Lesson_name</th><th>View Details</th></thead>";
				    echo"<tbody>";
				    foreach($results as $d)
				    {
						$lesson_name=getFldValueById("lesson","name",array("lesson_id"=>$d['lesson_id']));
						echo"<tr><td>".showdate($d['date'])."</td><td>".$d['cat_name'] ."</td><td>" . $lesson_name."</td><td><a href='" . $configs['vpath'] ."exam/".$d['cat_id']."-".seo_url($lesson_name) ."/" . $d['lesson_id'] ."/result' class='btn btn-info'>View Score</a></td></tr>";
					   	
					}
				    echo"</tbody></table>";
				break;
				
				case"prediction":
				
				    echo"<h4>AFQT Prediction</h4>"; 
				    echo'<br><table class="table table-bordered table-striped">';
				    echo"<thead><th>Subject</th><th>Score</th></thead>";
				    echo"<tbody>";
				    $st=0;
				    
				    foreach($score as $d)
				    {
				
						$st+=$d['score'];
						echo"<tr><td>".$d['name']."</td><td>".(int)$d['score'] ."</td></tr>";
					   	
					}
					
					echo"<tr><td><b>Total Raw Score</b></td><td>".(int)$raw_score ."</td></tr>";
					echo"<tr><td><b>Your AFQT</b></td><td>".(int)$scaled_score ."</td></tr>";
				    echo"</tbody></table>";
				    
				break;
				
				case"prediction-test":
				    if($message)
				    {
						echo "<h3 style='margin-top:60px'>".$message."</h3>";
					} 
					else
					{
						
					
						?>
						<div style='padding:10px'> 
						<h4>You are requested to answer 59 Questions to get AFQT Predictions Score</h4><br>
						<p><span class='badge-success'><a href='<?=$configs['vpath']?>exam/prediction-test#211' style='color:#fff'>Arithmetic 16 Questions</a></span> <span class='badge-success'><a href='<?=$configs['vpath']?>exam/prediction-test#212' style='color:#fff'>Math Knowledge 16 Questions</a></span> <span class='badge-success'><a href='<?=$configs['vpath']?>exam/prediction-test#213' style='color:#fff'>Paragraph Comprehension 11 Questions</a></span> <span class='badge-success'><a href='<?=$configs['vpath']?>exam/prediction-test#214' style='color:#fff'>Word Knowledge 16 Questions</a></span> </p>
						</div>
						<form method='post' id='form'>
						    <div id="big-image" style='padding:10px'>
						<?
						$n=0;
						foreach($lesson as $c=>$v)
						{   $n++;
						    if($c==221)
							{
								 $limit=11;
							} 
							else
							{
								$limit=16;
							}
						    $i=0;
						    echo"<a name='" . $c ."'></a>
						    <div class='row'><div class='col-md-12 text-center'><h3 >" .$v .  "  <span class='badge badge-success'>" .$limit ." Questions</span></h3></div></div>
						    <div class='row'><div class='col-md-12'><hr></div></div>";
							foreach($question_test[$c] as $f)
	                 	    {
								$i++;
								$badge="";
								
								if($my_answers[$f['question_id']])
								{
									$badge="badge-success";
									
								}
								 
								//echo'<item>
								echo'<div id="response_'.$f['question_id'].'"></div>
								<table class="table table-borderless">
								<tr><td width=10><span class="badge '.$badge .'">'.$i.'</span></td><td colspan=3>'.html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['question'])))."</td></tr>";
								$j=0;
								
								foreach($answers[$f['question_id']] as $a)
								{   $j++; $q++;
								    $answer="";
								    $ans=$alphabet[$j];
								    if($my_answers[$f['question_id']]==$j)
								    {
										$answer="-answer"; 
										$ans='<span class="badge '.$answer .'">'.$alphabet[$j].'</a>';
										$answer_opt=str_replace("&#92;","\\",$a['answer']);
										echo"<tr><td width=10></td><td width=5>" . $ans."</td><td width=5><input type='radio' name='answer" . $f['question_id']."' id='" . $f['question_id']."' onClick=\"getFinalAnswer('" . $f['question_id']."','" . $f['lesson_id']."','" . $j ."');\" checked></td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
									}
									else
									{
										echo"<tr><td width=10></td><td width=5>" . $ans."</td><td width=5><input type='radio' name='answer" . $f['question_id']."' id='" . $f['question_id']."' onClick=\"getFinalAnswer('" . $f['question_id']."','" . $f['lesson_id']."','" . $j ."');\" ></td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
							
									}
								}
								echo"</table>";
								//echo"</item>" ;
							}
						} 
                 	   ?>
                 	  </div>
                 	  <div id='list'></div>
                 	  
					    <div class='row'>
					    	<div class="col-md-12 text-center total-qs social-float-parent"><div id="social-float"><div id='total'><center>There are <?=$total?> questions to complete.</center> </div></div></div>
						   	
					    </div> 
					 
				      </form>   
				    <? 
				    }
				break;
				default:
					$p="";
					if($_GET['cat_id']==70)
					{
						$p="final-test";
					}
		            if($lesson_total)
		            {
						foreach($lesson as $c)
						{
							?><div class="col-sm-6 col-lg-4">
				                <div class="card desh-top-item bg-flat-color-5 download-box">
				                    <div class="card-body pb-0">
				                      <div class="col-md-12 text-center">
				                        <div class="icon-image">
				                        <?
				                        if($_GET['cat_id']==70)
				                        {
										?>
				                        <a href='<?=$configs['vpath']."lesson/". $c['cat_id']."-".$c['lesson_id'] ."/".$p."/" . seo_url($c['name']);?>'> <img class="img-responsive" src="<?=$configs['vpath']?>img/lesson.png" /></a>
				                        <?
				                        }
				                        else
				                        {
											?>
											<a href='<?=$configs['vpath']."lesson/". $c['cat_id']."-".$c['lesson_id'] ."-" . seo_url($c['name']);?>'> <img class="img-responsive" src="<?=$configs['vpath']?>img/lesson.png" /></a>
											<?
										}
										?>
				                        </div>
				                        </div>
				                        <?
				                        if($_GET['cat_id']==70)
				                        {
											?>
										    <p class="text-light"><a class='ocust-text' href='<?=$configs['vpath']."lesson/". $c['cat_id']."-".$c['lesson_id'] ."/". $p ."/" . seo_url($c['name']);?>'><?=$c['name'];?></a></p>            
										    <?	
										}
										else
										{
										 ?>
										 <p class="text-light"><a class='ocust-text' href='<?=$configs['vpath']."lesson/". $c['cat_id']."-".$c['lesson_id'] ."-" . seo_url($c['name']);?>'><?=$c['name'];?></a></p>            
										 <?	
										}  
										?> 
				                        
				                    </div>
				                
				                </div>
				            </div>
				            <?
						}
						
					}
					elseif($_GET['cat_id']==18 && $_GET['homework'])
					{
						
						?>
						<div class="mob-tab-menu">
							<ul class="nav nav-tabs">
	                        <?
	                        for($i=1;$i<=$total_set;$i++)
							{   
							    if($_GET['set_no']==$i)
							    {
									echo"<li class='active'><a  href='".$configs['vpath']."exam/". $_GET['cat_id'] ."-" . seo_url($db_value['cat_name'])."/set-" .$i."'> Set " .$i."</a></li>";
						  		}
								else
								{
									echo"<li ><a  href='".$configs['vpath']."exam/". $_GET['cat_id'] ."-" . seo_url($db_value['cat_name'])."/set-" .$i."'> Set " .$i."</a></li>";
						  		
								}
							} 
							?>  
							</ul>
					    </div>

						<div class="tab-content">
			  			<div id="overview" class="tab-pane fade in active"> 
						<div class="flip-slider">
						<?
                 	     
						foreach($question_test as $f)
                 	    {
							
							echo'<div>
						    <div class="flip-box">
						    <div class="flip-box-inner">
							<div class="flip-box-front">'.html_entity_decode($f['question']) .'</div>';
							echo'<div class="flip-box-back">';
						 
							echo html_entity_decode($answers[$f['question_id']]);
							 
							echo"</div></div></div>";
							echo"</div>" ;
						}
						 
                 	   ?>
                 	   </div>
					   <?
					  
						
					}
					elseif($_GET['set_no'])
					{
						if($_GET['homework'])
						{
							
							?>
							<form method='post' id='form'>
						
							<div id="big-image">

	                 	    <?
	                 	    if($total)
	                 	    {   $i=1;
								foreach($homework_list as $f)
	                     	    {
									
									//echo'<item>
									echo'<table class="table table-borderless">
									<tr><td ><span class="badge '.$badge .'">'.$i.'</span></td><td >'.html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['question'])))."</td></tr>";
									$j=0;
									foreach($answers[$f['question_id']] as $a)
									{   $j++; 
									    $ans=$alphabet[$j];
									    if($my_answers[$f['question_id']]==$j)
									    {
											 $ans='<span class="badge '.$badge .'">'.$alphabet[$j].'</a>';
										}
										echo"<tr><td width=5>" . $ans."</td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
									}
									echo"</table>";
									echo"<a class='btn btn-info' href='javascript://'  onClick='showDiv(" . $i .");'>View Answer</a>";
									echo"<div class='col-md-12 mtq_explanation' id='ansdiv_" .$i."' style='margin-left:15px;display:none;1px solid #d6d6d6;background:left top repeat-y #f0f0f0;padding-top:10px;'><h4>Answer & Explanation:</h4><br><b>Answer : ". $alphabet[$f['answer_no']]."</b><br><br><b>Explanaion : </b><br><br>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['explanation']))) ."</div>";
								    echo"<div class='col-md-12'><hr></div>";
									//echo"</item>" ; 
								    $i++;
								}
							}
	                 	   ?>

	                 	   </div>
	                 	   
						    
	                       </form>
				 
							<?
							
						}
						else
						{
						
						?>
						
						<form method='post' id='form'>
						
						<div id="big-image">
						 
						<?
						$i=0;
						foreach($question_test as $f)
                 	    {
							$i++;
							$badge="";
							
							if($my_answers[$f['question_id']])
							{
								$badge="badge-success";
								
							}
							 
							//echo'<item>
							echo'<div id="response_'.$f['question_id'].'"></div>
							<table class="table table-borderless">
							<tr><td width=10><span class="badge '.$badge .'">'.$i.'</span></td><td colspan=3>'.html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$f['answer'])))."</td></tr>";
							$j=0;
							
							foreach($answers[$f['question_id']] as $a)
							{   $j++; 
							    $answer="";
							    $ans=$alphabet[$j];
							    if($my_answers[$f['question_id']]==$j)
							    {
									$answer="-answer"; 
									$ans='<span class="badge '.$answer .'">'.$alphabet[$j].'</a>';
									echo"<tr><td width=10></td><td width=5>" . $ans."</td><td width=5><input type='radio' name='answer" . $i."' id='" . $i."' onClick=\"getAnswer('" . $f['question_id']."','" . $j ."');\" checked></td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
								}
								else
								{
									echo"<tr><td width=10></td><td width=5>" . $ans."</td><td width=5><input type='radio' name='answer" . $i."' id='" . $i."' onClick=\"getAnswer('" . $f['question_id']."','" . $j ."');\" ></td><td class='text-align:left'>" . html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$a['answer']))) ."</td></tr>";
						
								}
							}
							echo"</table>";
							//echo"</item>" ;
						}
						 
                 	   ?>
                 	  </div>
                 	  <div id='list'></div>
                 	    <div class='row'>
					    	<div class="col-md-12 text-center" id='total'><center>There are <?=$total?> questions to complete.</center> <div id='pending'></div></div>
						   	
					    </div> 
					 
				      </form>   
				 
				      <?
				     }
				    }
				    else
				    {
					    if($total_setno_1)
					    {
						 
					    	?>
					    	<h3>Home Work</h3>
             	     		<div class='clearfix'><br></div>
             	     		<table class='table table-bordered'>
                 	    	<?
                 	    	foreach($setno_1 as $f)
                     		{
								echo"<tr><td width=20%><span class='badge'>Set " . $f['set_no']."</span></td><td width=50%> Total Questions " . $total_ques_1[$f['set_no_1']]."</td><td ><a href='" . $configs['vpath']."homework/" . $_GET['cat_id']."-".seo_url($db_value['cat_name'])."/set-".$f['set_no']."'>Study Homework</a></td></tr>";
							}
						  
							echo"</table>";
							 
						}
						
						if($total_setno_0)
					    {
						    ?>
					    	<h3>Practice Test</h3>
             	     		<div class='clearfix'><br></div>
             	     		<table class='table table-bordered table-striped'>
                 	    	<?
                 	    	//foreach($setno_0 as $f)
                     		//{
								echo"<tr><td width=20%><span class='badge'>Set 1</span></td><td width=50%><h4>Total Questions " . $total."</h4></td><td ><a href='" . $configs['vpath']."exam/" . $_GET['cat_id']."-".seo_url($db_value['cat_name'])."/".$set_no."' class='btn btn-success'>Take Test</a></td></tr>";
						//	}
						 
							echo"</table>";
							
						}	
						if($_GET['cat_id']==17)
						{
							?>
							<h3>Final Practice Test</h3>
             	     		<div class='clearfix'><br></div>
             	     		<table class='table table-bordered table-striped'>
                 	    	<?
                 	    	 echo"<tr><td width=20%><span class='badge'>Set 1</span></td><td width=50%> Total Questions 16</td><td ><a href='" . $configs['vpath']."exam/" . $_GET['cat_id']."-".seo_url($db_value['cat_name'])."/set-1'>Take Test</a></td></tr>";
							 
							 echo"</table>";
							 
						}	
					} 
				break;	
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

<!-- <script   type="text/javascript"   src="//code.jquery.com/jquery-1.6.4.js"></script> -->
						