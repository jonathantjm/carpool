<?php
include("header.php");
include("adminNavBar.php");

$advertisementID = $_GET['id'];
$email = $_GET['mail'];
$result = pg_query_params($db, 'SELECT * FROM bid WHERE advertisementid = $1 AND email = $2', array($advertisementID, $email));
$row = pg_fetch_array($result);

//Initialize potential errors
$emailError = '';
$adIdError = '';
$priceError = '';
$statusError = '';

if (isset($_POST['submitForm'])) {
	
	$newMail = $_POST['email'];
	$newID = $_POST['advertisementID'];
	$newStatus = $_POST['status'];
	$newPrice = $_POST['price'];

	$query = pg_query_params($db, "SELECT admin_editBid($1, $2, $3, $4, $5, $6, $7)", array($email, $advertisementID, $newMail, $newID, $newStatus, $newPrice, $dateAndTime));
	$result = pg_fetch_array($query);
	$error = $result[0];
	
	if (preg_match('/email/i', $error)) {
		$emailError = $error;
	} elseif (preg_match('/advertisement/i', $error)) {
		$adIdError = $error;
	} elseif (preg_match('/price/i', $error)) {
		$priceError = $error;
	} elseif (preg_match('/status/i', $error)) {
		$statusError = $error;
	} elseif ($error == '') {
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
				<input type="email" name="email" class="form-control" id="inputEmail" placeholder="<?php echo $row[0]; ?>" required>
				<span style="color:red"><?php echo $emailError; ?></span>
			</div>
			<div class='form-group'>
				<label for='inputID'>Advertisement ID</label>
				<input type='text' name="advertisementID" class='form-control' id='inputID' placeholder="<?php echo $row[1]; ?>" required>
				<span style="color:red"><?php echo $adIdError; ?></span>
			</div>
			<div class='form-group'>
				<label for='inputStatus'>Status</label>
				<input type='text' name='status' class='form-control' id='inputStatus' placeholder="<?php echo $row[2]; ?>" required>
				<span style="color:red"><?php echo $statusError; ?></span>
			</div>
			<div class='form-group'>
				<label for='inputPrice'>Price</label>
				<input type="text" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" name="price" class="form-control" id="inputPrice" placeholder="<?php echo $row[3]; ?>" required>
				<span style="color:red"><?php echo $priceError; ?></span>
			</div>
			<button type="submit" name="submitForm" class="btn btn-primary">Submit</button>
		</form>
	</body>
</html>
