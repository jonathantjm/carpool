<?php
ob_start();
include("header.php");
include("adminNavBar.php");

//Initialize potential errors
$emailError = "";
$locationError =  '';
$dateError = '';
$timeError = '';
$row = array('', '', '', '', '', '');

$locations = pg_query($db, "SELECT * FROM locations"); 

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $start_location = $_POST['start_location'];
    $end_location = $_POST['end_location'];
    $date_of_pickup = $_POST['date_of_pickup'];
    $time_of_pickup = $_POST['time_of_pickup'];
    $self_select = $_POST['self_select'];

    $tempResult = pg_query_params($db, "SELECT add_advertisement($1, $2, $3, $4, $5, $6)", array($email, $start_location, $end_location, $date_of_pickup, $time_of_pickup, $self_select));

    $row[0] = $email;
    $row[1] = $start_location;
    $row[2] = $end_location;
    $row[3] = $date_of_pickup;
    $row[4] = $time_of_pickup;
    $row[5] = $self_select;
    $result = pg_fetch_array($tempResult);

    $error =  $result[0];
    if ($error === ''){
		header("Location: adminOffer.php");	
    }
    else if(strpos($error, 'location') !== false){		
        $locationError = $error;
    }
    else if(strpos($error, 'hour') !== false){
        $timeError = $error;
    }
    else if (strpos($error, 'email') !== false) {
        $emailError = 'Account for this email does not exist!';
    } else {
        echo pg_last_error($db);
    }
}
?>
<html>
	<body id = 'adminpage'>
        <h2 class="text-center">Create new advertisement</h2>
		<div id="divForm">
			<form action="" method="post">
				<div class='form-group'>
					<label for="inputEmail">Email of driver</label>
					<input type="email" name="email" class='form-control' id='inputEmail' placeholder="Enter the email" value="<?php echo $row[0]; ?>" required>
					<span style='color:red'><?php echo $emailError; ?></span></br>
				</div>
<?php
$locations_array = array();
while($adv_row = pg_fetch_array( $locations )) {
	$locations_array[] = $adv_row[0];
}
echo "<strong>Start Location: </strong>";
echo "<select name= \"start_location\" />";
foreach ($locations_array as $location){
	if ($location == $row[1]){
		echo "<option value =\"".$location."\" selected>".$location."</option>";
	}
	else{
		echo "<option value =\"".$location."\" >".$location."</option>";
	}
}
echo "</select><br/></br>";
echo "<strong>End Location: </strong>";
echo "<select name=\"end_location\" />";
foreach ($locations_array as $location){
	if ($location == $row[2]){
		echo "<option value =\"".$location."\" selected>".$location."</option>";
	}
	else{
		echo "<option value =\"".$location."\" >".$location."</option>";
	}
}
echo "</select><br/></br>";
echo "<span style=\"color:red\">" . $locationError . "</span>";
?>
				<div class='form-group'>
					<label for="inputPickupDate">Date of Pickup: </label>
					<input type='date' name='date_of_pickup' class='form-control' id='inputPickupDate' value='<?php echo $row[3]; ?>'required>
					<span style="color:red"><?php echo $dateError;?></span>
				</div>
				<div class='form-group'>
					<label for="inputPickupTime">Time of Pickup: </label>
					<input type='time' name='time_of_pickup' class='form-control' id='inputPickupTime' value='<?php echo $row[4]; ?>' required>
					<span style="color:red"><?php echo $timeError;?></span>
				</div>
				<div class='form-group'>
					<label for="offerStatus">Driver self-select: </label>
<?php
	if($row[5] == 'f'){
		echo "<input type=\"radio\" name=\"self_select\" value = \"t\"/> Yes ";
		echo "<input type=\"radio\" name=\"self_select\" value = \"f\" checked/> No ";
	}else{
		echo "<input type=\"radio\" name=\"self_select\" value = \"t\" checked/> Yes ";
		echo "<input type=\"radio\" name=\"self_select\" value = \"f\" /> No ";
	}
?>
				</div>
				<button type="submit" name="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</body>
</html>
