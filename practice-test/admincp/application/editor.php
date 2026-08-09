<?
include("header.php");
validate($_SESSION['user_id']);
?>
<!---Start-->
<?
$page_date=date("Y-m-d");
if(!$last_modify){$last_modify=$page_date;}
$msg="";$admin_body="";$flag=1;
if($SBMT_REG):
  if(!get_magic_quotes_runtime() && $REMOTE_ADDR=="127.0.0.1"):
	  $page_source=addslashes($page_source);
  endif;
  $page_source=htmlentities($page_source);
  if($_REQUEST['page_id']):
   	  $r=mysqli_query($con,"update " . $prev. "page set page_title=\"" . $page_title . "\",bookmark=\"" . $bookmark . "\",bg=\"" . $bg . "\",bgcolor=\"" . $bgcolor . "\",lpage=\"" . $lpage . "\",page_source=\"" . $page_source . "\",meta_desc=\"" . $meta_desc . "\",meta_tags=\"" . $meta_tags . "\",admin_chk=\"" . $admin_chk . "\",total_traffic=\"" . $total_traffic . "\",temaplte_id=\"" . $_REQUEST[tid] . "\" where page_id=" . $_REQUEST['page_id']);
  else:
	  $r=mysqli_query($con,"select * from " . $prev. "page where lpage=\"" . $lpage . "\"");
      if(!@mysqli_num_rows($r)):
  	 	   $r=mysqli_query($con,"insert into " . $prev. "page(user_id,lpage,page_title,page_source,page_date,last_modify,admin_chk,bg,bgcolor,total_traffic,bookmark,meta_desc,meta_tags,template_id)
		    values (\"" . $_SESSION['user_id'] . "\",\"" . $lpage . "\",\"" . $page_title . "\",\"" . $page_source . "\",\"" . $page_date . "\",\"" . $last_modify . "\",\"" . $admin_chk . "\",\"" . $bg . "\",\"" .$bgcolor . "\",\"" .$total_traffic . "\",\"" .$bookmark . "\",\"" .$meta_desc . "\",\"" .$meta_tags . "\",\"" .$tid . "\")");
		   $_REQUEST['page_id']=mysqli_insert_id($con);
		   
		   $rr=mysqli_query($con,"select * from " . $prev. "message where tid=" . $_REQUEST[tid]);
 	       if(@mysqli_num_rows($rr)):
		   		while($dd=@mysqli_fetch_array($rr)):
		      		mysqli_query($con,"insert into " . $prev. "msg(mSubject,mBody,mDay,mType,user_id,page_id) values(\"" . $dd[mSubject] . "\",\"" . str_replace("\"","'",$dd[mBody]) . "\",\"" . $dd[mDay] . "\",\"" . $dd[mType] . "\",\"" . $_SESSION['user_id'] . "\",\"" . $_REQUEST['page_id'] . "\")");
	       		endwhile;
		   endif;
		   
           $h=mysqli_query($con,"select * from " . $prev. "form_temp where tid=" . $tid . " and type='T'");
           if(@mysqli_num_rows($h)):
              while($dd=mysqli_fetch_array($h)):
                  mysqli_query($con,"insert into " . $prev. "form_temp(tid,fld_text,fld_type,fld_desc,type,compulsory,fld_order) values (\"" . $_REQUEST['page_id'] . "\",\"" . $dd[fld_text] . "\",\"" . $dd[fld_type] . "\",\"" . $dd[fld_desc] . "\",\"P\",\"" . $dd[compulsory] . "\",\"" . $dd[fld_order] . "\")");
              endwhile;
           else:
               $r=mysqli_query($con,"insert into " . $prev. "form_temp(tid,fld_text,fld_type,type,compulsory) values (\"" . $_REQUEST['page_id'] . "\",\"Name\",\"1\",\"P\",\"Y\")");
               $r=mysqli_query($con,"insert into " . $prev. "form_temp(tid,fld_text,fld_type,type,compulsory) values (\"" . $_REQUEST['page_id']  . "\",\"Email\",\"1\",\"P\",\"Y\")");
               $r=mysqli_query($con,"insert into " . $prev. "form_temp(tid,fld_text,fld_type,type,compulsory) values (\"" . $_REQUEST['page_id']  . "\",\"Phone\",\"1\",\"P\",\"Y\")");
           endif;       
       else:
	       $flag=1;
           $msg="<font face=verdana size=1 color=red><b>Sorry page name in use try something else.</b></font>";
           echo"<div align=center 2>$msg<br><br><a href='editor.php' >Back</a></div>";
           include("footer-top.php");
           exit();
       endif;	
  endif;    
  if($r):
	   if($background!=""):
		   $ext=substr($background_name,-3,3);
		   copy($background,"background/" . $_REQUEST['page_id'] . "." . $ext);
       endif;
	  $msg="<font face=verdana size=1 color=$dark><b>Update Successfull.</b></font>";
  endif;
elseif($_REQUEST[del]):
    $r=mysqli_query($con,"delete from " . $prev. "page where user_id=" . $_SESSION['user_id'] . " and page_id=" . $_REQUEST[del]);
	$r=mysqli_query($con,"delete from " . $prev. "lead where user_id=" . $_SESSION['user_id'] . " and page_id=" . $_REQUEST[del]);
	$r=mysqli_query($con,"delete from " . $prev. "traffic where user_id=" . $_SESSION['user_id'] . " and page_id=" . $_REQUEST[del]);
	if($r):
		$msg="<font face=verdana size=1 color=dark><b>Delete successfull.</b></font>";	 
		echo"<script>window.location='edit_leadpage.php';</script>"; 
	endif;
endif; 
if($msg):
    echo"<div align=center 2>$msg</div>";
endif;    
    $q="select * from " . $prev. "template where tid=" . $tid;
    if($_REQUEST['page_id']){$type="P";
    $q="select * from " . $prev. "page where page_id=" . $_REQUEST['page_id'];}
    else{$type="T";}
	$r=mysqli_query($con,$q);
    $d=@mysqli_fetch_array($r);
	if(!$tid){$tid=$d[template_id];}
    
    $rr=mysqli_query($con,"select * from " . $prev. "form_action where type='" . $type . "' and tid=" . $_REQUEST['page_id']);
    $form    =@mysqli_result($rr,0,"form_source");
    if(!$form):
      include("form.php");
    endif;
    ?>
     <?php if(!$_REQUEST['page_id']){echo"<div align=left red>&nbsp;&nbsp;&nbsp;<b>Note :</b><br>&nbsp;&nbsp;&nbsp;Do NOT Try And Edit Your Page Here.  You'll Have The Complete Editor After You Click Save And Save The Page Under Your User ID.</div><br>";}?>
	<script type="text/javascript" src="./FCKeditor/fckeditor.js"></script>
	<script type="text/javascript">
	window.onload = function()
	{
	// Automatically calculates the editor base path based on the _samples directory.
	// This is usefull only for these samples. A real application should use something like this:
	// oFCKeditor.BasePath = '/FCKeditor/' ;	// '/fckeditor/' is the default value.
	var sBasePath = "./FCKeditor/";

	var oFCKeditor = new FCKeditor('page_source') ;
	oFCKeditor.BasePath	= sBasePath ;
	oFCKeditor.ReplaceTextarea() ;
	}
	</script>
   <table border=0 cellpadding=4 cellspacing=1 width=100% align=center bgcolor=<?php echo $light?> style="border:solid 1px #b4b4b4">
   <form name=register method=post action="<?php echo $_SERVER['PHP_SELF']?>" onSubmit="javascript:return ValidPage(this);" enctype="multipart/form-data">
   <input type=hidden name=page_id value='<?php echo $_REQUEST['page_id']?>'>
   <input type=hidden name=tid value='<?php echo $tid?>'>
   
   <?php if($_REQUEST['page_id'])
   {?>
      <tr bgcolor=<?php echo $light?>><td  height=25 colspan=2 align=center><input type=button  value='Edit Autoresponder Form' style="width:130; height:20;font-face:tahoma;font-size:11px;border:solid 1px;background-color:#DDE7F7" onClick="Javascript:window.open('admin/" . $prev. "form.php?page_id=<?php echo $_REQUEST['page_id']?>&type=P&user_id=<?php echo $_SESSION['user_id']?>&tid=<?php echo $tid?>','_newx','width=600,height=600,scrollbars=yes,addressbar=no');">&nbsp;
      <input type=button  value='Edit Thank You Page' style="width:120; height:20;font-face:tahoma;font-size:11px;border:solid 1px;background-color:#DDE7F7" onClick="Javascript:window.open('admin/thankyou_editor.php?page_id=<?php echo $_REQUEST['page_id']?>&type=P&user_id=<?php echo $_SESSION['user_id']?>&tid=<?php echo $tid?>','_newx','width=800,height=600,scrollbars=yes,addressbar=no');">&nbsp;
      <input type=button  value='Add Popup' style="width:80; height:20;font-face:tahoma;font-size:11px;border:solid 1px;background-color:#DDE7F7" onClick="Javascript:window.open('popup.builder.php?page_id=<?php echo $_REQUEST['page_id']?>','_newx','width=800,height=600,scrollbars=yes,addressbar=no');">&nbsp;<input type=button  value='Add Audio' style="width:100; height:20;font-face:tahoma;font-size:11px;border:solid 1px;background-color:#DDE7F7" onClick="Javascript:window.open('audio.builder.php?page_id=<?php echo $_REQUEST['page_id']?>','_newx','width=800,height=600,scrollbars=yes,addressbar=no');">&nbsp;
	   <?php if($_SESSION['member_type']=="C"){?><input  type=button  value='My Image Library' style="width:110; height:20;font-face:tahoma;font-size:11px;border:solid 1px;background-color:#DDE7F7" onClick="Javascript:window.open('photo.builder.php?page_id=<?php echo $_REQUEST['page_id']?>','_newx1','width=800,height=600,scrollbars=yes,addressbar=no,resizable=yes');">&nbsp;
	 <input type=button  value='EZLead Image Library' style="width:120; height:20;font-face:tahoma;font-size:11px;border:solid 1px;background-color:#DDE7F7" onClick="Javascript:window.open('image.library.php','_newx1','width=800,height=600,scrollbars=yes,addressbar=no,resizable=yes');"><?}?>
	  </td></tr>
   <?}?>
   
  <tr bgcolor=#ededed><td  height=25 ><font face=arial color=<?php echo $dark?> size=4><b><?php if(!$_REQUEST['page_id']){?>New Ezlead Page <?}else{echo"Page Name ";}?> </b></font></td><td><font face=arial color=<?php echo $dark?> size=4><b><u><?php if(!$_REQUEST['page_id']){echo $d["temp_name"];}else{echo $vpath .$d["lpage"] . ".htm";}?></u></b></font></td></tr>
  <tr  bgcolor=#ffffff ><td 2>Page Url</td><td 2><a 2><?php echo $vpath?></a> <input type=text name=lpage  value="<?php echo $d["lpage"]?>" size=45  style="width:20%; height:20">.htm<br><font face=verdana size=1 color=red>[Example <?php echo $vpath?>scriptfirm.htm ] </font></td></tr>
  <tr  bgcolor=#ffffff 2><td>Page Title</td><td ><input type=text name=page_title  value="<?php echo $d["page_title"]?>" size=45  style="width:80%; height:20"> </td></tr>
  <tr  bgcolor=#ffffff 2><td >Meta Keys</td><td ><textarea name=meta_tags style="width:80%; height:50" class=btn><?php echo $d["meta_tags"]?></textarea></td></tr>
  <tr  bgcolor=#ffffff 2><td>Meta Description</td><td ><textarea name=meta_desc style="width:80%; height:50" class=btn><?php echo $d["meta_desc"]?></textarea></td></tr>
  <tr  bgcolor=#ffffff 2><td>Set Traffic No.</td><td ><input type=text name=total_traffic  value="<?php echo $d["total_traffic"]?>" size=5  style="width:10%; height:20"> </td></tr>
  <tr  bgcolor=#ffffff 2><td>Bookmark Button Text</td><td ><input type=text name=bookmark  value="<?php echo $d["bookmark"]?>" size=5  style="width:40%; height:20"> </td></tr>
  <tr  bgcolor=#dddddd 2><td >Page Source</td><td align=right>&nbsp;&nbsp;</td></tr>
  <tr  bgcolor=#ffffff 2><td >Page Background Color</td><td ><input type=radio name=bg value="bgcolor" <?php if($d["bg"]=="bgcolor"){echo"checked";}?> ><input type=text name=bgcolor value="<?php echo $d["bgcolor"]?>" style="width:131; height:20;font-face:tahoma;font-size:11px;border:solid 1px"> <input type=button value=BgColor onClick="javascript:window.open('popups/select_color2.html','_new','width=160,height=20,scrollbars=no,addressbar=no');" style="width:70; height:20;font-face:tahoma;font-size:11px;border:solid 1px"></td></tr>
  <tr  bgcolor=#ffffff 2><td >Page Background Image</td><td ><input type=radio name=bg value="background" <?php if($d["bg"]=="background"){echo"checked";}?>><input type=file name=background style="width:200; height:20;font-face:tahoma;font-size:11px;border:solid 1px"></td></tr>
  <tr  bgcolor=#ffffff 2><td colspan=2  valign=top><textarea name="page_source"><?php echo stripslashes($d["page_source"]);?> </textarea></td></tr>
  <tr bgcolor=#ededed><td colspan=2 height=25 align=center ><input type=submit  name='SBMT_REG'  value='Save' style="width:20%; height:25px">&nbsp;<input type=button onclick="javascript:if(confirm('Are You Sure You Want To Delete This Page')){window.location='<?php echo $_SERVER['PHP_SELF']?>?del=<?php echo $_REQUEST['page_id']?>';}" name='SBMT_DELT' value='Delete Page' style="width:20%; height:25px" >&nbsp;<?php if($_REQUEST['page_id']){?><input type=button onClick="javascript:window.open('viewpage.php?type=P&page_id=<?php echo $_REQUEST['page_id']?>','_new','width=1000,height=600,scrollbars=yes');" value='View In Browser' style="width:20%; height:25px" ><?}?>
  <input type=hidden name='user_id' value="<?php echo $_SESSION['user_id']?>">
  <input type=hidden name='fname' value="<?php echo $fname?>">
  <input type=hidden name='lname' value="<?php echo $lname?>">
  <input type=hidden name='email' value="<?php echo $email?>">
 </td></tr>
 <tr ><td colspan=2>
 
  <table align=center width=100% cellpadding=4 cellspacing=1 border=0 bgcolor=silver>
  <tr><td colspan=2><b>You can add the following functional tags to your lead capture page :</b></td></tr>
 <tr 5 bgcolor=silver><td><b>Tag</b></td><td><b>Description</b></td></tr>
 <tr 5 bgcolor=#ffffff><td width=20%> {form}</td><td> - This tag is on your page by default.  This is where your form will be displayed.  If you like you can add multiple forms to your page or change the location of your default form.</td></tr>
 
<tr 5 bgcolor=#ffffff><td>{bookmark} </td><td>- Place this tag on your page to give your visitor the option of adding your page to their favorites.</td></tr>
 
<tr 5 bgcolor=#ffffff><td>{tellfriend}</td><td> - Place this tag on your page to give your visitor the option of telling some friends about your page.</td></tr>
 
<tr 5 bgcolor=#ffffff><td>{date}</td><td> - Place this tag on your page to display the visitors current date. (eg.. February 4th 2007)</td></tr>
 
<tr 5 bgcolor=#ffffff><td>{datetime} </td><td>- Place this tag on your page to display the visitors current time & date. (eg.. 10:57am, Feb 4th 2007)</td></tr>
 
<tr 5 bgcolor=#ffffff><td>{audio}</td><td> - Place this tag on your page and your audio message will appear (if you have recorded & loaded one)</td></tr></table>
<br>
 <table align=center width=100% cellpadding=4 cellspacing=1 border=0 bgcolor=silver>
 <tr><td>
 <?php include("../newtutorials/tutorialmenutest.htm");?>
 </td></tr>
 </table>
 </td></tr>
 </form></table>
 <?
 include("footer-top.php");?>
 <div align=center><font face=verdana size=2 color=red>NOTE:  This Site & Template Editors Are Most Compatible With Internet Explorer.<br> Firefox & Safari Users May Experience Problems.</font></div>