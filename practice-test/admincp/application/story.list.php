<?
include("includes/access.php");
include("includes/header.php");
?>
<?php
 if($_REQUEST['page'])
 {
 $page=$_REQUEST['page'];
 }
 else
 {
 $page=1;
 }
 ?>
<?php
$pagesql="select * from ".$prev."story ";
$pagequery= mysqli_query($pagesql);
$no_of_row= mysqli_num_rows($pagequery);
$page_limit=20;
$limit=($page-1)*$page_limit;
$no_of_page=ceil($no_of_row/$page_limit);
?>
<?php

if($_GET[del]):

   mysqli_query($con,"delete from " . $prev . "story where story_id=" . $_GET[id]);
   @unlink("../" . $_GET[file1]);
endif;
if(!$_REQUEST[lang]){$_REQUEST[lang]="E";}
?>
<form method=post action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" bgcolor=<?php echo $light?> >
<tr bgcolor=<?php echo $light?>>
  <td width="70%" height=25 class=header>&nbsp;&nbsp;<a href="story.list.php" class="header"><?php
if($_REQUEST['id'])
{echo "Member Management->";}?>Story Management-><?php print $uname;?></a>&nbsp;&nbsp;&nbsp;<? if($_REQUEST[lang]=="E"){?>
    <input type=button value='Add New' class=lnk onClick="javascript:window.location.href='story.entryform.php'"><? }?></td>
<td bgcolor="<?php echo $light?>" width=46% align=right><table >
<tr ><td><select name="param" size="1"  >
<option value='story_title'   <? if($_REQUEST[param]=="story_title"){echo" selected";}?>>Story Title</option>
<option value='story_id'   <? if($_REQUEST[param]=="story_id"){echo" selected";} ?>> Story ID</option>
</select></td><td>  <input type="text" size="8" name="search"  value="<?php echo $_REQUEST[search]?>"> </td>
<td><input type="submit"  name='SBMT_SEARCH'  value='  Search  '>  </td></tr></table>
</td></tr></table>
<table id="table-1" width="100%" class="table table-bordered" border="0" align="center" cellspacing="1" cellpadding="4"  >
<thead><tr bgcolor=<?php echo $graywhite2?>>


<td><b>Story ID</b></td>

<td><b>Story Title</b></td>



<td  align=center><b>Action</b></td>
</tr></thead>
<!--//................................................................-->


<?php
if($_REQUEST['id'])
{
$select_member="select * from ".$prev."story where status='y'";
$rec_member= mysqli_query($select_member);
$num_member= mysqli_num_rows($rec_member);
if($num_member > 0)
{$j=1;
	while($row_member= mysqli_fetch_array($rec_member))
	{if(!($j%2)){$class="even";}else{$class="odd";}
?>
<tr   class="<?php echo $class;?>">

<td><?php echo $row_member['story_id'];?>
</td>
<td><?php echo $row_member['story_title'];?>
</td>

<td>
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<?php 
	
	echo "<td align=center><a class=lnk title='Edit this News' href='story.entryform.php?id=" . $row_member[story_id] . "'><u>Edit</u></a> | <a title='Delete this News' class=lnk  href=\"javascript:if(confirm('Are you sure which to delete?')){window.location='" . $_SERVER[PHP_SELF] . "?id=" . $row_member[story_id] . "&file1=" . $row_member[image] . "&del=1';}\"><u>Delete</u></a></td>";
	
	?>
	</tr>
	</table>

</td>
</tr>
<?php
	$j++;}
}
else
{
?>
<tr>
<td colspan="4">Data not found..
</td>
</tr>
<?php
}
?>

</table>

</form>



<script type="text/javascript">
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
	["Number","String","String","String","String","String","String","String","Number","String","None"]);
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
<?
include("includes/footer.php");
?>
<?php
}
else
{
?>
<!--.........................................-->



<?php

if($_POST[SBMT_SEARCH] && $_POST[param] && $_POST[search])
   {
   if($_POST[param]=="story_id")
   {

       $cond=$_POST[param] . "='" . $_POST[search] . "'";
    }
   else if($_POST[param]=="story_title")
     {
      $cond=$_POST[param] . " like '%" . $_POST[search] . "%'";

   }  

}
      
if($cond){$cond2=" where " . $cond;}
if(isset($cond2))
{
$select_member="select * from ".$prev."story ".$cond2." limit $limit,$page_limit";
}
else{
$select_member="select * from ".$prev."story limit $limit,$page_limit";
}

$rec_member= mysqli_query($select_member);
$num_member= mysqli_num_rows($rec_member);
if($num_member > 0)
{$j=1;
	while($row_member= mysqli_fetch_array($rec_member))
	{if(!($j%2)){$class="even";}else{$class="odd";}
?>
<tr   class="<?php echo $class;?>">

<td><?php echo $row_member['story_id'];?>
</td>
<td><?php echo $row_member['story_title'];?>
</td>

<td>
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<?php 
	
	echo "<td align=center><a class=lnk title='Edit this News' href='story.entryform.php?id=" . $row_member[story_id] . "'><u>Edit</u></a> | <a title='Delete this News' class=lnk  href=\"javascript:if(confirm('Are you sure which to delete?')){window.location='" . $_SERVER[PHP_SELF] . "?id=" . $row_member[story_id] . "&file1=" . $row_member[image] . "&del=1';}\"><u>Delete</u></a></td>";
	
	?>
	</tr>
	</table>

</td>
</tr>
<?php
	$j++;}
}
else
{
?>
<tr>
<td colspan="4">Data not found..
</td>
</tr>
<?php
}
?>

</table>

</form>
<table  width="100%"  border="0" align="center" HEIGHT=25 cellspacing="0" cellpadding="4" >

  <tr bgcolor=<?php echo $light?>><td  align=center >Pages ... <?php
for($i=1;$i<=$no_of_page;$i++)
{?>
<a href="story.list.php?page=<?php print $i;?>"><?php print $i;?></a>
<?php
}
?></td></tr>

</table>
<script type="text/javascript">
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
	["Number","String","String","String","String","String","String","String","Number","String","None"]);
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
<?
include("includes/footer.php");
}
?>