<?

if(empty($_SESSION['user_id']))

{

  header("location:http://".$_SERVER['HTTP_HOST']."/education");

}
session_start();
session_unset();
session_unregister();
session_destroy();
header("location:http://".$_SERVER['HTTP_HOST'] . "/education");
?>











