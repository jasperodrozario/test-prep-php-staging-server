<div id="tabs">
<ul>
    <li ><a href='#tabs-1'>Tutor Login</a></li> 
  	<?
  	if($_REQUEST['user_id'])
  	{
		?>
		<li ><a id='tutor-info' href='tutor_profile_form?action=tutor-info&user_id=<?=$_REQUEST['user_id']?>'><span>Info</span></a></li> 
 	
	 	<!--<li ><a href='tutor_profile_form?action=tutor-skill&user_id=<?=$_REQUEST['user_id']?>'>Skills</a></li>
	    <li ><a href='tutor_profile_form?action=students&user_id=<?=$_REQUEST['user_id']?>'>List of Students</a></li>
	    
	    <li ><a href='tutor_profile_form?action=enquires&user_id=<?=$_REQUEST['user_id']?>'>Enquires</a></li>
	    <li ><a href='tutor_profile_form?action=reviews&user_id=<?=$_REQUEST['user_id']?>'>Reviews</a></li>-->
	    <li ><a href='tutor_profile_form?action=notes&user_id=<?=$_REQUEST['user_id']?>'>Notes</a></li>
	    <li ><a href='tutor_profile_form?action=mail&user_id=<?=$_REQUEST['user_id']?>'>Send Email</a></li>
		<?
	}
	?>
  </ul>
  
 <hr>
<div id="tabs-1" style='margin-top:20px'>
<?
    
   include("tmpl_tutor_profile_form.php");
   
  
?> 
</div></div>

<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
<script charset="utf-8" src="kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="kindeditor/lang/en.js"></script>
<script>
var editor;
KindEditor.ready(function(K) 
{
	editor = K.create('#contents', {
		allowFileManager : true
	});
});
</script>


