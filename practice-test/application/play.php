
<?
include("../configs/license.php");
include("../configs/common.php");
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&times;</button>
    <h4 class="modal-title">Play Video</h4>
</div>
<div class="modal-body"><div id='response'></div>
<?
if($_GET['file'])
{
	$e=explode(".",$_GET['file']);
	if(strtolower($e[1])=="avi")
	{
		?>
		<object data="<?=$vpath?><?=$_GET['file'];?>" width="360" height="250"> <param name="src" value="<?=$vpath?><?=$_GET['file'];?>" /> </object>
		 

        <?
	}
	else
	{
		?>
			<video width="780" height="540" controls autoplay>
		    <source src="<?=$vpath?><?=$_GET['file'];?>" type="video/mp4">
		   
		    </video>
		  <?
	}	
}	
?>
</div>
 

