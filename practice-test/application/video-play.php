<?php  
include("../configs/config.php");
include("../configs/license.php");
include("../configs/common.php");
include("../configs/array.php");

$video=getDataByWhere('file','*',array("id"=>$_GET['id']));
$file=$video['file'];
if(substr_count($file,"youtu.be"))
{	
    $e=end(explode("/",$file));
}
else
{
	$e1=explode("?v=",$file);
	$e=$e1[1];
}			
 
$picture="http://img.youtube.com/vi/" .$e."/mqdefault.jpg";
$youtube='<iframe width="714" height="482" src="https://www.youtube.com/embed/'.$e.'?autoplay=1" frameborder="0"  allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
	 
?>
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="window.parent.location.reload()">&times;</button>
<h4 class="modal-title"><h3><?=$video['title']?></h3></h4>

</div>

<div class="modal-body" style='height:500px'>
<?=$youtube;?>
	
</div>

 
</div>