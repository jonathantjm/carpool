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

$result = pg_query($db, 'SELECT * FROM bid'); 
?>

<html>
	<body>
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