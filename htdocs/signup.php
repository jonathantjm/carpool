<?php  
include("header.php");

//Initialize potential errors
$vehiclePlateError = '';
$emailError = '';
$passwordRepeatError = '';
$row = array('', '', '', '', '', '');

if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $contactNumber = $_POST['contact_number'];
    $vehiclePlate = $_POST['vehicle_plate'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    $capacity = $_POST['capacity'];
    $gender = $_POST['gender'];
    $isDriver = $_POST['isDriver'];

    if($isDriver == "No"){
        $vehiclePlate = NULL;
        $capacity = NULL;
    }
    
    $row[0] = $name;
    $row[1] = $contact_number;
    $row[2] = $email;
    $row[3] = $password;
    $row[4] = $vehicle_plate;
    $row[5] = $capacity;

    if($password != $password_repeat){
        $passwordRepeatError = "Password did not match! Please try again.";
    }else{
        $tempResult = pg_query_params($db, 'SELECT add_user($1, $2, $3, $4, $5, $6, $7, $8, false)', array($name, $gender, $contactNumber, $email, $password, $vehiclePlate, $capacity, $isDriver));

        $result = pg_fetch_array($tempResult);
        if (preg_match('/Email/i', $result[0])) {
            $emailError = $result[0];
        } else if (preg_match('/Plate/i', $result[0])) {
            $vehiclePlateError = $result[0];
        } else {
            header("Location: userPage.php");
        }

    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript">
            function checkDriver() {
                if(document.getElementById("yesDriver").checked){
                    document.getElementById("inputPlate").disabled = false;
                    document.getElementById("inputCapacity").disabled = false;
                    document.getElementById("inputPlate").required = true;
                    document.getElementById("inputCapacity").required = true;
                }else {
                    document.getElementById("inputPlate").disabled = true;
                    document.getElementById("inputCapacity").disabled = true;
                    document.getElementById("inputPlate").required = false;
                    document.getElementById("inputCapacity").required = false;
                }
            }
            </script>
    </head>
    <body>
        <h2>Create an account</h2>
        <form action="" method="post">
        <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" name="name" class="form-control" id="inputName" placeholder="Enter your name" value="<?php echo $row[0]; ?>" required>
        </div>
        <div class="form-group">
                <label for="inputGender">Gender:</label>
                </br>
                <input type="radio" name="gender" id='inputGender' value="Male" checked/> Male
                <input type="radio" name="gender" id='inputGender' value="Female"/> Female
        </div>
        <div class="form-group">
                <label for="inputNumber">Contact Number</label>
                <input type="text" pattern="^[89][0-9]{7}$" title="Contact number starts with a 8 or 9, and must be 8 digits long." name="contact_number" class="form-control" id="inputNumber" placeholder="Enter your contact number" value="<?php echo $row[1]; ?>" required>
        </div>
        <div class="form-group">
                <label for="inputEmail">Email address</label>
                <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter your email address" value="<?php echo $row[2]; ?>" required>
                <span style="color:red"><?php echo $emailError;?></span>
        </div>
        <div class="form-group">
                <label for="inputDriver">Is a driver?</label>
                </br>
                <input type="radio" name="isDriver" id="yesDriver" value="Yes" onclick="checkDriver()" checked/> Yes
                <input type="radio" name="isDriver" id="noDriver" onclick="checkDriver()" value="No"/> No
        </div>
        <div class="form-group">
                <label for="inputPlate">Vehicle Plate Number</label>
                <input type="text" name="vehicle_plate" pattern="^S[A-Z]{1,2}[0-9]{1,4}[A-Z]$" class="form-control" id="inputPlate" placeholder="Enter you vehicle plate number" value="<?php echo $row[4]; ?>" title="Some example: SGE3213Z, SH123D" required>
                <span style="color:red"><?php echo $vehiclePlateError;?></span>
        </div>
        <div class="form-group">
                <label for="inputCapacity">Capacity of vehicle</label>
                <input type="number" name="capacity" min='1' max='7' class="form-control" id="inputCapacity" placeholder="Enter the capacity of your vehicle" value="<?php echo $row[5]; ?>" required>
        </div>
        <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Enter password" required>
        </div>
        <div class="form-group">
                <label for="inputPassword">Retype password</label>
                <input type="password" name="password_repeat" class="form-control" id="inputPasswordRepeat" placeholder="Enter password again" required>
                <span style="color:red"><?php echo $passwordRepeatError;?></span>
        </div>
	<button type="submit" name="submitForm" class="btn btn-primary">Submit</button>
</form>
</body>
</html>


