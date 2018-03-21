<?php  

$db = pg_connect("host=localhost port=5432 dbname=carpooling user=postgres password=25071995h!");
if(!$db){
    echo "error connecting";
}
if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $name = $_POST['username'];
    $contact_number = $_POST['contactnumber'];
    $vehicle_plate = $_POST['vehicleplate'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    $capacity = $_POST['capacity'];
    $gender = $_POST['gender'];
    $isDriver = $_POST['isDriver'];
    if($capacity == ""){
        $capacity = NULL;
    }
    if($vehicle_plate == ""){
        $vehicle_plate = NULL;
    }

    if($password != $password_repeat){
        $message = "Password did not match! Please try again.";
    }else{
        /*$result = pg_query_params($db, "INSERT INTO useraccount VALUES($1, $2, $3, $4, $5, $6, $7, $8, DEFAULT)", 
        array($name, $gender, $contact_number, $email, $password, $vehicle_plate, $capacity, $isDriver));*/
        $result = pg_query_params($db, 'SELECT add_user($1, $2, $3, $4, $5, $6, $7, $8, false)', array($name, $gender, $contact_number, $email, $password, $vehicle_plate, $capacity, $isDriver));
        /*if(!$result){
            echo "Insertion failed.";
        }else{
            $row = pg_fetch_array($result);
            $message = $row[0];
        }*/
        $row = pg_fetch_array($result);
        if(!isset($row)){
            $message = "Failed to create account! Please check your values again!";
        }else{
            $message = $row[0];
        }
    }
}

?>

<!DOCTYPE html>
<html>
<script type="text/javascript">
function checkDriver() {
    if(document.getElementById("yesDriver").checked){
        document.getElementById("vehicleplate").disabled = false;
        document.getElementById("capacity").disabled = false;
        document.getElementById("capacity").placeholder = "Single digit only";
    }else {
        document.getElementById("vehicleplate").disabled = true;
        document.getElementById("capacity").disabled = true;
        document.getElementById("vehicleplate").required = true;
        document.getElementById("capacity").required = true;
        document.getElementById("capacity").placeholder = "";
    }
}
</script>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Sign-up Below</h2>
    <form method="post" name="signupform">
        Name: <input type="text" name="username" required><br><br>
        Gender:<br><br>
        <input type="radio" name="gender" value="Male" checked> Male 
        <input type="radio" name="gender" value="Female"> Female<br><br>
        Driver:<br><br>
        <input type="radio" name="isDriver" id="yesDriver" value="Yes" checked onclick="checkDriver()"> Yes
        <input type="radio" name="isDriver" value="No" onclick="checkDriver()"> No<br><br>
        Contact Number: <input type="text" name="contactnumber" placeholder="Numbers only" required><br><br>
        Vehicle Plate (*if driver): <input type="text" name="vehicleplate" id="vehicleplate"><br><br>
        Capacity of vehicle (*if driver): <input type="text" name="capacity" id="capacity" placeholder="Single digit only"><br><br>
        Email: <input type="text" name="email" required>
        <br><br>
        Password: <input type="password" name="password" required>
        <br><br>
        Password(repeat): <input type="password" name="password_repeat" required>
        <br><br>
        <input type="submit" name="submit">
    </form>
    <div><?php if (isset($message)) {echo $message;} ?>
</body>

</html>


