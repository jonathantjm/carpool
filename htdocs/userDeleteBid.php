<?php
session_start();

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

//echo $_GET['id'];
//echo $_GET['mail'];

$advertisementID = $_GET['id'];
$email = $_SESSION['user'];
$creator = pg_query($db, "SELECT email FROM bid WHERE advertisementid = '" . $advertisementID . "';");
if(pg_fetch_array($creator)[0] != $email || is_null($advertisementID)){
	header("Location: error.php");
}


if(isset($_POST['yes'])){
	$result = pg_query($db, "DELETE FROM bid WHERE advertisementid = '" . $advertisementID . "';");
	header("Location: userOffer.php");
}
elseif(isset($_POST['no'])){
	header("Location: userOffer.php");
}

echo pg_last_error($db);

?>

<html>

<body>
	<div>
		<h2>Are you sure you want to delete?</h2>
		<form action="" method="post">
			<input type="submit" name = "yes" value = "Yes">
			<input type="submit" name = "no" value = "No">
	</div>
</body>

</html>