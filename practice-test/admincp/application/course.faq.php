<?
include("includes/access.php");
include("includes/header.php");
$page_title="Add Course Faq";
 
 
 
 
$breadcrum=array("Course List"=>"course.list.php", 
    "Add | Edit Announcement"=>"");
$btn="<input name='button' type='button' class='btn btn-success' onclick=\"javascript:window.location.href='course.list.php'\" value='Back to Course List' style='float:right;margin-right:36px' />";

include_once("includes/header_common.php");

  
if($_REQUEST['delete']):
	deleteData("course_faq",array("ann_id"=>$_REQUEST['delete'])); 
	 
endif;

if($_POST['submit']):
 
	if($_POST['faq_id']):
	    $sql1="update  " . $prev . "course_faq set question=\"" . $_POST['question'] ."\",answer=\"" . $_POST['answer'] ."\",status=\"" . $_POST['status'] ."\" where faq_id=" . $_POST['faq_id'];
 		$r=mysqli_query($con,$sql1);
		$faq_id=$_REQUEST['faq_id'];
	else:
		$sql2="insert into " . $prev . "course_faq set question=\"" . $_POST['question'] ."\",answer=\"" . $_POST['answer'] ."\",course_id=\"".$_POST['course_id']."\",status=\"" . $_POST['status'] ."\""; 
  
		$r=mysqli_query($con,$sql2);
		$faq_id=mysqli_insert_id($con); 
	endif;
	if($r)
	{
		 
			$msg="Faq updated successfully.";
         
    }
    else
    {
	    $msg1="Some error occurred, please try again.";
	}
endif;
 
if($msg)
{
   echo $msg;	
}
 
$data=getDataByWhere("course_faq","*",array("faq_id"=>$_REQUEST['faq_id']));
?> 


 <form action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="return(formValidate());">
  <TABLE width=100% border=0 cellPadding=4 cellSpacing=1 class='table table-responsive'>
    <tr><td width="20%" height="25" align=left valign="top" >
	 Question : </td><td ><input type='text' name='question' value="<?=$data['question'];?>" class='form-control' reqired placeholder="Question" required></td></tr>

 	  <tr><td width="20%" height="25" align=left valign="top" >
	 Answer : </td>
 	 <td ><textarea  cols="50" rows="5" name="answer"  class="form-control" placeholder="Answer"><?php echo $data['answer'];?></textarea></td></tr> 
 	
     <tr ><td align="left"><b>status</b></td><td align="left"><input type="radio" name="status" value="Yes" <?if($data["status"]=="Yes"){echo" checked";}?> >Yes <input type="radio" name="status" value="No" <?if($data["status"]=="No"){echo" checked";}?>> No </td></tr> 

    		
      <TR>
        <TD  height="25" align=left valign="top" > </TD>
        <TD >
		<input type="hidden" id="faq_id" name="faq_id" value="<?php echo  $data['faq_id']; ?>"  />

		<INPUT type='submit' value="Submit" name='submit' class='btn btn-success' ></TD>
      </TR>
    </TBODY>
  </TABLE>
  <input type="hidden" id="course_id" name="course_id" value="<?php echo $_REQUEST['course_id']; ?>"  />
   <input type="hidden" id="faq_id" name="faq_id" value="<?php echo $_REQUEST['faq_id']; ?>"  />
</form>
<br><br>
<h3>Course Faq List</h3>

<hr>
<table id="table-content" width="100%" class="table" border="0" cellspacing="1" cellpadding="4">
	  <thead>
	  <tr  >
		<td height="25" width="5%" align="left"><b>Faq Id</b></td>
		
 		<td height="25" width="15%" align="left"><b>Question</b></td>
		
		<td height="25" width="15%" align="left"><b>Status</b></td>

		<td width="15%" align="center"><b>Options</b></td>
	  </tr>
	  </thead>
	  <tbody>
	<?php
	 
    $data=getQuery("course_faq","*",array("course_id"=>$_REQUEST['course_id'])); 
    foreach($data as $main_fetch)
    {
    	?>
	 
  
	   <tr >
		<td align="left"><?php echo $main_fetch['faq_id'];?></td>
		
		
 		<td align="left"><?php echo $main_fetch['question'];?></td>
		
		<td align="left"><?php echo $main_fetch['status'];?></td>
		<td align="center"><a href="course.faq.php?course_id=<?=$_REQUEST['course_id'];?>&faq_id=<?php echo $main_fetch['faq_id'];?>"><img src='images/edit.png' alt='Delete'  border='0'></a>
		<?php  echo" | <a  href=\"javascript:if(confirm('Are you sure you want to delete it?')){window.location='" . $_SERVER['PHP_SELF'] . "?course_id=" .$_REQUEST['course_id']."&delete=" . $main_fetch['faq_id'] . "';}\"><img src='images/delete.png' alt='Delete'  border='0'></a>"; ?>
		</td></tr>
	    <?php
	}
    ?>
	 </tbody>
	</table> 
 
 
<?
include("includes/footer.php");
?>