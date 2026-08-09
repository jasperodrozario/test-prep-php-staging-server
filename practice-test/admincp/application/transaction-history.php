<?
include("includes/access.php");
include("includes/header.php");
if($_REQUEST['user_id']):
	$r=mysqli_query($conweb,"select * FROM ".$prev."register where user_id=" . $_REQUEST['user_id']);
	$d=mysqli_fetch_array($r);
	$data = getMemberPlan($_REQUEST['user_id']);
	$lead_count = activeLeadPage($_REQUEST['user_id']);
	$tran_amount = tranAmount($_REQUEST['user_id']);
	$traffic = totalTraffic($_REQUEST['user_id']);
	$current_plan = currentPlan($_REQUEST['user_id']);
	$purchased_theme = purchasedTheme($_REQUEST['user_id']);
	$total_leads=total_leads($_REQUEST['user_id']);
	$msg_boradcast=msg_broadcast($_REQUEST['user_id']);
endif;	
?>
<ol class="breadcrumb bc-3" >
	<li>
		<a href="index.php"><i class="fa fa-home"></i>Home</a>
	</li>
	<li >
	    <a href='member_list_master.php'>Member Management</a>
	</li>
	<li class="active"> <?=$d['fname'];?> <?=$d['lname'];?></li>
</ol>
<h2>Profile summay of <font color='blue'> <u><?=$d['fname'];?> <?=$d['lname'];?></u></font></h2>
<br />
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-body">
 

<table width="100%" border="0" align="center" cellspacing="0" cellpadding="5" class="table">
<tr>
<td ><a href='profile_summary.php?user_id=<?=$_REQUEST['user_id'];?>'>Profile Summary</a></td>
<td ><a href='mem_regform.php?user_id=<?=$_REQUEST['user_id'];?>'>Edit Profile</a></td>
<td ><a href='leadpage.php?user_id=<?=$_REQUEST['user_id'];?>'>Leadpages</a></td>
<td ><a href='purchased-themes.php?user_id=<?=$_REQUEST['user_id'];?>'>Purchased Themes</a></td>
<td style='background-color: #303641;color:#fff'>Transaction History</td>
<td ><a href='affiliate.php?user_id=<?=$_REQUEST['user_id'];?>'>Affiliate</a></td>
<td ><a href='change_password.php?user_id=<?=$_REQUEST['user_id'];?>'>Change Password</a></td></tr>
</table>
<table class="table table-bordered table-striped" width="100%" border="0" align="center" cellspacing="1" cellpadding="4" >
  <thead>
    <tr ><th width=7%>ID</th><th >Trans. Date</th><th  align=center>Name</th>
      <th style='text-align:right'>Amount</th><th >Details</th><th align=center width=12%>Payer Email</th>
      <th align=center >Trans Id</th><th style='text-align:center' >Status</th></tr>
    </thead><tbody>
      <?
      //echo "select * FROM ".$prev."transactions where user_id=" . $_SESSION['user_id'] ." order by add_date desc LIMIT " . ($_REQUEST['limit'])*20 . ",20";die;
      $r=mysqli_query($conweb,"select * FROM ".$prev."transactions where user_id=" . $_REQUEST['user_id'] ." order by add_date desc");
      $d=@mysqli_num_rows($r);
      $j=0;$k=0;
      while($d=mysqli_fetch_array($r)):
	$res_user=mysqli_query($conweb,"select * FROM ".$prev."register where user_id=".$d['user_id']);
	//echo "select * FROM ".$prev."register where user_id=".$id;
		$row_user=mysqli_fetch_array($res_user);
        echo"<tr  ><td>" . $d['id'] ."</td><td class='vertical-middle'>" .showdate(substr($d['add_date'],0,10)) . "</td>
        <td class='vertical-middle'>" .$row_user['fname'] . " ". $row_user['lname']."</td>
        <td  class='vertical-middle' style='text-align:right'>". $d['amount']." ".$d['currency']."</td>
        <td   class='vertical-middle'>" . $d['details'] . "</td>
        <td   class='vertical-middle'>" . $d['payer_email'] . "</td>
        <td   class='vertical-middle'>" . $d['trans_id'] . "</td>
        <td align=center class='vertical-middle'>" . $d['status'] . "</td></tr>";
        $j++;
      endwhile;
      ?>
    </tbody>
  </table>
 
 
 </div>
</div>
 </div>
</div>
<!-- BEGIN FOOTER -->
<?include("includes/footer.php");?>
			
