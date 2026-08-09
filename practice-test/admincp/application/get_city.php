<?php
include('../configs/config.php');

if($_POST['country_id'])
{


	$stmt = mysqli_query($con,"SELECT * FROM " . $prev. "city WHERE  country_id=" . $_POST['country_id'] . " and status='Y'");

	if(@mysqli_num_rows($stmt))
	{
		echo"<option selected='selected'>Select City</option>";
		while($row=mysqli_fetch_array($stmt))
		{
		?>
		<option value="<?php echo $row['city_id']; ?>"><?php echo $row['city_name']; ?></option>
		<?php
	    }
	}
}
?>
