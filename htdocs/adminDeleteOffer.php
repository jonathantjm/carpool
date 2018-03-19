<?php

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

//echo $_GET['id'];
//echo $_GET['mail'];

$advertisementID = $_GET['id'];

if(is_null($advertisementID)){
    echo 'No advertisement ID detected.';
}

$result = pg_query_params($db, 'DELETE FROM advertisements WHERE advertisementID = $1', array($advertisementID));

echo pg_last_error($db);

header("Location: adminOffer.php");

?>
