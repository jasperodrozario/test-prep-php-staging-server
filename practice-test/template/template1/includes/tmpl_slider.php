<div class="banner_wrapper">
  <!--carousel section-->
<div id="demo1">
<?php 
$i=0; 
foreach($sliders as $b) 
{   
	$active="";
	if(!$i){$active="active";}
	?>
		<div class="slide <?=$active;?>">

		<img alt=fw_al_006_01 src="<?php echo $configs['vpath'].$b['slider'];?>">
		<!--Slider Description example-->
          	<div class="slide-desc">
            <h3><?php echo $b['title1']; ?></h3>
            <h2><?php echo $b['title2']; ?></h2>
            <p><?php echo $b['text']; ?> </p>
            <a class="cust-btn" href="<?php $configs['vpath'] . "info/about-us"; ?>">Read more</a>
    		</div>

  		</div>

    <?

    $i++;

    }
?>  
</div>
	<div class="free-classform">
                   <h3 class="title"><span>Enroll now for free live demo class</span></h3>
                   <div class="rgform">
                   <div id='contact_response'></div>
                      <form method="post" id="contactForm"> 
     
                         <div class="form-field">
                            <span><input type="text" name="name" id="name" placeholder="Your Name *" required></span>
                         </div>
                         <div class="form-field">
                            <span><input type="email" id="email" name="email" placeholder="Email *" required></span>
                         </div>
                         <div class="form-field">
                            <span><input type="text" name="phone" id="phone" placeholder="Mobile *" required></span>
                         </div>
                         <div class="form-field">
                        <span>
                        <select class="form-control" id="course_id" name="course_id">
                            <option>Select program</option>
                            <?
                            foreach($data as $d)
          					{
          						echo"<option value='" .$d['course_id'] ."'>".$d['name']."</option>";
          					}
          					?>
                             
                        </select>
                        </span>
                         </div>
                     
		                <div class="form-field">
		                    <button class="get-itnow" type='submit' name='submit' >Get It Now</button> 
		                </div>
		                      </form>
		                   </div>
		                </div>
</div>  
 