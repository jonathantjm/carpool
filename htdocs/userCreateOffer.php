<?php
include("header.php");
include("userNavBar.php");

$locations = pg_query($db, "SELECT * FROM locations"); 
$max_advertisementID = pg_query($db, "SELECT max(advertisementID) FROM advertisements"); 

if (isset($_POST['submit'])) {
	
	echo time();
	
	$advertisementID = pg_fetch_array($max_advertisementID)[0] + 1;
	$email = $_SESSION['user'];
	$start_location = $_POST['start_location'];
	$end_location = $_POST['end_location'];
	$date_of_pickup = $_POST['date_of_pickup'];
	$time_of_pickup = $_POST['time_of_pickup'];
	$self_select = $_POST['self_select'];
	
	pg_query_params("INSERT INTO 
		advertisements (advertisementid, email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select)
		VALUES ($1, $2, $3, $4, NOW(), $5, $6, $7)",
		array($advertisementID, $email, $start_location, $end_location, $date_of_pickup, $time_of_pickup, $self_select));
	
	echo pg_last_error($db);
	
	header("Location: userOffer.php");

}
?>

<html>

<form action="" method="post">

<div>

<?php
$locations_array = array();
while($row = pg_fetch_array( $locations )) {
	$locations_array[] = $row[0];
}


echo "<strong>Start Location: *</strong>";
//echo "<select name= 'start_location' />";
//echo "<option value= '' selected disabled hidden /> Choose here </option>";
echo "<select name= \"start_location\" />";
echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
foreach ($locations_array as $location){
	echo "<option value ='".$location."' >".$location."</option>";
}
echo "</select><br/>";

echo "<strong>End Location: *</strong>";
//echo "<select name='end_location' />";
//echo "<option value= '' selected disabled hidden /> Choose here </option>";
echo "<select name=\"end_location\" />";
echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
foreach ($locations_array as $location){
	echo "<option value ='".$location."' >".$location."</option>";
}
echo "</select><br/>";

?>

<strong>Date Of Pickup: *</strong> <input type="date" name="date_of_pickup" /><br/>

<strong>Time Of Pickup: *</strong> <input type="time" name="time_of_pickup" /><br/>

<strong> Would you like to select your own riders?: </strong>
<input type = "hidden" value = "f" name = "self_select">
<input type = "checkbox" name = "self_select" value = "t"><br/>

<p>* required</p>

<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
