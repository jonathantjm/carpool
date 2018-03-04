<?php  
$error1="";
$error2="";

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=byakuya~720");
if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $name = $_POST['username'];
    $contact_number = $_POST['contactnumber'];
    $vehicle_plate = $_POST['vehicleplate'];
    $password = $_POST['password'];
    $capacity = $_POST['capacity'];
    $gender;
    $isDriver;
    if(isset($genderMale)){
        $gender = 'Male';
    }else{
        $gender = "Female";
    }
    if(isset($isDriver)){
        $isDriver = true;
    }else{
        $isDriver = false;
    }

    $result = pg_query_params($db, 'INSERT INTO useraccount VALUES ($1)', $email); 
    $row = pg_fetch_array($result);
    if (!isset($row[0])){
        $error1 = "Email is invalid!";
    }
    $verify = $password == $row[0];

    if ($verify) {
        $_SESSION['user']=$email;
        header("Location: www.yahoo.com");    
    }else{
        $error2 = "Password is invalid!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Sign-up Below</h2>
    <form method="post" name="signupform" action="login.php">
        Name: <input type="text" name="username"><br><br>
        Gender:<br><br>
        <input type="radio" name="genderMale" value="male" checked> Male 
        <input type="radio" name="genderFemale" value="female"> Female<br><br>
        Driver:<br><br>
        <input type="radio" name="isDriver" value="Yes"> Yes
        <input type="radio" name="notDriver" value="No" checked> No<br><br>
        Contact Number: <input type="text" name="contactnumber"><br><br>
        Vehicle Plate (*if driver): <input type="text" name="vehicleplate"><br><br>
        Capacity of vehicle (*if driver): <input type="text" name="capacity"><br><br>
        Email: <input type="text" name="email">
        <span><?php if(isset($error1)) {echo $error1; } ?></span>
        <br><br>
        Password: <input type="password" name="password">
        <span><?php if(isset($error2) && !isset($error1)) {echo $error2; } ?></span>
        <br><br>
        <input type="submit" name="submit">
    </form>
</body>

</html>


