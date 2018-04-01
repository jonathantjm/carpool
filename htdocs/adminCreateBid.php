<?php
include("header.php");
include("adminNavBar.php");

//Initialize potential errors
$emailError = '';
$adIdError = '';
$priceError = '';
$row = array('Enter email', 'Enter advertisement id', 'Enter bidding price');

if (isset($_POST['submitForm'])) {
		
	$email = $_POST['email'];
	$advertisementID = $_POST['advertisementID'];
	$price = $_POST['price'];
	$creationDateAndTime = date("Y/m/d h:i:s");

	$query = pg_query_params($db, "SELECT admin_addBid($1, $2, $3, $4)", array($email, $advertisementID, $price, $creationDateAndTime));
	
	$row[0] = $email;
	$row[1] = $advertisementID;
	$row[2] = $price;
	
	$result = pg_fetch_array($query);
	$error = $result[0];
	
	if (preg_match('/email/i', $error)) {
		$emailError = $error;
	} elseif (preg_match('/advertisement/i', $error)) {
		$adIdError = $error;
	} elseif (preg_match('/price/i', $error)) {
		$priceError = $error;
	} elseif ($error == '') {
		header("Location: adminBid.php");
	}

}
?>

<html>
	<body>
		<h2>Create Bid</h2>
		<form action="" method="post">
			<div class="form-group">
				<label for="inputEmail">Email address</label>
				<input type="email" name="email" class="form-control" id="inputEmail" placeholder="<?php echo $row[0]; ?>" required>
				<span style="color:red"><?php echo $emailError; ?></span>
			</div>
			<div class="form-group">
				<label for="inputID" class="control-lable">Advertisement ID</label>
				<input type='text' name="advertisementID" class='form-control' id='inputID' placeholder="<?php echo $row[1]; ?>" required>
				<span style="color:red"><?php echo $adIdError; ?></span>
			</div>
			<div class="form-group">
				<label for="inputPrice">Price</label>
				<input type="text" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" name="price" class="form-control" id="inputPrice" placeholder="<?php echo $row[2]; ?>" required>
				<span style="color:red"><?php echo $priceError; ?></span>
			</div>
			<button type="submit" name="submitForm" class="btn btn-primary">Submit</button>
		</form>
	</body>
</html>
