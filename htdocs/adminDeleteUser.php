<?php
include("header.php");

//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];
if($isAdmin == 'f') {
    $message = "You are not authorized to view this page!";
    echo "<script type='text/javascript'>alert('$message');
    window.location.href='login.php';
    </script>";
}

$userMail = $_GET['email'];

if(is_null($userMail)){
    echo 'No user email detected.';
}

$result = pg_query_params($db, 'DELETE FROM useraccount WHERE email = $1', array($userMail));

echo pg_last_error($db);

header("Location: adminUser.php");

?>
