<?php 
include_once("includes/access.php");
include("includes/header.php");
?>

<!--- Validation-------->
<script>
$(function() {
	
$( "#validate" ).validate({
rules: {
title: {
required: true,

},
url: {
required: true,

},
order: {
required: true,
number:true,
minlength: 1,
maxlength: 20
},
status: {
required: true,

}
},

messages: {
title: {
required: "Please Enter Title ",
},
url: {
required: "Please Enter Page Url",

},
order: {
required: "Please Enter Menu Order",
minlength: $.format("Keep typing, at least {0} characters required!"),
maxlength: $.format("Whoa! Maximum {0} characters allowed!")
},
status: {
required: "Please Select Radio Button",

},
}
});

});
</script>


<!----------- validation ------------->        

<script language="javascript" type="text/javascript">
<!--
function ValidEditor(register)
{
    var txt="";
	if(!register.site_title.value)
	{
		txt+="     Title should not be empty.\n"
	}

   	if(txt)
	{
   		alert("Sorry!! Following errors has been occured :\n\n"+ txt +"\n     Please Check");
  	 	return false
	}
    return true
}
function txtduplicate(dest,src)
{
	var src3=document.getElementById(src).value;
	if(src3!="" && document.getElementById('id').value != '1' )
	{
	var src2=src3.replace(/\s/g,"_");
	document.getElementById(dest).value="<?php echo $vpath?>cms/"+src2+".htm";
	}
	else
	{
		document.getElementById(dest).value="";
	}
}
function textCounter(field,cntfield,maxlimit) {
	   if (field.value.length > maxlimit) {
	   field.value = field.value.substring(0, maxlimit);
	   alert('Max lenght is 300 characters. You have typed ' + field.value.length + ' charachters.');
	   }
	   // otherwise, update 'characters left' counter
	   else
	   cntfield.value = maxlimit - field.value.length;
	   }
	   
</script>	   
<?
//if(!$_GET[lang]){$_GET[lang]="E";}

if($_POST['SBMT_REG']):
	 if(!$_POST['status']){$status="Y";}else{$status=$_POST['status'];}
     $test_name=htmlentities($_POST['test_name']);
     $test_desc=htmlentities($_POST['test_desc']);
    
	
	if($_FILES["image"]["name"]):
		  $ext=substr($_FILES["image"]["name"],-3,3);
		  if($ext=="peg")
		  {
		  	$ext="jpg";
		  }
		  $old=$_FILES["image"]["tmp_name"];
		  $pi="img/testimonial/".time().".".$ext;
		  move_uploaded_file("$old","../".$pi);
		  $txt="banner=\"". $pi . "\",";
		  
		endif;
	 if($_FILES["image"]["name"] == '') {
	 $pi = $_POST['change_image'];
	 } else {
	 $pi="img/testimonial/".time().".".$ext;
	 }
	  
   	 if($_POST['id']):
	  	  $r=mysqli_query($con,"update " . $prev . "testimonials set test_name=\"" . $test_name . "\" ,status=\"" . $_POST['status'] . "\",test_desc=\"" . $test_desc . "\",test_image=\"" . $pi . "\"	where id=" . $_POST['id']);
		  $id=$_POST['id'];
			
	 else:
	      $r=mysqli_query($con,"insert into " . $prev . "testimonials set test_name=\"" . $test_name . "\" ,status=\"" . $_POST['status'] . "\",test_desc=\"" . $test_desc . "\",test_image=\"" . $pi . "\" ");
		  
		  $id=mysqli_insert_id($con);
 
	 endif;
	 if($r):
    	$msg="<i class=\"fa fa-check-square\"></i> <font face=Arial size=2 ><b>Update Successful.</b></font>";
	    //header("Location:".$vpath."admincp/slider_edit.php?id=".$_REQUEST['id']);
	    header("Location:".$vpath."admincp/tesimonials.php");
	 else:
	 	$msg="<i class=\"fa fa-frown-o\"></i> <font face=Arial size=2 color=red><b>Error! Please try again.</b></font>";
	 endif;

endif;

 

