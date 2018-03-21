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

//Initialize potential errors
$idError = '';
$emailError = '';
$row = array('Enter email', 'Enter advertisement id', 'Enter bidding price');

if (isset($_POST['submitForm'])) {
		
	$first = $_POST['email'];
	$second = $_POST['advertisementID'];
	$third = $_POST['price'];
	$fourth = date("Y/m/d h:i:s");

	pg_query_params("INSERT INTO bid VALUES ($1, $2, 'pending', $3, $4)", array($first, $second, $third, $fourth));

	$row[0] = $first;
	$row[1] = $second;
	$row[2] = $third;
	
	$error = pg_last_error($db);
	if (preg_match('/email/i', $error)) {
		$emailError = 'Email does not exists';
	} elseif (preg_match('/advertisementid/i', $error)) {
		$idError = 'Advertisement id does not exists';
	} else {
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
				<span style="color:red"><?php echo $emailError;?></span>
			</div>
			<div class="form-group">
				<label for="inputID">Advertisement ID</label>
				<input type="number" name="advertisementID" class="form-control" id="inputID" placeholder="<?php echo $row[1]; ?>" required>
				<span style="color:red"><?php echo $idError;?></span>
			</div>
			<div class="form-group">
				<label for="inputPrice">Price</label>
				<input type="text" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" name="price" class="form-control" id="inputPrice" placeholder="<?php echo $row[2]; ?>" required>
			</div>
			<button type="submit" name="submitForm" class="btn btn-primary">Submit</button>
		</form>
	</body>
</html>
