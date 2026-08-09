<section id="page-content">
                       
<div class="inner-body">
  <div class="container">
    <div class="col-md-12 pagebody-con">
	   <div class="midle-conarea mr-t88" style='min-height: 400px'>
		   <div class='col-md-12'><h2><?php echo Ucwords($db_value['title']);?></h2></div>
			<div class='col-md-12'>
			<?php 
			 if($db_value['img'])
			 {
			  echo'<img class="cust-img2"  align="right" src="'. $configs['vpath'].'image.php?src=' .$configs['vpath'].$db_value['img'] .'&w=400" alt="about-imge">';
			 }    
			 echo html_entity_decode(html_entity_decode(html_entity_decode($db_value['contents'])));
			 ?></div>
		 
		  
	   </div>
    </div>
  </div>
</div>
<section>
