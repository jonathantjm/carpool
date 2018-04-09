<?php
include("header.php");
include("userNavBar.php");

$checkEmail = $_SESSION['user'];
$checkResult1 = pg_query_params($db, 'SELECT is_driver FROM useraccount WHERE email = $1', array($checkEmail));
$checkResult2 = pg_fetch_array($checkResult1);

if($checkResult2[0] == 'f'){
    $message = "A non-driver cannot create offers!";
    echo "<script type='text/javascript'>alert('$message');
    window.location.href='userPage.php';
    </script>";
}

$locationError = $isUserDriver;

$locations = pg_query($db, "SELECT * FROM locations");

//$locationError = '';
$dateError = '';
$timeError = '';

$email = '';
$start_location = '';
$end_location = '';
$date_of_pickup = '';
$time_of_pickup = '';
$self_select = '';

if (isset($_POST['submit'])) {

    $email = $_SESSION['user'];
    $start_location = $_POST['start_location'];
    $end_location = $_POST['end_location'];
    $date_of_pickup = $_POST['date_of_pickup'];
    $time_of_pickup = $_POST['time_of_pickup'];
    $self_select = $_POST['self_select'];

    $tempResult = pg_query_params($db, "SELECT add_advertisement($1, $2, $3, $4, $5, $6)", array($email, $start_location, $end_location, $date_of_pickup, $time_of_pickup, $self_select));
    $result = pg_fetch_array($tempResult);

    $error =  $result[0];
    if ($error == ''){
        header("Location: userOffer.php");	
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
        echo $error;
    }
}
?>

<html>
	<body id="b5">
		<div id="divForm">
			<form action="" method="post">
<?php
$locations_array = array();
while($row = pg_fetch_array( $locations )) {
    $locations_array[] = $row[0];
}
echo "<div class = \"form-group\">";
echo "<lael for =\"input_start_location\">Start Location: *</label>";
echo "<select class = \"form-control\" id = \"input_start_location\" name= \"start_location\" required/>";
if ($start_location == ''){
    echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
}
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
if ($end_location == ''){
    echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
}
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
			<input type = "hidden" value = "f" name = "self_select"/>
			<input type = "checkbox" name = "self_select" value = "t"/><br/>
			<p>* required</p>
			<button type="submit" name="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</body>
</html>
