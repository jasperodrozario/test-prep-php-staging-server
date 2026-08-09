<?


include("access.php");


include("header.php");


?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>"><br>


<table cellpadding=4 cellspacing=4 border=0 align=center width=30%  align=center >


<?php if($SBMT && $old_pass):


      $r=mysqli_query($con,"select * from " . $prev. "admin where pass=\"" . $old_pass . "\"");


	  if(@mysqli_num_rows($r)):


	     mysqli_query($con,"delete from prado_admin");


		 $r=mysqli_query($con,"insert into " . $prev. "admin(pass) values(\"" . $pass . "\")");


	     if($r):


	        echo"<tr  bgcolor=yellow red ><td colspan=2 align=center>Update Successful</td></tr>";


	     endif;


	  else:


	      echo"<tr  bgcolor=yellow 1red ><td colspan=2 align=center>Wrong Old Password</td></tr>";


	  endif;	 


  endif;   	  





  ?>


<tr >


	<td colspan=2 align=center><font face=arial size=4 color=<?php echo $dark?>><b>Change Password</b></font></td>


</tr>


<tr  1 bgcolor=silver>


	<td colspan=2 align=center><img width=1 height=1></td>


</tr>





<tr s11 bgcolor=<?php echo $body?>>


	<td>Old Password </td><td> <input type=text  name=old_pass  size=6></td>


</tr>





<tr s11 bgcolor=<?php echo $body?>>


	<td>New Password </td><td> <input type=text  name=pass  size=6></td>


</tr>


<tr 1 bgcolor=silver>


	<td colspan=2 align=center><img width=1 height=1></td>


</tr>


<tr s11>


	<td align=center colspan=2><input type=submit name='SBMT' value="Change Password" > </td>


</tr></table></form>


<?


include("footer-top.php");


?>