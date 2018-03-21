<?php
include("header.php");
include("adminNavBar.php");

if(!$db){
    echo "cannot connect";
}

//echo $_GET['id'];
//echo $_GET['mail'];

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contactNumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $vehicle_plate = $_POST['vehiclePlate'];
    $capacity = $_POST['capacity'];
    $isADriver = $_POST['isDriver'];

    pg_query_params($db, 'SELECT add_user($1, $2, $3, $4, $5, $6, $7, $8, false)', array($name, $gender, $contact_number, $email, $password, $vehicle_plate, $capacity, $isADriver));

    echo pg_last_error($db);

    header("Location: adminUser.php");

}

?>

<html>

<form action="" method="post">

<div>

<strong>Name: *</strong> <input type="text" name="name" /><br/></br>

<strong>Gender: *</strong></br> 
<input type="radio" name="gender" value="Male"/>Male
<input type="radio" name="gender" value="Female"/>Female
<br/></br>

<strong>Contact Number: *</strong> <input type="text" name="contactNumber" /><br/></br>

<strong>Email: *</strong> <input type="text" name="email" /><br/></br>

<strong>Password: *</strong> <input type="text" name="password" /><br/></br>

<strong>Vehicle Plate: *</strong> <input type="text" name="vehiclePlate" /><br/></br>

<strong>Capacity: *</strong> <input type="text" name="capacity" /><br/></br>

<strong>Is a driver?: *</strong></br> 
<input type="radio" name="isDriver" value="Yes"/>Yes
<input type="radio" name="isDriver" value="No"/>No
<br/></br>

<p>* required</p>

<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
