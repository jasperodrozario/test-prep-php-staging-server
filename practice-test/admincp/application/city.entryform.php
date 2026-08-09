<?
include("includes/access.php");
include("includes/header.php");


?>
<Script Language="JavaScript">
<!--
function isValid(T)
{
    if(!Trim(T.co_city.value))
    {
        alert("Category Name is empty");
        return false;
    }
}

function Trim (s)
{
    var iLen = s.length;
    var sOut = "";
    var chr = "";
    for (var i=0; i<iLen; i++)
    {
        chr = s.charAt (i); 
        if (chr!=" ")
            sOut = sOut + chr; 
    }
    return sOut;
}
//-->
</Script>

<?

$msg="";
if($_POST['Update']):
    if(!$_POST['city_id']):
       $r=mysqli_query($con,"insert into " . $prev . "city(city_name,country_id,status) values(\"" . $_POST['city_name'] . "\",\"" . $_POST['country_id'] . "\",\"" .$_POST['status'] . "\")");
	   //$error= "insert into " . $prev . "city(city_name,country_id,status) values(\"" . $_POST['city_name'] . "\",\"" . $_POST['country_id'] . "\",\"" .$_POST[status] . "\")";

 
	   $_REQUEST['city_id']=mysqli_insert_id($con);



    else:
       //$r=mysqli_query($con,"update " . $prev . "city set city_name=\"" . $_POST['city_name'] . "\",status=\"" . $_POST[status] . "\",country_id=\"" . $_POST['country_id'] . "\" where city_id=" . $_REQUEST['city_id']);
     //$_REQUEST['city_id']=$_POST['city_id'];


            $r=mysqli_query($con,"update " . $prev . "city set city_name=\"" . $_POST['city_name'] . "\",status=\"" . $_POST['status'] . "\" where city_id=" . $_POST[city_id]);
         $city_id=$_POST['city_id'];



	   //$error="update " . $prev . "city set city_name=\"" . $_POST['city_name'] . "\",status=\"" . $_POST[status] . "\",country_id=\"" . $_POST['country_id'] . "\" where city_id=" . $_REQUEST['city_id'];
    endif;
    if($r):
       $msg="Update Successful.";
       header("location:country_city.list.php");
    else:
       $msg="Update Failure.";
    endif;		
elseif($_POST[DELT]):
    $r=mysqli_query($con,"delete from " . $prev . "city where city_id=\"" . $_POST['city_id'] . "\"");

    echo "delete from " . $prev . "city where city_id=\"" . $_POST['city_id'] . "\"";


	header("location:country_city.list.php");
endif;
$page_title="Add City";
$breadcrum=array("Country List"=>"country_city.list.php",
    "Add City"=>"");
require_once("includes/header_common.php");
if($_GET['city_id']):
   $r=mysqli_query($con,"select * from  " . $prev . "city where city_id=" . $_GET['city_id']);
   $d=mysqli_fetch_array($r);
endif;
if(!$d["status"]){$d["status"]="Y";}
?>
<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>" enctype="multipart/form-data">
<input type="hidden" name="country_id" value="<?php echo $_GET['country_id']?>">
<input type="hidden" name="city_id" value="<?php echo $_GET['city_id']?>">
<table width="100%" border="0"  cellpadding=3 cellspacing=1 class='table table-bordered'> 
 
<tr ><td valign=top>City Name</td><td><input class='form-control' type="text" size=35 name="city_name" value="<?php echo $d['city_name']?>"></td></tr>		
<tr ><td>Status</td><td><input name="status" type="radio" value="Y"  <? if($d["status"]=="Y"){echo" checked";}?> >Active 
<input type="radio" name="status" value="N" <? if($d["status"]=="N"){echo" checked";}?>> Hidden  </td></tr>
<tr><td></td><td><input type="submit" name="Update" value="Update" class='btn btn-success'>&nbsp;&nbsp;<input type="submit" name="DELT" value="Delete" class='btn btn-danger'>&nbsp;&nbsp;<input type="Button"  value="Back" onClick="JavaScript:window.location.href='country_city.list.php';" class='btn btn-info'></td></tr>
</table><br>
</form>
<hr>
<h3>City List of <?=getCountry($_GET['country_id']);?></h3>
<table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4"  >
<thead>
<tr><td valign=top width="20%"><b>City Name</b></td><td valign=top width=15% align=left><b>Status</b></td><td valign=top align=right><b>Action</b></td></tr>
</thead>
<?
$rr= mysqli_query($con,"select * from " . $prev . "city where country_id=" . $_GET['country_id'] . " order by city_name");
if(@mysqli_num_rows($rr)):
	$k=0;
	while($dd= mysqli_fetch_array($rr)):
	    $total = 0;
		$ads_res = mysqli_query($con,"select * from  " . $prev . "city where city_id = ". $dd['city_id']);
	   
		$total=@mysqli_num_rows($ads_res);
	    echo"<tr ><td ><a  href='city.entryform.php?city_id=" . $dd['city_id'] . "&country_id=" . $dd['country_id'] . "'>" . $dd['city_name'] . "</a></td>
		

    <td align=left>". $dd['status']."</td><td align=right> <a  href='city.entryform.php?city_id=" . $dd['city_id'] . "'>Edit City</a></td></tr>";
		$k++;
     endwhile;
endif;     
?>
<tbody>
</tbody></table>
<?
include("includes/footer.php");
?>