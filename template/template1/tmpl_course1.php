<div class="page-title-area bg-25 mt-110">
<div class="container">
<div class="page-title-content">
<h2><?=strtoupper($db_value['header'])?></h2>
 <h4><?=strtoupper($db_value['tagline'])?>
 <?
 if($_GET['location'])
 {
      echo" in <b>"  . ucwords(str_replace("-"," ",$_GET['location'])). "</b>";
 }
?>    
 </h4>
</div>
</div>
</div>


<section class="single-course-area ptb-70">
<div class="container">
<div class="row">
<div class="col-lg-8">

<div class="tab single-course-tab">
<ul class="tabs">
<li>
<a href="#">Overview of <?=strtoupper($_GET['seo_url'])?></a>
</li>
<li>
<a href="#">Curriculum</a>
</li>
<li>
<a href="#"> Course Video</a>
</li>
<li>
<a href="#">Reviews</a>
</li>
</ul>
<div class="tab_content">
<div class="tabs_item">

<div class="single-course-content">

<script src="https://cdn.plyr.io/3.6.12/plyr.js"></script>
							<link rel="stylesheet" href="https://cdn.plyr.io/3.6.12/plyr.css" />
								<style class="INLINE_PEN_STYLESHEET_ID">
							    .player-container {
								margin: 50px auto;
								max-width: 1080px;
							}
							</style>
							
							<div class="player-container">
							    <video id="player" playsinline controls data-poster="https://ocs.asvab-tutoring.online/" >
							      
							  	  <source src="<?=$configs['vpath'] .$db_value['picture']?>" type="video/mp4" type="video/mp4" size="796" />
							  	  							  	  	
							        							  	  
						 	    </video> 
						    </div>
						    <script>
							const player = new Plyr('#player');
							</script>
							<script src="https://cdn.plyr.io/3.6.12/demo.js" crossorigin="anonymous"></script> 

	
<!--<img src="<?=$configs['vpath']."image.php?image=" .$configs['vpath'] .$db_value['picture']?>&w=796&h=491" alt="<?=$db_value['name'];?>">-->
</div>

<h3>Course Description</h3>
<div class="table-responsive course-table">
<?=html_entity_decode($db_value["details"]);?>
</div>
<!--
<p align=justify>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has svived not only five centuries, but also the leap into electronic typesetting, remaining essentially.</p>
<h3>Certification Online</h3>
<p align=justify>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
<h3>What you’ll learn?</h3>
<ul class="course-list">
 
<li>
<i class="bx bx-check"></i>
Full topic review including verbal analogies, word knowledge, block counting, instrument comprehension, table reading, general science, hidden figures, aviation information, rotated blocks, self-description inventory, and math knowledge

</li>
<li>
<i class="bx bx-check"></i>
27 flashcard decks and 3 full-length AFOQT practice tests, for test prep experience you won’t find anywhere else

</li>
<li>
<i class="bx bx-check"></i>
Explanation of every type of question you will encounter, with examples of each type of question

</li>
<li>
<i class="bx bx-check"></i>
Comprehensive information about the exam itself, including the number of questions and time allotted for each section in the test

</li>
<li>
<i class="bx bx-check"></i>
Every answer, right and wrong, is displayed with a detailed explanation of why it is right or wrong, giving you the most complete understanding of all the AFOQT topics

</li>
<li>
<i class="bx bx-check"></i>
Detailed information on how the AFOQT is scored, how composite scoring is calculated, and an explanation of how the information is used by the Armed Forces

</li>
</ul>
<h3>Who This Course is for</h3>
<p align=justify>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
-->
	
</div>
<div class="tabs_item">
<div class="curriculum-content">
<h3>Curriculum Summery</h3>
<div class="curriculum-list">
	<?=html_entity_decode($db_value["curriculum"]);?>
<!--<h4>General Science (GS)</h4>
 
<ul><li><i class="bx bx-right-arrow"></i> Knowledge of physical and biological sciences.</li><ul>

<h4>Arithmetic Reasoning (AR)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Ability to solve basic arithmetic word problems.</li><ul>


<h4>Word Knowledge (WK)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Ability to identify the best synonym for a given word.</li><ul>

<h4>Paragraph Comprehension (PC)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Ability to obtain information from written passages.</li><ul>

<h4>Mathematics Knowledge (MK)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Knowledge of high school mathematics principles.</li><ul>

<h4>Electronics Information (EI)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Knowledge of electricity and electronics.</li><ul>

<h4>Auto Information (AI)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Knowledge of automobile technology.</li><ul>

<h4>Shop Information (SI)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Knowledge of tools and shop terminology and practices.</li><ul>

<h4>Mechanical Comprehension (MC)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Knowledge of mechanical and physicial principles.</li><ul>

<h4>Assembling Objects (AO)</h4>
<ul><li><i class="bx bx-right-arrow"></i> Ability to determine how an object will look when its parts are put together.</li><ul>
-->
</div>
 
