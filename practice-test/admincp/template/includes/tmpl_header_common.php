<div class="wrapper">
<!-- BEGIN LEFTSIDE -->
<?php include("includes/left.php");?>
<!-- BEGIN RIGHTSIDE -->
<div class="rightside bg-grey-100">
<!-- BEGIN PAGE HEADING -->
<div class="page-head bg-grey-100">
	<h1 class="page-title"><?php echo  breadcrum($breadcrum)?></h1>
	<div class='pull-right' style='padding-right:30px'><?if(isset($btn)){echo  $btn;}?></div>
</div>
<!-- END PAGE HEADING -->
<div class="container-fluid">
 <div class="panel-body no-padding-top bg-white">
<h4 class="color-grey-700" style='padding-bottom:10px'><?php echo $page_title?> 
<?if(isset($msg)){echo " > <span class='blinking'>" .$msg."</span>";}?>
</h4>
<div class="table-responsive">