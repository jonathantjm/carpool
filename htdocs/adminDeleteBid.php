<?php
include("header.php");

//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];
if($isAdmin == 'f') {
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
} elseif ($isAdmin == null) {
	$message = "Please login to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
}

$advertisementID = $_GET['id'];
$email = $_GET['mail'];

$result = pg_query_params($db, 'SELECT deleteBid($1, $2)', array($email, $advertisementID));

header("Location: adminBid.php");

?>
