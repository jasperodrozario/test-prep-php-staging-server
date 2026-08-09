<div class="row">
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
	<div class="panel bg-teal-500">
		<div class="panel-body padding-15-20">
			<div class="clearfix">
			<a href="">
				<div class="pull-left">
					<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="<?php echo getTotalRow("login",array("user_type"=>'S'));?>" data-speed="500" data-refresh-interval="10"><?php echo getTotalRow("login",array("user_type"=>'S'));?></div>
					<div class="display-block color-teal-50 font-weight-600"><i class="ion-plus-round"></i> TOTAL Students</div>
				</div>
				<div class="pull-right">
					<i class="font-size-36 color-teal-100 ion-person-add"></i>
				</div>
				</a>
			</div>
			 	<div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
				<div class="progress-bar bg-blue-100" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
				</div>
			</div>
			 
		</div>
	</div><!-- /.panel -->
</div><!-- /.col -->
								

								
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
						<div class="panel bg-blue-400">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
									
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="<?php echo getTotalRow("course");?>" data-speed="500" data-refresh-interval="10">
										<?php echo getTotalRow("course");?>
										</div>
										<div class="display-block color-blue-50 font-weight-600">
										<i class="ion-plus-round"></i> Total Courses
										</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-blue-100 ion-ios-cart"></i>
									</div>
								</div>
								<div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-blue-100" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								 
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->


					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
						<div class="panel bg-red-400">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
								<a href="">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="<?php echo getTotalRow("enquiry");?>" data-speed="500" data-refresh-interval="10"><?php echo getTotalRow("enquiry");?></div>
										<div class="display-block color-red-50 font-weight-600"><i class="ion-plus-round"></i> Total Application</div>
									</div>

									<div class="pull-right">
										<i class="font-size-36 color-red-100 ion-email-unread"></i>
									</div>
									</a>
								</div>
								<div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-red-100" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								 
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
								
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
						<div class="panel bg-blue-grey-400">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="<?php echo getTotalRow("category");?>" data-speed="500" data-refresh-interval="10"><?php echo getTotalRow("category");?></div>
										<div class="display-block color-blue-grey-50 font-weight-600"><i class="ion-plus-round"></i> Category</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-blue-grey-100 ion-social-rss"></i>
									</div>
								</div>
								<div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-blue-grey-100" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
										 
									</div>
								</div>
								 
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
				</div><!-- /.row -->

                <div class="row" >
					<div class="col-lg-12"> 
						<div class="panel">
							<div class="panel-title no-border bg-white">
								<div class="panel-head"><i class="ion-arrow-graph-up-right"></i> Website Statistics</div>
								<div class="panel-tools">
									<a href="#" data-toggle="dropdown"><i class="ion-gear-a"></i></a>  
									<ul class="dropdown-menu pull-right margin-right-10">
										<li>
											<a href="#"><i class="ion-gear-a"></i> Settings </a>
										</li>
										<li>
											<a href="#"><i class="ion-ios-printer"></i> Print </a>
										</li>
										<li>
											<a href="#"><i class="ion-refresh"></i> Refresh </a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="#" class="clearfix"><span class="pull-left">New Visitors</span> <span class="label bg-teal-500 pull-right">3</span></a>
										</li>
										<li>
											<a href="#" class="clearfix"><span class="pull-left">Total</span> <span class="label bg-red-500 pull-right">2</span></a>
										</li>
                                    </ul>
									<a href="#" class="panel-refresh"><i class="ion-refresh"></i></a>
									<a href="#" class="panel-close"><i class="ion-close"></i></a>
								</div>
							</div>
							<div class="panel-body padding-top-5">
								<div class="row">
									<div class="col-lg-12">
										<div id="placeholder" class="flot-placeholder height-440"></div>
									</div>
								</div>
                            </div>
                        </div><!-- /.panel -->
					</div><!-- /.col -->
						
					 
				</div><!-- /.row -->
					
					
                