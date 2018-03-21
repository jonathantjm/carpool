<?php

session_start();

//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];
if($isAdmin == 'f') {
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
}

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

//echo $_GET['id'];
//echo $_GET['mail'];

$advertisementID = $_GET['id'];
$email = $_GET['mail'];

if(is_null($advertisementID)){
	echo 'no adv ID detected';
} else {
	echo 'found adv ID...';
}

$result = pg_query_params($db, 'DELETE FROM bid WHERE advertisementid = $1 AND email = $2', array($advertisementID, $email));

echo pg_last_error($db);

header("Location: adminBid.php");

?>
