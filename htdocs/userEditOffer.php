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

$locationError = '';
$dateError = '';
$timeError = '';

$start_location = $adv_row[0];
$end_location = $adv_row[1];
$date_of_pickup = $adv_row[2];
$time_of_pickup = $adv_row[3];
$self_select = $adv_row[4];

if (isset($_POST['submit'])) {
	
	$start_location = $_POST['start_location'];
	$end_location = $_POST['end_location'];
	$date_of_pickup = $_POST['date_of_pickup'];
	$time_of_pickup = $_POST['time_of_pickup'];
	$self_select = $_POST['self_select'];

	pg_query_params($db, 'UPDATE advertisements SET start_location = $1, end_location = $2, date_of_pickup = $3, time_of_pickup = $4, self_select = $5 
		WHERE advertisementid = $6',
		array($start_location, $end_location, $date_of_pickup, $time_of_pickup, $self_select, $advertisementID));

	$error = pg_last_error($db);

	if ($error == ''){
		header("Location: userOffer.php");	
	}
	else if(strpos($error, 'same_start_end_location') !== false){		
		$locationError = 'Cannot have the same start and end location!';
	}
	else if(strpos($error, 'pickup_date_before_current_date') !== false){
		$dateError = 'Date cannot be before current date!';
	}
	else if(strpos($error, 'pickup_time_before_current_time') !== false){
		$timeError = 'Time is before current time!';
	}
	else {
		echo $error;
	}

}

?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<h2>New:</h2>
<form action="" method="post">

	<?php
	$locations_array = array();
	while($row = pg_fetch_array( $locations )) {
		$locations_array[] = $row[0];
	}

	echo "<div class = \"form-group\">";
	echo "<lael for =\"input_start_location\">Start Location: *</label>";
	echo "<select class = \"form-control\" id = \"input_start_location\" name= \"start_location\" required/>";
	foreach ($locations_array as $location){
		if ($start_location == $location){
			echo "<option value ='".$location."' selected>".$location."</option>";
		}
		else{
			echo "<option value ='".$location."' >".$location."</option>";
		}
	}
	echo "</select><br/>";
	echo "</div>";

	echo "<div class = \"form-group\">";
	echo "<lael for =\"input_end_location\">End Location: *</label>";
	echo "<select class = \"form-control\" id = \"input_end_location\" name= \"end_location\" required/>";
	foreach ($locations_array as $location){
		if ($end_location == $location){
			echo "<option value ='".$location."' selected>".$location."</option>";
		}
		else{
			echo "<option value ='".$location."' >".$location."</option>";
		}
	}
	echo "</select><br/>";
	echo "<span style=\"color:red\">" . $locationError . "</span>";
	echo "</div>";
	?>

	<div class = "form-group">
	<label for = "date_input">Date Of Pickup: *</label>
	<input type="date" class = "form-control" id = "date_input" name="date_of_pickup" value = <?php echo $date_of_pickup?> required/><br/>
	<span style="color:red"><?php echo $dateError;?></span>
	</div>

	<div class = "form-group">
	<label for = "time_input">Time Of Pickup: *</label>
	<input type="time" class = "form-control" id = "time_input" name="time_of_pickup" step = "900" value = <?php echo $time_of_pickup?> required/><br/>
	<span style="color:red"><?php echo $timeError;?></span>
	</div>

<strong> Would you like to select your own riders?: </strong> 
<input type = "hidden" value = "f" name = "self_select">
<?php 
if ($self_select == t){
	echo "<input type = \"checkbox\" name = \"self_select\" value = \"t\" checked><br/>";
}
else{
	echo "<input type = \"checkbox\" name = \"self_select\" value = \"t\"><br/>";
}
?>

<p>* required</p>

<input type="submit" name="submit" value="Submit">

</form>

</html>
