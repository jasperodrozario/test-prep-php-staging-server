  <!--<video width="400" controls>
  <source src="mov_bbb.mp4" type="video/mp4">
  <source src="mov_bbb.ogg" type="video/ogg">
  Your browser does not support HTML5 video.
</video>-->

<?
include("configs/license.php");
include("configs/common.php");
$video=getFldValueById("course","video","course_id=" . $_REQUEST['id']);
 ?> 
 <iframe id="popup-youtube-player" src="<?=$video?>" frameborder="0" allowfullscreen="true" allowscriptaccess="always"></iframe>
 