<?php
ob_start();
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
	
	$status = $_POST['status'];
	$price = $_POST['price'];

	$query = pg_query_params($db, "SELECT editBid($1, $2, $3, $4, $5)", array($email, $advertisementID, $status, $price, $row[4]));
	$result = pg_fetch_array($query);
	$error = $result[0];
    if (($error === 'You cannot bid for your own offer!') OR ($error === 'Your email is invalid!')) {
		$emailError = $error;
    } elseif (($error === 'Advertisement id does not exist!') OR ($error === 'Sorry, advertisement has already been closed!') 
		OR ($error === 'Please make sure you have an existing bid for that offer!')) {
		$adIdError = $error;
    } elseif ($error === 'Price should be numeric and greater than 0!') {
		$priceError = $error;
    } elseif ($error === 'Status is case-sensitive and should be Pending, Rejected, Accepted or Offer retracted') {
		$statusError='';
	} elseif ($error == '') {
		header("Location: adminBid.php");
    }
}
?>

<html>
	<body id = 'adminpage'>
		<h2 class="text-center">Edit Bid</h2>
		<div id='divForm'>
			<form action="" method="post">
				<div class="form-group">
					<label for="status">Status (Select one):</label>
					<select class="form-control" id="status" name='status'>
						<option>Pending</option>
						<option>Accepted</option>
						<option>Rejected</option>
						<option>Offer retracted</option>
						<option>Offer expired</option>
					</select>
				</div>
				<div class='form-group'>
					<label for='inputPrice'>Price</label>
					<input type="text" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" name="price" class="form-control" id="inputPrice" value="<?php echo $row[3]; ?>" required>
					<span style="color:red"><?php echo $priceError; ?></span>
				</div>
				<button type="submit" name="submitForm" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</body>
</html>
