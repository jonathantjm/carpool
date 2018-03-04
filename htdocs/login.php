<?php  
$error1="";
$error2="";

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");
if (!$db) 
    echo "not connected";
if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = pg_query_params($db, 'SELECT password, is_admin FROM useraccount WHERE email= $1', array($email)); 
    $row = pg_fetch_array($result);
    if (!isset($row[0])){
        $error1 = "Email is invalid!";
    }
    $verify = $password == $row[0];
    $isAdmin = $row[1];

    if ($verify) {
        $_SESSION['user']=$email;
        header("Location: www.yahoo.com");    
        echo "password is valid";
        if ($isAdmin) {
            header("Location: adminPage.php");
        } else {
            header("Location: userPage.php");
        }		
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
    <h2>Login Below</h2>
    <form method="post" name="form" action="login.php">
        Email:<br>
        <input type="text" name="email">
        <span><?php if(isset($error1)) {echo $error1; } ?></span>
        <br>
        Password:<br>
        <input type="password" name="password">
        <span><?php if(isset($error2) && !isset($error1)) {echo $error2; } ?></span>
        <br><br>
        <input type="submit" name="submit">
    </form>
</body>

</html>
