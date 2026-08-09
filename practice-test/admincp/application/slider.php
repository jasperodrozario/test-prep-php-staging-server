<?php 
include_once("includes/access.php");
include_once("includes/header.php");
 
if($_REQUEST['del_id'])
{
   $r=mysqli_query($con,"delete from " . $prev . "banner where id=" . $_REQUEST['del_id']); 
	 header("Location:".$vpath."admincp/slider.php");
}
?>
<div class="wrapper">
		<!-- BEGIN LEFTSIDE -->
        <?php include("includes/left.php");?>
		<!-- BEGIN RIGHTSIDE -->
        <div class="rightside bg-grey-100">
		<!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
			<h1 class="page-title">Slider Management</h1>
		</div>
		<!-- END PAGE HEADING -->
        <div class="container-fluid">
         <div class="panel-body no-padding-top bg-white">
		<h4 class="color-grey-700">Slider Page List</h4>
		<div class="table-responsive">
		
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">                                                                     
<thead>
<tr ><th width="7%"><b>ID</b></th><th><b>Image</b></th>
<th align="center"><b>Status</b></th>
<th  align="center" style='text-align:center'><b>Action</b></th></tr>
</thead><tbody>
<?
$r=mysqli_query($con,"select count(*) as total from " . $prev . "banner  order by id");
$total=@mysqli_result($r,0,"total");
if(!$total):
	echo"<tr><td class='lnkred' colspan='7' align='center'>No Record Found</td></tr>";
endif;
$r=mysqli_query($con,"select * from " . $prev . "banner  where status = 'Y' ");
//cho "select * from " . $prev . "contents where parent_id=0 " . $cond2 . " order by ord";
$j=0;$k=0;
while($d=@mysqli_fetch_array($r)):
	if(!($j%2)){$class="even";}else{$class="odd";}
    if($d[status]=="Y"){$status="Active";}else{$status="Offline";}
    echo"<tr ><td >" . $d[id] . "</td><td ><a href='slider_edit.php?id=" . $d[id] . "'><img style='width:60px;height:60px' src='" .$vpath. "". $d['banner']. "' ></a></td><td align='center'>" . $status . "</td><td align='center'> <i class='fa fa-pencil-square-o'></i> <a  href='slider_edit.php?id=" . $d['id'] . "'>Edit</a> | <i class='fa fa-times'></i> <a   href=\"javascript:if(confirm('Are you sure whish to delete?')){window.location.href='" . $_SERVER['PHP_SELF'] . "?del_id=" . $d['id'] . "';}\">Delete</a></td></tr>\n";
	
	$j++;
endwhile;
?>
</tbody>
</table>
<?
if($total>20):
?>
    <table  width="100%"  border="0" align="center"  cellspacing="0" cellpadding="0">
    <tr><td  align=right height=20 valign=middle><?php echo paging(20,$total,$_GET['limit']);?></td></tr></table>
<?
endif;
?>
</form>
</div>
</div>
<!-- BEGIN FOOTER -->
				<?php include("includes/footer-top.php");?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div><!-- /.rightside -->
    </div><!-- /.wrapper -->
	<!-- END CONTENT -->
	<?php include("includes/index_footer.php");?>