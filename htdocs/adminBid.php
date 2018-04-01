<?php  
include("header.php");
//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];
if($isAdmin == 'f') {
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		
	</script>";
} elseif ($isAdmin == null) {
	$message = "Please login to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
}
include("adminNavBar.php");

$result = pg_query($db, 'SELECT * FROM bid ORDER BY advertisementid'); 
$counter = 1;

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
?>


<html>
	<body>
		<h1 class="text-center">Existing Bidding Information</h1>
		<table id="table" class="table table-striped table-bordered" style="width:100%">
<?php 
echo "<thead>
		<tr>
			<th>S/N</th>
			<th>Email</th>
			<th>AdvertisementID</th>
			<th>Status</th>
			<th>Price(SGD)</th>
			<th>Date and Time created</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
	</thead>";
echo "<tbody>";
while($row = pg_fetch_array( $result )) { 
	echo "<tr>";
		echo "<td>" . $counter . "</td>";		
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[4] . "</td>";
		echo "<td><a href='adminEditBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Edit</a></td>";
		echo "<td><a href='adminDeleteBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Delete</a></td>";
	echo "</tr>";
	$counter++;
}
echo "</tbody>";
?>
		</table>
		<a href="adminCreateBid.php" class="btn btn-primary" role="button">Create new bid?</a>
		<ul class="pager">
			<li class="previous"><a href="adminPage.php">Back</a></li>
		</ul>
	</body>
</html>