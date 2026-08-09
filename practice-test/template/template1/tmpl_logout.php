<?

if(empty($_SESSION['user_id']))

{

 //header("location:".$_SERVER['HTTP_HOST']);
  header("location:" . $vpath . "index.php");

}

session_start();

session_unset();

session_unregister();

session_destroy();

//header("location:".$_SERVER['HTTP_HOST']);
header("location:" . $vpath . "index.php");

?>
