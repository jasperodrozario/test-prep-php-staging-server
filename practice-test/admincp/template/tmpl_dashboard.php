	<div class="row">
		<?
		$j=0;
		foreach($stats as $d=>$v)
		{
			$j++;
		   ?>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
				<div class="panel bg-teal-500">
					<div class="panel-body padding-15-20">
						<div class="clearfix">
						<a href="">
						    
							 
								<div class="display-block color-teal-50 font-weight-500"><a href="questions_list?cat_id=<?=$cat_ids[$j];?>" class="dash-link"><?=$d?></a></div>
							
								<div class="color-white pull-left font-size-26 font-roboto font-weight-400" data-toggle="counter" data-start="0" data-from="0" data-to="<?php echo (int)$v;?>" data-speed="500" data-refresh-interval="10"><a href="questions_list?cat_id=<?=$cat_ids[$j];?>" class="dash-link"><?php echo (int)$v;?></a></div>
							    <div class="pull-right">
							     <img src='images/stat<?=rand(1,6)?>.png' width=40>
							    </div>
							 
							
							</a>
						</div>
						 	
						 
					</div>
				</div><!-- /.panel -->
			</div><!-- /.col -->
			<?
		}				
		?>
</div> 	
                