<?
include_once("includes/access.php");
include("includes/header_pop.php");
include("mail.php");
mysqli_query($con,"update ".$prev."enquiry set reading_status='R' where id='".$_REQUEST['id']."'");
if($_GET[del]=="1" && $_SESSION['admin_type']=="S"):
    mysqli_query($con,"delete from " . $prev . "enquiry where id=" . $_GET[id]);
endif;
$d=mysqli_fetch_array(mysqli_query($con,"select * from " . $prev . "enquiry where id=" . $_GET[id]));

	if($d['p_s_type']=='P'){
	if($d['product_id']>0){
$prd=mysqli_fetch_array(mysqli_query($con,"select * from " . $prev . "product where prod_id=" . $d[product_id]));
$product_name = $prd['product_name'];
}else{
$product_name = $d['prod_name'];
}
}else{
$prd=mysqli_fetch_array(mysqli_query($con,"select title as product_name from ". $prev. "services where id='".$d['product_id']."'"));
$product_name = $prd['product_name'];
}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" project_id="form1">
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="4" bgcolor=<?php echo $light?> class=table>
<tr bgcolor=<?php echo $light?>><td height="25" align="left" class=header_tr><a href='enquiry_list.php' class=header_tr>Enquiry  List</a> > <u><?php echo $product_name;?></u> by <?php echo ucwords($d[name])?>&nbsp;&nbsp;&nbsp;
</td>
<td  width=50% align=right>

<input type=button value='Add New <?php echo $txt?> &raquo;'  onclick="javascript:window.location.href='enquiry.message.entryform.php?id=<?php echo $_REQUEST[id]?>#new'" class="btn btn-success">&nbsp;</td></tr></table><br>

<table id="table-1" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="0" cellpadding="4">

	<tr   bgcolor=<?php echo $light?>>
		<td height=30 width="100%" colspan=2> Enquiry Detail:&nbsp;<?php echo $prd[product_name];?> by <font color="#000000"><?php echo $d['name']?></font>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr class="even" height="30">		
		<td valign="middle" width="20%"><b>Date :</b></td>		
		<td><?php echo date(('d M, Y'),strtotime($d['date']));?></td>	
	</tr>
	<tr class="odd" height="30">		
		<td valign="middle" width="20%">Subject :</td>		
		<td><?php echo $d['subject'];?></td>	
	</tr>
	<tr class="even" height="30">		
		<td valign="middle" width="20%">Message :</td>		
		<td><?php echo $d[message];?></td>	
	</tr>
    
	<?
		if($d['p_s_type']=='P'){
	$p=mysqli_fetch_array(mysqli_query($con,"select * from " . $prev . "product where prod_id=".$d[product_id]));
	?>
    <tr class="odd" height="30">		
		<td valign="middle" width="20%">Product :</td>		
		<td><?php echo $product_name;?></td>	
	</tr>
	<? }else{?>
	 <tr class="odd" height="30">		
		<td valign="middle" width="20%">Service :</td>		
		<td><?php echo $prd[product_name];?></td>	
	</tr>
	<? }?>
	<tr class="even" height="30">		
		<td valign="middle" width="20%">Estimated Budget :</td>		
		<td><?php echo $d[estimated_bud];?></td>	
	</tr>
	<tr class="odd" height="30">		
		<td valign="middle" width="20%">Name :</td>		
		<td><?php echo $d[name];?></td>	
	</tr>
	<tr class="even" height="30">		
		<td valign="middle" width="20%">Email Id :</td>		
		<td><a href="mailto:<?php echo $d[email_id]?>"><?php echo $d[email_id];?></a></td>	
	</tr>
	
	<tr class="odd" height="30">		
		<td valign="middle" width="20%">Company Name :</td>		
		<td><?php echo $d[company];?></td>	
	</tr>
	
	
	<tr class="even" height="30">		
		<td valign="middle" width="20%">Company Website :</td>		
		<td><a href="http://<?php echo $d[website_url]?>" target="_blank"><?php echo $d[website_url];?></a></td>	
	</tr>
	
	
	<tr class="odd" height="30">		
		<td valign="middle" width="20%">Country :</td>		
		<td><?php echo $d[country];?></td>
	
	</tr>

	
