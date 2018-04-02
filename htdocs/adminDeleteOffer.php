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

if(is_null($advertisementID)){
    echo 'No advertisement ID detected.';
}

pg_query_params($db, 'SELECT delete_advertisement($1)', array($advertisementID));
echo pg_last_error($db);
header("Location: adminOffer.php");
?>

