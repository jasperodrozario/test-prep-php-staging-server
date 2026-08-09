<form method='post' action="<?php echo $_SERVER['PHP_SELF']?>" name='ed'>
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="5" class="table table-striped">
<tr><td>
<!-- <select name='city_id' size=1 class='form-control' onChange='this.form.submit()'>
<option value=''>Member By City</option>
<?//php echo getDropdown($prev . "city","country_id=1");?>
</select>	 -->
</td><td  style='border-left:solid 1px #eee'> 
<select name='param' size=1 class='form-control'>
<option value=''>Search By</option>
<option value='<?php echo $prev;?>login.user_id'>Id</option>
<option value='<?php echo $prev;?>member.nickname'> Name</option>
<option value='<?php echo $prev;?>login.email'>Email</option>
<option value='<?php echo $prev;?>member.mobile'>Status</option>
</select></td><td><input type='text' name='key' class='form-control'></td><td><input type='submit' value='Searh' name='search' class='btn btn-success'></td>
</tr></table>
<table id="table-1" width="100%" class="table table-bordered" border="0" align="center" cellspacing="1" cellpadding="4">
<thead>
<tr><td valign=top width=10%><b>User Id</b></td>

<td valign=top width=15%><b> Name </b></td>
<td valign=top width=20%><b>Email</b></td>

<td valign=top width=20%><b>Course Enrolled</b></td>

<td valign=top align=center><b>Status</b></td>
<td valign=top align=center width=15%><b>Reg Date</b></td>

<td align=center valign=top ><strong>Action </strong></td></thead>

<tbody>
<?php

if($_GET['delte']){
	echo $rr;//die;
}

$num_records_per_page = 25;
$offset = 0;
if($_GET['limit']){
	$offset = ($_GET['limit'] - 1) * $num_records_per_page;
}
$r=mysqli_query($con,"select * from " . $prev . "login");

 
$total=@mysqli_num_rows($r);
if(!$total):
   echo"<tr><td colspan='7' align='center'><span class='lnkred'>No Escort found.</span></td></tr>";
endif;
 

$j=0;
while($d=@mysqli_fetch_array($r)):
    if($d['free_member_active']!='Y'){$status="<font color=red>Inactive</font>";}else{$status="<font color=green>Active</font>";}
 
    if($d['status']=='Y')
	{
		//$status="Active";
		$d['status']="<img src='assets/img/on.png'>";
	}
	else
	{
		//$status="Inactive";
		$d['status']="<img src='assets/img/off.png'>";
	}
	$course_enrolled=@getTotalRow("course_user",array("user_id"=>$d['user_id']));
	echo"<tr ><td>" . $d['user_id'] . "</td>
	
  
   <td >" . $d['fname'] . " " . $d['lname'] . "</td>   <td >" . $d['email'] . "</td>
   <td >" . $course_enrolled . "</td>
   <td align=center>" . $d['status'] . "</td>
   <td align=center>" . $d['reg_date'] . "</td>
   
   
   <td  align=center width=25%><a href='edu_member.regform.php?user_id=" .$d['user_id'] ."'  ><img src='images/edit.png' alt='Edit' width='22' height='24' border='0'> | <a href='course_enrolled_update.php?user_id=" .$d['user_id'] ."'  >Updated Course</a> 
   <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='" . $_SERVER['PHP_SELF'] . "?delete_id=" . $d['user_id']."';}\"><img src='images/delete.png' alt='Delete' width='22' height='24' border='0'></a> 

    </tr>\n";
   $j++;
endwhile;
?></tbody></table>

<?php if($total > $num_records_per_page): ?>
<table width="100%" border="0" align="center" HEIGHT="25" cellspacing="0" cellpadding="4">
	<tr ><td align="center" ><?=paging($total, $num_records_per_page, $param, "btn")?></td></tr>
</table>
<? endif;?></form>
 