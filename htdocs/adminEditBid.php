<?php
include("header.php");
include("adminNavBar.php");

//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];

if($isAdmin == 'f') {
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
}

$advertisementID = $_GET['id'];
$email = $_GET['mail'];
$result = pg_query_params($db, 'SELECT * FROM bid WHERE advertisementid = $1 AND email = $2', array($advertisementID, $email));
$row = pg_fetch_array($result);

if (isset($_POST['submitForm'])) {
	
	$newMail = $_POST['email'];
	$newID = $_POST['advertisementID'];
	$newStatus = $_POST['status'];
	$newPrice = $_POST['price'];
	$newDateAndTime = date("Y/m/d h:i:s");

	pg_query_params($db, 'UPDATE bid SET email = $3, advertisementid = $4, status = $5, price = $6, creation_date_and_time = $7 WHERE advertisementid = $1 AND email = $2', array($advertisementID, $email, $newMail, $newID, $newStatus, $newPrice, $newDateAndTime));
	
	$row[0] = $newMail;
	$row[1] = $newID;
	$row[2] = $newStatus;
	$row[3] = $newPrice;

	if (preg_match('/advertisementid/i', pg_last_error($db))) {
		echo 'Advertisement id does not exists';		
	} elseif (preg_match('/email/i', pg_last_error($db))) {
		echo 'Email does not exists';
	} else {
		header("Location: adminBid.php");
	}
}
?>

<html>
	<body>
		<h2>Edit Bid</h2>
		<form action="" method="post">
			<div class='form-group'>
				<label for='inputEmail'>Email address</label>
				<?php echo"<input type='text' name='email' class='form-control' id='inputEmail' value='" . $row[0] . "'>";?>
			</div>
			<div class='form-group'>
				<label for='inputID'>Advertisement ID</label>
				<?php echo "<input type='text' name='advertisementID' class='form-control' id='inputID' value='" . $row[1] . "'>";?>
			</div>
			<div class='form-group'>
				<label for='inputStatus'>Status</label>
				<?php echo "<input type='text' name='status' class='form-control' id='inputStatus' value='" . $row[2] . "'>";?>
			</div>
			<div class='form-group'>
				<label for='inputPrice'>Price</label>
				<?php echo"<input type='number' min='0.01' step='0.01' name='price' class='form-control' id='inputPrice' value='" . $row[3] . "'>";?>
			</div>
			<button type="submit" name="submitForm" class="btn btn-primary">Submit</button>
		</form>
	</body>
</html>
