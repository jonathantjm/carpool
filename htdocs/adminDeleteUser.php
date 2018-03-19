<?php

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

//echo $_GET['id'];
//echo $_GET['mail'];

$userMail = $_GET['email'];

if(is_null($userMail)){
    echo 'No user email detected.';
}

$result = pg_query_params($db, 'DELETE FROM useraccount WHERE email = $1', array($userMail));

echo pg_last_error($db);

header("Location: adminUser.php");

?>
