<?
include("includes/access.php");
include("includes/header.php");
 
?>
<Script Language="JavaScript">
<!--
function isValid(T)
{
    if(!Trim(T.co_name.value))
    {
        alert("country Name is empty");
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
$page_title="Country City List";
$breadcrum=array("Country City List"=>"country_city.list.php", 
    "View Enquiry"=>"");

require_once("includes/header_common.php");
$msg="";
if($_POST['Update']):
    if(!$_POST['country_id']):
       $r=mysqli_query($con,"insert into " . $prev . "country(name,status)values (\"" . $_POST['name'] . "\",\"" .$_POST['status'] . "\")");

       echo "insert into " . $prev . "country(name,status)values (\"" . $_POST['name'] . "\",\"" .$_POST['status'] . "\")";
     $country_id=mysqli_insert_id($con);
    else:
       $r=mysqli_query($con,"update " . $prev . "country set name=\"" . $_POST['name'] . "\",status=\"" . $_POST['status'] . "\" where country_id=" . $_POST[country_id]);
     //echo "update " . $prev . "country set name=\"" . $_POST['name'] . "\",status=\"" . $_POST['status'] . "\" where id=" . $_POST[id];
     $country_id=$_POST['country_id'];
 
    endif;
  
  if($_FILES['pic']['name']):
    $fname=explode(".",$_FILES['pic']['name']);
    $new="country_".$country_id.".".$fname[1];
   
    if(copy($_FILES['pic']['tmp_name'],$apath."flags/" . $new)):
   
          $sqlc="update ".$prev."country set pic='flags/".$new."' where country_id=".$country_id;
      mysqli_query($con,$sqlc);
    endif; 
    
    endif;
   
   if($r):
       $msg="<b>Update Successful.</b>";
       header("location:country_city.list.php");
   else:
       $msg="<b>Update Failure.</b>";
   endif;   
elseif($_POST['DELT']):
   $r=mysqli_query($con,"select * from " . $prev . "city where country_id=" . $_POST[country_id]);
   if(@mysqli_num_rows($r)):
      $msg="<b>You Can't delete.City is there.</b>";    
   else:
      $r=mysqli_query($con,"delete from " . $prev . "country where country_id=\"" . $_POST[country_id] . "\"");
      header("location:country_city.list.php");
   endif;
endif;


if($_REQUEST['country_id']){$country_id=$_REQUEST['country_id'];}

if($_GET['country_id']):
   $r=mysqli_query($con,"select * from  " . $prev . "country where country_id=" . $_GET['country_id']);
   $d=mysqli_fetch_array($r);
endif;
if(!$d["status"]){$d["status"]="Y";}
echo $msg;
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type="hidden" name="country_id" value="<?php echo $_GET['country_id']?>">
<table width="100%" border="0" align="center" cellpadding=3 cellspacing=1  class="table table-bordered">

<tr ><td valign=top>Country Name</td><td><input type="text"  name="name" value="<?php echo $d['name']?>" class='form-control'></td></tr>

 <tr  >
   <td align="left"> Upload Image<font color="#FF0000">*</font></td>
   <td align="left" ><input type="file" name="pic"  value="<?php echo $d['pic']; ?>">&nbsp;<img src="../<?php echo $d['pic']?>" width="100" /></td>
 </tr>

<tr ><td>Status</td><td><input name="status" type="radio" value="Y"  <? if($d["status"]=="Y"){echo" checked";}?> >Active 
<input type="radio" name="status" value="N" <? if($d["status"]=="N"){echo" checked";}?>> Hidden  </td></tr>
<tr><td></td><td ><input type="submit" name="Update" value="Update" class='btn btn-success'>&nbsp;&nbsp;<?if($id){?><input type="submit" name="DELT" value="Delete" class='btn btn-danger'><?}?>&nbsp;&nbsp;<input type="Button"  value="Back" onClick="JavaScript:window.location.href='history.back()';" class='btn btn-default'></td></tr>
</form>
</table><br>
<?
include("includes/footer.php");
?>