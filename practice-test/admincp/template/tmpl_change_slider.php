  <form action="<?=basename($_SERVER['PHP_SELF'],".php");?>" method="post" enctype="multipart/form-data" name="form1" onsubmit="return(formValidate());">
  <TABLE width=100% border=0 cellPadding=4 cellSpacing=1 class='table table-responsive table-borderless'>
    <TBODY>
    <tr><td width="20%" height="25" align=left valign="top" >
	 Title 1 : </td><td ><input type='text' name='title1' value="<?=$db_value['title1'];?>" class='form-control' reqired placeholder="Title 1"></td></tr>
 	 <tr><td width="20%" height="25" align=left valign="top" >
	 title 2 : </td><td ><input type='text' name='title2' value="<?=$db_value['title2'];?>" class='form-control' reqired placeholder="Title 2"></td></tr>
 	 <tr><td width="20%" height="25" align=left valign="top" >
	 Description : </td><td ><input type='text' name='description' value="<?=$db_value['text'];?>" class='form-control' reqired placeholder="Description"></td></tr>
	<tr></tr><TD height="25" align=left valign="top" >Slider Image : </TD>
	<TD ><input type="file" name="slider" /><br /><font color='brown'>[]</font>
	<?
	if($db_value["slider"]):
	?>
	<center><br /><img src="<?php echo $configs['vpath'] ?><?php echo $db_value['slider'];?>" width="500" height="250"></center>
	<?
	endif; 
	?>
	</TD></TR>

	<tr><td width="20%" height="25" align=left valign="top" >
	 Link : </td>
 	 <td ><input type='text' name='link' value="<?=$db_value['link'];?>" class='form-control' reqired placeholder="education.com"></td></tr>
	
	 
	<tr><td width="20%" height="25" align=left valign="top" >
	 Set Display Order : </td>
 	 <td ><input type='text' name='ord' value="<?=$db_value['ord'];?>" class='form-control' reqired placeholder="1"></td></tr>
      <TR>
        <TD  height="25" align=left valign="top" > </TD>
        <TD >
		<input type="hidden" id="id" name="id" value="<?php echo  $db_value['id']; ?>"  />
		<INPUT type='submit' value="Submit" name='submit' class='btn btn-success' >&nbsp;                    </TD>
      </TR>
    </TBODY>
  </TABLE>
</form>
<br><br>
<h3>Slider List</h3>

<hr>
<table id="table-content" width="100%" class="table" border="0" cellspacing="1" cellpadding="4">
	  <thead>
	  <tr  >
		<td height="25" width="5%" align="left"><b>Id</b></td>
		<td height="25" width="10%" align="left"><b>Slider Image</b></td>
 		<td height="25" width="10%" align="left"><b>Title</b></td>
		<td height="25" width="15%" align="center"><b>Display Order</b></td>

		<td width="15%" align="center"><b>Options</b></td>
	  </tr>
	  </thead>
	  <tbody>
	<?php
	 

	foreach($sliders as $main_fetch) 
	{
		$j++;
		 
	   ?>
	   <tr >
		<td align="left"><?php echo $main_fetch['id'];?></td>
		<td align="left"><img src="<?=$configs['vpath']?>image.php?src=<?php echo $configs['vpath'].$main_fetch['slider'];?>&w=100"></td> 
		
 		<td align="left"><?php echo $main_fetch['title1'];?></td>
		<td align="center"><?php echo $main_fetch['ord'];?></td>
		<td align="center"><a href="change_slider?id=<?php echo $main_fetch['id'];?>"><img src='images/edit.png' alt='Delete' height=24  border='0'></a>
		<?php  echo" | <a  href=\"javascript:if(confirm('Are you sure you want to delete it?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?delete=" . $main_fetch['id'] . "';}\"><img src='images/delete.png'  height=16 border='0'></a>"; ?>
		</td></tr>
	    <?php
	}
    ?>
	 </tbody>
	</table>