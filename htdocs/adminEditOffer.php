<?php
include("header.php");
include("adminNavBar.php");

//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];

if($isAdmin == 'f') {
    $message = "You are not authorized to view this page!";
    echo "<script type='text/javascript'>alert('$message');
    window.location.href='login.php';
    </script>";
}

$locations = pg_query($db, "SELECT * FROM locations"); 

$advertisementID = $_GET['id'];
$result = pg_query_params($db, 'SELECT * FROM advertisements WHERE advertisementID = $1', array($advertisementID));
$adv_row = pg_fetch_array($result);

if (isset($_POST['submit'])) {

    $newDateAndTime = $_POST['date_and_time'];
    $startLocation = $_POST['start_location'];
    $endLocation = $_POST['end_location'];
    $pickupTime = $_POST['time_of_pickup'];
    $pickupDate = $_POST['date_of_pickup'];
    $newStatus = $_POST['offer_status'];
    $newSelect = $_POST['select_status'];

    pg_query_params($db, 'UPDATE advertisements SET creation_date_and_time = $2, start_location = $3, end_location = $4, date_of_pickup = $5, time_of_pickup = $6, closed = $7, self_select = $8 WHERE advertisementID = $1', array($advertisementID, $newDateAndTime, $startLocation, $endLocation, $pickupDate, $pickupTime, $newStatus, $newSelect));

    $adv_row[2] = $startLocation;
    $adv_row[3] = $endLocation;
    $adv_row[4] = $newDateAndTime;
    $adv_row[5] = $pickupDate;
    $adv_row[6] = $pickupTime;
    $adv_row[7] = $newStatus;
    $adv_row[8] = $newSelect;

    if (preg_match('/advertisementid/i', pg_last_error($db))) {
        echo 'Advertisement ID does not exist.';		
    } else {
        header("Location: adminOffer.php");
    }
}

?>

<html>
<body>
<h2>Edit Offer</h2>
<form action="" method="post">
    <div class='form-group'>
        <label for="inputDateAndTime">Date and time created: </label>
        <?php echo "<input type='text' name='date_and_time' class='form-control' id='inputDateAndTime' value='" . $adv_row[4] . "' required>";?>
    </div>

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
echo "</select><br/>";

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
echo "</select><br/>";

?>
    <div class='form-group'>
        <label for="inputPickupTime">Time of pick-up: </label>
        <?php echo "<input type='time' name='time_of_pickup' class='form-control' id='inputPickupTime' value='" . $adv_row[6] . "' required>";?>
    </div>
    <div class='form-group'>
        <label for="inputPickupDate">Date of pick-up: </label>
        <?php echo "<input type='date' name='date_of_pickup' class='form-control' id='inputPickupDate' value='" . $adv_row[5] . "' required>";?>
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
