<?php
include("includes/access.php");
include("includes/header.php");
?>
<div class="wrapper">
		<!-- BEGIN LEFTSIDE -->
        <?php include("includes/left.php");?>
		<!-- BEGIN RIGHTSIDE -->
        <div class="rightside bg-grey-100">
		<!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
			<h1 class="page-title">City Managment</h1>
		</div>
		<!-- END PAGE HEADING -->
        <div class="container-fluid">
         <div class="panel-body no-padding-top bg-white">
		<h4 class="color-grey-700">City List</h4>
		<div class="table-responsive">
<?		
 /************************Delete***************************************************/	
if($_GET['del']):
//echo "delete from " .$prev."city where city_id=" . $_GET['city_id'];
//die();
  mysqli_query($con,"DELETE FROM " .$prev."city WHERE city_id=" . $_GET['city_id']);
  $_SESSION['succ_message']="City Deleted Successfully !!";
  pageRedirect('city.list.php?menuid=116&menupid=113');
  exit();
endif;
/***********************End Delete**************************/

if(isset($_REQUEST['status'])){
	   $update=mysqli_query($con,"UPDATE ".$prev."city SET status='".$_REQUEST['status']."' WHERE city_id=".$_REQUEST['city_id']);	
	}

 /************************Search***************************************************/	
  $sortby='';$cond=array();$parama="";

  
	if($_POST[sortby])
	{
	   if($_POST[sortby]=="Y"){$cond[]=" status='Y'";}	
	   if($_POST[sortby]=="N"){$cond[]=" status='N'";}	
	   if($_POST[sortby]=="rasc"){$sortby=" ORDER BY city_name ASC";}
	   if($_POST[sortby]=="rdesc"){$sortby=" ORDER BY city_name DESC";}
	 // $parama.="&shortby=".$_POST[shortby];
	}
/***********************End Search**************************/


	if(count($cond))
	{
		if($_POST['search'])
		{
			$conds="and ".implode(" and ",$cond);

		}
		else
		{
			$conds="and ". implode(" and ",$cond);
			//$parama="&".implode("&",$cond);
		}
	}
	
	/*************************Main Query******************************/	

  $sql="SELECT * FROM ".$prev."city WHERe 1 ".$conds." ".$sortby;
  $resut=mysqli_query($con,$sql);
  $totalrow=mysqli_num_rows($resut);
?>
<!------------------------Multiple Checkbox---------------->
<script type="text/javascript">
checked=false;
function checkedAll (formListContainer) {
	
 var aa= document.getElementById('formListContainer');
  if (checked == false)
          {
           checked = true;
		   $("#but_delete").css("display", "block");
          }
        else
          {
          checked = false;
		   $("#but_delete").css("display", "none");
          }
 for (var i =0; i < aa.elements.length; i++) 
 {
  aa.elements[i].checked = checked;
 }
}
function del()
{	
	if(confirm('Are you sure you want to delete all Cities?')==true)
	{
		document.formListContainer.method="post";
		document.formListContainer.action="delete.php?city_id=city_id";
		document.formListContainer.submit();
	}
}
</script>


<script type="text/javascript">
//jQuery.noConflict();
$(document).ready( function () {
	//alert('asd');
   $('#tableListContent').dataTable( {
  "sPaginationType": "full_numbers",
    "aoColumns": [
                 { "bSortable": false },
                 { "bSortable": false },
                null,
                  null,
                 null,
				 null,
				 { "bSortable": false },
				  { "bSortable": false },
				   { "bSortable": false },
							
            ],
			
		"iDisplayLength": 50,
        "aLengthMenu": [
        [50,100,300,-1],
		[50,100,300,'All']
		]
			
 } );
 
$('.checkbox_id').click(function(){

	    var checkbox_id=$(this).attr("id");

	if($('#'+checkbox_id).is(':checked')==true){
		$("#but_delete").css("display", "block");
		
	}
	else
	{
		$("#but_delete").css("display", "none");
	}
	
	
	}); 
} );
</script>
<?php if(isset($_SESSION['succ_message'])){?>
 <strong style="color:#006600;"><?php echo $_SESSION['succ_message']; unset($_SESSION["succ_message"]);?><br></strong>
<?php }if(isset($_SESSION['err_message'])){?>
<strong style="color:#900;"><?php echo $_SESSION['err_message']; unset($_SESSION["err_message"]);?><br></strong>
<?php }?>
 
