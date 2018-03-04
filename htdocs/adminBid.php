<?php  

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

//echo 'DB is connected';

$result = pg_query($db, 'SELECT * FROM bid'); 

/*
if(!is_null( $result )) {
	echo 'Retrieved bids from db stored as result';
} else {
	echo 'No bids are being retrieved!';
}
*/

?>

<html>

<h1><b>View Existing Bidding Information</b></h1>

<table>

<?php 

echo "<tr>
		<th>Email</th>
		<th>AdvertisementID</th>
		<th>Status</th>
		<th>Price</th>
		<th>Date and Time created</th>
	</tr>";

while($row = pg_fetch_array( $result )) { 
	echo "<tr>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[4] . "</td>";
		echo "<td><a href='adminDeleteBid.php?id=".$row[1]."&mail=".$row[0]."'>Delete</a></td>";
		echo "<td><a href='adminEditBid.php?id=".$row[1]."&mail=".$row[0]."'>Edit</a></td>";
	echo "</tr>";
}

?>
	
</table>

<p>Add a new bid</p>

<p><a href="adminCreateBid.php">New bid</a></p>

</body>

</html>