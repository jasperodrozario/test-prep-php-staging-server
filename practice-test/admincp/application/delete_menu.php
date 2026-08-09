<?
include("../configs/config.php");  

$del_id = $_POST['del_id'];

$sql = "DELETE FROM ".$prev."menu WHERE menu_id = ".$del_id;
mysqli_query($con,$sql);
echo "sucess";


?>