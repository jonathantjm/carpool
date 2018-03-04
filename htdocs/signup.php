<?php  
$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");
if(!$db){
    echo "error connecting";
}
/*$value1 = 6;
$value2 = 'bull';
$result = pg_query_params($db, "INSERT INTO trial(sid, name) VALUES ($1, $2)", array($value1, $value2));
if(!$result){
    echo "There is still a problem.";
}*/

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

    if($password != $password_repeat){
        $message = "Password did not match! Please try again.";
        echo $password;
        echo $password_repeat;
        echo $password == $password_repeat;
    }else{
        echo "here";
        $result = pg_query_params($db, "INSERT INTO useraccount VALUES($1, $2, $3, $4, $5, $6, $7, $8, DEFAULT)", 
        array($name, $gender, $contact_number, $email, $password, $vehicle_plate, $capacity, $isDriver));

            /*,array($name, $gender, $contact_number, $email, $password, $vehicle_plate, $capacity, $isDriver));*/
        /*$result = pg_query($db, "INSERT INTO useraccount VALUES('Jessica', 'Female', '87658678', 'jpohjt@yahoo.com',
            'fishskin2', null, null, False, default)");*/
        echo $result;
        if(!$result){
            echo "Insertion failed.";
        }else{
            $row = pg_fetch_array($result);
            $message = $row[0];
            echo $row[0];
            echo Hellow;
        }
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
    <form method="post" name="signupform" onsubmit="check()">
        Name: <input type="text" name="username"><br><br>
        Gender:<br><br>
        <input type="radio" name="gender" value="Male" checked> Male 
        <input type="radio" name="gender" value="Female"> Female<br><br>
        Driver:<br><br>
        <input type="radio" name="isDriver" value="Yes"> Yes
        <input type="radio" name="isDriver" value="No" checked> No<br><br>
        Contact Number: <input type="text" name="contactnumber"><br><br>
        Vehicle Plate (*if driver): <input type="text" name="vehicleplate"><br><br>
        Capacity of vehicle (*if driver): <input type="text" name="capacity"><br><br>
        Email: <input type="text" name="email">
        <br><br>
        Password: <input type="password" name="password">
        <br><br>
        Password(repeat): <input type="password" name="password_repeat">
        <br><br>
        <input type="submit" name="submit">
    </form>
    <div><?php if (isset($message)) {echo $message;} ?>
</body>
<script>
function check() {
    if(document.forms["signupform"]["vehicleplate"].value == "")
        document.forms["signupform"]["vehicleplate"].value = null;
    if(document.forms["signupform"]["capacity"].value == "")
        document.forms["signupform"]["capacity"].value = null;
}
</script>

</html>


