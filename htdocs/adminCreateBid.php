<?php
include("header.php");
include("adminNavBar.php");

//Initialize potential errors
$emailError;
$adIdError;
$priceError;
$row = array('Enter email', 'Enter advertisement id', 'Enter bidding price');

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $advertisementID = $_POST['advertisementID'];
    $price = $_POST['price'];

    $query = pg_query_params($db, "SELECT addBid($1, $2, $3)", array($email, $advertisementID, $price));

    $row[0] = $email;
    $row[1] = $advertisementID;
    $row[2] = $price;

    $result = pg_fetch_array($query);
    $error = $result[0];
	
    if (($error === 'You cannot bid for your own offer!') OR ($error === 'Your email is invalid!') OR ($error === 'You have already submitted a bid for this offer!')) {
        $emailError = $error;
    } elseif (($error === 'Advertisement id does not exist!') OR ($error === 'Sorry, advertisement has already been closed!')) {
        $adIdError = $error;
    } elseif ($error === 'Price should be numeric and greater than 0!') {
        $priceError = $error;
    } elseif ($error == '') {
		header("Location: adminBid.php");
    }
}
?>

<html>
	<body id = 'adminpage'>
        <h2 class="text-center">Create Bid</h2>
		<div id="divForm">
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
				<button type="submit" name="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
    </body>
</html>
