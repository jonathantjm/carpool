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
$counter = 1;
?>


<html>
	<body>
		<h1 class="text-center">Existing Bidding Information</h1>
		<table class="table table-bordered">
<?php 
echo "<tr>
		<th>S/N</th>
		<th>Email</th>
		<th>AdvertisementID</th>
		<th>Status</th>
		<th>Price(SGD)</th>
		<th>Date and Time created</th>
	</tr>";
while($row = pg_fetch_array( $result )) { 
	echo "<tr>";
		echo "<td>" . $counter . "</td>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[4] . "</td>";
		echo "<td class='table-fit'><a href='adminDeleteBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Delete</a></td>";
		echo "<td class='table-fit'><a href='adminEditBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Edit</a></td>";
	echo "</tr>";
	$counter++;
}
?>
		</table>
		<a href="adminCreateBid.php" class="btn btn-primary" role="button">Create new bid?</a>
		<ul class="pager">
			<li class="previous"><a href="adminPage.php">Back</a></li>
		</ul>
	</body>
</html>