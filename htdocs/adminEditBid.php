<?php

session_start();

//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];
if($isAdmin == 'f') {
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
}

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

$advertisementID = $_GET['id'];
$email = $_GET['mail'];
$result = pg_query_params($db, 'SELECT * FROM bid WHERE advertisementid = $1 AND email = $2', array($advertisementID, $email));
$row = pg_fetch_array($result);

//Obtain timezone
date_default_timezone_set("Singapore");

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
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>		
	</head>
	<body>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
				  <a class="navbar-brand" href="adminPage.php">Car Pool</a>
				</div>
				<div>
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="adminBid.php">Bids
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="adminBid.php">View all</a></li>
								<li><a href="adminCreateBid.php">Create bid</a></li>
							</ul>
						</li>
						<li><a href="adminOffer.php">Advertisements</a></li>
						<li><a href="adminUser.php">Users</a></li>
					</ul>
				</div>
				<div>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
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
