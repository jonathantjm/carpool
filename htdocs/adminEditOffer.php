<style type="text/css">
table.sample {
    border-width: 2px;
    border-spacing: 1px;
    border-style: solid;
    border-color: black;
    border-collapse: collapse;
    background-color: white;
}
table.sample th {
    border-width: 1px;
    padding: 1px;
    border-style: inset;
    border-color: gray;
    background-color: white;
    -moz-border-radius: ;
}
table.sample td {
    border-width: 1px;
    padding: 1px;
    border-style: inset;
    border-color: gray;
    background-color: white;
    -moz-border-radius: ;
}
</style>

<?php
include("header.php");
include("adminNavBar.php");

$locations = pg_query($db, "SELECT * FROM locations"); 
if(!$db){
    echo "cannot connect";
}

//echo $_GET['id'];
//echo $_GET['mail'];

$advertisementID = $_GET['id'];
$result = pg_query_params($db, 'SELECT * FROM advertisements WHERE advertisementID = $1', array($advertisementID));
$row = pg_fetch_array($result);

if(is_null($advertisementID)){
    echo 'No advertisement ID found.';
}

if (isset($_POST['submit'])) {

    echo $newMail = $_POST['email'];
    echo $newID = $_POST['advertisementID'];
    echo $newDateAndTime = $_POST['date_and_time'];
    echo $startLocation = $_POST['start_location'];
    echo $endLocation = $_POST['end_location'];
    echo $pickupTime = $_POST['time_of_pickup'];
    echo $pickupDate = $_POST['date_of_pickup'];
    echo $newStatus = $_POST['offer_status'];
    echo $newSelect = $_POST['select_status'];

    pg_query_params($db, 'UPDATE advertisements SET email_of_driver = $2, advertisementID = $3, creation_date_and_time = $4, start_location = $5, end_location = $6, date_of_pickup = $7, time_of_pickup = $8, closed = $9, self_select = $10 WHERE advertisementID = $1', array($advertisementID, $newMail, $newID, $newDateAndTime, $startLocation, $endLocation, $pickupDate, $pickupTime, $newStatus, $newSelect));

    echo pg_last_error($db);

    header("Location: adminOffer.php");

}

?>

<html>

<h2>Old:</h2>

<?php
echo "<table class ='sample'>";
echo "<tr>";
echo "<th>Email</th>";
echo "<th>Advertisement ID</th>";
echo "<th>Date and time created</th>";
echo "<th>Starting location</th>";
echo "<th>Ending location</th>";
echo "<th>Time of pick-up</th>";
echo "<th>Date of pick-up</th>";
echo "<th>Offer status</th>";
echo "<th>Driver self-select</th>";
echo "</tr>";
echo "<tr>";
echo "<td>" . $row[1] . "</td>";
echo "<td>" . $row[0] . "</td>";
echo "<td>" . $row[4] . "</td>";
echo "<td>" . $row[2] . "</td>";
echo "<td>" . $row[3] . "</td>";
echo "<td>" . $row[6] . "</td>";
echo "<td>" . $row[5] . "</td>";
echo "<td>" . $row[7] . "</td>";
echo "<td>" . $row[8] . "</td>";
echo "</tr>";
echo "</table>";
?>

<h2>New:</h2>

<form action="" method="post">

<div>

<strong>Email: *</strong> <input type="text" name="email" /><br/>

<strong>Advertisement ID: *</strong> <input type="text" name="advertisementID" /><br/>

<strong>Date and time created: *</strong> <input type="text" name="date_and_time" /><br/>

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

<strong>Time of pick-up: *</strong> <input type="text" name="time_of_pickup" /><br/>

<strong>Date of pick-up: *</strong> <input type="text" name="date_of_pickup" /><br/>

<strong>Offer status: *</strong><br> 
<input type="radio" name="offer_status" value = "t"/>Yes
<input type="radio" name="offer_status" value = "f"/>No
<br/>

<strong>Driver self-select: *</strong><br>
<input type="radio" name="select_status" value = 't'/>Yes
<input type="radio" name="select_status" value = 'f'/>No
<br/>

<p>* required</p>

<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
