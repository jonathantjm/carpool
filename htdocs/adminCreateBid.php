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

//Obtain timezone
date_default_timezone_set("Singapore");

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