</div>
</div>
<div class="tabs_item">
<div class="instructor-content">
<div class="row align-items-center">
<?
if($db_value['video'])
{
	$videos=explode(",",$db_value['video']);
	for($i=0;$i<count($videos);$i++)
	{
		?>
		<div class="col-lg-12"><iframe width="790" height="415" src="<?=$videos[$i]?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>

	    <div class='clearfix'><br></div>
		<?
	}	
}	
?>
</div>
</div>
</div>
<div class="tabs_item">
<div class="review-content">
<h3>Course rating</h3>
<ul class="rating-star">
<li>
<i class='bx bxs-star'></i>
</li>
<li>
<i class='bx bxs-star'></i>
</li>
<li>
<i class='bx bxs-star'></i>
</li>
<li>
<i class='bx bxs-star'></i>
</li>
<li>
<i class='bx bxs-star'></i>
</li>
</ul>
<span>5.00 average based on 1 rating</span>
 
<div class="course-reviews-content">
<h3>Reviews</h3>
<ul class="course-reviews">
<?
foreach($feedback as $d)
{
	if(!$d['pic']) 
	{
		$d['pic']="img/noimage.png";
	}
	?>
	<li>
	<img src="<?=$configs['vpath']?>image.php?image=<?=$configs['vpath'].$d['pic']?>&w=70&h=70" alt="<?=$d['name']?>" width=70 height=70 class=fimage>
	<h3><?=$d['name']?></h3>
	 
	<p class='feedback' align=justify><?=html_entity_decode($d['feedback']);?></p>
	<div class='clearfix'><br></div>
	</li>
	<?
}	
?>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-lg-4">
<div class='col-md-12 left_header text-center'><h3>SUBSCRIBE <?=strtoupper($_GET['seo_url']);?> TEST</h3></div>
<div class="account-wrap">
<ul>
<li>1 Month Subscription <span class="bold">$<?=number_format($configs['1month'],2);?></span></li>
<li>3 Months Subscription <span class="bold">$<?=number_format($configs['3month'],2)?></span></li>
<li>6 Months Subscription <span class="bold">$<?=number_format($configs['6month'],2)?></span></li>
<li>12 Months Subscription <span class="bold">$<?=number_format($configs['12month'],2)?></span></li>
<!--<li>90 Minutes Live Online Tutoring<span><img src="<?=$configs['vpath']?>img/on.png"></span>
</li>
<li>
Essay Review<span><img src="<?=$configs['vpath']?>img/on.png"></span>
</li>
<li>
Personalized Onboarding <span><img src="<?=$configs['vpath']?>img/on.png"></span>
</li>-->
 
</ul>
<a href="<?=$configs['vpath']?>register/<?=$_GET['seo_url']?>" class="default-btn">Subscribe Now</a>
 
</div>

<div class='clrarfix'><br></div>
<div class="account-wrap">
<img src='<?=$configs['vpath']?>img/best.jpg'>
</div>
<div class='clrarfix'><br></div>
<div class="account-wrap">
 
</div>
</div>
</div>
</div>
</div>
</section>
<?
if($total_faq)
{?>
	<section class="faq-area">
	<div class="container">
	<div class="section-title">
	 
	<h2>Frequently asked questions</h2>
	</div>
	<div class="faq-accordion">
	<ul class="accordion">
	<?
	$i=0;
	foreach($faqs as $d)
	{	
        $show="";
        if(!$i){$show="show";} 
		echo'<li class="accordion-item">
		<a class="accordion-title active" href="javascript:void(0)">
		<i class="bx bx-plus"></i>'
		.$d['question'] .'
		</a>
		<div class="accordion-content ' . $show .'">' .
		html_entity_decode($d['answers']) .'</div>
		</li>';
		$i++;
	}
	?>
	</ul>
	</div>
	</div>
	</section> 
<?}?>
<section class="courses-area-style ptb-100">
<div class="container">
<div class="section-title">
<h2>Related Courses
 
<?
 if($_GET['location'])
 {
      echo" <b>"  . ucwords($_GET['location']) . "</b>";
 }
?>    
     
</h2>
</div>
<div class="row">
<?
foreach($similar_courses as $c)
{?>	
	<div class="col-lg-4 col-md-6">
		<div class="single-course">
		<a href="<?=$configs['vpath']?>course/<?=$c['seo_url']?>"><img src="<?=$configs['vpath']?>image.php?image=<?=$configs['vpath'].$c['thumb']?>&w=386&h=187" alt="<?=$c['name'];?>" class='simage'></a>
		<div class="course-content">
		 <a href="<?=$configs['vpath']?>course/<?=$c['seo_url']?>">
		<h3><?=$c['name']?></h3>
		</a>
		<ul class="rating">
		<li>
		<i class="bx bxs-star"></i>
		</li>
		<li>
		<i class="bx bxs-star"></i>
		</li>
		<li>
		<i class="bx bxs-star"></i>
		</li>
		<li>
		<i class="bx bxs-star"></i>
		</li>
		<li>
		<i class="bx bxs-star"></i>
		</li>
		<li>
		<span>0.5</span>
		<a href="#">(5 rating)</a>
		</li>
		</ul>
		<p><?=$c['meta_desc']?></p>
		<div class='text-center'><a href="<?=$configs['vpath']?>register/<?=$c['seo_url']?>" class="default-btn text-center">Subscribe Now</a></div>
		
		</div>
		
		</div>
		</div>
		

<?
}
?>

</div>
</div>
</section>
