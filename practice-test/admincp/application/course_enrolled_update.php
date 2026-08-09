<?php
include("includes/access.php");
include("includes/header.php");

if(isset($_REQUEST['del_id'])):
   deleteData("course_user",array("id"=>$_REQUEST['del_id'])); 
endif;
 
$user_name=getDataByWhere("login","fname,lname",array("user_id"=>$_GET['user_id'])); 
$page_title="Course Enrolled By ".ucwords($user_name['fname'] . " " . $user_name['lname']);
$breadcrum=array("Student List"=>"edu_member.list.php"  
);
$btn="<a href='edu_member.list.php' class='btn btn-success'>Back to Student List</a>";
require_once("includes/header_common.php");

if($_POST):
	$r=mysqli_query($con,"insert into " . $prev . "course_user set course_id=" . $_POST['course_id'].",user_id=" . $_POST['user_id'] .",add_date=\"" . date("Y-m-d") . "\"");
  
endif; 

?>
<form method='post' action="<?php echo $_SERVER['PHP_SELF']?>?user_id=<?=$_REQUEST['user_id']?>" name='ed'>
 
<table id="table-1" width="100%" class="table table-bordered" border="0" align="center" cellspacing="1" cellpadding="4">
<thead>
<tr><td valign=top width=10%><b>Course Id</b></td>

<td valign=top width=15%><b> Name </b></td>
<td valign=top width=20%><b>Enroll Date</b></td><td valign=top width=20%><b>Action</b></td>
 </thead>

<tbody>
<?php
$courses=getQueryByJoin(array("course_user as u","course as c"),"c.name,u.id,u.add_date,u.course_id",array("u.user_id"=>$_GET['user_id']),array("u.course_id"=>"c.course_id")); 
 
$total=count($courses);
 
if(!$total)
{


   echo"<tr><td colspan='3' align='center'><span class='lnkred'>No Course enrolled yet.</span></td></tr>";
}   
else
{
   
   foreach($courses as $course)
   {
   	   echo"<tr ><td>" . $course['course_id'] . "</td>
	
	   <td >" . $course['name']  . "</td>   <td >" . showdate($course['add_date']) . "</td><td ><a  href=\"javascript:if(confirm('Are you sure you want to delete it?')){window.location='" . $_SERVER['PHP_SELF'] . "?del_id=" . $course['id']."&user_id=" .$_REQUEST['user_id'] . "';}\">Delete</a></td></tr>"; 
   }	
   


}	
echo"</table>";
$courses=getQuery("course","*","");

 ?>
 	
 </tbody></table>

 <h3>Add a Course to <?=ucwords($user_name['fname'] . " " . $user_name['lname']);?></h3>

 <form method='post' action='<?=$_SERVER['PHP_SELF'];?>?user_id=<?=$_REQUEST['user_id'];?>'>
 <input type='hidden' name='user_id' value='<?=$_REQUEST['user_id'];?>'>	
 <table width="100%" border="0" align="center" cellpadding=3 cellspacing=1 class='table table-bordered'>
 <tr><td>Add Course</td><td>
 <select name='course_id' class='form-control' size=1>
 <option value=''>Select one</option>
 <?
 foreach($courses as $course)
 {
    echo"<option value='" . $course['course_id'] ."'>" . $course['name'] ."</option>";
 }
 ?>	
</select>
 </td> <td><input type='submit' name='add' class='btn btn-sucees' value='Add'></td></tr>
 </table>
 </form>	
 
<?php
include("includes/footer.php");
?>