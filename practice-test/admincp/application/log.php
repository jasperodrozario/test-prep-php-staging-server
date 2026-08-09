<?php
require_once("includes/access.php");
require_once("includes/header.php");

if($_GET[del]==1):
    $r=mysqli_query($con,"delete from " . $prev . "log where log_id=". txt_value($_GET[id]));
endif;
if(!$_REQUEST[limit]){$_REQUEST[limit]=1;}
 
$page_title="Log List";
$breadcrum=array(
    array("Log List", "")
);

require_once("includes/header_common.php"); 
?>
<form method="post" action="<?php $_SERVER[PHP_SELF]?>">
 
<table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4"  style="border:solid 1px <?=$dark?>">
<thead>
<tr >

<td width="34%" align="left"  valign="top"><b>Employee Name</b></td>
<td width="37%"  align="left" valign="top"><b>Update Date</b></td>
<td width="29%"  align="left" valign="top"><b>Courier No</b></td>
</tr>
</thead><tbody>
<?php
$r=mysqli_query($con,"select * from " . $prev . "log");
$total=@mysqli_num_rows($r);
$r=mysqli_query($con,"select * from " . $prev . "log order by log_date DESC limit " . (txt_value($_REQUEST['limit'])-1)*20 . ",20");
if(!$total):
	echo"<tr class='lnkred'><td colspan='4' align='center'>No Users found.</td></tr>";
endif;
$j=0;$k=0;
while($d=@mysqli_fetch_array($r)):
 
    echo"<tr >
	<td width='7%'>" . $d['user_name'] . "</td>
	<td>" . $d['log_date'] . "</td>
	<td>" . $d['courior_no'] . "</td>
	</td></tr>";
	$j++;
endwhile;
$parama="&amp;param=".$_REQUEST['param']."&amp;search=".$_REQUEST['search'];
if($_REQUEST['alpha'])
{
	$parama.="&amp;alpha=".$_REQUEST[alpha];
}
?>
</tbody>
</table>
<?
if($total>20):?>
<table  width="100%"  border="0" align="center" HEIGHT=25 cellspacing="0" cellpadding="4">
  <tr  ><td  align=center ><?=paging($total,20,"$parama","lnk");?></td></tr>
</table>
<? endif;?>
 
</form>
 
<? include("includes/footer.php");?>

