<?php
ob_start();
include("header.php");
include("userNavBar.php");

//Initialize potential errors
$currentPasswordError = '';
$retypePasswordError = '';

$userMail = $_SESSION['user'];
$result = pg_query_params($db, 'SELECT password FROM useraccount WHERE email = $1', array($userMail));
$row = pg_fetch_array($result);
$currentPassword = $row[0];

if(isset($_POST['submit'])){
    $enteredPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $retypeNewPassword = $_POST['retypeNewPassword'];

    if($enteredPassword != $currentPassword){
        $currentPasswordError = 'Wrong password!';
    }else if($newPassword != $retypeNewPassword){
        $retypePasswordError = 'Password did not match! Please try again.';
    }else{
        pg_query_params($db, 'UPDATE useraccount SET password = $1 WHERE email = $2', array($newPassword, $userMail));
        header("Location: userPage.php");
    }
}

?>

<html>
    <body id = 'adminpage'>
        <h2 class="text-center">Change your password</h2>
        <div id="divForm">
            <form action="" method="post">
                <div class='form-group'>
                    <label for="inputName">Current password: </label>
                    <input type='password' name='currentPassword' class='form-control' id='currentPassword' required>
                    <span style='color:red'><?php echo $currentPasswordError;?></span>
                </div>
                <div class='form-group'>
                    <label for="inputName">New password: </label>
                    <input type='password' name='newPassword' class='form-control' id='newPassword' required>
                </div>
                <div class='form-group'>
                    <label for="inputName">Retype new password: </label>
                    <input type='password' name='retypeNewPassword' class='form-control' id='retypeNewPassword' required>
                    <span style='color:red'><?php echo $retypePasswordError;?></span>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </body>
</html>
