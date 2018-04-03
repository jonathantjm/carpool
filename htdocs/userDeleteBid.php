<?php
include("header.php");

$advertisementID = $_GET['id'];
$email = $_SESSION['user'];
$creator = pg_query($db, "SELECT email FROM bid WHERE advertisementid = '" . $advertisementID . "';");
if(is_null($advertisementID)){
	$message = "Oops something went wrong!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}
if(pg_num_rows($creator == 0)){
	$message = "Advertisement not found!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";	
}
if(pg_fetch_array($creator)[0] != $email){
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}


if(isset($_POST['yes'])){
	$result = pg_query_params($db, 'SELECT deleteBid($1, $2)', array($email, $advertisementID));
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