<form name="restaurantmanage" method="post" action="city.list.php" />
 <table width=100%><tr><td>Sort By</td><td>
			<select class="form-control" name="sortby" id="sortby" size="1" onchange="document.restaurantmanage.submit();">
				<option value="">Select</option>
				<optgroup label="Status">
                   <option value="Y" <?php if($_POST[sortby]=="Y"){echo "selected";}?>>&nbsp;&nbsp;Active</option>
                   <option value="N" <?php if($_POST[sortby]=="N"){echo "selected";}?>>&nbsp;&nbsp;Deactiv</option>
				</optgroup>
				<optgroup label="Others">
                    <option value="rasc" <?php if($_POST[sortby]=="rasc"){echo "selected";}?>>&nbsp;&nbsp;City Name A to Z</option>
			        <option value="rdesc" <?php if($_POST[sortby]=="rdesc"){echo "selected";}?>>&nbsp;&nbsp;City Name Z to A</option>
				</optgroup>
			</select>
			</td><td align=right>
			<a class="btn btn-success" href="city.entryform.php">Add New</a>
			<!--
		 		<input type="button" class="btn btn-success" href="city.entryform.php'" value='Add New'>
				<input type="button"  class="btn btn-info" value="Activate" onclick="adminActivateDeactivate('1','status','maincateid','rt_category_main','Main Category');"  />
				<input type="button" class="btn btn-alert" value="Deactivate"   onclick="adminActivateDeactivate('0','status','maincateid','rt_category_main','Main Category');" />
				<input type="button" class="btn btn-danger" value="Delete"   onclick="adminActivateDeactivate('delete','deletefield','maincateid','rt_category_main','Main Category','category');" />
				-->
			    </td></tr></table></form><br>
				 
                <form name="formListContainer" id="formListContainer" action="">
				<table id="table-1" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="1" cellpadding="4">
                <thead>
				<tr  >
					<td width="3%" align="center" ><input type="checkbox" onclick="checkedAll(formListContainer)" /></td>
					<td width="7%" align="center" ><a onclick="sortByAscDesc('zdesc','','');" href="javascript:void(0);">S.No</a></td>
					<td width="10%" align="left" >
						<a onclick="sortByAscDesc('zdesc','','');" href="javascript:void(0);">City Name						</a>
				  </td>
                  	<td width="20%" align="left" ><a onclick="sortByAscDesc('zdesc','','');" href="javascript:void(0);">City ID</a></td>
					<td width="10%" align="left" >
						<a onclick="sortByAscDesc('sdesc','','');" href="javascript:void(0);">State Name						</a>
				  </td>				
					<!--<td width="40%" align="left" >
						<a onclick="sortByAscDesc('adesc','','');" href="javascript:void(0);">Area Name						</a>
				  </td>-->
					<td width="10%" align="center" ><a onclick="sortByAscDesc('zdesc','','');" href="javascript:void(0);">State Code</a></td>
					<td width="15%" align="center" ><a onclick="sortByAscDesc('zdesc','','');" href="javascript:void(0);">Added Date</a></td>
					<td width="5%" align="center" >Status</td>
					<td width="10%" align="center" >Action</td>
				</tr>
                </thead>
                <tbody>
                <?php if($totalrow){
							$i=1;
							while($row=mysqli_fetch_object($resut)){
							?>
					<tr  >
					<td align="center" ><span class="listContResta">
					  <input type='checkbox' name="chk[]" id="checkbox_id_<?php echo $row->city_id ?>" class="checkbox_id"  value="<?php echo $row->city_id?>"/>
					</span></td>
					<td align="center" ><?php echo $i?></td>
					
                    <td align="left" ><?php echo $row->city_name?></td>
					<td align="left" ><?php echo $row->city_id?></td>
					<!--<td align="left" ><?//=$row->area_name?></td>-->
                    <td align="left" ><?php echo getstatenameById($row->state_id)?></td>
					<td align="center" ><?php echo getstatecodeById($row->state_id)?></td>
					<td align="center" ><?php echo date("M j, Y",strtotime($row->cur_date)); ?></td>
					
					<td align="center"   >
					<?php if($row->status=='Y'){?>
                     <a href="city.list.php?city_id=<?php echo $row->city_id?>&status=N">
                        <img src='images/icon_active.png' alt='Active' title='Active' style="cursor:pointer;"  />
                    </a>
                    <?php }else{?>
                    <a href="city.list.php?city_id=<?php echo $row->city_id?>&status=Y">
                   <img src='images/delete.png' alt='Inactive' title='Inactive' style="cursor:pointer;"  />
                    </a>
                    <?php }?>
					</td>
					
					<td align="center" >
						<span class="EditDeleteButton">
							<a href="city.entryform.php?city_id=<?php echo $row->city_id; ?>">
								<img width="16" height="16" title="Edit" alt="Edit" src="images/icon_edit.png">
							</a>
						</span>
						<span class="EditDeleteButton">
                            <a href="<?php echo $_SERVER['PHP_SELF']?>?city_id=<?php echo $row->city_id?>&del=1" class='lnk'  onclick="return confirm('Are you sure you want to delete this record?')">
							<img src="images/icon_delete.png" width="14" height="14" alt="Delete" title="Delete"  border="0" style="cursor:pointer;" />
                            </a>
                        </span>
					</td>
				</tr>
												
                 <?php
						      $i++; } 
							} else {?>
                       <tr >
						  <td colspan="9" align="center" >No record found</td>
					  </tr>
                            <?php }?>
							</tbody></table>
  
    			</form>
				<!--List End-->
				<!--Pagination start-->
				
				<!--Pagination End-->
				<div class="clr"></div>
				<!--Button List start-->
				<!--
				<div style='text-align:center'>
					<input type="button" class="btn btn-success" href="city.entryform.php'" value='Add New'>
					<input type="button"  class="btn btn-info" value="Activate" onclick="adminActivateDeactivate('1','status','maincateid','rt_category_main','Main Category');"  />
					<input type="button" class="btn btn-alert" value="Deactivate"   onclick="adminActivateDeactivate('0','status','maincateid','rt_category_main','Main Category');" />
					<input type="button" class="btn btn-danger" value="Delete"   onclick="adminActivateDeactivate('delete','deletefield','maincateid','rt_category_main','Main Category','category');" />
				</div>
				-->
				<!--Button List End-->
		 
<script cat_id="text/javascript">
//<![CDATA[
function addClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var l = p.length;
	for (var i = 0; i < l; i++) {
		if (p[i] == sClassName)
			return;
	}
	p[p.length] = sClassName;
	el.className = p.join(" ");

}

function removeClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var np = [];
	var l = p.length;
	var j = 0;
	for (var i = 0; i < l; i++) {
		if (p[i] != sClassName)
			np[j++] = p[i];
	}
	el.className = np.join(" ");
}
var st = new SortableTable(document.getElementById("table-1"),
	["Number","None","String","String","Number","date","date","String","None"]);
	// restore the class names
st.onsort = function () {
	var rows = st.tBody.rows;
	var l = rows.length;
	for (var i = 0; i < l; i++) {
		removeClassName(rows[i], i % 2 ? "odd" : "even");
		addClassName(rows[i], i % 2 ? "even" : "odd");
	}
};
//]]>
</script>

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