</table>
<br />
<br />
<table id="table-1" width="100%" class="table table-striped table-bordered table-hover" border="0" align="center" cellspacing="0" cellpadding="4">
<thead>
<tr bgcolor="<?php echo $graywhite2?>" valign=top><td width="3%" height=30><b>ID</b></td><td align=left colspan=2><b>Description</b></td></tr>
</thead><tbody >
<?
if(!$_GET[limit]){$_GET[limit]=1;}
$lmt=" limit " . ($_GET[limit]-1)*20 . ",20";
if($_POST[SBMT_SEARCH] && $_POST[param] && $_POST[search]):
   if($_POST[param]=="id" || $_POST[param]=="project_id"):
       $cond[]=$_POST[param] . "='" . $_POST[search] . "'";
   else:
      $cond[]=$_POST[param] . " rlike '" . $_POST[search] . "'";
   endif;
endif;

if($_GET[id]):
	$cond[]= "id='" . $_GET[id] . "'";
endif;


if(count($cond)):
   $cond2=implode(" and ", $cond);
endif;

$var="select * from " . $prev . "enquiry  where id=" . $_GET[id];
$rr=mysqli_query($con,$var);
$bb=mysqli_fetch_array($rr);
$r=mysqli_query($con,"select * from " . $prev . "enquiry_message_board where id=" . $_GET[id] ." order by message_id " . $lmt);
 
$total=@mysqli_num_rows($rr);

//echo "select * from " . $prev . "enquiry where admin_id=".$_SESSION[admin_id] .  $cond." order by start_dt  " . $lmt;
if(!$total):
   echo"<tr class='lnkred' ><td colspan='3' align='center'>No Record found.</td></tr>";
endif;
$j=0;$k=0;
while($d=@mysqli_fetch_array($r)):
	$j++;
	if(!($j%2)):
	    $class="even";
	else:
	    $class="odd";
	endif;
	$message="";
	if(!trim($d[status])){$d[status]="-none-";}
	if(!$d[attachment]){$d[attachment]="-none-";}
   

  
	if($d[admin_id]==$d[id] && $d[sender_type]=="B")
	{ 
	   $name=$bb[name]; $rowspan=7; $department="Project Owner";
	}
	else
	{
		$b=@mysqli_fetch_array(mysqli_query($con,"select * from " . $prev . "admin where admin_id=" .$d[admin_id]));
		$name=$b[fname] . " " .$b[lname];
		$department="Admin";
	}
	
	$message="<tr  class=" . $class .   "><td width=10   valign=top >" . $j . "</td>
	<td style='border-right:dotted 1px #111111;' valign=top ><strong>Name: " .  $name . " </strong><br>Department: " . $department . "<br><strong>Product :</strong> " . Ucwords($p['product_name']) . "<br>";
	
		$message.="Submit On : " . $d[date] . "<br>";
		
	$message.="</td><td  width=75% style='padding-left:10px'>" .nl2br($d[description]) ."<br><br>";
	$message.="Attachment : " . $d[attachment] . "<br>
	Status : " . $d[status] . "&nbsp;</td></tr><tr><td colspan=3><hr size=1></td></tr>";
	
	echo $message;
		
endwhile;
$parama="&amp;alpha=".chr($i)."&amp;param=".$_REQUEST[param]."&amp;search=".$_REQUEST[search];
echo"<tr ><td bgcolor=whitesmoke valign=top style='border-bottom:solid 1px ". $dark."' colspan=3 align=right><input type=button value='Add New " .$txt ." &raquo;' onclick=\"javascript:window.location.href='enquiry.message.entryform.php?id=".$_REQUEST[id]."'\" class="btn btn-success">&nbsp;</td></tr>";

?>
</tbody>
</table>

</form>
</body></html>