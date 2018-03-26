<?php
include("header.php");
include("userNavBar.php");

$locations = pg_query($db, "SELECT * FROM locations"); 

//echo $_GET['id'];
//echo $_GET['mail'];

$advertisementID = $_GET['id'];
$email = $_SESSION['user'];
$creator = pg_query($db, "SELECT email_of_driver FROM advertisements WHERE advertisementid = '" . $advertisementID . "';");
if(is_null($advertisementID)){
	$message = "Oops something went wrong!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}
if(pg_num_rows($creator == 0)){
	$message = "Advertisement not found!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";	
}
if(pg_fetch_array($creator)[0] != $email){
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}

$result = pg_query($db, "SELECT start_location, end_location, date_of_pickup, time_of_pickup, self_select FROM advertisements WHERE advertisementid = '" . $advertisementID . "';");
$adv_row = pg_fetch_array($result);

if (isset($_POST['submit'])) {
	
	$start_location = $_POST['start_location'];
	$end_location = $_POST['end_location'];
	$date_of_pickup = $_POST['date_of_pickup'];
	$time_of_pickup = $_POST['time_of_pickup'];
	$self_select = $_POST['self_select'];

	pg_query_params($db, 'UPDATE advertisements SET start_location = $1, end_location = $2, date_of_pickup = $3, time_of_pickup = $4, self_select = $5 
		WHERE advertisementid = $6',
		array($start_location, $end_location, $date_of_pickup, $time_of_pickup, $self_select, $advertisementID));

	echo pg_last_error($db);

	header("Location: userOffer.php");

}

?>

<html>

<h2>New:</h2>
<form action="" method="post">

<div>

<?php
$locations_array = array();
while($row = pg_fetch_array( $locations )) {
	$locations_array[] = $row[0];
}


echo "<strong>Start Location: *</strong>";
echo "<select name= \"start_location\" />";
foreach ($locations_array as $location){
	if ($location == $adv_row[0]){
		echo "<option value =\"".$location."\" selected>".$location."</option>";
	}
	else{
		echo "<option value =\"".$location."\" >".$location."</option>";
	}
}
echo "</select><br/>";

echo "<strong>End Location: *</strong>";
echo "<select name=\"end_location\" />";
foreach ($locations_array as $location){
	if ($location == $adv_row[1]){
		echo "<option value =\"".$location."\" selected>".$location."</option>";
	}
	else{
		echo "<option value =\"".$location."\" >".$location."</option>";
	}
}
echo "</select><br/>";

?>

<strong>Date Of Pickup: *</strong> <input type="date" name="date_of_pickup" value = "<?php echo $adv_row[2]?>"/><br/>

<strong>Time Of Pickup: *</strong> <input type="time" name="time_of_pickup" value = "<?php echo $adv_row[3]?>" step = "900"/><br/>

<strong> Would you like to select your own riders?: </strong> 
<input type = "hidden" value = "f" name = "self_select">
<?php 
if ($adv_row[4] == t){
	echo "<input type = \"checkbox\" name = \"self_select\" value = \"t\" checked><br/>";
}
else{
	echo "<input type = \"checkbox\" name = \"self_select\" value = \"t\"><br/>";
}
?>

<p>* required</p>

<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
