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

//Initialize potential errors
$emailError = "";
$locationError =  '';
$dateError = '';
$timeError = '';
$row = array('', '', '', '', '', '');

$locations = pg_query($db, "SELECT * FROM locations"); 
$max_advertisementID = pg_query($db, "SELECT max(advertisementID) FROM advertisements"); 

if (isset($_POST['submit'])) {

    $advertisementID = pg_fetch_array($max_advertisementID)[0] + 1;
    $email = $_POST['email'];
    $start_location = $_POST['start_location'];
    $end_location = $_POST['end_location'];
    $date_of_pickup = $_POST['date_of_pickup'];
    $time_of_pickup = $_POST['time_of_pickup'];
    $self_select = $_POST['self_select'];

    pg_query_params("INSERT INTO advertisements (advertisementid, email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select) VALUES ($1, $2, $3, $4, NOW(), $5, $6, $7)", array($advertisementID, $email, $start_location, $end_location, $date_of_pickup, $time_of_pickup, $self_select));

    $row[0] = $email;
    $row[1] = $start_location;
    $row[2] = $end_location;
    $row[3] = $date_of_pickup;
    $row[4] = $time_of_pickup;
    $row[5] = $self_select;

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
    else if (strpos($error, 'email') !== false) {
        $emailError = 'Account for this email does not exist!';
    } else {
        echo $error;
    }
}
?>

<html>
    <body>
        <h2>Create new advertisement</h2>
        <form action="" method="post">
            <div class='form-group'>
                <label for="inputEmail">Email of driver</label>
                <input type="email" name="email" class='form-control' id='inputEmail' placeholder="Enter the email" value="<?php echo $row[0]; ?>" required>
                <span style='color:red'><?php echo $emailError;?></span></br>
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
echo "</div>";

?>
            <div class='form-group'>
                <label for="inputPickupDate">Date of Pickup: </label>
                <?php echo "<input type='date' name='date_of_pickup' class='form-control' id='inputPickupDate' value='$row[3]' required>";?>
                <span style="color:red"><?php echo $dateError;?></span>
            </div>
            <div class='form-group'>
                <label for="inputPickupTime">Time of Pickup: </label>
                <?php echo "<input type='time' name='time_of_pickup' class='form-control' id='inputPickupTime' value='" . $row[4] . "' required>";?>
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

<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
