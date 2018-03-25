<?php  
include("header.php");

$emailError="";
$passwordError="";

if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = pg_query_params($db, 'SELECT password, is_admin FROM useraccount WHERE email= $1', array($email)); 
    $row = pg_fetch_array($result);

    $error = pg_last_error($db);
    if (!isset($row[0])) {
        $emailError = 'There is no such email in use.';
    } else if(!($password == $row[0])){
        $passwordError="Wrong password!";
    }else{
        if($row[1] == "t"){
            $_SESSION['isAdmin']=$isAdmin;
            header("Location: adminPage.php");
        }else{
            $_SESSION['user']=$email;
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
    <title>Login</title>
</head>

<body>
    <h2>Login Below</h2>
    <form method="post" name="form" action="login.php">
        <div class='form-group'>
            <label for="inputEmail">Email: </label>
            <input type="email" name="email" class="form-control" id="inputEmail" required>
            <span style="color:red"><?php echo $emailError;?></span>
        </div>
        <div class='form-group'>
            <label for="inputPassword">Password: </label>
            <input type="password" name="password" class="form-control" id="inputPassword" required>
            <span style="color:red"><?php echo $passwordError;?></span>
        </div>
        <input type="submit" name="submit">
    </form>
</body>

</html>
