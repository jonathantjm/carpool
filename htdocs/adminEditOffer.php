<?php
include("header.php");
include("adminNavBar.php");

$locations = pg_query($db, "SELECT * FROM locations"); 

$locationError =  '';
$dateError = '';
$timeError = '';
$advertisementID = $_GET['id'];
$result = pg_query_params($db, 'SELECT * FROM advertisements WHERE advertisementID = $1', array($advertisementID));
$adv_row = pg_fetch_array($result);

if (isset($_POST['submit'])) {

    $startLocation = $_POST['start_location'];
    $endLocation = $_POST['end_location'];
    $pickupTime = $_POST['time_of_pickup'];
    $pickupDate = $_POST['date_of_pickup'];
    $newStatus = $_POST['offer_status'];
    $newSelect = $_POST['select_status'];

    pg_query_params($db, 'UPDATE advertisements SET start_location = $2, end_location = $3, date_of_pickup = $4, time_of_pickup = $5, closed = $6, self_select = $7 WHERE advertisementID = $1', array($advertisementID, $startLocation, $endLocation, $pickupDate, $pickupTime, $newStatus, $newSelect));

    $adv_row[2] = $startLocation;
    $adv_row[3] = $endLocation;
    $adv_row[5] = $pickupDate;
    $adv_row[6] = $pickupTime;
    $adv_row[7] = $newStatus;
    $adv_row[8] = $newSelect;

    $error =  pg_last_error($db);
    if ($error == ''){
        header("Location: adminOffer.php");	
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
<body>
<h2>Edit Offer</h2>
<form action="" method="post">
<?php
$locations_array = array();
while($row = pg_fetch_array( $locations )) {
    $locations_array[] = $row[0];
}


echo "<strong>Start Location: </strong>";
echo "<select name= \"start_location\" />";
foreach ($locations_array as $location){
    if ($location == $adv_row[2]){
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
    if ($location == $adv_row[3]){
        echo "<option value =\"".$location."\" selected>".$location."</option>";
    }
    else{
        echo "<option value =\"".$location."\" >".$location."</option>";
    }
}
echo "</select><br/></br>";
echo "<span style=\"color:red\">" . $locationError . "</span>";
echo "</div>";

?>
    <div class='form-group'>
        <label for="inputPickupTime">Time of pick-up: </label>
        <?php echo "<input type='time' name='time_of_pickup' class='form-control' id='inputPickupTime' value='" . $adv_row[6] . "' required>";?>
        <span style="color:red"><?php echo $timeError;?></span>
    </div>
    <div class='form-group'>
        <label for="inputPickupDate">Date of pick-up: </label>
        <?php echo "<input type='date' name='date_of_pickup' class='form-control' id='inputPickupDate' value='" . $adv_row[5] . "' required>";?>
        <span style="color:red"><?php echo $dateError;?></span>
    </div>
    <div class='form-group'>
        <label for="offerStatus">Offer status: </label>
        <?php
            if($adv_row[7] == 't'){
                echo "<input type=\"radio\" name=\"offer_status\" value = \"t\" checked/> Open ";
                echo "<input type=\"radio\" name=\"offer_status\" value = \"f\"/> Closed ";
            }else{
                echo "<input type=\"radio\" name=\"offer_status\" value = \"t\"/> Open ";
                echo "<input type=\"radio\" name=\"offer_status\" value = \"f\" checked/> Closed ";

            }
        ?>
    </div>
    <div class='form-group'>
        <label for="offerStatus">Driver self-select: </label>
        <?php
            if($adv_row[8] == 't'){
                echo "<input type=\"radio\" name=\"select_status\" value = \"t\" checked/> Yes ";
                echo "<input type=\"radio\" name=\"select_status\" value = \"f\"/> No ";
            }else{
                echo "<input type=\"radio\" name=\"select_status\" value = \"t\"/> Yes ";
                echo "<input type=\"radio\" name=\"select_status\" value = \"f\" checked/> No ";

            }
        ?>
    </div>
<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