?>
<div class="wrapper">
		<!-- BEGIN LEFTSIDE -->
        <?php include("includes/left.php");?>
		<!-- BEGIN RIGHTSIDE -->
        <div class="rightside bg-grey-100">
		<!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
			<h1 class="page-title">Testimonials Management </h1> 
			<?php if($msg){echo"<table width=100% cellpadding=0 cellspacing=0 border=0 style='margin-top:12px'><tr><td>" .$msg ."</td><td  style='float:right;'><input type='button' value='Add New' class='btn btn-success'  onclick=\"javascript:window.location.href='page.editor.php?id=0'\" ></td></tr></table>";}?>
		    
		</div>
		
		<!-- END PAGE HEADING -->
		<? 
		if($_REQUEST['id']){$id=$_REQUEST['id'];}
		$r=mysqli_query($con,"select * from " . $prev . "testimonials where id=" . $id);
		$d=@mysqli_fetch_array($r); 
		if(!$d[status]){$d[status]="Y";}
		?>		
        <div class="container-fluid">
        
         <div class="panel-body no-padding-top bg-white">
		<h4 class="color-grey-700"><a href='tesimonials.php'>Testimonials List</a> > <?php echo   'Tesimonial' ;?></h4>
		<div class="table-responsive">
		
 

	   <SCRIPT language="JavaScript">
	   <!-- Begin
	   function textCounter(field,cntfield,maxlimit) {
	   if (field.value.length > maxlimit) { 
	   field.value = field.value.substring(0, maxlimit);
	   alert('Max lenght is 200 characters. You have typed ' + field.value.length + ' charachters.');
	   }
	   // otherwise, update 'characters left' counter
	   else
	   cntfield.value = maxlimit - field.value.length;
	   }
	   //  End -->

		</SCRIPT>
      <SCRIPT type="text/javascript">
					function submit_form(value) {
					if(value.meta_desc.value.length >200) {
						alert("The Abstract Contents must be between in 200 characters.");
						value.meta_desc.focus();
						return false;
					}
					else {
						return true;
					}
			}
		</SCRIPT>
		
		<SCRIPT type="text/javascript">
					function submit_form(value) {
					if(value.meta_key.value.length >200) {
						alert("The Contents must be in between 200 characters.");
						value.meta_key.focus();
						return false;
					}
					else {
						return true;
					}
			}
		</SCRIPT>
		<script>
function toggle() {
 if( document.getElementById("hidethis").style.display=='none' ){
   document.getElementById("hidethis").style.display = '';
 }else{
   document.getElementById("hidethis").style.display = 'none';
 }
}
</script>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method=post enctype="multipart/form-data"  name="minform">
<input type=hidden name='id' value="<?php echo $d[id]?>">
<table width="100%" border="0" align="left" cellspacing="1" cellpadding="4" class='table table-bordered table striped'>
<tr ><td width="20%">Tesimonial name</td><td><input type="text" class='form-control' name='test_name' size="30" value="<?php echo $d[test_name]?>" ></td></tr>
<tr >
  <td  >Tesimonial Description</td>
  <td ><textarea maxlength="250"  cols="50" rows="2" name="test_desc"  class='form-control' onKeyDown="textCounter(minform.meta_desc,minform.remLen12,200)" onKeyUp="textCounter(minform.meta_desc,minform.remLen12,200)" ><?php echo $d[test_desc]?>
</textarea>
<br />
      <!--input  readonly="readOnly" size="5" value="200" name="remLen12" /--></td>
</tr>
<!--<tr ><td width="20%">Product Category</td><td><input type=checkbox value='Y' name=product_category  <?php if($d[product_category]=="Y"){echo"checked";}?>> Yes </td></tr>-->
<tr ><td width="20%">Tesimonial Image</td><td><?php if($d['img']){echo"<img src='../image.php?image=" .$d['img'] . "&width=132'><br />";}?>
<input type="file" name="image" value="<?php echo $d['test_image']?>" />
<input type="hidden" name="change_image" value="<?php echo $d['test_image']?>" />
</td></tr>

<tr ><td valign=top width="20%">Status </td>
<td><input type=radio value='Y' name=status  <?php if($d[status]=="Y"){echo"checked";}?>>Yes <input type=radio name=status value='N' <?php if($d[status]=="N"){echo"checked";}?>>No</td></tr>



<tr><td></td><td  height=20 ><input type=submit  name='SBMT_REG' <?php if($d[id]){ ?>value='Update'<?php }else{ ?>value='Add'<?php } ?> class='btn btn-success' >&nbsp;&nbsp;<input type="Reset" class="btn btn-default"></td></tr>
</table>
</form></div>
</div>
<!-- BEGIN FOOTER -->
				<?php include("includes/footer-top.php");?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div><!-- /.rightside -->
    </div><!-- /.wrapper -->
	<!-- END CONTENT -->
	<?php include("includes/index_footer.php");?>