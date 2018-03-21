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

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");
if(!$db){
    echo "cannot connect";
}

//echo $_GET['id'];
//echo $_GET['mail'];

$userMail = $_GET['email'];
$result = pg_query_params($db, 'SELECT * FROM useraccount WHERE email = $1', array($userMail));
$row = pg_fetch_array($result);

if(is_null($userMail)){
    echo 'No such user found.';
}

if (isset($_POST['submit'])) {

    echo $newName = $_POST['name'];
    echo $newGender = $_POST['gender'];
    echo $newContact = $_POST['contact_number'];
    echo $newEmail = $_POST['email'];
    echo $newPassword = $_POST['password'];
    echo $newVehiclePlate = $_POST['vehicle_plate'];
    echo $newCapacity = $_POST['capacity'];
    echo $isADriver = $_POST['isDriver'];
    
    //Check for email and vehicle plate unique constraints. Probably use a function to take care of it.

    pg_query_params($db, 'UPDATE useraccount SET name = $2, gender = $3, contact_number = $4, email = $5,password = $6, vehicle_plate = $7, capacity = $8, is_driver = $9 WHERE email = $1', array($userMail, $newName, $newGender, $newContact, $newEmail, $newPassword, $newVehiclePlate, $newCapacity, $isADriver));

    echo pg_last_error($db);

    header("Location: adminUser.php");

}

?>

<html>

<h2>Old:</h2>

<?php
echo "<table class ='sample'>";
echo "<tr>";
echo "<th>Name</th>";
echo "<th>Gender</th>";
echo "<th>Contact Number</th>";
echo "<th>Email</th>";
echo "<th>Password</th>";
echo "<th>Vehicle Plate</th>";
echo "<th>Capacity</th>";
echo "<th>Is a driver?</th>";
echo "</tr>";
echo "<tr>";
echo "<td>" . $row[0] . "</td>";
echo "<td>" . $row[1] . "</td>";
echo "<td>" . $row[2] . "</td>";
echo "<td>" . $row[3] . "</td>";
echo "<td>" . $row[4] . "</td>";
echo "<td>" . $row[5] . "</td>";
echo "<td>" . $row[6] . "</td>";
echo "<td>" . $row[7] . "</td>";
echo "</tr>";
echo "</table>";
?>

<h2>New:</h2>

<form action="" method="post">

<div>

<strong>Name: *</strong> <input type="text" name="name" /><br/></br>

<strong>Gender: *</strong></br> 
<input type="radio" name="gender" value="Male"/>Male
<input type="radio" name="gender" value="Female"/>Female
<br/></br>

<strong>Contact Number: *</strong> <input type="text" name="contact_number" /><br/></br>

<strong>Email: *</strong> <input type="text" name="email" /><br/></br>

<strong>Password: *</strong> <input type="text" name="password" /><br/></br>

<strong>Vehicle Plate: *</strong> <input type="text" name="vehicle_plate" /><br/></br>

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
