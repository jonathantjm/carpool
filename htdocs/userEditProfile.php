<?php
ob_start();
include("header.php");
include("userNavBar.php");

//Initialize potential errors
$vehiclePlateError = '';
$emailError = '';
$driverError = '';
$offerError = '';

$userMail = $_SESSION['user'];
$result = pg_query_params($db, 'SELECT * FROM useraccount WHERE email = $1', array($userMail));
$row = pg_fetch_array($result);

if (isset($_POST['submit'])) {

    $newName = $_POST['name'];
    $newGender = $_POST['gender'];
    $newContact = $_POST['contact_number'];
    $newEmail = $_POST['email'];
    $newVehiclePlate = $_POST['vehicle_plate'];
    $newCapacity = $_POST['capacity'];
    $isADriver = $_POST['isDriver'];

    if($isADriver == 'n'){
        $newCapacity = NULL;
        $newVehiclePlate = NULL;
    }

    if($newVehiclePlate == ''){
        $newVehiclePlate = NULL;
    }

    if($newCapacity == ''){
        $newCapacity = NULL;
    }

    $row[0] = $newName;
    $row[1] = $newGender;
    $row[2] = $newContact;
    $row[3] = $newEmail;
    $row[5] = $newVehiclePlate;
    $row[6] = $newCapacity;
    $row[7] = $isADriver;

    $existingOffersResults = pg_query_params($db, 'SELECT checkExistingOffer($1)', array($userMail));
    $existingOffers = pg_fetch_array($existingOffersResults);

    if($isADriver == 'n' AND $existingOffers[0] == 't'){
        $offerError = 'You cannot declare yourself as a non-driver when you have existing offers! Please delete those offers first!';
    }else{
        pg_query_params($db, 'UPDATE useraccount SET name = $2, gender = $3, contact_number = $4, email = $5, vehicle_plate = $6, capacity = $7, is_driver = $8 WHERE email = $1', array($userMail, $newName, $newGender, $newContact, $newEmail, $newVehiclePlate, $newCapacity, $isADriver));

        $error = pg_last_error($db);
        if (preg_match('/email/i', $error)) {
            $emailError = 'Email is already in use.';
        } else if (preg_match('/vehicle_plate/i', $error)) {
            $vehiclePlateError = 'Vehicle plate number is already in use.';
        } else if (preg_match('/driver_must_fill_in/i', $error)) {
            $driverError = 'This field must be filled in if you are a driver!';
        } else {
            header("Location: userPage.php");
        }
    }
}

?>

<html>
    <body id = 'adminpage'>
        <h2 class="text-center">Edit your profile</h2>
        <div id="divForm">
            <form action="" method="post">
                <div class='form-group'>
                    <label for="inputName">Name: </label>
                    <?php echo "<input type='text' name='name' class='form-control' id='inputName' value='" . $row[0] . "' required>";?>
                </div>
                <div class='form-group'>
                    <label for="inputGender">Gender: </label></br>
<?php 
if($row[1] == "Male"){
    echo " <input type=\"radio\" name=\"gender\" value = \"Male\" checked/> Male ";
    echo " <input type=\"radio\" name=\"gender\" value = \"Female\"/> Female ";
}else{
    echo " <input type=\"radio\" name=\"gender\" value = \"Male\" /> Male ";
    echo " <input type=\"radio\" name=\"gender\" value = \"Female\" checked/> Female ";
}
?>
                </div>
                <div class='form-group'>
                    <label for="inputNumber">Contact Number: </label>
                    <?php echo "<input type='text' pattern=\"^[89][0-9]{7}$\" title=\"Contact number starts with a 8 or 9, and must be 8 digits long.\" name='contact_number' class='form-control' id='inputNumber' placeholder = 'Enter contact number' value='" . $row[2] . "' required>";?>
                </div>
                <div class='form-group'>
                    <label for="inputEmail">Email: </label>
                    <?php echo "<input type='email' name='email' class='form-control' id='inputEmail' value='" . $row[3] . "' required>";?>
                    <span style='color:red'><?php echo $emailError;?></span>
                </div>
                <div class='form-group'>
                    <label for="inputDriver">Is a driver? </label></br>
<?php 
if($row[7] == "t"){
    echo "<input type=\"radio\" name=\"isDriver\" value = \"t\" checked/> Yes ";
    echo "<input type=\"radio\" name=\"isDriver\" value = \"n\"/> No ";
}else{
    echo "<input type=\"radio\" name=\"isDriver\" value = \"t\"/> Yes ";
    echo "<input type=\"radio\" name=\"isDriver\" value = \"n\" checked/> No ";
}
?>
            <span style='color:red'><?php echo $offerError;?></span>
                </div>
                <div class='form-group'>
                    <label for="inputDriver">Vehicle Plate: </label>
<?php 
if($row[7] == "t"){
    echo "<input type=\"text\" name=\"vehicle_plate\" pattern='^S[A-Z]{1,2}[0-9]{1,4}[A-Z]$' class='form-control' title='Some example: SGE3213Z, SH123D' value = \"$row[5]\"/>";
}else{
    echo "<input type=\"text\" name=\"vehicle_plate\" pattern='^S[A-Z]{1,2}[0-9]{1,4}[A-Z]$' class='form-control' title='Some example: SGE3213Z, SH123D'/>";
}
?>
                    <span style='color:red'><?php echo $vehiclePlateError; echo $driverError;?></span>
                </div>
                <div class='form-group'>
                    <label for="inputCapacity">Capacity: </label>
<?php 
if($row[7] == "t"){
    echo "<input type=\"number\" name=\"capacity\" min='1' max='7' id='inputCapacity' class='form-control' value = \"$row[6]\"/>";
}else{
    echo "<input type=\"number\" name=\"capacity\" min='1' max='7' id='inputCapacity' class='form-control'/>";
}
?>
                    <span style='color:red'><?php echo $driverError;?></span>
                </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </body>
</html>
