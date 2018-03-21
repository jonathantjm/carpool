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

//echo 'DB is connected';

$result = pg_query($db, 'SELECT * FROM bid'); 
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
		<h1><b>Existing Bidding Information</b></h1>
		<table class="table table-bordered">
<?php 
echo "<tr>
		<th>Email</th>
		<th>AdvertisementID</th>
		<th>Status</th>
		<th>Price(SGD)</th>
		<th>Date and Time created</th>
	</tr>";
while($row = pg_fetch_array( $result )) { 
	echo "<tr>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[4] . "</td>";
		echo "<td><a href='adminDeleteBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "'>Delete</a></td>";
		echo "<td><a href='adminEditBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "'>Edit</a></td>";
	echo "</tr>";
}
?>
		</table>
		<p><a href="adminCreateBid.php">Add a new bid?</a></p>
	</body>
</html>